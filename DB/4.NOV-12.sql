-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 12, 2023 at 12:53 PM
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
  `concourse_address` varchar(255) NOT NULL,
  `concourse_map` varchar(255) NOT NULL,
  `concourse_image` varchar(255) NOT NULL,
  `spaces` int(11) NOT NULL,
  `concourse_total_area` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `concourse_verification`
--

INSERT INTO `concourse_verification` (`concourse_id`, `owner_id`, `owner_name`, `concourse_name`, `concourse_address`, `concourse_map`, `concourse_image`, `spaces`, `concourse_total_area`, `created_at`, `status`) VALUES
(1, 1, 'Coms Owner', 'ced', '', '', '', 4, 0, '2023-11-07 14:56:10', 'rejected'),
(2, 1, 'Coms Owner', 'asdgfd', '', '', '', 4, 0, '2023-11-07 14:56:12', 'rejected'),
(3, 1, 'Coms Owner', 'pup', '', '6544f5167cb2a.png', '', 5, 0, '2023-11-07 14:56:13', 'rejected'),
(4, 1, 'John Cedrick Garcia', 'gfd', '', '6544fb5992906.png', '', 6, 0, '2023-11-07 14:56:14', 'rejected'),
(5, 1, 'John Cedrick Garcia', 'blach', '', '654504fc31f2a.jpg', '', 10, 0, '2023-11-07 14:56:16', 'rejected'),
(6, 1, 'John Cedrick Garcia', '124', '', '65450d7fe37a0.png', '', 6, 0, '2023-11-07 14:56:17', 'rejected'),
(7, 1, 'John Cedrick Garcia', 'pup', '', '654511c0c3398.gif', '', 6, 0, '2023-11-08 03:08:00', 'rejected'),
(8, 1, 'John Cedrick Garcia', 'lagoon', '', '65451214de2ef.gif', '', 7, 0, '2023-11-07 14:56:20', 'rejected'),
(9, 3, 'John Cedric Garci', 'ced', '', '6545c67f3b2ab.png', '', 20, 0, '2023-11-07 14:56:21', 'rejected'),
(10, 3, 'John Cedric Garci', 'cedrick', '', '6545c6c8ebcd1.jpg', '', 24, 0, '2023-11-08 03:07:57', 'rejected'),
(11, 1, 'John Cedrick Garcia', 'PUP Lagoon', '', '654a50547ec39.gif', '654b66251c387.jpg', 40, 400, '2023-11-08 10:42:45', 'approved'),
(12, 1, 'John Cedrick Garcia', 'PUP Lagoon', '1016 Anonas, Santa Mesa, Maynila, Kalakhang Maynila', '654a53dfee7e9.gif', '654b6748bab68.png', 40, 1000, '2023-11-08 10:47:36', 'approved'),
(13, 3, 'John Cedric Garci', 'Concourse 1', '#26 San Ignacio St. Kapitolyo Pasig City', '654b00bcf3abd.png', '', 30, 0, '2023-11-08 03:30:16', 'approved'),
(14, 1, 'John Cedrick Garcia', 'New Area', 'Area', '654b46329c4d4.png', '', 20, 0, '2023-11-08 08:26:48', 'approved'),
(15, 1, 'John Cedrick Garcia', 'Ced Concourse', '#26 San Ignacio St. Kapitolyo Pasig City', '654b6de50e906.png', '', 40, 0, '2023-11-08 11:16:18', 'approved'),
(16, 8, 'ced john', 'Sample Concourse 1', 'pasig city', '654ba46f9c178.png', '654ba4ba23588.jpg', 30, 500, '2023-11-08 15:09:46', 'approved');

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
(106, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:05:28', 1, 'system-admin'),
(107, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:07:48', 1, 'system-admin'),
(108, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:11:46', 1, 'system-admin'),
(109, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-03 15:16:57', 1, 'system-admin'),
(110, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:29:12', 1, 'system-admin'),
(111, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:29:13', 1, 'system-admin'),
(112, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-03 15:29:15', 0, 'system-admin'),
(116, 3, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-04 04:13:54', 1, 'system-admin'),
(117, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 04:20:22', 1, 'system-admin'),
(118, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 04:21:42', 1, 'system-admin'),
(119, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 04:29:31', 1, 'system-admin'),
(120, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 04:29:33', 1, 'system-admin'),
(121, 3, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-04 04:31:44', 1, 'system-admin'),
(122, 3, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-04 04:31:51', 1, 'system-admin'),
(123, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 04:32:35', 1, 'system-admin'),
(124, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 04:32:36', 1, 'system-admin'),
(125, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 04:32:37', 1, 'system-admin'),
(126, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 04:32:45', 1, 'system-admin'),
(127, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 04:35:41', 1, 'system-admin'),
(128, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 04:35:47', 1, 'system-admin'),
(129, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 04:36:15', 1, 'system-admin'),
(130, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 04:36:20', 1, 'system-admin'),
(131, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 04:36:21', 1, 'system-admin'),
(132, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-04 04:59:46', 1, 'system-admin'),
(133, 3, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-04 05:00:31', 1, 'system-admin'),
(134, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-04 05:00:44', 1, 'system-admin'),
(135, 2, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-04 05:00:45', 1, 'system-admin'),
(136, 3, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-04 05:00:47', 1, 'system-admin'),
(137, 1, '', 'Your verification has been rejected.', 'unread', 'Account Verification', '2023-11-04 05:24:03', 1, 'system-admin'),
(138, 1, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-04 05:24:08', 1, 'system-admin'),
(139, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 05:24:17', 1, 'system-admin'),
(140, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 05:24:19', 1, 'system-admin'),
(141, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 05:24:20', 1, 'system-admin'),
(142, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:24:44', 1, 'system-admin'),
(143, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:24:46', 1, 'system-admin'),
(144, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:24:47', 1, 'system-admin'),
(145, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:36:45', 1, 'system-admin'),
(146, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:36:46', 1, 'system-admin'),
(147, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:36:47', 1, 'system-admin'),
(148, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 05:43:03', 1, 'system-admin'),
(149, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 05:43:05', 1, 'system-admin'),
(150, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:56:53', 1, 'system-admin'),
(151, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:56:54', 1, 'system-admin'),
(152, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:56:55', 1, 'system-admin'),
(153, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:56:56', 1, 'system-admin'),
(154, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 05:56:57', 1, 'system-admin'),
(155, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:13', 1, 'system-admin'),
(156, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:14', 1, 'system-admin'),
(157, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:15', 1, 'system-admin'),
(158, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:18', 1, 'system-admin'),
(159, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:19', 1, 'system-admin'),
(160, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:20', 1, 'system-admin'),
(161, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:21', 1, 'system-admin'),
(162, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:22', 1, 'system-admin'),
(163, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:24', 1, 'system-admin'),
(164, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:05:25', 1, 'system-admin'),
(165, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:30', 1, 'system-admin'),
(166, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:31', 1, 'system-admin'),
(167, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:32', 1, 'system-admin'),
(168, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:34', 1, 'system-admin'),
(169, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:35', 1, 'system-admin'),
(170, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:37', 1, 'system-admin'),
(171, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:38', 1, 'system-admin'),
(172, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:39', 1, 'system-admin'),
(173, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:39', 1, 'system-admin'),
(174, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:41', 1, 'system-admin'),
(175, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:08:42', 1, 'system-admin'),
(176, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:24', 1, 'system-admin'),
(177, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:25', 1, 'system-admin'),
(178, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:27', 1, 'system-admin'),
(179, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:28', 1, 'system-admin'),
(180, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:29', 1, 'system-admin'),
(181, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:30', 1, 'system-admin'),
(182, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:31', 1, 'system-admin'),
(183, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:33', 1, 'system-admin'),
(184, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:34', 1, 'system-admin'),
(185, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:35', 1, 'system-admin'),
(186, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:36', 1, 'system-admin'),
(187, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:42', 1, 'system-admin'),
(188, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:43', 1, 'system-admin'),
(189, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:44', 1, 'system-admin'),
(190, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:45', 1, 'system-admin'),
(191, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:57', 1, 'system-admin'),
(192, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:32:59', 1, 'system-admin'),
(193, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:33:01', 1, 'system-admin'),
(194, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:33:03', 1, 'system-admin'),
(195, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:33:04', 1, 'system-admin'),
(196, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-04 06:33:25', 1, 'system-admin'),
(197, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:10', 1, 'system-admin'),
(198, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:12', 1, 'system-admin'),
(199, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:13', 1, 'system-admin'),
(200, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:14', 1, 'system-admin'),
(201, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:16', 1, 'system-admin'),
(202, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:17', 1, 'system-admin'),
(203, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:19', 1, 'system-admin'),
(204, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:20', 1, 'system-admin'),
(205, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:21', 1, 'system-admin'),
(206, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 14:56:23', 1, 'system-admin'),
(207, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-07 14:57:36', 1, 'system-admin'),
(208, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-07 15:11:21', 1, 'system-admin'),
(209, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-07 15:12:48', 1, 'system-admin'),
(210, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-08 03:07:55', 1, 'system-admin'),
(211, 3, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-08 03:07:57', 1, 'system-admin'),
(212, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-08 03:07:59', 1, 'system-admin'),
(213, 1, '', 'Your Concourse application has been rejected.', 'unread', 'MAP APPLICATION', '2023-11-08 03:08:00', 1, 'system-admin'),
(214, 3, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-08 03:30:16', 1, 'system-admin'),
(215, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-08 06:52:36', 1, 'system-admin'),
(216, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-08 08:26:48', 1, 'system-admin'),
(217, 1, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-08 11:16:18', 1, 'system-admin'),
(218, 8, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-08 15:06:56', 1, 'system-admin'),
(219, 8, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-08 15:09:02', 1, 'system-admin');

-- --------------------------------------------------------

--
-- Table structure for table `space`
--

CREATE TABLE `space` (
  `space_id` int(11) NOT NULL,
  `concourse_id` int(11) DEFAULT NULL,
  `space_name` varchar(255) DEFAULT NULL,
  `space_width` float DEFAULT NULL,
  `space_length` float DEFAULT NULL,
  `space_height` float DEFAULT NULL,
  `space_area` float GENERATED ALWAYS AS (`space_width` * `space_length`) STORED,
  `space_dimension` varchar(255) GENERATED ALWAYS AS (concat(`space_width`,' x ',`space_length`,' x ',`space_height`)) STORED,
  `space_status` enum('available','reserved','occupied') DEFAULT 'available',
  `space_tenant` varchar(255) DEFAULT NULL,
  `space_bill` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(7, 'john ', 'icedtek@gmail.com', '$2y$10$mcVcKHeYs.jcwCYq1ZwCXecE6Xq2Fon68KM4reXmR4KWVymaXoDcC', 0, 'Tenant', '', '2023-11-03 12:11:39', '', '', NULL, NULL, '', '89243', '58fk5g2bm32oanhcj2deli', '', 1),
(8, 'cedrick', 'cedrick.jcmg@gmail.com', '$2y$10$gMNw0qZgrbjGucSWIRg3oOevIPmcH33wztQKkwm55jYu4iXq4XIhy', 0, 'Owner', '', '2023-11-08 15:05:27', '', '', NULL, NULL, '', '42853', '8oahj5bgmfck8d66i8nle2', '', 1);

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
(2, 2, '', 'John Cedrick Tenant', 'Garcia', 'Arkong Bato Kapitolyo', 'Female', '2023-11-23', '65413523562b8.jpg', '654135235650b.pdf', 'approved'),
(3, 3, '', 'John Cedric', 'Garci', 'Arkong Bato Kapitolyo', 'Male', '2023-11-09', '6544d2f1cd1e6.png', '6544d2f1cdabf.pdf', 'approved'),
(4, 7, '', 'John Cedrick', 'Garcia', 'Arkong Bato Kapitolyo', 'Male', '2023-11-16', '6544e3987bca4.png', '6544e3987c1e3.pdf', ''),
(5, 8, '', 'ced', 'john', 'aghjghj', 'Male', '2023-11-09', '654ba3fb38ff2.jpg', '654ba3fb39565.pdf', 'approved');

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
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Indexes for table `space`
--
ALTER TABLE `space`
  ADD PRIMARY KEY (`space_id`),
  ADD KEY `concourse_id` (`concourse_id`);

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
  MODIFY `concourse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=220;

--
-- AUTO_INCREMENT for table `space`
--
ALTER TABLE `space`
  MODIFY `space_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `user_verification`
--
ALTER TABLE `user_verification`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `concourse_verification`
--
ALTER TABLE `concourse_verification`
  ADD CONSTRAINT `FK_concourse_user` FOREIGN KEY (`owner_id`) REFERENCES `user_verification` (`user_id`);

--
-- Constraints for table `space`
--
ALTER TABLE `space`
  ADD CONSTRAINT `space_ibfk_1` FOREIGN KEY (`concourse_id`) REFERENCES `concourse_verification` (`concourse_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
