-- phpMyAdmin SQL Dump
-- version 4.8.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3307
-- Généré le :  mar. 14 mai 2019 à 10:34
-- Version du serveur :  10.3.12-MariaDB
-- Version de PHP :  7.2.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `db_cp_test`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrator`
--

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `id` int(11) NOT NULL,
  `userNumber` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `basketproduct`
--

DROP TABLE IF EXISTS `basketproduct`;
CREATE TABLE IF NOT EXISTS `basketproduct` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userNumber` int(11) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `idProduct` int(11) DEFAULT NULL,
  `designation`  varchar(128) DEFAULT NULL,
  `userComment`  varchar(128) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_ProductBasket` (`idProduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'C1'),
(2, 'C2');

-- --------------------------------------------------------

--
-- Structure de la table `consumableborrowing`
--

DROP TABLE IF EXISTS `consumableborrowing`;
CREATE TABLE IF NOT EXISTS `consumableborrowing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `designation` varchar(128) DEFAULT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `renderDate` date DEFAULT NULL,
  `adminComment` varchar(512) DEFAULT NULL,
  `userComment` varchar(512) DEFAULT NULL,
  `userNumber` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `consumableborrowing`
--

INSERT INTO `consumableborrowing` (`id`, `designation`, `startDate`, `endDate`, `renderDate`, `adminComment`, `userComment`, `userNumber`) VALUES
(1, 'tournevis', '2019-04-15', '2019-04-24', NULL, NULL, NULL, 11111111),
(2, 'chiffon', '2019-04-18', '2019-04-20', NULL, NULL, NULL, 22222222);

-- --------------------------------------------------------

--
-- Structure de la table `hardware`
--

DROP TABLE IF EXISTS `hardware`;
CREATE TABLE IF NOT EXISTS `hardware` (
  `barCode` varchar(255) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `idProduct` int(11) DEFAULT NULL,
  `reserved` tinyint(1) DEFAULT NULL,
  `outOfService` tinyint(1) DEFAULT NULL,
  `donation` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`barCode`),
  KEY `FK_ProductHardware` (`idProduct`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `hardware`
--

INSERT INTO `hardware` (`barCode`, `comment`, `idProduct`, `reserved`, `outOfService`, `donation`) VALUES
(5487545, NULL, 2, 0, 0, 0),
(9996551, NULL, 1, NULL, NULL, NULL),
(218548545, NULL, 1, 0, 0, 0),
(478968785, NULL, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `hardwareborrowing`
--

DROP TABLE IF EXISTS `hardwareborrowing`;
CREATE TABLE IF NOT EXISTS `hardwareborrowing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idHardware` varchar(255) NOT NULL,
  `startDate` date DEFAULT NULL,
  `endDate` date DEFAULT NULL,
  `renderDate` date DEFAULT NULL,
  `adminComment` varchar(512) DEFAULT NULL,
  `userComment` varchar(512) DEFAULT NULL,
  `userNumber` int(11) NOT NULL,
  `ready` tinyint(1) DEFAULT 0,
  `pickedup` tinyint(1) DEFAULT 0,
  `refused` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_HardwareHardwareBorrowing` (`idHardware`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `hardwareborrowing`
--

INSERT INTO `hardwareborrowing` (`id`, `idHardware`, `startDate`, `endDate`, `renderDate`, `adminComment`, `userComment`, `userNumber`, `ready`, `pickedup`, `refused`) VALUES
(1, 5487545, '2019-04-12', '2019-04-19', NULL, NULL, NULL, 11111111, 0, 0, 0),
(2, 9996551, '2019-04-23', '2019-04-27', NULL, NULL, NULL, 11111111, 0, 0, 0),
(3, 218548545, '2019-04-14', '2019-04-18', NULL, NULL, NULL, 22222222, 0, 0, 0),
(4, 218548545, '2019-04-22', '2019-04-28', NULL, NULL, NULL, 22222222, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCategory` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `FK_CategoryProduct` (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=262 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`id`, `idCategory`, `name`, `description`) VALUES
(1, 1, 'Produit 1', 'Description 1'),
(2, 2, 'Produit 2', 'Description 2');

-- --------------------------------------------------------

--
-- Structure de la table `request`
--

DROP TABLE IF EXISTS `request`;
CREATE TABLE IF NOT EXISTS `request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userNumber` int(11) DEFAULT NULL,
  `productType` varchar(255) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `read` tinyint(1) DEFAULT 0,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `request`
--

INSERT INTO `request` (`id`, `userNumber`, `productType`, `message`, `read`, `date`) VALUES
(1, 22222222, 'Demande de carte SD', 'Salut, ça va ?', 0, '2019-05-15'),
(2, 22222222, 'materiel', 'Je veux un matéiel', 1, '2019-05-31');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `hardware`
--
ALTER TABLE `hardware`
  ADD CONSTRAINT `FK_ProductHardware` FOREIGN KEY (`idProduct`) REFERENCES `product` (`id`);

--
-- Contraintes pour la table `hardwareborrowing`
--
ALTER TABLE `hardwareborrowing`
  ADD CONSTRAINT `FK_HardwareHardwareBorrowing` FOREIGN KEY (`idHardware`) REFERENCES `hardware` (`barCode`);

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `FK_CategoryProduct` FOREIGN KEY (`idCategory`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
