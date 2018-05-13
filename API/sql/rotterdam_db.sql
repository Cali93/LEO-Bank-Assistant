-- phpMyAdmin SQL Dump
-- version 4.0.10.17
-- https://www.phpmyadmin.net
--
-- Client: 10.0.234.51
-- Généré le: Dim 13 Mai 2018 à 19:23
-- Version du serveur: 5.5.59-0+deb8u1-log
-- Version de PHP: 5.6.33-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `rotterdam_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sepaN` varchar(255) NOT NULL,
  `typeID` tinyint(4) NOT NULL,
  `balance` bigint(20) NOT NULL COMMENT '€',
  PRIMARY KEY (`id`),
  UNIQUE KEY `sepaN` (`sepaN`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `accounts`
--

INSERT INTO `accounts` (`id`, `sepaN`, `typeID`, `balance`) VALUES
(1, 'BE11111111111111', 1, 1267),
(2, 'BE22222222222222', 1, 1862),
(3, 'NL3333333333333333', 1, 3854),
(4, 'NL4444444444444444', 2, 1000000);

-- --------------------------------------------------------

--
-- Structure de la table `accountsCards`
--

CREATE TABLE IF NOT EXISTS `accountsCards` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cardID` smallint(6) NOT NULL,
  `accountID` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cardID` (`cardID`,`accountID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `accountsCards`
--

INSERT INTO `accountsCards` (`id`, `cardID`, `accountID`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 3, 4),
(5, 4, 3);

-- --------------------------------------------------------

--
-- Structure de la table `accountTypes`
--

CREATE TABLE IF NOT EXISTS `accountTypes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `accountTypes`
--

INSERT INTO `accountTypes` (`id`, `name`) VALUES
(1, 'Checking'),
(2, 'Savings');

-- --------------------------------------------------------

--
-- Structure de la table `branches`
--

CREATE TABLE IF NOT EXISTS `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `address` varchar(255) NOT NULL,
  `postalCode` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `country` char(2) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `branches`
--

INSERT INTO `branches` (`id`, `address`, `postalCode`, `city`, `country`, `phone`, `email`) VALUES
(1, 'Avenue des Loisirs 1', '1140', 'Bruxelles', '1', '02/724 11 40', 'evere.olympiades@ing.be'),
(2, 'Parvis Saint Gilles 23', '1060', 'Bruxelles', '1', '02/543 16 00', 'stgil.parvis-voorplein@ing.be'),
(3, 'Mathenesserplein 100', '3023 LA', 'Rotterdam', '3', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `cards`
--

CREATE TABLE IF NOT EXISTS `cards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clientID` bigint(20) NOT NULL,
  `cardNumber` varchar(255) NOT NULL,
  `typeID` tinyint(4) NOT NULL,
  `pinCode` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `cards`
--

INSERT INTO `cards` (`id`, `clientID`, `cardNumber`, `typeID`, `pinCode`) VALUES
(1, 1, '11111111111111111', 1, '011c945f30ce2cbafc452f39840f025693339c42'),
(2, 2, '22222222222222222', 1, 'fea7f657f56a2a448da7d4b535ee5e279caf3d9a'),
(3, 3, '33333333333333333', 1, 'f56d6351aa71cff0debea014d13525e42036187a'),
(4, 3, '44444444444444444', 2, '7110eda4d09e062aa5e4a390b0a572ac0d2c0220');

-- --------------------------------------------------------

--
-- Structure de la table `cardTypes`
--

CREATE TABLE IF NOT EXISTS `cardTypes` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `cardTypes`
--

INSERT INTO `cardTypes` (`id`, `name`) VALUES
(1, 'Debit'),
(2, 'Credit');

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint(5) unsigned NOT NULL AUTO_INCREMENT,
  `lastname` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `postalCode` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `countryID` char(2) NOT NULL,
  `nationalID` varchar(255) NOT NULL,
  `language` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `branchID` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `clients`
--

INSERT INTO `clients` (`id`, `lastname`, `firstname`, `birthdate`, `address`, `phone`, `postalCode`, `city`, `countryID`, `nationalID`, `language`, `email`, `branchID`) VALUES
(1, 'Hamilton', 'Margaret', '1990-03-04', 'Rue Vanderschrick n°656', '+32 111 11 11 11', '1060', 'Bruxelles', '1', '11.11.11-111.11', 'EN', 'alexandrentougas@gmail.com', 2),
(2, 'McIntosh', 'John', '1981-06-17', 'Bara street n°100', '+32 222 22 22 22 ', '1170', 'Bruxelles', '1', '22.22.22-222.22', 'FR', 'laurenthulstaert@gmail.com', 1),
(3, 'Sampras', 'Pete', '1985-12-12', 'Van Nelleweg 1', '+31 333 33 33 33', '3044 BC', 'Rotterdam', '3', '33.33.33-333.33', 'NL', 'princekombo@gmail.com', 3);

-- --------------------------------------------------------

--
-- Structure de la table `clientsAccounts`
--

CREATE TABLE IF NOT EXISTS `clientsAccounts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `clientID` smallint(6) NOT NULL,
  `accountID` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clientID` (`clientID`,`accountID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `clientsAccounts`
--

INSERT INTO `clientsAccounts` (`id`, `clientID`, `accountID`) VALUES
(1, 1, 1),
(2, 2, 2),
(3, 3, 3),
(4, 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `countryCode` char(2) NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Contenu de la table `countries`
--

INSERT INTO `countries` (`id`, `countryCode`, `name`) VALUES
(1, 'BE', 'Belgium'),
(2, 'FR', 'France'),
(3, 'NL', 'Netherlands');

-- --------------------------------------------------------

--
-- Structure de la table `schedule`
--

CREATE TABLE IF NOT EXISTS `schedule` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branchID` smallint(6) NOT NULL,
  `day` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `startAM` time DEFAULT NULL,
  `endAM` time DEFAULT NULL,
  `startPM` time DEFAULT NULL,
  `endPM` time DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `schedule`
--

INSERT INTO `schedule` (`id`, `branchID`, `day`, `startAM`, `endAM`, `startPM`, `endPM`) VALUES
(1, 1, 'Monday', '09:00:00', '12:30:00', '13:30:00', '17:00:00'),
(2, 1, 'Tuesday', NULL, NULL, NULL, NULL),
(3, 1, 'Wednesday', '09:00:00', '12:30:00', '13:30:00', '17:00:00'),
(4, 1, 'Thursday', '10:00:00', '12:30:00', '13:30:00', '19:00:00'),
(5, 1, 'Friday', '09:00:00', '12:30:00', '13:30:00', '17:00:00'),
(6, 1, 'Saturday', NULL, NULL, NULL, NULL),
(7, 1, 'Sunday', NULL, NULL, NULL, NULL),
(8, 2, 'Monday', NULL, NULL, NULL, NULL),
(9, 2, 'Tuesday', '09:00:00', '12:30:00', '13:30:00', '17:00:00'),
(10, 2, 'Wednesday', '09:00:00', '12:30:00', '13:30:00', '17:00:00'),
(11, 2, 'Thursday', NULL, NULL, NULL, NULL),
(12, 2, 'Friday', '09:00:00', '12:30:00', '13:30:00', '17:00:00'),
(13, 2, 'Saturday', NULL, NULL, NULL, NULL),
(15, 2, 'Sunday', NULL, NULL, NULL, NULL),
(16, 3, 'Monday', '09:30:00', NULL, NULL, '18:00:00'),
(17, 3, 'Tuesday', '09:30:00', NULL, NULL, '18:00:00'),
(18, 3, 'Wednesday', '09:30:00', NULL, NULL, '18:00:00'),
(19, 3, 'Thursday', '09:30:00', NULL, NULL, '18:00:00'),
(20, 3, 'Friday', '09:30:00', NULL, NULL, '18:00:00'),
(21, 3, 'Saturday', '09:30:00', NULL, NULL, '16:00:00'),
(22, 3, 'Sunday', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE IF NOT EXISTS `transactions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sendingAccount` varchar(255) NOT NULL,
  `receivingAccount` varchar(255) NOT NULL,
  `senderInfo` text NOT NULL,
  `receiverInfo` text NOT NULL,
  `amount` bigint(20) NOT NULL COMMENT '€',
  `communication` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

--
-- Contenu de la table `transactions`
--

INSERT INTO `transactions` (`id`, `sendingAccount`, `receivingAccount`, `senderInfo`, `receiverInfo`, `amount`, `communication`) VALUES
(1, 'BE11111111111111', 'BE22222222222222', 'Ntougas Alexandre\nRue vanderschrick n°656\n1060 Bruxelles\n', 'Hulstaert Laurent\nBara street n°100\n1170 Bruxelles', 37, 'Pizza'),
(2, 'BE22222222222222', 'NL3333333333333333', 'Hulstaert Laurent\nBara street n°100\n1170 Bruxelles', 'Kombo Prince\r\nVan Nelleweg 1\r\n3044 BC Rotterdam', 10000, 'Website'),
(7, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 100, 'yeahh'),
(8, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 200, 'Happy Birthday'),
(9, 'NL3333333333333333', 'BE22222222222222', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'McIntosh John\nBara street n°100\n1170 Bruxelles\nBelgium', 500, 'how are you'),
(10, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 100, 'where the moon is it tonight'),
(11, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 100, 'transfer 100€ to Margaret Hamilton'),
(12, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 100, 'haha Kelly'),
(13, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 200, 'Happy Birthday'),
(14, 'NL3333333333333333', 'BE22222222222222', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'McIntosh John\nBara street n°100\n1170 Bruxelles\nBelgium', 25, 'Happy Birthday John'),
(15, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 25, 'Happy Birthday Margaret'),
(16, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 25, 'Happy Birthday'),
(17, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 25, 'Happy Birthday Margaret'),
(18, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 25, 'talk to my banking app'),
(19, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 25, 'Happy Birthday'),
(20, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 25, 'Happy Birthday Margaret'),
(21, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 25, 'Happy Birthday Margaret'),
(22, 'NL3333333333333333', 'BE11111111111111', 'Sampras Pete\nVan Nelleweg 1\n3044 BC Rotterdam\nNetherlands', 'Hamilton Margaret\nRue Vanderschrick n°656\n1060 Bruxelles\nBelgium', 25, 'Happy Birthday Margaret');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
