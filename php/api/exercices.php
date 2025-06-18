<?php
// php/api/exercices.php - Point d'accès API pour la gestion des exercices

// Inclut les fichiers nécessaires
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php'; // Pour vérifier l'authentification
require_once __DIR__ . '/../controllers/ExerciceController.php'; // Le contrôleur des exercices
require_once __DIR__ . '/../controllers/EntrepriseController.php'; // Le contrôleur des entreprises (nécessaire pour la validation d'entreprise_id)


// Assurez-vous que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Accès non autorisé. Veuillez vous connecter."]);
    exit();
}

$user_id = $_SESSION['user_id']; // Récupère l'ID de l'utilisateur connecté
$exerciceController = new ExerciceController($mysqli); // Instancie le contrôleur des exercices

header('Content-Type: application/json'); // Définir l'en-tête pour la réponse JSON

$method = $_SERVER['REQUEST_METHOD']; // Récupère la méthode HTTP (GET, POST, PUT, DELETE)

switch ($method) {
    case 'GET':
        // Gère la récupération d'un exercice spécifique ou de tous les exercices
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $entreprise_id = filter_input(INPUT_GET, 'entreprise_id', FILTER_VALIDATE_INT);

        if ($id) {
            $exercice = $exerciceController->getExerciceById($id, $user_id);
            if ($exercice) {
                echo json_encode(["success" => true, "data" => $exercice]);
            } else {
                echo json_encode(["success" => false, "message" => "Exercice non trouvé ou accès non autorisé."]);
            }
        } else {
            // Si entreprise_id est fourni, filtre les exercices pour cette entreprise
            $exercices = $exerciceController->getExercices($user_id, $entreprise_id);
            echo json_encode(["success" => true, "data" => $exercices]);
        }
        break;

    case 'POST':
        // Gère l'ajout d'un nouvel exercice
        $data = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["success" => false, "message" => "Format de données invalide (JSON attendu pour POST). Erreur: " . json_last_error_msg()]);
            break;
        }

        $result = $exerciceController->addExercice($data, $user_id);
        echo json_encode($result);
        break;

    case 'PUT':
        // Gère la mise à jour d'un exercice existant
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["success" => false, "message" => "Format de données invalide (JSON attendu pour PUT). Erreur: " . json_last_error_msg()]);
            break;
        }

        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(["success" => false, "message" => "ID de l'exercice manquant pour la mise à jour."]);
            break;
        }
        unset($data['id']); // L'ID est déjà géré séparément

        $result = $exerciceController->updateExercice($id, $data, $user_id);
        echo json_encode($result);
        break;

    case 'DELETE':
        // Gère la suppression d'un exercice
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT); // Priorité à l'ID dans l'URL
        if (!$id) {
            // Si pas dans l'URL, tente de lire l'ID depuis le corps de la requête DELETE
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        }

        if (!$id) {
            echo json_encode(["success" => false, "message" => "ID de l'exercice manquant pour la suppression."]);
            break;
        }

        $result = $exerciceController->deleteExercice($id, $user_id);
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
