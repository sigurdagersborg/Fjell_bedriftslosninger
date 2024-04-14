-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2024 at 10:04 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mydb`
--

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `idmessages` int(11) NOT NULL,
  `saksnummer` int(11) NOT NULL,
  `melding` varchar(455) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`idmessages`, `saksnummer`, `melding`, `created_at`) VALUES
(17, 8, 'Vi jobber med saken!', '2024-04-14 21:58:36'),
(18, 9, 'løsningen til problemet er: Bla bla bla', '2024-04-14 21:59:04');

-- --------------------------------------------------------

--
-- Table structure for table `saker`
--

CREATE TABLE `saker` (
  `saksnummer` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `problem` varchar(455) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `er_lost` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `saker`
--

INSERT INTO `saker` (`saksnummer`, `user_id`, `problem`, `created_at`, `er_lost`) VALUES
(8, 3, 'Problem 1', '2024-04-14 21:57:51', 1),
(9, 3, 'Problem 2', '2024-04-14 21:57:57', 2),
(10, 3, 'Problem 3', '2024-04-14 21:58:02', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`, `isAdmin`) VALUES
(1, 'Admin', 'admin@fjell.no', '$2y$10$2LcFqBcfyxdaPLjqlJbSYOHu4Q.1a7mQtInXLuLkECt70743hfTvu', '2024-03-22 10:20:22', 2),
(3, 'kunde', 'kunde@kunde.com', '$2y$12$ZryppQ4jQZ/gDaDnyq3dX.jDZtMNfZeA8fXTGGxx557WwW/eCm1m2', '2024-04-04 11:05:37', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`idmessages`),
  ADD UNIQUE KEY `created_at_UNIQUE` (`created_at`),
  ADD KEY `fk_messages_saker1_idx` (`saksnummer`);

--
-- Indexes for table `saker`
--
ALTER TABLE `saker`
  ADD PRIMARY KEY (`saksnummer`),
  ADD KEY `fk_saker_users_idx` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `idmessages` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `saker`
--
ALTER TABLE `saker`
  MODIFY `saksnummer` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_saker1` FOREIGN KEY (`saksnummer`) REFERENCES `saker` (`saksnummer`);

--
-- Constraints for table `saker`
--
ALTER TABLE `saker`
  ADD CONSTRAINT `fk_saker_users` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
