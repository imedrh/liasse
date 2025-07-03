<?php
require_once 'auth.php';

if (!isLoggedIn()) {
    redirect('../login.html');
}


$entreprise_id  = $_GET['entreprise_id'] ?? '';
$exercice_id    = $_GET['exercice_id'] ?? '';
$declaration_id = $_GET['declaration_id'] ?? '';
$periode        = $_GET['periode'] ?? 'N';

// Sécurité : on ne lance l'affichage que si tout est cohérent
$check = $mysqli->prepare("SELECT d.id FROM declarations d INNER JOIN exercices e ON d.exercice_id = e.id INNER JOIN entreprises en ON e.entreprise_id = en.id WHERE d.id=? AND d.exercice_id=? AND e.entreprise_id=?");
$check->bind_param("iii", $declaration_id, $exercice_id, $entreprise_id);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    $check->close();
    die('<div class="p-4 text-red-700 bg-red-100 rounded">Données incohérentes ou accès interdit.</div>');
}
$check->close();

// Filtres dynamiques
$filtre = $_GET['filtre'] ?? 'tous'; // tous, non_affecte, affecte, code_liasse
$code_liasse = $_GET['code_liasse'] ?? '';

$sql = "SELECT * FROM balances WHERE entreprise_id=? AND exercice_id=? AND declaration_id=? AND periode=?";
$params = [$entreprise_id, $exercice_id, $declaration_id, $periode];
$types = "iiis";

if ($filtre === 'non_affecte') {
    $sql .= " AND affecte=0";
} elseif ($filtre === 'affecte') {
    $sql .= " AND affecte=1";
} elseif ($filtre === 'code_liasse' && $code_liasse) {
    $sql .= " AND code_liasse=?";
    $params[] = $code_liasse;
    $types .= "s";
}
$sql .= " ORDER BY num_compte";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$res = $stmt->get_result();

$balances = [];
while ($row = $res->fetch_assoc()) {
    $balances[] = $row;
}
$stmt->close();

// Pour liste des codes liasse uniques
$codes_liasse = [];
$res = $mysqli->prepare("SELECT DISTINCT code_liasse FROM balances WHERE entreprise_id=? AND exercice_id=? AND declaration_id=? AND periode=? AND code_liasse IS NOT NULL AND code_liasse != '' ORDER BY code_liasse");
$res->bind_param("iiis", $entreprise_id, $exercice_id, $declaration_id, $periode);
$res->execute();
$res2 = $res->get_result();
while ($row = $res2->fetch_assoc()) {
    $codes_liasse[] = $row['code_liasse'];
}
$res->close();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Balances importées</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto py-8">
        <div class="mb-5 flex flex-wrap gap-4 items-end">
            <h1 class="text-3xl font-bold text-indigo-800 flex-1">Balances importées</h1>
            <a href="import_balance.php?entreprise_id=<?= $entreprise_id ?>&exercice_id=<?= $exercice_id ?>&declaration_id=<?= $declaration_id ?>&periode=<?= htmlspecialchars($periode) ?>" class="px-4 py-2 rounded bg-gray-200 hover:bg-gray-300">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
        <form method="get" class="mb-4 flex gap-4 flex-wrap items-center">
            <input type="hidden" name="entreprise_id" value="<?= $entreprise_id ?>">
            <input type="hidden" name="exercice_id" value="<?= $exercice_id ?>">
            <input type="hidden" name="declaration_id" value="<?= $declaration_id ?>">
            <input type="hidden" name="periode" value="<?= htmlspecialchars($periode) ?>">

            <label class="font-medium">Filtrer&nbsp;:</label>
            <select name="filtre" onchange="this.form.submit()" class="px-2 py-1 rounded border">
                <option value="tous" <?= $filtre === 'tous' ? 'selected' : '' ?>>Tous</option>
                <option value="non_affecte" <?= $filtre === 'non_affecte' ? 'selected' : '' ?>>Non affectés</option>
                <option value="affecte" <?= $filtre === 'affecte' ? 'selected' : '' ?>>Affectés</option>
                <option value="code_liasse" <?= $filtre === 'code_liasse' ? 'selected' : '' ?>>Par code liasse</option>
            </select>
            <?php if ($filtre === 'code_liasse'): ?>
                <select name="code_liasse" onchange="this.form.submit()" class="px-2 py-1 rounded border">
                    <option value="">-- Code Liasse --</option>
                    <?php foreach ($codes_liasse as $cl): ?>
                        <option value="<?= htmlspecialchars($cl) ?>" <?= $cl === $code_liasse ? 'selected' : '' ?>><?= htmlspecialchars($cl) ?></option>
                    <?php endforeach ?>
                </select>
            <?php endif ?>
        </form>
        <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded shadow text-xs md:text-sm">
            <thead class="bg-indigo-100">
                <tr>
                    <th class="px-2 py-2 border-b text-left">Compte</th>
                    <th class="px-2 py-2 border-b text-left">Intitulé</th>
                    <th class="px-2 py-2 border-b text-right">Débit</th>
                    <th class="px-2 py-2 border-b text-right">Crédit</th>
                    <th class="px-2 py-2 border-b text-right">Solde</th>
                    <th class="px-2 py-2 border-b text-center">Affecté</th>
                    <th class="px-2 py-2 border-b text-center">Code Liasse</th>
                    <th class="px-2 py-2 border-b text-center">Libellé Liasse</th>
                    <th class="px-2 py-2 border-b text-center">Colonne</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$balances): ?>
                    <tr><td colspan="9" class="px-2 py-8 text-center text-gray-400">Aucun compte importé pour la sélection.</td></tr>
                <?php else: foreach ($balances as $b): ?>
                <tr class="<?= $b['affecte'] ? 'bg-green-50' : 'bg-red-50' ?>">
                    <td class="px-2 py-1 border-b"><?= htmlspecialchars($b['num_compte']) ?></td>
                    <td class="px-2 py-1 border-b"><?= htmlspecialchars($b['intitule']) ?></td>
                    <td class="px-2 py-1 border-b text-right"><?= number_format($b['solde_debit'], 2, ',', ' ') ?></td>
                    <td class="px-2 py-1 border-b text-right"><?= number_format($b['solde_credit'], 2, ',', ' ') ?></td>
                    <td class="px-2 py-1 border-b text-right"><?= number_format($b['solde'], 2, ',', ' ') ?></td>
                    <td class="px-2 py-1 border-b text-center">
                        <?php if ($b['affecte']): ?>
                            <span class="inline-block px-2 py-0.5 bg-green-200 text-green-900 rounded">Oui</span>
                        <?php else: ?>
                            <span class="inline-block px-2 py-0.5 bg-red-200 text-red-900 rounded">Non</span>
                        <?php endif ?>
                    </td>
                    <td class="px-2 py-1 border-b text-center"><?= htmlspecialchars($b['code_liasse']?? '') ?></td>
                    <td class="px-2 py-1 border-b text-center"><?= htmlspecialchars($b['libelle_liasse']?? '') ?></td>
                    <td class="px-2 py-1 border-b text-center"><?= htmlspecialchars($b['colonne']?? '') ?></td>
                </tr>
                <?php endforeach; endif ?>
            </tbody>
        </table>
        </div>
        <div class="mt-6">
            <a href="import_balance.php?entreprise_id=<?= $entreprise_id ?>&exercice_id=<?= $exercice_id ?>&declaration_id=<?= $declaration_id ?>&periode=<?= htmlspecialchars($periode) ?>" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">
                <i class="fas fa-arrow-left"></i> Retour à l'import
            </a>
        </div>
    </div>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</body>
</html>