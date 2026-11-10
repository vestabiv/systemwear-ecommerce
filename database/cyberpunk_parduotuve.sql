-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 07, 2026 at 01:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- Existing databases are preserved; use a new local database for this demo.
CREATE DATABASE IF NOT EXISTS cyberpunk_parduotuve
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE cyberpunk_parduotuve;


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cyberpunk_parduotuve`
--

-- --------------------------------------------------------

--
-- Table structure for table `atributai`
--

CREATE TABLE `atributai` (
  `id` int(11) NOT NULL,
  `tipas` enum('spalva','medziaga','dydis') NOT NULL,
  `pavadinimas` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `atributai`
--

INSERT INTO `atributai` (`id`, `tipas`, `pavadinimas`) VALUES
(2, 'spalva', 'Balta'),
(6, 'spalva', 'Chaki'),
(1, 'spalva', 'Juoda'),
(4, 'spalva', 'Mėlyna'),
(3, 'spalva', 'Pilka'),
(5, 'spalva', 'Ruda'),
(9, 'spalva', 'Sidabro'),
(8, 'spalva', 'Smėlio'),
(7, 'spalva', 'Žydra'),
(10, 'medziaga', 'Medvilnė'),
(12, 'medziaga', 'Oda'),
(11, 'medziaga', 'Poliesteris'),
(16, 'dydis', 'L'),
(15, 'dydis', 'M'),
(14, 'dydis', 'S'),
(17, 'dydis', 'XL'),
(13, 'dydis', 'XS');

-- --------------------------------------------------------

--
-- Table structure for table `kategorijos`
--

CREATE TABLE `kategorijos` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(100) NOT NULL,
  `aprasymas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategorijos`
--

INSERT INTO `kategorijos` (`id`, `pavadinimas`, `aprasymas`) VALUES
(1, 'Striukės', NULL),
(2, 'Marškinėliai', NULL),
(3, 'Kelnės', NULL),
(4, 'Suknelės', NULL),
(5, 'Aksesuarai', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `prekes`
--

CREATE TABLE `prekes` (
  `id` int(11) NOT NULL,
  `pavadinimas` varchar(150) NOT NULL,
  `aprasymas` text DEFAULT NULL,
  `kaina` decimal(10,2) NOT NULL,
  `kategorija_id` int(11) NOT NULL,
  `nuotrauka` varchar(255) DEFAULT NULL,
  `sukurta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prekes`
--

INSERT INTO `prekes` (`id`, `pavadinimas`, `aprasymas`, `kaina`, `kategorija_id`, `nuotrauka`, `sukurta`) VALUES
(1, 'Warrior Tech Jacket', 'Trumpa cyberpunk stiliaus striukė su futuristinėmis detalėmis', 129.99, 1, 'jackets/jacket1.jfif', '2025-12-27 19:25:43'),
(2, 'Urban Armor Jacket', 'Apsauganti nuo šalčio miesto stiliaus striukė', 139.99, 1, 'jackets/jacket2.jfif', '2025-12-27 19:25:43'),
(3, 'Cyber Windbreaker', 'Lengva striukė kasdieniam techwear stiliui', 119.99, 1, 'jackets/jacket3.jfif', '2025-12-27 19:25:43'),
(4, 'Mono Tech Jacket', 'Minimalistinė formali futuristinė striukė', 149.99, 1, 'jackets/jacket4.jfif', '2025-12-27 19:25:43'),
(5, 'Desert Jacket', 'Saharos įkvėpta striukė', 159.99, 1, 'jackets/jacket5.jfif', '2025-12-27 19:25:43'),
(6, 'Cutout Tech Top', 'Simetriška techwear palaidinė', 59.99, 2, 'shirts/shirt1.jfif', '2025-12-27 19:25:43'),
(7, 'Dark Core Top', 'Juodi futuristinio golfo stiliaus marškinėliai', 54.99, 2, 'shirts/shirt2.jfif', '2025-12-27 19:25:43'),
(8, 'Wave Shirt', 'Mėlyna crop-top stiliaus palaidinė', 39.99, 2, 'shirts/shirt3.jfif', '2025-12-27 19:25:43'),
(9, 'Neo Sleeve Top', 'Cyber topas ilgomis rankovėmis', 44.99, 2, 'shirts/shirt4.jfif', '2025-12-27 19:25:43'),
(10, 'Urban Office Shirt', 'Formalaus stiliaus palaidinė', 59.99, 2, 'shirts/shirt5.jfif', '2025-12-27 19:25:43'),
(11, 'Tech Shirt', 'Futuristiniai marškinėliai', 74.99, 2, 'shirts/shirt6.jfif', '2025-12-27 19:25:43'),
(12, 'Belt Pants', 'Mėlynos kelnės su diržais', 109.99, 3, 'pants/pants1.jfif', '2025-12-27 19:25:43'),
(13, 'Urban Tactical Pants', 'Taktinio dizaino miesto kelnės', 119.99, 3, 'pants/pants2.jfif', '2025-12-27 19:25:43'),
(14, 'Split Leg Pants', 'Asimetriškos kelnės', 99.99, 3, 'pants/pants3.jfif', '2025-12-27 19:25:43'),
(15, 'Split Tech Pants', 'Patogios semi-formalios kelnės', 129.99, 3, 'pants/pants4.jfif', '2025-12-27 19:25:43'),
(16, 'Slim Cyber Pants', 'Prigludusios kelnės', 94.99, 3, 'pants/pants5.jfif', '2025-12-27 19:25:43'),
(17, 'Split Dress', 'Chaotiška asimetriška suknelė', 89.99, 4, 'dresses/dress1.jfif', '2025-12-27 19:25:43'),
(18, 'Black Void Dress', 'Asimetriško kirpimo futuristinė juoda suknelė', 99.99, 4, 'dresses/dress2.jfif', '2025-12-27 19:25:43'),
(19, 'Geometry Dress', 'Suknelė su geometrinėmis tech detalėmis', 109.99, 4, 'dresses/dress3.jfif', '2025-12-27 19:25:43'),
(20, 'Killer Night Dress', 'Ilga ir tamsi miesto stiliaus suknelė', 119.99, 4, 'dresses/dress4.jfif', '2025-12-27 19:25:43'),
(21, 'Corpo Princess Dress', 'Moderni cyber suknelė', 129.99, 4, 'dresses/dress5.jfif', '2025-12-27 19:25:43'),
(22, 'Tech Sunglasses', 'Futuristiniai siauri akiniai', 49.99, 5, 'accessories/glasses1.jfif', '2025-12-27 19:25:43'),
(23, 'Neo Frame Glasses', 'Cyberpunk stiliaus akiniai', 44.99, 5, 'accessories/glasses2.jfif', '2025-12-27 19:25:43'),
(24, 'Utility Bag', 'Praktiška miesto tech rankinė', 79.99, 5, 'accessories/bag1.jfif', '2025-12-27 19:25:43'),
(25, 'Modular Backpack', 'Modulinė futuristinė kuprinė', 99.99, 5, 'accessories/bag2.jfif', '2025-12-27 19:25:43'),
(26, 'Compact Tech Bag', 'Maža cyber rankinė', 69.99, 5, 'accessories/bag3.jfif', '2025-12-27 19:25:43'),
(27, 'Cyber Shoulder Bag', 'Peties krepšys', 84.99, 5, 'accessories/bag4.jfif', '2025-12-27 19:25:43'),
(28, 'Nail Bracelet', 'Metalinė futuristinė apyrankė su nagų forma', 39.99, 5, 'accessories/bracelet1.jfif', '2025-12-27 19:25:43'),
(29, 'Spine Bracelet', 'Grandininė stuburo formos cyber apyrankė', 64.99, 5, 'accessories/bracelet2.jfif', '2025-12-27 19:25:43'),
(30, 'Ring Bracelet', 'Moderni žiedinė apyrankė', 34.99, 5, 'accessories/bracelet3.jfif', '2025-12-27 19:25:43');

-- --------------------------------------------------------

--
-- Table structure for table `prekes_atributai`
--

CREATE TABLE `prekes_atributai` (
  `preke_id` int(11) NOT NULL,
  `atributas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prekes_atributai`
--

INSERT INTO `prekes_atributai` (`preke_id`, `atributas_id`) VALUES
(1, 1),
(1, 3),
(1, 11),
(1, 14),
(1, 15),
(1, 16),
(2, 5),
(2, 6),
(2, 11),
(2, 15),
(2, 16),
(2, 17),
(3, 7),
(3, 11),
(3, 15),
(3, 16),
(3, 17),
(4, 3),
(4, 12),
(4, 15),
(4, 16),
(5, 8),
(5, 11),
(5, 14),
(5, 15),
(6, 1),
(6, 10),
(6, 14),
(6, 15),
(7, 1),
(7, 11),
(7, 15),
(7, 16),
(8, 4),
(8, 10),
(8, 13),
(8, 14),
(8, 15),
(9, 1),
(9, 10),
(9, 13),
(9, 14),
(9, 15),
(10, 1),
(10, 10),
(10, 11),
(10, 14),
(10, 15),
(11, 1),
(11, 10),
(11, 15),
(11, 16),
(11, 17),
(12, 4),
(12, 10),
(12, 14),
(12, 15),
(13, 1),
(13, 11),
(13, 15),
(13, 16),
(14, 1),
(14, 7),
(14, 8),
(14, 10),
(14, 12),
(14, 15),
(15, 1),
(15, 3),
(15, 11),
(15, 15),
(15, 16),
(15, 17),
(16, 4),
(16, 6),
(16, 10),
(16, 14),
(17, 1),
(17, 3),
(17, 11),
(17, 14),
(17, 15),
(18, 1),
(18, 10),
(18, 13),
(18, 14),
(18, 15),
(19, 1),
(19, 11),
(19, 15),
(20, 1),
(20, 3),
(20, 10),
(20, 14),
(20, 15),
(20, 16),
(21, 1),
(21, 10),
(21, 13),
(21, 14),
(21, 15),
(21, 16),
(21, 17),
(22, 1),
(23, 1),
(23, 9),
(24, 1),
(24, 10),
(25, 3),
(25, 10),
(25, 11),
(26, 1),
(26, 10),
(27, 1),
(27, 9),
(27, 10),
(27, 11),
(28, 9),
(29, 9),
(30, 9);

-- --------------------------------------------------------

--
-- Table structure for table `uzsakymai`
--

CREATE TABLE `uzsakymai` (
  `id` int(11) NOT NULL,
  `vartotojas_id` int(11) DEFAULT NULL,
  `vardas` varchar(100) DEFAULT NULL,
  `adresas` text NOT NULL,
  `suma` decimal(10,2) NOT NULL,
  `el_pastas` varchar(255) DEFAULT NULL,
  `busena` enum('Laukiama mokejimo','Naujas','Patvirtintas','Vykdomas','Issiustas','Atsauktas') DEFAULT 'Laukiama mokejimo',
  `sukurta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uzsakymai`
--



-- --------------------------------------------------------

--
-- Table structure for table `uzsakymo_prekes`
--

CREATE TABLE `uzsakymo_prekes` (
  `id` int(11) NOT NULL,
  `uzsakymas_id` int(11) NOT NULL,
  `preke_id` int(11) NOT NULL,
  `kiekis` int(11) NOT NULL,
  `kaina` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `uzsakymo_prekes`
--



-- --------------------------------------------------------

--
-- Table structure for table `vartotojai`
--

CREATE TABLE `vartotojai` (
  `id` int(11) NOT NULL,
  `vardas` varchar(100) NOT NULL,
  `el_pastas` varchar(150) NOT NULL,
  `slaptazodis` varchar(255) NOT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `sukurta` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vartotojai`
--



--
-- Indexes for dumped tables
--

--
-- Indexes for table `atributai`
--
ALTER TABLE `atributai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_atributas` (`tipas`,`pavadinimas`);

--
-- Indexes for table `kategorijos`
--
ALTER TABLE `kategorijos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `prekes`
--
ALTER TABLE `prekes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategorija_id` (`kategorija_id`);

--
-- Indexes for table `prekes_atributai`
--
ALTER TABLE `prekes_atributai`
  ADD PRIMARY KEY (`preke_id`,`atributas_id`),
  ADD KEY `atributas_id` (`atributas_id`);

--
-- Indexes for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `vartotojas_id` (`vartotojas_id`);

--
-- Indexes for table `uzsakymo_prekes`
--
ALTER TABLE `uzsakymo_prekes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `uzsakymas_id` (`uzsakymas_id`),
  ADD KEY `preke_id` (`preke_id`);

--
-- Indexes for table `vartotojai`
--
ALTER TABLE `vartotojai`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `el_pastas` (`el_pastas`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atributai`
--
ALTER TABLE `atributai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `kategorijos`
--
ALTER TABLE `kategorijos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `prekes`
--
ALTER TABLE `prekes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `uzsakymo_prekes`
--
ALTER TABLE `uzsakymo_prekes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `vartotojai`
--
ALTER TABLE `vartotojai`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `prekes`
--
ALTER TABLE `prekes`
  ADD CONSTRAINT `prekes_ibfk_1` FOREIGN KEY (`kategorija_id`) REFERENCES `kategorijos` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `prekes_atributai`
--
ALTER TABLE `prekes_atributai`
  ADD CONSTRAINT `prekes_atributai_ibfk_1` FOREIGN KEY (`preke_id`) REFERENCES `prekes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `prekes_atributai_ibfk_2` FOREIGN KEY (`atributas_id`) REFERENCES `atributai` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `uzsakymai`
--
ALTER TABLE `uzsakymai`
  ADD CONSTRAINT `uzsakymai_ibfk_1` FOREIGN KEY (`vartotojas_id`) REFERENCES `vartotojai` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `uzsakymo_prekes`
--
ALTER TABLE `uzsakymo_prekes`
  ADD CONSTRAINT `uzsakymo_prekes_ibfk_1` FOREIGN KEY (`uzsakymas_id`) REFERENCES `uzsakymai` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `uzsakymo_prekes_ibfk_2` FOREIGN KEY (`preke_id`) REFERENCES `prekes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
