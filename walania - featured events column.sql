-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 27, 2026 at 11:07 AM
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
(22, 'REF-10001', 1, 'Juan', 'dela Cruz', '2026-07-09 00:14:22'),
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
(42, 'REF-10051', 2, 'Mary Rose', 'Soriano', '2026-07-09 05:18:40'),
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

--
-- Dumping data for table `walania_chat_messages`
--

INSERT INTO `walania_chat_messages` (`id`, `session_id`, `sender`, `message`, `timestamp`) VALUES
(97, 9, 'user', 'Tell me about the available campus events', '2026-07-19 20:04:38'),
(98, 9, 'bot', 'You can view all upcoming campus events by logging into the Walania student portal and clicking the \"Events\" tab. Alternatively, check the bulletin board in the Student Union for a list of this week\'s activities.', '2026-07-19 20:04:39'),
(99, 9, 'user', 'Tell me about the available campus events', '2026-07-19 20:06:05'),
(100, 9, 'bot', 'You can view the full schedule of upcoming campus events by logging into the Walania student portal and selecting the \"Events\" tab. For a quick look at this week\'s activities, you can also check the digital bulletin board located in the Student Union.', '2026-07-19 20:06:05'),
(101, 9, 'user', 'Tell me about the available campus events', '2026-07-19 20:07:46'),
(102, 9, 'bot', 'You can view all current campus events by visiting the \"Events\" tab on the Walania student portal. You may also check the digital bulletin boards located throughout the Student Union for daily activity updates.', '2026-07-19 20:07:47');

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
(5, '143bacc2926d0f5783f6e11cfc3d9233', 'bot', '2026-07-19 20:03:49'),
(6, 'fbbf7c7b5dc975e1d9874b8bb1c4a289', 'bot', '2026-07-19 20:03:49'),
(7, '9b74addbb0048d69f09719877ad38755', 'bot', '2026-07-19 20:03:49'),
(8, '18ff5a5073deefe921ff5e5c285ea8e3', 'bot', '2026-07-19 20:03:50'),
(9, 'b546aeb8094b0a26b2c4e0e4a525b8b4', 'bot', '2026-07-19 20:04:34'),
(10, '26a68e4d4bd00e8fd290e7c5992850a6', 'bot', '2026-07-19 20:05:40'),
(11, 'a257dd63690bb30fa8675826806a222c', 'bot', '2026-07-19 20:05:40'),
(12, '59cd20bde26586e10a8cf2068e6eae92', 'bot', '2026-07-19 20:05:42'),
(13, '0222c4bdf83c8305c9a2a2d00b6c9070', 'bot', '2026-07-26 09:18:38'),
(14, '179c5f004c5a093ffdcfb89e465e3930', 'bot', '2026-07-26 09:19:04'),
(15, '6e6b0ec86c5d5217e6c6cfc363617a67', 'bot', '2026-07-26 09:19:04'),
(16, 'dbd1a1b75aef9dcfacde00213ee63622', 'bot', '2026-07-26 09:20:47'),
(17, '1905712c2c76ba37e15d1f22bff27373', 'bot', '2026-07-26 13:36:34'),
(18, '171b8ece00fd50d65d4a29cdd14bb546', 'bot', '2026-07-26 21:21:15'),
(19, 'a55b12f2b33d795e8808b4594bff9bb6', 'bot', '2026-07-27 07:40:48');

-- --------------------------------------------------------

--
-- Table structure for table `walania_event`
--

CREATE TABLE `walania_event` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` varchar(50) NOT NULL DEFAULT 'Seminar',
  `event_date` date NOT NULL,
  `location` varchar(1000) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `description` varchar(1000) NOT NULL,
  `open_registration` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `thumbnail` varchar(255) DEFAULT 'uploads/events/default-banner.png',
  `max_capacity` int(11) DEFAULT 100
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_event`
--

INSERT INTO `walania_event` (`id`, `name`, `category`, `event_date`, `location`, `price`, `description`, `open_registration`, `is_active`, `is_featured`, `thumbnail`, `max_capacity`) VALUES
(1, 'Maglupasay sa Pasay', 'Seminar', '2026-08-15', 'CvSU - Pasay Campus', 10.00, 'Akala ko sa kanya lang ako babagsak, sa ITEC 60 din pala. Hindi na nga maka-usad, hindi pa maka-usad pa next sem TvT. Tara, maglupasay nalang sa Pasay.', 1, 1, 1, '/Walany/assets/images/event_thumbnails/maglupasay.jpg', 20),
(2, 'Batuhan ng parcel sa j&t cubao', 'Seminar', '2026-09-05', 'J&T Cubao', 0.00, 'Rider tumira ng tres, BASKEEEEETTTTT!!!!', 1, 1, 1, '/Walany/assets/images/event_thumbnails/parcel.jpg', 100),
(11, 'Drama sa 2B', 'Seminar', '2026-03-06', 'Basta', 0.00, 'Nagsisimula ng away si Bunyad', 1, 1, 1, '/Walany/assets/images/event_thumbnails/bunyad.jpg', 100),
(12, 'Finals Examination', 'Seminar', '2026-06-03', 'CVSU - Imus Campus', 0.00, 'Students will be taking on their Finals Exam as part of their Course Requirements.', 1, 1, 0, '/Walany/assets/images/event_thumbnails/cvsu-imus.png', 100),
(13, 'suntukan sa ace hardware', 'Seminar', '2005-05-24', 'ace hardware sa sm bacoor', 0.00, 'GAME NA!!! SAGOT KO NA PAMASAHE!!!', 1, 1, 0, '/Walany/assets/images/event_thumbnails/ace-hardware.jpg', 100),
(14, 'Presentation kay Sir Jeff', 'Seminar', '2026-06-03', 'CvSU Imus Campus', 0.00, 'Goodluck guys!! <3 :)', 1, 1, 0, '/Walany/assets/images/event_thumbnails/system-presentation.jpg', 100),
(15, 'Magbebenta kay Boss Toyo', 'Seminar', '2026-05-05', 'Pinoy Pawn Stars', 0.00, 'Kung \'di nila naapreciate value mo, tara benta kita kay boss toyo.', 1, 1, 0, '/Walany/assets/images/event_thumbnails/pawnstars.jpg', 100),
(16, 'Magpakabit ng DITO, doon', 'Seminar', '2026-05-04', 'Dito lang', 0.00, 'LAG NANAMANNN PLDT!!!!!!', 1, 1, 0, '/Walany/assets/images/event_thumbnails/dito.jpg', 100),
(17, 'Magkamot sa Makati', 'Seminar', '2026-06-12', 'Cavite State University - Makati Campus', 0.00, 'basta', 1, 1, 0, '/Walany/assets/images/event_thumbnails/makati.jpg', 100),
(18, 'Enrollment Period', 'Seminar', '2026-07-15', 'CvSU - Imus Campus', 0.00, 'Enrollment Period Starts', 1, 1, 1, '/Walany/assets/images/event_thumbnails/cvsu-imus.png', 10000),
(19, 'Cybersecurity Awareness & Threat Trends', 'Seminar', '2026-08-15', 'Main Campus Auditorium', 0.00, 'Learn essential skills to protect digital identities, spot phishing attempts, and handle personal data safely online.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 150),
(20, 'AI & Ethics in Modern Education', 'Seminar', '2026-08-28', 'AVR Room 201', 0.00, 'A thought-provoking discussion on how artificial intelligence is reshaping academic integrity and future career paths.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 100),
(21, 'Financial Literacy for Young Professionals', 'Seminar', '2026-09-05', 'Student Center Multi-Purpose Hall', 50.00, 'Master budgeting, smart saving, and foundational investment strategies before entering the corporate world.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 120),
(22, 'Hands-On Web Development Bootcamp', 'Workshop', '2026-08-20', 'Computer Lab 304', 100.00, 'A practical 4-hour session covering HTML, CSS, JavaScript basics, and deploying your first web page.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 40),
(23, 'UI/UX Design Essentials with Figma', 'Workshop', '2026-09-02', 'Mac Design Lab 102', 150.00, 'Design user-centered mobile applications from wireframe to interactive prototype using Figma.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 30),
(24, 'Public Speaking & Pitch Deck Mastery', 'Workshop', '2026-09-12', 'Mini Theater', 0.00, 'Overcome stage fright and learn how to present startup ideas persuasively to judges and investors.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 50),
(25, 'Inter-College Esports Showdown: Valorant', 'Tournament', '2026-08-22', 'Student Lounge - Gaming Zone', 250.00, 'Assemble your 5-man roster and battle for the title of campus champions in a double-elimination bracket.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 16),
(26, 'Campus Chess Championship 2026', 'Tournament', '2026-09-10', 'Library Quiet Zone Hall', 0.00, 'Standard Swiss-system chess tournament open to all undergraduate and graduate students.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 64),
(27, 'Speed Debate Clash', 'Tournament', '2026-09-18', 'Humanities Hall 105', 0.00, 'Rapid-fire parliamentary debate competition tackling current national and international affairs.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 32),
(28, 'Varsity Basketball Team Tryouts', 'Tryouts', '2026-08-10', 'University Gymnasium', 0.00, 'Official selection drills and scrimmage matches for the upcoming university athletic season.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 80),
(29, 'Walania Debate Society Recruitment Tryouts', 'Tryouts', '2026-08-18', 'Social Sciences Building R204', 0.00, 'Showcase your critical thinking and speech formulation skills to join the competitive debate core.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 40),
(30, 'Campus Performing Arts Chorale Auditions', 'Tryouts', '2026-08-25', 'Music Room B1', 0.00, 'Open vocal auditions for soprano, alto, tenor, and bass parts for the 2026 concert series.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 50),
(31, 'Annual Intramural Games Opening Ceremony', 'Intramurals', '2026-09-01', 'Main Sports Complex Stadium', 0.00, 'Grand parade, torch lighting, and cheering showcase signaling the start of the inter-department sports fest.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 500),
(32, 'Intramural Volleyball League Matches', 'Intramurals', '2026-09-03', 'Outdoor Court 2', 0.00, 'Elimination rounds for Men’s and Women’s division inter-college volleyball teams.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 200),
(33, 'Track & Field Athletics Meet', 'Intramurals', '2026-09-04', 'Track Oval', 0.00, '100m sprint, relay, long jump, and shotput competitions between department delegations.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 150),
(34, 'Annual Fine Arts & Photography Expo', 'Exhibitions', '2026-08-21', 'Art Gallery Hall', 0.00, 'Exhibition featuring student paintings, digital art, and photography portfolios from the College of Fine Arts.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 300),
(35, 'Engineering & Tech Innovation Showcase', 'Exhibitions', '2026-09-15', 'Engineering Plaza', 0.00, 'Interactive exhibition of senior capstone projects, robotics, and prototype hardware buildouts.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 250),
(36, 'Cultural Heritage & History Display', 'Exhibitions', '2026-09-22', 'Grand Atrium', 0.00, 'Interactive historical artifacts display, traditional attire, and regional food sampling.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 200),
(37, 'Run for Literacy: 5K Fun Run', 'Fundraisers', '2026-08-30', 'Campus Grand Oval', 150.00, 'Charity fun run aimed at raising funds for book donations and learning supplies for community daycare centers.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 300),
(38, 'Campus Acoustic Charity Night', 'Fundraisers', '2026-09-08', 'Student Amphitheater', 80.00, 'An evening of live acoustic performances by student bands to raise funds for disaster relief operations.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 180),
(39, 'Bake & Craft Sale for Animal Rescue', 'Fundraisers', '2026-09-17', 'Student Center Walkway', 0.00, 'Handmade goods, pastries, and merchandise sold to benefit local stray animal shelters.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 100),
(40, 'Freshmen General Orientation 2026', 'Orientations', '2026-08-03', 'Main University Gymnasium', 0.00, 'Welcome session for incoming first-year students to introduce campus policies, facilities, and student services.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 600),
(41, 'Library & Research Database Orientation', 'Orientations', '2026-08-08', 'Main Library Learning Commons', 0.00, 'Guided walkthrough on utilizing digital research journals, borrowing privileges, and online archives.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 80),
(42, 'Student Organization Officers Briefing', 'Orientations', '2026-08-12', 'Student Affairs Conference Room', 0.00, 'Orientation for newly elected organization leaders regarding event clearance protocols and budget approvals.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 60),
(43, 'Navigating Global Career Opportunities', 'Webinars', '2026-08-19', 'Zoom / Online Platform', 0.00, 'Online session featuring international alumni sharing advice on remote work and global internships.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 300),
(44, 'Intro to Cloud Computing & AWS Services', 'Webinars', '2026-08-27', 'Google Meet / Online', 0.00, 'Virtual workshop introducing key cloud infrastructure concepts and cloud practitioner certification paths.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 250),
(45, 'Mental Health & Stress Management in College', 'Webinars', '2026-09-14', 'MS Teams / Online', 0.00, 'An interactive online wellness session providing coping strategies for midterms and academic stress.', 1, 1, 0, 'assets/images/event_thumbnails/cvsu-imus.png', 500),
(46, 'test', 'Seminar', '2026-07-27', 'CvSU - Imus Campus', 10.00, 'test', 1, 1, 0, '/Walany/assets/images/event_thumbnails/cvsu-imus.png', 10),
(47, 'THIS IS A TEST EVENT', 'Seminar', '2026-07-27', 'Secret', 67.00, 'Test EVENT NGAA!!!!', 1, 1, 0, '/Walany/assets/images/event_thumbnails/1785082022_a6d0b559c8e55cbb.png', 67);

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
(12, '8', 1, 'geaa', 3),
(14, '2', 1, 'bait ni woody 1 star ka sakin', 1),
(15, 'PMEQ-5563', 1, 'sdadsad', 5),
(16, 'TLTY-5477', 1, 'asdas', 5),
(17, 'TMIN-9583', 1, 'safjhdasfdghgddgf', 5),
(18, 'ITYQ-8230', 17, 'dsaf123;&#039;[/.&amp;^%$#@', 4),
(19, 'PDRJ-0672', 13, 'this is a nice event!!', 5),
(20, 'KCIP-8393', 2, 'this is a test feedback', 4),
(21, 'VYHS-7040', 14, 'test', 3);

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
  `temp_password` varchar(255) DEFAULT NULL,
  `forgot_request` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `walania_managers`
--

INSERT INTO `walania_managers` (`id`, `first_name`, `last_name`, `email`, `password_hash`, `role`, `temp_password`, `forgot_request`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'System', 'admin@walany.edu.ph', '$2y$10$hqYI668gUI.xV2FEmE8BsuREY56bCfde3tF4zaX3rOnuPXQZOGTnq', 'admin', NULL, 0, '2026-07-05 22:25:26', '2026-07-16 18:07:57'),
(3, 'Registrar', 'System', 'registrar@walany.edu.ph', '$2y$10$uqNVxcX1VyzJIsIEecl4jOh8QmhJUjdcf/xJ/eLT7llDnFZzqNU8.', 'registrar', NULL, 0, '2026-07-05 22:42:55', '2026-07-16 18:08:13'),
(4, 'test', 'test', 'test@walany.edu.ph', '$2y$10$7ISRqJrURWSUqelNslk0/On8nxPCtORu4Li35oXwluLY57ZNZ9owi', 'planner', 'Wln-RDK1m1eums', 0, '2026-07-13 06:40:05', '2026-07-13 08:45:57'),
(5, 'Planner', 'System', 'planner@walany.edu.ph', '$2y$10$UixOIXA9VL1BwUuwZNQ/EOkvA3h8XChFANirpqlqDhHC.W088BsFW', 'planner', NULL, 0, '2026-07-13 06:45:17', '2026-07-16 22:23:36'),
(6, 'test', 'two', 'testtwo@walany.edu.ph', '$2y$10$f3M7RZAnJMmXsAk9H3r/XusBg5EvpHcyQKJL4jnJu2y1bc7knfMGG', 'planner', 'Wln-9QrSj4HjEf', 0, '2026-07-13 06:55:28', '2026-07-13 06:55:28'),
(7, 'test3', 'test3', 'test3@walany.edu.ph', '$2y$10$qT4G6TITPEXZgBBIf.iqxOdIkYFW.MW/OXRWIras7ikAAMPglldrW', 'planner', 'Wln-vc7mSEGHBu', 0, '2026-07-13 07:33:36', '2026-07-13 07:33:36');

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
(102, 'kristianelmerdelatorre@gmail.com', '589948', 0, 5, 1, '2026-07-17 06:59:15', NULL, '2026-07-17 06:54:15', '2026-07-17 06:54:15'),
(103, 'kristianelmerdelatorre@gmail.com', '390923', 0, 5, 2, '2026-07-17 06:59:28', NULL, '2026-07-17 06:54:28', '2026-07-17 06:54:28'),
(104, 'kristianelmerdelatorre@gmail.com', '242061', 0, 5, 3, '2026-07-17 07:00:49', NULL, '2026-07-17 06:55:49', '2026-07-17 06:55:49'),
(105, 'kristianelmerdelatorre@gmail.com', '411906', 0, 5, 4, '2026-07-17 07:05:30', NULL, '2026-07-17 07:00:30', '2026-07-17 07:00:30'),
(106, 'kristianelmerdelatorre@gmail.com', '448833', 0, 5, 5, '2026-07-17 07:10:36', NULL, '2026-07-17 07:05:36', '2026-07-17 07:05:36'),
(107, 'kristianelmerdelatorre@gmail.com', '755909', 0, 5, 6, '2026-07-17 15:40:09', NULL, '2026-07-17 15:35:09', '2026-07-17 15:35:09'),
(108, 'kristianelmerdelatorre@gmail.com', '527641', 0, 5, 1, '2026-07-26 21:37:14', NULL, '2026-07-26 21:32:14', '2026-07-26 21:32:14'),
(109, 'kristianelmerdelatorre@gmail.com', '808732', 0, 5, 2, '2026-07-26 21:44:13', NULL, '2026-07-26 21:39:13', '2026-07-26 21:39:13'),
(110, 'kristianelmerdelatorre@gmail.com', '878279', 0, 5, 1, '2026-07-27 07:02:19', NULL, '2026-07-27 06:57:19', '2026-07-27 06:57:19'),
(111, 'kristianelmerdelatorre@gmail.com', '454637', 0, 5, 2, '2026-07-27 07:11:17', NULL, '2026-07-27 07:06:17', '2026-07-27 07:06:17'),
(112, 'kristianelmerdelatorre@gmail.com', '331714', 0, 5, 3, '2026-07-27 07:12:49', NULL, '2026-07-27 07:07:49', '2026-07-27 07:07:49');

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
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `payment_status` enum('pending','completed','failed') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_amount` decimal(10,2) DEFAULT 0.00,
  `payment_reference` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_registrant`
--

INSERT INTO `walania_registrant` (`id`, `reference_id`, `first_name`, `middle_name`, `last_name`, `birthdate`, `email`, `contact_number`, `is_verified`, `registered_at`, `event_id`, `user_id`, `payment_status`, `payment_method`, `payment_amount`, `payment_reference`) VALUES
(1070, 'REF-10001', 'Juan', 'Santos', 'dela Cruz', '1982-04-12', 'juan.delacruz@email.com', '09171234567', 1, '2026-07-08 14:11:56', 1, NULL, 'pending', NULL, 0.00, NULL),
(1071, 'REF-10001', 'Juan', 'Santos', 'dela Cruz', '1982-04-12', 'juan.delacruz@email.com', '09171234567', 1, '2026-07-08 14:11:56', 11, NULL, 'pending', NULL, 0.00, NULL),
(1072, 'REF-10001', 'Juan', 'Santos', 'dela Cruz', '1982-04-12', 'juan.delacruz@email.com', '09171234567', 1, '2026-07-08 14:11:56', 15, NULL, 'pending', NULL, 0.00, NULL),
(1073, 'REF-10002', 'Maria', 'Sayson', 'Clara', '1995-11-23', 'maria.clara@email.com', '09189876543', 1, '2026-07-08 14:11:56', 2, NULL, 'pending', NULL, 0.00, NULL),
(1074, 'REF-10002', 'Maria', 'Sayson', 'Clara', '1995-11-23', 'maria.clara@email.com', '09189876543', 1, '2026-07-08 14:11:56', 12, NULL, 'pending', NULL, 0.00, NULL),
(1075, 'REF-10003', 'Crisostomo', 'Magsalin', 'Ibarra', '1991-08-15', 'c.ibarra@email.com', '09225554433', 1, '2026-07-08 14:11:56', 1, NULL, 'pending', NULL, 0.00, NULL),
(1076, 'REF-10003', 'Crisostomo', 'Magsalin', 'Ibarra', '1991-08-15', 'c.ibarra@email.com', '09225554433', 1, '2026-07-08 14:11:56', 14, NULL, 'pending', NULL, 0.00, NULL),
(1077, 'REF-10004', 'Elias', 'Pineda', 'Salome', '1978-02-28', 'elias.salome@email.com', '09151112233', 1, '2026-07-08 14:11:56', 2, NULL, 'pending', NULL, 0.00, NULL),
(1078, 'REF-10004', 'Elias', 'Pineda', 'Salome', '1978-02-28', 'elias.salome@email.com', '09151112233', 1, '2026-07-08 14:11:56', 13, NULL, 'pending', NULL, 0.00, NULL),
(1079, 'REF-10004', 'Elias', 'Pineda', 'Salome', '1978-02-28', 'elias.salome@email.com', '09151112233', 1, '2026-07-08 14:11:56', 17, NULL, 'pending', NULL, 0.00, NULL),
(1080, 'REF-10005', 'Andres', 'Castro', 'Bonifacio', '1988-11-30', 'andres.b@email.com', '09994447788', 1, '2026-07-08 14:11:56', 11, NULL, 'pending', NULL, 0.00, NULL),
(1081, 'REF-10006', 'Leonor', 'Bautista', 'Rivera', '2001-05-07', 'leonor.rivera@email.com', '09278889900', 1, '2026-07-08 14:11:56', 16, NULL, 'pending', NULL, 0.00, NULL),
(1082, 'REF-10007', 'Jose', 'Mercado', 'Rizal', '1990-06-19', 'jose.rizal@email.com', '09163334455', 1, '2026-07-08 14:11:56', 2, NULL, 'pending', NULL, 0.00, NULL),
(1083, 'REF-10008', 'Melchora', 'Ramos', 'Aquino', '1972-01-06', 'tandang.sora@email.com', '09192228811', 1, '2026-07-08 14:11:56', 12, NULL, 'pending', NULL, 0.00, NULL),
(1084, 'REF-10009', 'Angelo', 'Delgado', 'Santos', '2004-10-14', 'angelo.santos@email.com', '09087776655', 1, '2026-07-08 14:11:56', 1, NULL, 'pending', NULL, 0.00, NULL),
(1085, 'REF-10009', 'Angelo', 'Delgado', 'Santos', '2004-10-14', 'angelo.santos@email.com', '09087776655', 1, '2026-07-08 14:11:56', 17, NULL, 'pending', NULL, 0.00, NULL),
(1086, 'REF-10010', 'Kristina', 'Manuel', 'Reyes', '1998-03-25', 'kristina.reyes@email.com', '09452223344', 1, '2026-07-08 14:11:56', 13, NULL, 'pending', NULL, 0.00, NULL),
(1087, 'REF-10011', 'Mark Lester', 'Soriano', 'Mendoza', '1985-07-19', 'lester.mendoza@email.com', '09334445566', 1, '2026-07-08 14:11:56', 15, NULL, 'pending', NULL, 0.00, NULL),
(1088, 'REF-10012', 'Princess Mae', 'Valdez', 'Soriano', '2006-12-02', 'princess.soriano@email.com', '09175551122', 1, '2026-07-08 14:11:56', 2, NULL, 'pending', NULL, 0.00, NULL),
(1089, 'REF-10013', 'John Paul', 'Cruz', 'Bautista', '2002-09-09', 'jp.bautista@email.com', '09283337744', 1, '2026-07-08 14:11:56', 11, NULL, 'pending', NULL, 0.00, NULL),
(1090, 'REF-10014', 'Rochelle', 'Aquino', 'Pascual', '1975-04-30', 'rochelle.p@email.com', '09064448899', 1, '2026-07-08 14:11:56', 14, NULL, 'pending', NULL, 0.00, NULL),
(1091, 'REF-10015', 'Christian', 'Gomez', 'Dimaculangan', '1993-02-14', 'c.dimaculangan@email.com', '09156663322', 1, '2026-07-08 14:11:56', 12, NULL, 'pending', NULL, 0.00, NULL),
(1092, 'REF-10015', 'Christian', 'Gomez', 'Dimaculangan', '1993-02-14', 'c.dimaculangan@email.com', '09156663322', 1, '2026-07-08 14:11:56', 16, NULL, 'pending', NULL, 0.00, NULL),
(1093, 'REF-10016', 'Althea Mae', 'Fernandez', 'Dizon', '2007-08-21', 'althea.dizon@email.com', '09981115544', 1, '2026-07-08 14:11:56', 1, NULL, 'pending', NULL, 0.00, NULL),
(1094, 'REF-10017', 'Joshua', 'Gutierrez', 'Villanueva', '2000-01-15', 'josh.villanueva@email.com', '09228883344', 1, '2026-07-08 14:11:56', 15, NULL, 'pending', NULL, 0.00, NULL),
(1095, 'REF-10018', 'Mary Grace', 'Villareal', 'Castro', '1980-06-05', 'grace.castro@email.com', '09164440011', 1, '2026-07-08 14:11:56', 17, NULL, 'pending', NULL, 0.00, NULL),
(1096, 'REF-10019', 'Nathaniel', 'Alvarez', 'Garcia', '2005-03-11', 'nate.garcia@email.com', '09772229988', 1, '2026-07-08 14:11:56', 13, NULL, 'pending', NULL, 0.00, NULL),
(1097, 'REF-10020', 'Patricia Anne', 'Roxas', 'Aquino', '1997-09-27', 'patricia.aquino@email.com', '09195556677', 1, '2026-07-08 14:11:56', 16, NULL, 'pending', NULL, 0.00, NULL),
(1098, 'REF-10021', 'Emilio', 'Famy', 'Aguinaldo', '1979-03-22', 'emilio.a@email.com', '09173332211', 1, '2026-07-08 14:12:28', 1, NULL, 'pending', NULL, 0.00, NULL),
(1099, 'REF-10021', 'Emilio', 'Famy', 'Aguinaldo', '1979-03-22', 'emilio.a@email.com', '09173332211', 1, '2026-07-08 14:12:28', 12, NULL, 'pending', NULL, 0.00, NULL),
(1100, 'REF-10021', 'Emilio', 'Famy', 'Aguinaldo', '1979-03-22', 'emilio.a@email.com', '09173332211', 1, '2026-07-08 14:12:28', 17, NULL, 'pending', NULL, 0.00, NULL),
(1101, 'REF-10022', 'Apolinario', 'Maranan', 'Mabini', '1984-07-23', 'a.mabini@email.com', '09187776655', 1, '2026-07-08 14:12:28', 2, NULL, 'pending', NULL, 0.00, NULL),
(1102, 'REF-10022', 'Apolinario', 'Maranan', 'Mabini', '1984-07-23', 'a.mabini@email.com', '09187776655', 1, '2026-07-08 14:12:28', 11, NULL, 'pending', NULL, 0.00, NULL),
(1103, 'REF-10023', 'Gabriela', 'Cariño', 'Silang', '1992-03-19', 'gabriela.silang@email.com', '09224443322', 1, '2026-07-08 14:12:28', 13, NULL, 'pending', NULL, 0.00, NULL),
(1104, 'REF-10024', 'Marcelo', 'Hilario', 'del Pilar', '1976-08-30', 'plaridel@email.com', '09159998877', 1, '2026-07-08 14:12:28', 1, NULL, 'pending', NULL, 0.00, NULL),
(1105, 'REF-10024', 'Marcelo', 'Hilario', 'del Pilar', '1976-08-30', 'plaridel@email.com', '09159998877', 1, '2026-07-08 14:12:28', 14, NULL, 'pending', NULL, 0.00, NULL),
(1106, 'REF-10025', 'Juan', 'Novicio', 'Luna', '1989-10-23', 'juan.luna@email.com', '09995551122', 1, '2026-07-08 14:12:28', 15, NULL, 'pending', NULL, 0.00, NULL),
(1107, 'REF-10026', 'Justin Miguel', 'Panganiban', 'Tolentino', '2005-04-14', 'jm.tolentino@email.com', '09271114477', 1, '2026-07-08 14:12:28', 2, NULL, 'pending', NULL, 0.00, NULL),
(1108, 'REF-10027', 'Ma. Theresa', 'Santiago', 'De Leon', '1996-01-28', 'theresa.deleon@email.com', '09168883344', 1, '2026-07-08 14:12:28', 12, NULL, 'pending', NULL, 0.00, NULL),
(1109, 'REF-10027', 'Ma. Theresa', 'Santiago', 'De Leon', '1996-01-28', 'theresa.deleon@email.com', '09168883344', 1, '2026-07-08 14:12:28', 16, NULL, 'pending', NULL, 0.00, NULL),
(1110, 'REF-10028', 'Jerome', 'Villanueva', 'Macaraeg', '2002-11-09', 'jerome.macaraeg@email.com', '09193335522', 1, '2026-07-08 14:12:28', 17, NULL, 'pending', NULL, 0.00, NULL),
(1111, 'REF-10029', 'Chloe Nicole', 'Mercado', 'Salvador', '2007-06-18', 'chloe.salvador@email.com', '09084441155', 1, '2026-07-08 14:12:28', 11, NULL, 'pending', NULL, 0.00, NULL),
(1112, 'REF-10030', 'Aldrin John', 'Domingo', 'Corpuz', '1999-09-05', 'aj.corpuz@email.com', '09456662288', 1, '2026-07-08 14:12:28', 13, NULL, 'pending', NULL, 0.00, NULL),
(1113, 'REF-10031', 'Erika Mae', 'Soriano', 'Francisco', '2004-02-21', 'erika.francisco@email.com', '09335559900', 1, '2026-07-08 14:12:28', 2, NULL, 'pending', NULL, 0.00, NULL),
(1114, 'REF-10031', 'Erika Mae', 'Soriano', 'Francisco', '2004-02-21', 'erika.francisco@email.com', '09335559900', 1, '2026-07-08 14:12:28', 15, NULL, 'pending', NULL, 0.00, NULL),
(1115, 'REF-10032', 'Gian Carlo', 'Alvarez', 'Mendoza', '1994-07-11', 'gian.mendoza@email.com', '09172228833', 1, '2026-07-08 14:12:28', 14, NULL, 'pending', NULL, 0.00, NULL),
(1116, 'REF-10033', 'Mary Joy', 'Legaspi', 'Evangelista', '1981-12-15', 'joy.evangelista@email.com', '09287771144', 1, '2026-07-08 14:12:28', 16, NULL, 'pending', NULL, 0.00, NULL),
(1117, 'REF-10034', 'Patrick Daniel', 'Reyes', 'Pascual', '2001-05-30', 'patrick.pascual@email.com', '09062227744', 1, '2026-07-08 14:12:28', 1, NULL, 'pending', NULL, 0.00, NULL),
(1118, 'REF-10035', 'Alyssa Marie', 'Cruz', 'Bunag', '2006-10-02', 'alyssa.bunag@email.com', '09158884411', 1, '2026-07-08 14:12:28', 2, NULL, 'pending', NULL, 0.00, NULL),
(1119, 'REF-10035', 'Alyssa Marie', 'Cruz', 'Bunag', '2006-10-02', 'alyssa.bunag@email.com', '09158884411', 1, '2026-07-08 14:12:28', 11, NULL, 'pending', NULL, 0.00, NULL),
(1120, 'REF-10035', 'Alyssa Marie', 'Cruz', 'Bunag', '2006-10-02', 'alyssa.bunag@email.com', '09158884411', 1, '2026-07-08 14:12:28', 17, NULL, 'pending', NULL, 0.00, NULL),
(1121, 'REF-10036', 'Kenneth Kyle', 'Santos', 'Gonzales', '1997-08-14', 'kenneth.gonzales@email.com', '09986662233', 1, '2026-07-08 14:12:28', 13, NULL, 'pending', NULL, 0.00, NULL),
(1122, 'REF-10037', 'Stephanie', 'Castro', 'Miranda', '1987-04-03', 'steph.miranda@email.com', '09221115566', 1, '2026-07-08 14:12:28', 12, NULL, 'pending', NULL, 0.00, NULL),
(1123, 'REF-10038', 'Dave Christian', 'Bautista', 'Javier', '2003-01-25', 'dave.javier@email.com', '09165559922', 1, '2026-07-08 14:12:28', 14, NULL, 'pending', NULL, 0.00, NULL),
(1124, 'REF-10039', 'Kimberly Rose', 'Aquino', 'Flores', '1973-11-12', 'kim.flores@email.com', '09774441100', 1, '2026-07-08 14:12:28', 15, NULL, 'pending', NULL, 0.00, NULL),
(1125, 'REF-10040', 'Janzen Paul', 'Gomez', 'Valenzuela', '2000-06-20', 'janzen.v@email.com', '09198884433', 1, '2026-07-08 14:12:28', 16, NULL, 'pending', NULL, 0.00, NULL),
(1126, 'REF-10041', 'Melchora', 'Ramos', 'Aquino', '1974-01-06', 'm.aquino@email.com', '09172225588', 1, '2026-07-08 14:13:21', 1, NULL, 'pending', NULL, 0.00, NULL),
(1127, 'REF-10041', 'Melchora', 'Ramos', 'Aquino', '1974-01-06', 'm.aquino@email.com', '09172225588', 1, '2026-07-08 14:13:21', 11, NULL, 'pending', NULL, 0.00, NULL),
(1128, 'REF-10041', 'Melchora', 'Ramos', 'Aquino', '1974-01-06', 'm.aquino@email.com', '09172225588', 1, '2026-07-08 14:13:21', 16, NULL, 'pending', NULL, 0.00, NULL),
(1129, 'REF-10042', 'Antonio', 'Novicio', 'Luna', '1983-10-29', 'general.luna@email.com', '09185554433', 1, '2026-07-08 14:13:21', 2, NULL, 'pending', NULL, 0.00, NULL),
(1130, 'REF-10042', 'Antonio', 'Novicio', 'Luna', '1983-10-29', 'general.luna@email.com', '09185554433', 1, '2026-07-08 14:13:21', 14, NULL, 'pending', NULL, 0.00, NULL),
(1131, 'REF-10043', 'Diego', 'Andaya', 'Silang', '1990-12-16', 'diego.silang@email.com', '09229990011', 1, '2026-07-08 14:13:21', 12, NULL, 'pending', NULL, 0.00, NULL),
(1132, 'REF-10044', 'Gregorio', 'Sempio', 'del Pilar', '1975-11-14', 'goyo.pilar@email.com', '09154447788', 1, '2026-07-08 14:13:21', 1, NULL, 'pending', NULL, 0.00, NULL),
(1133, 'REF-10044', 'Gregorio', 'Sempio', 'del Pilar', '1975-11-14', 'goyo.pilar@email.com', '09154447788', 1, '2026-07-08 14:13:21', 15, NULL, 'pending', NULL, 0.00, NULL),
(1134, 'REF-10045', 'Emilio', 'Dizon', 'Jacinto', '1986-12-15', 'pingkian@email.com', '09998883322', 1, '2026-07-08 14:13:21', 13, NULL, 'pending', NULL, 0.00, NULL),
(1135, 'REF-10046', 'Miguel Antonio', 'Roxas', 'Santos', '2004-05-20', 'miggy.santos@email.com', '09275556633', 1, '2026-07-08 14:13:21', 2, NULL, 'pending', NULL, 0.00, NULL),
(1136, 'REF-10047', 'Maria Angelica', 'Pascual', 'Cruz', '1998-02-14', 'ma.angelica@email.com', '09164441122', 1, '2026-07-08 14:13:21', 12, NULL, 'pending', NULL, 0.00, NULL),
(1137, 'REF-10047', 'Maria Angelica', 'Pascual', 'Cruz', '1998-02-14', 'ma.angelica@email.com', '09164441122', 1, '2026-07-08 14:13:21', 17, NULL, 'pending', NULL, 0.00, NULL),
(1138, 'REF-10048', 'John Michael', 'Villanueva', 'Reyes', '2001-08-09', 'jm.reyes@email.com', '09192224455', 1, '2026-07-08 14:13:21', 11, NULL, 'pending', NULL, 0.00, NULL),
(1139, 'REF-10049', 'Nicole Beatrice', 'Salvador', 'Diaz', '2008-03-22', 'nicole.diaz@email.com', '09081119966', 1, '2026-07-08 14:13:21', 13, NULL, 'pending', NULL, 0.00, NULL),
(1140, 'REF-10050', 'Paolo Gabriel', 'Domingo', 'Mendoza', '1995-10-04', 'paolo.mendoza@email.com', '09459993311', 1, '2026-07-08 14:13:21', 14, NULL, 'pending', NULL, 0.00, NULL),
(1141, 'REF-10051', 'Mary Rose', 'Francisco', 'Soriano', '2003-04-11', 'maryrose.s@email.com', '09334440022', 1, '2026-07-08 14:13:21', 2, NULL, 'pending', NULL, 0.00, NULL),
(1142, 'REF-10051', 'Mary Rose', 'Francisco', 'Soriano', '2003-04-11', 'maryrose.s@email.com', '09334440022', 1, '2026-07-08 14:13:21', 15, NULL, 'pending', NULL, 0.00, NULL),
(1143, 'REF-10052', 'Christian James', 'Alvarez', 'Castro', '1992-06-30', 'cj.castro@email.com', '09178881144', 1, '2026-07-08 14:13:21', 17, NULL, 'pending', NULL, 0.00, NULL),
(1144, 'REF-10053', 'Patricia Mae', 'Legaspi', 'Fernandez', '1982-11-25', 'patricia.f@email.com', '09283339900', 1, '2026-07-08 14:13:21', 1, NULL, 'pending', NULL, 0.00, NULL),
(1145, 'REF-10054', 'Joshua Daniel', 'Tolentino', 'Gabriel', '2000-09-17', 'josh.gabriel@email.com', '09065552277', 1, '2026-07-08 14:13:21', 12, NULL, 'pending', NULL, 0.00, NULL),
(1146, 'REF-10055', 'Althea Louise', 'Gomez', 'Bunag', '2007-01-14', 'althea.bunag@email.com', '09152226699', 1, '2026-07-08 14:13:21', 1, NULL, 'pending', NULL, 0.00, NULL),
(1147, 'REF-10055', 'Althea Louise', 'Gomez', 'Bunag', '2007-01-14', 'althea.bunag@email.com', '09152226699', 1, '2026-07-08 14:13:21', 13, NULL, 'pending', NULL, 0.00, NULL),
(1148, 'REF-10055', 'Althea Louise', 'Gomez', 'Bunag', '2007-01-14', 'althea.bunag@email.com', '09152226699', 1, '2026-07-08 14:13:21', 16, NULL, 'pending', NULL, 0.00, NULL),
(1149, 'REF-10056', 'Renz Christopher', 'Santiago', 'Gonzales', '1996-07-03', 'renz.gonzales@email.com', '09984441155', 1, '2026-07-08 14:13:21', 14, NULL, 'pending', NULL, 0.00, NULL),
(1150, 'REF-10057', 'Alyssa Nicole', 'Bautista', 'Miranda', '1989-05-19', 'alyssa.m@email.com', '09227773322', 1, '2026-07-08 14:13:21', 15, NULL, 'pending', NULL, 0.00, NULL),
(1151, 'REF-10058', 'Dave Anthony', 'Aquino', 'Javier', '2005-02-28', 'dave.javier@email.com', '09161118844', 1, '2026-07-08 14:13:21', 17, NULL, 'pending', NULL, 0.00, NULL),
(1152, 'REF-10059', 'Janine Rose', 'De Leon', 'Flores', '1971-10-14', 'janine.flores@email.com', '09773335522', 1, '2026-07-08 14:13:21', 11, NULL, 'pending', NULL, 0.00, NULL),
(1153, 'REF-10060', 'John Kenneth', 'Corpuz', 'Valenzuela', '1999-12-02', 'kenneth.v@email.com', '09195550011', 1, '2026-07-08 14:13:21', 16, NULL, 'pending', NULL, 0.00, NULL),
(1156, 'LFND-2535', 'Kristian Elmer', NULL, 'Dela Torre', '2005-05-24', 'yeahlow24@gmail.com', '09068005260', 0, '2026-07-09 05:12:21', 1, NULL, 'pending', NULL, 0.00, NULL),
(1157, 'YSHH-0342', 'Kristian Elmer', NULL, 'Dela Torre', '2005-05-24', 'yeahlow24@gmail.com', '09068005260', 0, '2026-07-09 05:13:10', 1, NULL, 'pending', NULL, 0.00, NULL),
(1158, 'WEQK-7846', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2005-05-24', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-11 23:45:46', 13, NULL, 'pending', NULL, 0.00, NULL),
(1159, 'IYDS-7415', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2005-05-24', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-11 23:47:13', 13, NULL, 'pending', NULL, 0.00, NULL),
(1160, 'QOTV-3875', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-12 00:22:13', 13, NULL, 'pending', NULL, 0.00, NULL),
(1161, 'IUBE-9120', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 0, '2026-07-12 00:22:35', 13, NULL, 'pending', NULL, 0.00, NULL),
(1162, 'BROU-5636', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 00:22:55', 13, NULL, 'pending', NULL, 0.00, NULL),
(1163, 'RSMS-2164', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 00:30:19', 13, NULL, 'pending', NULL, 0.00, NULL),
(1164, 'EOLN-1057', 'Kristian Elmer', NULL, 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 01:21:28', 1, NULL, 'pending', NULL, 0.00, NULL),
(1165, 'TIDU-0580', 'Kristian Elmer', NULL, 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 01:22:37', 1, NULL, 'pending', NULL, 0.00, NULL),
(1166, 'SULV-3995', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 02:04:34', 1, NULL, 'pending', NULL, 0.00, NULL),
(1167, 'XBSF-3880', 'Kristian Elmer', NULL, 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 02:05:47', 1, NULL, 'pending', NULL, 0.00, NULL),
(1168, 'PXQR-7820', 'Kristian Elmer', NULL, 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 02:19:28', 1, NULL, 'pending', NULL, 0.00, NULL),
(1169, 'EQIT-1680', 'Kristian Elmer', NULL, 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 02:22:25', 1, NULL, 'pending', NULL, 0.00, NULL),
(1171, 'ZYTT-6513', 'Kristian Elmer', 'Robiato', 'Dela Torre', '2000-01-01', 'kristianelmer.delatorre@cvsu.edu.ph', '09123456789', 1, '2026-07-12 02:38:12', 1, NULL, 'completed', 'PayMongo_Gateway', 250.00, 'PMGO-28B2D74C47'),
(1172, 'ADFL-9738', 'Juan', NULL, 'Dela Cruz', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-16 22:33:57', 15, NULL, 'pending', NULL, 0.00, NULL),
(1173, 'SRXZ-3698', 'Juan', NULL, 'Dela Cruz', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-16 22:36:04', 15, NULL, 'pending', NULL, 0.00, NULL),
(1174, 'EOXT-8919', 'Juan', NULL, 'Dela Cruz', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-16 22:37:00', 15, NULL, 'pending', NULL, 0.00, NULL),
(1175, 'XGMW-7507', 'Juan', NULL, 'Dela Cruz', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-16 22:38:49', 15, NULL, 'pending', NULL, 0.00, NULL),
(1176, 'BJWO-2569', 'Juan', NULL, 'Dela Cruz', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-16 22:47:13', 15, NULL, 'pending', NULL, 0.00, NULL),
(1177, 'HITN-6688', 'Juan', NULL, 'Dela Cruz', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-16 22:51:45', 15, NULL, 'pending', NULL, 0.00, NULL),
(1178, 'CJXT-4706', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:31:45', 18, NULL, 'pending', NULL, 0.00, NULL),
(1179, 'SHDE-0378', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:39:53', 18, NULL, 'pending', NULL, 0.00, NULL),
(1180, 'HRDP-9972', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:45:33', 18, NULL, 'pending', NULL, 0.00, NULL),
(1181, 'NKLJ-1868', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:48:37', 18, NULL, 'pending', NULL, 0.00, NULL),
(1182, 'VDJT-6577', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:49:20', 18, NULL, 'pending', NULL, 0.00, NULL),
(1183, 'BWRQ-1078', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:54:33', 18, NULL, 'pending', NULL, 0.00, NULL),
(1184, 'NFXQ-1054', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:55:20', 18, NULL, 'pending', NULL, 0.00, NULL),
(1185, 'YCAA-9609', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:58:50', 18, NULL, 'pending', NULL, 0.00, NULL),
(1186, 'SIDA-8853', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:58:56', 18, NULL, 'pending', NULL, 0.00, NULL),
(1187, 'PYBY-2488', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 05:59:04', 18, NULL, 'pending', NULL, 0.00, NULL),
(1188, 'KBGW-8948', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-17 06:01:46', 18, NULL, 'pending', NULL, 0.00, NULL),
(1189, 'RDVS-6551', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:06:47', 18, NULL, 'pending', NULL, 0.00, NULL),
(1190, 'JCZE-4571', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:10:07', 18, NULL, 'pending', NULL, 0.00, NULL),
(1191, 'NWYU-5603', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-17 06:13:18', 18, NULL, 'pending', NULL, 0.00, NULL),
(1192, 'VNYM-9237', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-17 06:18:09', 18, NULL, 'completed', 'PayMongo_Gateway', 250.00, 'PMGO-9480BAA9C8'),
(1193, 'TPUL-2265', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:24:19', 14, NULL, 'pending', NULL, 0.00, NULL),
(1194, 'VQQD-8347', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:26:30', 14, NULL, 'pending', NULL, 0.00, NULL),
(1195, 'OVMB-6406', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:26:35', 14, NULL, 'pending', NULL, 0.00, NULL),
(1196, 'DYYZ-2919', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:27:52', 14, NULL, 'pending', NULL, 0.00, NULL),
(1197, 'ITZM-2075', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:29:01', 14, NULL, 'pending', NULL, 0.00, NULL),
(1198, 'VYHS-7040', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:30:23', 14, NULL, 'pending', NULL, 0.00, NULL),
(1199, 'FWWP-0885', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:32:17', 14, NULL, 'pending', NULL, 0.00, NULL),
(1200, 'XTTS-3287', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:32:51', 14, NULL, 'pending', NULL, 0.00, NULL),
(1201, 'HTJG-7097', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:33:30', 14, NULL, 'pending', NULL, 0.00, NULL),
(1202, 'RETR-2940', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:35:08', 14, NULL, 'pending', NULL, 0.00, NULL),
(1203, 'IVMY-6283', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:36:16', 14, NULL, 'pending', NULL, 0.00, NULL),
(1204, 'EIFY-0481', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:37:21', 14, NULL, 'pending', NULL, 0.00, NULL),
(1205, 'GBQS-7053', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:39:06', 14, NULL, 'pending', NULL, 0.00, NULL),
(1206, 'DNDH-3856', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:40:18', 14, NULL, 'pending', NULL, 0.00, NULL),
(1207, 'CQSO-0411', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:42:42', 14, NULL, 'pending', NULL, 0.00, NULL),
(1208, 'QHDA-6576', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:43:07', 14, NULL, 'pending', NULL, 0.00, NULL),
(1209, 'SNYU-3366', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:49:19', 14, NULL, 'pending', NULL, 0.00, NULL),
(1210, 'NDHW-9384', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:50:20', 14, NULL, 'pending', NULL, 0.00, NULL),
(1211, 'MRWU-3944', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:51:08', 14, NULL, 'pending', NULL, 0.00, NULL),
(1212, 'BQCZ-2864', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:52:48', 14, NULL, 'pending', NULL, 0.00, NULL),
(1213, 'YKAF-2342', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:53:26', 14, NULL, 'pending', NULL, 0.00, NULL),
(1214, 'UFYT-0265', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 06:54:15', 14, NULL, 'pending', NULL, 0.00, NULL),
(1215, 'FXWZ-0712', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-17 06:54:28', 14, NULL, 'pending', NULL, 0.00, NULL),
(1216, 'MDXM-8658', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-17 06:55:49', 14, NULL, 'pending', NULL, 0.00, NULL),
(1217, 'JRIY-8860', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-17 07:00:30', 14, NULL, 'pending', NULL, 0.00, NULL),
(1218, 'JIWK-0695', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 0, '2026-07-17 07:05:36', 14, NULL, 'pending', NULL, 0.00, NULL),
(1219, 'QZZC-9545', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-17 15:35:09', 18, NULL, 'completed', 'PayMongo_Gateway', 250.00, 'PMGO-A4EFCB9270'),
(1220, 'EQCB-1193', 'test', NULL, 'System', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09068005260', 1, '2026-07-26 21:32:14', 47, NULL, 'completed', 'PayMongo_Gateway', 250.00, 'PMGO-34E59C70E8'),
(1221, 'SIBG-4905', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-26 21:39:13', 47, NULL, 'completed', 'PayMongo_Gateway', 250.00, 'PMGO-8972577CCB'),
(1222, 'BRTF-2424', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-27 06:57:19', 47, NULL, 'completed', 'PayMongo_Gateway', 67.00, 'PMGO-89B744B2E2'),
(1223, 'AZOT-2026', 'Test', NULL, 'Register', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-27 07:06:17', 47, NULL, 'completed', 'PayMongo_Gateway', 67.00, 'PMGO-53B76255EB'),
(1224, 'XPXA-5284', 'Kristian', NULL, 'Dela Torre', '2000-01-01', 'kristianelmerdelatorre@gmail.com', '09123456789', 1, '2026-07-27 07:07:49', 47, NULL, 'pending', NULL, 0.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `walania_registration_queue`
--

CREATE TABLE `walania_registration_queue` (
  `session_id` varchar(255) NOT NULL,
  `event_id` int(11) NOT NULL,
  `status` enum('waiting','active') DEFAULT 'waiting',
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_registration_queue`
--

INSERT INTO `walania_registration_queue` (`session_id`, `event_id`, `status`, `joined_at`, `last_activity`) VALUES
('mclcs7p439fu3jno8iik73go0k', 1, 'active', '2026-07-27 07:22:07', '2026-07-27 07:22:07');

-- --------------------------------------------------------

--
-- Table structure for table `walania_system_access_logs`
--

CREATE TABLE `walania_system_access_logs` (
  `id` int(11) NOT NULL,
  `actor_id` int(11) NOT NULL,
  `actor_name` varchar(255) NOT NULL,
  `action_details` text NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `logged_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `walania_system_access_logs`
--

INSERT INTO `walania_system_access_logs` (`id`, `actor_id`, `actor_name`, `action_details`, `ip_address`, `logged_at`) VALUES
(1, 0, 'System Admin', 'Provisioned new system user: testtwo@walany.edu.ph with environment role matrix: [planner]', '::1', '2026-07-13 06:55:28'),
(2, 0, 'System Admin', 'Regenerated structural access temp key token for user ID: 4', '::1', '2026-07-13 07:04:52'),
(3, 1, 'System Admin', 'Provisioned new system user: test3@walany.edu.ph with environment role matrix: [planner]', '::1', '2026-07-13 07:33:36'),
(4, 1, 'System Admin', 'Admin updated their own credentials matrix parameters (ID: 1)', '::1', '2026-07-13 08:22:42'),
(5, 1, 'System Admin', 'Approved forgot password request and issued temporary key for profile: test@walany.edu.ph', '::1', '2026-07-13 08:45:57'),
(6, 1, 'System Admin', 'Admin updated their own credentials matrix parameters (ID: 1)', '::1', '2026-07-16 17:51:44'),
(7, 1, 'System Admin', 'Admin updated their own credentials matrix parameters (ID: 1)', '::1', '2026-07-16 17:51:53'),
(8, 3, '', 'User updated their own profile credentials.', '', '2026-07-16 17:55:34'),
(9, 3, '', 'User updated their own profile credentials.', '', '2026-07-16 17:55:45'),
(10, 3, '', 'User updated their own profile credentials.', '', '2026-07-16 17:57:58'),
(11, 3, '', 'User updated their own profile credentials.', '', '2026-07-16 17:58:04'),
(12, 3, '', 'User updated their own profile credentials.', '', '2026-07-16 18:07:31'),
(13, 1, '', 'User updated their own profile credentials.', '', '2026-07-16 18:07:50'),
(14, 1, '', 'User updated their own profile credentials.', '', '2026-07-16 18:07:57'),
(15, 3, '', 'User updated their own profile credentials.', '', '2026-07-16 18:08:13'),
(16, 5, '', 'User updated their own profile credentials.', '', '2026-07-16 22:22:53'),
(17, 5, '', 'User updated their own profile credentials.', '', '2026-07-16 22:23:27'),
(18, 5, '', 'User updated their own profile credentials.', '', '2026-07-16 22:23:36');

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
-- Indexes for table `walania_registration_queue`
--
ALTER TABLE `walania_registration_queue`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `walania_system_access_logs`
--
ALTER TABLE `walania_system_access_logs`
  ADD PRIMARY KEY (`id`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `walania_chat_sessions`
--
ALTER TABLE `walania_chat_sessions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `walania_event`
--
ALTER TABLE `walania_event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `walania_event_feedback`
--
ALTER TABLE `walania_event_feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `walania_managers`
--
ALTER TABLE `walania_managers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `walania_otp_logs`
--
ALTER TABLE `walania_otp_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- AUTO_INCREMENT for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1225;

--
-- AUTO_INCREMENT for table `walania_system_access_logs`
--
ALTER TABLE `walania_system_access_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

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
