-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 08, 2026 at 08:45 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `food_helpline`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

DROP TABLE IF EXISTS `activity_log`;
CREATE TABLE IF NOT EXISTS `activity_log` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `activity` varchar(50) NOT NULL,
  `ref_type` enum('food_listing','assistance_request') NOT NULL,
  `ref_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `assistance_requests`
--

DROP TABLE IF EXISTS `assistance_requests`;
CREATE TABLE IF NOT EXISTS `assistance_requests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `requester_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `servings` int DEFAULT '1',
  `address` varchar(255) NOT NULL,
  `needed_by` datetime DEFAULT NULL,
  `status` enum('open','picked_up','delivered','closed') DEFAULT 'open',
  `volunteer_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `requester_id` (`requester_id`),
  KEY `volunteer_id` (`volunteer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `food_listings`
--

DROP TABLE IF EXISTS `food_listings`;
CREATE TABLE IF NOT EXISTS `food_listings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text,
  `quantity` int DEFAULT '1',
  `unit` varchar(50) DEFAULT 'items',
  `location` varchar(255) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `status` enum('available','claimed','completed','expired') DEFAULT 'available',
  `claimed_by` int DEFAULT NULL,
  `volunteer_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `claimed_by` (`claimed_by`),
  KEY `volunteer_id` (`volunteer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `food_listings`
--

INSERT INTO `food_listings` (`id`, `user_id`, `title`, `description`, `quantity`, `unit`, `location`, `expires_at`, `status`, `claimed_by`, `volunteer_id`, `created_at`) VALUES
(1, 12, 'Tasty Food', 'Some Description of the food', 1, 'items', 'XYZ', '2026-03-26 13:44:00', 'claimed', 13, NULL, '2026-03-08 08:14:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','donor','receiver','volunteer') DEFAULT NULL,
  `profile_completed` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `organization`, `password`, `role`, `profile_completed`, `status`, `created_at`) VALUES
(1, 'Admin', 'admin@foodhelpline.com', '9999999999', NULL, NULL, '$2y$10$Z9dRrZqH2sH9QZzqzv1w7eYfLkVqK9xE3H2Wn2v5dCq7uY8H1KQqK', 'admin', 0, 1, '2026-01-24 07:43:49'),
(15, 'Admin', 'admin@gmail.com', '121212', '121212', 'd', '$2y$10$Mlz0M/s/aMv3bcxqljqFCeSrVIgOWBBkt8NLezV66Z/sPnVP7Ot1O', 'admin', 1, 1, '2026-03-08 08:18:36'),
(3, 'Jahanvi', 'xyzs@gmail.com', '9999999999', NULL, NULL, '$2y$10$bbjTnAYPz9PMKjMBtZMwIO/bVFiGoCospg.oVvEzLHQ7p0YaAl8h.', 'donor', 0, 1, '2026-01-24 08:19:21'),
(4, 'Jahanvi', 'xyzsds@gmail.com', '9999999999', NULL, NULL, '$2y$10$vxXHybYv98PJ3PK153/Adujb.DKIV/X4QsB6VfVjw8cpwPoReiRAm', 'donor', 0, 1, '2026-01-27 03:52:22'),
(5, 'Smit Rami', 'smitrami@outlook.com', '08401582866', NULL, NULL, '$2y$10$93E7fkTQdlpnGScAArEJoOidhHRMC.AxIJ9W9iv.rt54Tn6iUDFZS', 'donor', 0, 1, '2026-01-29 04:25:22'),
(7, 'Smit', 'smitt@123.com', '1121212121', NULL, NULL, '$2y$10$DRpPz27cldpH1hT8ZkKukeTQmIFwqnm25iyEmtGCd8U1SgPHOwi/a', 'receiver', 0, 1, '2026-01-29 12:41:07'),
(8, 'Smit', 'smittt@123.com', '1121212121', NULL, NULL, '$2y$10$vRo1943GsSfijH9AEwc0uurS4JaGLotIAJsFMZDWePDsGXd5hnG1S', 'donor', 0, 1, '2026-02-02 04:17:16'),
(10, 'Smit', 'smit@gmail.com', '1121212121', NULL, NULL, '$2y$10$Co0jPaeYthGTW2OV27c9vO.lgeObR6SKddJTA118cOotWwe.MGdne', 'volunteer', 0, 1, '2026-02-02 04:22:36'),
(12, 'Jahanvi', 'jahanvi@gmail.com', '121212', 'HD', 'ds', '$2y$10$ZvJYMvQ3Yl2jTpXJKsPbN.8QcqwpuzCkYKho.3PCxlSiH.rvCy.MS', 'donor', 1, 1, '2026-03-08 08:13:58'),
(13, 'Riya', 'riya@gmail.com', '1212d', 'ddsad', 'dsa', '$2y$10$RiF5WccXSthoyFcqjoVhiuaXRDbk3uSM6hH1w8zeJ8Zzmj9dp06zy', 'receiver', 1, 1, '2026-03-08 08:15:17'),
(14, 'Andrew', 'andrew@gmail.com', '212112', 'sdsd', 'ds', '$2y$10$OqP9mGYLWTZJEXmulaHdveZMTJJ04CPhYQFvEp8sCU7fjMgi6hK/.', 'volunteer', 1, 1, '2026-03-08 08:16:18');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
