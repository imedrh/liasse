<?php
// php/api/entreprises.php - Point d'accès API pour la gestion des entreprises

// Inclut les fichiers nécessaires
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php'; // Pour vérifier l'authentification
require_once __DIR__ . '/../controllers/EntrepriseController.php'; // Le contrôleur des entreprises

// Assurez-vous que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Accès non autorisé. Veuillez vous connecter."]);
    exit();
}

$user_id = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté
$entrepriseController = new EntrepriseController($mysqli); // Instancie le contrôleur

header('Content-Type: application/json'); // Définir l'en-tête pour la réponse JSON

$method = $_SERVER['REQUEST_METHOD']; // Récupère la méthode HTTP (GET, POST, PUT, DELETE)

switch ($method) {
    case 'GET':
        // Gère la récupération d'une entreprise spécifique ou de toutes les entreprises
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $entreprise = $entrepriseController->getEntrepriseById($id, $user_id);
            if ($entreprise) {
                echo json_encode(["success" => true, "data" => $entreprise]);
            } else {
                echo json_encode(["success" => false, "message" => "Entreprise non trouvée ou accès non autorisé."]);
            }
        } else {
            $entreprises = $entrepriseController->getEntreprises($user_id);
            echo json_encode(["success" => true, "data" => $entreprises]);
        }
        break;

    case 'POST':
        // Gère l'ajout d'une nouvelle entreprise
        $data = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["success" => false, "message" => "Format de données invalide (JSON attendu pour POST). Erreur: " . json_last_error_msg()]);
            break;
        }

        $result = $entrepriseController->addEntreprise($data, $user_id);
        echo json_encode($result);
        break;

    case 'PUT':
        // Gère la mise à jour d'une entreprise existante
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["success" => false, "message" => "Format de données invalide (JSON attendu pour PUT). Erreur: " . json_last_error_msg()]);
            break;
        }

        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(["success" => false, "message" => "ID de l'entreprise manquant pour la mise à jour."]);
            break;
        }
        unset($data['id']); // L'ID est déjà géré séparément

        $result = $entrepriseController->updateEntreprise($id, $data, $user_id);
        echo json_encode($result);
        break;

    case 'DELETE':
        // Gère la suppression d'une entreprise
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // Priorité à l'ID dans l'URL
        if (!$id) {
            // Si pas dans l'URL, tente de lire l'ID depuis le corps de la requête DELETE
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        }

        if (!$id) {
            echo json_encode(["success" => false, "message" => "ID de l'entreprise manquant pour la suppression."]);
            break;
        }

        $result = $entrepriseController->deleteEntreprise($id, $user_id);
        echo json_encode($result);
        break;

    default:
        // Méthode non supportée
        header('HTTP/1.0 405 Method Not Allowed');
        echo json_encode(["success" => false, "message" => "Méthode HTTP non supportée."]);
        break;
}

// Ferme la connexion à la base de données après chaque requête API
$mysqli->close();
?>
