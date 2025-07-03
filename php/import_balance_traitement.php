<?php
require_once 'auth.php';
require '../vendor/autoload.php'; // chemin correct pour PhpSpreadsheet
use PhpOffice\PhpSpreadsheet\IOFactory;

if (!isLoggedIn()) {
    redirect('../login.html');
}

$mysqli = $mysqli ?? null;
$entreprise_id  = $_POST['entreprise_id'] ?? '';
$exercice_id    = $_POST['exercice_id'] ?? '';
$declaration_id = $_POST['declaration_id'] ?? '';
$periode        = $_POST['periode'] ?? '';
$redirect_url   = "import_balance.php?entreprise_id=$entreprise_id&exercice_id=$exercice_id&declaration_id=$declaration_id&period=$periode";

// Sécurité & cohérence
$check = $mysqli->prepare("SELECT d.id FROM declarations d INNER JOIN exercices e ON d.exercice_id = e.id INNER JOIN entreprises en ON e.entreprise_id = en.id WHERE d.id=? AND d.exercice_id=? AND e.entreprise_id=?");
$check->bind_param("iii", $declaration_id, $exercice_id, $entreprise_id);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    $check->close();
    header("Location: $redirect_url&message=" . urlencode("Déclaration non cohérente !") . "&msg_type=danger");
    exit;
}
$check->close();

if (!isset($_FILES['balance_file']) || $_FILES['balance_file']['error'] !== UPLOAD_ERR_OK) {
    header("Location: $redirect_url&message=" . urlencode("Erreur de chargement du fichier.") . "&msg_type=danger");
    exit;
}

$ext = strtolower(pathinfo($_FILES['balance_file']['name'], PATHINFO_EXTENSION));
$tmpfile = $_FILES['balance_file']['tmp_name'];
$columns_expected = ['Compte', 'Intitulé', 'Débit', 'Crédit'];

// Remise à zéro (éviter doublons)
$stmt = $mysqli->prepare("DELETE FROM balances WHERE entreprise_id=? AND exercice_id=? AND declaration_id=? AND periode=?");
$stmt->bind_param("iiis", $entreprise_id, $exercice_id, $declaration_id, $periode);
$stmt->execute();
$stmt->close();

$comptes_importes = 0;
$error = '';

if ($ext === 'csv') {
    if (($handle = fopen($tmpfile, "r")) !== false) {
        // Lecture brute de la première ligne (pour BOM éventuel et séparateur)
        $firstLine = fgets($handle);
        $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine); // retire BOM UTF-8 si présent
        $sep = (substr_count($firstLine, ";") >= 3) ? ";" : ((substr_count($firstLine, ",") >= 3) ? "," : ((substr_count($firstLine, "\t") >= 3) ? "\t" : ";"));
        $header = array_map(function($v){ return trim(mb_strtolower($v)); }, str_getcsv($firstLine, $sep));

        // Validation stricte de l'en-tête (ignore casse, espaces, BOM)
        foreach ($columns_expected as $i => $col) {
            if (!isset($header[$i]) || $header[$i] !== mb_strtolower($col)) {
                fclose($handle);
                header("Location: $redirect_url&message=" . urlencode("En-tête du fichier incorrect. Attendu : " . implode(';', $columns_expected)) . "&msg_type=danger");
                exit;
            }
        }

        $line = 1;
        while (($data = fgetcsv($handle, 1000, $sep)) !== false) {
            $line++;
            if (count($data) < 4) {
                $error = "Ligne $line: nombre de colonnes insuffisant.";
                break;
            }
            $num_compte = trim($data[0]);
            $intitule   = trim($data[1]);
            $solde_d    = str_replace(',', '.', $data[2]);
            $solde_c    = str_replace(',', '.', $data[3]);
            if ($num_compte === '' && $intitule === '' && $solde_d === '' && $solde_c === '') continue; // ligne vide
            if (empty($num_compte) || empty($intitule)) {
                $error = "Ligne $line: compte ou intitulé manquant.";
                break;
            }
            if (!is_numeric($solde_d) && $solde_d !== '' && $solde_d !== null) {
                $error = "Ligne $line: Montant Débit non numérique.";
                break;
            }
            if (!is_numeric($solde_c) && $solde_c !== '' && $solde_c !== null) {
                $error = "Ligne $line: Montant Crédit non numérique.";
                break;
            }
            $solde = floatval($solde_d) - floatval($solde_c);
            $stmt = $mysqli->prepare("INSERT INTO balances (entreprise_id, exercice_id, declaration_id, periode, num_compte, intitule, solde_debit, solde_credit, solde) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisssddd", $entreprise_id, $exercice_id, $declaration_id, $periode, $num_compte, $intitule, $solde_d, $solde_c, $solde);
            $stmt->execute();
            $stmt->close();
            $comptes_importes++;
        }
        fclose($handle);

        if ($error) {
            header("Location: $redirect_url&message=" . urlencode($error) . "&msg_type=danger");
            exit;
        }
    }
} elseif (in_array($ext, ['xls', 'xlsx'])) {
    try {
        $spreadsheet = IOFactory::load($tmpfile);
        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $worksheet->toArray(null, true, true, true);

        if (empty($rows) || count($rows) < 2) {
            throw new Exception("Fichier Excel vide ou sans données.");
        }

        // Vérification stricte de l'en-tête
        $header = array_map(function($v){ return trim(mb_strtolower($v)); }, $rows[1]);
        foreach ($columns_expected as $i => $col) {
            $colLetter = chr(ord('A') + $i);
            if (!isset($header[$colLetter]) || $header[$colLetter] !== mb_strtolower($col)) {
                throw new Exception("En-tête du fichier incorrect. Attendu : " . implode(';', $columns_expected));
            }
        }

        foreach ($rows as $idx => $row) {
            if ($idx == 1) continue; // sauter l'en-tête
            $num_compte = trim($row['A'] ?? '');
            $intitule   = trim($row['B'] ?? '');
            $solde_d    = str_replace(',', '.', $row['C'] ?? '');
            $solde_c    = str_replace(',', '.', $row['D'] ?? '');
            if ($num_compte === '' && $intitule === '' && $solde_d === '' && $solde_c === '') continue; // ligne vide
            if (empty($num_compte) || empty($intitule)) {
                throw new Exception("Ligne $idx: compte ou intitulé manquant.");
            }
            if (!is_numeric($solde_d) && $solde_d !== '' && $solde_d !== null) {
                throw new Exception("Ligne $idx: Montant Débit non numérique.");
            }
            if (!is_numeric($solde_c) && $solde_c !== '' && $solde_c !== null) {
                throw new Exception("Ligne $idx: Montant Crédit non numérique.");
            }
            $solde = floatval($solde_d) - floatval($solde_c);
            $stmt = $mysqli->prepare("INSERT INTO balances (entreprise_id, exercice_id, declaration_id, periode, num_compte, intitule, solde_debit, solde_credit, solde) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iiisssddd", $entreprise_id, $exercice_id, $declaration_id, $periode, $num_compte, $intitule, $solde_d, $solde_c, $solde);
            $stmt->execute();
            $stmt->close();
            $comptes_importes++;
        }
    } catch(Exception $e) {
        header("Location: $redirect_url&message=" . urlencode("Erreur de lecture du fichier Excel : ".$e->getMessage()) . "&msg_type=danger");
        exit;
    }
} else {
    header("Location: $redirect_url&message=" . urlencode("Format non supporté: CSV ou Excel uniquement") . "&msg_type=danger");
    exit;
}

header("Location: $redirect_url&message=" . urlencode("Import terminé. Comptes importés : $comptes_importes") . "&msg_type=success");
exit;