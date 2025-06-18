-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 18 juin 2025 à 16:15
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
  `declaration_id` int NOT NULL,
  `exercice_id` int NOT NULL,
  `periode` enum('N','N-1','N-2') NOT NULL,
  `code_compte` varchar(20) NOT NULL,
  `libelle` varchar(255) DEFAULT NULL,
  `debit` decimal(15,2) DEFAULT '0.00',
  `credit` decimal(15,2) DEFAULT '0.00',
  `solde` decimal(15,2) DEFAULT '0.00',
  `affecte` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `declaration_id` (`declaration_id`),
  KEY `exercice_id` (`exercice_id`)
) ENGINE=InnoDB AUTO_INCREMENT=483 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `declarations`
--

INSERT INTO `declarations` (`id`, `entreprise_id`, `exercice_id`, `user_id`, `type_depot`, `nature_depot`, `numero_depot`, `date_declaration`, `created_at`, `updated_at`) VALUES
(1, 11, 1, 1, 'D', '0', 'DECL-2025-01', '2025-06-16', '2025-06-16 13:49:58', '2025-06-16 13:49:58'),
(2, 11, 1, 1, 'P', '1', 'DECL-2025-02', '2025-06-17', '2025-06-17 22:32:25', '2025-06-17 22:32:25');

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
(11, 'immaje', 'info', '7 rue marsa 2070', '4578923', 'A', 'P', 'A', '000');

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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `exercices`
--

INSERT INTO `exercices` (`id`, `entreprise_id`, `user_id`, `annee`, `date_debut`, `date_fin`, `statut`, `created_at`, `updated_at`) VALUES
(1, 11, 1, 2025, '2025-01-01', '2025-12-31', 'Ouvert', '2025-06-16 12:42:35', '2025-06-16 12:42:35');

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `form_data`
--

INSERT INTO `form_data` (`id`, `declaration_id`, `form_type`, `form_data_json`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'etat_resultat_simplifie', '{\"impot_societes\": 123, \"achats_consommes\": 888, \"chiffre_affaires\": 150000, \"charges_personnel\": 200, \"dotations_amortissements\": 0, \"autres_charges_exploitation\": 0, \"autres_produits_exploitation\": 133}', 1, '2025-06-16 22:55:13', '2025-06-16 22:55:13'),
(2, 1, 'F6001_Bilan_Actif', '{\"F60010001\": 7912612, \"F60010002\": 7912448, \"F60010003\": 7912315, \"F60010004\": 120, \"F60010005\": 10, \"F60010006\": 7884554, \"F60010007\": 332, \"F60010008\": 23332, \"F60010009\": 521, \"F60010010\": 3231, \"F60010011\": 215, \"F60010012\": 133, \"F60010013\": 24, \"F60010014\": 55, \"F60010015\": 54, \"F60010016\": 0, \"F60010017\": 0, \"F60010018\": 0, \"F60010019\": 0, \"F60010020\": 0, \"F60010021\": 0, \"F60010022\": 0, \"F60010023\": 0, \"F60010024\": 0, \"F60010025\": 0, \"F60010026\": 0, \"F60010027\": 0, \"F60010028\": 0, \"F60010029\": 0, \"F60010030\": 0, \"F60010031\": 164, \"F60010032\": 54, \"F60010033\": 0, \"F60010034\": 55, \"F60010035\": 55, \"F60010036\": 401, \"F60010037\": 401, \"F60010038\": 120, \"F60010039\": 25, \"F60010040\": 256, \"F60010041\": 0, \"F60010042\": 0, \"F60010043\": 0, \"F60010044\": 0, \"F60010045\": 0, \"F60010046\": 0, \"F60010047\": 0, \"F60010048\": 0, \"F60010049\": 0, \"F60010050\": 0, \"F60010051\": 0, \"F60010052\": 0, \"F60010053\": 0, \"F60010054\": 0, \"F60010055\": 0, \"F60010056\": 0, \"F60010057\": 0, \"F60010058\": 0, \"F60010059\": 0, \"F60010060\": 0, \"F60010061\": 0, \"F60010062\": 0, \"F60010063\": 0, \"F60010064\": 0, \"F60010065\": 0, \"F60010066\": 0, \"F60010067\": 4555, \"F60010068\": 7917568, \"F60011001\": 0, \"F60011002\": 0, \"F60011003\": 0, \"F60011004\": 0, \"F60011005\": 0, \"F60011006\": 0, \"F60011007\": 0, \"F60011008\": 0, \"F60011009\": 0, \"F60011010\": 0, \"F60011011\": 0, \"F60011012\": 0, \"F60011013\": 0, \"F60011014\": 0, \"F60011015\": 0, \"F60011016\": 0, \"F60011017\": 0, \"F60011018\": 0, \"F60011019\": 0, \"F60011020\": 0, \"F60011021\": 0, \"F60011022\": 0, \"F60011023\": 0, \"F60011024\": 0, \"F60011025\": 0, \"F60011026\": 0, \"F60011027\": 0, \"F60011028\": 0, \"F60011029\": 0, \"F60011030\": 0, \"F60011031\": 0, \"F60011032\": 0, \"F60011033\": 0, \"F60011034\": 0, \"F60011035\": 0, \"F60011036\": 0, \"F60011037\": 0, \"F60011038\": 0, \"F60011039\": 0, \"F60011040\": 0, \"F60011041\": 0, \"F60011042\": 0, \"F60011043\": 0, \"F60011044\": 0, \"F60011045\": 0, \"F60011046\": 0, \"F60011047\": 0, \"F60011048\": 0, \"F60011049\": 0, \"F60011050\": 0, \"F60011051\": 0, \"F60011052\": 0, \"F60011053\": 0, \"F60011054\": 0, \"F60011055\": 0, \"F60011056\": 0, \"F60011057\": 0, \"F60011058\": 0, \"F60011059\": 0, \"F60011060\": 0, \"F60011061\": 0, \"F60011062\": 0, \"F60011063\": 0, \"F60011064\": 0, \"F60011065\": 0, \"F60011066\": 0, \"F60011067\": 0, \"F60011068\": 0, \"F60012001\": 7912612, \"F60012002\": 7912448, \"F60012003\": 7912315, \"F60012004\": 120, \"F60012005\": 10, \"F60012006\": 7884554, \"F60012007\": 332, \"F60012008\": 23332, \"F60012009\": 521, \"F60012010\": 3231, \"F60012011\": 215, \"F60012012\": 133, \"F60012013\": 24, \"F60012014\": 55, \"F60012015\": 54, \"F60012016\": 0, \"F60012017\": 0, \"F60012018\": 0, \"F60012019\": 0, \"F60012020\": 0, \"F60012021\": 0, \"F60012022\": 0, \"F60012023\": 0, \"F60012024\": 0, \"F60012025\": 0, \"F60012026\": 0, \"F60012027\": 0, \"F60012028\": 0, \"F60012029\": 0, \"F60012030\": 0, \"F60012031\": 164, \"F60012032\": 54, \"F60012033\": 0, \"F60012034\": 55, \"F60012035\": 55, \"F60012036\": 401, \"F60012037\": 401, \"F60012038\": 120, \"F60012039\": 25, \"F60012040\": 256, \"F60012041\": 0, \"F60012042\": 0, \"F60012043\": 0, \"F60012044\": 0, \"F60012045\": 0, \"F60012046\": 0, \"F60012047\": 0, \"F60012048\": 0, \"F60012049\": 0, \"F60012050\": 0, \"F60012051\": 0, \"F60012052\": 0, \"F60012053\": 0, \"F60012054\": 0, \"F60012055\": 0, \"F60012056\": 0, \"F60012057\": 0, \"F60012058\": 0, \"F60012059\": 0, \"F60012060\": 0, \"F60012061\": 0, \"F60012062\": 0, \"F60012063\": 0, \"F60012064\": 0, \"F60012065\": 0, \"F60012066\": 0, \"F60012067\": 4555, \"F60012068\": 7917568, \"F60013001\": 0, \"F60013002\": 0, \"F60013003\": 0, \"F60013004\": 0, \"F60013005\": 0, \"F60013006\": 0, \"F60013007\": 0, \"F60013008\": 0, \"F60013009\": 0, \"F60013010\": 0, \"F60013011\": 0, \"F60013012\": 0, \"F60013013\": 0, \"F60013014\": 0, \"F60013015\": 0, \"F60013016\": 0, \"F60013017\": 0, \"F60013018\": 0, \"F60013019\": 0, \"F60013020\": 0, \"F60013021\": 0, \"F60013022\": 0, \"F60013023\": 0, \"F60013024\": 0, \"F60013025\": 0, \"F60013026\": 0, \"F60013027\": 0, \"F60013028\": 0, \"F60013029\": 0, \"F60013030\": 0, \"F60013031\": 0, \"F60013032\": 0, \"F60013033\": 0, \"F60013034\": 0, \"F60013035\": 0, \"F60014001\": 0, \"F60014002\": 0, \"F60014003\": 0, \"F60014004\": 0, \"F60014005\": 0, \"F60014006\": 0, \"F60014007\": 0, \"F60014008\": 0, \"F60014009\": 0, \"F60014010\": 0, \"F60014011\": 0, \"F60014012\": 0, \"F60014013\": 0, \"F60014014\": 0, \"F60014015\": 0, \"F60014016\": 0, \"F60014017\": 0, \"F60014018\": 0, \"F60014019\": 0, \"F60014020\": 0, \"F60014021\": 0, \"F60014022\": 0, \"F60014023\": 0, \"F60014024\": 0, \"F60014025\": 0, \"F60014026\": 0, \"F60014027\": 0, \"F60014028\": 0, \"F60014029\": 0, \"F60014030\": 0, \"F60014031\": 0, \"F60014032\": 0, \"F60014033\": 0, \"F60014034\": 0, \"F60014035\": 0, \"F60015001\": 0, \"F60015002\": 0, \"F60015003\": 0, \"F60015004\": 0, \"F60015005\": 0, \"F60015006\": 0, \"F60015007\": 0, \"F60015008\": 0, \"F60015009\": 0, \"F60015010\": 0, \"F60015011\": 0, \"F60015012\": 0, \"F60015013\": 0, \"F60015014\": 0, \"F60015015\": 0, \"F60015016\": 0, \"F60015017\": 0, \"F60015018\": 0, \"F60015019\": 0, \"F60015020\": 0, \"F60015021\": 0, \"F60015022\": 0, \"F60015023\": 0, \"F60015024\": 0, \"F60015025\": 0, \"F60015026\": 0, \"F60015027\": 0, \"F60015028\": 0, \"F60015029\": 0, \"F60015030\": 0, \"F60015031\": 0, \"F60015032\": 0, \"F60015033\": 0, \"F60015034\": 0, \"F60015035\": 0, \"F60015036\": 0, \"F60015037\": 0, \"F60015038\": 0, \"F60015039\": 0, \"F60015040\": 0, \"F60015041\": 0, \"F60015042\": 0, \"F60015043\": 0, \"F60015044\": 0, \"F60015045\": 0, \"F60015046\": 0, \"F60015047\": 0, \"F60015048\": 0, \"F60015049\": 0, \"F60015050\": 0, \"F60015051\": 0, \"F60015052\": 0, \"F60015053\": 0, \"F60015054\": 0, \"F60015055\": 0, \"F60015056\": 0, \"F60015057\": 0, \"F60015058\": 0, \"F60015059\": 0, \"F60015060\": 0, \"F60015061\": 0, \"F60015062\": 0, \"F60015063\": 0, \"F60015064\": 0, \"F60015065\": 0, \"F60015066\": 0, \"F60015067\": 0, \"F60015068\": 0}', 1, '2025-06-16 23:49:39', '2025-06-17 22:31:03');

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
(1, 'imed', '$2y$10$uP3Pk9huWCKbkef7P.h0RO9GcxX5sudoHgFxLQCMWZqh3pXzueLYW', 'rhaimi.imed@gmail.com', 'user', '2025-06-15 22:15:06', '2025-06-15 22:15:06'),
(2, 'admin', '$2y$10$zQDl5zEJI7h4iVRVx8iG7.g8N11qeicQIGCa20VG/eO93WVlOeftq', 'admin@gmail.com', 'user', '2025-06-16 10:22:48', '2025-06-16 10:22:48'),
(25, 'user2', '$2y$10$rIDNHxfGaEQuWBlg5Dv6e.D7t2KnNdNe6.UwHw1hUcn.Ciq3mQ8jy', 'us@gmail.com', 'user', '2025-06-16 22:26:55', '2025-06-16 22:26:55');

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
-- Contraintes pour la table `balances`
--
ALTER TABLE `balances`
  ADD CONSTRAINT `balances_ibfk_1` FOREIGN KEY (`declaration_id`) REFERENCES `declarations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `balances_ibfk_2` FOREIGN KEY (`exercice_id`) REFERENCES `exercices` (`id`) ON DELETE CASCADE;

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
