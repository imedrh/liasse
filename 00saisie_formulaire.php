<?php
// saisie_formulaire.php - Page de saisie dynamique des formulaires de liasse

require_once 'php/auth.php'; // Inclut les fonctions d'authentification
require_once 'php/controllers/EntrepriseController.php';
require_once 'php/controllers/ExerciceController.php';
require_once 'php/controllers/DeclarationController.php';

// Vérifie si l'utilisateur est connecté, sinon redirige vers la page de connexion
if (!isLoggedIn()) {
    redirect('login.html');
}

// Récupérer le nom d'utilisateur et le rôle de la session
$username = htmlspecialchars($_SESSION['username']);
$role = htmlspecialchars($_SESSION['role']);
$user_id = $_SESSION['user_id'];

// Récupérer les données nécessaires pour les sélecteurs
$entrepriseController = new EntrepriseController($mysqli);
$user_entreprises = $entrepriseController->getEntreprises($user_id);

$exerciceController = new ExerciceController($mysqli);
$user_exercices = $exerciceController->getExercices($user_id);

$declarationController = new DeclarationController($mysqli);
$user_declarations = $declarationController->getDeclarations($user_id);

// Définitions complètes des structures de formulaires
// Les IDs des champs sont tirés directement des codes XML du document PDF.
// Les formules sont des expressions JavaScript utilisant ces IDs.
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
            // Colonne N-1 (Brut)
            [
                'sectionTitle' => 'Actifs non courants (Brut N-1)',
                'fields' => [
                    ['id' => 'F60013001', 'label' => 'Actifs non courants (Brut N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013002 + F60013031'],
                    ['id' => 'F60013002', 'label' => 'Actifs immobilisés (Brut N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013003 + F60013012 + F60013021'],
                    ['id' => 'F60013003', 'label' => 'Immobilisations Incorporelles (Brut N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013004 + F60013005 + F60013006 + F60013007 + F60013008 + F60013009 + F60013010 + F60013011'],
                    ['id' => 'F60013004', 'label' => 'Investissement recherche et développement (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013005', 'label' => 'Concess. marque,brevet,licence,marque (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013006', 'label' => 'Logiciels (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013007', 'label' => 'Fonds commercial (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013008', 'label' => 'Droit au bail (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013009', 'label' => 'Autres Immobilisations Incorporelles (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013010', 'label' => 'Immobilisations Incorporelles en cours (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013012', 'label' => 'Immobilisations corporelles (Brut N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013013 + F60013014 + F60013015 + F60013016 + F60013017 + F60013018 + F60013019 + F60013020'],
                    ['id' => 'F60013013', 'label' => 'Terrains (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013014', 'label' => 'Constructions (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013016', 'label' => 'Materiel de transport (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013017', 'label' => 'Autres Immobilisations Corporelles (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013018', 'label' => 'Immob. Corporelles en cours (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013020', 'label' => 'Immob. a statut juridique particulier (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013021', 'label' => 'Immobilisations Financières (Brut N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013022 + F60013023 + F60013024 + F60013025 + F60013026 + F60013027 + F60013028 + F60013029 + F60013030'],
                    ['id' => 'F60013022', 'label' => 'Actions (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013023', 'label' => 'Autres creances rattach. a des participat. (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013024', 'label' => 'Creances rattach. a des stes en participat. (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013025', 'label' => 'Vers.a eff./titre de participation non liberes (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013026', 'label' => 'Titres immobilises (droit de propriete) (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013027', 'label' => 'Titres immobilises (droit de creance) (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013028', 'label' => 'Depots et cautionnements verses (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013029', 'label' => 'Autres creances immobilisees (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013031', 'label' => 'Autres Actifs Non Courants (Brut N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013032 + F60013033 + F60013034 + F60013035'],
                    ['id' => 'F60013032', 'label' => 'Frais preliminaires (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013033', 'label' => 'Charges a repartir (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013035', 'label' => 'ecarts de conversion (Brut N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Colonne N-1 (Amortissement/Provision)
            [
                'sectionTitle' => 'Actifs non courants (Amortissement/Provision N-1)',
                'fields' => [
                    ['id' => 'F60014001', 'label' => 'Actifs non courants (Amortissement/Provision N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60014002 + F60014031'],
                    ['id' => 'F60014002', 'label' => 'Actifs immobilisés (Amortissement/Provision N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60014003 + F60014012 + F60014021'],
                    ['id' => 'F60014003', 'label' => 'Immobilisations Incorporelles (Amortissement/Provision N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60014004 + F60014005 + F60014006 + F60014007 + F60014008 + F60014009 + F60014010 + F60014011'],
                    ['id' => 'F60014004', 'label' => 'Investissement recherche et developpement (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014005', 'label' => 'Concess. marque,brevet,licence,marque (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014006', 'label' => 'Logiciels (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014007', 'label' => 'Fonds commercial (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014008', 'label' => 'Droit au bail (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014009', 'label' => 'Autres Immobilisations Incorporelles (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014010', 'label' => 'Immobilisations Incorporelles en cours (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014012', 'label' => 'Immobilisations corporelles (Amortissement/Provision N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60014013 + F60014014 + F60014015 + F60014016 + F60014017 + F60014018 + F60014019 + F60014020'],
                    ['id' => 'F60014013', 'label' => 'Terrains (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014014', 'label' => 'Constructions (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014016', 'label' => 'Materiel de transport (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014017', 'label' => 'Autres Immobilisations Corporelles (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014018', 'label' => 'Immob. Corporelles en cours (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014020', 'label' => 'Immob. a statut juridique particulier (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014021', 'label' => 'Immobilisations Financières (Amortissement/Provision N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60014022 + F60014023 + F60014024 + F60014025 + F60014026 + F60014027 + F60014028 + F60014029 + F60014030'],
                    ['id' => 'F60014022', 'label' => 'Actions (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014023', 'label' => 'Autres creances rattach. a des participat. (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014024', 'label' => 'Creances rattach. a des stes en participat. (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014025', 'label' => 'Vers.a eff./titre de participation non liberes (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014026', 'label' => 'Titres immobilises (droit de propriete) (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014027', 'label' => 'Titres immobilises (droit de creance) (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014028', 'label' => 'Depots et cautionnements verses (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014029', 'label' => 'Autres creances immobilisees (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014031', 'label' => 'Autres Actifs Non Courants (Amortissement/Provision N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60014032 + F60014033 + F60014034 + F60014035'],
                    ['id' => 'F60014032', 'label' => 'Frais preliminaires (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014033', 'label' => 'Charges a repartir (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60014035', 'label' => 'ecarts de conversion (Amortissement/Provision N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Colonne N-1 (Net)
            [
                'sectionTitle' => 'Actifs non courants (Net N-1)',
                'fields' => [
                    // Champs "Net N-1" : Généralement "Brut N-1 - Amortissements/Provisions N-1"
                    ['id' => 'F60015001', 'label' => 'Actifs non courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013001 - F60014001'],
                    ['id' => 'F60015002', 'label' => 'Actifs immobilisés (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013002 - F60014002'],
                    ['id' => 'F60015003', 'label' => 'Immobilisations Incorporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013003 - F60014003'],
                    ['id' => 'F60015004', 'label' => 'Investissement recherche et developpement (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013004 - F60014004'],
                    ['id' => 'F60015005', 'label' => 'Concess. marque,brevet,licence,marque (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013005 - F60014005'],
                    ['id' => 'F60015006', 'label' => 'Logiciels (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013006 - F60014006'],
                    ['id' => 'F60015007', 'label' => 'Fonds commercial (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013007 - F60014007'],
                    ['id' => 'F60015008', 'label' => 'Droit au bail (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013008 - F60014008'],
                    ['id' => 'F60015009', 'label' => 'Autres Immobilisations Incorporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013009 - F60014009'],
                    ['id' => 'F60015010', 'label' => 'Immobilisations Incorporelles en cours (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013010 - F60014010'],
                    ['id' => 'F60015011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013011 - F60014011'],
                    ['id' => 'F60015012', 'label' => 'Immobilisations corporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013012 - F60014012'],
                    ['id' => 'F60015013', 'label' => 'Terrains (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013013 - F60014013'],
                    ['id' => 'F60015014', 'label' => 'Constructions (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013014 - F60014014'],
                    ['id' => 'F60015015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013015 - F60014015'],
                    ['id' => 'F60015016', 'label' => 'Materiel de transport (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013016 - F60014016'],
                    ['id' => 'F60015017', 'label' => 'Autres Immobilisations Corporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013017 - F60014017'],
                    ['id' => 'F60015018', 'label' => 'Immob. Corporelles en cours (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013018 - F60014018'],
                    ['id' => 'F60015019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013019 - F60014019'],
                    ['id' => 'F60015020', 'label' => 'Immob. a statut juridique particulier (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013020 - F60014020'],
                    ['id' => 'F60015021', 'label' => 'Immobilisations Financières (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013021 - F60014021'],
                    ['id' => 'F60015022', 'label' => 'Actions (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013022 - F60014022'],
                    ['id' => 'F60015023', 'label' => 'Autres creances rattach. a des participat. (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013023 - F60014023'],
                    ['id' => 'F60015024', 'label' => 'Creances rattach. a des stes en participat. (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013024 - F60014024'],
                    ['id' => 'F60015025', 'label' => 'Vers.a eff./titre de participation non liberes (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013025 - F60014025'],
                    ['id' => 'F60015026', 'label' => 'Titres immobilises (droit de propriete) (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013026 - F60014026'],
                    ['id' => 'F60015027', 'label' => 'Titres immobilises (droit de creance) (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013027 - F60014027'],
                    ['id' => 'F60015028', 'label' => 'Depots et cautionnements verses (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013028 - F60014028'],
                    ['id' => 'F60015029', 'label' => 'Autres creances immobilisees (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013029 - F60014029'],
                    ['id' => 'F60015030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013030 - F60014030'],
                    ['id' => 'F60015031', 'label' => 'Autres Actifs Non Courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013031 - F60014031'],
                    ['id' => 'F60015032', 'label' => 'Frais preliminaires (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013032 - F60014032'],
                    ['id' => 'F60015033', 'label' => 'Charges a repartir (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013033 - F60014033'],
                    ['id' => 'F60015034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013034 - F60014034'],
                    ['id' => 'F60015035', 'label' => 'ecarts de conversion (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013035 - F60014035'],
                ]
            ],
            // Actifs Courants (Net N-1)
            [
                'sectionTitle' => 'Actifs courants (Net N-1)',
                'fields' => [
                    ['id' => 'F60015036', 'label' => 'Actifs courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013036 - F60014036'],
                    ['id' => 'F60015037', 'label' => 'Stocks (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013037 - F60014037'],
                    ['id' => 'F60015038', 'label' => 'Stocks Matieres Premieres et Fournit. Liees (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013038 - F60014038'],
                    ['id' => 'F60015039', 'label' => 'Stocks Autres Approvisionnements (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013039 - F60014039'],
                    ['id' => 'F60015040', 'label' => 'Stocks En-cours de production de biens (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013040 - F60014040'],
                    ['id' => 'F60015041', 'label' => 'Stocks En-cours de production services (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013041 - F60014041'],
                    ['id' => 'F60015042', 'label' => 'Stocks de produits (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013042 - F60014042'],
                    ['id' => 'F60015043', 'label' => 'Stocks de marchandises (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013043 - F60014043'],
                    ['id' => 'F60015044', 'label' => 'Clients et Comptes Rattaches (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013044 - F60014044'],
                    ['id' => 'F60015045', 'label' => 'Clients et comptes rattaches (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013045 - F60014045'],
                    ['id' => 'F60015046', 'label' => 'Clients - effets a recevoir (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013046 - F60014046'],
                    ['id' => 'F60015047', 'label' => 'Clients douteux ou litigieux (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013047 - F60014047'],
                    ['id' => 'F60015048', 'label' => 'Creances/travaux non encore facturables (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013048 - F60014048'],
                    ['id' => 'F60015049', 'label' => 'Clt-pdts non encore factures (pdt a recev.) (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013049 - F60014049'],
                    ['id' => 'F60015050', 'label' => 'Autres Actifs Courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013050 - F60014050'],
                    ['id' => 'F60015051', 'label' => 'Fournisseurs debiteurs (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013051 - F60014051'],
                    ['id' => 'F60015052', 'label' => 'Personnel et comptes rattaches (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013052 - F60014052'],
                    ['id' => 'F60015053', 'label' => 'etat et collectivites publiques (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013053 - F60014053'],
                    ['id' => 'F60015054', 'label' => 'Societes du groupe et associes (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013054 - F60014054'],
                    ['id' => 'F60015055', 'label' => 'Debiteurs divers et Crediteurs divers (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013055 - F60014055'],
                    ['id' => 'F60015056', 'label' => 'Comptes transitoires ou d\'attente (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013056 - F60014056'],
                    ['id' => 'F60015057', 'label' => 'Comptes de regularisation (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013057 - F60014057'],
                    ['id' => 'F60015058', 'label' => 'Autres (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013058 - F60014058'],
                    ['id' => 'F60015059', 'label' => 'Placements et Autres Actifs Financiers (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013059 - F60014059'],
                    ['id' => 'F60015060', 'label' => 'Prets et autres creances Fin. courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013060 - F60014060'],
                    ['id' => 'F60015061', 'label' => 'Placements courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013061 - F60014061'],
                    ['id' => 'F60015062', 'label' => 'Regies d\'avances et accreditifs (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013062 - F60014062'],
                    ['id' => 'F60015063', 'label' => 'Autres (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013063 - F60014063'],
                    ['id' => 'F60015064', 'label' => 'Liquidites et equivalents de liquidites (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013064 - F60014064'],
                    ['id' => 'F60015065', 'label' => 'Banques, etabl. Financiers et assimiles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013065 - F60014065'],
                    ['id' => 'F60015066', 'label' => 'Caisse (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013066 - F60014066'],
                ]
            ],
            // Autres Postes et Total Actif (Net N-1)
            [
                'sectionTitle' => 'Autres Postes et Total Actif (Net N-1)',
                'fields' => [
                    ['id' => 'F60015067', 'label' => 'Autres Postes des Actifs du Bilan (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013067 - F60014067'],
                    ['id' => 'F60015068', 'label' => 'Total des actifs (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013068 - F60014068'],
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
                ['id' => 'F60020001', 'label' => 'Capitaux propres', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020002 + F60020010 + F60020014 + F60020015 + F60020017 + F60020019 + F60020020 + F60020021 + F60020022 + F60020023 + F60020025'],
                ['id' => 'F60020002', 'label' => 'Capital social ou individuel', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020010', 'label' => 'Primes d\'émission, de fusion, d\'apport', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020014', 'label' => 'Écarts de réévaluation', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020015', 'label' => 'Réserves', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020016 + F60020017 + F60020018'],
                ['id' => 'F60020016', 'label' => 'Réserve légale', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020017', 'label' => 'Autres réserves', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020018', 'label' => 'Réserve statutaire', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020019', 'label' => 'Report à nouveau', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020020', 'label' => 'Résultat net de l\'exercice', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020021', 'label' => 'Subventions d\'investissement', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020022', 'label' => 'Provisions réglementées', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020023', 'label' => 'Autres fonds propres', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020025', 'label' => 'Écarts de conversion passif', 'type' => 'number', 'required' => true, 'default' => 0.00],
            ]
        ],
        // Section Passifs Non Courants
        [
            'sectionTitle' => 'Passifs non courants',
            'fields' => [
                ['id' => 'F60020030', 'label' => 'Passifs non courants', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020031 + F60020039 + F60020043 + F60020046'],
                // Emprunts et dettes assimilées
                ['id' => 'F60020031', 'label' => 'Emprunts et dettes assimilées', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020032 + F60020033 + F60020034 + F60020035 + F60020036 + F60020037 + F60020038'],
                ['id' => 'F60020032', 'label' => 'Emprunts obligataires', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020033', 'label' => 'Emprunts auprès des établissements de crédit', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020034', 'label' => 'Emprunts et dettes financières divers', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020035', 'label' => 'Avances et acomptes reçus sur commandes', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020036', 'label' => 'Dettes rattachées à des participations', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020037', 'label' => 'Dettes rattachées à des sociétés en participation', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020038', 'label' => 'Autres emprunts et dettes assimilées', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Provisions pour risques et charges
                ['id' => 'F60020039', 'label' => 'Provisions pour risques et charges', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020040 + F60020041 + F60020042'],
                ['id' => 'F60020040', 'label' => 'Provisions pour litiges', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020041', 'label' => 'Provisions pour garanties données aux clients', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020042', 'label' => 'Autres provisions pour risques et charges', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Dépôts et cautionnements reçus
                ['id' => 'F60020043', 'label' => 'Dépôts et cautionnements reçus', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Autres passifs non courants
                ['id' => 'F60020046', 'label' => 'Autres passifs non courants', 'type' => 'number', 'required' => true, 'default' => 0.00],
            ]
        ],
        // Section Passifs Courants
        [
            'sectionTitle' => 'Passifs courants',
            'fields' => [
                ['id' => 'F60020050', 'label' => 'Passifs courants', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020051 + F60020056 + F60020062 + F60020067 + F60020071'],
                // Dettes fournisseurs et comptes rattachés
                ['id' => 'F60020051', 'label' => 'Fournisseurs et comptes rattachés', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020052 + F60020053 + F60020054 + F60020055'],
                ['id' => 'F60020052', 'label' => 'Fournisseurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020053', 'label' => 'Fournisseurs – effets à payer', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020054', 'label' => 'Fournisseurs débiteurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020055', 'label' => 'Autres fournisseurs et comptes rattachés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Dettes fiscales et sociales
                ['id' => 'F60020056', 'label' => 'Dettes fiscales et sociales', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020057 + F60020058 + F60020059 + F60020060 + F60020061'],
                ['id' => 'F60020057', 'label' => 'Personnel et comptes rattachés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020058', 'label' => 'État et collectivités publiques', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020059', 'label' => 'Organismes sociaux', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020060', 'label' => 'Organismes d\'assurances', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020061', 'label' => 'Autres dettes fiscales et sociales', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Autres dettes courantes
                ['id' => 'F60020062', 'label' => 'Autres dettes courantes', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020063 + F60020064 + F60020065 + F60020066'],
                ['id' => 'F60020063', 'label' => 'Associés – comptes courants créditeurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020064', 'label' => 'Dettes sur immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020065', 'label' => 'Dettes diverses', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60020066', 'label' => 'Comptes d\'attente créditeurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Produits constatés d\'avance
                ['id' => 'F60020067', 'label' => 'Produits constatés d\'avance', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Autres passifs courants
                ['id' => 'F60020071', 'label' => 'Autres passifs courants', 'type' => 'number', 'required' => true, 'default' => 0.00],
            ]
        ],
        // Section Total Passif
        [
            'sectionTitle' => 'Total passif',
            'fields' => [
                ['id' => 'F60020100', 'label' => 'Total passif', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020001 + F60020030 + F60020050'],
            ]
        ]
    ]
],
	
	'F6003_Compte_de_Resultat' => [
    'id' => 'F6003_Compte_de_Resultat',
    'title' => 'F6003 - Compte de Résultat',
    'description' => 'Saisie détaillée du compte de résultat pour l\'exercice N et N-1.',
    'sections' => [
        // Section Produits d'exploitation
        [
            'sectionTitle' => 'Produits d\'exploitation',
            'fields' => [
                ['id' => 'F60030001', 'label' => 'Chiffre d\'affaires hors taxes', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030002', 'label' => 'Production vendue (biens et services)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030003', 'label' => 'Production stockée', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030004', 'label' => 'Production immobilisée', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030005', 'label' => 'Subventions d\'exploitation', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030006', 'label' => 'Autres produits d\'exploitation', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030007', 'label' => 'Transferts de charges', 'type' => 'number', 'required' => true, 'default' => 0.00],
            ]
        ],
        // Section Charges d'exploitation
        [
            'sectionTitle' => 'Charges d\'exploitation',
            'fields' => [
                ['id' => 'F60030010', 'label' => 'Achats consommés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030011', 'label' => 'Services extérieurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030012', 'label' => 'Impôts, taxes et versements assimilés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030013', 'label' => 'Charges de personnel', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030014', 'label' => 'Dotations d\'exploitation', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030015', 'label' => 'Autres charges d\'exploitation', 'type' => 'number', 'required' => true, 'default' => 0.00],
            ]
        ],
        // Résultat d'exploitation
        [
            'sectionTitle' => 'Résultat d\'exploitation',
            'fields' => [
                ['id' => 'F60030020', 'label' => 'Résultat d\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' => '(F60030001 + F60030002 + F60030003 + F60030004 + F60030005 + F60030006 + F60030007) - (F60030010 + F60030011 + F60030012 + F60030013 + F60030014 + F60030015)'],
            ]
        ],
        // Section Produits financiers
        [
            'sectionTitle' => 'Produits financiers',
            'fields' => [
                ['id' => 'F60030021', 'label' => 'Revenus des participations', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030022', 'label' => 'Revenus des autres valeurs et créances', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030023', 'label' => 'Autres produits financiers', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030024', 'label' => 'Reprises sur provisions financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030025', 'label' => 'Gains de change', 'type' => 'number', 'required' => true, 'default' => 0.00],
            ]
        ],
        // Section Charges financières
        [
            'sectionTitle' => 'Charges financières',
            'fields' => [
                ['id' => 'F60030030', 'label' => 'Charges d\'intérêts', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030031', 'label' => 'Pertes de change', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030032', 'label' => 'Dotations financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60030033', 'label' => 'Autres charges financières', 'type' => 'number', 'required' => true, 'default' => 0.00],
            ]
        ],
        // Résultat financier
        [
            'sectionTitle' => 'Résultat financier',
            'fields' => [
                ['id' => 'F60030040', 'label' => 'Résultat financier', 'type' => 'number', 'calculated' => true, 'formula' => '(F60030021 + F60030022 + F60030023 + F60030024 + F60030025) - (F60030030 + F60030031 + F60030032 + F60030033)'],
            ]
        ],
        // Résultat courant avant impôt
        [
            'sectionTitle' => 'Résultat courant avant impôt',
            'fields' => [
                ['id' => 'F60030050', 'label' => 'Résultat courant avant impôt', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030020 + F60030040'],
            ]
        ],
        // Section Produits non courants
        [
            'sectionTitle' => 'Produits non courants',
            'fields' => [
                ['id' => 'F60030060', 'label' => 'Produits non courants', 'type' => 'number', 'required' => true, 'default' => 0.00]
            ]
        ],
        // Section Charges non courantes
        [
            'sectionTitle' => 'Charges non courantes',
            'fields' => [
                ['id' => 'F60030070', 'label' => 'Charges non courantes', 'type' => 'number', 'required' => true, 'default' => 0.00]
            ]
        ],
        // Résultat non courant
        [
            'sectionTitle' => 'Résultat non courant',
            'fields' => [
                ['id' => 'F60030080', 'label' => 'Résultat non courant', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030060 - F60030070'],
            ]
        ],
        // Résultat avant impôt
        [
            'sectionTitle' => 'Résultat avant impôt',
            'fields' => [
                ['id' => 'F60030090', 'label' => 'Résultat avant impôt', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030050 + F60030080'],
            ]
        ],
        // Impôt sur les résultats
        [
            'sectionTitle' => 'Impôt sur les résultats',
            'fields' => [
                ['id' => 'F60030100', 'label' => 'Impôt sur les résultats', 'type' => 'number', 'required' => true, 'default' => 0.00],
            ]
        ],
        // Résultat net
        [
            'sectionTitle' => 'Résultat net',
            'fields' => [
                ['id' => 'F60030110', 'label' => 'Résultat net', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030090 - F60030100'],
            ]
        ],
    ]
],

'F6004_Tableau_Flux_de_Tresorerie' => [
    'id' => 'F6004_Tableau_Flux_de_Tresorerie',
    'title' => 'F6004 - Tableau des flux de trésorerie',
    'description' => 'Saisie détaillée du tableau des flux de trésorerie pour l\'exercice N et N-1.',
    'sections' => [
        // Flux de trésorerie liés à l'exploitation
        [
            'sectionTitle' => 'Flux de trésorerie liés à l\'exploitation',
            'fields' => [
                ['id' => 'F60040001', 'label' => 'Résultat net de l\'exercice', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040002', 'label' => 'Dotations aux amortissements et provisions', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040003', 'label' => 'Valeurs comptables des éléments d\'actif cédés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040004', 'label' => 'Produits des cessions d\'éléments d\'actif', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040005', 'label' => 'Variation des stocks', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040006', 'label' => 'Variation des créances', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040007', 'label' => 'Variation des dettes fournisseurs', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040008', 'label' => 'Autres flux liés à l\'exploitation', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Flux net de trésorerie provenant de l'exploitation
                ['id' => 'F60040009', 'label' => 'Flux net de trésorerie provenant de l\'exploitation', 'type' => 'number', 'calculated' => true, 'formula' =>
                    'F60040001 + F60040002 - F60040003 + F60040004 + F60040005 + F60040006 + F60040007 + F60040008'],
            ]
        ],
        // Flux de trésorerie liés aux activités d'investissement
        [
            'sectionTitle' => 'Flux de trésorerie liés aux activités d\'investissement',
            'fields' => [
                ['id' => 'F60040010', 'label' => 'Décaissements pour acquisitions d\'immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040011', 'label' => 'Encaissements suite à cession d\'immobilisations', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040012', 'label' => 'Décaissements pour acquisitions de titres', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040013', 'label' => 'Encaissements suite à cession de titres', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040014', 'label' => 'Autres flux liés aux investissements', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Flux net de trésorerie lié aux investissements
                ['id' => 'F60040015', 'label' => 'Flux net de trésorerie lié aux investissements', 'type' => 'number', 'calculated' => true, 'formula' =>
                    '- F60040010 + F60040011 - F60040012 + F60040013 + F60040014'],
            ]
        ],
        // Flux de trésorerie liés aux activités de financement
        [
            'sectionTitle' => 'Flux de trésorerie liés aux activités de financement',
            'fields' => [
                ['id' => 'F60040016', 'label' => 'Augmentation de capital', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040017', 'label' => 'Encaissements d\'emprunts', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040018', 'label' => 'Remboursements d\'emprunts', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040019', 'label' => 'Dividendes versés', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040020', 'label' => 'Autres flux liés au financement', 'type' => 'number', 'required' => true, 'default' => 0.00],
                // Flux net de trésorerie lié au financement
                ['id' => 'F60040021', 'label' => 'Flux net de trésorerie lié au financement', 'type' => 'number', 'calculated' => true, 'formula' =>
                    'F60040016 + F60040017 - F60040018 - F60040019 + F60040020'],
            ]
        ],
        // Variation de la trésorerie
        [
            'sectionTitle' => 'Variation de la trésorerie',
            'fields' => [
                ['id' => 'F60040022', 'label' => 'Variation de la trésorerie', 'type' => 'number', 'calculated' => true, 'formula' =>
                    'F60040009 + F60040015 + F60040021'],
                ['id' => 'F60040023', 'label' => 'Trésorerie à l\'ouverture', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ['id' => 'F60040024', 'label' => 'Trésorerie à la clôture', 'type' => 'number', 'calculated' => true, 'formula' =>
                    'F60040023 + F60040022'],
            ]
        ]
    ]
],
    // Ajoutez d'autres définitions de formulaires ici si nécessaire
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie Formulaire - Liasse Fiscale</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <header>
        <h1>Application Liasse Fiscale</h1>
        <p>Bienvenue, <?php echo $username; ?> (<?php echo $role; ?>)</p>
        <nav>
            <a href="dashboard.php">Tableau de Bord</a> |
            <a href="logout.php">Déconnexion</a>
        </nav>
    </header>

    <main>
        <h2>Saisie des Formulaires</h2>

        <div class="message-box" id="generalMessageBox" style="display:none;"></div>

        <div class="selectors-container">
            <div class="form-group">
                <label for="selectEntreprise">Sélectionner une entreprise :</label>
                <select id="selectEntreprise" name="entreprise_id">
                    <option value="">-- Choisir une entreprise --</option>
                    <?php foreach ($user_entreprises as $entreprise): ?>
                        <option value="<?php echo htmlspecialchars($entreprise['id']); ?>"><?php echo htmlspecialchars($entreprise['nom_entreprise']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="selectExercice">Sélectionner un exercice :</label>
                <select id="selectExercice" name="exercice_id">
                    <option value="">-- Choisir un exercice --</option>
                    <?php foreach ($user_exercices as $exercice): ?>
                        <option value="<?php echo htmlspecialchars($exercice['id']); ?>"><?php echo htmlspecialchars($exercice['annee_declaration']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="selectFormType">Sélectionner un formulaire :</label>
                <select id="selectFormType" name="form_type">
                    <option value="">-- Choisir un formulaire --</option>
                    <?php foreach ($form_definitions as $key => $def): ?>
                        <option value="<?php echo htmlspecialchars($key); ?>"><?php echo htmlspecialchars($def['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button id="loadFormBtn">Charger le formulaire</button>
        </div>

        <div id="dynamicFormContainer" class="dynamic-form-container">
            </div>

        <button id="saveFormBtn" style="display:none;">Enregistrer les données</button>

    </main>

    <footer>
        <p>&copy; <?php echo date("Y"); ?> Votre Entreprise. Tous droits réservés.</p>
    </footer>

    <script>
        // Passer les définitions de formulaires du PHP au JavaScript
        const formDefinitions = <?php echo json_encode($form_definitions); ?>;

        // Fonction utilitaire pour afficher les messages
        function showMessage(element, message, type) {
            element.textContent = message;
            element.className = `message-box ${type}`;
            element.style.display = 'block';
            setTimeout(() => {
                element.style.display = 'none';
            }, 5000);
        }

        // --- Fonctions de CALCUL AUTOMATIQUE ---

        /**
         * Calcule la valeur d'un champ automatique basé sur sa formule.
         * @param {string} formula La formule JavaScript à évaluer.
         * @returns {number} La valeur calculée.
         */
        function calculateField(formula) {
            // Remplace les IDs des champs par leurs valeurs numériques
            const evaluatedFormula = formula.replace(/[Ff]\d{8}/g, function(match) {
                const inputElement = document.getElementById(match);
                // Retourne 0 si l'élément n'existe pas, n'a pas de valeur, ou si la valeur n'est pas un nombre valide
                return parseFloat(inputElement ? inputElement.value : '0') || 0;
            });

            try {
                // Utilisation de Function pour évaluer la chaîne comme du code JavaScript
                // Ceci est puissant mais doit être utilisé avec des formules dont la source est maîtrisée.
                const result = new Function('return ' + evaluatedFormula)();
                return parseFloat(result.toFixed(2)); // Arrondir à 2 décimales
            } catch (e) {
                console.error("Erreur de calcul pour la formule:", formula, "Erreur:", e);
                return 0; // Retourne 0 en cas d'erreur de calcul
            }
        }

        /**
         * Met à jour un champ calculé et propage les mises à jour aux champs qui en dépendent.
         * @param {string} fieldId L'ID du champ à mettre à jour.
         */
        function updateCalculatedField(fieldId) {
            // Trouver la définition du champ dans formDefinitions
            let fieldDef = null;
            for (const formKey in formDefinitions) {
                for (const section of formDefinitions[formKey].sections) {
                    fieldDef = section.fields.find(f => f.id === fieldId);
                    if (fieldDef) break;
                }
                if (fieldDef) break;
            }

            if (fieldDef && fieldDef.calculated && fieldDef.formula) {
                const calculatedValue = calculateField(fieldDef.formula);
                const inputElement = document.getElementById(fieldId);
                if (inputElement) {
                    // Mettre à jour la valeur du champ
                    inputElement.value = calculatedValue;

                    // Déclencher un événement 'input' artificiel pour propager les mises à jour
                    // aux autres champs calculés qui pourraient dépendre de celui-ci.
                    const event = new Event('input', { bubbles: true });
                    inputElement.dispatchEvent(event);
                }
            }
        }

        /**
         * Déclenche la mise à jour de TOUS les champs calculés du formulaire actif.
         * Utile au chargement initial des données ou après une modification importante.
         */
        function triggerAllCalculatedFieldsUpdate() {
            // Obtenir le type de formulaire actuellement sélectionné
            const currentFormType = selectFormType.value;
            if (!currentFormType || !formDefinitions[currentFormType]) {
                return; // Aucun formulaire sélectionné ou définition introuvable
            }

            const currentFormDef = formDefinitions[currentFormType];

            // Pour gérer les dépendances en cascade, on peut faire plusieurs passes
            // ou construire un graphe de dépendances (plus complexe).
            // Plusieurs passes est plus simple et suffisant pour la plupart des cas.
            for (let i = 0; i < 5; i++) { // 5 passes devraient suffire pour la plupart des chaînes de dépendances
                for (const section of currentFormDef.sections) {
                    for (const field of section.fields) {
                        if (field.calculated) {
                            updateCalculatedField(field.id);
                        }
                    }
                }
            }
        }

        /**
         * Attache les écouteurs d'événements aux champs de saisie pour déclencher les calculs.
         * Doit être appelée après le chargement du formulaire dynamique.
         */
        function attachCalculationEventListeners() {
            // Reconstruire les dépendances à chaque fois que le formulaire est chargé
            const fieldDependencies = {};
            const currentFormType = selectFormType.value;

            if (!currentFormType || !formDefinitions[currentFormType]) {
                console.warn("Impossible d'attacher les écouteurs: type de formulaire non sélectionné ou introuvable.");
                return;
            }

            const currentFormDef = formDefinitions[currentFormType];

            for (const section of currentFormDef.sections) {
                for (const field of section.fields) {
                    if (field.calculated && field.formula) {
                        // Extraire les IDs des champs de la formule (regex: F suivi de 8 chiffres)
                        const dependentFieldIds = field.formula.match(/[Ff]\d{8}/g);
                        if (dependentFieldIds) {
                            for (const depId of dependentFieldIds) {
                                if (!fieldDependencies[depId]) {
                                    fieldDependencies[depId] = [];
                                }
                                if (!fieldDependencies[depId].includes(field.id)) {
                                    fieldDependencies[depId].push(field.id);
                                }
                            }
                        }
                    }
                }
            }

            // Supprimer les écouteurs existants pour éviter les doublons si le formulaire est rechargé
            // (Nécessite de stocker les références des écouteurs, ou de recharger complètement le DOM du formulaire)
            // Une solution plus simple pour cette architecture est de s'assurer que le formulaire est vidé et recréé.
            // Si vous utilisez jQuery, vous pourriez utiliser $(document).off('input', '.form-group input').on(...)

            // Ajouter les écouteurs aux champs de saisie non calculés
            for (const section of currentFormDef.sections) {
                for (const field of section.fields) {
                    const inputElement = document.getElementById(field.id);
                    if (inputElement && !field.calculated) { // N'ajouter des écouteurs qu'aux champs de saisie manuelle
                        inputElement.removeEventListener('input', handleFieldInput); // Supprimer l'ancien pour éviter les multiples
                        inputElement.addEventListener('input', handleFieldInput);
                    }
                }
            }

            // Fonction de rappel pour l'écouteur d'événement 'input'
            function handleFieldInput(event) {
                const changedFieldId = event.target.id;
                // Mettre à jour tous les champs qui dépendent de ce champ
                if (fieldDependencies[changedFieldId]) {
                    fieldDependencies[changedFieldId].forEach(depFieldId => {
                        updateCalculatedField(depFieldId);
                    });
                }
            }

            // Exécuter les calculs initiaux une fois que tous les écouteurs sont attachés
            triggerAllCalculatedFieldsUpdate();
        }

        // --- Fin des fonctions de CALCUL AUTOMATIQUE ---


        // Votre code JavaScript existant pour la gestion des sélecteurs et AJAX

        $(document).ready(function() {
            const selectEntreprise = $('#selectEntreprise');
            const selectExercice = $('#selectExercice');
            const selectFormType = $('#selectFormType');
            const loadFormBtn = $('#loadFormBtn');
            const dynamicFormContainer = $('#dynamicFormContainer');
            const saveFormBtn = $('#saveFormBtn');
            const generalMessageBox = $('#generalMessageBox');

            // Fonction pour charger et afficher le formulaire dynamique
            async function loadDynamicForm(formType) {
                if (!formType) {
                    dynamicFormContainer.empty();
                    saveFormBtn.hide();
                    return;
                }

                const formDef = formDefinitions[formType];
                if (!formDef) {
                    showMessage(generalMessageBox, 'Définition de formulaire introuvable.', 'error');
                    dynamicFormContainer.empty();
                    saveFormBtn.hide();
                    return;
                }

                let formHtml = `<form id="dynamicForm" data-form-type="${formType}">`;
                formHtml += `<h3>${formDef.title}</h3>`;
                formHtml += `<p>${formDef.description}</p>`;

                formDef.sections.forEach(section => {
                    formHtml += `<h4>${section.sectionTitle}</h4><div class="form-section-fields">`;
                    section.fields.forEach(field => {
                        const requiredAttr = field.required ? 'required' : '';
                        const readOnlyAttr = field.calculated ? 'readonly' : ''; // Champ calculé = lecture seule
                        const defaultValue = field.default !== undefined ? field.default : '';

                        formHtml += `
                            <div class="form-group">
                                <label for="${field.id}">${field.label}:</label>
                                <input type="${field.type}" id="${field.id}" name="${field.id}" value="${defaultValue}" step="0.01" ${requiredAttr} ${readOnlyAttr}>
                            </div>
                        `;
                    });
                    formHtml += `</div>`;
                });
                formHtml += `</form>`;
                dynamicFormContainer.html(formHtml);
                saveFormBtn.show();

                // APRES QUE LE FORMULAIRE EST CHARGÉ DANS LE DOM, ATTACHER LES ÉCOUTEURS DE CALCUL
                attachCalculationEventListeners();
            }

            // Fonction pour charger les données existantes pour un formulaire
            async function loadExistingFormData(declarationId, formType) {
                if (!declarationId || !formType) {
                    // console.log("Pas d'ID de déclaration ou de type de formulaire pour charger les données.");
                    // Si aucune déclaration n'est sélectionnée, charger le formulaire vide et réinitialiser les calculs
                    loadDynamicForm(formType); // Recharger le formulaire vide pour réinitialiser
                    return;
                }

                try {
                    const response = await fetch('php/api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'getFormData',
                            declaration_id: declarationId,
                            form_type: formType
                        }),
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`Erreur lors du chargement des données existantes: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }

                    const result = await response.json();
                    // console.log('API Response (Load Form Data):', result);

                    if (result.success && result.data) {
                        // Charger le formulaire dynamique vide en premier
                        await loadDynamicForm(formType);

                        // Remplir les champs du formulaire avec les données existantes
                        for (const fieldId in result.data) {
                            const inputElement = document.getElementById(fieldId);
                            if (inputElement) {
                                inputElement.value = result.data[fieldId];
                                // Déclencher un événement 'input' pour chaque champ chargé pour propager les calculs
                                const event = new Event('input', { bubbles: true });
                                inputElement.dispatchEvent(event);
                            }
                        }
                        showMessage(generalMessageBox, 'Données chargées avec succès.', 'success');
                        triggerAllCalculatedFieldsUpdate(); // S'assurer que tous les calculs sont faits après chargement
                    } else if (result.success && Object.keys(result.data).length === 0) {
                        showMessage(generalMessageBox, 'Aucune donnée existante trouvée pour ce formulaire. Saisie vierge.', 'info');
                        await loadDynamicForm(formType); // Charger le formulaire vide pour la saisie
                    } else {
                        showMessage(generalMessageBox, result.message || 'Erreur lors du chargement des données existantes.', 'error');
                        await loadDynamicForm(formType); // Charger le formulaire vide en cas d'erreur ou d'absence de données
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement des données du formulaire:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur lors du chargement: ${error.message}.`, 'error');
                    await loadDynamicForm(formType); // Charger le formulaire vide en cas d'erreur
                }
            }


            // Événements pour les sélecteurs
            selectEntreprise.on('change', function() {
                const entrepriseId = $(this).val();
                selectExercice.val(''); // Réinitialiser l'exercice
                selectFormType.val(''); // Réinitialiser le formulaire
                selectFormType.prop('disabled', true); // Désactiver le sélecteur de formulaire
                dynamicFormContainer.empty(); // Vider le conteneur du formulaire
                saveFormBtn.hide(); // Cacher le bouton d'enregistrement

                if (entrepriseId) {
                    // Activer le sélecteur d'exercice si une entreprise est sélectionnée
                    // (Si vos exercices sont filtrés par entreprise, vous devrez recharger les options ici)
                    // Pour l'instant, on suppose que tous les exercices sont disponibles indépendamment de l'entreprise.
                }
            });

            selectExercice.on('change', function() {
                const exerciceId = $(this).val();
                selectFormType.val(''); // Réinitialiser le formulaire
                selectFormType.prop('disabled', true); // Désactiver le sélecteur de formulaire
                dynamicFormContainer.empty(); // Vider le conteneur du formulaire
                saveFormBtn.hide(); // Cacher le bouton d'enregistrement

                const entrepriseId = selectEntreprise.val();
                if (entrepriseId && exerciceId) {
                    // Activer le sélecteur de formulaire si entreprise et exercice sont sélectionnés
                    selectFormType.prop('disabled', false);
                }
            });

            loadFormBtn.on('click', async function() {
                const entrepriseId = selectEntreprise.val();
                const exerciceId = selectExercice.val();
                const formType = selectFormType.val();

                if (!entrepriseId || !exerciceId || !formType) {
                    showMessage(generalMessageBox, 'Veuillez sélectionner une entreprise, un exercice et un type de formulaire.', 'error');
                    return;
                }

                // Trouver ou créer une déclaration
                try {
                    const response = await fetch('php/api.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            action: 'getOrCreateDeclaration',
                            entreprise_id: entrepriseId,
                            exercice_id: exerciceId,
                            form_type: formType // Passer le form_type pour la création de la déclaration
                        }),
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        throw new Error(`Erreur lors de la récupération/création de la déclaration: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }

                    const result = await response.json();
                    if (result.success) {
                        showMessage(generalMessageBox, result.message, 'success');
                        const declarationId = result.declaration_id;

                        // Stocker l'ID de la déclaration dans un attribut data sur le conteneur du formulaire pour un accès facile
                        dynamicFormContainer.data('declaration-id', declarationId);

                        // Charger les données existantes pour ce formulaire
                        await loadExistingFormData(declarationId, formType);
                    } else {
                        showMessage(generalMessageBox, result.message || 'Erreur lors de la récupération/création de la déclaration.', 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement/création de la déclaration:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur: ${error.message}.`, 'error');
                }
            });


            // Gestion de l'enregistrement du formulaire dynamique
            saveFormBtn.on('click', async function() {
                const declarationId = dynamicFormContainer.data('declaration-id');
                const formType = $('#dynamicForm').data('form-type');
                if (!declarationId || !formType) {
                    showMessage(generalMessageBox, 'Aucune déclaration ou formulaire sélectionné à enregistrer.', 'error');
                    return;
                }

                const formData = {};
                // Collecter toutes les valeurs des champs du formulaire dynamique
                $('#dynamicForm input').each(function() {
                    const fieldId = $(this).attr('id');
                    const value = $(this).val();
                    // Convertir en nombre si c'est un champ numérique, sinon garder la chaîne
                    formData[fieldId] = $(this).attr('type') === 'number' ? parseFloat(value) || 0 : value;
                });

                try {
                    const response = await fetch('php/api.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            action: 'saveFormData',
                            declaration_id: declarationId,
                            form_type: formType,
                            form_data: formData
                        }),
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        // Tenter d'analyser l'erreur pour un message plus clair
                        console.error('Erreur brute du serveur:', response.status, errorText);
                        if (errorText.includes("Fatal error") || errorText.includes("<br />") || errorText.includes("<font")) {
                            throw new Error(`Erreur serveur inattendue. Veuillez réessayer. (Détails en console)`);
                        }
                        throw new Error(`Erreur serveur: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }

                    const result = await response.json();
                    console.log('API Response (Submit Form Data):', result);

                    if (result.success) {
                        showMessage(generalMessageBox, result.message, 'success');
                        loadExistingFormData(declarationId, formType); // Recharger les données pour s'assurer de l'état actuel et rafraîchir les calculs
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors de l'enregistrement des données.", 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de l\'envoi du formulaire dynamique:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            });

            // Initialisation au chargement de la page: Désactiver le sélecteur de formulaire au début
            selectFormType.prop('disabled', true);
        });
    </script>
</body>
</html>