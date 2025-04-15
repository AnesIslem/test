-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2025 at 11:57 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `agency`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `name` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`name`, `password`) VALUES
('Admin', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `ID_C` int(11) NOT NULL,
  `NIN` varchar(12) NOT NULL,
  `Nom` text NOT NULL,
  `Prenom` text NOT NULL,
  `Date de naissance` date NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `numero de Telephone` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `client`
--

INSERT INTO `client` (`ID_C`, `NIN`, `Nom`, `Prenom`, `Date de naissance`, `adresse`, `email`, `numero de Telephone`) VALUES
(1, '123456789012', 'Bensalah', 'Omar', '1995-06-15', 'Alger, Algérie', 'omar.bensalah@gmail.com', '0550123456'),
(3, '567890123456', 'Fatima', 'Zohra', '1992-09-10', 'Constantine, Algérie', 'fatima.zohra@outlook', '0775123456'),
(4, '345678901234', 'Ali', 'Kamel', '2000-04-05', 'Annaba, Algérie', 'ali.kamel@live.com', '0543123456'),
(5, '678901234567', 'Yasmine', 'Nadia', '1997-01-30', 'Tizi Ouzou, Algérie', 'yasmine.nadia@gmail.', '0567234567'),
(6, '456789012345', 'Zerrouki', 'Mohamed', '1990-08-25', 'Blida, Algérie', 'mohamed.zerrouki@hot', '0556234875'),
(7, '234567890123', 'Belkacem', 'Sofiane', '1985-03-18', 'Sétif, Algérie', 'sofiane.belkacem@gma', '0667543892'),
(8, '789012345678', 'Kherfallah', 'Amina', '1998-11-05', 'Béjaïa, Algérie', 'amina.kherfallah@out', '0778345678'),
(9, '890123456789', 'Haddad', 'Rachid', '1993-06-20', 'Tlemcen, Algérie', 'rachid.haddad@yahoo.', '0549876231'),
(10, '901234567890', 'Benali', 'Nour', '2001-02-14', 'Ghardaïa, Algérie', 'nour.benali@gmail.co', '0562147893'),
(11, '123098456789', 'Mokadem', 'Fayçal', '1995-09-12', 'Laghouat, Algérie', 'faycal.mokadem@live.', '0558741236'),
(12, '567123890456', 'Rahmani', 'Hana', '1999-07-30', 'Batna, Algérie', 'hana.rahmani@gmail.c', '0775423981'),
(13, '678234901567', 'Bouchenak', 'Khaled', '1987-12-01', 'Mostaganem, Algérie', 'khaled.bouchenak@hot.com', '0665124789');

-- --------------------------------------------------------

--
-- Table structure for table `proprietes`
--

CREATE TABLE `proprietes` (
  `pid` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `type_propriete` enum('maison','appartement','villa','lot','terrain') NOT NULL,
  `transaction_type` enum('vente','location') NOT NULL,
  `prix` decimal(12,2) NOT NULL,
  `surface` int(11) NOT NULL COMMENT 'en m²',
  `wilaya_id` int(11) NOT NULL COMMENT 'Référence à la table wilayas',
  `commune` varchar(50) DEFAULT NULL,
  `adresse` text DEFAULT NULL,
  `chambres` tinyint(4) DEFAULT NULL,
  `salles_de_bain` tinyint(4) DEFAULT NULL,
  `etage` tinyint(4) DEFAULT NULL COMMENT 'Pour appartements',
  `nombre_etages` tinyint(4) DEFAULT NULL COMMENT 'Pour villas',
  `has_garage` tinyint(1) DEFAULT 0,
  `has_jardin` tinyint(1) DEFAULT 0,
  `annee_construction` year(4) DEFAULT NULL,
  `date_ajout` datetime DEFAULT current_timestamp(),
  `date_modification` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `proprietaire_id` int(11) DEFAULT NULL COMMENT 'Référence à la table utilisateurs',
  `statut` enum('disponible','vendu','loué') DEFAULT 'disponible',
  `caracteristiques` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Type-specific attributes in JSON format' CHECK (json_valid(`caracteristiques`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `proprietes`
--

INSERT INTO `proprietes` (`pid`, `titre`, `description`, `type_propriete`, `transaction_type`, `prix`, `surface`, `wilaya_id`, `commune`, `adresse`, `chambres`, `salles_de_bain`, `etage`, `nombre_etages`, `has_garage`, `has_jardin`, `annee_construction`, `date_ajout`, `date_modification`, `proprietaire_id`, `statut`, `caracteristiques`) VALUES
(2, 'Appartement F3 à Sidi Maarouf', 'Appartement lumineux F3 au 5ème étage avec ascenseur. Proche de l\'université et des commodités.', 'appartement', 'location', 60000.00, 90, 31, 'Oran', 'Rue Ahmed Zabana, Sidi Maarouf', 2, 1, 5, NULL, 1, 0, '2010', '2025-04-12 23:26:29', NULL, NULL, 'disponible', NULL),
(3, 'Terrain constructible à Constantine', 'Terrain viabilisé de 500m² en zone urbaine, idéal pour construction villa.', 'lot', 'vente', 80000000.00, 500, 25, 'Constantine', 'Zone El Khroub', NULL, NULL, NULL, NULL, 0, 1, NULL, '2025-04-12 23:26:29', NULL, NULL, 'disponible', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `propriete_photos`
--

CREATE TABLE `propriete_photos` (
  `pid` int(11) NOT NULL,
  `propriete_id` int(11) NOT NULL,
  `url_photo` varchar(255) NOT NULL,
  `est_principale` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `propriete_photos`
--

INSERT INTO `propriete_photos` (`pid`, `propriete_id`, `url_photo`, `est_principale`) VALUES
(1, 3, 'new york.jpg', 0),
(2, 2, 'city.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `wilayas`
--

CREATE TABLE `wilayas` (
  `wid` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `code` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wilayas`
--

INSERT INTO `wilayas` (`wid`, `nom`, `code`) VALUES
(1, 'Adrar', '01'),
(2, 'Chlef', '02'),
(3, 'Laghouat', '03'),
(4, 'Oum El Bouaghi', '04'),
(5, 'Batna', '05'),
(6, 'Béjaïa', '06'),
(7, 'Biskra', '07'),
(8, 'Béchar', '08'),
(9, 'Blida', '09'),
(10, 'Bouira', '10'),
(11, 'Tamanrasset', '11'),
(12, 'Tébessa', '12'),
(13, 'Tlemcen', '13'),
(14, 'Tiaret', '14'),
(15, 'Tizi Ouzou', '15'),
(16, 'Alger', '16'),
(17, 'Djelfa', '17'),
(18, 'Jijel', '18'),
(19, 'Sétif', '19'),
(20, 'Saïda', '20'),
(21, 'Skikda', '21'),
(22, 'Sidi Bel Abbès', '22'),
(23, 'Annaba', '23'),
(24, 'Guelma', '24'),
(25, 'Constantine', '25'),
(26, 'Médéa', '26'),
(27, 'Mostaganem', '27'),
(28, 'M\'Sila', '28'),
(29, 'Mascara', '29'),
(30, 'Ouargla', '30'),
(31, 'Oran', '31'),
(32, 'El Bayadh', '32'),
(33, 'Illizi', '33'),
(34, 'Bordj Bou Arreridj', '34'),
(35, 'Boumerdès', '35'),
(36, 'El Tarf', '36'),
(37, 'Tindouf', '37'),
(38, 'Tissemsilt', '38'),
(39, 'El Oued', '39'),
(40, 'Khenchela', '40'),
(41, 'Souk Ahras', '41'),
(42, 'Tipaza', '42'),
(43, 'Mila', '43'),
(44, 'Aïn Defla', '44'),
(45, 'Naâma', '45'),
(46, 'Aïn Témouchent', '46'),
(47, 'Ghardaïa', '47'),
(48, 'Relizane', '48'),
(49, 'Timimoun', '49'),
(50, 'Bordj Badji Mokhtar', '50'),
(51, 'Ouled Djellal', '51'),
(52, 'Béni Abbès', '52'),
(53, 'In Salah', '53'),
(54, 'In Guezzam', '54'),
(55, 'Touggourt', '55'),
(56, 'Djanet', '56'),
(57, 'El M\'Ghair', '57'),
(58, 'El Menia', '58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`ID_C`,`NIN`) USING BTREE;

--
-- Indexes for table `proprietes`
--
ALTER TABLE `proprietes`
  ADD PRIMARY KEY (`pid`);

--
-- Indexes for table `propriete_photos`
--
ALTER TABLE `propriete_photos`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `propriete_id` (`propriete_id`);

--
-- Indexes for table `wilayas`
--
ALTER TABLE `wilayas`
  ADD PRIMARY KEY (`wid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `ID_C` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `proprietes`
--
ALTER TABLE `proprietes`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `propriete_photos`
--
ALTER TABLE `propriete_photos`
  MODIFY `pid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `propriete_photos`
--
ALTER TABLE `propriete_photos`
  ADD CONSTRAINT `propriete_photos_ibfk_1` FOREIGN KEY (`propriete_id`) REFERENCES `proprietes` (`pid`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
