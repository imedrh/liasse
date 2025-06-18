<?php
// entreprises.php - Page de gestion des entreprises

require_once 'php/auth.php'; // Inclut les fonctions d'authentification

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isLoggedIn()) {
    redirect('login.html');
}

// Récupérer le nom d'utilisateur et le rôle de la session
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);

// Définition des options pour les listes déroulantes (Clé, TVA, Catégorie)
$cle_options = str_split('ABCDEFGHJKLMNPQRSTVWXYZ'); // Exclut I et O
$tva_options = ['A', 'B', 'P', 'D', 'N'];
$categorie_options = ['M', 'C', 'P', 'N'];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Entreprises - Liasse Fiscale</title>
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
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Entreprises</h1>
            <div class="text-gray-600">
                Utilisateur: <span class="font-semibold text-indigo-700"><?php echo $username; ?></span>
                (Rôle: <span class="font-semibold text-indigo-700"><?php echo $role; ?></span>)
            </div>
        </div>

        <div class="content-area">
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <div class="flex justify-between items-center mb-6 flex-wrap gap-4">
                    <h2 class="text-2xl font-bold text-gray-800">Liste des entreprises</h2>
                    <button id="addEntrepriseBtn" class="btn btn-primary">
                        <i class="fas fa-plus-circle mr-2"></i> Ajouter une entreprise
                    </button>
                </div>

                <!-- Message Box for general operations -->
                <div id="generalMessageBox" class="message-box"></div>

                <!-- Champ de recherche -->
                <div class="mb-5">
                    <input type="text" id="searchInput" class="form-input" placeholder="🔍 Rechercher par raison sociale, activité, ou matricule...">
                </div>

                <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-100 border-b border-gray-200">
                            <tr>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Raison Sociale</th>
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Matricule Fiscal</th> <!-- Ordre changé -->
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Activité</th>         <!-- Ordre changé -->
                                <th class="py-3 px-4 text-left text-sm font-semibold text-gray-700 uppercase tracking-wider">Adresse</th>
                                <th class="py-3 px-4 text-center text-sm font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="entreprisesTableBody">
                            <!-- Les entreprises seront chargées ici par JavaScript -->
                            <tr><td colspan="5" class="py-6 text-center text-gray-500 text-lg">Chargement des entreprises...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal pour Ajouter/Modifier une entreprise -->
    <div id="entrepriseModal" class="modal">
        <div class="modal-content">
            <span class="close-button" id="closeModalButton">&times;</span>
            <h2 id="modalTitle" class="text-2xl font-bold mb-6 text-gray-800">Ajouter une entreprise</h2>

            <!-- Message Box for modal form -->
            <div id="modalMessageBox" class="message-box"></div>

            <form id="entrepriseForm" class="space-y-4">
                <input type="hidden" id="entrepriseId"> <!-- Pour stocker l'ID de l'entreprise en mode édition -->

                <div>
                    <label for="raison_sociale" class="block text-gray-700 text-sm font-medium mb-2">Raison Sociale <span class="text-red-500">*</span></label>
                    <input type="text" id="raison_sociale" name="raison_sociale" class="form-input" required autocomplete="organization">
                </div>
                <div>
                    <label for="activite" class="block text-gray-700 text-sm font-medium mb-2">Activité</label>
                    <input type="text" id="activite" name="activite" class="form-input">
                </div>
                <div>
                    <label for="adresse" class="block text-gray-700 text-sm font-medium mb-2">Adresse</label>
                    <textarea id="adresse" name="adresse" class="form-input" rows="3"></textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-4"> <!-- Grid responsive pour le matricule -->
                    <div>
                        <label for="matricule" class="block text-gray-700 text-sm font-medium mb-2">Matricule <span class="text-red-500">*</span></label>
                        <input type="text" id="matricule" name="matricule" class="form-input" maxlength="7" pattern="[0-9]{7}" title="Matricule: 7 chiffres requis." inputmode="numeric" required>
                    </div>
                    <div>
                        <label for="cle" class="block text-gray-700 text-sm font-medium mb-2">Clé <span class="text-red-500">*</span></label>
                        <select id="cle" name="cle" class="form-input" required>
                            <option value="">--</option>
                            <?php foreach ($cle_options as $option): ?>
                                <option value="<?= $option ?>"><?= $option ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="tva" class="block text-gray-700 text-sm font-medium mb-2">TVA <span class="text-red-500">*</span></label>
                        <select id="tva" name="tva" class="form-input" required>
                            <option value="">--</option>
                            <?php foreach ($tva_options as $option): ?>
                                <option value="<?= $option ?>"><?= $option ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="categorie" class="block text-gray-700 text-sm font-medium mb-2">Catégorie <span class="text-red-500">*</span></label>
                        <select id="categorie" name="categorie" class="form-input" required>
                            <option value="">--</option>
                            <?php foreach ($categorie_options as $option): ?>
                                <option value="<?= $option ?>"><?= $option ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="serie" class="block text-gray-700 text-sm font-medium mb-2">Série <span class="text-red-500">*</span></label>
                        <input type="text" id="serie" name="serie" class="form-input" maxlength="3" pattern="[0-9]{3}" title="Série: 3 chiffres requis." value="000" inputmode="numeric" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-full mt-6">
                    Enregistrer l'entreprise
                </button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const entreprisesTableBody = document.getElementById('entreprisesTableBody');
            const addEntrepriseBtn = document.getElementById('addEntrepriseBtn');
            const entrepriseModal = document.getElementById('entrepriseModal');
            const closeModalButton = document.getElementById('closeModalButton');
            const entrepriseForm = document.getElementById('entrepriseForm');
            const modalTitle = document.getElementById('modalTitle');
            const entrepriseIdInput = document.getElementById('entrepriseId');
            const generalMessageBox = document.getElementById('generalMessageBox');
            const modalMessageBox = document.getElementById('modalMessageBox');
            const searchInput = document.getElementById('searchInput');

            // Options pour les validations côté client (doivent correspondre au backend)
            const CLE_OPTIONS = <?php echo json_encode($cle_options); ?>;
            const TVA_OPTIONS = <?php echo json_encode($tva_options); ?>;
            const CATEGORIE_OPTIONS = <?php echo json_encode($categorie_options); ?>;

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
            addEntrepriseBtn.addEventListener('click', () => {
                entrepriseForm.reset();
                entrepriseIdInput.value = '';
                modalTitle.textContent = 'Ajouter une entreprise';
                document.getElementById('serie').value = '000'; // Assurer la valeur par défaut
                hideMessage(modalMessageBox);
                entrepriseModal.classList.add('open');
            });

            // Ferme le modal (bouton X)
            closeModalButton.addEventListener('click', () => {
                entrepriseModal.classList.remove('open');
            });

            // Ferme le modal si on clique en dehors
            entrepriseModal.addEventListener('click', (event) => {
                // Ne ferme le modal que si le clic est directement sur l'arrière-plan du modal
                if (event.target === entrepriseModal) {
                    entrepriseModal.classList.remove('open');
                }
            });

            // Fonction de validation côté client des champs du matricule fiscal
            function validateFiscalIdClient(data) {
                if (!/^\d{7}$/.test(data.matricule)) {
                    return "Matricule: exactement 7 chiffres requis.";
                }
                if (!CLE_OPTIONS.includes(data.cle)) {
                    return "Clé invalide. Veuillez choisir parmi les options autorisées (A-H, J-N, P-Z).";
                }
                if (!TVA_OPTIONS.includes(data.tva)) {
                    return "TVA invalide. Veuillez choisir parmi les options autorisées (A, B, P, D, N).";
                }
                if (!CATEGORIE_OPTIONS.includes(data.categorie)) {
                    return "Catégorie invalide. Veuillez choisir parmi les options autorisées (M, C, P, N).";
                }
                if (!/^\d{3}$/.test(data.serie)) {
                    return "Série invalide: 3 chiffres requis.";
                }
                return null; // Pas d'erreur
            }

            // Fonction pour charger les entreprises
            async function loadEntreprises() {
                entreprisesTableBody.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-gray-500 text-lg">Chargement des entreprises...</td></tr>';
                hideMessage(generalMessageBox);

                try {
                    const response = await fetch('php/api/entreprises.php', {
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
                    console.log('API Response (loadEntreprises):', result);

                    if (result.success) {
                        entreprisesTableBody.innerHTML = '';
                        if (result.data.length === 0) {
                            entreprisesTableBody.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-gray-500 text-lg">Aucune entreprise trouvée.</td></tr>';
                        } else {
                            result.data.forEach(entreprise => {
                                // Formatage du matricule fiscal comme demandé : matricule/cle/tva/categorie/serie
                                const matriculeFiscalFormatted = `${htmlspecialchars(entreprise.matricule)}/${htmlspecialchars(entreprise.cle)}/${htmlspecialchars(entreprise.tva)}/${htmlspecialchars(entreprise.categorie)}/${htmlspecialchars(entreprise.serie)}`;

                                const row = `
                                    <tr class="border-b border-gray-100 last:border-b-0 hover:bg-gray-50">
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(entreprise.raison_sociale)}</td>
                                        <td class="py-3 px-4 text-gray-700">${matriculeFiscalFormatted}</td> <!-- Ordre changé -->
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(entreprise.activite || 'N/A')}</td>         <!-- Ordre changé -->
                                        <td class="py-3 px-4 text-gray-700">${htmlspecialchars(entreprise.adresse || 'N/A')}</td>
                                        <td class="py-3 px-4 text-center table-actions">
                                            <button data-id="${entreprise.id}" class="edit-btn btn btn-info btn-icon" title="Modifier l'entreprise">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button data-id="${entreprise.id}" class="delete-btn btn btn-danger btn-icon" title="Supprimer l'entreprise">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </td>
                                    </tr>
                                `;
                                entreprisesTableBody.insertAdjacentHTML('beforeend', row);
                            });

                            document.querySelectorAll('.edit-btn').forEach(button => {
                                button.addEventListener('click', (e) => editEntreprise(e.currentTarget.dataset.id));
                            });
                            document.querySelectorAll('.delete-btn').forEach(button => {
                                button.addEventListener('click', (e) => deleteEntreprise(e.currentTarget.dataset.id));
                            });

                            filterTable(); // Appliquer le filtre de recherche après chargement
                        }
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors du chargement des entreprises.", 'error');
                        entreprisesTableBody.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-red-500 text-lg">Erreur de chargement des entreprises.</td></tr>';
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement des entreprises:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                    entreprisesTableBody.innerHTML = '<tr><td colspan="5" class="py-6 text-center text-red-500 text-lg">Erreur réseau.</td></tr>';
                }
            }

            // Fonction d'échappement HTML pour l'affichage dans le tableau
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
            entrepriseForm.addEventListener('submit', async (event) => {
                event.preventDefault();
                hideMessage(modalMessageBox);

                const id = entrepriseIdInput.value;
                const url = 'php/api/entreprises.php';
                const method = id ? 'PUT' : 'POST';

                const formData = new FormData(entrepriseForm);
                const data = Object.fromEntries(formData.entries());

                // Client-side validation for general required fields
                const requiredFields = ['raison_sociale', 'matricule', 'cle', 'categorie', 'tva', 'serie'];
                for (let field of requiredFields) {
                    if (!data[field] || data[field].trim() === '') {
                        showMessage(modalMessageBox, `Le champ '${field.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase())}' est obligatoire.`, 'error');
                        return;
                    }
                }

                // Client-side validation for fiscal ID specific fields
                const fiscalIdError = validateFiscalIdClient(data);
                if (fiscalIdError) {
                    showMessage(modalMessageBox, fiscalIdError, 'error');
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
                        entrepriseModal.classList.remove('open');
                        loadEntreprises();
                    } else {
                        showMessage(modalMessageBox, result.message, 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de l\'envoi du formulaire:', error);
                    showMessage(modalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            });

            // Fonction pour éditer une entreprise (charge les données dans le modal)
            async function editEntreprise(id) {
                hideMessage(modalMessageBox);

                try {
                    const response = await fetch(`php/api/entreprises.php?id=${id}`, {
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
                    console.log('API Response (editEntreprise - GET by ID):', result);

                    if (result.success && result.data) {
                        const entreprise = result.data;
                        entrepriseIdInput.value = entreprise.id;
                        document.getElementById('raison_sociale').value = entreprise.raison_sociale || '';
                        document.getElementById('activite').value = entreprise.activite || '';
                        document.getElementById('adresse').value = entreprise.adresse || '';
                        document.getElementById('matricule').value = entreprise.matricule || '';
                        document.getElementById('cle').value = entreprise.cle || '';
                        document.getElementById('tva').value = entreprise.tva || '';
                        document.getElementById('categorie').value = entreprise.categorie || '';
                        document.getElementById('serie').value = entreprise.serie || '000';

                        modalTitle.textContent = 'Modifier une entreprise';
                        entrepriseModal.classList.add('open');
                    } else {
                        showMessage(generalMessageBox, result.message || "Entreprise non trouvée pour l'édition.", 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement de l\'entreprise pour édition:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur lors de l'édition: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            }

            // Fonction pour supprimer une entreprise
            async function deleteEntreprise(id) {
                hideMessage(generalMessageBox);

                if (!confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ? Cette action est irréversible.')) {
                    return;
                }

                try {
                    const response = await fetch('php/api/entreprises.php', {
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
                    console.log('API Response (Delete Entreprise):', result);

                    if (result.success) {
                        showMessage(generalMessageBox, result.message, 'success');
                        loadEntreprises();
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors de la suppression de l'entreprise.", 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de la suppression de l\'entreprise:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur lors de la suppression: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            }

            // Fonction de filtrage du tableau pour la recherche
            function filterTable() {
                const filter = searchInput.value.toLowerCase();
                const rows = entreprisesTableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes('chargement des entreprises') || text.includes('aucune entreprise trouvée')) {
                        row.style.display = '';
                    } else {
                        row.style.display = text.includes(filter) ? '' : 'none';
                    }
                });
            }

            // Écouteur d'événement pour le champ de recherche
            searchInput.addEventListener('keyup', filterTable);

            // Filtres d'entrée pour matricule et série (nombres seulement)
            document.getElementById('matricule').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 7);
            });
            document.getElementById('serie').addEventListener('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '').slice(0, 3);
            });

            // Charge les entreprises au chargement de la page
            loadEntreprises();
        });
    </script>
</body>
</html>
