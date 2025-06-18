<?php
// PHP/register.php - Traitement du formulaire d'inscription
require_once 'auth.php'; // Inclut les fonctions d'authentification

$message = ''; // Message à afficher à l'utilisateur

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $confirm_password = trim($_POST['confirm_password'] ?? '');

    // Valide les entrées
    if (empty($username) || empty($password) || empty($email) || empty($confirm_password)) {
        $message = "Veuillez remplir tous les champs.";
    } elseif ($password !== $confirm_password) {
        $message = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($password) < 6) {
        $message = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Format d'email invalide.";
    } else {
        // Tente d'inscrire l'utilisateur
        $result = registerUser($username, $password, $email);
        $message = $result['message'];
        if ($result['success']) {
            // Redirige vers la page de connexion après une inscription réussie
            redirect('../login.html');
        }
    }
}

// Le reste du HTML est géré par register.html
?>