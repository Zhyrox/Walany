-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 02, 2026 at 02:49 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `walania`
--

-- --------------------------------------------------------

--
-- Table structure for table `walania_attendance`
--

CREATE TABLE `walania_attendance` (
  `id` int(11) NOT NULL,
  `registrant_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `attendance_status` enum('present','absent','late','n/a') DEFAULT 'n/a',
  `time_checked_in` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_attendance`
--

INSERT INTO `walania_attendance` (`id`, `registrant_id`, `event_id`, `attendance_status`, `time_checked_in`) VALUES
(10, 1009, 11, 'absent', NULL),
(11, 1010, 12, 'n/a', NULL),
(12, 1011, 16, 'n/a', NULL),
(13, 1012, 16, 'n/a', NULL),
(14, 1013, 12, 'n/a', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `walania_event`
--

CREATE TABLE `walania_event` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `location` varchar(1000) NOT NULL,
  `description` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_event`
--

INSERT INTO `walania_event` (`id`, `name`, `event_date`, `location`, `description`) VALUES
(11, 'Drama sa 2B', '2026-03-06', 'Basta', 'Nagsisimula ng away si Bunyad'),
(12, 'Finals Examination', '2026-06-03', 'CVSU - Imus Campus', 'Students will be taking on their Finals Exam as part of their Course Requirements.'),
(13, 'suntukan sa ace hardware', '2005-05-24', 'ace hardware sa sm bacoor', 'suntukan sa ace hardware'),
(14, 'Magpresent kay Sir Jeff', '2026-05-05', 'CvSU Imus Campus', 'Description'),
(15, 'Magbebenta kay Boss Toyo', '2026-05-05', 'Pinoy Pawn Stars', 'Basta'),
(16, 'Propak 2027', '2026-05-04', 'United States of the Philippines', 'Come and get some'),
(17, 'Bagsakan kay Sir Jeff.', '2026-06-12', 'Cavite State University - Imus Campus', 'Nagbabagsakan dito');

-- --------------------------------------------------------

--
-- Table structure for table `walania_event_feedback`
--

CREATE TABLE `walania_event_feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `event_id` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `rating` tinyint(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_event_feedback`
--

INSERT INTO `walania_event_feedback` (`id`, `user_id`, `event_id`, `comment`, `rating`) VALUES
(12, 8, 12, 'geaa', 3),
(14, 2, 13, 'bait ni woody 1 star ka sakin', 1);

-- --------------------------------------------------------

--
-- Table structure for table `walania_registrant`
--

CREATE TABLE `walania_registrant` (
  `id` int(11) NOT NULL,
  `full_name` varchar(120) NOT NULL,
  `age` tinyint(3) UNSIGNED NOT NULL,
  `email` varchar(160) NOT NULL,
  `contact_number` varchar(40) NOT NULL,
  `preference_allergy` varchar(500) DEFAULT NULL,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_registrant`
--

INSERT INTO `walania_registrant` (`id`, `full_name`, `age`, `email`, `contact_number`, `preference_allergy`, `registered_at`, `event_id`, `user_id`) VALUES
(1009, 'tttt', 67, 'tripletsahur@echoes.com', '09128495727', '', '2026-06-03 06:51:31', 11, 5),
(1010, 'John Doe', 21, 'email@123.com', '0912 345 6789', 'qwerty', '2026-06-03 10:57:45', 12, 8),
(1011, 'John Pratts', 1, 'john@yahoo.com', '31313122312333123', '1!@#1123', '2026-06-03 11:06:17', 16, 2),
(1012, 'Kristian Elmer Dela Torre', 19, 'account2@cvsu.edu.ph', '0912 345 6789', 'shizer', '2026-06-23 05:47:53', 16, 9),
(1013, 'Kristian Elmer Dela Torre', 21, 'kennharveyfbrocoy@gmail.com', '09123456789', 'bfbfcb', '2026-06-23 06:32:12', 12, 10);

-- --------------------------------------------------------

--
-- Table structure for table `walania_user`
--

CREATE TABLE `walania_user` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_user`
--

INSERT INTO `walania_user` (`id`, `username`, `password`, `created_at`, `role`) VALUES
(1, 'hunter', '$2y$10$uvaXG7x6TF3zV1jAC5S51OKqibYPrWbtiSE8Emu6CqXJmEfagUwqm', '2026-05-24 12:44:12', 'user'),
(2, 'admin', '$2y$10$tVAv5pLY4aSTnsYzaL1Ng.PuOXz61gu4f/ER.EjNEA9T3xP0dJhG6', '2026-05-25 02:02:54', 'admin'),
(3, 'acc1', '$2y$10$5Jyldceyxp6oGcqpDfQKXeW2kBkYicagCRzOcf/TprNMwY/SWdK2S', '2026-06-01 01:11:31', 'user'),
(4, 'acc2', '$2y$10$FjqWfXng3Mo4YmTlTAsITu8evDouPgprrId4sG5vVKYu4NRmiCFDO', '2026-06-01 01:11:38', 'user'),
(5, 'tungtungtungsahur', '$2y$10$OWiKODZlUzdWvuFBt64qnewXfhNun0C7xCu0.S1yu6RAxCzOdJaLm', '2026-06-03 06:41:18', 'user'),
(6, 'tester', '$2y$10$39xaO2SbhqG4F6FhEUo5xeJu5YdPFa84czEZsCnjb2.7gnsnPnu2C', '2026-06-03 10:28:17', 'user'),
(7, 'tester1', '$2y$10$lZAAS5BrRPjlqRX2X/KAnOsXA3UjffBAmBRFm8EyGwlXXzoeEeWI2', '2026-06-03 10:31:42', 'user'),
(8, 'tester11', '$2y$10$jyuSOXY5U/1HRPMbj9yOrOq3iBmpKJVXCUv.vt48/ozbOyLAZOO2y', '2026-06-03 10:56:10', 'user'),
(9, 'tester111', '$2y$10$xtWEnZ73drk3X32EOjBj1O87IOnHBZf1b/U/dgqlAX5RrV.RbStu6', '2026-06-23 05:46:56', 'user'),
(10, 'test123', '$2y$10$S9YNJYHi0KY78MEqVHAaheADyopx.gWgrjJPQH8dqXjYkRsv6sJLm', '2026-06-23 06:30:32', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `walania_attendance`
--
ALTER TABLE `walania_attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_attendance_registrant` (`registrant_id`),
  ADD KEY `fk_attendance_event` (`event_id`);

--
-- Indexes for table `walania_event`
--
ALTER TABLE `walania_event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `walania_event_feedback`
--
ALTER TABLE `walania_event_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_feedback_user` (`user_id`),
  ADD KEY `fk_feedback_event` (`event_id`);

--
-- Indexes for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `walania_user`
--
ALTER TABLE `walania_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `walania_attendance`
--
ALTER TABLE `walania_attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `walania_event`
--
ALTER TABLE `walania_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `walania_event_feedback`
--
ALTER TABLE `walania_event_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1014;

--
-- AUTO_INCREMENT for table `walania_user`
--
ALTER TABLE `walania_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `walania_attendance`
--
ALTER TABLE `walania_attendance`
  ADD CONSTRAINT `fk_attendance_event` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attendance_registrant` FOREIGN KEY (`registrant_id`) REFERENCES `walania_registrant` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `walania_event_feedback`
--
ALTER TABLE `walania_event_feedback`
  ADD CONSTRAINT `fk_feedback_event` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_feedback_user` FOREIGN KEY (`user_id`) REFERENCES `walania_user` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  ADD CONSTRAINT `walania_registrant_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`),
  ADD CONSTRAINT `walania_registrant_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `walania_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
