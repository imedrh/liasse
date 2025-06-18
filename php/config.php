<?php
// PHP/config.php - Configuration de la base de données
// Ce fichier contient les paramètres de connexion à votre base de données MySQL.
// Assurez-vous de remplacer les valeurs par les vôtres.
// Activer l'affichage des erreurs pour le développement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('DB_SERVER', 'localhost'); // L'adresse de votre serveur de base de données
define('DB_USERNAME', 'root');    // Votre nom d'utilisateur MySQL
define('DB_PASSWORD', ''); // Votre mot de passe MySQL
define('DB_NAME', 'liasse_fiscale_db'); // Le nom de votre base de données

// Tente de se connecter à la base de données MySQL
$mysqli = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

// Vérifie la connexion
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données: " . $mysqli->connect_error);
}

// Définit le jeu de caractères à UTF-8 pour supporter les caractères spéciaux (comme le français)
$mysqli->set_charset("utf8mb4");

// Démarre la session PHP, nécessaire pour la gestion de l'authentification
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Fonction utilitaire pour rediriger
function redirect($url) {
    header("Location: " . $url);
    exit();
}

?>