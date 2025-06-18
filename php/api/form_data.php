<?php
// php/api/form_data.php - API pour la soumission et la récupération des données de formulaires dynamiques

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../auth.php'; // Pour la vérification de l'authentification (votre fichier auth.php)
require_once __DIR__ . '/../controllers/DeclarationController.php'; // Pour la validation de la déclaration

header('Content-Type: application/json');

// Gérer l'authentification de l'utilisateur
$user_id = null;
if (function_exists('isLoggedIn') && isLoggedIn()) {
    if (function_exists('getUser')) { 
        $user_data = getUser($mysqli); 
        if ($user_data && isset($user_data['id'])) {
            $user_id = $user_data['id'];
        }
    } else {
        $user_id = $_SESSION['user_id'] ?? null; 
    }
}

if (!$user_id) {
    echo json_encode(['success' => false, 'message' => 'Accès non autorisé. Utilisateur non connecté.']);
    exit();
}


$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        // Gérer la soumission de nouvelles données de formulaire
        $input = json_decode(file_get_contents('php://input'), true);

        $declaration_id = $input['declaration_id'] ?? null;
        $form_type = $input['form_type'] ?? null;
        $form_data = json_encode($input['form_data'] ?? []); // Stocker les données du formulaire comme JSON

        if (!$declaration_id || !$form_type) {
            echo json_encode(['success' => false, 'message' => 'ID de déclaration ou type de formulaire manquant.']);
            exit();
        }

        // Vérifier si la déclaration appartient bien à l'utilisateur (sécurité)
        $declarationController = new DeclarationController($mysqli);
        $declaration = $declarationController->getDeclarationById($declaration_id, $user_id);

        if (!$declaration) {
            echo json_encode(['success' => false, 'message' => 'Déclaration non trouvée ou accès non autorisé.']);
            exit();
        }

        // Vérifier si des données pour ce form_type existent déjà pour cette déclaration
        $stmt_check = $mysqli->prepare("SELECT id FROM form_data WHERE declaration_id = ? AND form_type = ?");
        if ($stmt_check === false) {
            echo json_encode(['success' => false, 'message' => 'Erreur de préparation (check): ' . $mysqli->error]);
            exit();
        }
        $stmt_check->bind_param("is", $declaration_id, $form_type);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            // Si des données existent, mettre à jour
            $stmt_check->bind_result($form_data_id);
            $stmt_check->fetch();
            $stmt_check->close();

            $stmt_update = $mysqli->prepare("UPDATE form_data SET form_data_json = ? WHERE id = ?");
            if ($stmt_update === false) {
                echo json_encode(['success' => false, 'message' => 'Erreur de préparation (update): ' . $mysqli->error]);
                exit();
            }
            $stmt_update->bind_param("si", $form_data, $form_data_id);
            if ($stmt_update->execute()) {
                echo json_encode(['success' => true, 'message' => 'Données du formulaire mises à jour avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour des données du formulaire: ' . $stmt_update->error]);
            }
            $stmt_update->close();
        } else {
            // Sinon, insérer de nouvelles données
            $stmt_insert = $mysqli->prepare("INSERT INTO form_data (declaration_id, form_type, form_data_json, user_id) VALUES (?, ?, ?, ?)");
            if ($stmt_insert === false) {
                echo json_encode(['success' => false, 'message' => 'Erreur de préparation (insert): ' . $mysqli->error]);
                exit();
            }
            $stmt_insert->bind_param("issi", $declaration_id, $form_type, $form_data, $user_id);
            if ($stmt_insert->execute()) {
                echo json_encode(['success' => true, 'message' => 'Données du formulaire enregistrées avec succès.', 'id' => $mysqli->insert_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement des données du formulaire: ' . $stmt_insert->error]);
            }
            $stmt_insert->close();
        }
        break;

    case 'GET':
        // Gérer la récupération de données de formulaire existantes
        $declaration_id = $_GET['declaration_id'] ?? null;
        $form_type = $_GET['form_type'] ?? null;

        if (!$declaration_id || !$form_type) {
            echo json_encode(['success' => false, 'message' => 'ID de déclaration ou type de formulaire manquant pour la récupération.']);
            exit();
        }

        // Vérifier si la déclaration appartient bien à l'utilisateur (sécurité)
        $declarationController = new DeclarationController($mysqli);
        $declaration = $declarationController->getDeclarationById($declaration_id, $user_id);

        if (!$declaration) {
            echo json_encode(['success' => false, 'message' => 'Déclaration non trouvée ou accès non autorisé pour la récupération.']);
            exit();
        }

        $stmt = $mysqli->prepare("SELECT form_data_json FROM form_data WHERE declaration_id = ? AND form_type = ? AND user_id = ?");
        if ($stmt === false) {
            echo json_encode(['success' => false, 'message' => 'Erreur de préparation (GET): ' . $mysqli->error]);
            exit();
        }
        $stmt->bind_param("isi", $declaration_id, $form_type, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        if ($row) {
            echo json_encode(['success' => true, 'data' => json_decode($row['form_data_json'], true)]);
        } else {
            echo json_encode(['success' => true, 'data' => null, 'message' => 'Aucune donnée trouvée pour ce formulaire.']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
        break;
}

$mysqli->close();
?>
