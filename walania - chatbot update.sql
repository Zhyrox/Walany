-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 11, 2026 at 03:34 AM
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
  `reference_id` varchar(100) NOT NULL,
  `event_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `time_checked_in` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_attendance`
--

INSERT INTO `walania_attendance` (`id`, `reference_id`, `event_id`, `first_name`, `last_name`, `time_checked_in`) VALUES
(22, 'REF-10001', 1, 'Juan', 'dela Cruz', '2026-07-08 00:14:22'),
(23, 'REF-10003', 1, 'Crisostomo', 'Ibarra', '2026-07-08 00:18:05'),
(24, 'REF-10009', 1, 'Angelo', 'Santos', '2026-07-08 00:31:44'),
(25, 'REF-10016', 1, 'Althea Mae', 'Dizon', '2026-07-08 00:45:12'),
(26, 'REF-10021', 1, 'Emilio', 'Aguinaldo', '2026-07-08 00:12:11'),
(27, 'REF-10024', 1, 'Marcelo', 'del Pilar', '2026-07-08 00:55:30'),
(28, 'REF-10034', 1, 'Patrick Daniel', 'Pascual', '2026-07-08 01:01:15'),
(29, 'REF-10044', 1, 'Gregorio', 'del Pilar', '2026-07-08 00:22:41'),
(30, 'REF-10053', 1, 'Patricia Mae', 'Fernandez', '2026-07-08 00:59:04'),
(31, 'REF-10055', 1, 'Althea Louise', 'Bunag', '2026-07-08 00:05:52'),
(32, 'REF-10002', 2, 'Maria', 'Clara', '2026-07-08 05:01:10'),
(33, 'REF-10004', 2, 'Elias', 'Salome', '2026-07-08 04:55:18'),
(34, 'REF-10007', 2, 'Jose', 'Rizal', '2026-07-08 05:15:33'),
(35, 'REF-10012', 2, 'Princess Mae', 'Soriano', '2026-07-08 05:22:04'),
(36, 'REF-10022', 2, 'Apolinario', 'Mabini', '2026-07-08 04:48:59'),
(37, 'REF-10026', 2, 'Justin Miguel', 'Tolentino', '2026-07-08 05:44:12'),
(38, 'REF-10031', 2, 'Erika Mae', 'Francisco', '2026-07-08 05:02:55'),
(39, 'REF-10035', 2, 'Alyssa Marie', 'Bunag', '2026-07-08 04:50:22'),
(40, 'REF-10042', 2, 'Antonio', 'Luna', '2026-07-08 05:11:47'),
(41, 'REF-10046', 2, 'Miguel Antonio', 'Santos', '2026-07-08 05:30:19'),
(42, 'REF-10051', 2, 'Mary Rose', 'Soriano', '2026-07-08 05:18:40'),
(43, 'REF-10001', 11, 'Juan', 'dela Cruz', '2026-07-08 01:05:14'),
(44, 'REF-10005', 11, 'Andres', 'Bonifacio', '2026-07-08 01:15:22'),
(45, 'REF-10013', 11, 'John Paul', 'Bautista', '2026-07-08 02:02:11'),
(46, 'REF-10022', 11, 'Apolinario', 'Mabini', '2026-07-08 01:11:03'),
(47, 'REF-10029', 11, 'Chloe Nicole', 'Salvador', '2026-07-08 01:34:55'),
(48, 'REF-10035', 11, 'Alyssa Marie', 'Bunag', '2026-07-08 01:21:40'),
(49, 'REF-10041', 11, 'Melchora', 'Aquino', '2026-07-08 00:58:12'),
(50, 'REF-10048', 11, 'John Michael', 'Reyes', '2026-07-08 01:47:31'),
(51, 'REF-10059', 11, 'Janine Rose', 'Flores', '2026-07-08 01:50:00'),
(52, 'REF-10002', 12, 'Maria', 'Clara', '2026-07-08 06:02:15'),
(53, 'REF-10008', 12, 'Melchora', 'Aquino', '2026-07-08 05:55:40'),
(54, 'REF-10015', 12, 'Christian', 'Dimaculangan', '2026-07-08 06:18:22'),
(55, 'REF-10021', 12, 'Emilio', 'Aguinaldo', '2026-07-08 06:00:11'),
(56, 'REF-10027', 12, 'Ma. Theresa', 'De Leon', '2026-07-08 06:35:10'),
(57, 'REF-10037', 12, 'Stephanie', 'Miranda', '2026-07-08 06:12:49'),
(58, 'REF-10043', 12, 'Diego', 'Silang', '2026-07-08 06:22:01'),
(59, 'REF-10047', 12, 'Maria Angelica', 'Cruz', '2026-07-08 06:05:33'),
(60, 'REF-10054', 12, 'Joshua Daniel', 'Gabriel', '2026-07-08 06:41:18'),
(61, 'REF-10001', 15, 'Juan', 'dela Cruz', '2026-07-08 02:15:33'),
(62, 'REF-10011', 15, 'Mark Lester', 'Mendoza', '2026-07-08 01:55:12'),
(63, 'REF-10017', 15, 'Joshua', 'Villanueva', '2026-07-08 02:22:04'),
(64, 'REF-10025', 15, 'Juan', 'Luna', '2026-07-08 02:01:45'),
(65, 'REF-10031', 15, 'Erika Mae', 'Francisco', '2026-07-08 02:44:19'),
(66, 'REF-10039', 15, 'Kimberly Rose', 'Flores', '2026-07-08 02:12:50'),
(67, 'REF-10044', 15, 'Gregorio', 'del Pilar', '2026-07-08 01:48:11'),
(68, 'REF-10051', 15, 'Mary Rose', 'Soriano', '2026-07-08 02:30:25'),
(69, 'REF-10057', 15, 'Alyssa Nicole', 'Miranda', '2026-07-08 02:05:14'),
(70, 'REF-10004', 17, 'Elias', 'Salome', '2026-07-08 07:45:10'),
(71, 'REF-10009', 17, 'Angelo', 'Santos', '2026-07-08 07:10:22'),
(72, 'REF-10018', 17, 'Mary Grace', 'Castro', '2026-07-08 07:32:41'),
(73, 'REF-10021', 17, 'Emilio', 'Aguinaldo', '2026-07-08 07:02:14'),
(74, 'REF-10028', 17, 'Jerome', 'Macaraeg', '2026-07-08 08:01:05'),
(75, 'REF-10035', 17, 'Alyssa Marie', 'Bunag', '2026-07-08 07:15:55'),
(76, 'REF-10047', 17, 'Maria Angelica', 'Cruz', '2026-07-08 07:20:30'),
(77, 'REF-10052', 17, 'Christian James', 'Castro', '2026-07-08 07:55:12'),
(78, 'REF-10058', 17, 'Dave Anthony', 'Javier', '2026-07-08 08:11:00');

-- --------------------------------------------------------

--
-- Table structure for table `walania_chat_messages`
--

CREATE TABLE `walania_chat_messages` (
  `id` int(11) NOT NULL,
  `session_id` int(11) NOT NULL,
  `sender` enum('user','bot','agent') NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `walania_chat_sessions`
--

CREATE TABLE `walania_chat_sessions` (
  `id` int(11) NOT NULL,
  `session_token` varchar(255) NOT NULL,
  `status` enum('bot','human') DEFAULT 'bot',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_chat_sessions`
--

INSERT INTO `walania_chat_sessions` (`id`, `session_token`, `status`, `created_at`) VALUES
(1, '3ed8b86367556a51fa12bb03711cc6d8', 'bot', '2026-07-11 00:32:42');

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
  `is_adult_only` tinyint(1) DEFAULT 0,
  `thumbnail` varchar(255) DEFAULT 'uploads/events/default-banner.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_event`
--

INSERT INTO `walania_event` (`id`, `name`, `event_date`, `location`, `description`, `is_adult_only`, `thumbnail`) VALUES
(1, 'Maglupasay sa Pasay', '2026-08-15', 'CvSU - Pasay Campus', 'Akala ko sa kanya lang ako babagsak, sa ITEC 60 din pala. Hindi na nga maka-usad, hindi pa maka-usad pa next sem TvT. Tara, maglupasay nalang sa Pasay.', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/maglupasay.jpg'),
(2, 'Batuhan ng parcel sa j&t cubao', '2026-09-05', 'J&T Cubao', 'Rider tumira ng tres, BASKEEEEETTTTT!!!!', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/parcel.jpg'),
(11, 'Drama sa 2B', '2026-03-06', 'Basta', 'Nagsisimula ng away si Bunyad', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/bunyad.jpg'),
(12, 'Finals Examination', '2026-06-03', 'CVSU - Imus Campus', 'Students will be taking on their Finals Exam as part of their Course Requirements.', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/cvsu-imus.png'),
(13, 'suntukan sa ace hardware', '2005-05-24', 'ace hardware sa sm bacoor', 'GAME NA!!! SAGOT KO NA PAMASAHE!!!', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/ace-hardware.jpg'),
(14, 'Presentation kay Sir Jeff', '2026-06-03', 'CvSU Imus Campus', 'Goodluck guys!! <3 :)', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/system-presentation.jpg'),
(15, 'Magbebenta kay Boss Toyo', '2026-05-05', 'Pinoy Pawn Stars', 'Kung \'di nila naapreciate value mo, tara benta kita kay boss toyo.', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/pawnstars.jpg'),
(16, 'Magpakabit ng DITO, doon', '2026-05-04', 'Dito lang', 'LAG NANAMANNN PLDT!!!!!!', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/dito.jpg'),
(17, 'Magkamot sa Makati', '2026-06-12', 'Cavite State University - Makati Campus', 'basta', 0, '/PHP_Project/Walany/assets/images/event_thumbnails/makati.jpg');

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
(19, 'PDRJ-0672', 13, 'this is a nice event!!', 5),
(20, 'KCIP-8393', 2, 'this is a test feedback', 4);

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
  `role` varchar(20) NOT NULL DEFAULT 'admin',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `walania_managers`
--

INSERT INTO `walania_managers` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'System', 'admin@walany.edu.ph', '$2y$10$rEL1rJNuZvd9qbUGIAjZRu7Hdz2QZNmxDXkbK.OjBean41oMzSARG', 'admin', '2026-07-05 22:25:26', '2026-07-05 22:25:26'),
(3, 'Registrar', 'System', 'registrar@walany.edu.ph', '$2y$10$uqNVxcX1VyzJIsIEecl4jOh8QmhJUjdcf/xJ/eLT7llDnFZzqNU8.', 'registrar', '2026-07-05 22:42:55', '2026-07-05 22:44:05');

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
(42, 'kristianelmerdelatorre@gmail.com', '913523', 0, 5, 1, '2026-07-08 16:00:19', NULL, '2026-07-08 15:55:19', '2026-07-08 15:55:19'),
(43, 'kristianelmer.delatorre@cvsu.edu.ph', '270853', 0, 5, 1, '2026-07-09 02:17:20', NULL, '2026-07-09 02:12:20', '2026-07-09 02:12:20'),
(44, 'yeahlow24@gmail.com', '532742', 0, 5, 1, '2026-07-09 05:17:21', NULL, '2026-07-09 05:12:21', '2026-07-09 05:12:21'),
(45, 'yeahlow24@gmail.com', '479956', 0, 5, 2, '2026-07-09 05:18:10', NULL, '2026-07-09 05:13:10', '2026-07-09 05:13:10');

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
(1070, 'REF-10001', 'Juan', 'Santos', 'dela Cruz', '1982-04-12', 'juan.delacruz@email.com', '09171234567', 1, '2026-07-08 14:11:56', 1, NULL),
(1071, 'REF-10001', 'Juan', 'Santos', 'dela Cruz', '1982-04-12', 'juan.delacruz@email.com', '09171234567', 1, '2026-07-08 14:11:56', 11, NULL),
(1072, 'REF-10001', 'Juan', 'Santos', 'dela Cruz', '1982-04-12', 'juan.delacruz@email.com', '09171234567', 1, '2026-07-08 14:11:56', 15, NULL),
(1073, 'REF-10002', 'Maria', 'Sayson', 'Clara', '1995-11-23', 'maria.clara@email.com', '09189876543', 1, '2026-07-08 14:11:56', 2, NULL),
(1074, 'REF-10002', 'Maria', 'Sayson', 'Clara', '1995-11-23', 'maria.clara@email.com', '09189876543', 1, '2026-07-08 14:11:56', 12, NULL),
(1075, 'REF-10003', 'Crisostomo', 'Magsalin', 'Ibarra', '1991-08-15', 'c.ibarra@email.com', '09225554433', 1, '2026-07-08 14:11:56', 1, NULL),
(1076, 'REF-10003', 'Crisostomo', 'Magsalin', 'Ibarra', '1991-08-15', 'c.ibarra@email.com', '09225554433', 1, '2026-07-08 14:11:56', 14, NULL),
(1077, 'REF-10004', 'Elias', 'Pineda', 'Salome', '1978-02-28', 'elias.salome@email.com', '09151112233', 1, '2026-07-08 14:11:56', 2, NULL),
(1078, 'REF-10004', 'Elias', 'Pineda', 'Salome', '1978-02-28', 'elias.salome@email.com', '09151112233', 1, '2026-07-08 14:11:56', 13, NULL),
(1079, 'REF-10004', 'Elias', 'Pineda', 'Salome', '1978-02-28', 'elias.salome@email.com', '09151112233', 1, '2026-07-08 14:11:56', 17, NULL),
(1080, 'REF-10005', 'Andres', 'Castro', 'Bonifacio', '1988-11-30', 'andres.b@email.com', '09994447788', 1, '2026-07-08 14:11:56', 11, NULL),
(1081, 'REF-10006', 'Leonor', 'Bautista', 'Rivera', '2001-05-07', 'leonor.rivera@email.com', '09278889900', 1, '2026-07-08 14:11:56', 16, NULL),
(1082, 'REF-10007', 'Jose', 'Mercado', 'Rizal', '1990-06-19', 'jose.rizal@email.com', '09163334455', 1, '2026-07-08 14:11:56', 2, NULL),
(1083, 'REF-10008', 'Melchora', 'Ramos', 'Aquino', '1972-01-06', 'tandang.sora@email.com', '09192228811', 1, '2026-07-08 14:11:56', 12, NULL),
(1084, 'REF-10009', 'Angelo', 'Delgado', 'Santos', '2004-10-14', 'angelo.santos@email.com', '09087776655', 1, '2026-07-08 14:11:56', 1, NULL),
(1085, 'REF-10009', 'Angelo', 'Delgado', 'Santos', '2004-10-14', 'angelo.santos@email.com', '09087776655', 1, '2026-07-08 14:11:56', 17, NULL),
(1086, 'REF-10010', 'Kristina', 'Manuel', 'Reyes', '1998-03-25', 'kristina.reyes@email.com', '09452223344', 1, '2026-07-08 14:11:56', 13, NULL),
(1087, 'REF-10011', 'Mark Lester', 'Soriano', 'Mendoza', '1985-07-19', 'lester.mendoza@email.com', '09334445566', 1, '2026-07-08 14:11:56', 15, NULL),
(1088, 'REF-10012', 'Princess Mae', 'Valdez', 'Soriano', '2006-12-02', 'princess.soriano@email.com', '09175551122', 1, '2026-07-08 14:11:56', 2, NULL),
(1089, 'REF-10013', 'John Paul', 'Cruz', 'Bautista', '2002-09-09', 'jp.bautista@email.com', '09283337744', 1, '2026-07-08 14:11:56', 11, NULL),
(1090, 'REF-10014', 'Rochelle', 'Aquino', 'Pascual', '1975-04-30', 'rochelle.p@email.com', '09064448899', 1, '2026-07-08 14:11:56', 14, NULL),
(1091, 'REF-10015', 'Christian', 'Gomez', 'Dimaculangan', '1993-02-14', 'c.dimaculangan@email.com', '09156663322', 1, '2026-07-08 14:11:56', 12, NULL),
(1092, 'REF-10015', 'Christian', 'Gomez', 'Dimaculangan', '1993-02-14', 'c.dimaculangan@email.com', '09156663322', 1, '2026-07-08 14:11:56', 16, NULL),
(1093, 'REF-10016', 'Althea Mae', 'Fernandez', 'Dizon', '2007-08-21', 'althea.dizon@email.com', '09981115544', 1, '2026-07-08 14:11:56', 1, NULL),
(1094, 'REF-10017', 'Joshua', 'Gutierrez', 'Villanueva', '2000-01-15', 'josh.villanueva@email.com', '09228883344', 1, '2026-07-08 14:11:56', 15, NULL),
(1095, 'REF-10018', 'Mary Grace', 'Villareal', 'Castro', '1980-06-05', 'grace.castro@email.com', '09164440011', 1, '2026-07-08 14:11:56', 17, NULL),
(1096, 'REF-10019', 'Nathaniel', 'Alvarez', 'Garcia', '2005-03-11', 'nate.garcia@email.com', '09772229988', 1, '2026-07-08 14:11:56', 13, NULL),
(1097, 'REF-10020', 'Patricia Anne', 'Roxas', 'Aquino', '1997-09-27', 'patricia.aquino@email.com', '09195556677', 1, '2026-07-08 14:11:56', 16, NULL),
(1098, 'REF-10021', 'Emilio', 'Famy', 'Aguinaldo', '1979-03-22', 'emilio.a@email.com', '09173332211', 1, '2026-07-08 14:12:28', 1, NULL),
(1099, 'REF-10021', 'Emilio', 'Famy', 'Aguinaldo', '1979-03-22', 'emilio.a@email.com', '09173332211', 1, '2026-07-08 14:12:28', 12, NULL),
(1100, 'REF-10021', 'Emilio', 'Famy', 'Aguinaldo', '1979-03-22', 'emilio.a@email.com', '09173332211', 1, '2026-07-08 14:12:28', 17, NULL),
(1101, 'REF-10022', 'Apolinario', 'Maranan', 'Mabini', '1984-07-23', 'a.mabini@email.com', '09187776655', 1, '2026-07-08 14:12:28', 2, NULL),
(1102, 'REF-10022', 'Apolinario', 'Maranan', 'Mabini', '1984-07-23', 'a.mabini@email.com', '09187776655', 1, '2026-07-08 14:12:28', 11, NULL),
(1103, 'REF-10023', 'Gabriela', 'Cariño', 'Silang', '1992-03-19', 'gabriela.silang@email.com', '09224443322', 1, '2026-07-08 14:12:28', 13, NULL),
(1104, 'REF-10024', 'Marcelo', 'Hilario', 'del Pilar', '1976-08-30', 'plaridel@email.com', '09159998877', 1, '2026-07-08 14:12:28', 1, NULL),
(1105, 'REF-10024', 'Marcelo', 'Hilario', 'del Pilar', '1976-08-30', 'plaridel@email.com', '09159998877', 1, '2026-07-08 14:12:28', 14, NULL),
(1106, 'REF-10025', 'Juan', 'Novicio', 'Luna', '1989-10-23', 'juan.luna@email.com', '09995551122', 1, '2026-07-08 14:12:28', 15, NULL),
(1107, 'REF-10026', 'Justin Miguel', 'Panganiban', 'Tolentino', '2005-04-14', 'jm.tolentino@email.com', '09271114477', 1, '2026-07-08 14:12:28', 2, NULL),
(1108, 'REF-10027', 'Ma. Theresa', 'Santiago', 'De Leon', '1996-01-28', 'theresa.deleon@email.com', '09168883344', 1, '2026-07-08 14:12:28', 12, NULL),
(1109, 'REF-10027', 'Ma. Theresa', 'Santiago', 'De Leon', '1996-01-28', 'theresa.deleon@email.com', '09168883344', 1, '2026-07-08 14:12:28', 16, NULL),
(1110, 'REF-10028', 'Jerome', 'Villanueva', 'Macaraeg', '2002-11-09', 'jerome.macaraeg@email.com', '09193335522', 1, '2026-07-08 14:12:28', 17, NULL),
(1111, 'REF-10029', 'Chloe Nicole', 'Mercado', 'Salvador', '2007-06-18', 'chloe.salvador@email.com', '09084441155', 1, '2026-07-08 14:12:28', 11, NULL),
(1112, 'REF-10030', 'Aldrin John', 'Domingo', 'Corpuz', '1999-09-05', 'aj.corpuz@email.com', '09456662288', 1, '2026-07-08 14:12:28', 13, NULL),
(1113, 'REF-10031', 'Erika Mae', 'Soriano', 'Francisco', '2004-02-21', 'erika.francisco@email.com', '09335559900', 1, '2026-07-08 14:12:28', 2, NULL),
(1114, 'REF-10031', 'Erika Mae', 'Soriano', 'Francisco', '2004-02-21', 'erika.francisco@email.com', '09335559900', 1, '2026-07-08 14:12:28', 15, NULL),
(1115, 'REF-10032', 'Gian Carlo', 'Alvarez', 'Mendoza', '1994-07-11', 'gian.mendoza@email.com', '09172228833', 1, '2026-07-08 14:12:28', 14, NULL),
(1116, 'REF-10033', 'Mary Joy', 'Legaspi', 'Evangelista', '1981-12-15', 'joy.evangelista@email.com', '09287771144', 1, '2026-07-08 14:12:28', 16, NULL),
(1117, 'REF-10034', 'Patrick Daniel', 'Reyes', 'Pascual', '2001-05-30', 'patrick.pascual@email.com', '09062227744', 1, '2026-07-08 14:12:28', 1, NULL),
(1118, 'REF-10035', 'Alyssa Marie', 'Cruz', 'Bunag', '2006-10-02', 'alyssa.bunag@email.com', '09158884411', 1, '2026-07-08 14:12:28', 2, NULL),
(1119, 'REF-10035', 'Alyssa Marie', 'Cruz', 'Bunag', '2006-10-02', 'alyssa.bunag@email.com', '09158884411', 1, '2026-07-08 14:12:28', 11, NULL),
(1120, 'REF-10035', 'Alyssa Marie', 'Cruz', 'Bunag', '2006-10-02', 'alyssa.bunag@email.com', '09158884411', 1, '2026-07-08 14:12:28', 17, NULL),
(1121, 'REF-10036', 'Kenneth Kyle', 'Santos', 'Gonzales', '1997-08-14', 'kenneth.gonzales@email.com', '09986662233', 1, '2026-07-08 14:12:28', 13, NULL),
(1122, 'REF-10037', 'Stephanie', 'Castro', 'Miranda', '1987-04-03', 'steph.miranda@email.com', '09221115566', 1, '2026-07-08 14:12:28', 12, NULL),
(1123, 'REF-10038', 'Dave Christian', 'Bautista', 'Javier', '2003-01-25', 'dave.javier@email.com', '09165559922', 1, '2026-07-08 14:12:28', 14, NULL),
(1124, 'REF-10039', 'Kimberly Rose', 'Aquino', 'Flores', '1973-11-12', 'kim.flores@email.com', '09774441100', 1, '2026-07-08 14:12:28', 15, NULL),
(1125, 'REF-10040', 'Janzen Paul', 'Gomez', 'Valenzuela', '2000-06-20', 'janzen.v@email.com', '09198884433', 1, '2026-07-08 14:12:28', 16, NULL),
(1126, 'REF-10041', 'Melchora', 'Ramos', 'Aquino', '1974-01-06', 'm.aquino@email.com', '09172225588', 1, '2026-07-08 14:13:21', 1, NULL),
(1127, 'REF-10041', 'Melchora', 'Ramos', 'Aquino', '1974-01-06', 'm.aquino@email.com', '09172225588', 1, '2026-07-08 14:13:21', 11, NULL),
(1128, 'REF-10041', 'Melchora', 'Ramos', 'Aquino', '1974-01-06', 'm.aquino@email.com', '09172225588', 1, '2026-07-08 14:13:21', 16, NULL),
(1129, 'REF-10042', 'Antonio', 'Novicio', 'Luna', '1983-10-29', 'general.luna@email.com', '09185554433', 1, '2026-07-08 14:13:21', 2, NULL),
(1130, 'REF-10042', 'Antonio', 'Novicio', 'Luna', '1983-10-29', 'general.luna@email.com', '09185554433', 1, '2026-07-08 14:13:21', 14, NULL),
(1131, 'REF-10043', 'Diego', 'Andaya', 'Silang', '1990-12-16', 'diego.silang@email.com', '09229990011', 1, '2026-07-08 14:13:21', 12, NULL),
(1132, 'REF-10044', 'Gregorio', 'Sempio', 'del Pilar', '1975-11-14', 'goyo.pilar@email.com', '09154447788', 1, '2026-07-08 14:13:21', 1, NULL),
(1133, 'REF-10044', 'Gregorio', 'Sempio', 'del Pilar', '1975-11-14', 'goyo.pilar@email.com', '09154447788', 1, '2026-07-08 14:13:21', 15, NULL),
(1134, 'REF-10045', 'Emilio', 'Dizon', 'Jacinto', '1986-12-15', 'pingkian@email.com', '09998883322', 1, '2026-07-08 14:13:21', 13, NULL),
(1135, 'REF-10046', 'Miguel Antonio', 'Roxas', 'Santos', '2004-05-20', 'miggy.santos@email.com', '09275556633', 1, '2026-07-08 14:13:21', 2, NULL),
(1136, 'REF-10047', 'Maria Angelica', 'Pascual', 'Cruz', '1998-02-14', 'ma.angelica@email.com', '09164441122', 1, '2026-07-08 14:13:21', 12, NULL),
(1137, 'REF-10047', 'Maria Angelica', 'Pascual', 'Cruz', '1998-02-14', 'ma.angelica@email.com', '09164441122', 1, '2026-07-08 14:13:21', 17, NULL),
(1138, 'REF-10048', 'John Michael', 'Villanueva', 'Reyes', '2001-08-09', 'jm.reyes@email.com', '09192224455', 1, '2026-07-08 14:13:21', 11, NULL),
(1139, 'REF-10049', 'Nicole Beatrice', 'Salvador', 'Diaz', '2008-03-22', 'nicole.diaz@email.com', '09081119966', 1, '2026-07-08 14:13:21', 13, NULL),
(1140, 'REF-10050', 'Paolo Gabriel', 'Domingo', 'Mendoza', '1995-10-04', 'paolo.mendoza@email.com', '09459993311', 1, '2026-07-08 14:13:21', 14, NULL),
(1141, 'REF-10051', 'Mary Rose', 'Francisco', 'Soriano', '2003-04-11', 'maryrose.s@email.com', '09334440022', 1, '2026-07-08 14:13:21', 2, NULL),
(1142, 'REF-10051', 'Mary Rose', 'Francisco', 'Soriano', '2003-04-11', 'maryrose.s@email.com', '09334440022', 1, '2026-07-08 14:13:21', 15, NULL),
(1143, 'REF-10052', 'Christian James', 'Alvarez', 'Castro', '1992-06-30', 'cj.castro@email.com', '09178881144', 1, '2026-07-08 14:13:21', 17, NULL),
(1144, 'REF-10053', 'Patricia Mae', 'Legaspi', 'Fernandez', '1982-11-25', 'patricia.f@email.com', '09283339900', 1, '2026-07-08 14:13:21', 1, NULL),
(1145, 'REF-10054', 'Joshua Daniel', 'Tolentino', 'Gabriel', '2000-09-17', 'josh.gabriel@email.com', '09065552277', 1, '2026-07-08 14:13:21', 12, NULL),
(1146, 'REF-10055', 'Althea Louise', 'Gomez', 'Bunag', '2007-01-14', 'althea.bunag@email.com', '09152226699', 1, '2026-07-08 14:13:21', 1, NULL),
(1147, 'REF-10055', 'Althea Louise', 'Gomez', 'Bunag', '2007-01-14', 'althea.bunag@email.com', '09152226699', 1, '2026-07-08 14:13:21', 13, NULL),
(1148, 'REF-10055', 'Althea Louise', 'Gomez', 'Bunag', '2007-01-14', 'althea.bunag@email.com', '09152226699', 1, '2026-07-08 14:13:21', 16, NULL),
(1149, 'REF-10056', 'Renz Christopher', 'Santiago', 'Gonzales', '1996-07-03', 'renz.gonzales@email.com', '09984441155', 1, '2026-07-08 14:13:21', 14, NULL),
(1150, 'REF-10057', 'Alyssa Nicole', 'Bautista', 'Miranda', '1989-05-19', 'alyssa.m@email.com', '09227773322', 1, '2026-07-08 14:13:21', 15, NULL),
(1151, 'REF-10058', 'Dave Anthony', 'Aquino', 'Javier', '2005-02-28', 'dave.javier@email.com', '09161118844', 1, '2026-07-08 14:13:21', 17, NULL),
(1152, 'REF-10059', 'Janine Rose', 'De Leon', 'Flores', '1971-10-14', 'janine.flores@email.com', '09773335522', 1, '2026-07-08 14:13:21', 11, NULL),
(1153, 'REF-10060', 'John Kenneth', 'Corpuz', 'Valenzuela', '1999-12-02', 'kenneth.v@email.com', '09195550011', 1, '2026-07-08 14:13:21', 16, NULL),
(1156, 'LFND-2535', 'Kristian Elmer', NULL, 'Dela Torre', '2005-05-24', 'yeahlow24@gmail.com', '09068005260', 0, '2026-07-09 05:12:21', 1, NULL),
(1157, 'YSHH-0342', 'Kristian Elmer', NULL, 'Dela Torre', '2005-05-24', 'yeahlow24@gmail.com', '09068005260', 0, '2026-07-09 05:13:10', 1, NULL);

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
  ADD KEY `fk_attendance_event` (`event_id`),
  ADD KEY `idx_attendance_reference` (`reference_id`);

--
-- Indexes for table `walania_chat_messages`
--
ALTER TABLE `walania_chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `session_id` (`session_id`);

--
-- Indexes for table `walania_chat_sessions`
--
ALTER TABLE `walania_chat_sessions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `session_token` (`session_token`);

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
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_registrants_reference_id` (`reference_id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `walania_chat_messages`
--
ALTER TABLE `walania_chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `walania_chat_sessions`
--
ALTER TABLE `walania_chat_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `walania_event`
--
ALTER TABLE `walania_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `walania_event_feedback`
--
ALTER TABLE `walania_event_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `walania_managers`
--
ALTER TABLE `walania_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `walania_otp_logs`
--
ALTER TABLE `walania_otp_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1158;

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
  ADD CONSTRAINT `fk_attendance_reference_rel` FOREIGN KEY (`reference_id`) REFERENCES `walania_registrant` (`reference_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `walania_chat_messages`
--
ALTER TABLE `walania_chat_messages`
  ADD CONSTRAINT `walania_chat_messages_ibfk_1` FOREIGN KEY (`session_id`) REFERENCES `walania_chat_sessions` (`id`) ON DELETE CASCADE;

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
