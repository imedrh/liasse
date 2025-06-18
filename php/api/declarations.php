<?php
// php/api/declarations.php - Point d'accès API pour la gestion des déclarations

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php'; // Pour vérifier l'authentification
require_once __DIR__ . '/../controllers/DeclarationController.php';
require_once __DIR__ . '/../controllers/EntrepriseController.php';
require_once __DIR__ . '/../controllers/ExerciceController.php';


// Assurez-vous que l'utilisateur est connecté
if (!isLoggedIn()) {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Accès non autorisé. Veuillez vous connecter."]);
    exit();
}

$user_id = $_SESSION['user_id'];
// Remarque sur le rôle: le code fourni avait un check `$_SESSION['user']['role'] !== 'admin'`.
// Notre système d'authentification utilise `$_SESSION['role']`.
// Si vous voulez restreindre l'accès à cette API aux admins, décommentez la ligne ci-dessous :
/*
if ($_SESSION['role'] !== 'admin') {
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Accès réservé aux administrateurs."]);
    exit();
}
*/

$declarationController = new DeclarationController($mysqli);

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $entreprise_id = filter_input(INPUT_GET, 'entreprise_id', FILTER_VALIDATE_INT);
        $exercice_id = filter_input(INPUT_GET, 'exercice_id', FILTER_VALIDATE_INT);

        if ($id) {
            $declaration = $declarationController->getDeclarationById($id, $user_id);
            if ($declaration) {
                echo json_encode(["success" => true, "data" => $declaration]);
            } else {
                echo json_encode(["success" => false, "message" => "Déclaration non trouvée ou accès non autorisé."]);
            }
        } else {
            $declarations = $declarationController->getDeclarations($user_id, $entreprise_id, $exercice_id);
            echo json_encode(["success" => true, "data" => $declarations]);
        }
        break;

    case 'POST':
        $data = json_decode(file_get_contents("php://input"), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["success" => false, "message" => "Format de données invalide (JSON attendu pour POST). Erreur: " . json_last_error_msg()]);
            break;
        }

        $result = $declarationController->addDeclaration($data, $user_id);
        echo json_encode($result);
        break;

    case 'PUT':
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            echo json_encode(["success" => false, "message" => "Format de données invalide (JSON attendu pour PUT). Erreur: " . json_last_error_msg()]);
            break;
        }

        $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        if (!$id) {
            echo json_encode(["success" => false, "message" => "ID de la déclaration manquant pour la mise à jour."]);
            break;
        }
        unset($data['id']); // L'ID est géré séparément

        $result = $declarationController->updateDeclaration($id, $data, $user_id);
        echo json_encode($result);
        break;

    case 'DELETE':
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if (!$id) {
            $input = file_get_contents("php://input");
            $data = json_decode($input, true);
            $id = filter_var($data['id'] ?? null, FILTER_VALIDATE_INT);
        }

        if (!$id) {
            echo json_encode(["success" => false, "message" => "ID de la déclaration manquant pour la suppression."]);
            break;
        }

        $result = $declarationController->deleteDeclaration($id, $user_id);
        echo json_encode($result);
        break;

    default:
        header('HTTP/1.0 405 Method Not Allowed');
        echo json_encode(["success" => false, "message" => "Méthode HTTP non supportée."]);
        break;
}

$mysqli->close();
?>
