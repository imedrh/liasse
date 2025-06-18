<?php
// php/controllers/DeclarationController.php - Contrôleur pour la gestion des déclarations

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/EntrepriseController.php'; // Nécessaire pour valider entreprise_id
require_once __DIR__ . '/ExerciceController.php';   // Nécessaire pour valider exercice_id

class DeclarationController {
    private $mysqli;

    public function __construct($mysqli_conn) {
        $this->mysqli = $mysqli_conn;
    }

    /**
     * Valide les données d'une déclaration.
     *
     * @param array $data Tableau associatif des données de la déclaration.
     * @return array Résultat de la validation (succès ou échec avec message).
     */
    private function validateDeclarationData($data) {
        // Suppression de 'statut' des champs obligatoires, 'montant_declare' et 'notes' ne sont plus dans la table
        $required_fields = ['entreprise_id', 'exercice_id', 'type_depot', 'nature_depot', 'date_declaration'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                return ["success" => false, "message" => "Le champ '" . ucfirst(str_replace('_', ' ', $field)) . "' est obligatoire."];
            }
        }

        // Validation des IDs numériques
        if (!is_numeric($data['entreprise_id']) || $data['entreprise_id'] <= 0) {
            return ["success" => false, "message" => "ID d'entreprise invalide."];
        }
        if (!is_numeric($data['exercice_id']) || $data['exercice_id'] <= 0) {
            return ["success" => false, "message" => "ID d'exercice invalide."];
        }

        // Validation du type de dépôt
        $allowed_type_depot = ['D', 'P'];
        if (!in_array($data['type_depot'], $allowed_type_depot)) {
            return ["success" => false, "message" => "Type de dépôt invalide. Les options autorisées sont 'D' (Définition) et 'P' (Provisoire)."];
        }

        // Validation de la nature de dépôt
        $allowed_nature_depot = ['0', '1', '2'];
        if (!in_array($data['nature_depot'], $allowed_nature_depot)) {
            return ["success" => false, "message" => "Nature de dépôt invalide. Les options autorisées sont '0' (Spontané), '1' (Rectification), '2' (Régularisation)."];
        }

        // Validation de la date de déclaration
        if (!strtotime($data['date_declaration'])) {
            return ["success" => false, "message" => "La date de déclaration est invalide."];
        }

        // 'montant_declare' et 'statut' et 'notes' ne sont plus validés ici car supprimés de la table
        
        return ["success" => true];
    }

    /**
     * Génère un numéro de dépôt unique pour une année donnée.
     * Format: DECL-ANNEE-NN (NN étant un numéro séquentiel à 2 chiffres)
     *
     * @param int $exercice_id L'ID de l'exercice auquel la déclaration est liée.
     * @return string|false Le numéro de dépôt généré ou false en cas d'erreur.
     */
    private function generateNumeroDepot($exercice_id) {
        // Récupérer l'année de l'exercice
        $stmt_annee = $this->mysqli->prepare("SELECT annee FROM exercices WHERE id = ?");
        if ($stmt_annee === false) {
            error_log("Failed to prepare statement for fetching exercice year: " . $this->mysqli->error);
            return false;
        }
        $stmt_annee->bind_param("i", $exercice_id);
        $stmt_annee->execute();
        $result_annee = $stmt_annee->get_result();
        $annee_data = $result_annee->fetch_assoc();
        $stmt_annee->close();

        if (!$annee_data) {
            error_log("Exercice with ID {$exercice_id} not found for numero_depot generation.");
            return false;
        }
        $annee = $annee_data['annee'];

        // Compter le nombre de déclarations existantes pour cette année
        $stmt_count = $this->mysqli->prepare("SELECT COUNT(*) FROM declarations d JOIN exercices e ON d.exercice_id = e.id WHERE e.annee = ?");
        if ($stmt_count === false) {
            error_log("Failed to prepare statement for counting declarations: " . $this->mysqli->error);
            return false;
        }
        $stmt_count->bind_param("i", $annee);
        $stmt_count->execute();
        $stmt_count->bind_result($count);
        $stmt_count->fetch();
        $stmt_count->close();

        $numero = str_pad($count + 1, 2, '0', STR_PAD_LEFT);
        return "DECL-{$annee}-{$numero}";
    }


    /**
     * Ajoute une nouvelle déclaration.
     *
     * @param array $data Données de la déclaration.
     * @param int $user_id ID de l'utilisateur.
     * @return array Résultat de l'opération.
     */
    public function addDeclaration($data, $user_id) {
        $validation = $this->validateDeclarationData($data);
        if (!$validation['success']) {
            return $validation;
        }

        // Vérifier que l'entreprise et l'exercice appartiennent à l'utilisateur
        $entrepriseController = new EntrepriseController($this->mysqli);
        if (!$entrepriseController->getEntrepriseById($data['entreprise_id'], $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé à l'entreprise sélectionnée ou entreprise non trouvée."];
        }

        $exerciceController = new ExerciceController($this->mysqli);
        if (!$exerciceController->getExerciceById($data['exercice_id'], $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé à l'exercice sélectionné ou exercice non trouvé."];
        }

        // Générer le numero_depot
        $numero_depot = $this->generateNumeroDepot($data['exercice_id']);
        if ($numero_depot === false) {
            return ["success" => false, "message" => "Erreur lors de la génération du numéro de dépôt."];
        }

        // Mise à jour de la requête SQL sans montant_declare, statut, notes
        $query = "INSERT INTO declarations (entreprise_id, exercice_id, user_id, type_depot, nature_depot, numero_depot, date_declaration) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            error_log("Failed to prepare statement for adding declaration: " . $this->mysqli->error);
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de l'ajout de la déclaration."];
        }

        // Mise à jour des types de paramètres pour bind_param
        $stmt->bind_param(
            "iiissss", // i = integer, s = string
            $data['entreprise_id'],
            $data['exercice_id'],
            $user_id,
            $data['type_depot'],
            $data['nature_depot'],
            $numero_depot, // Le numéro de dépôt généré
            $data['date_declaration']
        );

        if ($stmt->execute()) {
            $declaration_id = $this->mysqli->insert_id;
            $stmt->close();
            return ["success" => true, "message" => "Déclaration ajoutée avec succès.", "id" => $declaration_id, "numero_depot" => $numero_depot];
        } else {
            $error_message = "Erreur lors de l'ajout de la déclaration: " . $stmt->error;
            error_log($error_message);
            // Gérer l'erreur d'unicité si numero_depot est dupliqué (très rare si generateNumeroDepot fonctionne)
            if ($this->mysqli->errno == 1062 && strpos($this->mysqli->error, 'numero_depot') !== false) {
                return ["success" => false, "message" => "Un numéro de dépôt en conflit a été détecté. Veuillez réessayer."];
            }
            return ["success" => false, "message" => $error_message];
        }
    }

    /**
     * Récupère la liste des déclarations pour un utilisateur, avec les informations de l'entreprise et de l'exercice.
     * Peut filtrer par entreprise_id ou exercice_id.
     *
     * @param int $user_id L'ID de l'utilisateur.
     * @param int|null $entreprise_id Optionnel: ID de l'entreprise pour filtrer.
     * @param int|null $exercice_id Optionnel: ID de l'exercice pour filtrer.
     * @return array Tableau des déclarations.
     */
    public function getDeclarations($user_id, $entreprise_id = null, $exercice_id = null) {
        $declarations = [];
        // Mise à jour de la requête SELECT sans montant_declare, statut, notes
        $query = "SELECT d.id, d.entreprise_id, d.exercice_id, d.type_depot, d.nature_depot, d.numero_depot, d.date_declaration, ent.raison_sociale, ex.annee FROM declarations d
                  JOIN entreprises ent ON d.entreprise_id = ent.id
                  JOIN exercices ex ON d.exercice_id = ex.id
                  WHERE d.user_id = ?";
        $params = [$user_id];
        $types = "i";

        if ($entreprise_id !== null) {
            $query .= " AND d.entreprise_id = ?";
            $params[] = $entreprise_id;
            $types .= "i";
        }
        if ($exercice_id !== null) {
            $query .= " AND d.exercice_id = ?";
            $params[] = $exercice_id;
            $types .= "i";
        }
        $query .= " ORDER BY d.date_declaration DESC, ent.raison_sociale ASC";

        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            error_log("Failed to prepare statement for getting declarations: " . $this->mysqli->error);
            return [];
        }

        // Utiliser la syntaxe variadic pour bind_param
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $declarations[] = $row;
            }
        }
        $stmt->close();
        return $declarations;
    }

    /**
     * Récupère une déclaration spécifique par son ID et l'ID de l'utilisateur pour la sécurité.
     *
     * @param int $id L'ID de la déclaration.
     * @param int $user_id L'ID de l'utilisateur.
     * @return array|null Tableau associatif de la déclaration ou null si non trouvée/non autorisée.
     */
    public function getDeclarationById($id, $user_id) {
        // Mise à jour de la requête SELECT sans montant_declare, statut, notes
        $query = "SELECT d.id, d.entreprise_id, d.exercice_id, d.type_depot, d.nature_depot, d.numero_depot, d.date_declaration, ent.raison_sociale, ex.annee FROM declarations d
                  JOIN entreprises ent ON d.entreprise_id = ent.id
                  JOIN exercices ex ON d.exercice_id = ex.id
                  WHERE d.id = ? AND d.user_id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            error_log("Failed to prepare statement for getting declaration by ID: " . $this->mysqli->error);
            return null;
        }

        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $declaration = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $declaration;
    }

    /**
     * Met à jour une déclaration existante.
     *
     * @param int $id L'ID de la déclaration à mettre à jour.
     * @param array $data Tableau associatif contenant les nouvelles données.
     * @param int $user_id L'ID de l'utilisateur.
     * @return array Résultat de l'opération.
     */
    public function updateDeclaration($id, $data, $user_id) {
        $validation = $this->validateDeclarationData($data);
        if (!$validation['success']) {
            return $validation;
        }

        // Vérifier que la déclaration appartient bien à l'utilisateur et qu'elle existe
        if (!$this->getDeclarationById($id, $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé ou déclaration non trouvée."];
        }

        // Vérifier que l'entreprise et l'exercice appartiennent à l'utilisateur (si changés)
        $entrepriseController = new EntrepriseController($this->mysqli);
        if (!$entrepriseController->getEntrepriseById($data['entreprise_id'], $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé à l'entreprise sélectionnée ou entreprise non trouvée."];
        }

        $exerciceController = new ExerciceController($this->mysqli);
        if (!$exerciceController->getExerciceById($data['exercice_id'], $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé à l'exercice sélectionné ou exercice non trouvé."];
        }

        // Mise à jour de la requête SQL sans montant_declare, statut, notes. numero_depot n'est pas modifiable.
        $query = "UPDATE declarations SET entreprise_id = ?, exercice_id = ?, type_depot = ?, nature_depot = ?, date_declaration = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            error_log("Failed to prepare statement for updating declaration: " . $this->mysqli->error);
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la mise à jour de la déclaration."];
        }
        
        // Mise à jour des types de paramètres pour bind_param
        $stmt->bind_param(
            "iiissii", // i = integer, s = string
            $data['entreprise_id'],
            $data['exercice_id'],
            $data['type_depot'],
            $data['nature_depot'],
            $data['date_declaration'],
            $id,
            $user_id
        );

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return ["success" => true, "message" => "Déclaration mise à jour avec succès."];
            } else {
                $stmt->close();
                return ["success" => false, "message" => "Aucune modification apportée ou déclaration non trouvée."];
            }
        } else {
            $error_message = "Erreur lors de la mise à jour de la déclaration: " . $stmt->error;
            error_log($error_message);
            // Gérer l'erreur d'unicité si numero_depot est dupliqué (très rare si on ne le modifie pas en update)
            if ($this->mysqli->errno == 1062 && strpos($this->mysqli->error, 'numero_depot') !== false) {
                return ["success" => false, "message" => "Un numéro de dépôt en conflit a été détecté lors de la mise à jour."];
            }
            return ["success" => false, "message" => $error_message];
        }
    }

    /**
     * Supprime une déclaration.
     *
     * @param int $id L'ID de la déclaration à supprimer.
     * @param int $user_id L'ID de l'utilisateur.
     * @return array Résultat de l'opération.
     */
    public function deleteDeclaration($id, $user_id) {
        // Vérifier que la déclaration appartient bien à l'utilisateur avant de supprimer
        if (!$this->getDeclarationById($id, $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé ou déclaration non trouvée."];
        }

        $query = "DELETE FROM declarations WHERE id = ? AND user_id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            error_log("Failed to prepare statement for deleting declaration: " . $this->mysqli->error);
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la suppression de la déclaration."];
        }

        $stmt->bind_param("ii", $id, $user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return ["success" => true, "message" => "Déclaration supprimée avec succès."];
            } else {
                $stmt->close();
                return ["success" => false, "message" => "Déclaration non trouvée ou déjà supprimée."];
            }
        } else {
            $error_message = "Erreur lors de la suppression de la déclaration: " . $stmt->error;
            error_log($error_message);
            return ["success" => false, "message" => $error_message];
        }
    }
}
?>
