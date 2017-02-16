-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:8889
-- Généré le :  Jeu 09 Février 2017 à 10:38
-- Version du serveur :  5.6.33
-- Version de PHP :  7.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `lillebio`
--

-- --------------------------------------------------------

--
-- Structure de la table `Categorie`
--

CREATE TABLE `Categorie` (
  `numCat` int(2) NOT NULL,
  `nomCat` varchar(25) NOT NULL,
  `numImg` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `Categorie`
--

INSERT INTO `Categorie` (`numCat`, `nomCat`, `numImg`) VALUES
(1, 'TradiChti', 32),
(2, 'Française', 29),
(3, 'Burger', 26),
(4, 'Vegetarien', 36),
(5, 'Chinois', 27),
(6, 'Africain', 28),
(7, 'Indien', 30),
(8, 'Thaïlandais', 35),
(9, 'Italien', 34);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `Categorie`
--
ALTER TABLE `Categorie`
  ADD PRIMARY KEY (`numCat`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `Categorie`
--
ALTER TABLE `Categorie`
  MODIFY `numCat` int(2) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;