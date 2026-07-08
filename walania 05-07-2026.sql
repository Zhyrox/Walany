-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 04, 2026 at 09:34 PM
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

-- --------------------------------------------------------

--
-- Table structure for table `walania_event`
--

CREATE TABLE `walania_event` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `event_date` date NOT NULL,
  `location` varchar(1000) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `is_adult_only` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_event`
--

INSERT INTO `walania_event` (`id`, `name`, `event_date`, `location`, `description`, `is_adult_only`) VALUES
(1, 'System Integration Seminar', '2026-08-15', 'Campus Auditorium', 'Mastering multi-tier application architectures and secure pipeline integrations.', 0),
(2, 'Web Engineering Workshop', '2026-09-05', 'IT Lab 3', 'Hands-on development exercises scaling native PHP database engines.', 0),
(11, 'Drama sa 2B', '2026-03-06', 'Basta', 'Nagsisimula ng away si Bunyad', 0),
(12, 'Finals Examination', '2026-06-03', 'CVSU - Imus Campus', 'Students will be taking on their Finals Exam as part of their Course Requirements.', 0),
(13, 'suntukan sa ace hardware', '2005-05-24', 'ace hardware sa sm bacoor', 'suntukan sa ace hardware', 0),
(14, 'Magpresent kay Sir Jeff', '2026-05-05', 'CvSU Imus Campus', 'Description', 0),
(15, 'Magbebenta kay Boss Toyo', '2026-05-05', 'Pinoy Pawn Stars', 'Basta', 0),
(16, 'Propak 2027', '2026-05-04', 'United States of the Philippines', 'Come and get some', 0),
(17, 'Bagsakan kay Sir Jeff.', '2026-06-12', 'Cavite State University - Imus Campus', 'Nagbabagsakan dito', 0);

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
-- Table structure for table `walania_otp_logs`
--

CREATE TABLE `walania_otp_logs` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `attempts` int(11) DEFAULT 0,
  `max_attempts_limit` int(11) DEFAULT 5,
  `resend_count_hourly` int(11) DEFAULT 0,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `locked_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_otp_logs`
--

INSERT INTO `walania_otp_logs` (`id`, `email`, `otp_code`, `attempts`, `max_attempts_limit`, `resend_count_hourly`, `expires_at`, `locked_until`, `created_at`, `updated_at`) VALUES
(29, 'yeahlow24@gmail.com', '785815', 0, 5, 1, '2026-07-04 18:43:50', NULL, '2026-07-04 18:38:50', '2026-07-04 18:38:50');

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
  `birthdate` date NOT NULL DEFAULT '2000-01-01',
  `email` varchar(160) NOT NULL,
  `contact_number` varchar(40) NOT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `registered_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_registrant`
--

INSERT INTO `walania_registrant` (`id`, `reference_id`, `first_name`, `middle_name`, `last_name`, `birthdate`, `email`, `contact_number`, `is_verified`, `registered_at`, `event_id`, `user_id`) VALUES
(1034, 'UWPG-8193', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-04 10:45:46', 13, NULL),
(1035, 'LHFS-2206', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-04 10:53:10', 13, NULL),
(1036, 'GIVZ-2903', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-04 10:53:55', 13, NULL),
(1037, 'ROIS-5771', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-04 10:54:11', 13, NULL),
(1038, 'QTAM-4106', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-04 10:55:36', 13, NULL),
(1039, 'JWLZ-1682', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-04 11:09:18', 13, NULL),
(1040, 'QILU-7350', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-04 11:21:39', 13, NULL),
(1041, 'FHQC-8105', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-04 11:26:53', 13, NULL),
(1042, 'QEKV-1437', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-04 11:30:55', 13, NULL),
(1043, 'VTMI-9993', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-04 11:37:35', 13, NULL),
(1044, 'MJQZ-6586', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 18:01:29', 13, NULL),
(1045, 'KASL-3911', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 18:02:36', 13, NULL),
(1046, 'SLGZ-5944', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 18:03:04', 13, NULL),
(1047, 'GTVQ-0595', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 18:03:30', 13, NULL),
(1048, 'EDQC-4115', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 18:03:44', 13, NULL),
(1049, 'PIVH-1453', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 18:04:18', 13, NULL),
(1050, 'QRWM-0919', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-04 18:09:38', 13, NULL),
(1051, 'XYWO-9470', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-04 18:15:54', 13, NULL),
(1052, 'LDTU-7725', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-04 18:18:23', 13, NULL),
(1053, 'BYHA-7487', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-04 18:19:27', 13, NULL),
(1054, 'UYLT-1914', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 18:22:37', 13, NULL),
(1055, 'BYIL-8477', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 18:30:34', 13, NULL),
(1056, 'BXFU-0584', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 1, '2026-07-04 18:35:40', 13, NULL),
(1057, 'SCKC-5864', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 1, '2026-07-04 18:36:28', 13, NULL),
(1058, 'YCPK-6688', 'Juan', 'Ramos', 'dela Cruz', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 1, '2026-07-04 18:38:50', 13, NULL);

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
-- Indexes for table `walania_otp_logs`
--
ALTER TABLE `walania_otp_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

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
-- AUTO_INCREMENT for table `walania_otp_logs`
--
ALTER TABLE `walania_otp_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1059;

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
