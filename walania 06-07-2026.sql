-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jul 06, 2026 at 01:23 AM
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
-- Database: `dbaccounts`
--
CREATE DATABASE IF NOT EXISTS `dbaccounts` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dbaccounts`;

-- --------------------------------------------------------

--
-- Table structure for table `tblaccounts`
--

CREATE TABLE `tblaccounts` (
  `account_id` int(10) NOT NULL,
  `account_name` varchar(50) NOT NULL,
  `account_type` varchar(50) NOT NULL,
  `balance` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblaccounts`
--

INSERT INTO `tblaccounts` (`account_id`, `account_name`, `account_type`, `balance`) VALUES
(1, 'Juan Dela Cruz', 'Savings', 10000),
(2, 'Maria Santos', 'Checking', 27000.5),
(3, 'Pedro Reyes', 'Savings', 18000.8),
(4, 'Ana Lopez', 'Checking', 17500),
(5, 'Carlos Garcia', 'Savings', 25000.2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tblaccounts`
--
ALTER TABLE `tblaccounts`
  ADD PRIMARY KEY (`account_id`);
--
-- Database: `dblibrarysystem`
--
CREATE DATABASE IF NOT EXISTS `dblibrarysystem` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `dblibrarysystem`;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_books`
--

CREATE TABLE `tbl_books` (
  `book_ID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `author` varchar(100) NOT NULL,
  `genre` varchar(100) NOT NULL,
  `description` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_books`
--

INSERT INTO `tbl_books` (`book_ID`, `name`, `author`, `genre`, `description`) VALUES
(1, 'Pinocchio', 'Carlo Collodi', 'Classic Fiction', ''),
(2, 'Peter Pan', 'J. M. Barrie', 'Fantasy', ''),
(3, 'Harry Potter and the Sorcerer\'s Stone', 'J. K. Rowling', 'Fantasy', '');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_borrowed_books`
--

CREATE TABLE `tbl_borrowed_books` (
  `borrow_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT 'borrowed',
  `time_borrowed` varchar(10) DEFAULT NULL,
  `date_borrowed` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_borrowed_books`
--

INSERT INTO `tbl_borrowed_books` (`borrow_id`, `student_id`, `book_id`, `borrow_date`, `due_date`, `return_date`, `status`, `time_borrowed`, `date_borrowed`) VALUES
(4, 202410902, 2, '2026-01-21', '2026-02-04', NULL, 'borrowed', NULL, NULL),
(5, 202410902, 1, '2026-01-21', '2026-02-04', NULL, 'borrowed', NULL, NULL),
(6, 2024123456, 3, '2026-01-21', '2026-02-04', NULL, 'borrowed', NULL, NULL),
(7, 202112345, 1, '2026-01-21', '2026-02-04', NULL, 'borrowed', NULL, NULL),
(8, 202410490, 1, '2026-01-21', '2026-02-04', NULL, 'borrowed', NULL, NULL),
(9, 202410902, 1, '2026-01-21', '2026-02-04', NULL, 'borrowed', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_students`
--

CREATE TABLE `tbl_students` (
  `student_ID` int(20) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `course` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_students`
--

INSERT INTO `tbl_students` (`student_ID`, `full_name`, `email`, `course`, `password_hash`) VALUES
(2024, 'kristian', 'kristian@gmail.com', 'IT', '$2a$10$j4ykePu2jJc5bHj1XnnWzOoUzW25IzNuRxzV7/oJajCg/R2xtSTGG'),
(202112345, 'Juan Dela Cruz', 'juan.delacruz@cvsu.edu.ph', 'BSCS', '$2a$10$03xwpxYZEIvMLU0kQL3SNeIsTENIeIQ7rIazPMbpo8hGa4Fluj28y'),
(202410394, 'Jericho Calunsag', 'jerichocalunsag@gmail.com', 'BSCS', '$2a$10$oiA5b5vRpAs0uqcm1sjdC.jMiGNAF3uukr/Ib8fKHe/aXxsffVYmq'),
(202410490, 'Christine Joy Arenas', 'christinejoy@gmail.com', 'BSIT', '$2a$10$o22rDTwJPIBTtQ4mT19QZe58cxS.nTmhD6tvdJbd8ziH6P2xMuBtG'),
(202410902, 'Kristian Elmer Dela Torre', 'kristianelmer.delatorre@cvsu.edu.ph', 'BSIT', '$2a$10$joZhpRsY2CvSgA7S6Q809eeFukQKYQbmEXM2XE3WxHQR4.Jvk5Zze'),
(202412345, 'Juan Dela Cruz', 'jdc@cvsu.edu.ph', 'IT', '$2a$10$A7PWfo3Uu6acE.b9W9STG.Ez0NtTbxIixrvIh1aMNHw./rElkRBvq'),
(2024123456, 'Jericho Calunsag', 'jericho.calunsag@cvsu.edu.ph', 'BSIT', '$2a$10$bRzQ0m0Q8O.OnCqow3P5COfp89Tt546tX/omzvhOl3PNXFQlcHHaC');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_books`
--
ALTER TABLE `tbl_books`
  ADD PRIMARY KEY (`book_ID`);

--
-- Indexes for table `tbl_borrowed_books`
--
ALTER TABLE `tbl_borrowed_books`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `tbl_students`
--
ALTER TABLE `tbl_students`
  ADD PRIMARY KEY (`student_ID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_books`
--
ALTER TABLE `tbl_books`
  MODIFY `book_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_borrowed_books`
--
ALTER TABLE `tbl_borrowed_books`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_borrowed_books`
--
ALTER TABLE `tbl_borrowed_books`
  ADD CONSTRAINT `tbl_borrowed_books_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `tbl_students` (`student_ID`),
  ADD CONSTRAINT `tbl_borrowed_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `tbl_books` (`book_ID`);
--
-- Database: `phpmyadmin`
--
CREATE DATABASE IF NOT EXISTS `phpmyadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_bin;
USE `phpmyadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `pma__bookmark`
--

CREATE TABLE `pma__bookmark` (
  `id` int(10) UNSIGNED NOT NULL,
  `dbase` varchar(255) NOT NULL DEFAULT '',
  `user` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Bookmarks';

-- --------------------------------------------------------

--
-- Table structure for table `pma__central_columns`
--

CREATE TABLE `pma__central_columns` (
  `db_name` varchar(64) NOT NULL,
  `col_name` varchar(64) NOT NULL,
  `col_type` varchar(64) NOT NULL,
  `col_length` text DEFAULT NULL,
  `col_collation` varchar(64) NOT NULL,
  `col_isNull` tinyint(1) NOT NULL,
  `col_extra` varchar(255) DEFAULT '',
  `col_default` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Central list of columns';

-- --------------------------------------------------------

--
-- Table structure for table `pma__column_info`
--

CREATE TABLE `pma__column_info` (
  `id` int(5) UNSIGNED NOT NULL,
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `column_name` varchar(64) NOT NULL DEFAULT '',
  `comment` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `mimetype` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `transformation` varchar(255) NOT NULL DEFAULT '',
  `transformation_options` varchar(255) NOT NULL DEFAULT '',
  `input_transformation` varchar(255) NOT NULL DEFAULT '',
  `input_transformation_options` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Column information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__designer_settings`
--

CREATE TABLE `pma__designer_settings` (
  `username` varchar(64) NOT NULL,
  `settings_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Settings related to Designer';

-- --------------------------------------------------------

--
-- Table structure for table `pma__export_templates`
--

CREATE TABLE `pma__export_templates` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL,
  `export_type` varchar(10) NOT NULL,
  `template_name` varchar(64) NOT NULL,
  `template_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved export templates';

-- --------------------------------------------------------

--
-- Table structure for table `pma__favorite`
--

CREATE TABLE `pma__favorite` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Favorite tables';

-- --------------------------------------------------------

--
-- Table structure for table `pma__history`
--

CREATE TABLE `pma__history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db` varchar(64) NOT NULL DEFAULT '',
  `table` varchar(64) NOT NULL DEFAULT '',
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp(),
  `sqlquery` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='SQL history for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__navigationhiding`
--

CREATE TABLE `pma__navigationhiding` (
  `username` varchar(64) NOT NULL,
  `item_name` varchar(64) NOT NULL,
  `item_type` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Hidden items of navigation tree';

-- --------------------------------------------------------

--
-- Table structure for table `pma__pdf_pages`
--

CREATE TABLE `pma__pdf_pages` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `page_nr` int(10) UNSIGNED NOT NULL,
  `page_descr` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='PDF relation pages for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__recent`
--

CREATE TABLE `pma__recent` (
  `username` varchar(64) NOT NULL,
  `tables` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Recently accessed tables';

--
-- Dumping data for table `pma__recent`
--

INSERT INTO `pma__recent` (`username`, `tables`) VALUES
('root', '[{\"db\":\"walania\",\"table\":\"walania_attendance\"},{\"db\":\"walania\",\"table\":\"walania_registrant\"},{\"db\":\"walania\",\"table\":\"walania_event\"},{\"db\":\"walania\",\"table\":\"walania_managers\"},{\"db\":\"walania\",\"table\":\"walania_user\"},{\"db\":\"walania\",\"table\":\"walania_event_feedback\"},{\"db\":\"walania\",\"table\":\"walania_otp_logs\"},{\"db\":\"test_db\",\"table\":\"users\"},{\"db\":\"mysql\",\"table\":\"user\"},{\"db\":\"dbaccounts\",\"table\":\"walania_registrant\"}]');

-- --------------------------------------------------------

--
-- Table structure for table `pma__relation`
--

CREATE TABLE `pma__relation` (
  `master_db` varchar(64) NOT NULL DEFAULT '',
  `master_table` varchar(64) NOT NULL DEFAULT '',
  `master_field` varchar(64) NOT NULL DEFAULT '',
  `foreign_db` varchar(64) NOT NULL DEFAULT '',
  `foreign_table` varchar(64) NOT NULL DEFAULT '',
  `foreign_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Relation table';

-- --------------------------------------------------------

--
-- Table structure for table `pma__savedsearches`
--

CREATE TABLE `pma__savedsearches` (
  `id` int(5) UNSIGNED NOT NULL,
  `username` varchar(64) NOT NULL DEFAULT '',
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `search_name` varchar(64) NOT NULL DEFAULT '',
  `search_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Saved searches';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_coords`
--

CREATE TABLE `pma__table_coords` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `pdf_page_number` int(11) NOT NULL DEFAULT 0,
  `x` float UNSIGNED NOT NULL DEFAULT 0,
  `y` float UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table coordinates for phpMyAdmin PDF output';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_info`
--

CREATE TABLE `pma__table_info` (
  `db_name` varchar(64) NOT NULL DEFAULT '',
  `table_name` varchar(64) NOT NULL DEFAULT '',
  `display_field` varchar(64) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Table information for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__table_uiprefs`
--

CREATE TABLE `pma__table_uiprefs` (
  `username` varchar(64) NOT NULL,
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `prefs` text NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Tables'' UI preferences';

--
-- Dumping data for table `pma__table_uiprefs`
--

INSERT INTO `pma__table_uiprefs` (`username`, `db_name`, `table_name`, `prefs`, `last_update`) VALUES
('root', 'dblibrarysystem', 'tbl_students', '{\"sorted_col\":\"`tbl_students`.`course` ASC\"}', '2026-01-21 15:22:22'),
('root', 'walania', 'walania_registrant', '{\"CREATE_TIME\":\"2026-07-04 17:54:51\"}', '2026-07-04 10:23:33');

-- --------------------------------------------------------

--
-- Table structure for table `pma__tracking`
--

CREATE TABLE `pma__tracking` (
  `db_name` varchar(64) NOT NULL,
  `table_name` varchar(64) NOT NULL,
  `version` int(10) UNSIGNED NOT NULL,
  `date_created` datetime NOT NULL,
  `date_updated` datetime NOT NULL,
  `schema_snapshot` text NOT NULL,
  `schema_sql` text DEFAULT NULL,
  `data_sql` longtext DEFAULT NULL,
  `tracking` set('UPDATE','REPLACE','INSERT','DELETE','TRUNCATE','CREATE DATABASE','ALTER DATABASE','DROP DATABASE','CREATE TABLE','ALTER TABLE','RENAME TABLE','DROP TABLE','CREATE INDEX','DROP INDEX','CREATE VIEW','ALTER VIEW','DROP VIEW') DEFAULT NULL,
  `tracking_active` int(1) UNSIGNED NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Database changes tracking for phpMyAdmin';

-- --------------------------------------------------------

--
-- Table structure for table `pma__userconfig`
--

CREATE TABLE `pma__userconfig` (
  `username` varchar(64) NOT NULL,
  `timevalue` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `config_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User preferences storage for phpMyAdmin';

--
-- Dumping data for table `pma__userconfig`
--

INSERT INTO `pma__userconfig` (`username`, `timevalue`, `config_data`) VALUES
('root', '2026-07-05 23:22:51', '{\"Console\\/Mode\":\"collapse\"}');

-- --------------------------------------------------------

--
-- Table structure for table `pma__usergroups`
--

CREATE TABLE `pma__usergroups` (
  `usergroup` varchar(64) NOT NULL,
  `tab` varchar(64) NOT NULL,
  `allowed` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='User groups with configured menu items';

-- --------------------------------------------------------

--
-- Table structure for table `pma__users`
--

CREATE TABLE `pma__users` (
  `username` varchar(64) NOT NULL,
  `usergroup` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin COMMENT='Users and their assignments to user groups';

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pma__central_columns`
--
ALTER TABLE `pma__central_columns`
  ADD PRIMARY KEY (`db_name`,`col_name`);

--
-- Indexes for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `db_name` (`db_name`,`table_name`,`column_name`);

--
-- Indexes for table `pma__designer_settings`
--
ALTER TABLE `pma__designer_settings`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_user_type_template` (`username`,`export_type`,`template_name`);

--
-- Indexes for table `pma__favorite`
--
ALTER TABLE `pma__favorite`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__history`
--
ALTER TABLE `pma__history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `username` (`username`,`db`,`table`,`timevalue`);

--
-- Indexes for table `pma__navigationhiding`
--
ALTER TABLE `pma__navigationhiding`
  ADD PRIMARY KEY (`username`,`item_name`,`item_type`,`db_name`,`table_name`);

--
-- Indexes for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  ADD PRIMARY KEY (`page_nr`),
  ADD KEY `db_name` (`db_name`);

--
-- Indexes for table `pma__recent`
--
ALTER TABLE `pma__recent`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__relation`
--
ALTER TABLE `pma__relation`
  ADD PRIMARY KEY (`master_db`,`master_table`,`master_field`),
  ADD KEY `foreign_field` (`foreign_db`,`foreign_table`);

--
-- Indexes for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `u_savedsearches_username_dbname` (`username`,`db_name`,`search_name`);

--
-- Indexes for table `pma__table_coords`
--
ALTER TABLE `pma__table_coords`
  ADD PRIMARY KEY (`db_name`,`table_name`,`pdf_page_number`);

--
-- Indexes for table `pma__table_info`
--
ALTER TABLE `pma__table_info`
  ADD PRIMARY KEY (`db_name`,`table_name`);

--
-- Indexes for table `pma__table_uiprefs`
--
ALTER TABLE `pma__table_uiprefs`
  ADD PRIMARY KEY (`username`,`db_name`,`table_name`);

--
-- Indexes for table `pma__tracking`
--
ALTER TABLE `pma__tracking`
  ADD PRIMARY KEY (`db_name`,`table_name`,`version`);

--
-- Indexes for table `pma__userconfig`
--
ALTER TABLE `pma__userconfig`
  ADD PRIMARY KEY (`username`);

--
-- Indexes for table `pma__usergroups`
--
ALTER TABLE `pma__usergroups`
  ADD PRIMARY KEY (`usergroup`,`tab`,`allowed`);

--
-- Indexes for table `pma__users`
--
ALTER TABLE `pma__users`
  ADD PRIMARY KEY (`username`,`usergroup`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pma__bookmark`
--
ALTER TABLE `pma__bookmark`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__column_info`
--
ALTER TABLE `pma__column_info`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__export_templates`
--
ALTER TABLE `pma__export_templates`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pma__history`
--
ALTER TABLE `pma__history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__pdf_pages`
--
ALTER TABLE `pma__pdf_pages`
  MODIFY `page_nr` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pma__savedsearches`
--
ALTER TABLE `pma__savedsearches`
  MODIFY `id` int(5) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- Database: `test`
--
CREATE DATABASE IF NOT EXISTS `test` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `test`;
--
-- Database: `test_db`
--
CREATE DATABASE IF NOT EXISTS `test_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `test_db`;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
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
(15, 'KCIP-8393', 2, 'Maria', 'Clara', '2026-07-05 23:18:51');

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
(35, 'yeahlow24@gmail.com', '373748', 0, 5, 1, '2026-07-05 19:59:19', NULL, '2026-07-05 19:54:19', '2026-07-05 19:54:19');

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
(1058, 'YCPK-6688', 'Juan', 'Ramos', 'dela Cruz', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 1, '2026-07-04 18:38:50', 13, NULL),
(1059, 'RXVU-0433', 'Juan', 'Ramos', 'dela Cruz', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-04 19:59:51', 13, NULL),
(1060, 'OJWC-1204', 'Juan', 'Ramos', 'dela Cruz', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 1, '2026-07-05 19:34:16', 16, NULL),
(1061, 'MDDV-1543', 'Maria', NULL, 'Clara', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 1, '2026-07-05 19:36:33', 13, NULL),
(1062, 'YQDM-5961', 'John', NULL, 'Doe', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-05 19:42:12', 13, NULL),
(1063, 'QYKT-1652', 'John', NULL, 'Doe', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 0, '2026-07-05 19:42:22', 13, NULL),
(1064, 'KCIP-8393', 'Maria', NULL, 'Clara', '2000-01-01', 'yeahlow24@gmail.com', '09123456789', 1, '2026-07-05 19:54:19', 2, NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `walania_registrant`
--
ALTER TABLE `walania_registrant`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1065;

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
