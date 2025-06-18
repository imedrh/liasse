<?php
// PHP/logout.php - Déconnexion de l'utilisateur
require_once 'auth.php'; // Inclut les fonctions d'authentification

$result = logoutUser(); // Appelle la fonction de déconnexion
redirect('../login.html'); // Redirige vers la page de connexion après déconnexion
?>