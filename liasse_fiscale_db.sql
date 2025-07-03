-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 03 juil. 2025 à 13:38
-- Version du serveur : 9.1.0
-- Version de PHP : 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `liasse_fiscale_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `balances`
--

DROP TABLE IF EXISTS `balances`;
CREATE TABLE IF NOT EXISTS `balances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entreprise_id` int NOT NULL,
  `exercice_id` int NOT NULL,
  `declaration_id` int NOT NULL,
  `periode` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
  `num_compte` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `intitule` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `solde_debit` decimal(16,3) DEFAULT '0.000',
  `solde_credit` decimal(16,3) DEFAULT '0.000',
  `solde` decimal(16,3) DEFAULT '0.000',
  `code_liasse` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `libelle_liasse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colonne` varchar(16) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `affecte` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `entreprise_id` (`entreprise_id`),
  KEY `exercice_id` (`exercice_id`),
  KEY `declaration_id` (`declaration_id`)
) ENGINE=MyISAM AUTO_INCREMENT=240 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `balances`
--

INSERT INTO `balances` (`id`, `entreprise_id`, `exercice_id`, `declaration_id`, `periode`, `num_compte`, `intitule`, `solde_debit`, `solde_credit`, `solde`, `code_liasse`, `libelle_liasse`, `colonne`, `affecte`) VALUES
(239, 11, 1, 2, 'N', '705002', 'Etudes et prest serv export', 0.000, 251397.946, -251397.946, NULL, NULL, NULL, 0),
(238, 11, 1, 2, 'N', '681000', 'Dotations aux amort. & aux déprét', 1168.818, 0.000, 1168.818, NULL, NULL, NULL, 0),
(237, 11, 1, 2, 'N', '636000', 'Autres cahrges divers', 466.835, 0.000, 466.835, NULL, NULL, NULL, 0),
(234, 11, 1, 2, 'N', '624000', 'Transports biens & collectifs', 22182.932, 0.000, 22182.932, NULL, NULL, NULL, 0),
(235, 11, 1, 2, 'N', '625600', 'Missions', 26308.774, 0.000, 26308.774, NULL, NULL, NULL, 0),
(236, 11, 1, 2, 'N', '626000', 'Frais postaux et de télécomm.', 28552.219, 0.000, 28552.219, NULL, NULL, NULL, 0),
(233, 11, 1, 2, 'N', '623000', 'Publicite publicat. relat. publiq.', 9752.632, 0.000, 9752.632, NULL, NULL, NULL, 0),
(231, 11, 1, 2, 'N', '613000', 'Locations', 36298.800, 0.000, 36298.800, NULL, NULL, NULL, 0),
(232, 11, 1, 2, 'N', '622000', 'REMUNERATION D\'INTERMIDIARES&HON', 7740.088, 0.000, 7740.088, NULL, NULL, NULL, 0),
(230, 11, 1, 2, 'N', '606300', 'Fournitures de bureau', 1462.108, 0.000, 1462.108, NULL, NULL, NULL, 0),
(228, 11, 1, 2, 'N', '580000', 'Virements internes', 711.036, 0.000, 711.036, NULL, NULL, NULL, 0),
(229, 11, 1, 2, 'N', '604001', 'ACHAT ETUDES ET PREST SERV', 27004.800, 0.000, 27004.800, NULL, NULL, NULL, 0),
(227, 11, 1, 2, 'N', '532402', 'BIAT USD', 5.505, 0.000, 5.505, NULL, NULL, NULL, 0),
(225, 11, 1, 2, 'N', '532100', 'BIAT TND', 0.000, 2.457, -2.457, NULL, NULL, NULL, 0),
(226, 11, 1, 2, 'N', '532401', 'BANQUE BIAT €', 57802.696, 0.000, 57802.696, NULL, NULL, NULL, 0),
(223, 11, 1, 2, 'N', '442000', 'Associés comptes courants', 478613.481, 0.000, 478613.481, NULL, NULL, NULL, 0),
(224, 11, 1, 2, 'N', '457800', 'DEBOURS RECUS CLIENTS', 0.000, 186831.024, -186831.024, NULL, NULL, NULL, 0),
(221, 11, 1, 2, 'N', '436662', 'TVA déductible 12%', 401.954, 0.000, 401.954, NULL, NULL, NULL, 0),
(222, 11, 1, 2, 'N', '439000', 'Recette des finances', 0.000, 1184.171, -1184.171, NULL, NULL, NULL, 0),
(219, 11, 1, 2, 'N', '401000', 'Fournisseurs de Biens et de serv.', 0.000, 10666.075, -10666.075, NULL, NULL, NULL, 0),
(220, 11, 1, 2, 'N', '411000', 'Clients', 32423.272, 0.000, 32423.272, NULL, NULL, NULL, 0),
(218, 11, 1, 2, 'N', '282830', 'Amort Matériel informatique', 0.000, 1792.188, -1792.188, NULL, NULL, NULL, 0),
(216, 11, 1, 2, 'N', '135000', 'Résultat de l\'exercice  (Pertes)', 21605.435, 0.000, 21605.435, NULL, NULL, NULL, 0),
(217, 11, 1, 2, 'N', '228300', 'Matériel informatique', 3506.459, 0.000, 3506.459, 'F60010017', 'Autres Immob. Corporelles', 'BRUT', 1),
(214, 11, 1, 2, 'N', '101000', 'Capital', 0.000, 1000.000, -1000.000, 'F60020002', 'Capital social', 'NET', 1),
(215, 11, 1, 2, 'N', '131000', 'Résultat de l\'exercice (bénéfice)', 0.000, 303133.983, -303133.983, NULL, NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Structure de la table `correspondance_liasse`
--

DROP TABLE IF EXISTS `correspondance_liasse`;
CREATE TABLE IF NOT EXISTS `correspondance_liasse` (
  `id` int NOT NULL AUTO_INCREMENT,
  `num_compte` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_liasse` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `libelle_liasse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `colonne` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `correspondance_liasse`
--

INSERT INTO `correspondance_liasse` (`id`, `num_compte`, `code_liasse`, `libelle_liasse`, `colonne`) VALUES
(1, '101000', 'F60020002', 'Capital social', 'NET'),
(2, '228300', 'F60010017', 'Autres Immob. Corporelles', 'BRUT');

-- --------------------------------------------------------

--
-- Structure de la table `declarations`
--

DROP TABLE IF EXISTS `declarations`;
CREATE TABLE IF NOT EXISTS `declarations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entreprise_id` int NOT NULL,
  `exercice_id` int NOT NULL,
  `user_id` int NOT NULL,
  `type_depot` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nature_depot` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero_depot` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_declaration` date NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_depot` (`numero_depot`),
  KEY `idx_declarations_entreprise_id` (`entreprise_id`),
  KEY `idx_declarations_exercice_id` (`exercice_id`),
  KEY `idx_declarations_user_id` (`user_id`),
  KEY `idx_declarations_type_depot` (`type_depot`),
  KEY `idx_declarations_nature_depot` (`nature_depot`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `declarations`
--

INSERT INTO `declarations` (`id`, `entreprise_id`, `exercice_id`, `user_id`, `type_depot`, `nature_depot`, `numero_depot`, `date_declaration`, `created_at`, `updated_at`) VALUES
(1, 11, 1, 1, 'D', '0', 'DECL-2025-01', '2025-06-16', '2025-06-16 13:49:58', '2025-06-16 13:49:58'),
(2, 11, 1, 1, 'P', '1', 'DECL-2025-02', '2025-06-17', '2025-06-17 22:32:25', '2025-06-17 22:32:25'),
(3, 10, 2, 1, 'D', '0', 'DECL-2023-01', '2025-06-23', '2025-06-23 12:50:31', '2025-06-23 12:50:31');

-- --------------------------------------------------------

--
-- Structure de la table `declaration_data`
--

DROP TABLE IF EXISTS `declaration_data`;
CREATE TABLE IF NOT EXISTS `declaration_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `declaration_id` int NOT NULL,
  `form_field_id` int NOT NULL,
  `value` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `declaration_field_unique` (`declaration_id`,`form_field_id`),
  KEY `form_field_id` (`form_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

DROP TABLE IF EXISTS `entreprises`;
CREATE TABLE IF NOT EXISTS `entreprises` (
  `id` int NOT NULL AUTO_INCREMENT,
  `raison_sociale` varchar(255) DEFAULT NULL,
  `activite` varchar(255) DEFAULT NULL,
  `adresse` text,
  `matricule` char(7) NOT NULL,
  `cle` char(1) NOT NULL,
  `categorie` char(1) NOT NULL,
  `tva` char(1) NOT NULL,
  `serie` char(3) NOT NULL DEFAULT '000',
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricule_unique` (`matricule`,`cle`,`categorie`,`tva`,`serie`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `raison_sociale`, `activite`, `adresse`, `matricule`, `cle`, `categorie`, `tva`, `serie`) VALUES
(10, 'yram', 'informatique', 'rue abbasines', '1234567', 'N', 'P', 'A', '111'),
(11, 'immaje', 'info', '7 rue marsa 2070', '1296739', 'K', 'M', 'A', '000');

-- --------------------------------------------------------

--
-- Structure de la table `exercices`
--

DROP TABLE IF EXISTS `exercices`;
CREATE TABLE IF NOT EXISTS `exercices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `entreprise_id` int NOT NULL,
  `user_id` int NOT NULL,
  `annee` int NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `statut` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Ouvert',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_entreprise_annee` (`entreprise_id`,`annee`),
  KEY `idx_exercices_entreprise_id` (`entreprise_id`),
  KEY `idx_exercices_user_id` (`user_id`),
  KEY `idx_exercices_annee` (`annee`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `exercices`
--

INSERT INTO `exercices` (`id`, `entreprise_id`, `user_id`, `annee`, `date_debut`, `date_fin`, `statut`, `created_at`, `updated_at`) VALUES
(1, 11, 1, 2025, '2025-01-01', '2025-12-31', 'Ouvert', '2025-06-16 12:42:35', '2025-06-16 12:42:35'),
(2, 10, 1, 2023, '2023-01-01', '2023-12-31', 'Ouvert', '2025-06-23 12:50:12', '2025-06-23 12:50:12');

-- --------------------------------------------------------

--
-- Structure de la table `form_data`
--

DROP TABLE IF EXISTS `form_data`;
CREATE TABLE IF NOT EXISTS `form_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `declaration_id` int NOT NULL,
  `form_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `form_data_json` json DEFAULT NULL,
  `user_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `declaration_id` (`declaration_id`,`form_type`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `form_data`
--

INSERT INTO `form_data` (`id`, `declaration_id`, `form_type`, `form_data_json`, `user_id`, `created_at`, `updated_at`) VALUES
(2, 1, 'F6001_Bilan_Actif', '{\"F60010001\": 4756, \"F60010002\": 4592, \"F60010003\": 4459, \"F60010004\": 120, \"F60010005\": 10, \"F60010006\": 20, \"F60010007\": 332, \"F60010008\": 10, \"F60010009\": 521, \"F60010010\": 3231, \"F60010011\": 215, \"F60010012\": 133, \"F60010013\": 24, \"F60010014\": 55, \"F60010015\": 54, \"F60010016\": 0, \"F60010017\": 0, \"F60010018\": 0, \"F60010019\": 0, \"F60010020\": 0, \"F60010021\": 0, \"F60010022\": 0, \"F60010023\": 0, \"F60010024\": 0, \"F60010025\": 0, \"F60010026\": 0, \"F60010027\": 0, \"F60010028\": 0, \"F60010029\": 0, \"F60010030\": 0, \"F60010031\": 164, \"F60010032\": 54, \"F60010033\": 0, \"F60010034\": 55, \"F60010035\": 55, \"F60010036\": 401, \"F60010037\": 401, \"F60010038\": 120, \"F60010039\": 25, \"F60010040\": 256, \"F60010041\": 0, \"F60010042\": 0, \"F60010043\": 0, \"F60010044\": 0, \"F60010045\": 0, \"F60010046\": 0, \"F60010047\": 0, \"F60010048\": 0, \"F60010049\": 0, \"F60010050\": 0, \"F60010051\": 0, \"F60010052\": 0, \"F60010053\": 0, \"F60010054\": 0, \"F60010055\": 0, \"F60010056\": 0, \"F60010057\": 0, \"F60010058\": 0, \"F60010059\": 0, \"F60010060\": 0, \"F60010061\": 0, \"F60010062\": 0, \"F60010063\": 0, \"F60010064\": 0, \"F60010065\": 0, \"F60010066\": 0, \"F60010067\": 4555, \"F60010068\": 9712, \"F60011001\": 0, \"F60011002\": 0, \"F60011003\": 0, \"F60011004\": 0, \"F60011005\": 0, \"F60011006\": 0, \"F60011007\": 0, \"F60011008\": 0, \"F60011009\": 0, \"F60011010\": 0, \"F60011011\": 0, \"F60011012\": 0, \"F60011013\": 0, \"F60011014\": 0, \"F60011015\": 0, \"F60011016\": 0, \"F60011017\": 0, \"F60011018\": 0, \"F60011019\": 0, \"F60011020\": 0, \"F60011021\": 0, \"F60011022\": 0, \"F60011023\": 0, \"F60011024\": 0, \"F60011025\": 0, \"F60011026\": 0, \"F60011027\": 0, \"F60011028\": 0, \"F60011029\": 0, \"F60011030\": 0, \"F60011031\": 0, \"F60011032\": 0, \"F60011033\": 0, \"F60011034\": 0, \"F60011035\": 0, \"F60011036\": 0, \"F60011037\": 0, \"F60011038\": 0, \"F60011039\": 0, \"F60011040\": 0, \"F60011041\": 0, \"F60011042\": 0, \"F60011043\": 0, \"F60011044\": 0, \"F60011045\": 0, \"F60011046\": 0, \"F60011047\": 0, \"F60011048\": 0, \"F60011049\": 0, \"F60011050\": 0, \"F60011051\": 0, \"F60011052\": 0, \"F60011053\": 0, \"F60011054\": 0, \"F60011055\": 0, \"F60011056\": 0, \"F60011057\": 0, \"F60011058\": 0, \"F60011059\": 0, \"F60011060\": 0, \"F60011061\": 0, \"F60011062\": 0, \"F60011063\": 0, \"F60011064\": 0, \"F60011065\": 0, \"F60011066\": 0, \"F60011067\": 0, \"F60011068\": 0, \"F60012001\": 4756, \"F60012002\": 4592, \"F60012003\": 4459, \"F60012004\": 120, \"F60012005\": 10, \"F60012006\": 20, \"F60012007\": 332, \"F60012008\": 10, \"F60012009\": 521, \"F60012010\": 3231, \"F60012011\": 215, \"F60012012\": 133, \"F60012013\": 24, \"F60012014\": 55, \"F60012015\": 54, \"F60012016\": 0, \"F60012017\": 0, \"F60012018\": 0, \"F60012019\": 0, \"F60012020\": 0, \"F60012021\": 0, \"F60012022\": 0, \"F60012023\": 0, \"F60012024\": 0, \"F60012025\": 0, \"F60012026\": 0, \"F60012027\": 0, \"F60012028\": 0, \"F60012029\": 0, \"F60012030\": 0, \"F60012031\": 164, \"F60012032\": 54, \"F60012033\": 0, \"F60012034\": 55, \"F60012035\": 55, \"F60012036\": 401, \"F60012037\": 401, \"F60012038\": 120, \"F60012039\": 25, \"F60012040\": 256, \"F60012041\": 0, \"F60012042\": 0, \"F60012043\": 0, \"F60012044\": 0, \"F60012045\": 0, \"F60012046\": 0, \"F60012047\": 0, \"F60012048\": 0, \"F60012049\": 0, \"F60012050\": 0, \"F60012051\": 0, \"F60012052\": 0, \"F60012053\": 0, \"F60012054\": 0, \"F60012055\": 0, \"F60012056\": 0, \"F60012057\": 0, \"F60012058\": 0, \"F60012059\": 0, \"F60012060\": 0, \"F60012061\": 0, \"F60012062\": 0, \"F60012063\": 0, \"F60012064\": 0, \"F60012065\": 0, \"F60012066\": 0, \"F60012067\": 4555, \"F60012068\": 9712, \"F60013001\": 0, \"F60013002\": 0, \"F60013003\": 0, \"F60013004\": 0, \"F60013005\": 0, \"F60013006\": 0, \"F60013007\": 0, \"F60013008\": 0, \"F60013009\": 0, \"F60013010\": 0, \"F60013011\": 0, \"F60013012\": 0, \"F60013013\": 0, \"F60013014\": 0, \"F60013015\": 0, \"F60013016\": 0, \"F60013017\": 0, \"F60013018\": 0, \"F60013019\": 0, \"F60013020\": 0, \"F60013021\": 0, \"F60013022\": 0, \"F60013023\": 0, \"F60013024\": 0, \"F60013025\": 0, \"F60013026\": 0, \"F60013027\": 0, \"F60013028\": 0, \"F60013029\": 0, \"F60013030\": 0, \"F60013031\": 0, \"F60013032\": 0, \"F60013033\": 0, \"F60013034\": 0, \"F60013035\": 0, \"F60013036\": 0, \"F60013037\": 0, \"F60013038\": 0, \"F60013039\": 0, \"F60013040\": 0, \"F60013041\": 0, \"F60013042\": 0, \"F60013043\": 0, \"F60013044\": 0, \"F60013045\": 0, \"F60013046\": 0, \"F60013047\": 0, \"F60013048\": 0, \"F60013049\": 0, \"F60013050\": 0, \"F60013051\": 0, \"F60013052\": 0, \"F60013053\": 0, \"F60013054\": 0, \"F60013055\": 0, \"F60013056\": 0, \"F60013057\": 0, \"F60013058\": 0, \"F60013059\": 0, \"F60013060\": 0, \"F60013061\": 0, \"F60013062\": 0, \"F60013063\": 0, \"F60013064\": 0, \"F60013065\": 0, \"F60013066\": 0, \"F60013067\": 0, \"F60013068\": 0}', 1, '2025-06-16 23:49:39', '2025-06-27 11:16:03'),
(7, 3, 'F6002_Bilan_Passif', '{\"F60020001\": 37, \"F60020002\": 10, \"F60020010\": 10, \"F60020014\": 0, \"F60020015\": 12, \"F60020016\": 10, \"F60020017\": 1, \"F60020018\": 1, \"F60020019\": 0, \"F60020020\": 0, \"F60020021\": 2, \"F60020022\": 1, \"F60020023\": 0, \"F60020025\": 1, \"F60020030\": 4, \"F60020031\": 4, \"F60020032\": 2, \"F60020033\": 2, \"F60020034\": 0, \"F60020035\": 0, \"F60020036\": 0, \"F60020037\": 0, \"F60020038\": 0, \"F60020039\": 0, \"F60020040\": 0, \"F60020041\": 0, \"F60020042\": 0, \"F60020043\": 0, \"F60020046\": 0, \"F60020050\": 0, \"F60020051\": 0, \"F60020052\": 0, \"F60020053\": 0, \"F60020054\": 0, \"F60020055\": 0, \"F60020056\": 0, \"F60020057\": 0, \"F60020058\": 0, \"F60020059\": 0, \"F60020060\": 0, \"F60020061\": 0, \"F60020062\": 0, \"F60020063\": 0, \"F60020064\": 0, \"F60020065\": 0, \"F60020066\": 0, \"F60020067\": 0, \"F60020071\": 0, \"F60020100\": 41}', 1, '2025-06-23 16:07:27', '2025-06-23 16:07:27');

-- --------------------------------------------------------

--
-- Structure de la table `form_fields`
--

DROP TABLE IF EXISTS `form_fields`;
CREATE TABLE IF NOT EXISTS `form_fields` (
  `id` int NOT NULL AUTO_INCREMENT,
  `form_type_id` int NOT NULL,
  `field_name` varchar(255) NOT NULL,
  `field_code` varchar(50) DEFAULT NULL,
  `field_type` enum('decimal','integer','string','boolean','date') DEFAULT 'decimal',
  `is_required` tinyint(1) DEFAULT '0',
  `parent_field_id` int DEFAULT NULL,
  `order_in_form` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `field_code` (`field_code`),
  KEY `form_type_id` (`form_type_id`),
  KEY `parent_field_id` (`parent_field_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `form_types`
--

DROP TABLE IF EXISTS `form_types`;
CREATE TABLE IF NOT EXISTS `form_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','user','comptable') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `created_at`, `updated_at`) VALUES
(1, 'imed', '$2y$10$uP3Pk9huWCKbkef7P.h0RO9GcxX5sudoHgFxLQCMWZqh3pXzueLYW', 'rhaimi.imed@gmail.com', 'admin', '2025-06-15 22:15:06', '2025-06-30 11:56:04'),
(2, 'admin', '$2y$10$zQDl5zEJI7h4iVRVx8iG7.g8N11qeicQIGCa20VG/eO93WVlOeftq', 'admin@gmail.com', 'admin', '2025-06-16 10:22:48', '2025-07-01 16:25:42');

-- --------------------------------------------------------

--
-- Structure de la table `user_entreprises`
--

DROP TABLE IF EXISTS `user_entreprises`;
CREATE TABLE IF NOT EXISTS `user_entreprises` (
  `user_id` int NOT NULL,
  `entreprise_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`entreprise_id`),
  KEY `entreprise_id` (`entreprise_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `user_entreprises`
--

INSERT INTO `user_entreprises` (`user_id`, `entreprise_id`) VALUES
(1, 10),
(1, 11);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `declaration_data`
--
ALTER TABLE `declaration_data`
  ADD CONSTRAINT `declaration_data_ibfk_1` FOREIGN KEY (`declaration_id`) REFERENCES `declarations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `declaration_data_ibfk_2` FOREIGN KEY (`form_field_id`) REFERENCES `form_fields` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `form_fields`
--
ALTER TABLE `form_fields`
  ADD CONSTRAINT `form_fields_ibfk_1` FOREIGN KEY (`form_type_id`) REFERENCES `form_types` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `form_fields_ibfk_2` FOREIGN KEY (`parent_field_id`) REFERENCES `form_fields` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `user_entreprises`
--
ALTER TABLE `user_entreprises`
  ADD CONSTRAINT `user_entreprises_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_entreprises_ibfk_2` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
