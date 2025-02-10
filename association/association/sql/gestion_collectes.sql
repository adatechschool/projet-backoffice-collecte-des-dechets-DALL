-- phpMyAdmin SQL Dump
-- version 5.2.1deb3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : dim. 02 fév. 2025 à 17:47
-- Version du serveur : 8.0.41-0ubuntu0.24.04.1
-- Version de PHP : 8.3.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestion_collectes`
--

-- --------------------------------------------------------

--
-- Structure de la table `benevoles`
--

CREATE TABLE `benevoles` (
  `id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','participant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `benevoles`
--

INSERT INTO `benevoles` (`id`, `nom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Alice Dupont', 'alice.dupont@example.com', '5504b4f70ca78f97137ff8ad5f910248', 'admin'),
(2, 'Bob Martin', 'bob.martin@example.com', '2e248e7a3b4fbaf2081b3dff10ee402b', 'participant'),
(3, 'Charlie Dubois', 'charlie.dubois@example.com', '9148b120a413e9e84e57f1231f04119a', 'participant');

-- --------------------------------------------------------

--
-- Structure de la table `collectes`
--

CREATE TABLE `collectes` (
  `id` int NOT NULL,
  `date_collecte` date NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `id_benevole` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `collectes`
--

INSERT INTO `collectes` (`id`, `date_collecte`, `lieu`, `id_benevole`) VALUES
(1, '2024-02-01', 'Parc Central', 1),
(2, '2024-02-05', 'Plage du Sud', 2),
(3, '2024-02-10', 'Quartier Nord', 1),
(4, '2025-01-04', 'paris', 3),
(6, '3058-06-25', 'lyon', 3),
(7, '2029-04-07', 'toulon', 3),
(8, '2026-04-25', 'lille', 1),
(9, '2028-05-10', 'toulouse', 3),
(10, '0008-02-02', 'vertou', 1);

-- --------------------------------------------------------

--
-- Structure de la table `dechets_collectes`
--

CREATE TABLE `dechets_collectes` (
  `id` int NOT NULL,
  `id_collecte` int DEFAULT NULL,
  `type_dechet` varchar(50) NOT NULL,
  `quantite_kg` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `dechets_collectes`
--

INSERT INTO `dechets_collectes` (`id`, `id_collecte`, `type_dechet`, `quantite_kg`) VALUES
(1, 1, 'plastique', 5.2),
(2, 1, 'verre', 3.1),
(3, 2, 'métal', 2.4),
(4, 2, 'papier', 1.7),
(5, 3, 'organique', 6.5),
(6, 3, 'plastique', 4.3);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `benevoles`
--
ALTER TABLE `benevoles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `collectes`
--
ALTER TABLE `collectes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_benevole` (`id_benevole`);

--
-- Index pour la table `dechets_collectes`
--
ALTER TABLE `dechets_collectes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_collecte` (`id_collecte`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `benevoles`
--
ALTER TABLE `benevoles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `collectes`
--
ALTER TABLE `collectes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `dechets_collectes`
--
ALTER TABLE `dechets_collectes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `collectes`
--
ALTER TABLE `collectes`
  ADD CONSTRAINT `collectes_ibfk_1` FOREIGN KEY (`id_benevole`) REFERENCES `benevoles` (`id`);

--
-- Contraintes pour la table `dechets_collectes`
--
ALTER TABLE `dechets_collectes`
  ADD CONSTRAINT `dechets_collectes_ibfk_1` FOREIGN KEY (`id_collecte`) REFERENCES `collectes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
