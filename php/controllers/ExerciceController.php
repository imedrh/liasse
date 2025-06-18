<?php
// php/controllers/ExerciceController.php - Contrôleur pour la gestion des exercices

// Inclut le fichier de configuration de la base de données
require_once __DIR__ . '/../config.php';

class ExerciceController {
    private $mysqli; // Instance de la connexion à la base de données

    // Constructeur : initialise la connexion à la base de données
    public function __construct($mysqli_conn) {
        $this->mysqli = $mysqli_conn;
    }

    /**
     * Valide les données d'un exercice.
     *
     * @param array $data Tableau associatif des données de l'exercice.
     * @return array Résultat de la validation (succès ou échec avec message).
     */
    private function validateExerciceData($data) {
        // Validation des champs obligatoires
        $required_fields = ['entreprise_id', 'annee', 'date_debut', 'date_fin', 'statut'];
        foreach ($required_fields as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                return ["success" => false, "message" => "Le champ '" . ucfirst(str_replace('_', ' ', $field)) . "' est obligatoire."];
            }
        }

        // Validation de l'année
        $annee = intval($data['annee']);
        if ($annee < 1900 || $annee > 2100) { // Plage d'années raisonnable
            return ["success" => false, "message" => "L'année doit être une valeur valide entre 1900 et 2100."];
        }

        // Validation des dates
        if (!strtotime($data['date_debut'])) {
            return ["success" => false, "message" => "La date de début est invalide."];
        }
        if (!strtotime($data['date_fin'])) {
            return ["success" => false, "message" => "La date de fin est invalide."];
        }
        if (strtotime($data['date_debut']) >= strtotime($data['date_fin'])) {
            return ["success" => false, "message" => "La date de fin doit être postérieure à la date de début."];
        }

        // Validation du statut
        $allowed_statuts = ['Ouvert', 'Fermé', 'Archivé'];
        if (!in_array($data['statut'], $allowed_statuts)) {
            return ["success" => false, "message" => "Statut invalide. Les statuts autorisés sont 'Ouvert', 'Fermé', 'Archivé'."];
        }

        return ["success" => true];
    }

    /**
     * Ajoute un nouvel exercice à la base de données et l'associe à une entreprise et un utilisateur.
     *
     * @param array $data Tableau associatif contenant les données de l'exercice.
     * Ex: ['entreprise_id' => 1, 'annee' => 2024, 'date_debut' => '2024-01-01', ...]
     * @param int $user_id L'ID de l'utilisateur qui crée l'exercice.
     * @return array Résultat de l'opération (succès ou échec avec message).
     */
    public function addExercice($data, $user_id) {
        // Valide les données de l'exercice
        $validation = $this->validateExerciceData($data);
        if (!$validation['success']) {
            return $validation;
        }

        // Vérifie si l'entreprise_id appartient bien à l'utilisateur
        $entrepriseController = new EntrepriseController($this->mysqli); // Nécessite l'instance du contrôleur entreprise
        if (!$entrepriseController->getEntrepriseById($data['entreprise_id'], $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé à l'entreprise sélectionnée ou entreprise non trouvée."];
        }

        // Vérifie l'unicité de l'exercice pour l'entreprise et l'année
        $stmt_check = $this->mysqli->prepare("SELECT id FROM exercices WHERE entreprise_id = ? AND annee = ?");
        if ($stmt_check === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la vérification d'unicité de l'exercice."];
        }
        $stmt_check->bind_param("ii", $data['entreprise_id'], $data['annee']);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            return ["success" => false, "message" => "Un exercice pour cette entreprise et cette année existe déjà."];
        }
        $stmt_check->close();

        // Prépare la requête d'insertion
        $query = "INSERT INTO exercices (entreprise_id, user_id, annee, date_debut, date_fin, statut) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de l'ajout de l'exercice."];
        }

        $stmt->bind_param(
            "iissss",
            $data['entreprise_id'],
            $user_id,
            $data['annee'],
            $data['date_debut'],
            $data['date_fin'],
            $data['statut']
        );

        if ($stmt->execute()) {
            $exercice_id = $this->mysqli->insert_id;
            $stmt->close();
            return ["success" => true, "message" => "Exercice ajouté avec succès.", "id" => $exercice_id];
        } else {
            $stmt->close();
            return ["success" => false, "message" => "Erreur lors de l'ajout de l'exercice: " . $stmt->error];
        }
    }

    /**
     * Récupère la liste des exercices pour un utilisateur, avec les informations de l'entreprise.
     * Peut filtrer par entreprise_id si spécifié.
     *
     * @param int $user_id L'ID de l'utilisateur.
     * @param int|null $entreprise_id Optionnel : ID de l'entreprise pour filtrer.
     * @return array Tableau des exercices.
     */
    public function getExercices($user_id, $entreprise_id = null) {
        $exercices = [];
        $query = "SELECT e.*, ent.raison_sociale FROM exercices e JOIN entreprises ent ON e.entreprise_id = ent.id WHERE e.user_id = ?";
        $params = [$user_id];
        $types = "i";

        if ($entreprise_id !== null) {
            $query .= " AND e.entreprise_id = ?";
            $params[] = $entreprise_id;
            $types .= "i";
        }
        $query .= " ORDER BY e.annee DESC, ent.raison_sociale ASC";

        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return [];
        }

        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $exercices[] = $row;
            }
        }
        $stmt->close();
        return $exercices;
    }

    /**
     * Récupère un exercice spécifique par son ID et l'ID de l'utilisateur pour la sécurité.
     *
     * @param int $id L'ID de l'exercice.
     * @param int $user_id L'ID de l'utilisateur qui tente d'accéder à l'exercice.
     * @return array|null Tableau associatif de l'exercice ou null si non trouvé/non autorisé.
     */
    public function getExerciceById($id, $user_id) {
        $query = "SELECT e.*, ent.raison_sociale FROM exercices e JOIN entreprises ent ON e.entreprise_id = ent.id WHERE e.id = ? AND e.user_id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return null;
        }

        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $exercice = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $exercice;
    }

    /**
     * Met à jour un exercice existant.
     *
     * @param int $id L'ID de l'exercice à mettre à jour.
     * @param array $data Tableau associatif contenant les nouvelles données.
     * @param int $user_id L'ID de l'utilisateur qui modifie l'exercice (pour la sécurité).
     * @return array Résultat de l'opération.
     */
    public function updateExercice($id, $data, $user_id) {
        // Valide les données de l'exercice
        $validation = $this->validateExerciceData($data);
        if (!$validation['success']) {
            return $validation;
        }

        // Vérifie que l'exercice appartient bien à l'utilisateur et qu'il existe
        if (!$this->getExerciceById($id, $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé ou exercice non trouvé."];
        }

        // Vérifie si l'entreprise_id appartient bien à l'utilisateur (si elle a été changée)
        $entrepriseController = new EntrepriseController($this->mysqli);
        if (!$entrepriseController->getEntrepriseById($data['entreprise_id'], $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé à la nouvelle entreprise sélectionnée ou entreprise non trouvée."];
        }

        // Vérifie l'unicité de l'exercice pour l'entreprise et l'année (excluant l'exercice actuel)
        $stmt_check = $this->mysqli->prepare("SELECT id FROM exercices WHERE entreprise_id = ? AND annee = ? AND id != ?");
        if ($stmt_check === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la vérification d'unicité de l'exercice."];
        }
        $stmt_check->bind_param("iii", $data['entreprise_id'], $data['annee'], $id);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            return ["success" => false, "message" => "Un exercice pour cette entreprise et cette année existe déjà (autre exercice)."];
        }
        $stmt_check->close();

        $query = "UPDATE exercices SET entreprise_id = ?, annee = ?, date_debut = ?, date_fin = ?, statut = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la mise à jour de l'exercice."];
        }

        $stmt->bind_param(
            "iissisi",
            $data['entreprise_id'],
            $data['annee'],
            $data['date_debut'],
            $data['date_fin'],
            $data['statut'],
            $id,
            $user_id // Sécurité supplémentaire : s'assurer que l'utilisateur est bien le propriétaire de l'exercice à modifier
        );

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return ["success" => true, "message" => "Exercice mis à jour avec succès."];
            } else {
                $stmt->close();
                return ["success" => false, "message" => "Aucune modification apportée ou exercice non trouvé."];
            }
        } else {
            return ["success" => false, "message" => "Erreur lors de la mise à jour de l'exercice : " . $stmt->error];
        }
    }

    /**
     * Supprime un exercice.
     *
     * @param int $id L'ID de l'exercice à supprimer.
     * @param int $user_id L'ID de l'utilisateur qui supprime l'exercice (pour la sécurité).
     * @return array Résultat de l'opération.
     */
    public function deleteExercice($id, $user_id) {
        // Vérifier que l'exercice appartient bien à l'utilisateur avant de supprimer
        if (!$this->getExerciceById($id, $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé ou exercice non trouvé."];
        }

        $query = "DELETE FROM exercices WHERE id = ? AND user_id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la suppression de l'exercice."];
        }

        $stmt->bind_param("ii", $id, $user_id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return ["success" => true, "message" => "Exercice supprimé avec succès."];
            } else {
                $stmt->close();
                return ["success" => false, "message" => "Exercice non trouvé ou déjà supprimé."];
            }
        } else {
            return ["success" => false, "message" => "Erreur lors de la suppression de l'exercice : " . $stmt->error];
        }
    }
}
?>
