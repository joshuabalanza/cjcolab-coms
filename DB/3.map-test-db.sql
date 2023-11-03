-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 03, 2023 at 04:33 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_coms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aid` int(11) NOT NULL,
  `aname` varchar(60) NOT NULL,
  `aemail` varchar(60) NOT NULL,
  `apassword` varchar(60) NOT NULL,
  `a_otp` varchar(255) NOT NULL,
  `a_activation_code` varchar(255) NOT NULL,
  `verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `aname`, `aemail`, `apassword`, `a_otp`, `a_activation_code`, `verified`) VALUES
(1, 'admin', 'coms.capstone@gmail.com', '$2y$10$yFUdCzPdevJdu2ibZYM1Du2.35VbvVcB0pE09zr9Y2wUMdmbUkd.m', '89471', '8amhbeo0n4li2d07gk1cjf', 1),
(2, 'system-admin', 'coms.system.adm@gmail.com', '$2y$10$a3rFEoeBUXfAp6Y9tiqEEO4eCu99t4pYCImyahbXN2OcW0pBW0iAW', '94357', 'gl9bfk4m9a2cendj1o36hi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `concourse_verification`
--

CREATE TABLE `concourse_verification` (
  `concourse_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `concourse_name` varchar(255) NOT NULL,
  `concourse_map` varchar(255) NOT NULL,
  `spaces` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `concourse_verification`
--

INSERT INTO `concourse_verification` (`concourse_id`, `owner_id`, `owner_name`, `concourse_name`, `concourse_map`, `spaces`, `created_at`, `status`) VALUES
(1, 1, 'Coms Owner', 'ced', '', 4, '2023-11-03 15:05:24', 'rejected'),
(2, 1, 'Coms Owner', 'asdgfd', '', 4, '2023-11-03 15:05:25', 'rejected'),
(3, 1, 'Coms Owner', 'pup', '6544f5167cb2a.png', 5, '2023-11-03 15:05:26', 'rejected'),
(4, 1, 'John Cedrick Garcia', 'gfd', '6544fb5992906.png', 6, '2023-11-03 15:29:12', 'rejected'),
(5, 1, 'John Cedrick Garcia', 'blach', '654504fc31f2a.jpg', 10, '2023-11-03 15:29:13', 'rejected'),
(6, 1, 'John Cedrick Garcia', '124', '65450d7fe37a0.png', 6, '2023-11-03 15:29:26', 'approved'),
(7, 1, 'John Cedrick Garcia', 'pup', '654511c0c3398.gif', 6, '2023-11-03 15:29:24', 'approved'),
(8, 1, 'John Cedrick Garcia', 'lagoon', '65451214de2ef.gif', 7, '2023-11-03 15:30:33', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `map`
--

CREATE TABLE `map` (
  `map_id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `owner_name` varchar(255) NOT NULL,
  `map_name` varchar(255) DEFAULT NULL,
  `map_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `spaces` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `map`
--

INSERT INTO `map` (`map_id`, `owner_id`, `owner_name`, `map_name`, `map_image`, `created_at`, `spaces`) VALUES
(1, 1, '', 'adsg', './uploads/concourse-1.png', '2023-11-01 06:57:11', 2);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` text NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('unread','read') NOT NULL DEFAULT 'unread',
  `notification_type` varchar(255) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(4) NOT NULL,
  `from_user` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `subject`, `message`, `status`, `notification_type`, `timestamp`, `active`, `from_user`) VALUES
(1, 1, '', 'Your verification has been approved.', 'unread', '', '2023-10-31 12:31:37', 0, NULL),
(2, 1, '', 'Your verification has been approved.', 'unread', '', '2023-10-31 12:46:21', 0, NULL),
(3, 1, '', 'Your verification has been approved.', 'unread', '', '2023-10-31 12:46:30', 0, NULL),
(4, 1, '', 'Your verification has been rejected.', 'unread', '', '2023-10-31 12:46:50', 0, NULL),
(5, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 12:52:02', 0, NULL),
(6, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 13:29:57', 0, NULL),
(36, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-10-31 16:04:41', 0, NULL),
(39, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 16:30:13', 0, NULL),
(40, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-10-31 16:30:14', 0, NULL),
(41, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 16:30:15', 0, NULL),
(42, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 16:34:45', 0, 'system-admin'),
(45, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 16:34:53', 0, 'system-admin'),
(46, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-10-31 16:34:54', 0, NULL),
(47, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 16:36:15', 0, 'system-admin'),
(48, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-10-31 16:36:16', 0, 'system-admin'),
(49, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 16:36:17', 0, 'system-admin'),
(50, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 16:50:35', 1, 'system-admin'),
(51, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-10-31 16:50:37', 0, 'system-admin'),
(52, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 16:50:37', 0, 'system-admin'),
(53, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-10-31 16:50:38', 0, 'system-admin'),
(54, 2, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 17:11:39', 1, 'system-admin'),
(55, 2, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-10-31 17:11:45', 1, 'system-admin'),
(56, 2, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-10-31 17:11:46', 1, 'system-admin'),
(57, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-01 06:10:11', 1, 'system-admin'),
(58, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-01 06:13:36', 1, 'system-admin'),
(59, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-01 06:14:48', 1, 'system-admin'),
(60, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-01 06:15:39', 1, 'system-admin'),
(61, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-01 06:29:54', 1, 'system-admin'),
(62, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-01 06:30:06', 1, 'system-admin'),
(63, 2, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-01 06:30:38', 1, 'system-admin'),
(64, 2, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-01 06:30:44', 1, 'system-admin'),
(65, 2, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-01 06:30:45', 1, 'system-admin'),
(66, 2, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-01 06:32:12', 1, 'system-admin'),
(67, 2, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-01 06:32:13', 1, 'system-admin'),
(68, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-01 06:55:54', 1, 'system-admin'),
(69, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-03 08:58:32', 1, 'system-admin'),
(70, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-03 08:58:33', 1, 'system-admin'),
(71, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-03 08:59:44', 1, 'system-admin'),
(72, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-03 09:00:49', 1, 'system-admin'),
(73, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-03 09:11:44', 1, 'system-admin'),
(74, 2, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-03 09:12:23', 1, 'system-admin'),
(75, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-03 09:19:31', 1, 'system-admin'),
(76, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-03 09:19:37', 1, 'system-admin'),
(77, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-03 09:20:13', 1, 'system-admin'),
(78, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-03 09:29:20', 1, 'system-admin'),
(79, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-03 09:29:27', 1, 'system-admin'),
(80, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-03 09:34:19', 1, 'system-admin'),
(81, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-03 09:41:16', 1, 'system-admin'),
(82, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-03 11:49:44', 1, 'system-admin'),
(83, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-03 11:54:08', 1, 'system-admin'),
(84, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP Verification', '2023-11-03 14:10:54', 1, 'system-admin'),
(85, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 14:12:13', 1, 'system-admin'),
(86, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 14:26:19', 1, 'system-admin'),
(87, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 14:32:15', 1, 'system-admin'),
(88, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 14:32:16', 0, 'system-admin'),
(89, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 14:34:43', 1, 'system-admin'),
(90, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 14:52:52', 1, 'system-admin'),
(91, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 14:52:54', 1, 'system-admin'),
(92, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 14:52:56', 1, 'system-admin'),
(93, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 14:52:57', 1, 'system-admin'),
(94, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 14:52:58', 1, 'system-admin'),
(95, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 14:52:59', 1, 'system-admin'),
(96, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:03:22', 1, 'system-admin'),
(97, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:03:44', 1, 'system-admin'),
(98, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:03:45', 1, 'system-admin'),
(99, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:03:46', 1, 'system-admin'),
(100, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:03:47', 1, 'system-admin'),
(101, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:04:05', 1, 'system-admin'),
(102, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:05:24', 1, 'system-admin'),
(103, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:05:25', 1, 'system-admin'),
(104, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:05:26', 1, 'system-admin'),
(105, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:05:27', 1, 'system-admin'),
(106, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:05:28', 1, 'system-admin'),
(107, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:07:48', 1, 'system-admin'),
(108, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:11:46', 1, 'system-admin'),
(109, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:16:57', 1, 'system-admin'),
(110, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:29:12', 1, 'system-admin'),
(111, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:29:13', 1, 'system-admin'),
(112, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:29:15', 1, 'system-admin'),
(113, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:29:24', 1, 'system-admin'),
(114, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:29:26', 1, 'system-admin'),
(115, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:30:33', 1, 'system-admin');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `uemail` varchar(255) NOT NULL,
  `upassword` varchar(255) NOT NULL,
  `uphone` int(20) NOT NULL,
  `utype` varchar(255) NOT NULL,
  `uimage` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `otp` varchar(255) NOT NULL,
  `activation_code` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `uname`, `uemail`, `upassword`, `uphone`, `utype`, `uimage`, `created_at`, `first_name`, `last_name`, `gender`, `birthday`, `address`, `otp`, `activation_code`, `status`, `verified`) VALUES
(1, 'Coms Owner', 'comsowner@gmail.com', '$2y$10$d1u2.EbIT48MYVfqHylzsOTBvUneJSSLUrIvEkf47TiFO1AN2GRfm', 0, 'Owner', 'uploads/profile/Logo-9b593c.png', '2023-10-29 08:58:13', '', '', NULL, NULL, '', '57981', 'inh7m7g9adc02jfk56leob', '', 1),
(2, 'Coms Tenant', 'comstenant@gmail.com', '$2y$10$Ba/h7WjCOZCFSrIKIIMcSuoiozGFJyTxZUVrKZp0pm3J0Euj8Q1NK', 0, 'Tenant', 'uploads/profile/Logo-9b593c.png', '2023-10-29 08:59:49', '', '', NULL, NULL, '', '86935', '2dm9f9hag9k9biclo8jen2', '', 1),
(3, 'iced', 'icedgarcia@gmail.com', '$2y$10$0r6xfY/c51D86rSyQiuvCuZYMl8X7PZvcPR0FwpkZztzKFicLNMf2', 0, 'Owner', '', '2023-11-01 12:15:22', '', '', NULL, NULL, '', '13542', '0c3dj1ab8eogf6li9hkm8n', '', 1),
(7, 'john ', 'icedtek@gmail.com', '$2y$10$mcVcKHeYs.jcwCYq1ZwCXecE6Xq2Fon68KM4reXmR4KWVymaXoDcC', 0, 'Tenant', '', '2023-11-03 12:11:39', '', '', NULL, NULL, '', '89243', '58fk5g2bm32oanhcj2deli', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_verification`
--

CREATE TABLE `user_verification` (
  `verification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `uemail` varchar(255) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `image_filename` varchar(255) DEFAULT NULL,
  `document_filename` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_verification`
--

INSERT INTO `user_verification` (`verification_id`, `user_id`, `uemail`, `first_name`, `last_name`, `address`, `gender`, `birthday`, `image_filename`, `document_filename`, `status`) VALUES
(1, 1, '', 'John Cedrick', 'Garcia', 'Arkong Bato Kapitolyo', 'Male', '2023-10-24', '6540f333dc76c.png', '6540f333dca95.pdf', 'approved'),
(2, 2, '', 'John Cedrick Tenant', 'Garcia', 'Arkong Bato Kapitolyo', 'Female', '2023-11-23', '65413523562b8.jpg', '654135235650b.pdf', 'rejected'),
(3, 3, '', 'John Cedric', 'Garci', 'Arkong Bato Kapitolyo', 'Male', '2023-11-09', '6544d2f1cd1e6.png', '6544d2f1cdabf.pdf', ''),
(4, 7, '', 'John Cedrick', 'Garcia', 'Arkong Bato Kapitolyo', 'Male', '2023-11-16', '6544e3987bca4.png', '6544e3987c1e3.pdf', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`);

--
-- Indexes for table `concourse_verification`
--
ALTER TABLE `concourse_verification`
  ADD PRIMARY KEY (`concourse_id`),
  ADD KEY `FK_concourse_user` (`owner_id`);

--
-- Indexes for table `map`
--
ALTER TABLE `map`
  ADD PRIMARY KEY (`map_id`),
  ADD KEY `fk_owner_id_user` (`owner_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`uid`),
  ADD UNIQUE KEY `uc_unique_email` (`uemail`);

--
-- Indexes for table `user_verification`
--
ALTER TABLE `user_verification`
  ADD PRIMARY KEY (`verification_id`),
  ADD UNIQUE KEY `unique_verification_id` (`verification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `concourse_verification`
--
ALTER TABLE `concourse_verification`
  MODIFY `concourse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `map`
--
ALTER TABLE `map`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=116;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_verification`
--
ALTER TABLE `user_verification`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `concourse_verification`
--
ALTER TABLE `concourse_verification`
  ADD CONSTRAINT `FK_concourse_user` FOREIGN KEY (`owner_id`) REFERENCES `user_verification` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
