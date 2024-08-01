-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 01, 2024 at 04:04 PM
-- Server version: 8.0.31
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `article`
--

DROP TABLE IF EXISTS `article`;
CREATE TABLE IF NOT EXISTS `article` (
  `id_Article` int NOT NULL AUTO_INCREMENT,
  `contenu` text NOT NULL,
  `date_post` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_Article`),
  KEY `fk_utilisateur` (`id_utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `article`
--

INSERT INTO `article` (`id_Article`, `contenu`, `date_post`, `id_utilisateur`) VALUES
(11, 'Momo is good ', '2024-07-08 17:49:10', 16),
(10, 'I like Anime ', '2024-07-08 17:46:27', 17),
(9, 'I like anime', '2024-07-08 17:46:04', 17),
(12, 'i love to love ru', '2024-07-08 17:53:29', 16),
(14, 'SAO', '2024-07-08 17:56:43', 16),
(15, 'SAO', '2024-07-08 17:59:51', 16),
(16, 'My waifuuuuuuu', '2024-07-08 18:00:30', 16),
(17, 'i love to love ru', '2024-07-09 10:24:43', 16),
(18, 'I like anime', '2024-07-09 10:25:03', 16),
(19, 'i\'m jhon', '2024-07-10 10:05:41', 15),
(20, 'I like anime', '2024-07-10 10:05:58', 15),
(21, 'Rimuru tempest', '2024-07-10 10:11:04', 15),
(22, 'I like anime', '2024-07-10 10:13:15', 17),
(23, 'Arifureta', '2024-07-10 10:16:00', 17);

-- --------------------------------------------------------

--
-- Table structure for table `imagepost`
--

DROP TABLE IF EXISTS `imagepost`;
CREATE TABLE IF NOT EXISTS `imagepost` (
  `id_image` int NOT NULL AUTO_INCREMENT,
  `url_image` varchar(255) NOT NULL,
  `id_article` int NOT NULL,
  PRIMARY KEY (`id_image`),
  KEY `fk_post` (`id_article`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `imagepost`
--

INSERT INTO `imagepost` (`id_image`, `url_image`, `id_article`) VALUES
(1, 'post/668bfbac3f7ef.jpg', 9),
(2, 'post/668bfc669b16b.jpg', 11),
(3, 'post/668bfd68f0e49.jpg', 12),
(5, 'post/668bfe2b9ad8a.jpg', 14),
(6, 'post/668bfee71af90.jpg', 15),
(7, 'post/668bff0e3afdd.jpg', 16),
(8, 'post/668ce5cf130fd.jpg', 18),
(9, 'post/668e32d6a92f8.jpg', 20),
(10, 'post/668e34083a318.jpg', 21),
(11, 'post/668e348bd6113.jpg', 22),
(12, 'post/668e353087be0.jpg', 23);

-- --------------------------------------------------------

--
-- Table structure for table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id_Utilisateur` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `mot_de_passe` varchar(50) NOT NULL,
  `image_profile` varchar(50) NOT NULL,
  `date_de_creation` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(50) NOT NULL,
  PRIMARY KEY (`id_Utilisateur`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `utilisateur`
--

INSERT INTO `utilisateur` (`id_Utilisateur`, `nom`, `prenom`, `mot_de_passe`, `image_profile`, `date_de_creation`, `email`) VALUES
(17, 'Radanielina', 'Aina', '123456789', 'profile/668e3507df8bd.jpg', '2024-07-05 21:56:25', 'aina@mail.com'),
(15, 'Doe', 'John', '147258369', 'profile/668e32e99e23a.jpg', '2024-07-05 19:40:42', 'john@doe.com'),
(16, 'Smith', 'Kevin', '147258369', 'profile/668ce64bc554b.jpeg', '2024-07-05 21:54:37', 'Kevin@smith.com');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
