<?php
require_once 'auth.php';

if (!isLoggedIn()) {
    redirect('../login.html');
}


$action         = $_POST['action'] ?? '';
$entreprise_id  = $_POST['entreprise_id'] ?? '';
$exercice_id    = $_POST['exercice_id'] ?? '';
$declaration_id = $_POST['declaration_id'] ?? '';
$periode        = $_POST['periode'] ?? '';

$redirect_url = "import_balance.php?entreprise_id=$entreprise_id&exercice_id=$exercice_id&declaration_id=$declaration_id&period=$periode";

// Sécurité : vérifier cohérence déclaration/exercice/entreprise
if (
    empty($declaration_id) || !is_numeric($declaration_id) ||
    empty($exercice_id) || !is_numeric($exercice_id) ||
    empty($entreprise_id) || !is_numeric($entreprise_id)
) {
    header("Location: $redirect_url&message=" . urlencode("Données manquantes ou invalides !") . "&msg_type=danger");
    exit;
}
$check = $mysqli->prepare("SELECT d.id FROM declarations d
    INNER JOIN exercices e ON d.exercice_id = e.id
    INNER JOIN entreprises en ON e.entreprise_id = en.id
    WHERE d.id=? AND d.exercice_id=? AND e.entreprise_id=?");
$check->bind_param("iii", $declaration_id, $exercice_id, $entreprise_id);
$check->execute();
$check->store_result();
if ($check->num_rows === 0) {
    $check->close();
    header("Location: $redirect_url&message=" . urlencode("Déclaration non cohérente avec l'exercice ou l'entreprise !") . "&msg_type=danger");
    exit;
}
$check->close();

// Action : remise à zéro de la balance
if ($action === 'reset') {
    $stmt = $mysqli->prepare("DELETE FROM balances WHERE entreprise_id=? AND exercice_id=? AND declaration_id=? AND periode=?");
    $stmt->bind_param("iiis", $entreprise_id, $exercice_id, $declaration_id, $periode);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
    header("Location: $redirect_url&message=" . urlencode("Balance remise à zéro. $affected lignes supprimées.") . "&msg_type=success");
    exit;
}

// Action : affectation des comptes
if ($action === 'affecter') {
    $sql = "UPDATE balances b
        JOIN correspondance_liasse c ON b.num_compte = c.num_compte
        SET b.code_liasse = c.code_liasse,
            b.libelle_liasse = c.libelle_liasse,
            b.colonne = c.colonne,
            b.affecte = 1
        WHERE b.entreprise_id=? AND b.exercice_id=? AND b.declaration_id=? AND b.periode=?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("iiis", $entreprise_id, $exercice_id, $declaration_id, $periode);
    $stmt->execute();
    $affected = $stmt->affected_rows;
    $stmt->close();
    header("Location: $redirect_url&message=" . urlencode("Affectation effectuée sur $affected comptes.") . "&msg_type=success");
    exit;
}

header("Location: $redirect_url&message=" . urlencode("Action inconnue.") . "&msg_type=danger");
exit;