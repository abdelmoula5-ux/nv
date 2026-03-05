-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 07 fév. 2025 à 14:29
-- Version du serveur : 8.2.0
-- Version de PHP : 8.2.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `pollproject`
--
CREATE DATABASE IF NOT EXISTS `pollproject` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `pollproject`;

-- --------------------------------------------------------

--
-- Structure de la table `answer`
--

DROP TABLE IF EXISTS `answer`;
CREATE TABLE IF NOT EXISTS `answer` (
  `pollId` int NOT NULL,
  `optionId` int NOT NULL,
  `userId` varchar(20) NOT NULL,
  `date` datetime DEFAULT NULL,
  UNIQUE KEY `unique_vote` (`userId`,`pollId`),
  KEY `pollId` (`pollId`),
  KEY `optionId` (`optionId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `answer`
--

INSERT INTO `answer` (`pollId`, `optionId`, `userId`, `date`) VALUES
(114, 161, 'admin', '2024-12-17 01:08:29'),
(114, 160, 'admin2', '2024-12-17 01:09:09'),
(114, 161, 'admin3', '2024-12-17 01:10:20'),
(115, 163, 'admin', '2024-12-17 17:46:51'),
(116, 164, 'admin', '2024-12-17 18:33:51');

-- --------------------------------------------------------

--
-- Structure de la table `options`
--

DROP TABLE IF EXISTS `options`;
CREATE TABLE IF NOT EXISTS `options` (
  `id` int NOT NULL AUTO_INCREMENT,
  `pollId` int NOT NULL,
  `reponse` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pollId` (`pollId`)
) ENGINE=MyISAM AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `options`
--

INSERT INTO `options` (`id`, `pollId`, `reponse`) VALUES
(160, 114, 'Bof'),
(159, 114, 'Bien évidemment'),
(161, 114, 'Monsieur LARGANGE est meilleur'),
(162, 115, 'Assurément oui'),
(163, 115, 'Non pas dutout'),
(164, 116, 'Pour'),
(165, 116, 'Contre');

-- --------------------------------------------------------

--
-- Structure de la table `poll`
--

DROP TABLE IF EXISTS `poll`;
CREATE TABLE IF NOT EXISTS `poll` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sujet` varchar(100) NOT NULL,
  `auteur` varchar(20) DEFAULT NULL,
  `datedebut` datetime DEFAULT NULL,
  `datefin` datetime DEFAULT NULL,
  `secret` tinyint(1) DEFAULT '0',
  `privee` tinyint(1) NOT NULL DEFAULT '0',
  `randomcode` int DEFAULT NULL,
  `color` varchar(18) NOT NULL DEFAULT 'rgb(255, 108, 113)',
  PRIMARY KEY (`id`),
  UNIQUE KEY `randomcode` (`randomcode`),
  KEY `auteur` (`auteur`)
) ENGINE=MyISAM AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `poll`
--

INSERT INTO `poll` (`id`, `sujet`, `auteur`, `datedebut`, `datefin`, `secret`, `privee`, `randomcode`, `color`) VALUES
(114, 'Monsieur Jean est-il le meilleur professeur ?', 'admin', '2024-12-16 21:06:00', NULL, 0, 0, NULL, 'rgb(62, 181, 255)'),
(115, 'Andy GUSTAVE est-il mariable ?', 'admin', '2024-12-17 13:36:00', NULL, 0, 0, NULL, 'rgb(210, 141, 255)'),
(116, 'Esclavage des élèves', 'admin', '2024-12-17 14:33:00', '2024-12-20 00:00:00', 0, 0, NULL, 'rgb(210, 141, 255)');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `pseudo` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL,
  PRIMARY KEY (`pseudo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`pseudo`, `password`) VALUES
('admin', '$2y$10$anYwIu7VFhDBGML7xxE1Gep7lDA7VfcElUAtsV4/LS0zMz6xnbwd6'),
('admin2', '$2y$10$TcKEQdSF1NX1EJHbMx0X.uchJwRgx0nTC95FO7YJp6uAHLz5FWCrO'),
('admin3', '$2y$10$DZ2YTTuUJW4MV5oCTmBrY.yZpQa8i2H9G83NX1Y4fqcrApYCzhzSS'),
('admin4', '$2y$10$oBg5R36J7BGoZXzeXL5QWOR8zwR8b.DX04CSmdZCmD72Du6KXHFHS'),
('admin5', '$2y$10$3oQ6aSO8s6j7224C.Z8Pm.VJxv.CQg/909DFRPIPAYOHhu7nPnfVm'),
('admin6', '$2y$10$rETFIrgdq3RTkmD/bgNvLeJi95vVSYaAUXRZ00V8cmJE0.BKGn9vq');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
