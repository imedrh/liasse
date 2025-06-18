<?php
// php/controllers/EntrepriseController.php - Contrôleur pour la gestion des entreprises

// Inclut le fichier de configuration de la base de données
require_once __DIR__ . '/../config.php';

class EntrepriseController {
    private $mysqli; // Instance de la connexion à la base de données

    // Constructeur : initialise la connexion à la base de données
    public function __construct($mysqli_conn) {
        $this->mysqli = $mysqli_conn;
    }

    /**
     * Valide les champs spécifiques du matricule fiscal.
     *
     * @param array $data Données de l'entreprise.
     * @return array Résultat de la validation (succès ou échec avec message).
     */
    private function validateFiscalIdFields($data) {
        // Validation matricule (7 chiffres)
        if (!isset($data['matricule']) || !preg_match('/^\d{7}$/', $data['matricule'])) {
            return ["success" => false, "message" => "Matricule: exactement 7 chiffres requis."];
        }
        // Validation clé (caractères spécifiques)
        // Les options fournies sont 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z' (I et O exclus)
        $allowed_cle = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'J', 'K', 'L', 'M', 'N', 'P', 'Q', 'R', 'S', 'T', 'V', 'W', 'X', 'Y', 'Z'];
        if (!isset($data['cle']) || !in_array($data['cle'], $allowed_cle)) {
             return ["success" => false, "message" => "Clé invalide. Veuillez choisir parmi les options autorisées (A-H, J-N, P-Z)."];
        }

        // Validation TVA (caractères spécifiques)
        $allowed_tva = ['A', 'B', 'P', 'D', 'N'];
        if (!isset($data['tva']) || !in_array($data['tva'], $allowed_tva)) {
            return ["success" => false, "message" => "TVA invalide. Veuillez choisir parmi les options autorisées (A, B, P, D, N)."];
        }
        // Validation Catégorie (caractères spécifiques)
        $allowed_categorie = ['M', 'C', 'P', 'N'];
        if (!isset($data['categorie']) || !in_array($data['categorie'], $allowed_categorie)) {
            return ["success" => false, "message" => "Catégorie invalide. Veuillez choisir parmi les options autorisées (M, C, P, N)."];
        }
        // Validation Série (3 chiffres)
        if (!isset($data['serie']) || !preg_match('/^\d{3}$/', $data['serie'])) {
            return ["success" => false, "message" => "Série invalide: 3 chiffres requis."];
        }

        return ["success" => true];
    }

    /**
     * Ajoute une nouvelle entreprise à la base de données et l'associe à l'utilisateur.
     *
     * @param array $data Tableau associatif contenant les données de l'entreprise.
     * @param int $user_id L'ID de l'utilisateur qui crée l'entreprise.
     * @return array Résultat de l'opération (succès ou échec avec message).
     */
    public function addEntreprise($data, $user_id) {
        // Validation des champs obligatoires généraux
        $required_fields = ['raison_sociale', 'matricule', 'cle', 'categorie', 'tva', 'serie'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return ["success" => false, "message" => "Le champ '" . ucfirst(str_replace('_', ' ', $field)) . "' est obligatoire."];
            }
        }

        // Validation des champs du matricule fiscal avec la nouvelle fonction
        $fiscal_id_validation = $this->validateFiscalIdFields($data);
        if (!$fiscal_id_validation['success']) {
            return $fiscal_id_validation; // Renvoie le message d'erreur spécifique
        }

        // Vérification de l'unicité du matricule fiscal complet
        $stmt_check = $this->mysqli->prepare("SELECT id FROM entreprises WHERE matricule = ? AND cle = ? AND categorie = ? AND tva = ? AND serie = ?");
        if ($stmt_check === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la vérification d'unicité."];
        }
        $stmt_check->bind_param("sssss", $data['matricule'], $data['cle'], $data['categorie'], $data['tva'], $data['serie']);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            return ["success" => false, "message" => "Une entreprise avec ce matricule fiscal complet existe déjà."];
        }
        $stmt_check->close();

        $raison_sociale = $data['raison_sociale'] ?? $data['raison'] ?? null; // Pour compatibilité si le frontend envoie 'raison'
        $activite = $data['activite'] ?? null;
        $adresse = $data['adresse'] ?? null;

        $query = "INSERT INTO entreprises (raison_sociale, activite, adresse, matricule, cle, categorie, tva, serie) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de l'ajout de l'entreprise."];
        }

        $stmt->bind_param(
            "ssssssss",
            $raison_sociale,
            $activite,
            $adresse,
            $data['matricule'],
            $data['cle'],
            $data['categorie'],
            $data['tva'],
            $data['serie']
        );

        if ($stmt->execute()) {
            $entreprise_id = $this->mysqli->insert_id;
            $stmt->close();

            $query_user_entreprise = "INSERT INTO user_entreprises (user_id, entreprise_id) VALUES (?, ?)";
            $stmt_user_entreprise = $this->mysqli->prepare($query_user_entreprise);
            if ($stmt_user_entreprise === false) {
                return ["success" => false, "message" => "L'entreprise a été ajoutée, mais l'association à l'utilisateur a échoué. Veuillez contacter l'administrateur."];
            }
            $stmt_user_entreprise->bind_param("ii", $user_id, $entreprise_id);
            if ($stmt_user_entreprise->execute()) {
                $stmt_user_entreprise->close();
                return ["success" => true, "message" => "Entreprise ajoutée et associée à l'utilisateur avec succès.", "id" => $entreprise_id];
            } else {
                return ["success" => false, "message" => "Erreur lors de l'association de l'entreprise à l'utilisateur."];
            }
        } else {
            return ["success" => false, "message" => "Erreur lors de l'ajout de l'entreprise: " . $stmt->error];
        }
    }

    /**
     * Récupère la liste des entreprises associées à un utilisateur.
     *
     * @param int $user_id L'ID de l'utilisateur.
     * @return array Tableau des entreprises ou tableau vide en cas d'erreur/aucun résultat.
     */
    public function getEntreprises($user_id) {
        $entreprises = [];
        $query = "SELECT e.* FROM entreprises e JOIN user_entreprises ue ON e.id = ue.entreprise_id WHERE ue.user_id = ? ORDER BY e.raison_sociale";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return [];
        }

        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $entreprises[] = $row;
            }
        }
        $stmt->close();
        return $entreprises;
    }

    /**
     * Récupère une entreprise spécifique par son ID et l'ID de l'utilisateur pour la sécurité.
     *
     * @param int $id L'ID de l'entreprise.
     * @param int $user_id L'ID de l'utilisateur qui tente d'accéder à l'entreprise.
     * @return array|null Tableau associatif de l'entreprise ou null si non trouvée/non autorisée.
     */
    public function getEntrepriseById($id, $user_id) {
        $query = "SELECT e.* FROM entreprises e JOIN user_entreprises ue ON e.id = ue.entreprise_id WHERE e.id = ? AND ue.user_id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return null;
        }

        $stmt->bind_param("ii", $id, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $entreprise = $result ? $result->fetch_assoc() : null;
        $stmt->close();
        return $entreprise;
    }

    /**
     * Met à jour une entreprise existante.
     *
     * @param int $id L'ID de l'entreprise à mettre à jour.
     * @param array $data Tableau associatif contenant les nouvelles données.
     * @param int $user_id L'ID de l'utilisateur qui modifie l'entreprise (pour la sécurité).
     * @return array Résultat de l'opération.
     */
    public function updateEntreprise($id, $data, $user_id) {
        // Validation des champs obligatoires généraux
        $required_fields = ['raison_sociale', 'matricule', 'cle', 'categorie', 'tva', 'serie'];
        foreach ($required_fields as $field) {
            if (empty($data[$field])) {
                return ["success" => false, "message" => "Le champ '" . ucfirst(str_replace('_', ' ', $field)) . "' est obligatoire."];
            }
        }

        // Validation des champs du matricule fiscal avec la nouvelle fonction
        $fiscal_id_validation = $this->validateFiscalIdFields($data);
        if (!$fiscal_id_validation['success']) {
            return $fiscal_id_validation; // Renvoie le message d'erreur spécifique
        }

        // Vérification de l'unicité du matricule fiscal complet pour les autres entreprises
        $stmt_check = $this->mysqli->prepare("SELECT id FROM entreprises WHERE matricule = ? AND cle = ? AND categorie = ? AND tva = ? AND serie = ? AND id != ?");
        if ($stmt_check === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la vérification d'unicité."];
        }
        $stmt_check->bind_param("sssssi", $data['matricule'], $data['cle'], $data['categorie'], $data['tva'], $data['serie'], $id);
        $stmt_check->execute();
        $stmt_check->store_result();
        if ($stmt_check->num_rows > 0) {
            $stmt_check->close();
            return ["success" => false, "message" => "Une autre entreprise avec ce matricule fiscal complet existe déjà."];
        }
        $stmt_check->close();


        // Vérifier que l'entreprise appartient bien à l'utilisateur avant de modifier
        if (!$this->getEntrepriseById($id, $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé ou entreprise non trouvée."];
        }

        $raison_sociale = $data['raison_sociale'] ?? $data['raison'] ?? null; // Pour compatibilité si le frontend envoie 'raison'
        $activite = $data['activite'] ?? null;
        $adresse = $data['adresse'] ?? null;

        $query = "UPDATE entreprises SET raison_sociale = ?, activite = ?, adresse = ?, matricule = ?, cle = ?, categorie = ?, tva = ?, serie = ? WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la mise à jour de l'entreprise."];
        }

        $stmt->bind_param(
            "ssssssssi",
            $raison_sociale,
            $activite,
            $adresse,
            $data['matricule'],
            $data['cle'],
            $data['categorie'],
            $data['tva'],
            $data['serie'],
            $id
        );

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return ["success" => true, "message" => "Entreprise mise à jour avec succès."];
            } else {
                $stmt->close();
                return ["success" => false, "message" => "Aucune modification apportée ou entreprise non trouvée."];
            }
        } else {
            return ["success" => false, "message" => "Erreur lors de la mise à jour de l'entreprise : " . $stmt->error];
        }
    }

    /**
     * Supprime une entreprise.
     *
     * @param int $id L'ID de l'entreprise à supprimer.
     * @param int $user_id L'ID de l'utilisateur qui supprime l'entreprise (pour la sécurité).
     * @return array Résultat de l'opération.
     */
    public function deleteEntreprise($id, $user_id) {
        // Vérifier que l'entreprise appartient bien à l'utilisateur avant de supprimer
        if (!$this->getEntrepriseById($id, $user_id)) {
            return ["success" => false, "message" => "Accès non autorisé ou entreprise non trouvée."];
        }

        $query = "DELETE FROM entreprises WHERE id = ?";
        $stmt = $this->mysqli->prepare($query);

        if ($stmt === false) {
            return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la suppression de l'entreprise."];
        }

        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                $stmt->close();
                return ["success" => true, "message" => "Entreprise supprimée avec succès."];
            } else {
                $stmt->close();
                return ["success" => false, "message" => "Entreprise non trouvée ou déjà supprimée."];
            }
        } else {
            return ["success" => false, "message" => "Erreur lors de la suppression de l'entreprise : " . $stmt->error];
        }
    }
}
?>
