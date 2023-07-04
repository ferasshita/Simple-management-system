-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  Dim 02 juil. 2023 à 18:15
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `employee`
--

-- --------------------------------------------------------

--
-- Structure de la table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `company` varchar(100) DEFAULT NULL,
  `birthday` varchar(100) DEFAULT NULL,
  `company_action` varchar(100) DEFAULT NULL,
  `company_address` varchar(100) DEFAULT NULL,
  `company_do` varchar(100) DEFAULT NULL,
  `date` date DEFAULT current_timestamp(),
  `education` varchar(100) DEFAULT NULL,
  `enter_date` varchar(100) DEFAULT NULL,
  `expiring` varchar(100) DEFAULT NULL,
  `gender` varchar(100) DEFAULT NULL,
  `groupco` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `nationality` varchar(100) DEFAULT NULL,
  `paid` varchar(100) DEFAULT NULL,
  `passport` varchar(100) DEFAULT NULL,
  `qualification_years` varchar(100) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `starting` varchar(100) DEFAULT NULL,
  `visa` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
