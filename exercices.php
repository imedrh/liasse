<?php
// exercices.php - Page de gestion des exercices

require_once 'php/auth.php'; // Inclut les fonctions d'authentification
require_once 'php/controllers/EntrepriseController.php'; // Pour récupérer les entreprises de l'utilisateur

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isLoggedIn()) {
    redirect('login.html');
}

// Récupérer le nom d'utilisateur et le rôle de la session
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);
$user_id = $_SESSION['user_id'];

// Récupérer la liste des entreprises pour le champ de sélection
// Assurez-vous que $mysqli est disponible via config.php qui est inclus par auth.php
$entrepriseController = new EntrepriseController($mysqli);
$user_entreprises = $entrepriseController->getEntreprises($user_id);

// Définition des options pour les statuts d'exercice
$statut_options = ['Ouvert', 'Fermé', 'Archivé'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Exercices - Liasse Fiscale</title>
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
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Exercices</h1>
            <div class="text-gray-600">
                Utilisateur: <span class="font-semibold text-indigo-700"><?php echo $username; ?></span>
                (Rôle: <span class="font-semibold text-indigo-700"><?php echo $role; ?></span>)
            </div>
        </div>

        <div class="content-area">
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">Liste des exercices</h2>
                    <button id="addExerciceBtn" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i> Ajouter un exercice
                    </button>
                </div>

                <!-- Message Box for general operations -->
                <div id="generalMessageBox" class="message-box"></div>

                <!-- Champ de recherche/filtre par entreprise -->
                <div class="mb-5 flex flex-wrap gap-4">
                    <input type="text" id="searchInput" class="form-input flex-grow" placeholder="🔍 Rechercher par année ou statut...">
                    <select id="filterEntrepriseSelect" class="form-input w-full md:w-auto">
                        <option value="">Filtrer par entreprise (Toutes)</option>
                        <?php foreach ($user_entreprises as $entreprise): ?>
                            <option value="<?= htmlspecialchars($entreprise['id']) ?>">
                                <?= htmlspecialchars($entreprise['raison_sociale']) ?> (<?= htmlspecialchars($entreprise['matricule']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Entreprise</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Année</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Date Début</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Date Fin</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Statut</th>
                                <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="exercicesTableBody">
                            <!-- Les exercices seront chargés ici par JavaScript -->
                            <tr><td colspan="6" class="py-6 text-center text-gray-500 text-lg">Chargement des exercices...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour Ajouter/Modifier un exercice -->
    <div id="exerciceModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeModalButton">&times;</span>
            <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-gray-800">Ajouter un exercice</h2>

            <!-- Message Box for modal form -->
            <div id="modalMessageBox" class="message-box"></div>

            <form id="exerciceForm" class="space-y-4">
                <input type="hidden" id="exerciceId"> <!-- Pour stocker l'ID de l'exercice en mode édition -->

                <div>
                    <label for="entreprise_id" class="block text-gray-700 text-sm font-medium mb-2">Entreprise <span class="text-red-500">*</span></label>
                    <select id="entreprise_id" name="entreprise_id" class="form-input" required>
                        <option value="">Sélectionnez une entreprise</option>
                        <?php foreach ($user_entreprises as $entreprise): ?>
                            <option value="<?= htmlspecialchars($entreprise['id']) ?>">
                                <?= htmlspecialchars($entreprise['raison_sociale']) ?> (<?= htmlspecialchars($entreprise['matricule']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="annee" class="block text-gray-700 text-sm font-medium mb-2">Année <span class="text-red-500">*</span></label>
                    <input type="number" id="annee" name="annee" class="form-input" min="1900" max="2100" required>
                </div>
                
                <div>
                    <label for="date_debut" class="block text-gray-700 text-sm font-medium mb-2">Date Début <span class="text-red-500">*</span></label>
                    <input type="date" id="date_debut" name="date_debut" class="form-input" required>
                </div>

                <div>
                    <label for="date_fin" class="block text-gray-700 text-sm font-medium mb-2">Date Fin <span class="text-red-500">*</span></label>
                    <input type="date" id="date_fin" name="date_fin" class="form-input" required>
                </div>

                <div>
                    <label for="statut" class="block text-gray-700 text-sm font-medium mb-2">Statut <span class="text-red-500">*</span></label>
                    <select id="statut" name="statut" class="form-input" required>
                        <?php foreach ($statut_options as $option): ?>
                            <option value="<?= htmlspecialchars($option) ?>"><?= htmlspecialchars($option) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-6">
                    Enregistrer l'exercice
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const exercicesTableBody = document.getElementById('exercicesTableBody');
            const addExerciceBtn = document.getElementById('addExerciceBtn');
            const exerciceModal = document.getElementById('exerciceModal');
            const closeModalButton = document.getElementById('closeModalButton');
            const exerciceForm = document.getElementById('exerciceForm');
            const modalTitle = document.getElementById('modalTitle');
            const exerciceIdInput = document.getElementById('exerciceId');
            const generalMessageBox = document.getElementById('generalMessageBox');
            const modalMessageBox = document.getElementById('modalMessageBox');
            const searchInput = document.getElementById('searchInput');
            const filterEntrepriseSelect = document.getElementById('filterEntrepriseSelect');

            // Champs du formulaire modal
            const anneeInput = document.getElementById('annee');
            const dateDebutInput = document.getElementById('date_debut');
            const dateFinInput = document.getElementById('date_fin');

            // Options pour les validations côté client (doivent correspondre au backend)
            const STATUT_OPTIONS = <?php echo json_encode($statut_options); ?>;

            // Fonction utilitaire pour formater une date en YYYY-MM-DD
            function formatDateForInput(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }

            // Fonction pour mettre à jour les dates de début et de fin en fonction de l'année
            function updateDatesForYear(year) {
                if (year) {
                    const startDate = new Date(year, 0, 1); // 1er janvier de l'année
                    const endDate = new Date(year, 11, 31); // 31 décembre de l'année

                    dateDebutInput.value = formatDateForInput(startDate);
                    dateFinInput.value = formatDateForInput(endDate);
                } else {
                    dateDebutInput.value = '';
                    dateFinInput.value = '';
                }
            }

            // Écouteur d'événement pour le champ Année (mise à jour des dates)
            anneeInput.addEventListener('change', () => {
                updateDatesForYear(anneeInput.value);
            });
            // Pour aussi réagir aux saisies directes sans perte de focus
            anneeInput.addEventListener('input', () => {
                updateDatesForYear(anneeInput.value);
            });


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

            // Ouvre le modal en mode "Ajouter"
            addExerciceBtn.addEventListener('click', () => {
                exerciceForm.reset();
                exerciceIdInput.value = '';
                modalTitle.textContent = 'Ajouter un exercice';
                hideMessage(modalMessageBox);

                // Définir l'année actuelle par défaut
                const currentYear = new Date().getFullYear();
                anneeInput.value = currentYear;
                updateDatesForYear(currentYear); // Initialise les dates pour l'année actuelle

                // Si une entreprise est déjà sélectionnée dans le filtre, pré-remplir le champ entreprise_id du modal
                if (filterEntrepriseSelect.value) {
                    document.getElementById('entreprise_id').value = filterEntrepriseSelect.value;
                }
                exerciceModal.classList.add('open');
            });

            // Ferme le modal (bouton X)
            closeModalButton.addEventListener('click', () => {
                exerciceModal.classList.remove('open');
            });

            // Ferme le modal si on clique en dehors
            exerciceModal.addEventListener('click', (event) => {
                if (event.target === exerciceModal) {
                    exerciceModal.classList.remove('open');
                }
            });

            // Fonction de validation côté client des données d'exercice
            function validateExerciceClient(data) {
                // MODIFICATION ICI: Changer la validation pour entreprise_id
                // data.entreprise_id est déjà un entier ou NaN ici
                if (isNaN(data.entreprise_id) || data.entreprise_id === 0 || data.entreprise_id === null) {
                    return "Veuillez sélectionner une entreprise.";
                }
                const annee = parseInt(data.annee);
                if (isNaN(annee) || annee < 1900 || annee > 2100) {
                    return "L'année doit être une valeur valide entre 1900 et 2100.";
                }
                if (!data.date_debut || data.date_debut.trim() === '') {
                    return "La date de début est obligatoire.";
                }
                if (!data.date_fin || data.date_fin.trim() === '') {
                    return "La date de fin est obligatoire.";
                }
                if (new Date(data.date_debut) >= new Date(data.date_fin)) {
                    return "La date de fin doit être postérieure à la date de début.";
                }
                if (!STATUT_OPTIONS.includes(data.statut)) {
                    return "Statut invalide. Les statuts autorisés sont 'Ouvert', 'Fermé', 'Archivé'.";
                }
                return null; // Pas d'erreur
            }

            // Fonction pour charger les exercices
            async function loadExercices() {
                exercicesTableBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-gray-500 text-lg">Chargement des exercices...</td></tr>';
                hideMessage(generalMessageBox);

                const selectedEntrepriseId = filterEntrepriseSelect.value;
                let url = 'php/api/exercices.php';
                if (selectedEntrepriseId) {
                    url += `?entreprise_id=${selectedEntrepriseId}`;
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
                    console.log('API Response (loadExercices):', result);

                    if (result.success) {
                        exercicesTableBody.innerHTML = '';
                        if (result.data.length === 0) {
                            exercicesTableBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-gray-500 text-lg">Aucun exercice trouvé.</td></tr>';
                        } else {
                            result.data.forEach(exercice => {
                                const row = `
                                    <tr class="border-b border-gray-100 last:border-b-0 hover:bg-gray-50">
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(exercice.raison_sociale)}</td>
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(exercice.annee)}</td>
                                        <td class="py-3 px-4 text-gray-700">${formatDate(exercice.date_debut)}</td>
                                        <td class="py-3 px-4 text-gray-700">${formatDate(exercice.date_fin)}</td>
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(exercice.statut)}</td>
                                        <td class="py-3 px-4 text-center table-actions">
                                            <button data-id="${exercice.id}" class="edit-btn btn btn-info btn-icon" title="Modifier l'exercice">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button data-id="${exercice.id}" class="delete-btn btn btn-danger btn-icon" title="Supprimer l'exercice">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                exercicesTableBody.insertAdjacentHTML('beforeend', row);
                            });

                            document.querySelectorAll('.edit-btn').forEach(button => {
                                button.addEventListener('click', (e) => editExercice(e.currentTarget.dataset.id));
                            });
                            document.querySelectorAll('.delete-btn').forEach(button => {
                                button.addEventListener('click', (e) => deleteExercice(e.currentTarget.dataset.id));
                            });

                            filterTable(); // Appliquer le filtre de recherche après chargement
                        }
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors du chargement des exercices.", 'error');
                        exercicesTableBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-red-500 text-lg">Erreur de chargement des exercices.</td></tr>';
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement des exercices:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                    exercicesTableBody.innerHTML = '<tr><td colspan="6" class="py-6 text-center text-red-500 text-lg">Erreur réseau.</td></tr>';
                }
            }

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
            exerciceForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                hideMessage(modalMessageBox);

                const id = exerciceIdInput.value;
                const url = 'php/api/exercices.php';
                const method = id ? 'PUT' : 'POST';

                const formData = new FormData(exerciceForm);
                const data = Object.fromEntries(formData.entries());
                // Cette ligne est cruciale et doit rester ici AVANT la validation
                data.entreprise_id = parseInt(data.entreprise_id); // Convertir en entier

                // Client-side validation
                const validationError = validateExerciceClient(data);
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
                        exerciceModal.classList.remove('open');
                        loadExercices();
                    } else {
                        showMessage(modalMessageBox, result.message, 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de l\'envoi du formulaire:', error);
                    showMessage(modalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            });

            // Fonction pour éditer un exercice (charge les données dans le modal)
            async function editExercice(id) {
                hideMessage(modalMessageBox);

                try {
                    const response = await fetch(`php/api/exercices.php?id=${id}`, {
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
                    console.log('API Response (editExercice - GET by ID):', result);

                    if (result.success && result.data) {
                        const exercice = result.data;
                        exerciceIdInput.value = exercice.id;
                        document.getElementById('entreprise_id').value = exercice.entreprise_id || '';
                        anneeInput.value = exercice.annee || '';
                        dateDebutInput.value = exercice.date_debut || ''; // Laisser les dates existantes
                        dateFinInput.value = exercice.date_fin || '';     // Laisser les dates existantes
                        document.getElementById('statut').value = exercice.statut || 'Ouvert';

                        modalTitle.textContent = 'Modifier un exercice';
                        exerciceModal.classList.add('open');
                    } else {
                        showMessage(generalMessageBox, result.message || "Exercice non trouvé pour l'édition.", 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement de l\'exercice pour édition:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur lors de l'édition: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            }

            // Fonction pour supprimer un exercice
            async function deleteExercice(id) {
                hideMessage(generalMessageBox);

                if (!confirm('Êtes-vous sûr de vouloir supprimer cet exercice ? Cette action est irréversible.')) {
                    return;
                }

                try {
                    const response = await fetch('php/api/exercices.php', {
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
                    console.log('API Response (Delete Exercice):', result);

                    if (result.success) {
                        showMessage(generalMessageBox, result.message, 'success');
                        loadExercices();
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors de la suppression de l'exercice.", 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de la suppression de l\'exercice:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur lors de la suppression: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            }

            // Fonction de filtrage du tableau pour la recherche
            function filterTable() {
                const filter = searchInput.value.toLowerCase();
                const rows = exercicesTableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    // Ne filtre pas les messages de chargement ou "aucun exercice trouvé"
                    if (textContent.includes('chargement des exercices') || textContent.includes('aucun exercice trouvé')) {
                        row.style.display = '';
                    } else {
                        row.style.display = textContent.includes(filter) ? '' : 'none';
                    }
                });
            }

            // Écouteur d'événement pour le champ de recherche
            searchInput.addEventListener('keyup', filterTable);
            // Écouteur d'événement pour le changement de filtre par entreprise
            filterEntrepriseSelect.addEventListener('change', loadExercices); // Recharge les exercices quand l'entreprise change

            // Charge les exercices au chargement de la page
            loadExercices();
        });
    </script>
</body>
</html>
