-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2023 at 05:17 PM
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
-- Database: `coms`
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
-- Table structure for table `map`
--

CREATE TABLE `map` (
  `map_id` int(11) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `map_name` varchar(255) DEFAULT NULL,
  `map_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `spaces` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `map`
--

INSERT INTO `map` (`map_id`, `owner_id`, `map_name`, `map_image`, `created_at`, `spaces`) VALUES
(10, 19, 'asg', './uploads/floorplan.png', '2023-10-25 15:10:45', 2),
(11, 19, 'asg', './uploads/floorplan.png', '2023-10-25 15:10:47', 2),
(12, 19, 'PUP', './uploads/floorplan.png', '2023-10-25 15:11:09', 13);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `message`, `timestamp`) VALUES
(1, 0, 'Your verification has been rejected.', '2023-10-20 12:25:33'),
(2, 0, 'Your verification has been rejected.', '2023-10-20 12:26:47'),
(3, 0, 'Your verification has been rejected.', '2023-10-20 12:33:37'),
(4, 0, 'Your verification has been approved.', '2023-10-20 12:39:23'),
(5, 19, 'Your verification has been approved.', '2023-10-20 12:44:56'),
(6, 19, 'Your verification has been rejected.', '2023-10-20 14:36:51'),
(7, 19, 'Your verification has been approved.', '2023-10-20 14:48:50'),
(8, 19, 'Your verification has been rejected.', '2023-10-20 14:55:53'),
(9, 19, 'Your verification has been approved.', '2023-10-20 14:57:01'),
(10, 19, 'Your verification has been rejected.', '2023-10-20 15:08:47'),
(11, 19, 'Your verification has been approved.', '2023-10-20 15:09:18'),
(12, 19, 'Your verification has been approved.', '2023-10-21 05:04:16'),
(13, 19, 'Your verification has been rejected.', '2023-10-21 05:15:41'),
(14, 19, 'Your verification has been approved.', '2023-10-21 05:25:03'),
(15, 19, 'Your verification has been rejected.', '2023-10-21 05:26:10'),
(16, 19, 'Your verification has been approved.', '2023-10-21 05:35:38'),
(17, 34, 'Your verification has been approved.', '2023-10-21 06:36:44'),
(18, 34, 'Your verification has been rejected.', '2023-10-21 07:09:50'),
(19, 34, 'Your verification has been approved.', '2023-10-21 07:10:50'),
(20, 30, 'Your verification has been approved.', '2023-10-24 07:24:01'),
(21, 19, 'Your verification has been approved.', '2023-10-24 07:35:48'),
(22, 39, 'Your verification has been approved.', '2023-10-25 13:51:07');

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
(19, 'Johncedrick', 'icedgarcia@gmail.com', '$2y$10$ZoudOslTBCeeqw6XwhrkRuMyi5wWaMl4YJu9AKG9qRXjteJYXu12m', 123, 'Owner', 'uploads/profile/2by2.png', '2023-10-20 12:09:50', '', '', '', NULL, '', '20178', 'bn7okam8hg1ji3cde2l7f5', '', 1),
(21, 'Ced-PUP', 'jcmgarcia@iskolarngbayan.pup.edu.ph', '$2y$10$hURM5dRSmgXNdU3wE1PRUedIAlMt8Vx/M6yhkHP5nlhBjP9L7Ltia', 0, 'Owner', 'uploads/profile/2by2.png', '2023-10-18 04:24:59', '', '', '', NULL, '', '27381', 'ol1nih9c1kaf1413mjebdg', '', 1),
(30, 'icedtek', 'icedtek@gmail.com', '$2y$10$oB8n7EznzdI6jndCm8/rmeb6pDtgIDV9lpZGzDCyu5/ILEVuMCJoq', 2147483647, 'Tenant', 'uploads/profile/CEDRICK-PhotoRoom.png-PhotoRoom.png', '2023-10-21 06:14:31', '', '', '', NULL, '', '91260', 'jb3odmafelhi984cn88gk7', '', 1),
(31, 'rk', 'reikristianapanelo@iskolarngbayan.pup.edu.ph', '$2y$10$jocVBKy5VjabGUVUAvcJUOEozvxDBmVuAUyhywcSs0U9DIq.ElcIq', 0, 'Owner', '', '2023-10-18 14:10:11', '', '', '', NULL, '', '59418', 'hcadnmeo8i71gfklbj3271', '', 1),
(32, 'rk', 'rekristianapanelo@iskolarngbayan.pup.edu.ph', '$2y$10$yWQLr9e1roKs8AuINYT8Neb3D29GUJwdZSAQIJOL8JYQrGY4V7KJS', 0, 'Owner', '', '2023-10-18 14:11:09', '', '', '', NULL, '', '73094', 'f6dim2an256ebgkl26ohjc', '', 1),
(35, 'mayki', 'mikeenestanilao@iskolarngbayan.pup.edu.ph', '$2y$10$d6n6lIPpFKaVYW6pCXx4mOkXbhD3N0tqKXNEt2DaijNtK2SVfAX1O', 0, 'Owner', '', '2023-10-18 14:22:05', '', '', '', NULL, '', '63079', 'jl8nh72ieg28fok08dbcma', '', 1),
(36, 'roland', 'rabtugaoen@iskolarngbayan.pup.edu.ph', '$2y$10$T93WPUAVbQZ5J.AMMqzdluCfyKqyG9Yx1u1EMbJm1GbvDfYr2ojSq', 0, 'Owner', '', '2023-10-18 14:22:49', '', '', '', NULL, '', '14276', '2fln1ge7dkajm06ch36boi', '', 1),
(39, 'ced', 'cedrick.jcmg@gmail.com', '$2y$10$R1Tcvr4SrmUmF5gniWulFuAkrWShEVOh2zI7kmDILOs758MzwsPDq', 0, 'Tenant', 'uploads/profile/2by2.png', '2023-10-25 13:51:26', '', '', NULL, NULL, '', '95713', 'b6jil62e17f2onhkmg9acd', '', 1);

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
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_verification`
--

INSERT INTO `user_verification` (`verification_id`, `user_id`, `uemail`, `first_name`, `last_name`, `address`, `gender`, `birthday`, `image_filename`, `document_filename`, `status`) VALUES
(9, 19, '', 'John Cedrick', 'Garcia', 'Arkong Bato Kapitolyo', 'Male', '2023-10-24', '6537734986518.jpeg', '6537734986afa.pdf', 'approved'),
(10, 39, '', 'John Cedrick', 'Garcia', 'Arkong Bato Kapitolyo', 'Male', '2023-10-24', '65391d3b8946b.jpg', '65391d3b8984a.pdf', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`aid`);

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
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `map`
--
ALTER TABLE `map`
  MODIFY `map_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user_verification`
--
ALTER TABLE `user_verification`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `map`
--
ALTER TABLE `map`
  ADD CONSTRAINT `fk_owner_id_user` FOREIGN KEY (`owner_id`) REFERENCES `user` (`uid`);

--
-- Constraints for table `user_verification`
--
ALTER TABLE `user_verification`
  ADD CONSTRAINT `user_verification_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`uid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
