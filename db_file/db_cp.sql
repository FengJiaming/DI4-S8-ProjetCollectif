SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `db_cp` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `db_cp`;

DROP TABLE IF EXISTS `administrator`;
CREATE TABLE IF NOT EXISTS `administrator` (
  `id` int(11) NOT NULL,
  `userNumber` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `refused` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `FK_HardwareHardwareBorrowing` (`idHardware`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idCategory` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(512) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `FK_CategoryProduct` (`idCategory`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

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


ALTER TABLE `basketproduct`
  ADD CONSTRAINT `FK_ProductBasket` FOREIGN KEY (`idProduct`) REFERENCES `product` (`id`);

ALTER TABLE `hardware`
  ADD CONSTRAINT `FK_ProductHardware` FOREIGN KEY (`idProduct`) REFERENCES `product` (`id`);

ALTER TABLE `hardwareborrowing`
  ADD CONSTRAINT `FK_HardwareHardwareBorrowing` FOREIGN KEY (`idHardware`) REFERENCES `hardware` (`barCode`);

ALTER TABLE `product`
  ADD CONSTRAINT `FK_CategoryProduct` FOREIGN KEY (`idCategory`) REFERENCES `category` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
