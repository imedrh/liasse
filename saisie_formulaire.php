<?php
// saisie_formulaire.php - Page de saisie dynamique des formulaires de liasse

require_once 'php/auth.php';
require_once 'php/controllers/EntrepriseController.php';
require_once 'php/controllers/ExerciceController.php';
require_once 'php/controllers/DeclarationController.php';

$page_title = "Saisir manuellement une liasse "; // adapte le titre si besoin
include 'includes/header.php';

if (!isLoggedIn()) {
    redirect('login.html');
}

$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);
$user_id = $_SESSION['user_id'];

$entrepriseController = new EntrepriseController($mysqli);
$user_entreprises = $entrepriseController->getEntreprises($user_id);

$exerciceController = new ExerciceController($mysqli);
$user_exercices = $exerciceController->getExercices($user_id);

$declarationController = new DeclarationController($mysqli);
$user_declarations = $declarationController->getDeclarations($user_id);

// ... [Gardez ici vos définitions PHP $form_definitions inchangées, trop long pour répéter ici. Assurez-vous de garder la version complète et correcte de votre repo] ...
$form_definitions = [
    'F6001_Bilan_Actif' => [
        'id' => 'F6001_Bilan_Actif',
        'title' => 'F6001 - Bilan Actif',
        'description' => 'Saisie des éléments de l\'actif du bilan pour l\'exercice N et N-1, Brut, Amortissements/Provisions et Net.',
        'sections' => [
            // Section Actifs non courants (Brut)
            [
                'sectionTitle' => 'Actifs non courants (Brut)',
                'fields' => [
                    ['id' => 'F60010001', 'label' => 'Actifs non courants (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010002 + F60010031'],
                    ['id' => 'F60010002', 'label' => 'Actifs immobilisés (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010003 + F60010012 + F60010021'],
                    // Immobilisations Incorporelles (Brut)
                    ['id' => 'F60010003', 'label' => 'Immobilisations Incorporelles (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010004 + F60010005 + F60010006 + F60010007 + F60010008 + F60010009 + F60010010 + F60010011'],
                    ['id' => 'F60010004', 'label' => 'Investissement recherche et développement (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010005', 'label' => 'Concess. marque,brevet,licence,marque (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010006', 'label' => 'Logiciels (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010007', 'label' => 'Fonds commercial (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010008', 'label' => 'Droit au bail (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010009', 'label' => 'Autres Immobilisations Incorporelles (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010010', 'label' => 'Immobilisations Incorporelles en cours (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Immobilisations corporelles (Brut)
                    ['id' => 'F60010012', 'label' => 'Immobilisations corporelles (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010013 + F60010014 + F60010015 + F60010016 + F60010017 + F60010018 + F60010019 + F60010020'],
                    ['id' => 'F60010013', 'label' => 'Terrains (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010014', 'label' => 'Constructions (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010016', 'label' => 'Materiel de transport (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010017', 'label' => 'Autres Immobilisations Corporelles (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010018', 'label' => 'Immob. Corporelles en cours (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010020', 'label' => 'Immob. a statut juridique particulier (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Immobilisations Financieres (Brut)
                    ['id' => 'F60010021', 'label' => 'Immobilisations Financières (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010022 + F60010023 + F60010024 + F60010025 + F60010026 + F60010027 + F60010028 + F60010029 + F60010030'],
                    ['id' => 'F60010022', 'label' => 'Actions (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010023', 'label' => 'Autres creances rattach. a des participat. (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010024', 'label' => 'Creances rattach. a des stes en participat. (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010025', 'label' => 'Vers.a eff./titre de participation non liberes (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010026', 'label' => 'Titres immobilises (droit de propriete) (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010027', 'label' => 'Titres immobilises (droit de creance) (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010028', 'label' => 'Depots et cautionnements verses (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010029', 'label' => 'Autres creances immobilisees (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Actifs Non Courants (Brut)
                    ['id' => 'F60010031', 'label' => 'Autres Actifs Non Courants (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010032 + F60010033 + F60010034 + F60010035'],
                    ['id' => 'F60010032', 'label' => 'Frais preliminaires (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010033', 'label' => 'Charges a repartir (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010035', 'label' => 'ecarts de conversion (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Section Actifs courants (Brut)
            [
                'sectionTitle' => 'Actifs courants (Brut)',
                'fields' => [
                    ['id' => 'F60010036', 'label' => 'Actifs courants (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010037 + F60010044 + F60010050 + F60010059 + F60010064'],
                    // Stocks (Brut)
                    ['id' => 'F60010037', 'label' => 'Stocks (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010038 + F60010039 + F60010040 + F60010041 + F60010042 + F60010043'],
                    ['id' => 'F60010038', 'label' => 'Stocks Matieres Premieres et Fournit. Liees (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010039', 'label' => 'Stocks Autres Approvisionnements (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010040', 'label' => 'Stocks En-cours de production de biens (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010041', 'label' => 'Stocks En-cours de production services (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010042', 'label' => 'Stocks de produits (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010043', 'label' => 'Stocks de marchandises (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Clients et Comptes Rattaches (Brut)
                    ['id' => 'F60010044', 'label' => 'Clients et Comptes Rattaches (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010045 + F60010046 + F60010047 + F60010048 + F60010049'],
                    ['id' => 'F60010045', 'label' => 'Clients et comptes rattaches (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010046', 'label' => 'Clients - effets a recevoir (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010047', 'label' => 'Clients douteux ou litigieux (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010048', 'label' => 'Creances/travaux non encore facturables (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010049', 'label' => 'Clt-pdts non encore factures (pdt a recev.) (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Actifs Courants (Brut)
                    ['id' => 'F60010050', 'label' => 'Autres Actifs Courants (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010051 + F60010052 + F60010053 + F60010054 + F60010055 + F60010056 + F60010057 + F60010058'],
                    ['id' => 'F60010051', 'label' => 'Fournisseurs debiteurs (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010052', 'label' => 'Personnel et comptes rattaches (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010053', 'label' => 'etat et collectivites publiques (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010054', 'label' => 'Societes du groupe et associes (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010055', 'label' => 'Debiteurs divers et Crediteurs divers (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010056', 'label' => 'Comptes transitoires ou d\'attente (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010057', 'label' => 'Comptes de regularisation (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010058', 'label' => 'Autres (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Placements et Autres Actifs Financiers (Brut)
                    ['id' => 'F60010059', 'label' => 'Placements et Autres Actifs Financiers (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010060 + F60010061 + F60010062 + F60010063'],
                    ['id' => 'F60010060', 'label' => 'Prets et autres creances Fin. courants (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010061', 'label' => 'Placements courants (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010062', 'label' => 'Regies d\'avances et accreditifs (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010063', 'label' => 'Autres (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Liquidites et equivalents de liquidites (Brut)
                    ['id' => 'F60010064', 'label' => 'Liquidites et equivalents de liquidites (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010065 + F60010066'],
                    ['id' => 'F60010065', 'label' => 'Banques, etabl. Financiers et assimiles (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010066', 'label' => 'Caisse (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Autres Postes des Actifs du Bilan (Brut) et Total (Brut)
            [
                'sectionTitle' => 'Autres Postes et Total Actif (Brut)',
                'fields' => [
                    ['id' => 'F60010067', 'label' => 'Autres Postes des Actifs du Bilan (Brut)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60010068', 'label' => 'Total des actifs (Brut)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010001 + F60010036 + F60010067'],
                ]
            ],
            // Section Amortissement/Provision (N)
            [
                'sectionTitle' => 'Actifs non courants (Amortissement/Provision)',
                'fields' => [
                    ['id' => 'F60011001', 'label' => 'Actifs non courants (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011002 + F60011031'],
                    ['id' => 'F60011002', 'label' => 'Actifs immobilisés (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011003 + F60011012 + F60011021'],
                    // Immobilisations Incorporelles (Amortissement/Provision)
                    ['id' => 'F60011003', 'label' => 'Immobilisations Incorporelles (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011004 + F60011005 + F60011006 + F60011007 + F60011008 + F60011009 + F60011010 + F60011011'],
                    ['id' => 'F60011004', 'label' => 'Investissement recherche et developpement (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011005', 'label' => 'Concess. marque,brevet,licence,marque (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011006', 'label' => 'Logiciels (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011007', 'label' => 'Fonds commercial (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011008', 'label' => 'Droit au bail (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011009', 'label' => 'Autres Immobilisations Incorporelles (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011010', 'label' => 'Immobilisations Incorporelles en cours (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Immobilisations corporelles (Amortissement/Provision)
                    ['id' => 'F60011012', 'label' => 'Immobilisations corporelles (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011013 + F60011014 + F60011015 + F60011016 + F60011017 + F60011018 + F60011019 + F60011020'],
                    ['id' => 'F60011013', 'label' => 'Terrains (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011014', 'label' => 'Constructions (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011016', 'label' => 'Materiel de transport (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011017', 'label' => 'Autres Immobilisations Corporelles (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011018', 'label' => 'Immob. Corporelles en cours (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011020', 'label' => 'Immob. a statut juridique particulier (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Immobilisations Financieres (Amortissement/Provision)
                    ['id' => 'F60011021', 'label' => 'Immobilisations Financières (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011022 + F60011023 + F60011024 + F60011025 + F60011026 + F60011027 + F60011028 + F60011029 + F60011030'],
                    ['id' => 'F60011022', 'label' => 'Actions (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011023', 'label' => 'Autres creances rattach. a des participat. (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011024', 'label' => 'Creances rattach. a des stes en participat. (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011025', 'label' => 'Vers.a eff./titre de participation non liberes (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011026', 'label' => 'Titres immobilises (droit de propriete) (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011027', 'label' => 'Titres immobilises (droit de creance) (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011028', 'label' => 'Depots et cautionnements verses (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011029', 'label' => 'Autres creances immobilisees (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Actifs Non Courants (Amortissement/Provision)
                    ['id' => 'F60011031', 'label' => 'Autres Actifs Non Courants (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011032 + F60011033 + F60011034 + F60011035'],
                    ['id' => 'F60011032', 'label' => 'Frais preliminaires (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011033', 'label' => 'Charges a repartir (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011035', 'label' => 'ecarts de conversion (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Section Actifs courants (Amortissement/Provision)
            [
                'sectionTitle' => 'Actifs courants (Amortissement/Provision)',
                'fields' => [
                    ['id' => 'F60011036', 'label' => 'Actifs courants (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011037 + F60011044 + F60011050 + F60011059 + F60011064'],
                    // Stocks (Amortissement/Provision)
                    ['id' => 'F60011037', 'label' => 'Stocks (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011038 + F60011039 + F60011040 + F60011041 + F60011042 + F60011043'],
                    ['id' => 'F60011038', 'label' => 'Stocks Matieres Premieres et Fournit. Liees (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011039', 'label' => 'Stocks Autres Approvisionnements (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011040', 'label' => 'Stocks En-cours de production de biens (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011041', 'label' => 'Stocks En-cours de production services (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011042', 'label' => 'Stocks de produits (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011043', 'label' => 'Stocks de marchandises (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Clients et Comptes Rattaches (Amortissement/Provision)
                    ['id' => 'F60011044', 'label' => 'Clients et Comptes Rattaches (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011045 + F60011046 + F60011047 + F60011048 + F60011049'],
                    ['id' => 'F60011045', 'label' => 'Clients et comptes rattaches (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011046', 'label' => 'Clients - effets a recevoir (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011047', 'label' => 'Clients douteux ou litigieux (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011048', 'label' => 'Creances/travaux non encore facturables (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011049', 'label' => 'Clt-pdts non encore factures (pdt a recev.) (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Actifs Courants (Amortissement/Provision)
                    ['id' => 'F60011050', 'label' => 'Autres Actifs Courants (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011051 + F60011052 + F60011053 + F60011054 + F60011055 + F60011056 + F60011057 + F60011058'],
                    ['id' => 'F60011051', 'label' => 'Fournisseurs debiteurs (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011052', 'label' => 'Personnel et comptes rattaches (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011053', 'label' => 'etat et collectivites publiques (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011054', 'label' => 'Societes du groupe et associes (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011055', 'label' => 'Debiteurs divers et Crediteurs divers (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011056', 'label' => 'Comptes transitoires ou d\'attente (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011057', 'label' => 'Comptes de regularisation (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011058', 'label' => 'Autres (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Placements et Autres Actifs Financiers (Amortissement/Provision)
                    ['id' => 'F60011059', 'label' => 'Placements et Autres Actifs Financiers (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011060 + F60011061 + F60011062 + F60011063'],
                    ['id' => 'F60011060', 'label' => 'Prets et autres creances Fin. courants (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011061', 'label' => 'Placements courants (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011062', 'label' => 'Regies d\'avances et accreditifs (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011063', 'label' => 'Autres (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Liquidites et equivalents de liquidites (Amortissement/Provision)
                    ['id' => 'F60011064', 'label' => 'Liquidites et equivalents de liquidites (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011065 + F60011066'],
                    ['id' => 'F60011065', 'label' => 'Banques, etabl. Financiers et assimiles (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011066', 'label' => 'Caisse (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Autres Postes des Actifs du Bilan (Amortissement/Provision) et Total (Amortissement/Provision)
            [
                'sectionTitle' => 'Autres Postes et Total Actif (Amortissement/Provision)',
                'fields' => [
                    ['id' => 'F60011067', 'label' => 'Autres Postes des Actifs du Bilan (Amortissement/Provision)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60011068', 'label' => 'Total des actifs (Amortissement/Provision)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60011001 + F60011036 + F60011067'],
                ]
            ],
            // Section Actifs non courants (Net)
            [
                'sectionTitle' => 'Actifs non courants (Net)',
                'fields' => [
                    // Champs "Net" : Généralement "Brut - Amortissements/Provisions"
                    // F60012001 (Net) = F60010001 (Brut) - F60011001 (Amortissement/Provision)
                    ['id' => 'F60012001', 'label' => 'Actifs non courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010001 - F60011001'],
                    ['id' => 'F60012002', 'label' => 'Actifs immobilisés (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010002 - F60011002'],
                    ['id' => 'F60012003', 'label' => 'Immobilisations Incorporelles (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010003 - F60011003'],
                    ['id' => 'F60012004', 'label' => 'Investissement recherche et developpement (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010004 - F60011004'],
                    ['id' => 'F60012005', 'label' => 'Concess. marque,brevet,licence,marque (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010005 - F60011005'],
                    ['id' => 'F60012006', 'label' => 'Logiciels (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010006 - F60011006'],
                    ['id' => 'F60012007', 'label' => 'Fonds commercial (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010007 - F60011007'],
                    ['id' => 'F60012008', 'label' => 'Droit au bail (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010008 - F60011008'],
                    ['id' => 'F60012009', 'label' => 'Autres Immobilisations Incorporelles (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010009 - F60011009'],
                    ['id' => 'F60012010', 'label' => 'Immobilisations Incorporelles en cours (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010010 - F60011010'],
                    ['id' => 'F60012011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010011 - F60011011'],
                    ['id' => 'F60012012', 'label' => 'Immobilisations corporelles (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010012 - F60011012'],
                    ['id' => 'F60012013', 'label' => 'Terrains (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010013 - F60011013'],
                    ['id' => 'F60012014', 'label' => 'Constructions (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010014 - F60011014'],
                    ['id' => 'F60012015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010015 - F60011015'],
                    ['id' => 'F60012016', 'label' => 'Materiel de transport (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010016 - F60011016'],
                    ['id' => 'F60012017', 'label' => 'Autres Immobilisations Corporelles (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010017 - F60011017'],
                    ['id' => 'F60012018', 'label' => 'Immob. Corporelles en cours (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010018 - F60011018'],
                    ['id' => 'F60012019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010019 - F60011019'],
                    ['id' => 'F60012020', 'label' => 'Immob. a statut juridique particulier (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010020 - F60011020'],
                    ['id' => 'F60012021', 'label' => 'Immobilisations Financières (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010021 - F60011021'],
                    ['id' => 'F60012022', 'label' => 'Actions (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010022 - F60011022'],
                    ['id' => 'F60012023', 'label' => 'Autres creances rattach. a des participat. (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010023 - F60011023'],
                    ['id' => 'F60012024', 'label' => 'Creances rattach. a des stes en participat. (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010024 - F60011024'],
                    ['id' => 'F60012025', 'label' => 'Vers.a eff./titre de participation non liberes (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010025 - F60011025'],
                    ['id' => 'F60012026', 'label' => 'Titres immobilises (droit de propriete) (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010026 - F60011026'],
                    ['id' => 'F60012027', 'label' => 'Titres immobilises (droit de creance) (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010027 - F60011027'],
                    ['id' => 'F60012028', 'label' => 'Depots et cautionnements verses (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010028 - F60011028'],
                    ['id' => 'F60012029', 'label' => 'Autres creances immobilisees (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010029 - F60011029'],
                    ['id' => 'F60012030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010030 - F60011030'],
                    ['id' => 'F60012031', 'label' => 'Autres Actifs Non Courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010031 - F60011031'],
                    ['id' => 'F60012032', 'label' => 'Frais preliminaires (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010032 - F60011032'],
                    ['id' => 'F60012033', 'label' => 'Charges a repartir (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010033 - F60011033'],
                    ['id' => 'F60012034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010034 - F60011034'],
                    ['id' => 'F60012035', 'label' => 'ecarts de conversion (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010035 - F60011035'],
                ]
            ],
            // Section Actifs courants (Net)
            [
                'sectionTitle' => 'Actifs courants (Net)',
                'fields' => [
                    ['id' => 'F60012036', 'label' => 'Actifs courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010036 - F60011036'],
                    ['id' => 'F60012037', 'label' => 'Stocks (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010037 - F60011037'],
                    ['id' => 'F60012038', 'label' => 'Stocks Matieres Premieres et Fournit. Liees (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010038 - F60011038'],
                    ['id' => 'F60012039', 'label' => 'Stocks Autres Approvisionnements (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010039 - F60011039'],
                    ['id' => 'F60012040', 'label' => 'Stocks En-cours de production de biens (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010040 - F60011040'],
                    ['id' => 'F60012041', 'label' => 'Stocks En-cours de production services (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010041 - F60011041'],
                    ['id' => 'F60012042', 'label' => 'Stocks de produits (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010042 - F60011042'],
                    ['id' => 'F60012043', 'label' => 'Stocks de marchandises (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010043 - F60011043'],
                    ['id' => 'F60012044', 'label' => 'Clients et Comptes Rattaches (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010044 - F60011044'],
                    ['id' => 'F60012045', 'label' => 'Clients et comptes rattaches (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010045 - F60011045'],
                    ['id' => 'F60012046', 'label' => 'Clients - effets a recevoir (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010046 - F60011046'],
                    ['id' => 'F60012047', 'label' => 'Clients douteux ou litigieux (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010047 - F60011047'],
                    ['id' => 'F60012048', 'label' => 'Creances/travaux non encore facturables (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010048 - F60011048'],
                    ['id' => 'F60012049', 'label' => 'Clt-pdts non encore factures (pdt a recev.) (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010049 - F60011049'],
                    ['id' => 'F60012050', 'label' => 'Autres Actifs Courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010050 - F60011050'],
                    ['id' => 'F60012051', 'label' => 'Fournisseurs debiteurs (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010051 - F60011051'],
                    ['id' => 'F60012052', 'label' => 'Personnel et comptes rattaches (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010052 - F60011052'],
                    ['id' => 'F60012053', 'label' => 'etat et collectivites publiques (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010053 - F60011053'],
                    ['id' => 'F60012054', 'label' => 'Societes du groupe et associes (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010054 - F60011054'],
                    ['id' => 'F60012055', 'label' => 'Debiteurs divers et Crediteurs divers (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010055 - F60011055'],
                    ['id' => 'F60012056', 'label' => 'Comptes transitoires ou d\'attente (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010056 - F60011056'],
                    ['id' => 'F60012057', 'label' => 'Comptes de regularisation (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010057 - F60011057'],
                    ['id' => 'F60012058', 'label' => 'Autres (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010058 - F60011058'],
                    ['id' => 'F60012059', 'label' => 'Placements et Autres Actifs Financiers (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010059 - F60011059'],
                    ['id' => 'F60012060', 'label' => 'Prets et autres creances Fin. courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010060 - F60011060'],
                    ['id' => 'F60012061', 'label' => 'Placements courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010061 - F60011061'],
                    ['id' => 'F60012062', 'label' => 'Regies d\'avances et accreditifs (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010062 - F60011062'],
                    ['id' => 'F60012063', 'label' => 'Autres (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010063 - F60011063'],
                    ['id' => 'F60012064', 'label' => 'Liquidites et equivalents de liquidites (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010064 - F60011064'],
                    ['id' => 'F60012065', 'label' => 'Banques, etabl. Financiers et assimiles (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010065 - F60011065'],
                    ['id' => 'F60012066', 'label' => 'Caisse (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010066 - F60011066'],
                ]
            ],
            // Autres Postes des Actifs du Bilan (Net) et Total (Net)
            [
                'sectionTitle' => 'Autres Postes et Total Actif (Net)',
                'fields' => [
                    ['id' => 'F60012067', 'label' => 'Autres Postes des Actifs du Bilan (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010067 - F60011067'],
                    ['id' => 'F60012068', 'label' => 'Total des actifs (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60010068 - F60011068'],
                ]
            ],
            

           
             // Colonne N-1 (Net)
            [
                'sectionTitle' => 'Actifs non courants (Net N-1)',
                'fields' => [
                    // Champs "Net N-1" : Généralement "Brut N-1 - Amortissements/Provisions N-1"
                    ['id' => 'F60013001', 'label' => 'Actifs non courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015001 - F60014001'],
                    ['id' => 'F60013002', 'label' => 'Actifs immobilisés (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015002 - F60014002'],
                    ['id' => 'F60013003', 'label' => 'Immobilisations Incorporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015003 - F60014003'],
                    ['id' => 'F60013004', 'label' => 'Investissement recherche et developpement (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015004 - F60014004'],
                    ['id' => 'F60013005', 'label' => 'Concess. marque,brevet,licence,marque (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015005 - F60014005'],
                    ['id' => 'F60013006', 'label' => 'Logiciels (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015006 - F60014006'],
                    ['id' => 'F60013007', 'label' => 'Fonds commercial (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015007 - F60014007'],
                    ['id' => 'F60013008', 'label' => 'Droit au bail (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015008 - F60014008'],
                    ['id' => 'F60013009', 'label' => 'Autres Immobilisations Incorporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015009 - F60014009'],
                    ['id' => 'F60013010', 'label' => 'Immobilisations Incorporelles en cours (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015010 - F60014010'],
                    ['id' => 'F60013011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015011 - F60014011'],
                    ['id' => 'F60013012', 'label' => 'Immobilisations corporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015012 - F60014012'],
                    ['id' => 'F60013013', 'label' => 'Terrains (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015013 - F60014013'],
                    ['id' => 'F60013014', 'label' => 'Constructions (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015014 - F60014014'],
                    ['id' => 'F60013015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015015 - F60014015'],
                    ['id' => 'F60013016', 'label' => 'Materiel de transport (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015016 - F60014016'],
                    ['id' => 'F60013017', 'label' => 'Autres Immobilisations Corporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015017 - F60014017'],
                    ['id' => 'F60013018', 'label' => 'Immob. Corporelles en cours (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015018 - F60014018'],
                    ['id' => 'F60013019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015019 - F60014019'],
                    ['id' => 'F60013020', 'label' => 'Immob. a statut juridique particulier (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015020 - F60014020'],
                    ['id' => 'F60013021', 'label' => 'Immobilisations Financières (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015021 - F60014021'],
                    ['id' => 'F60013022', 'label' => 'Actions (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015022 - F60014022'],
                    ['id' => 'F60013023', 'label' => 'Autres creances rattach. a des participat. (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015023 - F60014023'],
                    ['id' => 'F60013024', 'label' => 'Creances rattach. a des stes en participat. (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015024 - F60014024'],
                    ['id' => 'F60013025', 'label' => 'Vers.a eff./titre de participation non liberes (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015025 - F60014025'],
                    ['id' => 'F60013026', 'label' => 'Titres immobilises (droit de propriete) (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015026 - F60014026'],
                    ['id' => 'F60013027', 'label' => 'Titres immobilises (droit de creance) (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015027 - F60014027'],
                    ['id' => 'F60013028', 'label' => 'Depots et cautionnements verses (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015028 - F60014028'],
                    ['id' => 'F60013029', 'label' => 'Autres creances immobilisees (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015029 - F60014029'],
                    ['id' => 'F60013030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015030 - F60014030'],
                    ['id' => 'F60013031', 'label' => 'Autres Actifs Non Courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015031 - F60014031'],
                    ['id' => 'F60013032', 'label' => 'Frais preliminaires (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015032 - F60014032'],
                    ['id' => 'F60013033', 'label' => 'Charges a repartir (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015033 - F60014033'],
                    ['id' => 'F60013034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015034 - F60014034'],
                    ['id' => 'F60013035', 'label' => 'ecarts de conversion (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015035 - F60014035'],
                ]
            ],
            // Actifs Courants (Net N-1)
            [
                'sectionTitle' => 'Actifs courants (Net N-1)',
                'fields' => [
                    ['id' => 'F60013036', 'label' => 'Actifs courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015036 - F60014036'],
                    ['id' => 'F60013037', 'label' => 'Stocks (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015037 - F60014037'],
                    ['id' => 'F60013038', 'label' => 'Stocks Matieres Premieres et Fournit. Liees (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015038 - F60014038'],
                    ['id' => 'F60013039', 'label' => 'Stocks Autres Approvisionnements (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015039 - F60014039'],
                    ['id' => 'F60013040', 'label' => 'Stocks En-cours de production de biens (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015040 - F60014040'],
                    ['id' => 'F60013041', 'label' => 'Stocks En-cours de production services (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015041 - F60014041'],
                    ['id' => 'F60013042', 'label' => 'Stocks de produits (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015042 - F60014042'],
                    ['id' => 'F60013043', 'label' => 'Stocks de marchandises (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015043 - F60014043'],
                    ['id' => 'F60013044', 'label' => 'Clients et Comptes Rattaches (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015044 - F60014044'],
                    ['id' => 'F60013045', 'label' => 'Clients et comptes rattaches (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015045 - F60014045'],
                    ['id' => 'F60013046', 'label' => 'Clients - effets a recevoir (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015046 - F60014046'],
                    ['id' => 'F60013047', 'label' => 'Clients douteux ou litigieux (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015047 - F60014047'],
                    ['id' => 'F60013048', 'label' => 'Creances/travaux non encore facturables (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015048 - F60014048'],
                    ['id' => 'F60013049', 'label' => 'Clt-pdts non encore factures (pdt a recev.) (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015049 - F60014049'],
                    ['id' => 'F60013050', 'label' => 'Autres Actifs Courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015050 - F60014050'],
                    ['id' => 'F60013051', 'label' => 'Fournisseurs debiteurs (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015051 - F60014051'],
                    ['id' => 'F60013052', 'label' => 'Personnel et comptes rattaches (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015052 - F60014052'],
                    ['id' => 'F60013053', 'label' => 'etat et collectivites publiques (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015053 - F60014053'],
                    ['id' => 'F60013054', 'label' => 'Societes du groupe et associes (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015054 - F60014054'],
                    ['id' => 'F60013055', 'label' => 'Debiteurs divers et Crediteurs divers (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015055 - F60014055'],
                    ['id' => 'F60013056', 'label' => 'Comptes transitoires ou d\'attente (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015056 - F60014056'],
                    ['id' => 'F60013057', 'label' => 'Comptes de regularisation (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015057 - F60014057'],
                    ['id' => 'F60013058', 'label' => 'Autres (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015058 - F60014058'],
                    ['id' => 'F60013059', 'label' => 'Placements et Autres Actifs Financiers (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015059 - F60014059'],
                    ['id' => 'F60013060', 'label' => 'Prets et autres creances Fin. courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015060 - F60014060'],
                    ['id' => 'F60013061', 'label' => 'Placements courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015061 - F60014061'],
                    ['id' => 'F60013062', 'label' => 'Regies d\'avances et accreditifs (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015062 - F60014062'],
                    ['id' => 'F60013063', 'label' => 'Autres (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015063 - F60014063'],
                    ['id' => 'F60013064', 'label' => 'Liquidites et equivalents de liquidites (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015064 - F60014064'],
                    ['id' => 'F60013065', 'label' => 'Banques, etabl. Financiers et assimiles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015065 - F60014065'],
                    ['id' => 'F60013066', 'label' => 'Caisse (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015066 - F60014066'],
                ]
            ],
            // Autres Postes et Total Actif (Net N-1)
            [
                'sectionTitle' => 'Autres Postes et Total Actif (Net N-1)',
                'fields' => [
                    ['id' => 'F60013067', 'label' => 'Autres Postes des Actifs du Bilan (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015067 - F60014067'],
                    ['id' => 'F60013068', 'label' => 'Total des actifs (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60015068 - F60014068'],
                ]
            ],
        ]
    ],

	
	'F6002_Bilan_Passif' => [
    'id' => 'F6002_Bilan_Passif',
    'title' => 'F6002 - Bilan Passif',
    'description' => 'Saisie des éléments du passif du bilan pour l\'exercice N et N-1.',
    'sections' => [
        // Section Capitaux Propres
        [
            'sectionTitle' => 'Capitaux propres',
            'fields' => [
                ['id' => 'F60020001', 'label' => 'Capitaux propres', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020006 + F60020007'],
                ['id' => 'F60020002', 'label' => 'Capital social', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020003', 'label' => 'Réserves', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020004', 'label' => 'Autres capitaux propres', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020005', 'label' => 'Résultats reportés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020006', 'label' => 'Capitaux propres avant résultat de l\'exercice', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020002 + F60020003 + F60020004 + F60020005'],
                ['id' => 'F60020007', 'label' => 'Résultat de l\'exercice', 'type' => 'number', 'required' => true, 'default' => 0.00]
            ]
        ],
        // Section Passifs Non Courants
        [
            'sectionTitle' => 'Passifs non courants',
            'fields' => [
                ['id' => 'F60020009', 'label' => 'Passifs non courants', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020010 + F60020019 + F60020022'],
                ['id' => 'F60020010', 'label' => 'Emprunts', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020011 + F60020012 + F60020013 + F60020014 + F60020015 + F60020016 + F60020017 + F60020018'],
                ['id' => 'F60020011', 'label' => 'Emprunts obligataires (assortis de sûretés)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020012', 'label' => 'Emprunts auprès d\'établissements financiers (assortis de sûretés)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020013', 'label' => 'Emprunts auprès d\'établissements financiers (assorti de sûretés)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020014', 'label' => 'Emprunts et dettes assorties de conditions particulières', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020015', 'label' => 'Emprunts non assortis de sûretés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020016', 'label' => 'Dettes rattachées à des participations', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020017', 'label' => 'Dépôts et cautionnements reçus', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020018', 'label' => 'Autres emprunts et dettes', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020019', 'label' => 'Autres Passifs Financiers', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020020 + F60020021'],
                ['id' => 'F60020020', 'label' => 'Ecarts de conversion', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020021', 'label' => 'Autres passifs financiers', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020022', 'label' => 'Provisions', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020023 + F60020024 + F60020025 + F60020026 + F60020027 + F60020028 + F60020029 + F60020030'],
                ['id' => 'F60020023', 'label' => 'Provisions pour risques', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020024', 'label' => 'Provisions pour charges à répartir sur plusieurs exercices', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020025', 'label' => 'Provisions pour retraites et obligations similaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020026', 'label' => 'Provisions d\'origine réglementaire', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020027', 'label' => 'Provisions pour impôts', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020028', 'label' => 'Provisions pour renouvellement des immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020029', 'label' => 'Provisions pour amortissement', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020030', 'label' => 'Autres provisions pour charges', 'type' => 'number', 'required' => true, 'default' => 0.00]
            ]
        ],
        // Section Passifs Courants
        [
            'sectionTitle' => 'Passifs courants',
            'fields' => [
                ['id' => 'F60020031', 'label' => 'Passifs courants', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020032 + F60020038 + F60020047'],
                ['id' => 'F60020032', 'label' => 'Fournisseurs et Comptes Rattachés', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020033 + F60020034 + F60020035 + F60020036 + F60020037'],
                ['id' => 'F60020033', 'label' => 'Fournisseurs d\'exploitation', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020034', 'label' => 'Fournisseurs d\'exploitation - effets à payer', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020035', 'label' => 'Fournisseurs d\'immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020036', 'label' => 'Fournisseurs d\'immobilisations - effets à payer', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020037', 'label' => 'Fournisseurs - factures non parvenues', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020038', 'label' => 'Autres passifs courants', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020039 + F60020040 + F60020041 + F60020042 + F60020043 + F60020044 + F60020045 + F60020046'],
                ['id' => 'F60020039', 'label' => 'Clients créditeurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020040', 'label' => 'Personnel & Comptes rattachés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020041', 'label' => 'Etat et collectivités publiques', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020042', 'label' => 'Sociétés du groupe et associés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020043', 'label' => 'Débiteurs divers et Créditeurs divers', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020044', 'label' => 'Comptes transitoires ou d\'attente', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020045', 'label' => 'Comptes de régularisation', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020046', 'label' => 'Provisions courantes pour risques et charges', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020047', 'label' => 'Concours Bancaires et Autres Passifs Financiers', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020048 + F60020049 + F60020050 + F60020051'],
                ['id' => 'F60020048', 'label' => 'Emprunts et autres dettes financières courants', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020049', 'label' => 'Emprunts échus et impayés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020050', 'label' => 'Intérêts courus', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020051', 'label' => 'Banques, établissements financiers et assimilés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020052', 'label' => 'Autres Postes des Capitaux Propres et Passifs du Bilan', 'type' => 'number', 'required' => true, 'default' => 0.00]
            ]
        ],
        // Section Total Passif
        [
            'sectionTitle' => 'Total Passif',
            'fields' => [
                ['id' => 'F60020008', 'label' => 'Total Passifs', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020015 + F60020037'],
                ['id' => 'F60020053', 'label' => 'Total des capitaux propres et passifs', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020001 + F60020008 + F60020031']
            ]
        ]
    ]
],
	'F6003_Etat_de_Resultat' => [
    'id' => 'F6003_Etat_de_Resultat',
    'title' => 'F6003 - Etat de Résultat',
    'description' => 'Saisie détaillée d\'etat de résultat pour l\'exercice N et N-1.',
    'sections' => [
      [
        'sectionTitle' => 'Produits d\'exploitation',
        'fields' => [
          ['id' => 'F60030001', 'label' => 'Produits d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030002 + F60030014 + F60030015'],
          ['id' => 'F60030002', 'label' => 'Revenus', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030003 + F60030006'],
          ['id' => 'F60030003', 'label' => 'Ventes nettes des marchandises', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030004 - F60030005'],
          ['id' => 'F60030004', 'label' => 'Ventes de Marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030005', 'label' => 'Rabais, Remises et Ristournes (3R) accordés/ventes de Marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030006', 'label' => 'Ventes nettes de la production', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030007 + F60030008 + F60030009 + F60030010 + F60030011 + F60030012 - F60030013'],
          ['id' => 'F60030007', 'label' => 'Ventes de Produits Finis', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030008', 'label' => 'Ventes de Produits Intermédiaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030009', 'label' => 'Ventes de Produits Résiduels', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030010', 'label' => 'Ventes des Travaux', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030011', 'label' => 'Ventes des Etudes et Prestations de Services', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030012', 'label' => 'Produits des Activités Annexes', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030013', 'label' => 'Rabais, Remises et Ristournes (3R) accordés/ventes de la Production', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030014', 'label' => 'Production immobilisée', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030015', 'label' => 'Autres produits d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030016 + F60030017 + F60030018 + F60030019'],
          ['id' => 'F60030016', 'label' => 'Produits divers ordin.(sans gains/cession immo.)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030017', 'label' => 'Subventions d\'exploitation et d\'équilibre', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030018', 'label' => 'Reprises sur amortissements et provisions', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030019', 'label' => 'Transferts de charges', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030020', 'label' => 'Charges d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030021 + F60030025 + F60030029 + F60030036 + F60030046 + F60030053'],
          ['id' => 'F60030021', 'label' => 'Variation stocks produits finis et encours', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030022 + F60030023 + F60030024'],
          ['id' => 'F60030022', 'label' => 'Variations des en-cours de production biens', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030023', 'label' => 'Variation des en-cours de production services', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030024', 'label' => 'Variation des stocks de produits', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030025', 'label' => 'Achats de marchandises consommées', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030026 - F60030027 + F60030028'],
          ['id' => 'F60030026', 'label' => 'Achats de marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030027', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus sur achats marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030028', 'label' => 'Variation des stocks de marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030029', 'label' => 'Achats d\'approvisionnements consommés', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030030 + F60030031 - F60030032 - F60030033 + F60030034 + F60030035'],
          ['id' => 'F60030030', 'label' => 'Achats stockés-Mat.Premières et Fournit. liées', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030031', 'label' => 'Achats stockés - Autres approvisionnements', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030032', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus/achats Mat.Premières et Fournit. liées', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030033', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus/achats autres approvisionnements', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030034', 'label' => 'Var.de stocks Mat.Premières et Fournitures', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030035', 'label' => 'Var.de stocks des autres approvisionnements', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030036', 'label' => 'Charges de personnel', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030037 + F60030038 + F60030039 + F60030040 + F60030041 + F60030042 + F60030043 + F60030044 + F60030045'],
          ['id' => 'F60030037', 'label' => 'Salaires et compléments de salaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030038', 'label' => 'Appointements et compléments d\'appoint.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030039', 'label' => 'Indemnités représentatives de frais', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030040', 'label' => 'Commissions au personnel', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030041', 'label' => 'Rémun.des administrateurs, gérants et associés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030042', 'label' => 'Ch.connexes sal., appoint., comm. et rémun.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030043', 'label' => 'Charges sociales légales', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030044', 'label' => 'Ch.PL/Modif.Compt.é imputer au Réslt de l\'exerc.ou Activ.abandonnée', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030045', 'label' => 'Autres charges de PL et autres charges sociales', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030046', 'label' => 'Dotations aux amortissements et aux provisions', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030047 + F60030048 + F60030049 + F60030050 + F60030051 + F60030052'],
          ['id' => 'F60030047', 'label' => 'Dot.amort. et prov.-Ch.ord.(autres que Fin.)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030048', 'label' => 'Dot. aux résorptions des charges reportées', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030049', 'label' => 'Dot.Prov. Risques et Charges d\'exploitation', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030050', 'label' => 'Dot.Prov.dépréc.immob. Incorp. et Corporelles', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030051', 'label' => 'Dot.Prov.dépréc.actifs courants (autres que Val.Mobil.de Placem. et équiv. de liquidités)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030052', 'label' => 'Dot.aux amort. et prov./Modif.Compt. é imputer au Réslt de l\'exerc. ou Activ. abandonnée', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030053', 'label' => 'Autres charges d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030054 + F60030055 + F60030056 + F60030057 + F60030058 + F60030059 + F60030060'],
          ['id' => 'F60030054', 'label' => 'Achats déétudes et prestations services (y compris achat de sous-traitance production)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030055', 'label' => 'Achats de matériel, équipements et travaux', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030056', 'label' => 'Achats non stockés non rattachés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030057', 'label' => 'Services extérieurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030058', 'label' => 'Autres services extérieurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030059', 'label' => 'Charges diverses ordinaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030060', 'label' => 'Impôts, taxes et versements assimilés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030061', 'label' => 'Resultat d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030001 - F60030020'],
          ['id' => 'F60030062', 'label' => 'Charges financières nettes', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030063 + F60030064'],
          ['id' => 'F60030063', 'label' => 'Charges financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030064', 'label' => 'Dot.amort. et provisions - charges financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030065', 'label' => 'Produits des placements', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030066 + F60030067 + F60030068'],
          ['id' => 'F60030066', 'label' => 'Produits financiers', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030067', 'label' => 'Reprise/prov.(é inscrire dans les pdts financ.)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030068', 'label' => 'Transferts de charges financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030069', 'label' => 'Autres gains ordinaires', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030070 + F60030071'],
          ['id' => 'F60030070', 'label' => 'Produits nets sur cessions d\'immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030071', 'label' => 'Autres gains/élém.non récurrents ou except.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030072', 'label' => 'Autres pertes ordinanires', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030073 + F60030074 + F60030075'],
          ['id' => 'F60030073', 'label' => 'Charges Nettes/cession immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030074', 'label' => 'Autres pertes/élém.non récurrents ou except.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030075', 'label' => 'Réduction de valeur', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030076', 'label' => 'Résultat des Activités Ordinaires avant Impôt', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030061 - F60030062 + F60030065 + F60030069 - F60030072'],
          ['id' => 'F60030077', 'label' => 'Impôt sur les bénéfices', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030078 + F60030079'],
          ['id' => 'F60030078', 'label' => 'Impôts/Bénéfices calculés/Résultat/activ./ ord.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030079', 'label' => 'Autres impôts/Bénéfice (régimes particuliers)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030080', 'label' => 'Résultat des Activités Ordinaires après Impôt', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030076 - F60030077'],
          ['id' => 'F60030081', 'label' => 'Elements extraordinanires (Gains/pertes)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030082 - F60030083'],
          ['id' => 'F60030082', 'label' => 'Gains extraordinaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030083', 'label' => 'Pertes extraordinaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030084', 'label' => 'Résultat net de l\'exercice', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030080 + F60030081'],
          ['id' => 'F60030085', 'label' => 'Effets des modif. Comptables (net d\'impôt)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030086 - F60030087'],
          ['id' => 'F60030086', 'label' => 'Effet positif/Modif.C.affectant Réslts Reportés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030087', 'label' => 'Effet négatif/Modif.C.affectant Réslts Reportés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030088', 'label' => 'Autres Postes des Comptes de Résultat', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030089', 'label' => 'Resultat apres modifications comptables', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030084 + F60030085 + F60030088']
        ]
      ],
      [
        'sectionTitle' => 'Exercice N-1',
        'fields' => [
          ['id' => 'F60030090', 'label' => 'Produits d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030091 + F60030103 + F60030104'],
          ['id' => 'F60030091', 'label' => 'Revenus', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030092 + F60030095'],
          ['id' => 'F60030092', 'label' => 'Ventes nettes des marchandises', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030093 - F60030094'],
          ['id' => 'F60030093', 'label' => 'Ventes de Marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030094', 'label' => 'Rabais, Remises et Ristournes (3R) accordés/ventes de Marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030095', 'label' => 'Ventes nettes de la production', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030096 + F60030097 + F60030098 + F60030099 + F60030100 + F60030101 - F60030102'],
          ['id' => 'F60030096', 'label' => 'Ventes de Produits Finis', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030097', 'label' => 'Ventes de Produits Intermédiaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030098', 'label' => 'Ventes de Produits Résiduels', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030099', 'label' => 'Ventes des Travaux', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030100', 'label' => 'Ventes des Etudes et Prestations de Services', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030101', 'label' => 'Produits des Activités Annexes', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030102', 'label' => 'Rabais, Remises et Ristournes (3R) accordés/ventes de la Production', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030103', 'label' => 'Production immobilisée', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030104', 'label' => 'Autres produits d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030105 + F60030106 + F60030107 + F60030108'],
          ['id' => 'F60030105', 'label' => 'Produits divers ordin.(sans gains/cession immo.)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030106', 'label' => 'Subventions d\'exploitation et d\'équilibre', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030107', 'label' => 'Reprises sur amortissements et provisions', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030108', 'label' => 'Transferts de charges', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030109', 'label' => 'Charges d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030110 + F60030114 + F60030118 + F60030125 + F60030135 + F60030142'],
          ['id' => 'F60030110', 'label' => 'Variation stocks produits finis et encours', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030111 + F60030112 + F60030113'],
          ['id' => 'F60030111', 'label' => 'Variations des en-cours de production biens', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030112', 'label' => 'Variation des en-cours de production services', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030113', 'label' => 'Variation des stocks de produits', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030114', 'label' => 'Achats de marchandises consommées', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030115 - F60030116 + F60030117'],
          ['id' => 'F60030115', 'label' => 'Achats de marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030116', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus sur achats marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030117', 'label' => 'Variation des stocks de marchandises', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030118', 'label' => 'Achats d\'approvisionnements consommés', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030119 + F60030120 - F60030121 - F60030122 + F60030123 + F60030124'],
          ['id' => 'F60030119', 'label' => 'Achats stockés-Mat.Premières et Fournit. liées', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030120', 'label' => 'Achats stockés - Autres approvisionnements', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030121', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus/achats Mat.Premières et Fournit. liées', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030122', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus/achats autres approvisionnements', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030123', 'label' => 'Var.de stocks Mat.Premières et Fournitures', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030124', 'label' => 'Var.de stocks des autres approvisionnements', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030125', 'label' => 'Charges de personnel', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030126 + F60030127 + F60030128 + F60030129 + F60030130 + F60030131 + F60030132 + F60030133 + F60030134'],
          ['id' => 'F60030126', 'label' => 'Salaires et compléments de salaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030127', 'label' => 'Appointements et compléments d\'appoint.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030128', 'label' => 'Indemnités représentatives de frais', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030129', 'label' => 'Commissions au personnel', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030130', 'label' => 'Rémun.des administrateurs, gérants et associés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030131', 'label' => 'Ch.connexes sal., appoint., comm. et rémun.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030132', 'label' => 'Charges sociales légales', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030133', 'label' => 'Ch.PL/Modif.Compt.é imputer au Réslt de l\'exerc.ou Activ.abandonnée', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030134', 'label' => 'Autres charges de PL et autres charges sociales', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030135', 'label' => 'Dotations aux amortissements et aux provisions', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030136 + F60030137 + F60030138 + F60030139 + F60030140 + F60030141'],
          ['id' => 'F60030136', 'label' => 'Dot.amort. et prov.-Ch.ord.(autres que Fin.)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030137', 'label' => 'Dot. aux résorptions des charges reportées', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030138', 'label' => 'Dot.Prov. Risques et Charges d\'exploitation', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030139', 'label' => 'Dot.Prov.dépréc.immob. Incorp. et Corporelles', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030140', 'label' => 'Dot.Prov.dépréc.actifs courants (autres que Val.Mobil.de Placem. et équiv. de liquidités)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030141', 'label' => 'Dot.aux amort. et prov./Modif.Compt. é imputer au Réslt de l\'exerc. ou Activ. abandonnée', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030142', 'label' => 'Autres charges d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030143 + F60030144 + F60030145 + F60030146 + F60030147 + F60030148 + F60030149'],
          ['id' => 'F60030143', 'label' => 'Achats déétudes et prestations services (y compris achat de sous-traitance production)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030144', 'label' => 'Achats de matériel, équipements et travaux', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030145', 'label' => 'Achats non stockés non rattachés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030146', 'label' => 'Services extérieurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030147', 'label' => 'Autres services extérieurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030148', 'label' => 'Charges diverses ordinaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030149', 'label' => 'Impôts, taxes et versements assimilés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030150', 'label' => 'Resultat d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030090 - F60030109'],
          ['id' => 'F60030151', 'label' => 'Charges financières nettes', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030152 + F60030153'],
          ['id' => 'F60030152', 'label' => 'Charges financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030153', 'label' => 'Dot.amort. et provisions - charges financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030154', 'label' => 'Produits des placements', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030155 + F60030156 + F60030157'],
          ['id' => 'F60030155', 'label' => 'Produits financiers', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030156', 'label' => 'Reprise/prov.(é inscrire dans les pdts financ.)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030157', 'label' => 'Transferts de charges financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030158', 'label' => 'Autres gains ordinaires', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030159 + F60030160'],
          ['id' => 'F60030159', 'label' => 'Produits nets sur cessions d\'immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030160', 'label' => 'Autres gains/élém.non récurrents ou except.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030161', 'label' => 'Autres pertes ordinanires', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030162 + F60030163 + F60030164'],
          ['id' => 'F60030162', 'label' => 'Charges Nettes/cession immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030163', 'label' => 'Autres pertes/élém.non récurrents ou except.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030164', 'label' => 'Réduction de valeur', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030165', 'label' => 'Résultat des Activités Ordinaires avant Impôt', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030150 - F60030151 + F60030154 + F60030158 - F60030161'],
          ['id' => 'F60030166', 'label' => 'Impôt sur les bénéfices', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030167 + F60030168'],
          ['id' => 'F60030167', 'label' => 'Impôts/Bénéfices calculés/Résultat/activ./ ord.', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030168', 'label' => 'Autres impôts/Bénéfice (régimes particuliers)', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030169', 'label' => 'Résultat des Activités Ordinaires après Impôt', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030165 - F60030166'],
          ['id' => 'F60030170', 'label' => 'Elements extraordinanires (Gains/pertes)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030171 - F60030172'],
          ['id' => 'F60030171', 'label' => 'Gains extraordinaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030172', 'label' => 'Pertes extraordinaires', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030173', 'label' => 'Résultat net de l\'exercice', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030169 + F60030170'],
          ['id' => 'F60030174', 'label' => 'Effets des modif. Comptables (net d\'impôt)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030175 - F60030176'],
          ['id' => 'F60030175', 'label' => 'Effet positif/Modif.C.affectant Réslts Reportés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030176', 'label' => 'Effet négatif/Modif.C.affectant Réslts Reportés', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030177', 'label' => 'Autres Postes des Comptes de Résultat', 'type' => 'number', 'required' => true, 'default' => 0.00],
          ['id' => 'F60030178', 'label' => 'Resultat apres modifications comptables', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030173 + F60030174 + F60030177']
        ]
      ]
    ]
],

 'F6004_Etat_Flux_de_Tresorerie_Modèle_de_Référence' => [
    'id' => 'F6004_Etat_Flux_de_Tresorerie_Modèle_de_Référence',
    'title' => 'F6004 - Etat des flux de trésorerie (Modèle de Référence)',
    'description' => 'Saisie détaillée d\'etat des flux de trésorerie pour l\'exercice N et N-1.',
    'sections' => [
      [
        'sectionTitle' => 'Flux de trésorerie liés à l\'exploitation (Exercice N)',
        'fields' => [
          ['id' => 'F60040001', 'label' => 'Flux de trésorerie liés à l\'exploitation', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040002" - "F60040012" - "F60040023" - "F60040032" - "F60040045"'],
          ['id' => 'F60040002', 'label' => 'Encaissements reçus des clients', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040003" - "F60040004" + "F60040005" + "F60040006" - "F60040007" + "F60040008" - "F60040009" - "F60040010" + "F60040011"'],
          ['id' => 'F60040003', 'label' => 'S.Débiteurs Clts et Rattachés et Regul.bruts en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040004', 'label' => 'S.Créditeurs Clts et Rattachés et Regul.bruts en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040005', 'label' => 'Ventes TTC', 'type' => 'number', 'required' => true],
          ['id' => 'F60040006', 'label' => 'Ajustements des ventes des exercices antérieurs', 'type' => 'number', 'required' => true],
          ['id' => 'F60040007', 'label' => 'Créances clients passées en pertes', 'type' => 'number', 'required' => true],
          ['id' => 'F60040008', 'label' => 'Gains de change sur créances clients en devises', 'type' => 'number', 'required' => true],
          ['id' => 'F60040009', 'label' => 'Pertes de change sur créances client en devises', 'type' => 'number', 'required' => true],
          ['id' => 'F60040010', 'label' => 'S.Débiteurs Clts et Rattachés et Regul.bruts en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040011', 'label' => 'S.Créditeurs Clts et Rattachés et Regul.bruts en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040012', 'label' => 'Sommes versées aux fournisseurs (d\'exploitation)', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040013" - "F60040014" + "F60040015" + "F60040016" + "F60040017" - "F60040018" + "F60040019" - "F60040020" + "F60040021" - "F60040022"'],
          ['id' => 'F60040013', 'label' => 'S.Créditeurs Frs Expl. et Rattachés et Regul. en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040014', 'label' => 'S.Débiteurs Frs Expl. et Rattachés et Regul. en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040015', 'label' => 'S.C. Etat, RaS et autres I et T/Ch.Exploitation en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040016', 'label' => 'Achats TTC', 'type' => 'number', 'required' => true],
          ['id' => 'F60040017', 'label' => 'Ajustements des achats des exercices antérieurs', 'type' => 'number', 'required' => true],
          ['id' => 'F60040018', 'label' => 'Gains de change sur dettes Frs Expl. en devises', 'type' => 'number', 'required' => true],
          ['id' => 'F60040019', 'label' => 'Pertes de change sur dettes Frs Expl. en devises', 'type' => 'number', 'required' => true],
          ['id' => 'F60040020', 'label' => 'S.Créditeurs Frs Expl. et Rattachés et Regul. en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040021', 'label' => 'S.Débiteurs Frs Expl. et Rattachés et Regul. en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040022', 'label' => 'S.C. Etat, RaS et autres I et T/Ch.Exploitation en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040023', 'label' => 'Sommes versées au personnel', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040024" - "F60040025" + "F60040026" + "F60040027" + "F60040028" - "F60040029" + "F60040030" + "F60040031"'],
          ['id' => 'F60040024', 'label' => 'S.Créditeurs PL(Org.Sociaux) et Liés et Regul.en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040025', 'label' => 'S.Débiteurs PL(Org.Sociaux) et Liés et Regul.en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040026', 'label' => 'S.C. Etat, RaS et autres I et T/Ch.Au personnel en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040027', 'label' => 'Charges de personnel de l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040028', 'label' => 'Ajustements des charges de personnel des exercices antérieurs', 'type' => 'number', 'required' => true],
          ['id' => 'F60040029', 'label' => 'S.Créditeurs PL(Org.Sociaux) et Liés et Regul.en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040030', 'label' => 'S.Débiteurs PL(Org.Sociaux) et Liés et Regul.en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040031', 'label' => 'S.C. Etat, RaS et autres I et T/Ch.Au personnel en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040032', 'label' => 'Intérêts payés', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040033" - "F60040034" + "F60040035" + "F60040036" + "F60040037" - "F60040038" - "F60040039" - "F60040040" + "F60040041" - "F60040042" + "F60040043" - "F60040044"'],
          ['id' => 'F60040033', 'label' => 'S.C. Intérêts dus et Rattachés et Regul à Payer en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040034', 'label' => 'S.D. Intérêts comptes de Regul.d\'Avance en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040035', 'label' => 'S.C. Etat, RaS/Revenus de Capitaux en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040036', 'label' => 'Charges Financières de l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040037', 'label' => 'Ajustements des charges d\'intérêt des exercices antérieurs', 'type' => 'number', 'required' => true],
          ['id' => 'F60040038', 'label' => 'Frais d\'émission d\'emprunt', 'type' => 'number', 'required' => true],
          ['id' => 'F60040039', 'label' => 'Dot.Resorp. Frais d\'émission et Primes de Rembours.Empts', 'type' => 'number', 'required' => true],
          ['id' => 'F60040040', 'label' => 'Dot.Prov. Risques et charges financiers', 'type' => 'number', 'required' => true],
          ['id' => 'F60040041', 'label' => 'Reprise/Prov. Risques et charges financiers', 'type' => 'number', 'required' => true],
          ['id' => 'F60040042', 'label' => 'S.C. Intérêts dus et Rattachés et Regul.a Payer en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040043', 'label' => 'S.D. Intérêts Comptes de Regul.d\'Avance en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040044', 'label' => 'S.C. Etat, RaS/Revenus de Capitaux en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040045', 'label' => 'Impôts et taxes payés', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040046" - "F60040047" - "F60040048" + "F60040049" + "F60040050" + "F60040051" - "F60040052" + "F60040053" + "F60040054" - "F60040055" + "F60040056" + "F60040057"'],
          ['id' => 'F60040046', 'label' => 'S.Créditeurs (Etat, impôts et taxes) en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040047', 'label' => 'S.Débiteurs (Etat, Impôts et taxes) en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040048', 'label' => 'S.C.Impôt/Résultat différé (+)actif(-)-passif en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040049', 'label' => 'Impôt sur les Résultats (69)', 'type' => 'number', 'required' => true],
          ['id' => 'F60040050', 'label' => 'Impôts et Taxes de l\'exercice (66)', 'type' => 'number', 'required' => true],
          ['id' => 'F60040051', 'label' => 'TVA et autres Taxes/B et S Hors exploitation', 'type' => 'number', 'required' => true],
          ['id' => 'F60040052', 'label' => 'Impôt/Résultat diffère (+)actif(-)-passif constaté durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040053', 'label' => 'Impôts et Taxes portés en Modif.Compt.(cpt128)', 'type' => 'number', 'required' => true],
          ['id' => 'F60040054', 'label' => 'Impôt/Résultat a(+)/liquider(-);Imputer contre en Modif.Compt.', 'type' => 'number', 'required' => true],
          ['id' => 'F60040055', 'label' => 'S.Créditeurs (Etat, Impôt et taxes) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040056', 'label' => 'S.Débiteurs (Etat, Impôt et taxes) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040057', 'label' => 'S.C.Impôt/Résultat différé (+)actif(-)-passif en fin d\'exercice', 'type' => 'number', 'required' => true]
        ]
      ],
      [
        'sectionTitle' => 'Flux de trésorerie liés aux activités d\'investissement (Exercice N)',
        'fields' => [
          ['id' => 'F60040058', 'label' => 'Flux de trésorerie liés aux activités d\'investissement', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"-F60040059" + "F60040066" - "F60040080" + "F60040084"'],
          ['id' => 'F60040059', 'label' => 'Décaissements liés aux immo. Corporelles et incorporelles', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040060" + "F60040061" + "F60040062" + "F60040063"'],
          ['id' => 'F60040060', 'label' => 'S.C.FRS d\'Invest. et Rattachés et Regul. en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040061', 'label' => 'S.C. Etat, RaS operee/plus value immobiliere en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040062', 'label' => 'Valeurs brutes des Invest. d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040063', 'label' => 'TVA payee/Investissements de l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040064', 'label' => 'S.C. FRS d\'Invest. et Rattachés et Regul. en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040065', 'label' => 'S.C. Etat, RaS operee/plus value immobiliere en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040066', 'label' => 'Encaissements liés aux immo. Corporelles et incorporelles', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040067" + "F60040068" - "F60040069" - "F60040070" + "F60040071" - "F60040072" - "F60040073" + "F60040074" - "F60040075" - "F60040076" + "F60040077" + "F60040078" - "F60040079"'],
          ['id' => 'F60040067', 'label' => 'S.D. Immo.Corporelles et incorporelles en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040068', 'label' => 'S.D. Débiteurs et autres Créances TTC / cession des immo en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040069', 'label' => 'S.C.TVA collectee/cession investissements en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040070', 'label' => 'S.C.TVA a reverser/cession investissements en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040071', 'label' => 'S.D.Etat, RaS supportee/plus value immobiliere en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040072', 'label' => 'TVA a reverser/cession d\'invest. durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040073', 'label' => 'Produits nets/Cession des invest.-t-TVA a reverser comprise) durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040074', 'label' => 'Charges Nettes/Cession des invest. durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040075', 'label' => 'S.D. Immo.Corporelles et incorporelles en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040076', 'label' => 'S.D.Débiteurs et autres Créances TTC / cession des invest. en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040077', 'label' => 'S.C. TVA collectee/cession investissements en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040078', 'label' => 'S.C.TVA a reverser/cession investissements en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040079', 'label' => 'S.D.Etat, RaS supportee/plus value immobiliere en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040080', 'label' => 'Décaissements liés aux immobilisations financières', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040081" + "F60040082" - "F60040083"'],
          ['id' => 'F60040081', 'label' => 'Dettes/acquisition d\'immo. Financières en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040082', 'label' => 'Valeur brute des titres acquis durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040083', 'label' => 'Dettes/acquisition d\'immo. Finan. en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040084', 'label' => 'Encaissements liés aux immobilisations financières', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040085" + "F60040086" + "F60040087" - "F60040088"'],
          ['id' => 'F60040085', 'label' => 'Créances sur cessions d\'immo Fin. en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040086', 'label' => 'Cessions /immo financières durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040087', 'label' => 'Remboursements/immo Financières durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040088', 'label' => 'Créances sur cessions d\'immo financ. en fin d\'exercice', 'type' => 'number', 'required' => true]
        ]
      ],
      [
        'sectionTitle' => 'Flux de trésorerie liés aux activités de financement (Exercice N)',
        'fields' => [
          ['id' => 'F60040089', 'label' => 'Flux de trésorerie liés aux activités de financement', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040090" + "F60040097" + "F60040103" - "F60040108"'],
          ['id' => 'F60040090', 'label' => 'Encaissements suite à l\'émission d\'actions', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040091" + "F60040092" - "F60040093" - "F60040094" - "F60040095" - "F60040096"'],
          ['id' => 'F60040091', 'label' => 'Capital en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040092', 'label' => 'Primes liées au capital en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040093', 'label' => 'Augmentations du capital durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040094', 'label' => 'Conversion de dettes en capital durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040095', 'label' => 'Capital en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040096', 'label' => 'Primes liées au capital en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040097', 'label' => 'Dividendes et autres distributions', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040098" + "F60040099" + "F60040100" + "F60040101" - "F60040102"'],
          ['id' => 'F60040098', 'label' => 'Dividendes dus aux actionnaires en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040099', 'label' => 'Dividendes attribués en (N)', 'type' => 'number', 'required' => true],
          ['id' => 'F60040100', 'label' => 'Prélèvements sur les réserves en (N)', 'type' => 'number', 'required' => true],
          ['id' => 'F60040101', 'label' => 'Rachat d\'actions et autres réductions de capital en (N)', 'type' => 'number', 'required' => true],
          ['id' => 'F60040102', 'label' => 'Dividendes dus aux actionnaires en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040103', 'label' => 'Encaissements/remboursements d\'emprunts', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040104" + "F60040105" - "F60040106" - "F60040107"'],
          ['id' => 'F60040104', 'label' => 'S.C. (Emprunts et dettes assimilées) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040105', 'label' => 'S.C. (Emprunts courants) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040106', 'label' => 'S.C. (Emprunts et dettes assimilées) en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040107', 'label' => 'S.C. (Emprunts courants) en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040108', 'label' => 'Décaissements/remboursements de prêts et des placements', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040109" + "F60040110" - "F60040111" - "F60040112"'],
          ['id' => 'F60040109', 'label' => 'S.D.(Prêts et Créances Fin. courants) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040110', 'label' => 'S.D. (Placements Courants) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040111', 'label' => 'S.D.(Prêts et Créances Fin. courants) en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040112', 'label' => 'S.D. (Placements Courants) en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60040113', 'label' => 'Incidences des variations des taux de change/les liquidités et equiv.', 'type' => 'number', 'required' => true],
          ['id' => 'F60040114', 'label' => 'Autres Postes des Flux de Trésorerie', 'type' => 'number', 'required' => true],
          ['id' => 'F60040115', 'label' => 'Variation de Trésorerie', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60040001" + "F60040058" + "F60040089" + "F60040113" + "F60040114"'],
          ['id' => 'F60040116', 'label' => 'Trésorerie au début de la période', 'type' => 'number', 'required' => true],
          ['id' => 'F60040117', 'label' => 'Trésorerie à la clôture de la période', 'type' => 'number', 'required' => true]
        ]
      ],
      [
        'sectionTitle' => 'Flux de trésorerie liés à l\'exploitation (Exercice N-1)',
        'fields' => [
          ['id' => 'F60041001', 'label' => 'Flux de trésorerie liés à l\'exploitation', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041002" - "F60041012" - "F60041023" - "F60041032" - "F60041045"'],
          ['id' => 'F60041002', 'label' => 'Encaissements reçus des clients', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041003" - "F60041004" + "F60041005" + "F60041006" - "F60041007" + "F60041008" - "F60041009" - "F60041010" + "F60041011"'],
          ['id' => 'F60041003', 'label' => 'S.Débiteurs Clts et Rattachés et Regul.bruts en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041004', 'label' => 'S.Créditeurs Clts et Rattachés et Regul.bruts en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041005', 'label' => 'Ventes TTC', 'type' => 'number', 'required' => true],
          ['id' => 'F60041006', 'label' => 'Ajustements des ventes des exercices antérieurs', 'type' => 'number', 'required' => true],
          ['id' => 'F60041007', 'label' => 'Créances clients passées en pertes', 'type' => 'number', 'required' => true],
          ['id' => 'F60041008', 'label' => 'Gains de change sur créances clients en devises', 'type' => 'number', 'required' => true],
          ['id' => 'F60041009', 'label' => 'Pertes de change sur créances client en devises', 'type' => 'number', 'required' => true],
          ['id' => 'F60041010', 'label' => 'S.Débiteurs Clts et Rattachés et Regul.bruts en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041011', 'label' => 'S.Créditeurs Clts et Rattachés et Regul.bruts en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041012', 'label' => 'Sommes versées aux fournisseurs (d\'exploitation)', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041013" - "F60041014" + "F60041015" + "F60041016" + "F60041017" - "F60041018" + "F60041019" - "F60041020" + "F60041021" - "F60041022"'],
          ['id' => 'F60041013', 'label' => 'S.Créditeurs Frs Expl. et Rattachés et Regul. en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041014', 'label' => 'S.Débiteurs Frs Expl. et Rattachés et Regul. en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041015', 'label' => 'S.C. Etat, RaS et autres I et T/Ch.Exploitation en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041016', 'label' => 'Achats TTC', 'type' => 'number', 'required' => true],
          ['id' => 'F60041017', 'label' => 'Ajustements des achats des exercices antérieurs', 'type' => 'number', 'required' => true],
          ['id' => 'F60041018', 'label' => 'Gains de change sur dettes Frs Expl. en devises', 'type' => 'number', 'required' => true],
          ['id' => 'F60041019', 'label' => 'Pertes de change sur dettes Frs Expl. en devises', 'type' => 'number', 'required' => true],
          ['id' => 'F60041020', 'label' => 'S.Créditeurs Frs Expl. et Rattachés et Regul. en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041021', 'label' => 'S.Débiteurs Frs Expl. et Rattachés et Regul. en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041022', 'label' => 'S.C. Etat, RaS et autres I et T/Ch.Exploitation en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041023', 'label' => 'Sommes versées au personnel', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041024" - "F60041025" + "F60041026" + "F60041027" + "F60041028" - "F60041029" + "F60041030" + "F60041031"'],
          ['id' => 'F60041024', 'label' => 'S.Créditeurs PL(Org.Sociaux) et Liés et Regul.en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041025', 'label' => 'S.Débiteurs PL(Org.Sociaux) et Liés et Regul.en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041026', 'label' => 'S.C. Etat, RaS et autres I et T/Ch.du personnel en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041027', 'label' => 'Charges de personnel de l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041028', 'label' => 'Ajustements des charges de personnel des exercices antérieurs', 'type' => 'number', 'required' => true],
          ['id' => 'F60041029', 'label' => 'S.Créditeurs PL(Org.Sociaux) et Liés et Regul.en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041030', 'label' => 'S.Débiteurs PL(Org.Sociaux) et Liés et Regul.en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041031', 'label' => 'S.C. Etat, RaS et autres I et T/Ch.du personnel en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041032', 'label' => 'Intérêts payés', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041033" - "F60041034" + "F60041035" + "F60041036" + "F60041037" - "F60041038" - "F60041039" - "F60041040" + "F60041041" - "F60041042" + "F60041043" - "F60041044"'],
          ['id' => 'F60041033', 'label' => 'S.C. Intérêts dus et Rattachés et Regul à Payer en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041034', 'label' => 'S.D. Intérêts comptes de Regul.d\'Avance en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041035', 'label' => 'S.C. Etat, RaS/Revenus de Capitaux en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041036', 'label' => 'Charges Financières de l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041037', 'label' => 'Ajustements des charges d\'intérêt des exercices antérieurs', 'type' => 'number', 'required' => true],
          ['id' => 'F60041038', 'label' => 'Frais d\'émission d\'emprunt', 'type' => 'number', 'required' => true],
          ['id' => 'F60041039', 'label' => 'Dot.Resorp. Frais d\'émission et Primes de Rembours.Empts', 'type' => 'number', 'required' => true],
          ['id' => 'F60041040', 'label' => 'Dot.Prov. Risques et charges financiers', 'type' => 'number', 'required' => true],
          ['id' => 'F60041041', 'label' => 'Reprise/Prov. Risques et charges financiers', 'type' => 'number', 'required' => true],
          ['id' => 'F60041042', 'label' => 'S.C. Intérêts dus et Rattachés et Regul.a Payer en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041043', 'label' => 'S.D. Intérêts Comptes de Regul.d\'Avance en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041044', 'label' => 'S.C. Etat, RaS/Revenus de Capitaux en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041045', 'label' => 'Impôts et taxes payés', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041046" - "F60041047" - "F60041048" + "F60041049" + "F60041050" + "F60041051" - "F60041052" + "F60041053" + "F60041054" - "F60041055" + "F60041056" + "F60041057"'],
          ['id' => 'F60041046', 'label' => 'S.Créditeurs (Etat, impôts et taxes) en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041047', 'label' => 'S.Débiteurs (Etat, Impôts et taxes) en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041048', 'label' => 'S.C.Impôt/Résultat différé (+)actif(-)-passif en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041049', 'label' => 'Impôt sur les Résultats (69)', 'type' => 'number', 'required' => true],
          ['id' => 'F60041050', 'label' => 'Impôts et Taxes de l\'exercice (66)', 'type' => 'number', 'required' => true],
          ['id' => 'F60041051', 'label' => 'TVA et autres Taxes/B et S Hors exploitation', 'type' => 'number', 'required' => true],
          ['id' => 'F60041052', 'label' => 'Impôt/Résultat diffère (+)actif(-)-passif constaté durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041053', 'label' => 'Impôts et Taxes portés en Modif.Compt.(cpt128)', 'type' => 'number', 'required' => true],
          ['id' => 'F60041054', 'label' => 'Impôt/Résultat a(+)/liquider(-);Imputer contre en Modif.Compt.', 'type' => 'number', 'required' => true],
          ['id' => 'F60041055', 'label' => 'S.Créditeurs (Etat, Impôt et taxes) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041056', 'label' => 'S.Débiteurs (Etat, Impôt et taxes) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041057', 'label' => 'S.C.Impôt/Résultat différé (+)actif(-)-passif en fin d\'exercice', 'type' => 'number', 'required' => true]
        ]
      ],
      [
        'sectionTitle' => 'Flux de trésorerie liés aux activités d\'investissement (Exercice N-1)',
        'fields' => [
          ['id' => 'F60041058', 'label' => 'Flux de trésorerie liés aux activités d\'investissement', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"-F60041059" + "F60041066" - "F60041080" + "F60041084"'],
          ['id' => 'F60041059', 'label' => 'Décaissements liés aux immo. Corporelles et incorporelles', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041060" + "F60041061" + "F60041062" + "F60041063"'],
          ['id' => 'F60041060', 'label' => 'S.C.FRS d\'Invest. et Rattachés et Regul. en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041061', 'label' => 'S.C. Etat, RaS operee/plus value immobiliere en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041062', 'label' => 'Valeurs brutes des Invest. d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041063', 'label' => 'TVA payee/Investissements de l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041064', 'label' => 'S.C. FRS d\'Invest. et Rattachés et Regul. en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041065', 'label' => 'S.C. Etat, RaS operee/plus value immobiliere en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041066', 'label' => 'Encaissements liés aux immo. Corporelles et incorporelles', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041067" + "F60041068" - "F60041069" - "F60041070" + "F60041071" - "F60041072" - "F60041073" + "F60041074" - "F60041075" - "F60041076" + "F60041077" + "F60041078" - "F60041079"'],
          ['id' => 'F60041067', 'label' => 'S.D. Immo.Corporelles et incorporelles en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041068', 'label' => 'S.D. Débiteurs et autres Créances TTC / cession des immo en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041069', 'label' => 'S.C.TVA collectee/cession investissements en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041070', 'label' => 'S.C.TVA a reverser/cession investissements en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041071', 'label' => 'S.D.Etat, RaS supportee/plus value immobiliere en Début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041072', 'label' => 'TVA a reverser/cession d\'invest. durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041073', 'label' => 'Produits nets/Cession des invest.-t-TVA a reverser comprise) durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041074', 'label' => 'Charges Nettes/Cession des invest. durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041075', 'label' => 'S.D. Immo.Corporelles et incorporelles en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041076', 'label' => 'S.D.Débiteurs et autres Créances TTC / cession des invest. en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041077', 'label' => 'S.C. TVA collectee/cession investissements en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041078', 'label' => 'S.C.TVA a reverser/cession investissements en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041079', 'label' => 'S.D.Etat, RaS supportee/plus value immobiliere en Fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041080', 'label' => 'Décaissements liés aux immobilisations financières', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041081" + "F60041082" - "F60041083"'],
          ['id' => 'F60041081', 'label' => 'Dettes/acquisition d\'immo. Financières en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041082', 'label' => 'Valeur brute des titres acquis durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041083', 'label' => 'Dettes/acquisition d\'immo. Finan. en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041084', 'label' => 'Encaissements liés aux immobilisations financières', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041085" + "F60041086" + "F60041087" - "F60041088"'],
          ['id' => 'F60041085', 'label' => 'Créances sur cessions d\'immo Fin. en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041086', 'label' => 'Cessions /immo financières durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041087', 'label' => 'Remboursements/immo Financières durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041088', 'label' => 'Créances sur cessions d\'immo financ. en fin d\'exercice', 'type' => 'number', 'required' => true]
        ]
      ],
      [
        'sectionTitle' => 'Flux de trésorerie liés aux activités de financement (Exercice N-1)',
        'fields' => [
          ['id' => 'F60041089', 'label' => 'Flux de trésorerie liés aux activités de financement', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041090" - "F60041097" + "F60041103" - "F60041108"'],
          ['id' => 'F60041090', 'label' => 'Encaissements suite à l\'émission d\'actions', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041091" + "F60041092" - "F60041093" - "F60041094" - "F60041095" - "F60041096"'],
          ['id' => 'F60041091', 'label' => 'Capital en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041092', 'label' => 'Primes liées au capital en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041093', 'label' => 'Augmentations du capital durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041094', 'label' => 'Conversion de dettes en capital durant l\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041095', 'label' => 'Capital en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041096', 'label' => 'Primes liées au capital en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041097', 'label' => 'Dividendes et autres distributions', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041098" + "F60041099" + "F60041100" + "F60041101" - "F60041102"'],
          ['id' => 'F60041098', 'label' => 'Dividendes dus aux actionnaires en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041099', 'label' => 'Dividendes attribués en (N)', 'type' => 'number', 'required' => true],
          ['id' => 'F60041100', 'label' => 'Prélèvements sur les réserves en (N)', 'type' => 'number', 'required' => true],
          ['id' => 'F60041101', 'label' => 'Rachat d\'actions et autres réductions de capital en (N)', 'type' => 'number', 'required' => true],
          ['id' => 'F60041102', 'label' => 'Dividendes dus aux actionnaires en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041103', 'label' => 'Encaissements/remboursements d\'emprunts', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041104" + "F60041105" - "F60041106" - "F60041107"'],
          ['id' => 'F60041104', 'label' => 'S.C. (Emprunts et dettes assimilées) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041105', 'label' => 'S.C. (Emprunts courants) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041106', 'label' => 'S.C. (Emprunts et dettes assimilées) en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041107', 'label' => 'S.C. (Emprunts courants) en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041108', 'label' => 'Décaissements/remboursements de prêts et des placements', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041109" + "F60041110" - "F60041111" - "F60041112"'],
          ['id' => 'F60041109', 'label' => 'S.D.(Prêts et Créances Fin. courants) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041110', 'label' => 'S.D. (Placements Courants) en fin d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041111', 'label' => 'S.D.(Prêts et Créances Fin. courants) en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041112', 'label' => 'S.D. (Placements Courants) en début d\'exercice', 'type' => 'number', 'required' => true],
          ['id' => 'F60041113', 'label' => 'Incidences des variations des taux de change/les liquidités et equiv.', 'type' => 'number', 'required' => true],
          ['id' => 'F60041114', 'label' => 'Autres Postes des Flux de Trésorerie', 'type' => 'number', 'required' => true],
          ['id' => 'F60041115', 'label' => 'Variation de Trésorerie', 'type' => 'number', 'required' => true, 'calculated' => true, 'formula' => '"F60041001" + "F60041058" + "F60041089" + "F60041113" + "F60041114"'],
          ['id' => 'F60041116', 'label' => 'Trésorerie au début de la période', 'type' => 'number', 'required' => true],
          ['id' => 'F60041117', 'label' => 'Trésorerie à la clôture de la période', 'type' => 'number', 'required' => true]
        ]
      ]
    ]
],

'F6005_Tableau_de_Determination_du_Resultat_Fiscal' => [
      
        'id' => 'F6005_Tableau_de_Determination_du_Resultat_Fiscal',
        'title' => 'F6005 - Tableau de détermination du résultat fiscal',
        'description' => 'Tableau de détermination du résultat fiscal à partir du résultat comptable',
        'sections' => [
          [
            'sectionTitle' => 'Exercice N',
            'fields' => [
              [
                'id' => 'F60050000',
                'label' => 'Code Forme Juridique',
                'type' => 'text',
                'required' => true,
                'default' => ''
              ],
              [
                'id' => 'F60050001',
                'label' => 'Nature résultat net comptable',
                'type' => 'text',
                'calculated' => true,
                'formula' => 'F60050002<0?"P":"B"'
              ],
              [
                'id' => 'F60050002',
                'label' => 'Résultat net comptable',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050003',
                'label' => 'Total charges non déductibles',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050004+F60050005+F60050006+F60050007+F60050008+F60050009+F60050010+F60050011+F60050012+F60050013+F60050014+F60050015+F60050016+F60050017+F60050018+F60050019+F60050020+F60050021+F60050022+F60050023+F60050024'
              ],
              [
                'id' => 'F60050004',
                'label' => 'Rémunérations exploitant/associés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050005',
                'label' => 'Charges établissements étranger',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050006',
                'label' => 'Quote-part frais de siège',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050007',
                'label' => 'Charges résidences secondaires',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050008',
                'label' => 'Charges véhicules >9CV',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050009',
                'label' => 'Cadeaux et frais réception non déductibles',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050010',
                'label' => 'Cadeaux et frais réception excédentaires',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050011',
                'label' => 'Commissions non déclarées',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050012',
                'label' => 'Dons et subventions non déductibles',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050013',
                'label' => 'Dons et subventions excédentaires',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050014',
                'label' => 'Abandon de créances non déductibles',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050015',
                'label' => 'Pertes de change non réalisées',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050016',
                'label' => 'Gains de change non imposés antérieurement',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050017',
                'label' => 'Intérêts exploitant/associés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050018',
                'label' => 'Rémunération excédentaire titres participatifs',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050019',
                'label' => 'Charges >5000 dinars payées en espèces',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050020',
                'label' => 'Moins-values OPCVM',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050021',
                'label' => 'Impôts directs supportés pour autrui',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050022',
                'label' => 'Taxe de voyages',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050023',
                'label' => 'Amendes et pénalités non déductibles',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050024',
                'label' => 'Dépenses excédentaires essaimage',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050025',
                'label' => 'Total amortissements non déductibles',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050026+F60050027+F60050028+F60050029+F60050030+F60050031+F60050032'
              ],
              [
                'id' => 'F60050026',
                'label' => 'Amort. établissements étranger',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050027',
                'label' => 'Amort. résidences secondaires',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050028',
                'label' => 'Amort. véhicules >9CV',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050029',
                'label' => 'Amort. terrains et fonds de commerce',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050030',
                'label' => 'Amort. actifs >5000DT payés en espèces',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050031',
                'label' => 'Amort. dépassant limite autorisée',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050032',
                'label' => 'Amort. période inférieure autorisée',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050033',
                'label' => 'Total provisions',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050034+F60050035+F60050036+F60050037+F60050038'
              ],
              [
                'id' => 'F60050034',
                'label' => 'Provisions non déductibles',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050035',
                'label' => 'Provisions créances douteuses',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050036',
                'label' => 'Provisions actions cotées',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050037',
                'label' => 'Provisions stocks',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050038',
                'label' => 'Provisions risques assurances',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050039',
                'label' => 'Total produits non comptabilisés',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050040+F60050041+F60050042+F60050043'
              ],
              [
                'id' => 'F60050040',
                'label' => 'Intérêts non décomptés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050041',
                'label' => 'Intérêts insuffisamment décomptés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050042',
                'label' => 'Plus-values non comptabilisées',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050043',
                'label' => 'Plus-values insuffisamment comptabilisées',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050044',
                'label' => 'Autres réintégrations',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050045',
                'label' => 'Total des réintégrations',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050003+F60050025+F60050033+F60050039+F60050044'
              ],
              [
                'id' => 'F60050046',
                'label' => 'Produits établissements étranger',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050047',
                'label' => 'Reprise sur provisions',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050048',
                'label' => 'Amortissements excédentaires',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050049',
                'label' => 'Gains de change années antérieures',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050050',
                'label' => 'Gains de change non réalisés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050051',
                'label' => 'Pertes de change antérieures',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050052',
                'label' => '50% salaires nouveaux recrutements',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050053',
                'label' => 'Autres déductions',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050054',
                'label' => 'Total des déductions',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050046+F60050047+F60050048+F60050049+F60050050+F60050051+F60050052+F60050053'
              ],
              [
                'id' => 'F60050055',
                'label' => 'Nature Résultat Fiscal',
                'type' => 'text',
                'calculated' => true,
                'formula' => 'F60050002+F60050045-F60050054<0?"P":"B"'
              ],
              [
                'id' => 'F60050056',
                'label' => 'Résultat Fiscal (Bénéficiaire)',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'MAX(F60050002+F60050045-F60050054,0)'
              ],
              [
                'id' => 'F60050057',
                'label' => 'Provisions créances douteuses',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050058',
                'label' => 'Provisions stocks',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050059',
                'label' => 'Provisions actions cotées',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050060',
                'label' => 'Provisions risques assurances',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050061',
                'label' => 'Résultat après provisions',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050056-MIN(F60050057+F60050058+F60050059+F60050060,F60050056/2)'
              ],
              [
                'id' => 'F60050062',
                'label' => 'Moins-value options salariés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050063',
                'label' => 'Résultat avant déficits/amortissements',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'MAX(F60050061-F60050062,0)'
              ],
              [
                'id' => 'F60050064',
                'label' => 'Réintégration amortissements',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050065',
                'label' => 'Déduction déficits reportés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050066',
                'label' => 'Déduction amortissements exercice',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050067',
                'label' => 'Déduction amortissements différés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050068',
                'label' => 'Résultat après déficits/amortissements',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'IF(OR(F60050063+F60050064-F60050065<0,F60050063+F60050064-F60050065-F60050066<0,F60050063+F60050064-F60050065-F60050066-F60050067<0),0,(F60050063+F60050064-F60050065-F60050066-F60050067))'
              ],
              [
                'id' => 'F60050069',
                'label' => 'Dividendes sociétés tunisiennes',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050070',
                'label' => 'Plus-value introduction en bourse',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050071',
                'label' => 'Plus-value actions cotées',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050072',
                'label' => 'Plus-value SICAR',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050073',
                'label' => 'Plus-value FCPR',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050074',
                'label' => 'Plus-value fonds amorçage',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050075',
                'label' => 'Plus-value fusion/scission',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050076',
                'label' => 'Plus-value restructuration',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050077',
                'label' => 'Plus-value cession retraite',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050078',
                'label' => 'Plus-value entreprises en difficulté',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050079',
                'label' => 'Intérêts dépôts/devises',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050080',
                'label' => 'Résultat avant déduction bénéfices exploitation',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'IF(OR(F60050063+F60050064-F60050065<0,F60050063+F60050064-F60050065-F60050066<0,F60050063+F60050064-F60050065-F60050066-F60050067<0),0,MAX(F60050068-SUM(F60050069:F60050079),0))'
              ],
              [
                'id' => 'F60050081',
                'label' => 'Total revenus accessoires',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050082+F60050083+F60050084+F60050085'
              ],
              [
                'id' => 'F60050082',
                'label' => 'Loyers',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050083',
                'label' => 'Revenus capitaux mobiliers',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050084',
                'label' => 'Dividendes étrangers',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050085',
                'label' => 'Autres revenus accessoires',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050086',
                'label' => 'Total gains exceptionnels',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050087+F60050088+F60050089+F60050090'
              ],
              [
                'id' => 'F60050087',
                'label' => 'Plus-value immeubles/fonds commerce',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050088',
                'label' => 'Gains de change non activité principale',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050089',
                'label' => 'Plus-value cession titres',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050090',
                'label' => 'Autres gains exceptionnels',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050091',
                'label' => 'Total revenus accessoires et exceptionnels',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050081+F60050086'
              ],
              [
                'id' => 'F60050092',
                'label' => 'Bénéfice base déduction exploitation',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050080-F60050091'
              ],
              [
                'id' => 'F60050093',
                'label' => 'Exportation',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050094',
                'label' => 'Développement régional',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050095',
                'label' => 'Développement agricole',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050096',
                'label' => 'Autres déductions',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050097',
                'label' => 'Total déductions bénéfices exploitation',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050093+F60050094+F60050095+F60050096'
              ],
              [
                'id' => 'F60050098',
                'label' => 'Bénéfice après déductions exploitation',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050080-F60050097'
              ],
              [
                'id' => 'F60050099',
                'label' => 'Déductions revenus réinvestis',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050100',
                'label' => 'Réintégration 1/5 plus-value fusion',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050101',
                'label' => 'Résultat imposable',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'IF(OR(F60050063+F60050064-F60050065<0,F60050063+F60050064-F60050065-F60050066<0,F60050063+F60050064-F60050065-F60050066-F60050067<0),0,F60050098-F60050099+F60050100)'
              ],
              [
                'id' => 'F60050102',
                'label' => 'Résultat fiscal (déficit)',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'MIN(F60050002+F60050045-F60050054,0)'
              ],
              [
                'id' => 'F60050103',
                'label' => 'Réintégration amortissements exercice',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050104',
                'label' => 'Déduction déficits reportés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050105',
                'label' => 'Déduction amortissements exercice',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050106',
                'label' => 'Déduction amortissements différés',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60050107',
                'label' => 'Déficit reportable',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60050102+F60050103-F60050104-F60050105-F60050106'
              ],
              [
                'id' => 'F60050108',
                'label' => 'Autre résultat imposable',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ]
            ]
          ],
          [
            'sectionTitle' => 'Exercice N-1',
            'fields' => [
              [
                'id' => 'F60051000',
                'label' => 'Code Forme Juridique N-1',
                'type' => 'text',
                'required' => true,
                'default' => ''
              ],
              [
                'id' => 'F60051001',
                'label' => 'Nature résultat net comptable N-1',
                'type' => 'text',
                'calculated' => true,
                'formula' => 'F60051002<0?"P":"B"'
              ],
              [
                'id' => 'F60051002',
                'label' => 'Résultat net comptable N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051003',
                'label' => 'Total charges non déductibles N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051004+F60051005+F60051006+F60051007+F60051008+F60051009+F60051010+F60051011+F60051012+F60051013+F60051014+F60051015+F60051016+F60051017+F60051018+F60051019+F60051020+F60051021+F60051022+F60051023+F60051024'
              ],
              [
                'id' => 'F60051004',
                'label' => 'Rémunérations exploitant/associés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051005',
                'label' => 'Charges établissements étranger N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051006',
                'label' => 'Quote-part frais de siège N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051007',
                'label' => 'Charges résidences secondaires N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051008',
                'label' => 'Charges véhicules >9CV N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051009',
                'label' => 'Cadeaux et frais réception non déductibles N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051010',
                'label' => 'Cadeaux et frais réception excédentaires N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051011',
                'label' => 'Commissions non déclarées N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051012',
                'label' => 'Dons et subventions non déductibles N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051013',
                'label' => 'Dons et subventions excédentaires N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051014',
                'label' => 'Abandon de créances non déductibles N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051015',
                'label' => 'Pertes de change non réalisées N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051016',
                'label' => 'Gains de change non imposés antérieurement N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051017',
                'label' => 'Intérêts exploitant/associés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051018',
                'label' => 'Rémunération excédentaire titres participatifs N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051019',
                'label' => 'Charges >5000 dinars payées en espèces N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051020',
                'label' => 'Moins-values OPCVM N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051021',
                'label' => 'Impôts directs supportés pour autrui N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051022',
                'label' => 'Taxe de voyages N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051023',
                'label' => 'Amendes et pénalités non déductibles N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051024',
                'label' => 'Dépenses excédentaires essaimage N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051025',
                'label' => 'Total amortissements non déductibles N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051026+F60051027+F60051028+F60051029+F60051030+F60051031+F60051032'
              ],
              [
                'id' => 'F60051026',
                'label' => 'Amort. établissements étranger N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051027',
                'label' => 'Amort. résidences secondaires N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051028',
                'label' => 'Amort. véhicules >9CV N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051029',
                'label' => 'Amort. terrains et fonds de commerce N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051030',
                'label' => 'Amort. actifs >5000DT payés en espèces N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051031',
                'label' => 'Amort. dépassant limite autorisée N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051032',
                'label' => 'Amort. période inférieure autorisée N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051033',
                'label' => 'Total provisions N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051034+F60051035+F60051036+F60051037+F60051038'
              ],
              [
                'id' => 'F60051034',
                'label' => 'Provisions non déductibles N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051035',
                'label' => 'Provisions créances douteuses N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051036',
                'label' => 'Provisions actions cotées N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051037',
                'label' => 'Provisions stocks N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051038',
                'label' => 'Provisions risques assurances N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051039',
                'label' => 'Total produits non comptabilisés N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051040+F60051041+F60051042+F60051043'
              ],
              [
                'id' => 'F60051040',
                'label' => 'Intérêts non décomptés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051041',
                'label' => 'Intérêts insuffisamment décomptés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051042',
                'label' => 'Plus-values non comptabilisées N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051043',
                'label' => 'Plus-values insuffisamment comptabilisées N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051044',
                'label' => 'Autres réintégrations N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051045',
                'label' => 'Total des réintégrations N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051003+F60051025+F60051033+F60051039+F60051044'
              ],
              [
                'id' => 'F60051046',
                'label' => 'Produits établissements étranger N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051047',
                'label' => 'Reprise sur provisions N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051048',
                'label' => 'Amortissements excédentaires N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051049',
                'label' => 'Gains de change années antérieures N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051050',
                'label' => 'Gains de change non réalisés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051051',
                'label' => 'Pertes de change antérieures N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051052',
                'label' => '50% salaires nouveaux recrutements N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051053',
                'label' => 'Autres déductions N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051054',
                'label' => 'Total des déductions N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051046+F60051047+F60051048+F60051049+F60051050+F60051051+F60051052+F60051053'
              ],
              [
                'id' => 'F60051055',
                'label' => 'Nature Résultat Fiscal N-1',
                'type' => 'text',
                'calculated' => true,
                'formula' => 'F60051002+F60051045-F60051054<0?"P":"B"'
              ],
              [
                'id' => 'F60051056',
                'label' => 'Résultat Fiscal (Bénéficiaire) N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'MAX(F60051002+F60051045-F60051054,0)'
              ],
              [
                'id' => 'F60051057',
                'label' => 'Provisions créances douteuses N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051058',
                'label' => 'Provisions stocks N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051059',
                'label' => 'Provisions actions cotées N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051060',
                'label' => 'Provisions risques assurances N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051061',
                'label' => 'Résultat après provisions N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051056-MIN(F60051057+F60051058+F60051059+F60051060,F60051056/2)'
              ],
              [
                'id' => 'F60051062',
                'label' => 'Moins-value options salariés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051063',
                'label' => 'Résultat avant déficits/amortissements N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'MAX(F60051061-F60051062,0)'
              ],
              [
                'id' => 'F60051064',
                'label' => 'Réintégration amortissements N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051065',
                'label' => 'Déduction déficits reportés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051066',
                'label' => 'Déduction amortissements exercice N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051067',
                'label' => 'Déduction amortissements différés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051068',
                'label' => 'Résultat après déficits/amortissements N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'IF(OR(F60051063+F60051064-F60051065<0,F60051063+F60051064-F60051065-F60051066<0,F60051063+F60051064-F60051065-F60051066-F60051067<0),0,(F60051063+F60051064-F60051065-F60051066-F60051067))'
              ],
              [
                'id' => 'F60051069',
                'label' => 'Dividendes sociétés tunisiennes N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051070',
                'label' => 'Plus-value introduction en bourse N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051071',
                'label' => 'Plus-value actions cotées N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051072',
                'label' => 'Plus-value SICAR N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051073',
                'label' => 'Plus-value FCPR N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051074',
                'label' => 'Plus-value fonds amorçage N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051075',
                'label' => 'Plus-value fusion/scission N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051076',
                'label' => 'Plus-value restructuration N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051077',
                'label' => 'Plus-value cession retraite N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051078',
                'label' => 'Plus-value entreprises en difficulté N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051079',
                'label' => 'Intérêts dépôts/devises N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051080',
                'label' => 'Résultat avant déduction bénéfices exploitation N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'IF(OR(F60051063+F60051064-F60051065<0,F60051063+F60051064-F60051065-F60051066<0,F60051063+F60051064-F60051065-F60051066-F60051067<0),0,MAX(F60051068-SUM(F60051069:F60051079),0))'
              ],
              [
                'id' => 'F60051081',
                'label' => 'Total revenus accessoires N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051082+F60051083+F60051084+F60051085'
              ],
              [
                'id' => 'F60051082',
                'label' => 'Loyers N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051083',
                'label' => 'Revenus capitaux mobiliers N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051084',
                'label' => 'Dividendes étrangers N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051085',
                'label' => 'Autres revenus accessoires N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051086',
                'label' => 'Total gains exceptionnels N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051087+F60051088+F60051089+F60051090'
              ],
              [
                'id' => 'F60051087',
                'label' => 'Plus-value immeubles/fonds commerce N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051088',
                'label' => 'Gains de change non activité principale N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051089',
                'label' => 'Plus-value cession titres N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051090',
                'label' => 'Autres gains exceptionnels N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051091',
                'label' => 'Total revenus accessoires et exceptionnels N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051081+F60051086'
              ],
              [
                'id' => 'F60051092',
                'label' => 'Bénéfice base déduction exploitation N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051080-F60051091'
              ],
              [
                'id' => 'F60051093',
                'label' => 'Exportation N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051094',
                'label' => 'Développement régional N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051095',
                'label' => 'Développement agricole N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051096',
                'label' => 'Autres déductions N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051097',
                'label' => 'Total déductions bénéfices exploitation N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051093+F60051094+F60051095+F60051096'
              ],
              [
                'id' => 'F60051098',
                'label' => 'Bénéfice après déductions exploitation N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051080-F60051097'
              ],
              [
                'id' => 'F60051099',
                'label' => 'Déductions revenus réinvestis N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051100',
                'label' => 'Réintégration 1/5 plus-value fusion N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051101',
                'label' => 'Résultat imposable N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'IF(OR(F60051063+F60051064-F60051065<0,F60051063+F60051064-F60051065-F60051066<0,F60051063+F60051064-F60051065-F60051066-F60051067<0),0,F60051098-F60051099+F60051100)'
              ],
              [
                'id' => 'F60051102',
                'label' => 'Résultat fiscal (déficit) N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'MIN(F60051055,0)'
              ],
              [
                'id' => 'F60051103',
                'label' => 'Réintégration amortissements exercice N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051104',
                'label' => 'Déduction déficits reportés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051105',
                'label' => 'Déduction amortissements exercice N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051106',
                'label' => 'Déduction amortissements différés N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ],
              [
                'id' => 'F60051107',
                'label' => 'Déficit reportable N-1',
                'type' => 'number',
                'calculated' => true,
                'formula' => 'F60051102+F60051103-F60051104-F60051105-F60051106'
              ],
              [
                'id' => 'F60051108',
                'label' => 'Autre résultat imposable N-1',
                'type' => 'number',
                'required' => true,
                'default' => 0.000
              ]
            ]
          ]
        ]
 ],

'F6006_Affectation_Resultat' => [
    'id' => 'F6006_Affectation_Resultat',
    'title' => 'F6006 - Tableau d\'affectation du résultat',
    'description' => 'Saisie détaillée de l\'affectation du résultat de l\'exercice N et N-1.',
    'sections' => [
        // Section : Résultat à affecter
        [
            'sectionTitle' => 'Résultat à affecter',
            'fields' => [
                ['id' => 'F60060001', 'label' => 'Résultat net de l\'exercice à affecter', 'type' => 'number', 'required' => true, 'default' => 0.00]
            ]
        ],
        // Section : Affectation du résultat
        [
            'sectionTitle' => 'Affectation du résultat',
            'fields' => [
                ['id' => 'F60060002', 'label' => 'Affectation à la réserve légale', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60060003', 'label' => 'Affectation à la réserve statutaire ou contractuelle', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60060004', 'label' => 'Autres réserves', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60060005', 'label' => 'Report à nouveau', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60060006', 'label' => 'Dividendes distribués', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60060007', 'label' => 'Autres affectations', 'type' => 'number', 'required' => true, 'default' => 0.00]
            ]
        ],
        // Section : Vérification de la concordance
        [
            'sectionTitle' => 'Vérification de la concordance',
            'fields' => [
                ['id' => 'F60060008', 'label' => 'Total des affectations', 'type' => 'number', 'calculated' => true, 'formula' => 'F60060002 + F60060003 + F60060004 + F60060005 + F60060006 + F60060007'],
                ['id' => 'F60060009', 'label' => 'Ecart à justifier (doit être égal à 0)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60060001 - F60060008']
            ]
        ]
    ]
],
'F6007_Faits_Marquants' => [
    'id' => 'F6007_Faits_Marquants',
    'title' => 'F6007 - Faits marquants de l\'exercice',
    'description' => 'Saisie détaillée des faits marquants pour les exercices N et N-1',
    'sections' => [
        [
            'sectionTitle' => 'Réunions',
            'fields' => [
                ['id' => 'F60070001', 'label' => 'Réunions ordinaires/extraordinaires (*)', 'type' => 'text', 'required' => true, 'options' => 'RO:Ordinaire, RE:Extraordinaire'],
                ['id' => 'F60073001', 'label' => 'Réunions ordinaires/extraordinaires (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Réunions ordinaires/extraordinaires (N-1)', 'type' => 'number', 'required' => false],
                ['id' => 'F60071001', 'label' => 'Organe', 'type' => 'text', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Organe (N)', 'type' => 'text', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Organe (N-1)', 'type' => 'text', 'required' => false],
                ['id' => 'F60071002', 'label' => 'Date', 'type' => 'date', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Date (N)', 'type' => 'date', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Date (N-1)', 'type' => 'date', 'required' => false],
                ['id' => 'F60071003', 'label' => 'Nombre de résolutions', 'type' => 'number', 'required' => false],
                ['id' => 'F60072003', 'label' => 'Résolution 1', 'type' => 'text', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Résolution 1 (N)', 'type' => 'text', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Résolution 1 (N-1)', 'type' => 'text', 'required' => false]
            ]
        ],
        [
            'sectionTitle' => 'Contrats',
            'fields' => [
                ['id' => 'F60070002', 'label' => 'Principaux contrats signés ou renouvelés (*)', 'type' => 'text', 'required' => false, 'options' => 'CS:Signé, CR:Renouvelé'],
                ['id' => 'F60073001', 'label' => 'Principaux contrats signés ou renouvelés (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Principaux contrats signés ou renouvelés (N-1)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Contrats (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Contrats (N-1)', 'type' => 'number', 'required' => false]
            ]
        ],
        [
            'sectionTitle' => 'Performance',
            'fields' => [
                ['id' => 'F60070003', 'label' => 'Éléments ayant effet sur la performance', 'type' => 'text', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Éléments ayant effet sur la performance (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Éléments ayant effet sur la performance (N-1)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Performance (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Performance (N-1)', 'type' => 'number', 'required' => false]
            ]
        ],
        [
            'sectionTitle' => 'Patrimoine',
            'fields' => [
                ['id' => 'F60070004', 'label' => 'Éléments ayant effet sur le patrimoine', 'type' => 'text', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Éléments ayant effet sur le patrimoine (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Éléments ayant effet sur le patrimoine (N-1)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Patrimoine (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Patrimoine (N-1)', 'type' => 'number', 'required' => false]
            ]
        ],
        [
            'sectionTitle' => 'Difficultés',
            'fields' => [
                ['id' => 'F60070005', 'label' => 'Difficultés rencontrées (*)', 'type' => 'text', 'required' => false, 'options' => 'DC:Commerciale, DF:Financière, DT:Technique'],
                ['id' => 'F60073001', 'label' => 'Difficultés rencontrées (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Difficultés rencontrées (N-1)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Difficulté (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Difficulté (N-1)', 'type' => 'number', 'required' => false]
            ]
        ],
        [
            'sectionTitle' => 'Perspectives',
            'fields' => [
                ['id' => 'F60070006', 'label' => 'Projets et Perspectives (*)', 'type' => 'text', 'required' => false, 'options' => 'PC:Commerciale, PF:Financière, PT:Technique'],
                ['id' => 'F60073001', 'label' => 'Projets et Perspectives (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Projets et Perspectives (N-1)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073001', 'label' => 'Perspectives (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60073002', 'label' => 'Perspectives (N-1)', 'type' => 'number', 'required' => false]
            ]
        ],
        [
            'sectionTitle' => 'Autres faits marquants',
            'fields' => [
                ['id' => 'F60070007', 'label' => 'Autres faits marquants', 'type' => 'text', 'required' => false],
                ['id' => 'F60071007', 'label' => 'Fait marquant (N)', 'type' => 'number', 'required' => false],
                ['id' => 'F60072007', 'label' => 'Fait marquant (N-1)', 'type' => 'number', 'required' => false]
            ]
        ]
    ]
],
    // Ajoutez d'autres définitions de formulaires ici si nécessaire
];

?>
        <div class="bg-white p-8 rounded-xl shadow-lg">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Sélection de la Déclaration et du Formulaire</h2>
            <!-- Message Box for general operations -->
            <div id="generalMessageBox" class="message-box"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div>
                    <label for="selectDeclaration" class="block text-gray-700 text-sm font-medium mb-2">
                        Sélectionner une Déclaration Existante <span class="text-red-500">*</span>
                    </label>
                    <select id="selectDeclaration" class="form-input" required>
                        <option value="">-- Sélectionnez une déclaration --</option>
                        <?php foreach ($user_declarations as $declaration): ?>
                            <option value="<?= htmlspecialchars($declaration['id']) ?>">
                                <?= htmlspecialchars($declaration['raison_sociale']) ?> (<?= htmlspecialchars($declaration['annee']) ?>) - <?= htmlspecialchars($declaration['numero_depot']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="selectFormType" class="block text-gray-700 text-sm font-medium mb-2">
                        Type de Formulaire <span class="text-red-500">*</span>
                    </label>
                    <select id="selectFormType" class="form-input" required disabled>
                        <option value="">-- Sélectionnez un type de formulaire --</option>
                        <?php foreach ($form_definitions as $key => $def): ?>
                            <option value="<?= htmlspecialchars($key) ?>"><?= htmlspecialchars($def['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <!-- Zone où le formulaire dynamique sera affiché -->
            <div id="dynamicFormContainer" class="hidden border-t pt-8 mt-8 border-gray-200">
                <h3 id="dynamicFormTitle" class="text-xl font-bold text-gray-800 mb-4"></h3>
                <p id="dynamicFormDescription" class="text-gray-600 mb-6"></p>
                <form id="dynamicDataForm" class="space-y-4">
                    <input type="hidden" id="currentDeclarationId" name="declaration_id">
                    <input type="hidden" id="currentFormType" name="form_type">
                    <div id="formFieldsArea"></div>
                    <button type="submit" class="btn btn-primary mt-6">
                        <i class="fas fa-save mr-2"></i> Enregistrer les données
                    </button>
					</form>
					<form id="generateXmlForm" method="get" action="php/generate_xml.php"  style="display:none; margin-top:1em">
    <input type="hidden" name="declaration_id" id="xmlDeclarationId">
    <input type="hidden" name="form_type" id="xmlFormType">
    <button type="submit" class="btn btn-primary">
        <i class="fa fa-file-code"></i> Générer et valider XML
    </button>
</form>
        
                
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectDeclaration = document.getElementById('selectDeclaration');
    const selectFormType = document.getElementById('selectFormType');
    const dynamicFormContainer = document.getElementById('dynamicFormContainer');
    const dynamicFormTitle = document.getElementById('dynamicFormTitle');
    const dynamicFormDescription = document.getElementById('dynamicFormDescription');
    const formFieldsArea = document.getElementById('formFieldsArea');
    const dynamicDataForm = document.getElementById('dynamicDataForm');
    const currentDeclarationIdInput = document.getElementById('currentDeclarationId');
    const currentFormTypeInput = document.getElementById('currentFormType');
    const generalMessageBox = document.getElementById('generalMessageBox');

    const FORM_DEFINITIONS = <?php echo json_encode($form_definitions); ?>;
    const ALL_USER_DECLARATIONS = <?php echo json_encode($user_declarations); ?>;

    const formInputElements = {};
    const calculatedFieldsDefinitions = [];

    function showMessage(box, message, type = 'error') {
        box.textContent = message;
        box.className = `message-box ${type}`;
        box.style.display = 'block';
        box.style.opacity = '0';
        box.style.transform = 'translateY(-10px)';
        setTimeout(() => {
            box.style.transition = 'opacity 0.3s, transform 0.3s';
            box.style.opacity = '1';
            box.style.transform = 'translateY(0)';
        }, 10);
        setTimeout(() => {
            box.style.opacity = '0';
            box.style.transform = 'translateY(-10px)';
            setTimeout(() => { hideMessage(box); }, 300);
        }, 5000);
    }
    function hideMessage(box) {
        box.style.display = 'none';
        box.textContent = '';
        box.classList.remove('error', 'success', 'info');
    }

    function evaluateFormula(formula, values) {
        let evalFormula = formula;
        const fieldIdsInFormula = formula.match(/[a-zA-Z0-9_]+/g) || [];
        fieldIdsInFormula.forEach(id => {
            const value = parseFloat(values[id]) || 0;
            const regex = new RegExp(`\\b${id}\\b`, 'g');
            evalFormula = evalFormula.replace(regex, value);
        });
        evalFormula = evalFormula.replace(/Sup\(([^,]+),([^)]+)\)/g, 'Math.max($1,$2)');
        evalFormula = evalFormula.replace(/Inf\(([^,]+),([^)]+)\)/g, 'Math.min($1,$2)');
        try {
            const result = new Function(`return ${evalFormula};`)();
            return parseFloat(result.toFixed(2));
        } catch (e) {
            console.error("Erreur d'évaluation de la formule:", formula, "Evaluated as:", evalFormula, "Error:", e);
            return 0;
        }
    }

    function recalculateAllFormulas() {
        for (let pass = 0; pass < 3; pass++) {
            const currentFormValues = {};
            for (const id in formInputElements) {
                currentFormValues[id] = parseFloat(formInputElements[id].value) || 0;
            }
            for (const field of calculatedFieldsDefinitions) {
                const inputElement = formInputElements[field.id];
                if (inputElement) {
                    const newValue = evaluateFormula(field.formula, currentFormValues);
                    inputElement.value = newValue;
                    currentFormValues[field.id] = newValue;
                }
            }
        }
    }

    function renderDynamicForm(formKey, formData = null) {
        const formDef = FORM_DEFINITIONS[formKey];
        if (!formDef) {
            formFieldsArea.innerHTML = '';
            dynamicFormContainer.classList.add('hidden');
            return;
        }
        dynamicFormTitle.textContent = formDef.title;
        dynamicFormDescription.textContent = formDef.description || '';
        currentFormTypeInput.value = formKey;
        formFieldsArea.innerHTML = '';
        Object.keys(formInputElements).forEach(key => delete formInputElements[key]);
        calculatedFieldsDefinitions.length = 0;

        formDef.sections.forEach(section => {
            const sectionDiv = document.createElement('div');
            sectionDiv.className = 'mb-6 p-4 bg-gray-50 rounded-lg border border-gray-100';
            sectionDiv.innerHTML = `<h4 class="text-lg font-semibold text-gray-700 mb-4">${section.sectionTitle}</h4>`;

            section.fields.forEach(field => {
                const fieldDiv = document.createElement('div');
                fieldDiv.className = 'mb-4';

                const label = document.createElement('label');
                label.htmlFor = field.id;
                label.className = 'block text-gray-700 text-sm font-medium mb-2';
                label.textContent = field.label;
                if (field.required && !field.calculated) {
                    const spanRequired = document.createElement('span');
                    spanRequired.className = 'text-red-500 ml-1';
                    spanRequired.textContent = '*';
                    label.appendChild(spanRequired);
                }
                fieldDiv.appendChild(label);

                let inputElement;
                if (field.type === 'textarea') {
                    inputElement = document.createElement('textarea');
                    inputElement.rows = 3;
                } else if (field.type === 'select') {
                    inputElement = document.createElement('select');
                    field.options.forEach(option => {
                        const optionEl = document.createElement('option');
                        optionEl.value = option.value;
                        optionEl.textContent = option.label;
                        inputElement.appendChild(optionEl);
                    });
                } else {
                    inputElement = document.createElement('input');
                    inputElement.type = field.type;
                    if (field.type === 'number') {
                        inputElement.step = '0.01';
                    }
                }
                inputElement.id = field.id;
                inputElement.name = field.id;
                inputElement.className = 'form-input';
                if (field.required) {
                    inputElement.required = true;
                }
                if (field.placeholder) {
                    inputElement.placeholder = field.placeholder;
                }

                if (field.calculated) {
                    inputElement.readOnly = true;
                    inputElement.placeholder = 'Calculé automatiquement';
                    calculatedFieldsDefinitions.push(field);
                } else {
                    inputElement.addEventListener('input', recalculateAllFormulas);
                }

                if (formData && formData[field.id] !== undefined && formData[field.id] !== null) {
                    inputElement.value = formData[field.id];
                } else if (field.default !== undefined) {
                    inputElement.value = field.default;
                }

                fieldDiv.appendChild(inputElement);
                sectionDiv.appendChild(fieldDiv);
                formInputElements[field.id] = inputElement;
            });
            formFieldsArea.appendChild(sectionDiv);
        });
        dynamicFormContainer.classList.remove('hidden');
        recalculateAllFormulas();
    }

    // Charger les données existantes pour un formulaire
    async function loadExistingFormData(declarationId, formType) {
        hideMessage(generalMessageBox);
        if (!declarationId || !formType) return;
        try {
            const url = `php/api/form_data.php?declaration_id=${declarationId}&form_type=${formType}`;
            const response = await fetch(url, { method: 'GET', headers: { 'Accept': 'application/json' } });
            if (!response.ok) {
                const errorText = await response.text();
                if (errorText.includes("Fatal error") || errorText.includes("<br />") || errorText.includes("<font")) {
                    throw new Error(`Erreur serveur inattendue lors du chargement. Veuillez réessayer. (Détails en console)`);
                }
                throw new Error(`Erreur serveur lors du chargement: ${response.status} - ${errorText.substring(0, 100)}...`);
            }
            const result = await response.json();
            if (result.success) {
                if (result.data) {
                    showMessage(generalMessageBox, `Données "${FORM_DEFINITIONS[formType].title}" chargées avec succès.`, 'success');
                    renderDynamicForm(formType, result.data);
                } else {
                    showMessage(generalMessageBox, `Aucune donnée existante trouvée pour "${FORM_DEFINITIONS[formType].title}". Formulaire vide.`, 'info');
                    renderDynamicForm(formType, null);
                }
            } else {
                showMessage(generalMessageBox, result.message || "Erreur lors du chargement des données.", 'error');
                renderDynamicForm(formType, null);
            }
        } catch (error) {
            showMessage(generalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
            renderDynamicForm(formType, null);
        }
    }

    selectDeclaration.addEventListener('change', function() {
        const selectedDeclarationId = this.value;
        if (selectedDeclarationId) {
            selectFormType.disabled = false;
            currentDeclarationIdInput.value = selectedDeclarationId;
            hideMessage(generalMessageBox);
            selectFormType.value = '';
            dynamicFormContainer.classList.add('hidden');
            formFieldsArea.innerHTML = '';
            currentFormTypeInput.value = '';
        } else {
            selectFormType.disabled = true;
            selectFormType.value = '';
            dynamicFormContainer.classList.add('hidden');
            formFieldsArea.innerHTML = '';
            currentDeclarationIdInput.value = '';
            currentFormTypeInput.value = '';
            hideMessage(generalMessageBox);
        }
    });

    selectFormType.addEventListener('change', function() {
        const selectedFormKey = this.value;
        const selectedDeclarationId = selectDeclaration.value;
        if (!selectedDeclarationId) {
            showMessage(generalMessageBox, "Veuillez d'abord sélectionner une déclaration.", 'error');
            selectFormType.value = '';
            return;
        }
        if (selectedFormKey) {
            loadExistingFormData(selectedDeclarationId, selectedFormKey);
        } else {
            dynamicFormContainer.classList.add('hidden');
            formFieldsArea.innerHTML = '';
        }
    });

    dynamicDataForm.addEventListener('submit', async function(event) {
        event.preventDefault();
        hideMessage(generalMessageBox);
        const declarationId = currentDeclarationIdInput.value;
        const formType = currentFormTypeInput.value;
        if (!declarationId || !formType) {
            showMessage(generalMessageBox, "Erreur: ID de déclaration ou type de formulaire manquant. Veuillez sélectionner.", 'error');
            return;
        }
        const dataToSave = {
            declaration_id: declarationId,
            form_type: formType,
            form_data: {}
        };
        const formDef = FORM_DEFINITIONS[formType];
        if (!formDef) {
            showMessage(generalMessageBox, "Erreur: Définition de formulaire introuvable.", 'error');
            return;
        }
        let isValid = true;
        formDef.sections.forEach(section => {
            section.fields.forEach(field => {
                const inputElement = document.getElementById(field.id);
                if (inputElement) {
                    let value = inputElement.value;
                    if (field.type === 'number') {
                        if (value === '') value = null;
                        else {
                            value = parseFloat(value);
                            if (isNaN(value)) {
                                isValid = false;
                                showMessage(generalMessageBox, `Le champ "${field.label}" doit être un nombre valide.`, 'error');
                            }
                        }
                    }
                    dataToSave.form_data[field.id] = value;
                    if (field.required && !field.calculated && (value === null || value === '')) {
                        isValid = false;
                        showMessage(generalMessageBox, `Le champ "${field.label}" est obligatoire.`, 'error');
                    }
                }
            });
        });
        if (!isValid) return;
        try {
            const response = await fetch('php/api/form_data.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(dataToSave)
            });
            if (!response.ok) {
                const errorText = await response.text();
                if (errorText.includes("Fatal error") || errorText.includes("<br />") || errorText.includes("<font")) {
                    throw new Error(`Erreur serveur inattendue. Veuillez réessayer. (Détails en console)`);
                }
                throw new Error(`Erreur serveur: ${response.status} - ${errorText.substring(0, 100)}...`);
            }
            const result = await response.json();
            if (result.success) {
                showMessage(generalMessageBox, result.message, 'success');
                loadExistingFormData(declarationId, formType);
            } else {
                showMessage(generalMessageBox, result.message || "Erreur lors de l'enregistrement des données.", 'error');
            }
        } catch (error) {
            showMessage(generalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
        }
    });

    selectFormType.disabled = true;
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectDeclaration = document.getElementById('selectDeclaration');
    const selectFormType = document.getElementById('selectFormType');
    const generateXmlForm = document.getElementById('generateXmlForm');
    const xmlDeclarationId = document.getElementById('xmlDeclarationId');
    const xmlFormType = document.getElementById('xmlFormType');

    function updateXmlForm() {
        if (selectDeclaration.value && selectFormType.value) {
            xmlDeclarationId.value = selectDeclaration.value;
            xmlFormType.value = selectFormType.value;
            generateXmlForm.action = "php/generate_" + selectFormType.value + "_xml.php";
            generateXmlForm.style.display = "block";
        } else {
            generateXmlForm.style.display = "none";
            xmlDeclarationId.value = "";
            xmlFormType.value = "";
            generateXmlForm.action = "php/generate_xml.php";
        }
    }

    selectDeclaration.addEventListener('change', function() {
        selectFormType.disabled = !this.value;
        if (!selectFormType.value) {
            generateXmlForm.style.display = "none";
        }
        updateXmlForm();
    });

    selectFormType.addEventListener('change', updateXmlForm);

    // Initialisation à l'ouverture
    updateXmlForm();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectDeclaration = document.getElementById('selectDeclaration');
    const selectFormType = document.getElementById('selectFormType');
    const currentDeclarationIdInput = document.getElementById('currentDeclarationId');
    const currentFormTypeInput = document.getElementById('currentFormType');
    const xmlDeclarationId = document.getElementById('xmlDeclarationId');
    const xmlFormType = document.getElementById('xmlFormType');
    const generateXmlForm = document.getElementById('generateXmlForm');

    // Fonction centrale : synchronise à chaque changement
    function syncXmlFormFields() {
        xmlDeclarationId.value = currentDeclarationIdInput.value;
        xmlFormType.value = currentFormTypeInput.value;

        // Affiche/cache bouton et met à jour action
        if (xmlDeclarationId.value && xmlFormType.value) {
            generateXmlForm.action = "php/generate_" + xmlFormType.value + "_xml.php";
            generateXmlForm.style.display = "block";
        } else {
            generateXmlForm.action = "php/generate_xml.php";
            generateXmlForm.style.display = "none";
        }
    }

    // Synchronise lors d'un changement de déclaration
    selectDeclaration.addEventListener('change', function() {
        currentDeclarationIdInput.value = this.value;
        syncXmlFormFields();
    });

    // Synchronise lors d'un changement de type de formulaire
    selectFormType.addEventListener('change', function() {
        currentFormTypeInput.value = this.value;
        syncXmlFormFields();
    });

    // Synchronise aussi après chaque rechargement ou action JS/AJAX
    // Exemple : chaque fois que tu modifies currentDeclarationIdInput.value ou currentFormTypeInput.value ailleurs,
    // ajoute un appel à syncXmlFormFields();

    // Initialisation
    syncXmlFormFields();
});
</script>

</body>
</html>
