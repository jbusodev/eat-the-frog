-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 02, 2020 at 08:13 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+02:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `d7ggb86d69c7un`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_classeurs`
--

CREATE TABLE `tbl_classeurs` (
  `numero` int(10) NOT NULL,
  `titreClasseur` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `nbRepertoire` int(20) NOT NULL,
  `num_tbl_users` int(10) NOT NULL,
  `num_tbl_images` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_classeurs`
--

INSERT INTO `tbl_classeurs` (`numero`, `titreClasseur`, `nbRepertoire`, `num_tbl_users`, `num_tbl_images`) VALUES
(1, 'Module 500', 11, 2, 58),
(6, 'Factures 2015', 12, 2, NULL),
(23, 'Cusine', 10, 2, NULL),
(32, '2022', 10, 29, 63),
(55, '2020', 11, 29, 61);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_images`
--

CREATE TABLE `tbl_images` (
  `numero` int(11) NOT NULL,
  `dateUploaded` datetime NOT NULL,
  `nomImage` varchar(50) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_images`
--

INSERT INTO `tbl_images` (`numero`, `dateUploaded`, `nomImage`) VALUES
(58, '2015-05-20 03:37:48', 'busojo_2015-05-20_03-37-48.jpg'),
(60, '2015-05-21 19:26:23', 'busojo_2015-05-21_19-26-23.jpg'),
(61, '2020-05-30 12:01:12', 'portfolio_2020-05-30_12-01-12.jpg'),
(63, '2020-05-31 09:03:46', 'portfolio_2020-05-31_09-03-46.jpg'),
(67, '2020-05-31 22:07:15', 'portfolio_2020-05-31_22-07-15.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_niveaux`
--

CREATE TABLE `tbl_niveaux` (
  `numero` int(11) NOT NULL,
  `niveau` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_niveaux`
--

INSERT INTO `tbl_niveaux` (`numero`, `niveau`) VALUES
(1, 'Super Administrateur'),
(2, 'Administrateur'),
(3, 'Utilisateur standard');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_priorites`
--

CREATE TABLE `tbl_priorites` (
  `numero` int(10) NOT NULL,
  `priorite` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `remarque` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_priorites`
--

INSERT INTO `tbl_priorites` (`numero`, `priorite`, `remarque`) VALUES
(1, 'A', 'Très important et indispensable'),
(2, 'B', 'Important mais pas indispensable'),
(3, 'C', 'Peut etre fait plus tard'),
(4, 'D', 'Neutre'),
(5, 'E', 'Sans conséquences');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_references_cl`
--

CREATE TABLE `tbl_references_cl` (
  `numero` int(10) NOT NULL,
  `num_tbl_classeurs` int(10) NOT NULL,
  `num_tbl_titres` int(10) NOT NULL,
  `chronologie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_references_cl`
--

INSERT INTO `tbl_references_cl` (`numero`, `num_tbl_classeurs`, `num_tbl_titres`, `chronologie`) VALUES
(271, 23, 3, 2),
(272, 23, 1, 10),
(710, 6, 1, 4),
(711, 6, 3, 6),
(712, 6, 2, 9),
(721, 1, 4, 1),
(722, 1, 3, 2),
(723, 1, 2, 3),
(724, 1, 1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_taches`
--

CREATE TABLE `tbl_taches` (
  `numero` int(10) NOT NULL,
  `num_tbl_users` int(10) NOT NULL,
  `num_tbl_priorites` int(10) NOT NULL,
  `tache` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date DEFAULT '0000-00-00',
  `remarque` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_taches`
--

INSERT INTO `tbl_taches` (`numero`, `num_tbl_users`, `num_tbl_priorites`, `tache`, `dateDebut`, `dateFin`, `remarque`) VALUES
(12, 2, 4, 'Tâche terminée', '2015-05-04', '2015-05-04', ''),
(18, 2, 5, 'Test Tache', '2015-05-12', '2015-05-12', ''),
(19, 2, 2, 'Finish audio version for TRU', '2020-05-11', '0000-00-00', '2 chapters done\n1 ongoing chapter 3\n12 more to go'),
(20, 2, 1, 'Pick niche for Consulting business', '2020-05-11', '0000-00-00', 'Main focus before doing anything else that does not make money'),
(21, 2, 3, 'Distribute book to libraries in city', '2020-05-11', '0000-00-00', 'Follow up with Payot to distribute book'),
(22, 2, 1, 'Define client avatar', '2020-05-11', '0000-00-00', 'Coaching business-Research on FB Groups Instagram LinkedIn'),
(25, 29, 3, 'one screwed date', '2020-05-29', '0000-00-00', ''),
(26, 29, 4, 'finito', '2020-05-29', '2020-05-29', ''),
(27, 1, 2, 'Tester affichage filtres', '2020-06-01', '0000-00-00', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_titres`
--

CREATE TABLE `tbl_titres` (
  `numero` int(10) NOT NULL,
  `titre` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_titres`
--

INSERT INTO `tbl_titres` (`numero`, `titre`) VALUES
(1, 'Légumes'),
(2, 'Fruits'),
(3, 'Groove'),
(4, 'Jazz'),
(26, 'Funk'),
(35, 'Crustacés'),
(36, 'Niches');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `numero` int(10) NOT NULL,
  `nomUtilisateur` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prenomUtilisateur` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `pseudo` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `num_tbl_niveaux` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`numero`, `nomUtilisateur`, `prenomUtilisateur`, `pseudo`, `password`, `num_tbl_niveaux`) VALUES
(1, 'Super', 'Administrateur', 'admin', '32250170a0dca92d53ec9624f336ca24', 1),
(2, 'Buso', 'Jonathan', 'busojo', 'e54747defc015e669834c058c825c1b9', 2),
(28, 'Demo', 'Portfolio', 'portfolioadm', '0df033a30642f2433b7ec710a2c5a4be', 2),
(29, 'Demo', 'Portfolio', 'portfolio', '0df033a30642f2433b7ec710a2c5a4be', 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_classeurs`
--
ALTER TABLE `tbl_classeurs`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `num_tbl_users` (`num_tbl_users`),
  ADD KEY `num_tbl_images` (`num_tbl_images`);

--
-- Indexes for table `tbl_images`
--
ALTER TABLE `tbl_images`
  ADD PRIMARY KEY (`numero`);

--
-- Indexes for table `tbl_niveaux`
--
ALTER TABLE `tbl_niveaux`
  ADD PRIMARY KEY (`numero`);

--
-- Indexes for table `tbl_priorites`
--
ALTER TABLE `tbl_priorites`
  ADD PRIMARY KEY (`numero`);

--
-- Indexes for table `tbl_references_cl`
--
ALTER TABLE `tbl_references_cl`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `num_tbl_classeurs` (`num_tbl_classeurs`),
  ADD KEY `num_tbl_titres` (`num_tbl_titres`);

--
-- Indexes for table `tbl_taches`
--
ALTER TABLE `tbl_taches`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `num_tbl_priorites` (`num_tbl_priorites`),
  ADD KEY `num_tbl_users` (`num_tbl_users`);

--
-- Indexes for table `tbl_titres`
--
ALTER TABLE `tbl_titres`
  ADD PRIMARY KEY (`numero`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`numero`),
  ADD KEY `num_tbl_niveaux` (`num_tbl_niveaux`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_classeurs`
--
ALTER TABLE `tbl_classeurs`
  MODIFY `numero` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `tbl_images`
--
ALTER TABLE `tbl_images`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `tbl_niveaux`
--
ALTER TABLE `tbl_niveaux`
  MODIFY `numero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_priorites`
--
ALTER TABLE `tbl_priorites`
  MODIFY `numero` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tbl_references_cl`
--
ALTER TABLE `tbl_references_cl`
  MODIFY `numero` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=743;

--
-- AUTO_INCREMENT for table `tbl_taches`
--
ALTER TABLE `tbl_taches`
  MODIFY `numero` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `tbl_titres`
--
ALTER TABLE `tbl_titres`
  MODIFY `numero` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `numero` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_classeurs`
--
ALTER TABLE `tbl_classeurs`
  ADD CONSTRAINT `FK_classeurs_images` FOREIGN KEY (`num_tbl_images`) REFERENCES `tbl_images` (`numero`) ON DELETE SET NULL ON UPDATE SET NULL,
  ADD CONSTRAINT `FK_classeurs_users` FOREIGN KEY (`num_tbl_users`) REFERENCES `tbl_users` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_references_cl`
--
ALTER TABLE `tbl_references_cl`
  ADD CONSTRAINT `FK_references_cl_classeurs` FOREIGN KEY (`num_tbl_classeurs`) REFERENCES `tbl_classeurs` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_references_cl_titres` FOREIGN KEY (`num_tbl_titres`) REFERENCES `tbl_titres` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_taches`
--
ALTER TABLE `tbl_taches`
  ADD CONSTRAINT `FK_taches_priorites` FOREIGN KEY (`num_tbl_priorites`) REFERENCES `tbl_priorites` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `FK_taches_users` FOREIGN KEY (`num_tbl_users`) REFERENCES `tbl_users` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD CONSTRAINT `FK_users_niveaux` FOREIGN KEY (`num_tbl_niveaux`) REFERENCES `tbl_niveaux` (`numero`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
