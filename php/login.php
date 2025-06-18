<?php
// PHP/login.php - Traitement du formulaire de connexion
require_once 'auth.php'; // Inclut les fonctions d'authentification

$message = ''; // Message à afficher à l'utilisateur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $message = "Veuillez remplir tous les champs.";
    } else {
        // Tente de connecter l'utilisateur
        $result = loginUser($username, $password);
        $message = $result['message'];
        if ($result['success']) {
            // Redirige vers le tableau de bord ou une page d'accueil après connexion réussie
            redirect('../index.php');
        }
    }
}

// Le reste du HTML est géré par login.html
?>