-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 03, 2024 at 07:00 PM
-- Server version: 10.11.8-MariaDB
-- PHP Version: 8.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `insa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tpvpGames`
--

CREATE TABLE `tpvpGames` (
  `gID` bigint(20) UNSIGNED NOT NULL,
  `started` datetime NOT NULL,
  `host` varchar(30) NOT NULL,
  `code` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tpvpPlayers`
--

CREATE TABLE `tpvpPlayers` (
  `pID` bigint(20) UNSIGNED NOT NULL,
  `game` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `wordCount` int(11) NOT NULL DEFAULT 0,
  `charCount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tpvpPlayerWords`
--

CREATE TABLE `tpvpPlayerWords` (
  `player` bigint(20) UNSIGNED DEFAULT NULL,
  `word` bigint(20) UNSIGNED DEFAULT NULL,
  `TTL` datetime NOT NULL DEFAULT (current_timestamp() + interval 1 day_minute)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tpvpWords`
--

CREATE TABLE `tpvpWords` (
  `wID` bigint(20) UNSIGNED NOT NULL,
  `game` bigint(20) UNSIGNED NOT NULL,
  `word` varchar(50) NOT NULL,
  `flag` varchar(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tpvpGames`
--
ALTER TABLE `tpvpGames`
  ADD PRIMARY KEY (`gID`),
  ADD UNIQUE KEY `gID` (`gID`);

--
-- Indexes for table `tpvpPlayers`
--
ALTER TABLE `tpvpPlayers`
  ADD PRIMARY KEY (`pID`),
  ADD UNIQUE KEY `pID` (`pID`,`game`),
  ADD KEY `game` (`game`);

--
-- Indexes for table `tpvpPlayerWords`
--
ALTER TABLE `tpvpPlayerWords`
  ADD KEY `player` (`player`),
  ADD KEY `word` (`word`);

--
-- Indexes for table `tpvpWords`
--
ALTER TABLE `tpvpWords`
  ADD PRIMARY KEY (`wID`,`game`),
  ADD UNIQUE KEY `wID` (`wID`),
  ADD KEY `game` (`game`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tpvpGames`
--
ALTER TABLE `tpvpGames`
  MODIFY `gID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tpvpPlayers`
--
ALTER TABLE `tpvpPlayers`
  MODIFY `pID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tpvpWords`
--
ALTER TABLE `tpvpWords`
  MODIFY `wID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tpvpPlayers`
--
ALTER TABLE `tpvpPlayers`
  ADD CONSTRAINT `tpvpPlayers_ibfk_1` FOREIGN KEY (`game`) REFERENCES `tpvpGames` (`gID`) ON DELETE CASCADE;

--
-- Constraints for table `tpvpPlayerWords`
--
ALTER TABLE `tpvpPlayerWords`
  ADD CONSTRAINT `tpvpPlayerWords_ibfk_1` FOREIGN KEY (`player`) REFERENCES `tpvpPlayers` (`pID`) ON DELETE CASCADE,
  ADD CONSTRAINT `tpvpPlayerWords_ibfk_2` FOREIGN KEY (`word`) REFERENCES `tpvpWords` (`wID`) ON DELETE CASCADE;

--
-- Constraints for table `tpvpWords`
--
ALTER TABLE `tpvpWords`
  ADD CONSTRAINT `tpvpWords_ibfk_1` FOREIGN KEY (`game`) REFERENCES `tpvpGames` (`gID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
