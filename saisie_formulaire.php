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
                    ['id' => 'F60012001', 'label' => 'Actifs non courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012002 + F60012031'],
                    ['id' => 'F60012002', 'label' => 'Actifs immobilisés (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012003 + F60012012 + F60012021'],
                    // Immobilisations Incorporelles (Net)
                    ['id' => 'F60012003', 'label' => 'Immobilisations Incorporelles (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012004 + F60012005 + F60012006 + F60012007 + F60012008 + F60012009 + F60012010 + F60012011'],
                    ['id' => 'F60012004', 'label' => 'Investissement recherche et developpement (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012005', 'label' => 'Concess. marque,brevet,licence,marque (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012006', 'label' => 'Logiciels (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012007', 'label' => 'Fonds commercial (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012008', 'label' => 'Droit au bail (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012009', 'label' => 'Autres Immobilisations Incorporelles (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012010', 'label' => 'Immobilisations Incorporelles en cours (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Immobilisations corporelles (Net)
                    ['id' => 'F60012012', 'label' => 'Immobilisations corporelles (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012013 + F60012014 + F60012015 + F60012016 + F60012017 + F60012018 + F60012019 + F60012020'],
                    ['id' => 'F60012013', 'label' => 'Terrains (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012014', 'label' => 'Constructions (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012016', 'label' => 'Materiel de transport (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012017', 'label' => 'Autres Immobilisations Corporelles (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012018', 'label' => 'Immob. Corporelles en cours (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012020', 'label' => 'Immob. a statut juridique particulier (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Immobilisations Financieres (Net)
                    ['id' => 'F60012021', 'label' => 'Immobilisations Financières (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012022 + F60012023 + F60012024 + F60012025 + F60012026 + F60012027 + F60012028 + F60012029 + F60012030'],
                    ['id' => 'F60012022', 'label' => 'Actions (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012023', 'label' => 'Autres creances rattach. a des participat. (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012024', 'label' => 'Creances rattach. a des stes en participat. (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012025', 'label' => 'Vers.a eff./titre de participation non liberes (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012026', 'label' => 'Titres immobilises (droit de propriete) (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012027', 'label' => 'Titres immobilises (droit de creance) (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012028', 'label' => 'Depots et cautionnements verses (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012029', 'label' => 'Autres creances immobilisees (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Actifs Non Courants (Net)
                    ['id' => 'F60012031', 'label' => 'Autres Actifs Non Courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012032 + F60012033 + F60012034 + F60012035'],
                    ['id' => 'F60012032', 'label' => 'Frais preliminaires (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012033', 'label' => 'Charges a repartir (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012035', 'label' => 'ecarts de conversion (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Section Actifs courants (Net)
            [
                'sectionTitle' => 'Actifs courants (Net)',
                'fields' => [
                    ['id' => 'F60012036', 'label' => 'Actifs courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012037 + F60012044 + F60012050 + F60012059 + F60012064'],
                    // Stocks (Net)
                    ['id' => 'F60012037', 'label' => 'Stocks (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012038 + F60012039 + F60012040 + F60012041 + F60012042 + F60012043'],
                    ['id' => 'F60012038', 'label' => 'Stocks Matieres Premieres et Fournit. Liees (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012039', 'label' => 'Stocks Autres Approvisionnements (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012040', 'label' => 'Stocks En-cours de production de biens (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012041', 'label' => 'Stocks En-cours de production services (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012042', 'label' => 'Stocks de produits (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012043', 'label' => 'Stocks de marchandises (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Clients et Comptes Rattaches (Net)
                    ['id' => 'F60012044', 'label' => 'Clients et Comptes Rattaches (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012045 + F60012046 + F60012047 + F60012048 + F60012049'],
                    ['id' => 'F60012045', 'label' => 'Clients et comptes rattaches (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012046', 'label' => 'Clients - effets a recevoir (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012047', 'label' => 'Clients douteux ou litigieux (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012048', 'label' => 'Creances/travaux non encore facturables (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012049', 'label' => 'Clt-pdts non encore factures (pdt a recev.) (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Actifs Courants (Net)
                    ['id' => 'F60012050', 'label' => 'Autres Actifs Courants (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012051 + F60012052 + F60012053 + F60012054 + F60012055 + F60012056 + F60012057 + F60012058'],
                    ['id' => 'F60012051', 'label' => 'Fournisseurs debiteurs (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012052', 'label' => 'Personnel et comptes rattaches (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012053', 'label' => 'etat et collectivites publiques (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012054', 'label' => 'Societes du groupe et associes (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012055', 'label' => 'Debiteurs divers et Crediteurs divers (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012056', 'label' => 'Comptes transitoires ou d\'attente (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012057', 'label' => 'Comptes de regularisation (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012058', 'label' => 'Autres (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Placements et Autres Actifs Financiers (Net)
                    ['id' => 'F60012059', 'label' => 'Placements et Autres Actifs Financiers (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012060 + F60012061 + F60012062 + F60012063'],
                    ['id' => 'F60012060', 'label' => 'Prets et autres creances Fin. courants (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012061', 'label' => 'Placements courants (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012062', 'label' => 'Regies d\'avances et accreditifs (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012063', 'label' => 'Autres (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Liquidites et equivalents de liquidites (Net)
                    ['id' => 'F60012064', 'label' => 'Liquidites et equivalents de liquidites (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012065 + F60012066'],
                    ['id' => 'F60012065', 'label' => 'Banques, etabl. Financiers et assimiles (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012066', 'label' => 'Caisse (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Autres Postes des Actifs du Bilan (Net) et Total (Net)
            [
                'sectionTitle' => 'Autres Postes et Total Actif (Net)',
                'fields' => [
                    ['id' => 'F60012067', 'label' => 'Autres Postes des Actifs du Bilan (Net)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60012068', 'label' => 'Total des actifs (Net)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60012001 + F60012036 + F60012067'],
                ]
            ],
             // Section Actifs non courants (Net N-1)
            [
                'sectionTitle' => 'Actifs non courants (Net N-1)',
                'fields' => [
                    ['id' => 'F60013001', 'label' => 'Actifs non courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013002 + F60013031'],
                    ['id' => 'F60013002', 'label' => 'Actifs immobilisés (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013003 + F60013012 + F60013021'],
                    // Immobilisations Incorporelles (Net N-1)
                    ['id' => 'F60013003', 'label' => 'Immobilisations Incorporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013004 + F60013005 + F60013006 + F60013007 + F60013008 + F60013009 + F60013010 + F60013011'],
                    ['id' => 'F60013004', 'label' => 'Investissement recherche et developpement (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013005', 'label' => 'Concess. marque,brevet,licence,marque (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013006', 'label' => 'Logiciels (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013007', 'label' => 'Fonds commercial (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013008', 'label' => 'Droit au bail (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013009', 'label' => 'Autres Immobilisations Incorporelles (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013010', 'label' => 'Immobilisations Incorporelles en cours (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013011', 'label' => 'Av. et Ac. Verses/Cmde.Immob.Incorp. (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Immobilisations corporelles (Net N-1)
                    ['id' => 'F60013012', 'label' => 'Immobilisations corporelles (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013013 + F60013014 + F60013015 + F60013016 + F60013017 + F60013018 + F60013019 + F60013020'],
                    ['id' => 'F60013013', 'label' => 'Terrains (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013014', 'label' => 'Constructions (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013015', 'label' => 'Inst. Tech., materiel et outillages Industriels (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013016', 'label' => 'Materiel de transport (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013017', 'label' => 'Autres Immobilisations Corporelles (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013018', 'label' => 'Immob. Corporelles en cours (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013019', 'label' => 'Av. et Ac. Verses/Commande Immob.Corp. (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013020', 'label' => 'Immob. a statut juridique particulier (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Immobilisations Financieres (Net N-1)
                    ['id' => 'F60013021', 'label' => 'Immobilisations Financières (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013022 + F60013023 + F60013024 + F60013025 + F60013026 + F60013027 + F60013028 + F60013029 + F60013030'],
                    ['id' => 'F60013022', 'label' => 'Actions (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013023', 'label' => 'Autres creances rattach. a des participat. (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013024', 'label' => 'Creances rattach. a des stes en participat. (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013025', 'label' => 'Vers.a eff./titre de participation non liberes (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013026', 'label' => 'Titres immobilises (droit de propriete) (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013027', 'label' => 'Titres immobilises (droit de creance) (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013028', 'label' => 'Depots et cautionnements verses (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013029', 'label' => 'Autres creances immobilisees (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013030', 'label' => 'Vers.a eff./Titres immobilises non liberes (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Actifs Non Courants (Net N-1)
                    ['id' => 'F60013031', 'label' => 'Autres Actifs Non Courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013032 + F60013033 + F60013034 + F60013035'],
                    ['id' => 'F60013032', 'label' => 'Frais preliminaires (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013033', 'label' => 'Charges a repartir (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013034', 'label' => 'Frais d\'emission et primes de Remb. Empts (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013035', 'label' => 'ecarts de conversion (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Section Actifs courants (Net N-1)
            [
                'sectionTitle' => 'Actifs courants (Net N-1)',
                'fields' => [
                    ['id' => 'F60013036', 'label' => 'Actifs courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013037 + F60013044 + F60013050 + F60013059 + F60013064'],
                    // Stocks (Net N-1)
                    ['id' => 'F60013037', 'label' => 'Stocks (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013038 + F60013039 + F60013040 + F60013041 + F60013042 + F60013043'],
                    ['id' => 'F60013038', 'label' => 'Stocks Matieres Premieres et Fournit. Liees (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013039', 'label' => 'Stocks Autres Approvisionnements (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013040', 'label' => 'Stocks En-cours de production de biens (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013041', 'label' => 'Stocks En-cours de production services (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013042', 'label' => 'Stocks de produits (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013043', 'label' => 'Stocks de marchandises (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Clients et Comptes Rattaches (Net N-1)
                    ['id' => 'F60013044', 'label' => 'Clients et Comptes Rattaches (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013045 + F60013046 + F60013047 + F60013048 + F60013049'],
                    ['id' => 'F60013045', 'label' => 'Clients et comptes rattaches (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013046', 'label' => 'Clients - effets a recevoir (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013047', 'label' => 'Clients douteux ou litigieux (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013048', 'label' => 'Creances/travaux non encore facturables (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013049', 'label' => 'Clt-pdts non encore factures (pdt a recev.) (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Actifs Courants (Net N-1)
                    ['id' => 'F60013050', 'label' => 'Autres Actifs Courants (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013051 + F60013052 + F60013053 + F60013054 + F60013055 + F60013056 + F60013057 + F60013058'],
                    ['id' => 'F60013051', 'label' => 'Fournisseurs debiteurs (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013052', 'label' => 'Personnel et comptes rattaches (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013053', 'label' => 'etat et collectivites publiques (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013054', 'label' => 'Societes du groupe et associes (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013055', 'label' => 'Debiteurs divers et Crediteurs divers (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013056', 'label' => 'Comptes transitoires ou d\'attente (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013057', 'label' => 'Comptes de regularisation (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013058', 'label' => 'Autres (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Placements et Autres Actifs Financiers (Net N-1)
                    ['id' => 'F60013059', 'label' => 'Placements et Autres Actifs Financiers (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013060 + F60013061 + F60013062 + F60013063'],
                    ['id' => 'F60013060', 'label' => 'Prets et autres creances Fin. courants (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013061', 'label' => 'Placements courants (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013062', 'label' => 'Regies d\'avances et accreditifs (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013063', 'label' => 'Autres (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Liquidites et equivalents de liquidites (Net N-1)
                    ['id' => 'F60013064', 'label' => 'Liquidites et equivalents de liquidites (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013065 + F60013066'],
                    ['id' => 'F60013065', 'label' => 'Banques, etabl. Financiers et assimiles (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013066', 'label' => 'Caisse (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Autres Postes des Actifs du Bilan (Net N-1) et Total (Net N-1)
            [
                'sectionTitle' => 'Autres Postes et Total Actif (Net N-1)',
                'fields' => [
                    ['id' => 'F60013067', 'label' => 'Autres Postes des Actifs du Bilan (Net N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60013068', 'label' => 'Total des actifs (Net N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60013001 + F60013036 + F60013067'],
                ]
            ],
        ]
    ],
    // F6002 : Bilan – passif (Cas général) - Pages 29-33
    'F6002_Bilan_Passif' => [
        'id' => 'F6002_Bilan_Passif',
        'title' => 'F6002 - Bilan Passif',
        'description' => 'Saisie des éléments du passif du bilan pour l\'exercice N et N-1.',
        'sections' => [
            // Capitaux propres (Net Exercice)
            [
                'sectionTitle' => 'Capitaux Propres (Net Exercice)',
                'fields' => [
                    ['id' => 'F60020001', 'label' => 'Capitaux propres (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020006 + F60020007'],
                    ['id' => 'F60020002', 'label' => 'Capital social (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020003', 'label' => 'Réserves (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020004', 'label' => 'Autres capitaux propres (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020005', 'label' => 'Résultats reportés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020006', 'label' => 'Capitaux propres avant résultat de l\'exercice (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020002 + F60020003 + F60020004 + F60020005'],
                    ['id' => 'F60020007', 'label' => 'Résultat de l\'exercice (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00], // Ce champ peut être lié à F6003.resultat_net
                ]
            ],
            // Passifs non courants (Net Exercice)
            [
                'sectionTitle' => 'Passifs non courants (Net Exercice)',
                'fields' => [
                    ['id' => 'F60020008', 'label' => 'Total Passifs (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020009 + F60020031'],
                    ['id' => 'F60020009', 'label' => 'Passifs non courants (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020010 + F60020019 + F60020022'],
                    // Emprunts (Net Exercice)
                    ['id' => 'F60020010', 'label' => 'Emprunts (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020011 + F60020012 + F60020013 + F60020014 + F60020015 + F60020016 + F60020017 + F60020018'],
                    ['id' => 'F60020011', 'label' => 'Emprunts obligataires (assortis de sûretés) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020012', 'label' => 'Empts auprès d\'étab.Fin. (assortis de sûretés) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020013', 'label' => 'Empts auprès d\'étab.Fin. (assorti de sûretés) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020014', 'label' => 'Empts et dettes assorties de condit. particulières (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020015', 'label' => 'Emprunts non assortis de sûretés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020016', 'label' => 'Dettes rattachées à des participations (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020017', 'label' => 'Dépôts et cautionnements reçus (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020018', 'label' => 'Autres emprunts et dettes (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Passifs Financiers (Net Exercice)
                    ['id' => 'F60020019', 'label' => 'Autres Passifs Financiers (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020020 + F60020021'],
                    ['id' => 'F60020020', 'label' => 'Écarts de conversion (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020021', 'label' => 'Autres passifs financiers (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Provisions (Net Exercice)
                    ['id' => 'F60020022', 'label' => 'Provisions (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020023 + F60020024 + F60020025 + F60020026 + F60020027 + F60020028 + F60020029 + F60020030'],
                    ['id' => 'F60020023', 'label' => 'Provisions pour risques (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020024', 'label' => 'Prov.pour charges à répartir/plusieurs exercices (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020025', 'label' => 'Prov.pour retraites et obligations similaires (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020026', 'label' => 'Provisions d\'origine réglementaire (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020027', 'label' => 'Provisions pour impôts (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020028', 'label' => 'Prov.pour renouvellement des immobilisations (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020029', 'label' => 'Provisions pour amortissement (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020030', 'label' => 'Autres provisions pour charges (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Passifs courants (Net Exercice)
            [
                'sectionTitle' => 'Passifs courants (Net Exercice)',
                'fields' => [
                    ['id' => 'F60020031', 'label' => 'Passifs courants (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020032 + F60020038 + F60020047'],
                    // Fournisseurs et Comptes Rattachés (Net Exercice)
                    ['id' => 'F60020032', 'label' => 'Fournisseurs et Comptes Rattachés (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020033 + F60020034 + F60020035 + F60020036 + F60020037'],
                    ['id' => 'F60020033', 'label' => 'Fournisseurs d\'exploitation (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020034', 'label' => 'Fournisseurs d\'exploitation - effets à payer (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020035', 'label' => 'Fournisseurs d\'immobilisations (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020036', 'label' => 'Fournisseurs d\'immobilisations - effets à payer (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020037', 'label' => 'Fournisseurs - factures non parvenues (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres passifs courants (Net Exercice)
                    ['id' => 'F60020038', 'label' => 'Autres passifs courants (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020039 + F60020040 + F60020041 + F60020042 + F60020043 + F60020044 + F60020045 + F60020046'],
                    ['id' => 'F60020039', 'label' => 'Clients créditeurs (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020040', 'label' => 'Sociétés du groupe et associés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020041', 'label' => 'État et collectivités publiques (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020042', 'label' => 'Sociétés du groupe et associés (Net Exercice) (Duplicate)', 'type' => 'number', 'required' => true, 'default' => 0.00], // Duplicate ID in PDF, keep for now
                    ['id' => 'F60020043', 'label' => 'Débiteurs divers et Créditeurs divers (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020044', 'label' => 'Comptes transitoires ou d\'attente (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020045', 'label' => 'Comptes de régularisation (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020046', 'label' => 'Provisions courantes pour risques et charges (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Concours Bancaires et Autres Passifs Financiers (Net Exercice)
                    ['id' => 'F60020047', 'label' => 'Concours Bancaires et Autres Passifs Financiers (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020048 + F60020049 + F60020050 + F60020051'],
                    ['id' => 'F60020048', 'label' => 'Emprunts et autres dettes financières courants (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020049', 'label' => 'Emprunts échus et impayés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020050', 'label' => 'Intérêts courus (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020051', 'label' => 'Banques, établissements financiers et assimilés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Autres Postes et Total Passif (Net Exercice)
            [
                'sectionTitle' => 'Autres Postes et Total Passif (Net Exercice)',
                'fields' => [
                    ['id' => 'F60020052', 'label' => 'Autres Postes des Capitaux Propres et Passifs du Bilan (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60020053', 'label' => 'Total des capitaux propres et passifs (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60020001 + F60020008 + F60020052'],
                ]
            ],
            // Section Capitaux propres (Net Exercice - 1)
            [
                'sectionTitle' => 'Capitaux Propres (Net Exercice - 1)',
                'fields' => [
                    ['id' => 'F60021001', 'label' => 'Capitaux propres (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021006 + F60021007'], 
                    ['id' => 'F60021002', 'label' => 'Capital social (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021003', 'label' => 'Réserves (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021004', 'label' => 'Autres capitaux propres (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021005', 'label' => 'Résultats reportés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021006', 'label' => 'Capitaux propres avant résultat de l\'exercice (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021002 + F60021003 + F60021004 + F60021005'], 
                    ['id' => 'F60021007', 'label' => 'Résultat de l\'exercice (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Passifs non courants (Net Exercice - 1)
            [
                'sectionTitle' => 'Passifs non courants (Net Exercice - 1)',
                'fields' => [
                    ['id' => 'F60021008', 'label' => 'Total Passifs (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021009 + F60021031'],
                    ['id' => 'F60021009', 'label' => 'Passifs non courants (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021010 + F60021019 + F60021022'],
                    // Emprunts (Net Exercice - 1)
                    ['id' => 'F60021010', 'label' => 'Emprunts (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021011 + F60021012 + F60021013 + F60021014 + F60021015 + F60021016 + F60021017 + F60021018'],
                    ['id' => 'F60021011', 'label' => 'Emprunts obligataires (assortis de sûretés) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021012', 'label' => 'Empts auprès d\'étab.Fin. (assortis de sûretés) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021013', 'label' => 'Empts auprès d\'étab.Fin. (assorti de sûretés) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021014', 'label' => 'Empts et dettes assorties de condit. particulières (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021015', 'label' => 'Emprunts non assortis de sûretés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021016', 'label' => 'Dettes rattachées à des participations (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021017', 'label' => 'Dépôts et cautionnements reçus (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021018', 'label' => 'Autres emprunts et dettes (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres Passifs Financiers (Net Exercice - 1)
                    ['id' => 'F60021019', 'label' => 'Autres Passifs Financiers (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021020 + F60021021'],
                    ['id' => 'F60021020', 'label' => 'Écarts de conversion (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021021', 'label' => 'Autres passifs financiers (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Provisions (Net Exercice - 1)
                    ['id' => 'F60021022', 'label' => 'Provisions (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021023 + F60021024 + F60021025 + F60021026 + F60021027 + F60021028 + F60021029 + F60021030'],
                    ['id' => 'F60021023', 'label' => 'Provisions pour risques (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021024', 'label' => 'Prov.pour charges à répartir/plusieurs exercices (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021025', 'label' => 'Prov.pour retraites et obligations similaires (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021026', 'label' => 'Provisions d\'origine réglementaire (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021027', 'label' => 'Provisions pour impôts (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021028', 'label' => 'Prov.pour renouvellement des immobilisations (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021029', 'label' => 'Provisions pour amortissement (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021030', 'label' => 'Autres provisions pour charges (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Passifs courants (Net Exercice - 1)
            [
                'sectionTitle' => 'Passifs courants (Net Exercice - 1)',
                'fields' => [
                    ['id' => 'F60021031', 'label' => 'Passifs courants (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021032 + F60021038 + F60021047'],
                    // Fournisseurs et Comptes Rattachés (Net Exercice - 1)
                    ['id' => 'F60021032', 'label' => 'Fournisseurs et Comptes Rattachés (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021033 + F60021034 + F60021035 + F60021036 + F60021037'],
                    ['id' => 'F60021033', 'label' => 'Fournisseurs d\'exploitation (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021034', 'label' => 'Fournisseurs d\'exploitation - effets à payer (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021035', 'label' => 'Fournisseurs d\'immobilisations (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021036', 'label' => 'Fournisseurs d\'immobilisations - effets à payer (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021037', 'label' => 'Fournisseurs - factures non parvenues (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Autres passifs courants (Net Exercice - 1)
                    ['id' => 'F60021038', 'label' => 'Autres passifs courants (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021039 + F60021040 + F60021041 + F60021042 + F60021043 + F60021044 + F60021045 + F60021046'],
                    ['id' => 'F60021039', 'label' => 'Clients créditeurs (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021040', 'label' => 'Sociétés du groupe et associés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021041', 'label' => 'État et collectivités publiques (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021042', 'label' => 'Sociétés du groupe et associés (Net Exercice - 1) (Duplicate)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021043', 'label' => 'Débiteurs divers et Créditeurs divers (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021044', 'label' => 'Comptes transitoires ou d\'attente (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021045', 'label' => 'Comptes de régularisation (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021046', 'label' => 'Provisions courantes pour risques et charges (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    // Concours Bancaires et Autres Passifs Financiers (Net Exercice - 1)
                    ['id' => 'F60021047', 'label' => 'Concours Bancaires et Autres Passifs Financiers (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021048 + F60021049 + F60021050 + F60021051'],
                    ['id' => 'F60021048', 'label' => 'Emprunts et autres dettes financières courants (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021049', 'label' => 'Emprunts échus et impayés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021050', 'label' => 'Intérêts courus (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021051', 'label' => 'Banques, établissements financiers et assimilés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            // Autres Postes et Total Passif (Net Exercice - 1)
            [
                'sectionTitle' => 'Autres Postes et Total Passif (Net Exercice - 1)',
                'fields' => [
                    ['id' => 'F60021052', 'label' => 'Autres Postes des Capitaux Propres et Passifs du Bilan (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60021053', 'label' => 'Total des capitaux propres et passifs (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60021001 + F60021008 + F60021052'],
                ]
            ],
        ]
    ],
    // F6003 : Etat de résultat (Cas général) - Pages 34-42
    'F6003_Etat_Resultat' => [
        'id' => 'F6003_Etat_Resultat',
        'title' => 'F6003 - État de Résultat',
        'description' => 'Saisie du compte de produits et charges pour l\'exercice N et N-1.',
        'sections' => [
            [
                'sectionTitle' => 'Produits d\'exploitation (Net Exercice)',
                'fields' => [
                    ['id' => 'F60030001', 'label' => 'Produits d\'exploitation (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030002 + F60030014 + F60030015'],
                    ['id' => 'F60030002', 'label' => 'Revenus (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030003 + F60030006'],
                    ['id' => 'F60030003', 'label' => 'Ventes nettes des marchandises (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030004 - F60030005'],
                    ['id' => 'F60030004', 'label' => 'Ventes de Marchandises (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030005', 'label' => 'Rabais, Remises et Ristournes (3R) accordés/ventes de Marchandises (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030006', 'label' => 'Ventes nettes de la production (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030007 + F60030008 + F60030009 + F60030010 + F60030011 + F60030012 - F60030013'],
                    ['id' => 'F60030007', 'label' => 'Ventes de Produits Finis (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030008', 'label' => 'Ventes de Produits Intermédiaires (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030009', 'label' => 'Ventes de Produits Résiduels (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030010', 'label' => 'Ventes des Travaux (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030011', 'label' => 'Ventes des Études et Prestations de Services (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030012', 'label' => 'Produits des Activités Annexes (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030013', 'label' => 'Rabais, Remises et Ristournes (3R) accordés/ventes de la Production (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030014', 'label' => 'Production immobilisée (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030015', 'label' => 'Autres produits d\'exploitation (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030016 + F60030017 + F60030018 + F60030019'],
                    ['id' => 'F60030016', 'label' => 'Produits divers ordin.(sans gains/cession immo.) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030017', 'label' => 'Subventions d\'exploitation et d\'équilibre (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030018', 'label' => 'Reprises sur amortissements et provisions (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030019', 'label' => 'Transferts de charges (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            [
                'sectionTitle' => 'Charges d\'exploitation (Net Exercice)',
                'fields' => [
                    ['id' => 'F60030020', 'label' => 'Charges d\'exploitation (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030021 + F60030025 + F60030029 + F60030036 + F60030046 + F60030053'],
                    ['id' => 'F60030021', 'label' => 'Variation stocks produits finis et encours (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030022 + F60030023 + F60030024'],
                    ['id' => 'F60030022', 'label' => 'Variations des en-cours de production biens (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030023', 'label' => 'Variation des en-cours de production services (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030024', 'label' => 'Variation des stocks de produits (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030025', 'label' => 'Achats de marchandises consommées (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030026 - F60030027 + F60030028'],
                    ['id' => 'F60030026', 'label' => 'Achats de marchandises (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030027', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus sur achats marchandises (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030028', 'label' => 'Variation des stocks de marchandises (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030029', 'label' => 'Achats d\'approvisionnements consommés (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030030 + F60030031 - F60030032 - F60030033 + F60030034 + F60030035'],
                    ['id' => 'F60030030', 'label' => 'Achats stockés-Mat.Premières et Fournit. liées (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030031', 'label' => 'Achats stockés - Autres approvisionnements (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030032', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus/achats Mat.Premières et Fournit. liées (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030033', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus/achats autres approvisionnements (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030034', 'label' => 'Var.de stocks Mat.Premières et Fournitures (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030035', 'label' => 'Var.de stocks des autres approvisionnements (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030036', 'label' => 'Charges de personnel (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030037 + F60030038 + F60030039 + F60030040 + F60030041 + F60030042 + F60030043 + F60030044 + F60030045'],
                    ['id' => 'F60030037', 'label' => 'Salaires et compléments de salaires (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030038', 'label' => 'Appointements et compléments d\'appoint. (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030039', 'label' => 'Indemnités représentatives de frais (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030040', 'label' => 'Commissions au personnel (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030041', 'label' => 'Rémun.des administrateurs, gérants et associés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030042', 'label' => 'Ch.connexes sal., appoint., comm. et rémun. (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030043', 'label' => 'Charges sociales légales (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030044', 'label' => 'Ch.PL/Modif.Compt.à imputer au Réslt de l\'exerc.ou Activ.abandonnée (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030045', 'label' => 'Autres charges de PL et autres charges sociales (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030046', 'label' => 'Dotations aux amortissements et aux provisions (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030047 + F60030048 + F60030049 + F60030050 + F60030051 + F60030052'],
                    ['id' => 'F60030047', 'label' => 'Dot.amort. et prov.-Ch.ord.(autres que Fin.) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030048', 'label' => 'Dot. aux résorptions des charges reportées (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030049', 'label' => 'Dot.Prov. Risques et Charges d\'exploitation (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030050', 'label' => 'Dot.Prov.dépréc.immob. Incorp. et Corporelles (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030051', 'label' => 'Dot.Prov.dépréc.actifs courants (autres que Val.Mobil.de Placem. et équiv. de liquidités) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030052', 'label' => 'Dot.aux amort. et prov./Modif.Compt. à imputer au Réslt de l\'exerc. ou Activ. abandonnée (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030053', 'label' => 'Autres charges d\'exploitation (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030054 + F60030055 + F60030056 + F60030057 + F60030058 + F60030059 + F60030060'],
                    ['id' => 'F60030054', 'label' => 'Achats d’études et prestations services (y compris achat de sous-traitance production) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030055', 'label' => 'Achats de matériel, équipements et travaux (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030056', 'label' => 'Achats non stockés non rattachés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030057', 'label' => 'Services extérieurs (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030058', 'label' => 'Autres services extérieurs (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030059', 'label' => 'Charges diverses ordinaires (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030060', 'label' => 'Impôts, taxes et versements assimilés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            [
                'sectionTitle' => 'Résultats et Impôts (Net Exercice)',
                'fields' => [
                    ['id' => 'F60030061', 'label' => 'Resultat d\'exploitation (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030001 - F60030020'],
                    ['id' => 'F60030062', 'label' => 'Charges financières nettes (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030063 + F60030064'],
                    ['id' => 'F60030063', 'label' => 'Charges financières (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030064', 'label' => 'Dot.amort. et provisions - charges financières (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030065', 'label' => 'Produits des placements (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030066 + F60030067 + F60030068'],
                    ['id' => 'F60030066', 'label' => 'Produits financiers (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030067', 'label' => 'Reprise/prov.(à inscrire dans les pdts financ.) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030068', 'label' => 'Transferts de charges financières (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030069', 'label' => 'Autres gains ordinaires (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030070 + F60030071'],
                    ['id' => 'F60030070', 'label' => 'Produits nets sur cessions d\'immobilisations (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030071', 'label' => 'Autres gains/élém.non récurrents ou except. (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030072', 'label' => 'Autres pertes ordinaires (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030073 + F60030074 + F60030075'],
                    ['id' => 'F60030073', 'label' => 'Charges Nettes/cession immobilisations (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030074', 'label' => 'Autres pertes/élém.non récurrents ou except. (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030075', 'label' => 'Réduction de valeur (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030076', 'label' => 'Résultat des Activités Ordinaires avant Impôt (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030061 - F60030062 + F60030065 + F60030069 - F60030072'],
                    ['id' => 'F60030077', 'label' => 'Impôt sur les bénéfices (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030078 + F60030079'],
                    ['id' => 'F60030078', 'label' => 'Impôts/Bénéfices calculés/Résultat/activ./ ord. (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030079', 'label' => 'Autres impôts/Bénéfice (régimes particuliers) (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030080', 'label' => 'Résultat des Activités Ordinaires après Impôt (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030076 - F60030077'],
                    ['id' => 'F60030081', 'label' => 'Elements extraordinaires (Gains/pertes) (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030082 - F60030083'],
                    ['id' => 'F60030082', 'label' => 'Gains extraordinaires (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030083', 'label' => 'Pertes extraordinaires (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030084', 'label' => 'Résultat net de l\'exercice (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030080 + F60030081'],
                    ['id' => 'F60030085', 'label' => 'Effets des modif. Comptables (net d\'impôt) (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030086 - F60030087'],
                    ['id' => 'F60030086', 'label' => 'Effet positif/Modif.C.affectant Réslts Reportés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030087', 'label' => 'Effet négatif/Modif.C.affectant Réslts Reportés (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030088', 'label' => 'Autres Postes des Comptes de Résultat (Net Exercice)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030089', 'label' => 'Resultat apres modifications comptables (Net Exercice)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030084 + F60030085 + F60030088'],
                ]
            ],
            [
                'sectionTitle' => 'Produits d\'exploitation (Net Exercice - 1)',
                'fields' => [
                    ['id' => 'F60030090', 'label' => 'Produits d\'exploitation (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030091 + F60030103 + F60030104'],
                    ['id' => 'F60030091', 'label' => 'Revenus (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030092 + F60030095'],
                    ['id' => 'F60030092', 'label' => 'Ventes nettes des marchandises (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030093 - F60030094'],
                    ['id' => 'F60030093', 'label' => 'Ventes de Marchandises (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030094', 'label' => 'Rabais, Remises et Ristournes (3R) accordés/ventes de Marchandises (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030095', 'label' => 'Ventes nettes de la production (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030096 + F60030097 + F60030098 + F60030099 + F60030100 + F60030101 - F60030102'],
                    ['id' => 'F60030096', 'label' => 'Ventes de Produits Finis (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030097', 'label' => 'Ventes de Produits Intermédiaires (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030098', 'label' => 'Ventes de Produits Résiduels (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030099', 'label' => 'Ventes des Travaux (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030100', 'label' => 'Ventes des Études et Prestations de Services (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030101', 'label' => 'Produits des Activités Annexes (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030102', 'label' => 'Rabais, Remises et Ristournes (3R) accordés/ventes de la Production (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030103', 'label' => 'Production immobilisée (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030104', 'label' => 'Autres produits d\'exploitation (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030105 + F60030106 + F60030107 + F60030108'],
                    ['id' => 'F60030105', 'label' => 'Produits divers ordin.(sans gains/cession immo.) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030106', 'label' => 'Subventions d\'exploitation et d\'équilibre (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030107', 'label' => 'Reprises sur amortissements et provisions (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030108', 'label' => 'Transferts de charges (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            [
                'sectionTitle' => 'Charges d\'exploitation (Net Exercice - 1)',
                'fields' => [
                    ['id' => 'F60030109', 'label' => 'Charges d\'exploitation (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030110 + F60030114 + F60030118 + F60030125 + F60030135 + F60030142'],
                    ['id' => 'F60030110', 'label' => 'Variation stocks produits finis et encours (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030111 + F60030112 + F60030113'],
                    ['id' => 'F60030111', 'label' => 'Variations des en-cours de production biens (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030112', 'label' => 'Variation des en-cours de production services (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030113', 'label' => 'Variation des stocks de produits (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030114', 'label' => 'Achats de marchandises consommées (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030115 - F60030116 + F60030117'],
                    ['id' => 'F60030115', 'label' => 'Achats de marchandises (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030116', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus sur achats marchandises (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030117', 'label' => 'Variation des stocks de marchandises (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030118', 'label' => 'Achats d\'approvisionnements consommés (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030119 + F60030120 - F60030121 - F60030122 + F60030123 + F60030124'],
                    ['id' => 'F60030119', 'label' => 'Achats stockés-Mat.Premières et Fournit. liées (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030120', 'label' => 'Achats stockés - Autres approvisionnements (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030121', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus/achats Mat.Premières et Fournit. liées (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030122', 'label' => 'Rabais, Remises et Ristournes (3R) obtenus/achats autres approvisionnements (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030123', 'label' => 'Var.de stocks Mat.Premières et Fournitures (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030124', 'label' => 'Var.de stocks des autres approvisionnements (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030125', 'label' => 'Charges de personnel (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030126 + F60030127 + F60030128 + F60030129 + F60030130 + F60030131 + F60030132 + F60030133 + F60030134'],
                    ['id' => 'F60030126', 'label' => 'Salaires et compléments de salaires (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030127', 'label' => 'Appointements et compléments d\'appoint. (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030128', 'label' => 'Indemnités représentatives de frais (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030129', 'label' => 'Commissions au personnel (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030130', 'label' => 'Rémun.des administrateurs, gérants et associés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030131', 'label' => 'Ch.connexes sal., appoint., comm. et rémun. (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030132', 'label' => 'Charges sociales légales (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030133', 'label' => 'Ch.PL/Modif.Compt.à imputer au Réslt de l\'exerc.ou Activ.abandonnée (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030134', 'label' => 'Autres charges de PL et autres charges sociales (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030135', 'label' => 'Dotations aux amortissements et aux provisions (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030136 + F60030137 + F60030138 + F60030139 + F60030140 + F60030141'],
                    ['id' => 'F60030136', 'label' => 'Dot.amort. et prov.-Ch.ord.(autres que Fin.) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030137', 'label' => 'Dot. aux résorptions des charges reportées (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030138', 'label' => 'Dot.Prov. Risques et Charges d\'exploitation (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030139', 'label' => 'Dot.Prov.dépréc.immob. Incorp. et Corporelles (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030140', 'label' => 'Dot.Prov.dépréc.actifs courants (autres que Val.Mobil.de Placem. et équiv. de liquidités) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030141', 'label' => 'Dot.aux amort. et prov./Modif.Compt. à imputer au Réslt de l\'exerc. ou Activ. abandonnée (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030142', 'label' => 'Autres charges d\'exploitation (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030143 + F60030144 + F60030145 + F60030146 + F60030147 + F60030148 + F60030149'],
                    ['id' => 'F60030143', 'label' => 'Achats d’études et prestations services (y compris achat de sous-traitance production) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030144', 'label' => 'Achats de matériel, équipements et travaux (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030145', 'label' => 'Achats non stockés non rattachés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030146', 'label' => 'Services extérieurs (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030147', 'label' => 'Autres services extérieurs (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030148', 'label' => 'Charges diverses ordinaires (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030149', 'label' => 'Impôts, taxes et versements assimilés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                ]
            ],
            [
                'sectionTitle' => 'Résultats et Impôts (Net Exercice - 1)',
                'fields' => [
                    ['id' => 'F60030150', 'label' => 'Resultat d\'exploitation (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030090 - F60030109'],
                    ['id' => 'F60030151', 'label' => 'Charges financières nettes (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030152 + F60030153'],
                    ['id' => 'F60030152', 'label' => 'Charges financières (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030153', 'label' => 'Dot.amort. et provisions - charges financières (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030154', 'label' => 'Produits des placements (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030155 + F60030156 + F60030157'],
                    ['id' => 'F60030155', 'label' => 'Produits financiers (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030156', 'label' => 'Reprise/prov.(à inscrire dans les pdts financ.) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030157', 'label' => 'Transferts de charges financières (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030158', 'label' => 'Autres gains ordinaires (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030159 + F60030160'],
                    ['id' => 'F60030159', 'label' => 'Produits nets sur cessions d\'immobilisations (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030160', 'label' => 'Autres gains/élém.non récurrents ou except. (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030161', 'label' => 'Autres pertes ordinaires (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030162 + F60030163 + F60030164'],
                    ['id' => 'F60030162', 'label' => 'Charges Nettes/cession immobilisations (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030163', 'label' => 'Autres pertes/élém.non récurrents ou except. (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030164', 'label' => 'Réduction de valeur (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030165', 'label' => 'Résultat des Activités Ordinaires avant Impôt (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030150 - F60030151 + F60030154 + F60030158 - F60030161'],
                    ['id' => 'F60030166', 'label' => 'Impôt sur les bénéfices (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030167 + F60030168'],
                    ['id' => 'F60030167', 'label' => 'Impôts/Bénéfices calculés/Résultat/activ./ ord. (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030168', 'label' => 'Autres impôts/Bénéfice (régimes particuliers) (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030169', 'label' => 'Résultat des Activités Ordinaires après Impôt (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030165 - F60030166'],
                    ['id' => 'F60030170', 'label' => 'Elements extraordinaires (Gains/pertes) (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030171 - F60030172'],
                    ['id' => 'F60030171', 'label' => 'Gains extraordinaires (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030172', 'label' => 'Pertes extraordinaires (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030173', 'label' => 'Résultat net de l\'exercice (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030169 + F60030170'],
                    ['id' => 'F60030174', 'label' => 'Effets des modif. Comptables (net d\'impôt) (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030175 - F60030176'],
                    ['id' => 'F60030175', 'label' => 'Effet positif/Modif.C.affectant Réslts Reportés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030176', 'label' => 'Effet négatif/Modif.C.affectant Réslts Reportés (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030177', 'label' => 'Autres Postes des Comptes de Résultat (Net Exercice - 1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60030178', 'label' => 'Resultat apres modifications comptables (Net Exercice - 1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030173 + F60030174 + F60030177'],
                ]
            ]
        ]
    ],
    // F6004 : Tableau des Flux de Trésorerie (TFT) - Pages 43-46
    'F6004_TFT' => [
        'id' => 'F6004_TFT',
        'title' => 'F6004 - Tableau des Flux de Trésorerie (TFT)',
        'description' => 'Saisie du tableau des flux de trésorerie pour l\'exercice N et N-1 (méthode directe).',
        'sections' => [
            [
                'sectionTitle' => 'Flux de trésorerie des activités opérationnelles (N)',
                'fields' => [
                    ['id' => 'F60040001', 'label' => 'Encaissements provenant des ventes de biens et services (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040002', 'label' => 'Encaissements provenant des redevances, honoraires, commissions (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040003', 'label' => 'Encaissements provenant des autres produits des activités opérationnelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040004', 'label' => 'Décaissements des achats de biens et services (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040005', 'label' => 'Décaissements des charges de personnel (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040006', 'label' => 'Décaissements des autres charges des activités opérationnelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040007', 'label' => 'Flux net de trésorerie des activités opérationnelles (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60040001 + F60040002 + F60040003 - F60040004 - F60040005 - F60040006'],
                ]
            ],
            [
                'sectionTitle' => 'Flux de trésorerie des activités d\'investissement (N)',
                'fields' => [
                    ['id' => 'F60040008', 'label' => 'Encaissements provenant des cessions d\'immobilisations corporelles et incorporelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040009', 'label' => 'Encaissements provenant des cessions de titres de participation (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040010', 'label' => 'Décaissements des acquisitions d\'immobilisations corporelles et incorporelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040011', 'label' => 'Décaissements des acquisitions de titres de participation (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040012', 'label' => 'Flux net de trésorerie des activités d\'investissement (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60040008 + F60040009 - F60040010 - F60040011'],
                ]
            ],
            [
                'sectionTitle' => 'Flux de trésorerie des activités de financement (N)',
                'fields' => [
                    ['id' => 'F60040013', 'label' => 'Encaissements provenant de l\'émission de titres de capital (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040014', 'label' => 'Encaissements provenant des emprunts (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040015', 'label' => 'Décaissements des remboursements d\'emprunts (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040016', 'label' => 'Décaissements des dividendes versés (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040017', 'label' => 'Flux net de trésorerie des activités de financement (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60040013 + F60040014 - F60040015 - F60040016'],
                ]
            ],
            [
                'sectionTitle' => 'Total (N)',
                'fields' => [
                    ['id' => 'F60040018', 'label' => 'Variation nette de trésorerie (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60040007 + F60040012 + F60040017'],
                    ['id' => 'F60040019', 'label' => 'Trésorerie d\'ouverture (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60040020', 'label' => 'Trésorerie de clôture (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60040018 + F60040019'],
                ]
            ],
             // Flux de trésorerie des activités opérationnelles (N-1)
            [
                'sectionTitle' => 'Flux de trésorerie des activités opérationnelles (N-1)',
                'fields' => [
                    ['id' => 'F60041001', 'label' => 'Encaissements provenant des ventes de biens et services (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041002', 'label' => 'Encaissements provenant des redevances, honoraires, commissions (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041003', 'label' => 'Encaissements provenant des autres produits des activités opérationnelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041004', 'label' => 'Décaissements des achats de biens et services (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041005', 'label' => 'Décaissements des charges de personnel (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041006', 'label' => 'Décaissements des autres charges des activités opérationnelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041007', 'label' => 'Flux net de trésorerie des activités opérationnelles (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60041001 + F60041002 + F60041003 - F60041004 - F60041005 - F60041006'],
                ]
            ],
            [
                'sectionTitle' => 'Flux de trésorerie des activités d\'investissement (N-1)',
                'fields' => [
                    ['id' => 'F60041008', 'label' => 'Encaissements provenant des cessions d\'immobilisations corporelles et incorporelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041009', 'label' => 'Encaissements provenant des cessions de titres de participation (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041010', 'label' => 'Décaissements des acquisitions d\'immobilisations corporelles et incorporelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041011', 'label' => 'Décaissements des acquisitions de titres de participation (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041012', 'label' => 'Flux net de trésorerie des activités d\'investissement (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60041008 + F60041009 - F60041010 - F60041011'],
                ]
            ],
            [
                'sectionTitle' => 'Flux de trésorerie des activités de financement (N-1)',
                'fields' => [
                    ['id' => 'F60041013', 'label' => 'Encaissements provenant de l\'émission de titres de capital (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041014', 'label' => 'Encaissements provenant des emprunts (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041015', 'label' => 'Décaissements des remboursements d\'emprunts (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041016', 'label' => 'Décaissements des dividendes versés (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041017', 'label' => 'Flux net de trésorerie des activités de financement (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60041013 + F60041014 - F60041015 - F60041016'],
                ]
            ],
            [
                'sectionTitle' => 'Total (N-1)',
                'fields' => [
                    ['id' => 'F60041018', 'label' => 'Variation nette de trésorerie (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60041007 + F60041012 + F60041017'],
                    ['id' => 'F60041019', 'label' => 'Trésorerie d\'ouverture (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60041020', 'label' => 'Trésorerie de clôture (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60041018 + F60041019'],
                ]
            ],
        ]
    ],
    // F6005 : Etat des Soldes de Gestion (ESG) - Pages 47-51
    'F6005_ESG' => [
        'id' => 'F6005_ESG',
        'title' => 'F6005 - État des Soldes de Gestion',
        'description' => 'Saisie de l\'état des soldes de gestion pour l\'exercice N et N-1.',
        'sections' => [
            [
                'sectionTitle' => 'Exercice N',
                'fields' => [
                    ['id' => 'F60050001', 'label' => 'Marge commerciale (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030003 - F60030025'], // Ventes nettes marchandises - Achats marchandises consommées
                    ['id' => 'F60050002', 'label' => 'Production de l\'exercice (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030006 + F60030014 + F60030021'], // Ventes production + Prod. Immobilisée + Var. stocks produits
                    ['id' => 'F60050003', 'label' => 'Valeur ajoutée (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60050001 + F60050002 - F60030029 - F60030053'], // Marge Commerciale + Production - Achats approvisionnements consommés - Autres charges d'exploitation
                    ['id' => 'F60050004', 'label' => 'Excédent Brut d\'Exploitation (EBE) (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60050003 - F60030036 - F60030060 + F60030017'], // VA - Ch. Personnel - Impôts, taxes - Subventions d'exploitation
                    ['id' => 'F60050005', 'label' => 'Résultat d\'exploitation (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030061'], // Reprend le résultat d'exploitation de F6003
                    ['id' => 'F60050006', 'label' => 'Résultat courant avant impôts (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030076'], // Reprend le résultat avant impôt de F6003
                    ['id' => 'F60050007', 'label' => 'Résultat net de l\'exercice (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030084'], // Reprend le résultat net de F6003
                ]
            ],
            [
                'sectionTitle' => 'Exercice N-1',
                'fields' => [
                    ['id' => 'F60051001', 'label' => 'Marge commerciale (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030092 - F60030114'],
                    ['id' => 'F60051002', 'label' => 'Production de l\'exercice (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030095 + F60030103 + F60030110'],
                    ['id' => 'F60051003', 'label' => 'Valeur ajoutée (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60051001 + F60051002 - F60030118 - F60030142'],
                    ['id' => 'F60051004', 'label' => 'Excédent Brut d\'Exploitation (EBE) (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60051003 - F60030125 - F60030149 + F60030106'],
                    ['id' => 'F60051005', 'label' => 'Résultat d\'exploitation (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030150'],
                    ['id' => 'F60051006', 'label' => 'Résultat courant avant impôts (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030165'],
                    ['id' => 'F60051007', 'label' => 'Résultat net de l\'exercice (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60030173'],
                ]
            ]
        ]
    ],
    // F6006 : Tableau de détermination du résultat fiscal (Cas général) - Pages 52-57
    'F6006_Resultat_Fiscal' => [
        'id' => 'F6006_Resultat_Fiscal',
        'title' => 'F6006 - Tableau de Détermination du Résultat Fiscal',
        'description' => 'Saisie des éléments de détermination du résultat fiscal pour l\'exercice N et N-1.',
        'sections' => [
            [
                'sectionTitle' => 'Exercice N',
                'fields' => [
                    ['id' => 'F60060001', 'label' => 'Résultat net comptable (N)', 'type' => 'number', 'required' => true, 'default' => 0.00], // Correspond à F60030084 ou F60030089 si ajusté
                    ['id' => 'F60060002', 'label' => 'Total des réintégrations (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60060003 + F60060004 + F60060005 + F60060006 + F60060007 + F60060008 + F60060009 + F60060010 + F60060011 + F60060012 + F60060013 + F60060014 + F60060015 + F60060016 + F60060017'],
                    ['id' => 'F60060003', 'label' => 'Amortissements non déductibles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060004', 'label' => 'Provisions non déductibles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060005', 'label' => 'Charges non déductibles fiscalement (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060006', 'label' => 'Pénalités et amendes (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060007', 'label' => 'Impôts et taxes non déductibles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060008', 'label' => 'Dépassements des limites de déductibilité (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060009', 'label' => 'Avantages en nature (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060010', 'label' => 'Dons et libéralités non déductibles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060011', 'label' => 'Jetons de présence non déductibles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060012', 'label' => 'Loyers de crédits-bail non déductibles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060013', 'label' => 'Intérêts des comptes courants d\'associés non déductibles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060014', 'label' => 'Subventions reçues non imposables (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060015', 'label' => 'Produits des cessions d\'immobilisations non imposables (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060016', 'label' => 'Quote-part des plus-values latentes réintégrées (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060017', 'label' => 'Autres réintégrations (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060018', 'label' => 'Total des déductions (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60060019 + F60060020 + F60060021 + F60060022 + F60060023 + F60060024 + F60060025 + F60060026'],
                    ['id' => 'F60060019', 'label' => 'Produits à déduire (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060020', 'label' => 'Amortissements déductibles exceptionnels (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060021', 'label' => 'Revenus des titres de participation exonérés (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060022', 'label' => 'Plus-values sur cessions d\'immobilisations exonérées (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060023', 'label' => 'Déficit reportable (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060024', 'label' => 'Quote-part des charges redevables (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060025', 'label' => 'Charges à reporter (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060026', 'label' => 'Autres déductions (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60060027', 'label' => 'Résultat fiscal (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60060001 + F60060002 - F60060018'],
                ]
            ],
            [
                'sectionTitle' => 'Exercice N-1',
                'fields' => [
                    ['id' => 'F60061001', 'label' => 'Résultat net comptable (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061002', 'label' => 'Total des réintégrations (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60061003 + F60061004 + F60061005 + F60061006 + F60061007 + F60061008 + F60061009 + F60061010 + F60061011 + F60061012 + F60061013 + F60061014 + F60061015 + F60061016 + F60061017'],
                    ['id' => 'F60061003', 'label' => 'Amortissements non déductibles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061004', 'label' => 'Provisions non déductibles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061005', 'label' => 'Charges non déductibles fiscalement (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061006', 'label' => 'Pénalités et amendes (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061007', 'label' => 'Impôts et taxes non déductibles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061008', 'label' => 'Dépassements des limites de déductibilité (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061009', 'label' => 'Avantages en nature (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061010', 'label' => 'Dons et libéralités non déductibles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061011', 'label' => 'Jetons de présence non déductibles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061012', 'label' => 'Loyers de crédits-bail non déductibles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061013', 'label' => 'Intérêts des comptes courants d\'associés non déductibles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061014', 'label' => 'Subventions reçues non imposables (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061015', 'label' => 'Produits des cessions d\'immobilisations non imposables (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061016', 'label' => 'Quote-part des plus-values latentes réintégrées (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061017', 'label' => 'Autres réintégrations (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061018', 'label' => 'Total des déductions (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60061019 + F60061020 + F60061021 + F60061022 + F60061023 + F60061024 + F60061025 + F60061026'],
                    ['id' => 'F60061019', 'label' => 'Produits à déduire (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061020', 'label' => 'Amortissements déductibles exceptionnels (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061021', 'label' => 'Revenus des titres de participation exonérés (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061022', 'label' => 'Plus-values sur cessions d\'immobilisations exonérées (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061023', 'label' => 'Déficit reportable (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061024', 'label' => 'Quote-part des charges redevables (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061025', 'label' => 'Charges à reporter (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061026', 'label' => 'Autres déductions (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60061027', 'label' => 'Résultat fiscal (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60061001 + F60061002 - F60061018'],
                ]
            ]
        ]
    ],
    // F6007 : Tableau des amortissements (Cas général) - Pages 58-61
    'F6007_Amortissements' => [
        'id' => 'F6007_Amortissements',
        'title' => 'F6007 - Tableau des Amortissements',
        'description' => 'Détail des amortissements de l\'exercice pour les immobilisations.',
        'sections' => [
            [
                'sectionTitle' => 'Immobilisations Incorporelles',
                'fields' => [
                    ['id' => 'F60070001', 'label' => 'Investissements Recherche et Développement (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070002', 'label' => 'Concessions, brevets, licences, marques (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070003', 'label' => 'Logiciels (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070004', 'label' => 'Fonds commercial (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070005', 'label' => 'Droit au bail (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070006', 'label' => 'Autres immobilisations incorporelles (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070007', 'label' => 'Immobilisations incorporelles en cours (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070008', 'label' => 'Avances et acomptes (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070009', 'label' => 'Total Incorporelles (Début N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60070001 + F60070002 + F60070003 + F60070004 + F60070005 + F60070006 + F60070007 + F60070008'],
                    ['id' => 'F60070010', 'label' => 'Dotations aux amortissements incorporelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070011', 'label' => 'Total Incorporelles (Fin N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60070009 + F60070010'],
                ]
            ],
            [
                'sectionTitle' => 'Immobilisations Corporelles',
                'fields' => [
                    ['id' => 'F60070012', 'label' => 'Terrains (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070013', 'label' => 'Constructions (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070014', 'label' => 'Installations techniques, matériel et outillage (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070015', 'label' => 'Matériel de transport (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070016', 'label' => 'Autres immobilisations corporelles (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070017', 'label' => 'Immobilisations corporelles en cours (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070018', 'label' => 'Avances et acomptes (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070019', 'label' => 'Total Corporelles (Début N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60070012 + F60070013 + F60070014 + F60070015 + F60070016 + F60070017 + F60070018'],
                    ['id' => 'F60070020', 'label' => 'Dotations aux amortissements corporelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60070021', 'label' => 'Total Corporelles (Fin N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60070019 + F60070020'],
                ]
            ],
            [
                'sectionTitle' => 'Total Général (N)',
                'fields' => [
                    ['id' => 'F60070022', 'label' => 'Total des Amortissements (Début N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60070009 + F60070019'],
                    ['id' => 'F60070023', 'label' => 'Total des Dotations (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60070010 + F60070020'],
                    ['id' => 'F60070024', 'label' => 'Total des Amortissements (Fin N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60070022 + F60070023'],
                ]
            ],
            // Exercice N-1 (similaire à N, mais avec des IDs distincts)
            [
                'sectionTitle' => 'Immobilisations Incorporelles (N-1)',
                'fields' => [
                    ['id' => 'F60071001', 'label' => 'Investissements Recherche et Développement (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071002', 'label' => 'Concessions, brevets, licences, marques (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071003', 'label' => 'Logiciels (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071004', 'label' => 'Fonds commercial (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071005', 'label' => 'Droit au bail (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071006', 'label' => 'Autres immobilisations incorporelles (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071007', 'label' => 'Immobilisations incorporelles en cours (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071008', 'label' => 'Avances et acomptes (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071009', 'label' => 'Total Incorporelles (Début N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60071001 + F60071002 + F60071003 + F60071004 + F60071005 + F60071006 + F60071007 + F60071008'],
                    ['id' => 'F60071010', 'label' => 'Dotations aux amortissements incorporelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071011', 'label' => 'Total Incorporelles (Fin N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60071009 + F60071010'],
                ]
            ],
            [
                'sectionTitle' => 'Immobilisations Corporelles (N-1)',
                'fields' => [
                    ['id' => 'F60071012', 'label' => 'Terrains (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071013', 'label' => 'Constructions (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071014', 'label' => 'Installations techniques, matériel et outillage (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071015', 'label' => 'Matériel de transport (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071016', 'label' => 'Autres immobilisations corporelles (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071017', 'label' => 'Immobilisations corporelles en cours (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071018', 'label' => 'Avances et acomptes (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071019', 'label' => 'Total Corporelles (Début N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60071012 + F60071013 + F60071014 + F60071015 + F60071016 + F60071017 + F60071018'],
                    ['id' => 'F60071020', 'label' => 'Dotations aux amortissements corporelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60071021', 'label' => 'Total Corporelles (Fin N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60071019 + F60071020'],
                ]
            ],
            [
                'sectionTitle' => 'Total Général (N-1)',
                'fields' => [
                    ['id' => 'F60071022', 'label' => 'Total des Amortissements (Début N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60071009 + F60071019'],
                    ['id' => 'F60071023', 'label' => 'Total des Dotations (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60071010 + F60071020'],
                    ['id' => 'F60071024', 'label' => 'Total des Amortissements (Fin N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60071022 + F60071023'],
                ]
            ]
        ]
    ],
    // F6008 : Tableau des Provisions (Cas général) - Pages 62-65
    'F6008_Provisions' => [
        'id' => 'F6008_Provisions',
        'title' => 'F6008 - Tableau des Provisions',
        'description' => 'Détail des provisions de l\'exercice et leur évolution.',
        'sections' => [
            [
                'sectionTitle' => 'Provisions pour Dépréciations des Actifs (N)',
                'fields' => [
                    ['id' => 'F60080001', 'label' => 'Immobilisations incorporelles (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080002', 'label' => 'Immobilisations corporelles (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080003', 'label' => 'Stocks (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080004', 'label' => 'Créances clients (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080005', 'label' => 'Titres de participation (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080006', 'label' => 'Titres et valeurs de placement (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080007', 'label' => 'Autres actifs (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080008', 'label' => 'Total Provisions pour dépréciations d\'actifs (Début N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60080001 + F60080002 + F60080003 + F60080004 + F60080005 + F60080006 + F60080007'],
                    ['id' => 'F60080009', 'label' => 'Dotations de l\'exercice (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080010', 'label' => 'Reprises de l\'exercice (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080011', 'label' => 'Total Provisions pour dépréciations d\'actifs (Fin N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60080008 + F60080009 - F60080010'],
                ]
            ],
            [
                'sectionTitle' => 'Provisions pour Risques et Charges (N)',
                'fields' => [
                    ['id' => 'F60080012', 'label' => 'Provisions pour litiges (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080013', 'label' => 'Provisions pour garanties données aux clients (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080014', 'label' => 'Provisions pour pertes de change (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080015', 'label' => 'Provisions pour restructuration (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080016', 'label' => 'Provisions pour impôts (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080017', 'label' => 'Autres provisions pour risques et charges (Début N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080018', 'label' => 'Total Provisions pour risques et charges (Début N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60080012 + F60080013 + F60080014 + F60080015 + F60080016 + F60080017'],
                    ['id' => 'F60080019', 'label' => 'Dotations de l\'exercice (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080020', 'label' => 'Reprises de l\'exercice (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60080021', 'label' => 'Total Provisions pour risques et charges (Fin N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60080018 + F60080019 - F60080020'],
                ]
            ],
            [
                'sectionTitle' => 'Total Général des Provisions (N)',
                'fields' => [
                    ['id' => 'F60080022', 'label' => 'Total Général des Provisions (Début N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60080008 + F60080018'],
                    ['id' => 'F60080023', 'label' => 'Total Général des Dotations (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60080009 + F60080019'],
                    ['id' => 'F60080024', 'label' => 'Total Général des Reprises (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60080010 + F60080020'],
                    ['id' => 'F60080025', 'label' => 'Total Général des Provisions (Fin N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60080022 + F60080023 - F60080024'],
                ]
            ],
            // Provisions pour Dépréciations des Actifs (N-1)
            [
                'sectionTitle' => 'Provisions pour Dépréciations des Actifs (N-1)',
                'fields' => [
                    ['id' => 'F60081001', 'label' => 'Immobilisations incorporelles (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081002', 'label' => 'Immobilisations corporelles (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081003', 'label' => 'Stocks (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081004', 'label' => 'Créances clients (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081005', 'label' => 'Titres de participation (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081006', 'label' => 'Titres et valeurs de placement (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081007', 'label' => 'Autres actifs (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081008', 'label' => 'Total Provisions pour dépréciations d\'actifs (Début N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60081001 + F60081002 + F60081003 + F60081004 + F60081005 + F60081006 + F60081007'],
                    ['id' => 'F60081009', 'label' => 'Dotations de l\'exercice (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081010', 'label' => 'Reprises de l\'exercice (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081011', 'label' => 'Total Provisions pour dépréciations d\'actifs (Fin N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60081008 + F60081009 - F60081010'],
                ]
            ],
            [
                'sectionTitle' => 'Provisions pour Risques et Charges (N-1)',
                'fields' => [
                    ['id' => 'F60081012', 'label' => 'Provisions pour litiges (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081013', 'label' => 'Provisions pour garanties données aux clients (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081014', 'label' => 'Provisions pour pertes de change (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081015', 'label' => 'Provisions pour restructuration (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081016', 'label' => 'Provisions pour impôts (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081017', 'label' => 'Autres provisions pour risques et charges (Début N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081018', 'label' => 'Total Provisions pour risques et charges (Début N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60081012 + F60081013 + F60081014 + F60081015 + F60081016 + F60081017'],
                    ['id' => 'F60081019', 'label' => 'Dotations de l\'exercice (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081020', 'label' => 'Reprises de l\'exercice (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60081021', 'label' => 'Total Provisions pour risques et charges (Fin N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60081018 + F60081019 - F60081020'],
                ]
            ],
            [
                'sectionTitle' => 'Total Général des Provisions (N-1)',
                'fields' => [
                    ['id' => 'F60081022', 'label' => 'Total Général des Provisions (Début N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60081008 + F60081018'],
                    ['id' => 'F60081023', 'label' => 'Total Général des Dotations (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60081009 + F60081019'],
                    ['id' => 'F60081024', 'label' => 'Total Général des Reprises (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60081010 + F60081020'],
                    ['id' => 'F60081025', 'label' => 'Total Général des Provisions (Fin N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60081022 + F60081023 - F60081024'],
                ]
            ]
        ]
    ],
    // F6009 : Tableau des plus ou moins values sur cessions d’immobilisations (Cas général) - Pages 66-67
    'F6009_Plus_Minus_Values' => [
        'id' => 'F6009_Plus_Minus_Values',
        'title' => 'F6009 - Tableau des Plus ou Moins Values',
        'description' => 'Détail des plus ou moins values sur cessions d\'immobilisations.',
        'sections' => [
            [
                'sectionTitle' => 'Plus-values de Cession (N)',
                'fields' => [
                    ['id' => 'F60090001', 'label' => 'Immobilisations incorporelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60090002', 'label' => 'Immobilisations corporelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60090003', 'label' => 'Immobilisations financières (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60090004', 'label' => 'Total Plus-values (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60090001 + F60090002 + F60090003'],
                ]
            ],
            [
                'sectionTitle' => 'Moins-values de Cession (N)',
                'fields' => [
                    ['id' => 'F60090005', 'label' => 'Immobilisations incorporelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60090006', 'label' => 'Immobilisations corporelles (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60090007', 'label' => 'Immobilisations financières (N)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60090008', 'label' => 'Total Moins-values (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60090005 + F60090006 + F60090007'],
                ]
            ],
            [
                'sectionTitle' => 'Net des Plus ou Moins-values (N)',
                'fields' => [
                    ['id' => 'F60090009', 'label' => 'Plus ou Moins-values nettes (N)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60090004 - F60090008'],
                ]
            ],
            // Plus-values de Cession (N-1)
            [
                'sectionTitle' => 'Plus-values de Cession (N-1)',
                'fields' => [
                    ['id' => 'F60091001', 'label' => 'Immobilisations incorporelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60091002', 'label' => 'Immobilisations corporelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60091003', 'label' => 'Immobilisations financières (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60091004', 'label' => 'Total Plus-values (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60091001 + F60091002 + F60091003'],
                ]
            ],
            [
                'sectionTitle' => 'Moins-values de Cession (N-1)',
                'fields' => [
                    ['id' => 'F60091005', 'label' => 'Immobilisations incorporelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60091006', 'label' => 'Immobilisations corporelles (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60091007', 'label' => 'Immobilisations financières (N-1)', 'type' => 'number', 'required' => true, 'default' => 0.00],
                    ['id' => 'F60091008', 'label' => 'Total Moins-values (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60091005 + F60091006 + F60091007'],
                ]
            ],
            [
                'sectionTitle' => 'Net des Plus ou Moins-values (N-1)',
                'fields' => [
                    ['id' => 'F60091009', 'label' => 'Plus ou Moins-values nettes (N-1)', 'type' => 'number', 'calculated' => true, 'formula' => 'F60091004 - F60091008'],
                ]
            ],
        ]
    ]
    // Ajoutez d'autres formulaires ici si nécessaire (F6007, F6008, F6009, etc.)
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisie Formulaire - Liasse Fiscale</title>
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
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            background-color: #1a202c;
            color: #ffffff;
            width: 250px;
            padding: 1.5rem;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
            flex-shrink: 0;
        }
        .sidebar-nav a {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: #cbd5e0;
            text-decoration: none;
            transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
        }
        .sidebar-nav a:hover {
            background-color: #2d3748;
            color: #e2e8f0;
        }
        .sidebar-nav a.active {
            background-color: #4c51bf;
            color: #ffffff;
            font-weight: 600;
        }
        .sidebar-nav .icon {
            margin-right: 0.75rem;
            width: 1.25rem;
            text-align: center;
        }

        .main-content {
            flex-grow: 1;
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
            flex-grow: 1;
        }

        /* Form Inputs */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.625rem;
            font-size: 1rem;
            color: #374151;
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .form-input:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.25);
        }
        textarea.form-input {
            min-height: 80px;
            resize: vertical;
        }
        /* Style for readonly/disabled inputs */
        .form-input:read-only,
        .form-input[disabled] {
            background-color: #e2e8f0; /* Lighter grey for read-only fields */
            cursor: not-allowed;
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
            background-color: #4f46e5;
            color: #ffffff;
        }
        .btn-primary:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .btn-primary:active {
            transform: translateY(0);
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        /* Message Box */
        .message-box {
            padding: 0.75rem 1.25rem;
            border-radius: 0.625rem;
            margin-bottom: 1.25rem;
            font-size: 0.95rem;
            display: none;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .message-box.error {
            background-color: #fef2f2;
            color: #dc2626;
            border: 1px solid #ef4444;
        }
        .message-box.success {
            background-color: #ecfdf5;
            color: #059669;
            border: 1px solid #10b981;
        }
        .message-box.info {
            background-color: #eff6ff;
            color: #2563eb;
            border: 1px solid #3b82f6;
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
            <a href="saisie_formulaire.php" class="sidebar-nav-item active">
                <i class="fas fa-keyboard icon"></i>
                Saisir un Formulaire
            </a>
            <a href="#" class="sidebar-nav-item">
                <i class="fas fa-upload icon"></i>
                Import des Balances
            </a>
            <a href="#" class="sidebar-nav-item">
                <i class="fas fa-folder-open icon"></i>
                Gestion des Liasses
            </a>
            <a href="#" class="sidebar-nav-item">
                <i class="fas fa-users icon"></i>
                Gestion des Utilisateurs
            </a>
            <a href="php/logout.php" class="sidebar-nav-item">
                <i class="fas fa-sign-out-alt icon"></i>
                Déconnexion
            </a>
        </nav>
    </div>

    <div class="main-content">
        <div class="header">
            <h1 class="text-3xl font-bold text-gray-800">Saisir un Formulaire de Liasse</h1>
            <div class="text-gray-600">
                Utilisateur: <span class="font-semibold text-indigo-700"><?php echo $username; ?></span>
                (Rôle: <span class="font-semibold text-indigo-700"><?php echo $role; ?></span>)
            </div>
        </div>

        <div class="content-area">
            <div class="bg-white p-8 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Sélection de la Déclaration et du Formulaire</h2>

                <!-- Message Box for general operations -->
                <div id="generalMessageBox" class="message-box"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div>
                        <label for="selectDeclaration" class="block text-gray-700 text-sm font-medium mb-2">Sélectionner une Déclaration Existante <span class="text-red-500">*</span></label>
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
                        <label for="selectFormType" class="block text-gray-700 text-sm font-medium mb-2">Type de Formulaire <span class="text-red-500">*</span></label>
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
                        <!-- Les champs du formulaire seront insérés ici par JavaScript -->
                        <div id="formFieldsArea"></div>
                        <button type="submit" class="btn btn-primary mt-6">
                            <i class="fas fa-save mr-2"></i> Enregistrer les données
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

            // Stocke les références des éléments d'entrée pour les calculs.
            const formInputElements = {}; 
            // Stocke les définitions des champs calculés
            const calculatedFieldsDefinitions = [];

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

            function hideMessage(box) {
                box.style.display = 'none';
                box.textContent = '';
                box.classList.remove('error', 'success', 'info'); // Clear all types
            }

            function formatDate(dateString) {
                if (!dateString) return 'N/A';
                const date = new Date(dateString);
                return date.toLocaleDateString('fr-FR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                });
            }

            /**
             * Évalue une formule mathématique à partir d'une chaîne de caractères.
             * Utilise new Function pour une évaluation plus sûre que eval().
             * Les variables dans la formule sont remplacées par les valeurs numériques des champs.
             * Traite les valeurs null/vides comme 0 pour les calculs.
             * Gère les fonctions Math.max et Math.min pour Sup et Inf
             * @param {string} formula L'expression mathématique (ex: "field1 + field2 - Math.max(field3, field4)")
             * @param {Object} values Un objet { fieldId: value, ... } contenant les valeurs des champs.
             * @returns {number} Le résultat du calcul.
             */
            function evaluateFormula(formula, values) {
                let evalFormula = formula;
                const fieldIdsInFormula = formula.match(/[a-zA-Z0-9_]+/g) || []; // Extrait tous les potentiels IDs

                // Remplace les IDs par leurs valeurs
                fieldIdsInFormula.forEach(id => {
                    const value = parseFloat(values[id]) || 0; // Utilise 0 si NaN ou null/undefined
                    const regex = new RegExp(`\\b${id}\\b`, 'g'); 
                    evalFormula = evalFormula.replace(regex, value);
                });

                // Gérer Math.max et Math.min
                evalFormula = evalFormula.replace(/Sup\(([^,]+),([^)]+)\)/g, 'Math.max($1,$2)');
                evalFormula = evalFormula.replace(/Inf\(([^,]+),([^)]+)\)/g, 'Math.min($1,$2)');

                try {
                    const result = new Function(`return ${evalFormula};`)();
                    return parseFloat(result.toFixed(2)); // Arrondir à 2 décimales pour les montants
                } catch (e) {
                    console.error("Erreur d'évaluation de la formule:", formula, "Evaluated as:", evalFormula, "Error:", e);
                    return 0; // Retourne 0 en cas d'erreur de formule
                }
            }


            // Fonction pour déclencher le recalcul de tous les champs calculés
            function recalculateAllFormulas() {
                const currentFormValues = {};
                // Collecte d'abord toutes les valeurs des champs saisissables
                // Les champs calculés ne sont pas encore mis à jour dans cette boucle
                for (const id in formInputElements) {
                    if (!formInputElements[id].readOnly) { // Ne prendre que les champs non calculés pour l'entrée
                        currentFormValues[id] = parseFloat(formInputElements[id].value) || 0;
                    }
                }

                // Effectuer les calculs par ordre de dépendance si possible, ou itérer jusqu'à convergence
                // Pour simplifier ici, on peut faire plusieurs passes si les formules sont chaînées
                for (let i = 0; i < calculatedFieldsDefinitions.length; i++) { // Première passe
                     const field = calculatedFieldsDefinitions[i];
                     const inputElement = formInputElements[field.id];
                     if (inputElement) {
                         const newValue = evaluateFormula(field.formula, {...currentFormValues, ...formInputElements}); // Passer toutes les valeurs
                         inputElement.value = newValue; 
                         currentFormValues[field.id] = newValue; // Mettre à jour pour les calculs suivants
                     }
                }
                // Si des calculs complexes ou chaînés, on peut répéter le processus
                // ou trier `calculatedFieldsDefinitions` par dépendances
            }

            // Fonction pour générer et peupler le formulaire dynamique
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
                formFieldsArea.innerHTML = ''; // Nettoyer les anciens champs
                
                // Réinitialiser les listes de référence pour les nouveaux formulaires
                Object.keys(formInputElements).forEach(key => delete formInputElements[key]);
                calculatedFieldsDefinitions.length = 0; // Vider le tableau

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
                        if (field.required && !field.calculated) { // Les champs calculés ne sont pas "requis" pour la saisie
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
                                inputElement.step = '0.01'; // Pour les décimales
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
                        
                        // Si c'est un champ calculé, le rendre en lecture seule et l'ajouter aux définitions des calculs
                        if (field.calculated) {
                            inputElement.readOnly = true;
                            inputElement.placeholder = 'Calculé automatiquement';
                            calculatedFieldsDefinitions.push(field); // Ajouter à la liste des champs calculés
                        } else {
                            // Pour les champs non calculés, attacher un écouteur pour déclencher le recalcul
                            inputElement.addEventListener('input', recalculateAllFormulas);
                        }

                        // Pré-remplir avec les données existantes ou les valeurs par défaut
                        if (formData && formData[field.id] !== undefined && formData[field.id] !== null) {
                            inputElement.value = formData[field.id];
                        } else if (field.default !== undefined) {
                            inputElement.value = field.default;
                        }

                        fieldDiv.appendChild(inputElement);
                        sectionDiv.appendChild(fieldDiv);
                        formInputElements[field.id] = inputElement; // Stocker la référence
                    });
                    formFieldsArea.appendChild(sectionDiv);
                });

                dynamicFormContainer.classList.remove('hidden');
                recalculateAllFormulas(); // Effectuer un calcul initial après le rendu
            }


            // Charger les données existantes pour un formulaire
            async function loadExistingFormData(declarationId, formType) {
                hideMessage(generalMessageBox);
                if (!declarationId || !formType) {
                    return; 
                }
                try {
                    const url = `php/api/form_data.php?declaration_id=${declarationId}&form_type=${formType}`;
                    const response = await fetch(url, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' }
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Erreur API (réponse non OK) lors du chargement des données:', response.status, errorText);
                        if (errorText.includes("Fatal error") || errorText.includes("<br />") || errorText.includes("<font")) {
                            throw new Error(`Erreur serveur inattendue lors du chargement. Veuillez réessayer. (Détails en console)`);
                        }
                        throw new Error(`Erreur serveur lors du chargement: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }

                    const result = await response.json();
                    console.log('API Response (Load Form Data):', result);

                    if (result.success) {
                        if (result.data) {
                            showMessage(generalMessageBox, `Données "${FORM_DEFINITIONS[formType].title}" chargées avec succès.`, 'success');
                            renderDynamicForm(formType, result.data); // Rendre et pré-remplir le formulaire
                        } else {
                            showMessage(generalMessageBox, `Aucune donnée existante trouvée pour "${FORM_DEFINITIONS[formType].title}". Formulaire vide.`, 'info');
                            renderDynamicForm(formType, null); // Rendre un formulaire vide
                        }
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors du chargement des données.", 'error');
                        renderDynamicForm(formType, null); // Rendre un formulaire vide en cas d'erreur
                    }
                } catch (error) {
                    console.error('Erreur lors du chargement des données existantes:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                    renderDynamicForm(formType, null); // Rendre un formulaire vide en cas d'échec
                }
            }


            // Gérer la sélection de la déclaration
            selectDeclaration.addEventListener('change', function() {
                const selectedDeclarationId = this.value;
                if (selectedDeclarationId) {
                    selectFormType.disabled = false;
                    currentDeclarationIdInput.value = selectedDeclarationId;
                    hideMessage(generalMessageBox);
                    
                    selectFormType.value = ''; // Réinitialiser le type de formulaire
                    dynamicFormContainer.classList.add('hidden'); // Cacher le conteneur du formulaire
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

            // Gérer la sélection du type de formulaire et rendre/charger le formulaire dynamique
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


            // Gérer la soumission du formulaire dynamique
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

                // Collecter toutes les données du formulaire dynamique
                let isValid = true;
                formDef.sections.forEach(section => {
                    section.fields.forEach(field => {
                        const inputElement = document.getElementById(field.id);
                        if (inputElement) {
                            let value = inputElement.value;
                            if (field.type === 'number') {
                                if (value === '') {
                                    value = null; 
                                } else {
                                    value = parseFloat(value);
                                    if (isNaN(value)) { 
                                        isValid = false;
                                        showMessage(generalMessageBox, `Le champ "${field.label}" doit être un nombre valide.`, 'error');
                                    }
                                }
                            }
                            dataToSave.form_data[field.id] = value;
                            
                            // Validation requise côté client pour les champs non calculés
                            if (field.required && !field.calculated && (value === null || value === '')) {
                                isValid = false;
                                showMessage(generalMessageBox, `Le champ "${field.label}" est obligatoire.`, 'error');
                            }
                        }
                    });
                });

                if (!isValid) {
                    return;
                }

                try {
                    const response = await fetch('php/api/form_data.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(dataToSave)
                    });

                    if (!response.ok) {
                        const errorText = await response.text();
                        console.error('Erreur API (réponse non OK) lors de la soumission du formulaire:', response.status, errorText);
                        if (errorText.includes("Fatal error") || errorText.includes("<br />") || errorText.includes("<font")) {
                            throw new Error(`Erreur serveur inattendue. Veuillez réessayer. (Détails en console)`);
                        }
                        throw new Error(`Erreur serveur: ${response.status} - ${errorText.substring(0, 100)}...`);
                    }

                    const result = await response.json();
                    console.log('API Response (Submit Form Data):', result);

                    if (result.success) {
                        showMessage(generalMessageBox, result.message, 'success');
                        loadExistingFormData(declarationId, formType); 
                    } else {
                        showMessage(generalMessageBox, result.message || "Erreur lors de l'enregistrement des données.", 'error');
                    }
                } catch (error) {
                    console.error('Erreur lors de l\'envoi du formulaire dynamique:', error);
                    showMessage(generalMessageBox, `Erreur de connexion au serveur ou données invalides: ${error.message}. Vérifiez la console pour plus de détails.`, 'error');
                }
            });

            // Initialisation au chargement de la page: Désactiver le sélecteur de formulaire au début
            selectFormType.disabled = true;
        });
    </script>
	
	<script>
        // Passer les définitions de formulaires du PHP au JavaScript
        const formDefinitions = <?php echo json_encode($form_definitions); ?>;

        /**
         * Calcule la valeur d'un champ automatique basé sur sa formule.
         * @param {string} formula La formule JavaScript à évaluer.
         * @returns {number} La valeur calculée.
         */
        function calculateField(formula) {
            // Remplacer les IDs des champs par leurs valeurs actuelles
            // Utilise une fonction de remplacement pour s'assurer que les valeurs sont traitées comme des nombres
            const evaluatedFormula = formula.replace(/[Ff]\d+/g, function(match) {
                const inputElement = document.getElementById(match);
                // Retourne 0 si l'élément n'existe pas ou n'a pas de valeur valide
                return parseFloat(inputElement ? inputElement.value : '0') || 0;
            });

            try {
                const result = new Function('return ' + evaluatedFormula)();
                return parseFloat(result.toFixed(2)); // Arrondir à 2 décimales
            } catch (e) {
                console.error("Erreur de calcul pour la formule:", formula, "Erreur:", e);
                return 0; // Retourne 0 en cas d'erreur de calcul
            }
        }

        /**
         * Met à jour un champ calculé et déclenche la mise à jour des champs qui en dépendent.
         * @param {string} fieldId L'ID du champ à mettre à jour.
         */
        function updateCalculatedField(fieldId) {
            for (const formKey in formDefinitions) {
                for (const section of formDefinitions[formKey].sections) {
                    for (const field of section.fields) {
                        if (field.id === fieldId && field.calculated && field.formula) {
                            const calculatedValue = calculateField(field.formula);
                            const inputElement = document.getElementById(field.id);
                            if (inputElement) {
                                inputElement.value = calculatedValue;
                                // Déclencher un événement 'input' artificiel si d'autres champs dépendent de celui-ci
                                const event = new Event('input', { bubbles: true });
                                inputElement.dispatchEvent(event);
                            }
                            return; // Le champ est trouvé et mis à jour
                        }
                    }
                }
            }
        }

        // Tableau pour stocker les dépendances : qui dépend de quel champ ?
        // { 'fieldId_qui_depend': ['fieldId_depen1', 'fieldId_depen2', ...]}
        const fieldDependencies = {};

        // Parcourir toutes les définitions de formulaires pour établir les dépendances
        for (const formKey in formDefinitions) {
            for (const section of formDefinitions[formKey].sections) {
                for (const field of section.fields) {
                    if (field.calculated && field.formula) {
                        // Extraire les IDs des champs de la formule (regex: F suivi de 8 chiffres)
                        const dependentFieldIds = field.formula.match(/[Ff]\d{8}/g); // Correspond aux IDs comme F60010001
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
        }

        // Ajouter les écouteurs d'événements à tous les champs de saisie
        document.addEventListener('DOMContentLoaded', function() {
            for (const formKey in formDefinitions) {
                for (const section of formDefinitions[formKey].sections) {
                    for (const field of section.fields) {
                        const inputElement = document.getElementById(field.id);
                        if (inputElement) {
                            // Si le champ n'est PAS calculé, il peut déclencher des calculs
                            if (!field.calculated) {
                                inputElement.addEventListener('input', function() {
                                    // Mettre à jour tous les champs qui dépendent de ce champ
                                    if (fieldDependencies[field.id]) {
                                        fieldDependencies[field.id].forEach(depFieldId => {
                                            updateCalculatedField(depFieldId);
                                        });
                                    }
                                });
                            }
                        }
                    }
                }
            }

            // Calculer toutes les valeurs initiales au chargement de la page
            // Important pour afficher les valeurs par défaut ou pré-chargées
            // Exécuter les calculs dans un ordre qui respecte les dépendances (du plus simple au plus complexe)
            // Une approche simple est de les exécuter plusieurs fois jusqu'à stabilisation.
            // Une meilleure approche nécessiterait un graphe de dépendances.
            for (let i = 0; i < 5; i++) { // Exécuter plusieurs passes pour les dépendances imbriquées
                for (const formKey in formDefinitions) {
                    for (const section of formDefinitions[formKey].sections) {
                        for (const field of section.fields) {
                            if (field.calculated) {
                                updateCalculatedField(field.id);
                            }
                        }
                    }
                }
            }
        });
    </script>
	
</body>
</html>
