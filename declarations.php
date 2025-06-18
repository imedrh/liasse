<?php
// declarations.php - Page de gestion des déclarations

require_once 'php/auth.php'; // Inclut les fonctions d'authentification
require_once 'php/controllers/EntrepriseController.php'; // Pour récupérer les entreprises de l'utilisateur
require_once 'php/controllers/ExerciceController.php';   // Pour récupérer les exercices de l'utilisateur

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isLoggedIn()) {
    redirect('login.html');
}

// Récupérer le nom d'utilisateur et le rôle de la session
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);
$user_id = $_SESSION['user_id'];

// Récupérer la liste complète des entreprises et exercices pour le frontend
$entrepriseController = new EntrepriseController($mysqli);
$user_entreprises = $entrepriseController->getEntreprises($user_id);

$exerciceController = new ExerciceController($mysqli);
$user_exercices = $exerciceController->getExercices($user_id); // Récupère TOUS les exercices de l'utilisateur

// Définition des options pour les types de dépôt et nature de dépôt
$type_depot_options = [
    'D' => 'D - Définition',
    'P' => 'P - Provisoire'
];
$nature_depot_options = [
    '0' => '0 - Spontané',
    '1' => '1 - Rectification',
    '2' => '2 - Régularisation'
];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Déclarations - Liasse Fiscale</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Importation de la police Inter */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
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

        /* Styles pour le modal */
        .modal {
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.6); /* Plus sombre */
            display: flex;
            justify-content: center;
            align-items: center;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }
        .modal.open {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            background-color: #fefefe;
            padding: 2.5rem;
            border-radius: 1rem; /* Plus arrondi */
            box-shadow: 0 15px 30px rgba(0,0,0,0.3); /* Ombre plus prononcée */
            width: 90%;
            max-width: 700px; /* Plus large pour le formulaire */
            position: relative;
            transform: translateY(-50px); /* Animation au chargement */
            transition: transform 0.3s ease-in-out;
        }
        .modal.open .modal-content {
            transform: translateY(0);
        }
        .close-button {
            color: #aaa;
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            font-size: 2rem; /* Plus grand */
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }
        .close-button:hover,
        .close-button:focus {
            color: #555;
        }
        /* Message Box */
        .message-box {
            padding: 0.75rem 1.25rem;
            border-radius: 0.625rem; /* Légèrement plus arrondi */
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            display: none; /* Hidden by default */
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .message-box.error {
            background-color: #fef2f2; /* Rouge pâle */
            color: #dc2626; /* Rouge plus foncé */
            border: 1px solid #ef4444; /* Bordure rouge */
        }
        .message-box.success {
            background-color: #ecfdf5; /* Vert pâle */
            color: #059669; /* Vert plus foncé */
            border: 1px solid #10b981; /* Bordure verte */
        }
        /* Form Inputs */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db; /* Bordure grise plus claire */
            border-radius: 0.625rem; /* Plus arrondi */
            font-size: 1rem;
            color: #374151; /* Texte gris foncé */
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-input:focus {
            outline: none;
            border-color: #4f46e5; /* Bleu-violet plus profond */
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25); /* Ombre de focus plus prononcée */
        }
        textarea.form-input {
            min-height: 80px; /* Minimum height for textareas */
            resize: vertical; /* Allow vertical resizing */
        }
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            border-radius: 0.625rem;
            transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out, box-shadow 0.2s ease-in-out;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            border: none;
            cursor: pointer;
        }
        .btn-primary {
            background-color: #4f46e5; /* Bleu-violet */
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #4338ca; /* Plus foncé */
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .btn-secondary {
            background-color: #6b7280; /* Gris */
            color: #ffffff;
        }
        .btn-secondary:hover {
            background-color: #4b5563;
        }
        .btn-danger {
            background-color: #ef4444; /* Rouge */
            color: #ffffff;
        }
        .btn-danger:hover {
            background-color: #dc2626;
        }
        .btn-info {
            background-color: #3b82f6; /* Bleu clair */
            color: #ffffff;
        }
        .btn-info:hover {
            background-color: #2563eb;
        }
        /* Adjusted padding for icon-only buttons in table */
        .btn-icon {
            padding: 0.5rem 0.75rem; /* Smaller padding for icon buttons */
        }
        .table-actions button {
            margin: 0.25rem;
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
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Déclarations</h1>
            <div class="text-gray-600">
                Utilisateur: <span class="font-semibold text-indigo-700"><?php echo $username; ?></span>
                (Rôle: <span class="font-semibold text-indigo-700"><?php echo $role; ?></span>)
            </div>
        </div>

        <div class="content-area">
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">Liste des déclarations</h2>
                    <button id="addDeclarationBtn" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i> Ajouter une déclaration
                    </button>
                </div>

                <!-- Message Box for general operations -->
                <div id="generalMessageBox" class="message-box"></div>

                <!-- Champs de recherche/filtres par entreprise et exercice -->
                <div class="mb-5 flex flex-wrap gap-4">
                    <input type="text" id="searchInput" class="form-input flex-grow" placeholder="🔍 Rechercher par numéro dépôt, type, nature...">
                    
                    <select id="filterEntrepriseSelect" class="form-input w-full md:w-auto">
                        <option value="">Filtrer par entreprise (Toutes)</option>
                        <?php foreach ($user_entreprises as $entreprise): ?>
                            <option value="<?= htmlspecialchars($entreprise['id']) ?>">
                                <?= htmlspecialchars($entreprise['raison_sociale']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <select id="filterExerciceSelect" class="form-input w-full md:w-auto">
                        <option value="">Filtrer par exercice (Tous)</option>
                        <!-- Les options seront chargées dynamiquement par JS si une entreprise est sélectionnée -->
                    </select>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Entreprise</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Exercice</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Numéro Dépôt</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Nature</th>
                                <!-- <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Date Déclaration</th> -->
                                <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="declarationsTableBody">
                            <!-- Les déclarations seront chargées ici par JavaScript -->
                            <!-- Colspan ajusté à 6 car 'Date Déclaration' est retirée -->
                            <tr><td colspan="6" class="py-6 text-center text-gray-500 text-lg">Chargement des déclarations...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour Ajouter/Modifier une déclaration -->
    <div id="declarationModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeModalButton">&times;</span>
            <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-gray-800">Ajouter une déclaration</h2>

            <!-- Message Box for modal form -->
            <div id="modalMessageBox" class="message-box"></div>

            <form id="declarationForm" class="space-y-4">
                <input type="hidden" id="declarationId"> <!-- Pour stocker l'ID de la déclaration en mode édition -->

                <div>
                    <label for="modal_entreprise_id" class="block text-gray-700 text-sm font-medium mb-2">Entreprise <span class="text-red-500">*</span></label>
                    <select id="modal_entreprise_id" name="entreprise_id" class="form-input" required>
                        <option value="">Sélectionnez une entreprise</option>
                        <?php foreach ($user_entreprises as $entreprise): ?>
                            <option value="<?= htmlspecialchars($entreprise['id']) ?>">
                                <?= htmlspecialchars($entreprise['raison_sociale']) ?> (<?= htmlspecialchars($entreprise['matricule']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="modal_exercice_id" class="block text-gray-700 text-sm font-medium mb-2">Exercice <span class="text-red-500">*</span></label>
                    <select id="modal_exercice_id" name="exercice_id" class="form-input" required disabled>
                        <option value="">Sélectionnez une entreprise d'abord</option>
                        <!-- Options d'exercice seront filtrées et chargées ici par JavaScript -->
                    </select>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="type_depot" class="block text-gray-700 text-sm font-medium mb-2">Type Dépôt <span class="text-red-500">*</span></label>
                        <select id="type_depot" name="type_depot" class="form-input" required>
                            <?php foreach ($type_depot_options as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="nature_depot" class="block text-gray-700 text-sm font-medium mb-2">Nature Dépôt <span class="text-red-500">*</span></label>
                        <select id="nature_depot" name="nature_depot" class="form-input" required>
                            <?php foreach ($nature_depot_options as $value => $label): ?>
                                <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Numero de dépôt sera généré côté serveur lors de l'ajout et affiché en lecture seule pour la modification -->
                <div id="numeroDepotDisplayDiv" class="hidden">
                    <label for="numero_depot_display" class="block text-gray-700 text-sm font-medium mb-2">Numéro Dépôt</label>
                    <input type="text" id="numero_depot_display" class="form-input bg-gray-100 cursor-not-allowed" readonly>
                </div>


                <div>
                    <label for="date_declaration" class="block text-gray-700 text-sm font-medium mb-2">Date de Déclaration <span class="text-red-500">*</span></label>
                    <input type="date" id="date_declaration" name="date_declaration" class="form-input" required>
                </div>
                
                <button type="submit" class="btn btn-primary w-full mt-6">
                    Enregistrer la déclaration
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const declarationsTableBody = document.getElementById('declarationsTableBody');
            const addDeclarationBtn = document.getElementById('addDeclarationBtn');
            const declarationModal = document.getElementById('declarationModal');
            const closeModalButton = document.getElementById('closeModalButton');
            const declarationForm = document.getElementById('declarationForm');
            const modalTitle = document.getElementById('modalTitle');
            const declarationIdInput = document.getElementById('declarationId');
            const generalMessageBox = document.getElementById('generalMessageBox');
            const modalMessageBox = document.getElementById('modalMessageBox');
            const searchInput = document.getElementById('searchInput');
            const filterEntrepriseSelect = document.getElementById('filterEntrepriseSelect');
            const filterExerciceSelect = document.getElementById('filterExerciceSelect');

            // Champs du formulaire modal
            const modalEntrepriseIdSelect = document.getElementById('modal_entreprise_id');
            const modalExerciceIdSelect = document.getElementById('modal_exercice_id');
            const dateDeclarationInput = document.getElementById('date_declaration');
            const numeroDepotDisplayDiv = document.getElementById('numeroDepotDisplayDiv');
            const numeroDepotDisplayInput = document.getElementById('numero_depot_display');


            // Données PHP passées au JavaScript
            const ALL_USER_ENTREPRISES = <?php echo json_encode($user_entreprises); ?>;
            const ALL_USER_EXERCICES = <?php echo json_encode($user_exercices); ?>;
            const TYPE_DEPOT_OPTIONS_MAP = <?php echo json_encode($type_depot_options); ?>;
            const NATURE_DEPOT_OPTIONS_MAP = <?php echo json_encode($nature_depot_options); ?>;

            // Extraire les clés (valeurs) pour la validation `includes`
            const TYPE_DEPOT_OPTIONS = Object.keys(TYPE_DEPOT_OPTIONS_MAP);
            const NATURE_DEPOT_OPTIONS = Object.keys(NATURE_DEPOT_OPTIONS_MAP);

            // Fonction utilitaire pour formater une date en YYYY-MM-DD (pour les inputs date)
            function formatDateForInput(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Fonction pour afficher les messages
            function showMessage(box, message, type = 'error') {
                box.textContent = message;
                box.className = `message-box ${type}`;
                box.style.display = 'block';
                box.style.opacity = '0';
                box.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    box.style.transition = 'opacity 0.3s ease-out, transform 0.3s ease-out';
                    box.style.opacity = '1';
                    box.style.transform = 'translateY(0)';
                }, 10);

                setTimeout(() => {
                    box.style.opacity = '0';
                    box.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        hideMessage(box);
                    }, 300);
                }, 5000);
            }

            // Fonction pour masquer les messages
            function hideMessage(box) {
                box.style.display = 'none';
                box.textContent = '';
                box.classList.remove('error', 'success');
            }

            // Filtre les exercices du modal en fonction de l'entreprise sélectionnée
            function filterModalExercices() {
                const selectedEntrepriseId = modalEntrepriseIdSelect.value;
                modalExerciceIdSelect.innerHTML = '<option value="">Sélectionnez un exercice</option>';
                modalExerciceIdSelect.disabled = true;

                if (selectedEntrepriseId) {
                    const filteredExercices = ALL_USER_EXERCICES.filter(exercice => 
                        exercice.entreprise_id == selectedEntrepriseId
                    );
                    filteredExercices.sort((a, b) => b.annee - a.annee); // Trier par année décroissante

                    filteredExercices.forEach(exercice => {
                        const option = document.createElement('option');
                        option.value = exercice.id;
                        option.textContent = `${exercice.annee} (${formatDate(exercice.date_debut)} - ${formatDate(exercice.date_fin)})`;
                        modalExerciceIdSelect.appendChild(option);
                    });
                    modalExerciceIdSelect.disabled = false;
                }
            }

            // Ouvre le modal en mode "Ajouter"
            addDeclarationBtn.addEventListener('click', () => {
                declarationForm.reset();
                declarationIdInput.value = '';
                modalTitle.textContent = 'Ajouter une déclaration';
                hideMessage(modalMessageBox);
                numeroDepotDisplayDiv.classList.add('hidden'); // Cacher le numéro de dépôt en mode ajout (car généré par le backend)
                numeroDepotDisplayInput.value = '';

                // Initialiser la date de déclaration à aujourd'hui
                dateDeclarationInput.value = formatDateForInput(new Date());

                // Gérer les filtres de la page principale pour le modal
                if (filterEntrepriseSelect.value) {
                    modalEntrepriseIdSelect.value = filterEntrepriseSelect.value;
                    filterModalExercices(); // Filtrer les exercices pour l'entreprise pré-sélectionnée
                    if (filterExerciceSelect.value) {
                        modalExerciceIdSelect.value = filterExerciceSelect.value;
                    }
                } else {
                    // Si aucune entreprise n'est sélectionnée dans le filtre, désactiver l'exercice du modal
                    modalEntrepriseIdSelect.value = ''; // Assurer que le champ entreprise est vide aussi si le filtre est "Toutes"
                    modalExerciceIdSelect.innerHTML = '<option value="">Sélectionnez une entreprise d\'abord</option>';
                    modalExerciceIdSelect.disabled = true;
                }
                
                declarationModal.classList.add('open');
            });

            // Ferme le modal (bouton X)
            closeModalButton.addEventListener('click', () => {
                declarationModal.classList.remove('open');
            });

            // Ferme le modal si on clique en dehors
            declarationModal.addEventListener('click', (event) => {
                if (event.target === declarationModal) {
                    declarationModal.classList.remove('open');
                }
            });

            // Écouteur pour le changement d'entreprise dans le modal (pour filtrer les exercices)
            modalEntrepriseIdSelect.addEventListener('change', filterModalExercices);


            // Fonction de validation côté client des données de déclaration
            function validateDeclarationClient(data) {
                if (isNaN(data.entreprise_id) || data.entreprise_id <= 0) {
                    return "Veuillez sélectionner une entreprise.";
                }
                if (isNaN(data.exercice_id) || data.exercice_id <= 0) {
                    return "Veuillez sélectionner un exercice.";
                }

                const typeDepotValue = String(data.type_depot || '').trim();
                // console.log('Validating type_depot:', `"${typeDepotValue}"`, 'Is in options:', TYPE_DEPOT_OPTIONS.includes(typeDepotValue)); // DEBUG LOG
                if (typeDepotValue === '') {
                    return "Le type de dépôt est obligatoire.";
                }
                if (!TYPE_DEPOT_OPTIONS.includes(typeDepotValue)) {
                    return "Type de dépôt invalide. Options: " + TYPE_DEPOT_OPTIONS.join(', ') + ".";
                }

                const natureDepotValue = String(data.nature_depot || '').trim();
                // console.log('Validating nature_depot:', `"${natureDepotValue}"`, 'Is in options:', NATURE_DEPOT_OPTIONS.includes(natureDepotValue)); // DEBUG LOG
                if (natureDepotValue === '') {
                    return "La nature de dépôt est obligatoire.";
                }
                if (!NATURE_DEPOT_OPTIONS.includes(natureDepotValue)) {
                    return "Nature de dépôt invalide. Options: " + NATURE_DEPOT_OPTIONS.join(', ') + ".";
                }

                if (!data.date_declaration || data.date_declaration.trim() === '') {
                    return "La date de déclaration est obligatoire.";
                }
                
                return null; // Pas d'erreur
            }

            // Fonction pour charger les déclarations
            async function loadDeclarations() {
                declarationsTableBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-gray-500 text-lg">Chargement des déclarations...</td></tr>';
                generalMessageBox.style.display = 'none'; // Ensure general message box is hidden on load

                const selectedEntrepriseId = filterEntrepriseSelect.value;
                const selectedExerciceId = filterExerciceSelect.value;
                let url = 'php/api/declarations.php';
                const params = new URLSearchParams();

                // Build parameters for API call
                if (selectedEntrepriseId) {
                    params.append('entreprise_id', selectedEntrepriseId);
                }
                if (selectedExerciceId) {
                    params.append('exercice_id', selectedExerciceId);
                }
                
                if (params.toString()) {
                    url += `?${params.toString()}`;
                }

                try {
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Erreur API (réponse non OK):', response.status, errorText);
                        throw new Error(`Erreur serveur: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }

                    const result = await response.json();
                    console.log('API Response (loadDeclarations):', result);

                    if (result.success) {
                        declarationsTableBody.innerHTML = '';
                        // Colspan ajusté à 6 pour les messages (Entreprise, Exercice, Numéro Dépôt, Type, Nature, Actions)
                        if (result.data.length === 0) {
                            declarationsTableBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-gray-500 text-lg">Aucune déclaration trouvée.</td></tr>';
                        } else {
                            result.data.forEach(declaration => {
                                // Mappage des valeurs 'D','P' et '0','1','2' vers leurs labels complets pour l'affichage
                                const displayTypeDepot = TYPE_DEPOT_OPTIONS_MAPPING[declaration.type_depot] || declaration.type_depot;
                                const displayNatureDepot = NATURE_DEPOT_OPTIONS_MAPPING[declaration.nature_depot] || declaration.nature_depot;

                                const row = `
                                    <tr class="border-b border-gray-100 last:border-b-0 hover:bg-gray-50">
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(declaration.raison_sociale)}</td>
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(declaration.annee)}</td>
                                        <td class="py-3 px-4 text-gray-700 font-semibold">${htmlspecialchars(declaration.numero_depot)}</td>
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(displayTypeDepot)}</td>
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(displayNatureDepot)}</td>
                                        <td class="py-3 px-4 text-center table-actions">
                                            <button data-id="${declaration.id}" class="edit-btn btn btn-info btn-icon" title="Modifier la déclaration">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button data-id="${declaration.id}" class="delete-btn btn btn-danger btn-icon" title="Supprimer la déclaration">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                declarationsTableBody.insertAdjacentHTML('beforeend', row);
                            });

                            document.querySelectorAll('.edit-btn').forEach(button => {
                                button.addEventListener('click', (e) => editDeclaration(e.currentTarget.dataset.id));
                            });
                            document.querySelectorAll('.delete-btn').forEach(button => {
                                button.addEventListener('click', (e) => deleteDeclaration(e.currentTarget.dataset.id));
                            });

                            filterTable(); // Appliquer le filtre de recherche après chargement
                        }
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors du chargement des déclarations.", 'error');
                        declarationsTableBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-red-500 text-lg">Erreur de chargement des déclarations.</td></tr>';
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement des déclarations:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                    declarationsTableBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-red-500 text-lg">Erreur réseau.</td></tr>';
                }
            }

            // Met à jour les options de filtre d'exercice en fonction de l'entreprise sélectionnée
            function updateFilterExerciceOptions(selectedEntrepriseId) {
                filterExerciceSelect.innerHTML = '<option value="">Filtrer par exercice (Tous)</option>';
                const currentFilterExerciceId = filterExerciceSelect.value; // Store current selected value

                if (selectedEntrepriseId) {
                    const filteredExercices = ALL_USER_EXERCICES.filter(exercice => 
                        exercice.entreprise_id == selectedEntrepriseId
                    );
                    filteredExercices.sort((a, b) => b.annee - a.annee); // Trier par année décroissante

                    filteredExercices.forEach(exercice => {
                        const option = document.createElement('option');
                        option.value = exercice.id;
                        option.textContent = `${exercice.annee} (${formatDate(exercice.date_debut)} - ${formatDate(exercice.date_fin)})`;
                        filterExerciceSelect.appendChild(option);
                    });
                }
                // Try to re-select the previously selected exercise, if it still exists for the new enterprise
                if (currentFilterExerciceId && Array.from(filterExerciceSelect.options).some(opt => opt.value === currentFilterExerciceId)) {
                    filterExerciceSelect.value = currentFilterExerciceId;
                } else {
                    filterExerciceSelect.value = ''; // Reset if not found
                }
            }


            // Mappings pour l'affichage des labels complets
            const TYPE_DEPOT_OPTIONS_MAPPING = {
                'D': 'D - Définition',
                'P': 'P - Provisoire'
            };
            const NATURE_DEPOT_OPTIONS_MAPPING = {
                '0': '0 - Spontané',
                '1': '1 - Rectification',
                '2': '2 - Régularisation'
            };

            // Fonction pour formater les dates pour l'affichage (YYYY-MM-DD vers DD/MM/YYYY)
            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            // Fonction d'échappement HTML
            function htmlspecialchars(str) {
                if (typeof str !== 'string' && str !== null) return str;
                if (str === null) return '';
                return str.replace(/&/g, '&amp;')
                          .replace(/</g, '&lt;')
                          .replace(/>/g, '&gt;')
                          .replace(/"/g, '&quot;')
                          .replace(/'/g, '&#039;');
            }

            // Soumission du formulaire Ajouter/Modifier
            declarationForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                hideMessage(modalMessageBox);

                const id = declarationIdInput.value;
                const url = 'php/api/declarations.php';
                const method = id ? 'PUT' : 'POST';

                const formData = new FormData(declarationForm);
                const data = Object.fromEntries(formData.entries());
                
                // Convertir les IDs en entiers
                data.entreprise_id = parseInt(data.entreprise_id);
                data.exercice_id = parseInt(data.exercice_id);
                
                // Client-side validation
                const validationError = validateDeclarationClient(data);
                if (validationError) {
                    showMessage(modalMessageBox, validationError, 'error');
                    return;
                }

                if (id) {
                    data.id = id;
                }

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Erreur API (réponse non OK) lors de la soumission du formulaire:', response.status, errorText);
                        throw new Error(`Erreur serveur: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }

                    const result = await response.json();
                    console.log('API Response (Submit Form):', result);

                    if (result.success) {
                        showMessage(generalMessageBox, result.message, 'success');
                        declarationModal.classList.remove('open');
                        loadDeclarations(); // Recharger les déclarations après ajout/modification
                    } else {
                        showMessage(modalMessageBox, result.message, 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de l\'envoi du formulaire:', error);
                    showMessage(modalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            });

            // Fonction pour éditer une déclaration (charge les données dans le modal)
            async function editDeclaration(id) {
                hideMessage(modalMessageBox);

                try {
                    const response = await fetch(`php/api/declarations.php?id=${id}`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Erreur API (réponse non OK) lors de l\'édition:', response.status, errorText);
                        throw new Error(`Erreur serveur: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }
                    
                    const result = await response.json();
                    console.log('API Response (editDeclaration - GET by ID):', result);

                    if (result.success && result.data) {
                        const declaration = result.data;
                        declarationIdInput.value = declaration.id;
                        
                        // Définir l'entreprise et filtrer les exercices correspondants
                        modalEntrepriseIdSelect.value = declaration.entreprise_id || '';
                        filterModalExercices(); // Appelle pour peupler le select d'exercice
                        modalExerciceIdSelect.value = declaration.exercice_id || '';

                        document.getElementById('type_depot').value = declaration.type_depot || '';
                        document.getElementById('nature_depot').value = declaration.nature_depot || '';
                        numeroDepotDisplayInput.value = declaration.numero_depot || '';
                        numeroDepotDisplayDiv.classList.remove('hidden'); // Afficher le numéro de dépôt en mode édition

                        dateDeclarationInput.value = declaration.date_declaration || '';
                        
                        modalTitle.textContent = 'Modifier une déclaration';
                        declarationModal.classList.add('open');
                    } else {
                        showMessage(generalMessageBox, result.message || "Déclaration non trouvée pour l'édition.", 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement de la déclaration pour édition:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur lors de l'édition: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            }

            // Fonction pour supprimer une déclaration
            async function deleteDeclaration(id) {
                hideMessage(generalMessageBox);

                if (!confirm('Êtes-vous sûr de vouloir supprimer cette déclaration ? Cette action est irréversible.')) {
                    return;
                }

                try {
                    const response = await fetch('php/api/declarations.php', {
                        method: 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ id: id })
                    });
                    
                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Erreur API (réponse non OK) lors de la suppression:', response.status, errorText);
                        throw new Error(`Erreur serveur: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }

                    const result = await response.json();
                    console.log('API Response (Delete Declaration):', result);

                    if (result.success) {
                        showMessage(generalMessageBox, result.message, 'success');
                        loadDeclarations();
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors de la suppression de la déclaration.", 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de la suppression de l\'déclaration:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur lors de la suppression: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            }

            // Fonction de filtrage du tableau pour la recherche (texte + dropdowns)
            function filterTable() {
                const textFilter = searchInput.value.toLowerCase();
                const rows = declarationsTableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const rowText = row.textContent.toLowerCase();
                    // Colspan ajusté à 6 pour les messages d'état du tableau
                    if (rowText.includes('chargement des déclarations') || rowText.includes('aucune déclaration trouvée')) {
                        row.style.display = '';
                    } else {
                        row.style.display = rowText.includes(textFilter) ? '' : 'none';
                    }
                });
            }

            // Écouteur d'événement pour le champ de recherche textuel
            searchInput.addEventListener('keyup', filterTable);
            // Écouteurs d'événements pour les changements de filtres par entreprise et exercice
            filterEntrepriseSelect.addEventListener('change', () => {
                updateFilterExerciceOptions(filterEntrepriseSelect.value); // Mise à jour des options d'exercice du filtre
                loadDeclarations(); // Recharge les déclarations
            });
            filterExerciceSelect.addEventListener('change', loadDeclarations);


            // Initialisation au chargement de la page
            // Met à jour les options de filtre d'exercice au chargement initial
            updateFilterExerciceOptions(filterEntrepriseSelect.value);
            loadDeclarations(); // Charge les déclarations au chargement de la page
        });
    </script>
</body>
</html>
