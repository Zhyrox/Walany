-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2026 at 01:51 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `walania`;
USE `walania`;

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
  `attendance_status` enum('N/A','present','absent','late') DEFAULT 'N/A',
  `time_checked_in` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_attendance`
--

INSERT INTO `walania_attendance` (`id`, `registrant_id`, `event_id`, `attendance_status`, `time_checked_in`) VALUES
(2, 1002, 3, 'absent', NULL);

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
(1, 'Tech Innovators Summit', '2026-06-15', 'University Convention Hall, Cavite State University - Imus Campus', 'A technology-focused seminar featuring discussions about web development, artificial intelligence, cybersecurity, and startup innovation.'),
(2, 'CodeFest 2026', '2026-07-03', 'SMX Convention Center, Pasay City', 'A programming competition and collaborative hackathon where students and developers build creative software solutions within a limited time.'),
(3, 'Green Earth Outreach', '2026-07-20', 'Imus River Park, Imus, Cavite', 'An environmental awareness and community outreach event involving tree planting, clean-up drives, and sustainability workshops.'),
(4, 'Digital Arts Expo', '2026-08-10', 'De La Salle University - Dasmariñas Auditorium', 'An exhibition showcasing digital illustrations, animation projects, multimedia productions, and creative technology presentations.'),
(5, 'Future Leaders Conference', '2026-09-05', 'Tagaytay International Convention Center', 'A leadership and personal development conference designed to inspire students through motivational talks, team-building activities, and networking sessions.');

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
(1002, 'test1', 34, 'idk@idk.com', '09112223333', 'Idk', '2026-05-30 10:41:05', 3, 2);

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
(2, 'admin', '$2y$10$tVAv5pLY4aSTnsYzaL1Ng.PuOXz61gu4f/ER.EjNEA9T3xP0dJhG6', '2026-05-25 02:02:54', 'admin');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `walania_event`
--
ALTER TABLE `walania_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `walania_event_feedback`
--
ALTER TABLE `walania_event_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003;

--
-- AUTO_INCREMENT for table `walania_user`
--
ALTER TABLE `walania_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
