-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 29 mars 2023 à 07:47
-- Version du serveur : 5.7.36
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api_articles`
--

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id_articles` int(11) NOT NULL AUTO_INCREMENT,
  `date_publi` date NOT NULL,
  `auteur` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `DerniereModification` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id_articles`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id_articles`, `date_publi`, `auteur`, `contenu`, `DerniereModification`) VALUES
(1, '2023-03-10', 'diego', 'Ceci est un article de blog sur les pinguins d\'Alaska.', '2023-03-23 23:52:25'),
(2, '2023-03-11', 'Baran', 'Comment les nuages volent-ils ? Articles en exclusivité.', '2023-03-23 23:53:08'),
(3, '2023-03-13', 'Michel', 'Article du jour : Michel et ses fonctions Javascript.', '2023-03-23 23:54:12'),
(4, '2023-03-14', 'Yayha', 'Comment l\'Algérie va bientôt dominer le monde.', '2023-03-23 23:54:51');

-- --------------------------------------------------------

--
-- Structure de la table `disliked`
--

DROP TABLE IF EXISTS `disliked`;
CREATE TABLE IF NOT EXISTS `disliked` (
  `id_articles` int(11) NOT NULL,
  `login` varchar(9) NOT NULL,
  `has_disliked` int(2) NOT NULL,
  PRIMARY KEY (`id_articles`,`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `disliked`
--

INSERT INTO `disliked` (`id_articles`, `login`, `has_disliked`) VALUES
(4, 'diego', 1),
(22, 'diego', 1),
(1, 'baran', 1),
(3, 'diego', 1);

-- --------------------------------------------------------

--
-- Structure de la table `liked`
--

DROP TABLE IF EXISTS `liked`;
CREATE TABLE IF NOT EXISTS `liked` (
  `id_articles` int(11) NOT NULL,
  `login` varchar(9) NOT NULL,
  `has_liked` tinyint(1) NOT NULL,
  PRIMARY KEY (`id_articles`,`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `liked`
--

INSERT INTO `liked` (`id_articles`, `login`, `has_liked`) VALUES
(1, 'diego', 1),
(3, 'diego', 1),
(1, 'baran', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `login` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` varchar(9) NOT NULL,
  PRIMARY KEY (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`login`, `password`, `role`) VALUES
('diego', 'diego972', 'publisher'),
('baran', 'baran31', 'moderator'),
('brice', 'PassProf', 'publisher');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
