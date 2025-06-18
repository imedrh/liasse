<?php
// PHP/index.php - Page d'accueil après connexion
require_once 'php/auth.php'; // Inclut les fonctions d'authentification

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isLoggedIn()) {
    redirect('login.html');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Liasse Fiscale</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Importation de la police Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Gris très clair */
            color: #333;
            margin: 0;
            display: flex; /* Utilise flexbox pour la disposition latérale */
            min-height: 100vh;
        }

        /* Styles de la barre latérale */
        .sidebar {
            background-color: #1a202c; /* Gris foncé */
            color: #ffffff;
            width: 250px;
            padding: 1.5rem;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1); /* Ombre légère */
            flex-shrink: 0; /* Empêche la barre latérale de rétrécir */
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: #cbd5e0; /* Gris clair */
            text-decoration: none;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }
        .sidebar-nav a:hover {
            background-color: #2d3748; /* Gris légèrement plus clair au survol */
            color: #e2e8f0;
        }
        .sidebar-nav a.active {
            background-color: #4c51bf; /* Bleu-violet */
            color: #ffffff;
            font-weight: 600;
        }
        .sidebar-nav .icon {
            margin-right: 0.75rem;
            width: 1.25rem; /* Standardize icon width */
            text-align: center;
        }

        /* Styles du contenu principal (header + main) */
        .main-content {
            flex-grow: 1; /* Permet au contenu principal de prendre l'espace restant */
            display: flex;
            flex-direction: column;
        }
        .header {
            background-color: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom-left-radius: 0.75rem;
            border-bottom-right-radius: 0.75rem;
        }
        .content-area {
            padding: 2rem;
            flex-grow: 1; /* Le contenu prend l'espace restant verticalement */
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-2xl font-bold mb-10 text-center text-white">Liasse Fiscale App</div>
                <nav class="sidebar-nav space-y-2">
            <a href="index.php" class="sidebar-nav-item">
                <i class="fas fa-tachometer-alt icon"></i>
                Tableau de bord
            </a>
            <a href="entreprises.php" class="sidebar-nav-item">
                <i class="fas fa-building icon"></i>
                Gestion des Entreprises
            </a>
            <a href="exercices.php" class="sidebar-nav-item">
                <i class="fas fa-calendar-alt icon"></i>
                Gestion des Exercices
            </a>
            <a href="declarations.php" class="sidebar-nav-item">
                <i class="fas fa-file-invoice icon"></i>
                Gestion des Déclarations
            </a>
            <a href="saisie_formulaire.php" class="sidebar-nav-item active"> <!-- Nouveau lien -->
                <i class="fas fa-keyboard icon"></i>
                Saisir un Formulaire
            </a>
            <a href="#">
                <i class="fas fa-upload icon"></i>
                Import des Balances
            </a>
            <a href="#">
                <i class="fas fa-folder-open icon"></i>
                Gestion des Liasses
            </a>
            <a href="#">
                <i class="fas fa-users icon"></i>
                Gestion des Utilisateurs
            </a>
            <a href="php/logout.php">
                <i class="fas fa-sign-out-alt icon"></i>
                Déconnexion
            </a>
        </nav>

    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="text-3xl font-bold text-gray-800">Tableau de Bord</h1>
            <div class="text-gray-600">
                Utilisateur: <span class="font-semibold text-indigo-700"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                (Rôle: <span class="font-semibold text-indigo-700"><?php echo htmlspecialchars($_SESSION['role']); ?></span>)
            </div>
        </div>

        <div class="content-area">
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold mb-4 text-gray-800">Aperçu</h2>
                <p class="text-gray-700 leading-relaxed">Bienvenue sur votre tableau de bord de l'application de liasse fiscale. Utilisez le menu latéral pour naviguer entre les différentes sections et gérer vos entreprises, exercices, déclarations, etc.</p>
                
                <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Carte 1: Total Entreprises -->
                    <div class="bg-blue-50 border border-blue-200 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                        <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                            <i class="fas fa-building text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Entreprises gérées</p>
                            <p class="text-2xl font-bold text-blue-800">0</p> <!-- Remplacer par un décompte dynamique plus tard -->
                        </div>
                    </div>
                    <!-- Carte 2: Déclarations en Brouillon -->
                    <div class="bg-yellow-50 border border-yellow-200 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                        <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                            <i class="fas fa-file-alt text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Déclarations en brouillon</p>
                            <p class="text-2xl font-bold text-yellow-800">0</p> <!-- Remplacer par un décompte dynamique plus tard -->
                        </div>
                    </div>
                    <!-- Carte 3: Exercices Actifs -->
                    <div class="bg-green-50 border border-green-200 p-6 rounded-lg shadow-sm flex items-center space-x-4">
                        <div class="p-3 bg-green-100 rounded-full text-green-600">
                            <i class="fas fa-calendar-check text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Exercices actifs</p>
                            <p class="text-2xl font-bold text-green-800">0</p> <!-- Remplacer par un décompte dynamique plus tard -->
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Dernières Activités</h3>
                    <ul class="space-y-3 text-gray-700">
                        <li class="p-3 bg-gray-50 rounded-lg border border-gray-200 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-3"></i>
                            Aucune activité récente à afficher.
                        </li>
                        <!-- D'autres activités pourront être ajoutées ici via JS/PHP -->
                    </ul>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
