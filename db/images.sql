-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:8889
-- Généré le :  Jeu 09 Février 2017 à 10:41
-- Version du serveur :  5.6.33
-- Version de PHP :  7.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `lillebio`
--

-- --------------------------------------------------------

--
-- Structure de la table `Image`
--

CREATE TABLE `Image` (
  `numImg` int(3) NOT NULL,
  `nomImg` varchar(25) NOT NULL,
  `urlImg` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Image`
--

INSERT INTO `Image` (`numImg`, `nomImg`, `urlImg`) VALUES
(1, 'restau1', '/img/restaurant/restau-1.png'),
(2, 'restau2', '/img/restaurant/restau-2.png'),
(3, 'restau3', '/img/restaurant/restau-3.png'),
(4, 'restau4', '/img/restaurant/restau-4.png'),
(5, 'restau5', '/img/restaurant/restau-5.png'),
(6, 'restau6', '/img/restaurant/restau-6.png'),
(7, 'restau7', '/img/restaurant/restau-7.png'),
(8, 'restau8', '/img/restaurant/restau-8.png'),
(9, 'restau9', '/img/restaurant/restau-9.png'),
(10, 'restau10', '/img/restaurant/restau-10.png'),
(11, 'restau11', '/img/restaurant/restau-11.png'),
(12, 'restau12', '/img/restaurant/restau-12.png'),
(13, 'restau13', '/img/restaurant/restau-13.png'),
(14, 'restau14', '/img/restaurant/restau-14.png'),
(15, 'restau15', '/img/restaurant/restau-15.png'),
(16, 'restau16', '/img/restaurant/restau-17.png'),
(17, 'restau17', '/img/restaurant/restau-18.png'),
(18, 'restau18', '/img/restaurant/restau-19.png'),
(19, 'restau19', '/img/restaurant/restau-20.png'),
(20, 'restau20', '/img/restaurant/restau-21.png'),
(21, 'restau21', '/img/restaurant/restau-22.png'),
(22, 'restau22', '/img/restaurant/restau-23.png'),
(23, 'restau23', '/img/restaurant/restau-24.png'),
(24, 'restau24', '/img/restaurant/restau-25.png'),
(25, 'restau25', '/img/restaurant/restau-26.png'),
(26, 'categorie', '/img/categorie/burger.php'),
(27, 'categorie', '/img/categorie/chinois.php'),
(28, 'categorie', '/img/categorie/creole.php'),
(29, 'categorie', '/img/categorie/français.php'),
(30, 'categorie', '/img/categorie/indien.php'),
(31, 'categorie', '/img/categorie/japonais.php'),
(32, 'categorie', '/img/categorie/lille.php'),
(33, 'categorie', '/img/categorie/mexicain.php'),
(34, 'categorie', '/img/categorie/pizzeria.php'),
(35, 'categorie', '/img/categorie/thailandais.php'),
(36, 'categorie', '/img/categorie/vegetarien.php'),
(37, 'categorie', '/img/categorie/italien.php');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Image`
--
ALTER TABLE `Image`
  ADD PRIMARY KEY (`numImg`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Image`
--
ALTER TABLE `Image`
  MODIFY `numImg` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;