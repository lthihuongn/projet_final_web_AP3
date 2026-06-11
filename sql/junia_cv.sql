-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 11, 2026 at 08:46 AM
-- Server version: 5.7.24
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `junia_cv`
--

-- --------------------------------------------------------

--
-- Table structure for table `competences`
--

CREATE TABLE `competences` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `competence` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `competences`
--

INSERT INTO `competences` (`id`, `etudiant_id`, `competence`) VALUES
(1, 1, 'cyber'),
(2, 4, 'gestion de projet '),
(3, 3, 'recherche'),
(6, 8, 'Cybersécurité'),
(7, 8, 'Pentesting'),
(8, 8, 'PHP / MySQL'),
(9, 8, 'Gadgets tactiques'),
(10, 9, 'bat');

-- --------------------------------------------------------

--
-- Table structure for table `convocations`
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
-- Dumping data for table `convocations`
--

INSERT INTO `convocations` (`id`, `etudiant_id`, `entreprise_id`, `type_contrat`, `message`, `date_convocation`, `statut`) VALUES
(1, 1, 1, 'CDD', 'Bonjour, \r\nvotre profile nous semble être parfait merci de venir à ce rendez vous ', '2026-06-19 13:15:10', 'en attente'),
(2, 4, 2, 'CDI', 'Message de Tales : \r\n\r\nil faut aimer les avions !', '2026-06-20 13:15:10', 'en attente'),
(3, 1, 1, NULL, 'coucou lilou', '2026-06-18 22:00:00', 'en attente'),
(4, 9, 1, NULL, NULL, '2026-06-17 22:00:00', 'en attente');

-- --------------------------------------------------------

--
-- Table structure for table `entreprises`
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
-- Dumping data for table `entreprises`
--

INSERT INTO `entreprises` (`id`, `nom`, `email_contact`, `password_hash`, `logo`, `secteur`, `date_creation`) VALUES
(1, 'EDF', 'Isa@edf.fr', 'isa', NULL, 'énergie', '2026-06-10 13:13:12'),
(2, 'Tales ', 'Tales@contact.com', 'tales', NULL, 'Avion ', '2026-06-10 13:13:12'),
(3, 'Pote au feu', 'potofeu@gmail.com', '$2y$10$/yBBEUYay3L5/dGn7.xvZO1y2BFiq8WEkc7H0cnsKhsv86mpIVLl.', NULL, 'cape & autres', '2026-06-10 23:17:52'),
(4, 'Crépouille', 'cestbonlescrepes@oui.com', '$2y$10$YrkZcGZ77fA0kkNJZx.2OeIrGzVD2eTpRWXbeAXoxx7i/pB0l1iOa', NULL, 'et c\'est vegan !', '2026-06-10 23:28:46');

-- --------------------------------------------------------

--
-- Table structure for table `etudiants`
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
-- Dumping data for table `etudiants`
--

INSERT INTO `etudiants` (`id`, `nom`, `email`, `password_hash`, `photo`, `biographie`, `domaines_recherche`, `date_creation`, `date_modification`) VALUES
(1, 'Lilou', 'Lilou@gmail.com', 'lilouchat', NULL, 'lilou fille de la classe d\'AP3 2026 junia', 'cyber', '2026-06-10 13:09:20', '2026-06-10 13:09:20'),
(3, 'Agathe', 'agaaathe@gmail.fr', 'lamotoetAgathe', NULL, 'Salut, moi c\'est Agathe, j\'ai bien faire de la moto et voili voilou ', 'La recherche ', '2026-06-10 13:11:58', '2026-06-10 13:11:58'),
(4, 'Salomé ', 'salome@edf.fr', 'motdepasse', NULL, 'je suis salomé j\'aime bien les scout et je deteste la soupe ', 'gestion de projet informatique ', '2026-06-10 13:11:58', '2026-06-10 13:11:58'),
(8, 'Batman Justice', 'batman@junia.fr', '$2y$10$vM8KNYD68W3B.K7OEs6fXuxHjUv7p8SkaA.O3Xw0jZ2eM7eW7UeS.', NULL, 'Justicier de l\'ombre la nuit, étudiant passionné de cybersécurité et d\'architecture logicielle le jour à JUNIA.', 'Stage, Alternance', '2026-06-10 23:05:14', '2026-06-10 23:05:14'),
(9, 'donilde', 'donilde@donilde.fr', '$2y$10$BgyFiIXfbVTr9dhzrWdBjujKgyzNPw6uEuXfbjKCQ3YkEqAPvGLXS', NULL, 'For JUSTIICCEEE', 'Stage, Alternance, Mobilité', '2026-06-11 08:27:57', '2026-06-11 08:28:54');

-- --------------------------------------------------------

--
-- Table structure for table `experiences`
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
-- Dumping data for table `experiences`
--

INSERT INTO `experiences` (`id`, `etudiant_id`, `entreprise`, `poste`, `date_debut`, `date_fin`, `description`) VALUES
(1, 3, 'Carrfour ', 'cassiére', '2026-06-16', '2026-06-20', 'j\'ai était cassiére '),
(2, 4, 'decathlon ', 'Mise en rayon ', '2019-12-12', '2019-06-14', ':)'),
(4, 8, 'Wayne Enterprises', 'Stagiaire en sécurité offensive', '2025-05-01', '2025-08-31', 'Audits de sécurité, tests d\'intrusion sur les gadgets embarqués et optimisation des systèmes de surveillance de la Batcave.'),
(5, 9, 'Batmobile', 'driver', '2026-02-21', '2026-06-13', 'it was fun');

-- --------------------------------------------------------

--
-- Table structure for table `formations`
--

CREATE TABLE `formations` (
  `id` int(11) NOT NULL,
  `etudiant_id` int(11) NOT NULL,
  `ecole` varchar(100) DEFAULT NULL,
  `diplome` varchar(100) DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `formations`
--

INSERT INTO `formations` (`id`, `etudiant_id`, `ecole`, `diplome`, `date_fin`) VALUES
(1, 1, 'Junia', 'CIR', '2025-07-31'),
(2, 4, 'Junia', 'Adimaker ', '2024-06-01'),
(4, 8, 'JUNIA', 'CIR - Réseaux & Cybersécurité', '2026-06-30'),
(5, 9, 'Gotham', 'fight', '0101-01-01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `competences`
--
ALTER TABLE `competences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- Indexes for table `convocations`
--
ALTER TABLE `convocations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_convocations_etudiant` (`etudiant_id`),
  ADD KEY `idx_convocations_entreprise` (`entreprise_id`);

--
-- Indexes for table `entreprises`
--
ALTER TABLE `entreprises`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_contact` (`email_contact`),
  ADD KEY `idx_entreprise_email` (`email_contact`);

--
-- Indexes for table `etudiants`
--
ALTER TABLE `etudiants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_etudiant_email` (`email`);

--
-- Indexes for table `experiences`
--
ALTER TABLE `experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- Indexes for table `formations`
--
ALTER TABLE `formations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `etudiant_id` (`etudiant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `competences`
--
ALTER TABLE `competences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `convocations`
--
ALTER TABLE `convocations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `entreprises`
--
ALTER TABLE `entreprises`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `etudiants`
--
ALTER TABLE `etudiants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `experiences`
--
ALTER TABLE `experiences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `formations`
--
ALTER TABLE `formations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `competences`
--
ALTER TABLE `competences`
  ADD CONSTRAINT `competences_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `convocations`
--
ALTER TABLE `convocations`
  ADD CONSTRAINT `convocations_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `convocations_ibfk_2` FOREIGN KEY (`entreprise_id`) REFERENCES `entreprises` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `experiences`
--
ALTER TABLE `experiences`
  ADD CONSTRAINT `experiences_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `formations`
--
ALTER TABLE `formations`
  ADD CONSTRAINT `formations_ibfk_1` FOREIGN KEY (`etudiant_id`) REFERENCES `etudiants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;