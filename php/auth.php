<?php
// PHP/auth.php - Fonctions d'authentification
// Ce fichier contient les fonctions pour l'inscription, la connexion et la déconnexion.

require_once 'config.php'; // Inclut le fichier de configuration de la base de données

// Fonction pour inscrire un nouvel utilisateur
function registerUser($username, $password, $email, $role = 'user') {
    global $mysqli; // Utilise la connexion globale à la base de données

    // Hachage du mot de passe pour des raisons de sécurité
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prépare la requête d'insertion
    $stmt = $mysqli->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        error_log("Failed to prepare statement for user registration: " . $mysqli->error);
        return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de l'inscription."];
    }

    // Lie les paramètres
    $stmt->bind_param("ssss", $username, $hashed_password, $email, $role);

    // Exécute la requête
    if ($stmt->execute()) {
        $stmt->close();
        return ["success" => true, "message" => "Inscription réussie. Vous pouvez maintenant vous connecter."];
    } else {
        // Vérifie si l'erreur est due à un nom d'utilisateur ou un email déjà existant
        if ($mysqli->errno == 1062) { // Code d'erreur MySQL pour 'Duplicate entry'
            if (strpos($mysqli->error, 'username') !== false) {
                $message = "Ce nom d'utilisateur est déjà pris.";
            } elseif (strpos($mysqli->error, 'email') !== false) {
                $message = "Cet email est déjà enregistré.";
            } else {
                $message = "Erreur lors de l'inscription: Nom d'utilisateur ou email déjà existant.";
            }
        } else {
            $message = "Erreur lors de l'inscription: " . $stmt->error;
            error_log("Error during user registration: " . $stmt->error);
        }
        $stmt->close();
        return ["success" => false, "message" => $message];
    }
}

// Fonction pour connecter un utilisateur
function loginUser($username, $password) {
    global $mysqli; // Utilise la connexion globale à la base de données

    // Prépare la requête pour récupérer l'utilisateur
    $stmt = $mysqli->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    if ($stmt === false) {
        error_log("Failed to prepare statement for user login: " . $mysqli->error);
        return ["success" => false, "message" => "Erreur interne du serveur lors de la préparation de la connexion."];
    }

    // Lie les paramètres
    $stmt->bind_param("s", $username);

    // Exécute la requête
    $stmt->execute();
    $stmt->store_result(); // Stocke le résultat pour pouvoir utiliser num_rows

    // Vérifie si un utilisateur a été trouvé
    if ($stmt->num_rows == 1) {
        // Lie les colonnes du résultat
        $stmt->bind_result($id, $db_username, $hashed_password, $role);
        $stmt->fetch();

        // Vérifie le mot de passe
        if (password_verify($password, $hashed_password)) {
            // Mot de passe correct, démarre la session
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $db_username;
            $_SESSION['role'] = $role;
            $stmt->close();
            return ["success" => true, "message" => "Connexion réussie."];
        } else {
            // Mot de passe incorrect
            $stmt->close();
            return ["success" => false, "message" => "Nom d'utilisateur ou mot de passe incorrect."];
        }
    } else {
        // Nom d'utilisateur non trouvé
        $stmt->close();
        return ["success" => false, "message" => "Nom d'utilisateur ou mot de passe incorrect."];
    }
}

// Fonction pour déconnecter l'utilisateur
function logoutUser() {
    // Détruit toutes les variables de session
    $_SESSION = array();

    // Détruit la session.
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Finalement, détruit la session.
    session_destroy();
    return ["success" => true, "message" => "Déconnexion réussie."];
}

// Vérifie si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

?>