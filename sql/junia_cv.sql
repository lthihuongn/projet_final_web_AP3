-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mer. 10 juin 2026 à 13:20
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cv_platform`
--

-- --------------------------------------------------------

--
-- Structure de la table `competences`
--

CREATE TABLE `competences` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `competence` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `competences`
--

INSERT INTO `competences` (`id`, `etudiant_id`, `competence`) VALUES
(1, 1, 'cyber'),
(2, 4, 'gestion de projet '),
(3, 3, 'recherche');

-- --------------------------------------------------------

--
-- Structure de la table `convocations`
--

CREATE TABLE `convocations` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `entreprise_id` int(11) NOT NULL,
  `type_contrat` varchar(50) DEFAULT NULL,
  `message` text,
  `date_convocation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` varchar(20) DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `convocations`
--

INSERT INTO `convocations` (`id`, `etudiant_id`, `entreprise_id`, `type_contrat`, `message`, `date_convocation`, `statut`) VALUES
(1, 1, 1, 'CDD', 'Bonjour, \r\nvotre profile nous semble être parfait merci de venir à ce rendez vous ', '2026-06-19 13:15:10', 'en attente'),
(2, 4, 2, 'CDI', 'Message de Tales : \r\n\r\nil faut aimer les avions !', '2026-06-20 13:15:10', 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `entreprises`
--

CREATE TABLE `entreprises` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email_contact` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `secteur` varchar(100) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `email_contact`, `password_hash`, `logo`, `secteur`, `date_creation`) VALUES
(1, 'EDF', 'Isa@edf.fr', 'isa', NULL, 'énergie', '2026-06-10 13:13:12'),
(2, 'Tales ', 'Tales@contact.com', 'tales', NULL, 'Avion ', '2026-06-10 13:13:12');

-- --------------------------------------------------------

--
-- Structure de la table `etudiants`
--

CREATE TABLE `etudiants` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `biographie` text,
  `domaines_recherche` varchar(255) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `etudiants`
--

INSERT INTO `etudiants` (`id`, `nom`, `email`, `password_hash`, `photo`, `biographie`, `domaines_recherche`, `date_creation`, `date_modification`) VALUES
(1, 'Lilou', 'Lilou@gmail.com', 'lilouchat', NULL, 'lilou fille de la classe d\'AP3 2026 junia', 'cyber', '2026-06-10 13:09:20', '2026-06-10 13:09:20'),
(3, 'Agathe', 'agaaathe@gmail.fr', 'lamotoetAgathe', NULL, 'Salut, moi c\'est Agathe, j\'ai bien faire de la moto et voili voilou ', 'La recherche ', '2026-06-10 13:11:58', '2026-06-10 13:11:58'),
(4, 'Salomé ', 'salome@edf.fr', 'motdepasse', NULL, 'je suis salomé j\'aime bien les scout et je deteste la soupe ', 'gestion de projet informatique ', '2026-06-10 13:11:58', '2026-06-10 13:11:58');

-- --------------------------------------------------------

--
-- Structure de la table `experiences`
--

CREATE TABLE `experiences` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `entreprise` varchar(100) DEFAULT NULL,
  `poste` varchar(100) DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `experiences`
--

INSERT INTO `experiences` (`id`, `etudiant_id`, `entreprise`, `poste`, `date_debut`, `date_fin`, `description`) VALUES
(1, 3, 'Carrfour ', 'cassiére', '2026-06-16', '2026-06-20', 'j\'ai était cassiére '),
(2, 4, 'decathlon ', 'Mise en rayon ', '2019-12-12', '2019-06-14', ':)');

-- --------------------------------------------------------

--
-- Structure de la table `formations`
--

CREATE TABLE `formations` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `ecole` varchar(100) DEFAULT NULL,
  `diplome` varchar(100) DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `formations`
--

INSERT INTO `formations` (`id`, `etudiant_id`, `ecole`, `diplome`, `date_fin`) VALUES
(1, 1, 'Junia', 'CIR', '2025-07-31'),
(2, 4, 'Junia', 'Adimaker ', '2024-06-01');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `competences`
--
ALTER TABLE `competences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- Index pour la table `convocations`
--
ALTER TABLE `convocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_convocations_etudiant` (`etudiant_id`),
  ADD KEY `idx_convocations_entreprise` (`entreprise_id`);

--
-- Index pour la table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_contact` (`email_contact`),
  ADD KEY `idx_entreprise_email` (`email_contact`);

--
-- Index pour la table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_etudiant_email` (`email`);

--
-- Index pour la table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- Index pour la table `formations`
--
ALTER TABLE `formations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `competences`
--
ALTER TABLE `competences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `convocations`
--
ALTER TABLE `convocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `formations`
--
ALTER TABLE `formations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `competences`
--
ALTER TABLE `competences`
  ADD CONSTRAINT `competences_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `convocations`
--
ALTER TABLE `convocations`
  ADD CONSTRAINT `convocations_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `convocations_ibfk_2` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `experiences`
--
ALTER TABLE `experiences`
  ADD CONSTRAINT `experiences_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `formations`
--
ALTER TABLE `formations`
  ADD CONSTRAINT `formations_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;