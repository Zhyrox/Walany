-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 03, 2026 at 12:54 PM
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
CREATE DATABASE IF NOT EXISTS `walania` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `walania`;

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
(1, 'System Integration Seminar', '2026-08-15', 'Campus Auditorium', 'Mastering multi-tier application architectures and secure pipeline integrations.'),
(2, 'Web Engineering Workshop', '2026-09-05', 'IT Lab 3', 'Hands-on development exercises scaling native PHP database engines.'),
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
  `reference_id` varchar(9) NOT NULL,
  `event_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `rating` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_event_feedback`
--

INSERT INTO `walania_event_feedback` (`id`, `reference_id`, `event_id`, `comment`, `rating`) VALUES
(12, '8', 12, 'geaa', 3),
(14, '2', 13, 'bait ni woody 1 star ka sakin', 1),
(15, 'PMEQ-5563', 1, 'sdadsad', 5),
(16, 'TLTY-5477', 2, 'asdas', 5),
(17, 'TMIN-9583', 13, 'safjhdasfdghgddgf', 5),
(18, 'ITYQ-8230', 17, 'dsaf123;&#039;[/.&amp;^%$#@', 4),
(19, 'PDRJ-0672', 13, 'this is a nice event!!', 5);

-- --------------------------------------------------------

--
-- Table structure for table `walania_managers`
--

CREATE TABLE `walania_managers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `walania_managers`
--

INSERT INTO `walania_managers` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'System', 'admin@walany.edu.ph', '$2y$10$f.V8t.z3Nw31Bux/f91vvuiATongHa2JuD51nmNSQ693s0/mybGxq', '2026-07-03 10:11:01', '2026-07-03 10:11:01'),
(2, 'admin', 'second', 'admin2@walany.edu.ph', '$2y$10$JmIvgngr7aLEgMsiKQcM1ufHqxjE82jTMCQ6Ie4515QpG6ApG7STq', '2026-07-03 10:15:06', '2026-07-03 10:15:06');

-- --------------------------------------------------------

--
-- Table structure for table `walania_registrant`
--

CREATE TABLE `walania_registrant` (
  `id` int(11) NOT NULL,
  `reference_id` varchar(9) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
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

INSERT INTO `walania_registrant` (`id`, `reference_id`, `first_name`, `middle_name`, `last_name`, `age`, `email`, `contact_number`, `preference_allergy`, `registered_at`, `event_id`, `user_id`) VALUES
(1009, 'LEG-1009', 'tttt', NULL, 'N/A', 67, 'tripletsahur@echoes.com', '09128495727', '', '2026-06-03 06:51:31', 11, 5),
(1010, 'LEG-1010', 'John', NULL, 'Doe', 21, 'email@123.com', '0912 345 6789', 'qwerty', '2026-06-03 10:57:45', 12, 8),
(1011, 'LEG-1011', 'John', NULL, 'Pratts', 1, 'john@yahoo.com', '31313122312333123', '1!@#1123', '2026-06-03 11:06:17', 16, 2),
(1012, 'LEG-1012', 'Kristian Elmer Dela', NULL, 'Torre', 19, 'account2@cvsu.edu.ph', '0912 345 6789', 'shizer', '2026-06-23 05:47:53', 16, 9),
(1013, 'LEG-1013', 'Kristian Elmer Dela', NULL, 'Torre', 21, 'kennharveyfbrocoy@gmail.com', '09123456789', 'bfbfcb', '2026-06-23 06:32:12', 12, 10),
(1014, 'TLTY-5477', 'John', 'asd', 'Doe', 0, 'email@123.com', '09123456789', NULL, '2026-07-02 21:07:47', 2, NULL),
(1015, 'PMEQ-5563', 'Juan', 'Ramos', 'dela Cruz', 0, 'name@example.com', '09123456789', NULL, '2026-07-02 21:09:15', 1, NULL),
(1016, 'TMIN-9583', 'Juan', 'Ramos', 'dela Cruz', 0, 'name@example.com', '09123456789', NULL, '2026-07-03 07:52:43', 13, NULL),
(1017, 'AJWD-9056', 'Juan', 'Ramos', 'dela Cruz', 0, 'name@example.com', '09123456789', NULL, '2026-07-03 09:27:57', 12, NULL),
(1018, 'ITYQ-8230', 'Juan', 'Ramos', 'dela Cruz', 0, 'name@example.com', '09123456789', NULL, '2026-07-03 09:29:45', 17, NULL),
(1019, 'KGYH-6706', 'Maria', NULL, 'Santos', 0, 'maria.santos@gmail.com', '09987654321', NULL, '2026-07-03 09:30:19', 13, NULL),
(1020, 'PDRJ-0672', 'John', NULL, 'Doe', 0, 'account2@cvsu.edu.ph', '09128495727', NULL, '2026-07-03 10:53:35', 13, NULL);

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
  ADD KEY `fk_feedback_user` (`reference_id`),
  ADD KEY `fk_feedback_event` (`event_id`);

--
-- Indexes for table `walania_managers`
--
ALTER TABLE `walania_managers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `walania_managers`
--
ALTER TABLE `walania_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1021;

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
  ADD CONSTRAINT `fk_attendance_event_rel` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_attendance_registrant_rel` FOREIGN KEY (`registrant_id`) REFERENCES `walania_registrant` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `walania_event_feedback`
--
ALTER TABLE `walania_event_feedback`
  ADD CONSTRAINT `fk_feedback_event_rel` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  ADD CONSTRAINT `fk_registrant_event_rel` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `walania_registrant_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `walania_event` (`id`),
  ADD CONSTRAINT `walania_registrant_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `walania_user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
