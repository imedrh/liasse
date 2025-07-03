<?php
require_once 'auth.php';
require_once 'controllers/EntrepriseController.php';
require_once 'controllers/ExerciceController.php';
require_once 'controllers/DeclarationController.php';

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isLoggedIn()) {
    redirect('../login.html');
}

// Récupérer le nom d'utilisateur et le rôle de la session
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);
$user_id = $_SESSION['user_id'];

// Récupérer les entreprises de l'utilisateur
$entrepriseController = new EntrepriseController($mysqli);
$user_entreprises = $entrepriseController->getEntreprises($user_id); // tableau de tableaux associatifs

$entreprise_id  = $_GET['entreprise_id'] ?? '';
$exercice_id    = $_GET['exercice_id'] ?? '';
$declaration_id = $_GET['declaration_id'] ?? '';
$periode        = $_GET['periode'] ?? 'N';

// Exercices (de l'entreprise sélectionnée ET de l'utilisateur)
$exercices = [];
$exerciceController = new ExerciceController($mysqli);
if ($entreprise_id) {
    // Vérifie que l'entreprise appartient bien à l'utilisateur
    $entreprise_trouvee = false;
    foreach ($user_entreprises as $ent) {
        if ($ent['id'] == $entreprise_id) {
            $entreprise_trouvee = true;
            break;
        }
    }
    if ($entreprise_trouvee) {
        $exercices = $exerciceController->getExercicesByEntreprise($user_id, $entreprise_id); // tableau de tableaux associatifs
    }
}

// Déclarations (de l'exercice sélectionné ET de l'utilisateur)
$declarations = [];
if ($exercice_id && $entreprise_id && !empty($exercices)) {
    // Vérifie que l'exercice appartient bien à l'entreprise sélectionnée ET à l'utilisateur
    $exercice_trouve = false;
    foreach ($exercices as $ex) {
        if ($ex['id'] == $exercice_id) {
            $exercice_trouve = true;
            break;
        }
    }
    if ($exercice_trouve) {
        $declarationController = new DeclarationController($mysqli);
        $declarations = $declarationController->getDeclarationsByExercice($exercice_id); // tableau de tableaux associatifs
    }
}

$message  = $_GET['message'] ?? '';
$msg_type = $_GET['msg_type'] ?? 'success';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import des Balances - Liasse Fiscale</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; color: #333; margin: 0; display: flex; min-height: 100vh; }
        .sidebar { background-color: #1a202c; color: #ffffff; width: 280px; padding: 1.5rem; box-shadow: 2px 0 5px rgba(0,0,0,0.1); flex-shrink: 0; }
        .sidebar-nav a { display: flex; align-items: center; padding: 0.50rem 0.2rem; border-radius: 0.5rem; color: #cbd5e0; text-decoration: none; transition: background-color 0.2s, color 0.2s; }
        .sidebar-nav a:hover { background-color: #2d3748; color: #e2e8f0; }
        .sidebar-nav a.active { background-color: #4c51bf; color: #ffffff; font-weight: 600; }
        .sidebar-nav .icon { margin-right: 0.75rem; width: 1.25rem; text-align: center; }
        .main-content { flex-grow: 1; display: flex; flex-direction: column; }
        .header { background-color: #ffffff; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1); padding: 1.5rem 2rem; display: flex; justify-content: space-between; align-items: center; border-bottom-left-radius: 0.75rem; border-bottom-right-radius: 0.75rem; }
        .content-area { padding: 2rem; flex-grow: 1; }
    </style>
</head>
<body>
    <div class="sidebar">
        <div class="text-2xl font-bold mb-10 text-center text-white">Liasse Fiscale</div>
        <nav class="sidebar-nav space-y-2">
            <a href="../index.php" class="sidebar-nav-item">
                <i class="fas fa-tachometer-alt icon"></i> Tableau de bord
            </a>
            <a href="../entreprises.php" class="sidebar-nav-item">
                <i class="fas fa-building icon"></i> Gestion des Entreprises
            </a>
            <a href="../exercices.php" class="sidebar-nav-item">
                <i class="fas fa-calendar-alt icon"></i> Gestion des Exercices
            </a>
            <a href="../declarations.php" class="sidebar-nav-item">
                <i class="fas fa-file-invoice icon"></i> Gestion des Déclarations
            </a>
            <a href="../saisie_formulaire.php" class="sidebar-nav-item">
                <i class="fas fa-keyboard icon"></i> Saisir un Formulaire
            </a>
            <a href="import_balance.php" class="sidebar-nav-item active">
                <i class="fas fa-upload icon"></i> Import des Balances
            </a>
            <a href="../liasses.php">
                <i class="fas fa-folder-open icon"></i> Gestion des Liasses
            </a>
            <a href="../utilisateurs.php">
                <i class="fas fa-users icon"></i> Gestion des Utilisateurs
            </a>
            <a href="logout.php">
                <i class="fas fa-sign-out-alt icon"></i> Déconnexion
            </a>
        </nav>
    </div>
    <div class="main-content">
        <div class="header">
            <h1 class="text-3xl font-bold text-gray-800">Import des Balances</h1>
            <div class="text-gray-600">
                Utilisateur: <span class="font-semibold text-indigo-700"><?= $username ?></span>
                (Rôle: <span class="font-semibold text-indigo-700"><?= $role ?></span>)
            </div>
        </div>
        <div class="content-area">
            <div class="bg-white p-8 rounded-xl shadow-lg max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center">
                    <i class="fas fa-upload mr-2 text-indigo-600"></i> Importer une balance
                </h2>
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="form-label block mb-1">Entreprise</label>
                        <select name="entreprise_id" class="form-select w-full px-2 py-2 rounded border" onchange="this.form.submit()">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($user_entreprises as $ent): ?>
                                <option value="<?= $ent['id'] ?>" <?= ($ent['id'] == $entreprise_id) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($ent['raison_sociale']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php if ($entreprise_id && !empty($exercices)): ?>
                    <div>
                        <label class="form-label block mb-1">Exercice</label>
                        <select name="exercice_id" class="form-select w-full px-2 py-2 rounded border" onchange="this.form.submit()">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($exercices as $ex): ?>
                                <option value="<?= $ex['id'] ?>" <?= ($ex['id'] == $exercice_id) ? 'selected' : '' ?>><?= htmlspecialchars($ex['annee']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php endif ?>
                    <?php if ($exercice_id && !empty($declarations)): ?>
                    <div>
                        <label class="form-label block mb-1">Déclaration</label>
                        <select name="declaration_id" class="form-select w-full px-2 py-2 rounded border" onchange="this.form.submit()">
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($declarations as $dec): ?>
                                <option value="<?= $dec['id'] ?>" <?= ($dec['id'] == $declaration_id) ? 'selected' : '' ?>><?= htmlspecialchars($dec['numero_depot']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php endif ?>
                    <?php if ($declaration_id): ?>
                    <div>
                        <label class="form-label block mb-1">Période</label>
                        <select name="periode" class="form-select w-full px-2 py-2 rounded border" onchange="this.form.submit()">
                            <?php foreach (['N', 'N-1', 'N-2'] as $p): ?>
                                <option value="<?= $p ?>" <?= ($p == $periode) ? 'selected' : '' ?>>Balance <?= $p ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php endif ?>
                </form>
                <?php if ($entreprise_id && $exercice_id && $declaration_id && !empty($exercices) && !empty($declarations)): ?>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-4">
                    <h5 class="font-semibold mb-2 text-indigo-700">Importer la balance <?= htmlspecialchars($periode) ?></h5>
                    <?php if ($message): ?>
                        <div class="mb-3 rounded px-4 py-3 text-sm <?= $msg_type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= htmlspecialchars($message) ?>
                        </div>
                    <?php endif; ?>
                    <form method="POST" enctype="multipart/form-data" action="import_balance_traitement.php">
                        <input type="hidden" name="entreprise_id" value="<?= $entreprise_id ?>">
                        <input type="hidden" name="exercice_id" value="<?= $exercice_id ?>">
                        <input type="hidden" name="declaration_id" value="<?= $declaration_id ?>">
                        <input type="hidden" name="periode" value="<?= htmlspecialchars($periode) ?>">
                        <div class="flex flex-col md:flex-row gap-4 items-center">
                            <input type="file" name="balance_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="form-control flex-grow" required>
                            <button class="btn bg-indigo-600 text-white font-semibold px-6 py-2 rounded hover:bg-indigo-700 transition">
                                <i class="fas fa-upload mr-2"></i> Importer
                            </button>
                        </div>
                        <div class="form-text mt-2 text-gray-500">Formats acceptés : CSV, Excel (.xlsx, .xls)</div>
                    </form>
                    <div class="mt-2">
                        <a href="modele_balance.xlsx" class="text-indigo-600 underline text-sm"><i class="fas fa-download mr-1"></i>Télécharger un modèle Excel</a>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <form method="POST" action="action_balance.php" class="inline">
                            <input type="hidden" name="action" value="affecter">
                            <input type="hidden" name="entreprise_id" value="<?= $entreprise_id ?>">
                            <input type="hidden" name="exercice_id" value="<?= $exercice_id ?>">
                            <input type="hidden" name="declaration_id" value="<?= $declaration_id ?>">
                            <input type="hidden" name="periode" value="<?= htmlspecialchars($periode) ?>">
                            <button class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded" type="submit">
                                <i class="fas fa-cogs"></i> Affectation des comptes
                            </button>
                        </form>
                        <form method="POST" action="action_balance.php" class="inline" onsubmit="return confirm('Confirmer la remise à zéro de la balance ?');">
                            <input type="hidden" name="action" value="reset">
                            <input type="hidden" name="entreprise_id" value="<?= $entreprise_id ?>">
                            <input type="hidden" name="exercice_id" value="<?= $exercice_id ?>">
                            <input type="hidden" name="declaration_id" value="<?= $declaration_id ?>">
                            <input type="hidden" name="periode" value="<?= htmlspecialchars($periode) ?>">
                            <button class="btn bg-red-100 hover:bg-red-200 text-red-800 px-4 py-2 rounded" type="submit">
                                <i class="fas fa-undo"></i> Remettre à zéro
                            </button>
                        </form>
                        <a href="afficher_balance.php?entreprise_id=<?= $entreprise_id ?>&exercice_id=<?= $exercice_id ?>&declaration_id=<?= $declaration_id ?>&periode=<?= htmlspecialchars($periode) ?>"
                           class="btn bg-blue-100 hover:bg-blue-200 text-blue-800 px-4 py-2 rounded">
                            <i class="fas fa-table"></i> Voir la balance importée
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <div class="mt-6 flex gap-2">
                    <a href="import_balance.php" class="btn bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded">
                        <i class="fas fa-sync-alt"></i> Nouvelle importation
                    </a>
                    <a href="../index.php" class="btn bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>