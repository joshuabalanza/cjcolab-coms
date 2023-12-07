-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2023 at 08:46 AM
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
-- Database: `test_coms`
--

-- --------------------------------------------------------

--
-- Table structure for table `accountant`
--

CREATE TABLE `accountant` (
  `act_id` int(11) NOT NULL,
  `acname` varchar(50) NOT NULL,
  `acemail` varchar(50) NOT NULL,
  `acusername` varchar(50) NOT NULL,
  `acpassword` varchar(255) NOT NULL,
  `actype` set('accountant') NOT NULL,
  `owner_id` int(11) NOT NULL,
  `acimage` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accountant`
--

INSERT INTO `accountant` (`act_id`, `acname`, `acemail`, `acusername`, `acpassword`, `actype`, `owner_id`, `acimage`, `created_at`) VALUES
(1, 'accountant name', 'maykililingg@gmail.com', 'act1', '$2y$10$GmT5u.64IXmcFQjeHPiMbO/zUjZSLqpxV/njeMEH4p1kxJVO5aWnC', 'accountant', 25, '', '2023-12-04 12:39:40');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `aid` int(11) NOT NULL,
  `aname` varchar(60) NOT NULL,
  `aemail` varchar(60) NOT NULL,
  `ausername` varchar(50) NOT NULL,
  `apassword` varchar(60) NOT NULL,
  `aimage` varchar(255) NOT NULL,
  `a_otp` varchar(255) NOT NULL,
  `a_activation_code` varchar(255) NOT NULL,
  `verified` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`aid`, `aname`, `aemail`, `ausername`, `apassword`, `aimage`, `a_otp`, `a_activation_code`, `verified`) VALUES
(1, 'admin', 'coms.capstone@gmail.com', 'admin1', '$2y$10$GmT5u.64IXmcFQjeHPiMbO/zUjZSLqpxV/njeMEH4p1kxJVO5aWnC', '', '89471', '8amhbeo0n4li2d07gk1cjf', 1),
(2, 'system-admin', 'coms.system.adm@gmail.com', 'admin2', '$2y$10$a3rFEoeBUXfAp6Y9tiqEEO4eCu99t4pYCImyahbXN2OcW0pBW0iAW', '../uploads/profile/iid.jpg', '94357', 'gl9bfk4m9a2cendj1o36hi', 1);

-- --------------------------------------------------------

--
-- Table structure for table `bill`
--

CREATE TABLE `bill` (
  `bill_id` int(11) NOT NULL,
  `space_id` int(11) DEFAULT NULL,
  `tenant_name` varchar(50) NOT NULL,
  `owner_name` varchar(50) NOT NULL,
  `tenant_email` varchar(50) NOT NULL,
  `electric` decimal(10,2) DEFAULT NULL,
  `water` decimal(10,2) DEFAULT NULL,
  `space_bill` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `notified` enum('notified','not notified') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bill`
--

INSERT INTO `bill` (`bill_id`, `space_id`, `tenant_name`, `owner_name`, `tenant_email`, `electric`, `water`, `space_bill`, `total`, `due_date`, `created_at`, `notified`) VALUES
(6, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 1200.00, 500.00, NULL, 1700.00, '2023-12-07', '2023-11-29 17:06:01', 'notified'),
(7, 30, 'Justin Bieber', '', 'maykililingg@gmail.com', 1500.00, 250.00, NULL, 1750.00, '2023-12-07', '2023-11-29 23:30:41', 'notified'),
(8, 30, 'Justin Bieber', '', 'maykililingg@gmail.com', 1250.00, 500.00, 1750.00, 3500.00, '2023-12-07', '2023-11-29 23:35:02', 'notified'),
(9, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 2300.00, 600.00, 1700.00, 4600.00, '2023-12-07', '2023-11-29 23:42:14', 'notified'),
(10, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 1233.00, 232.00, 4600.00, 6065.00, '2023-12-07', '2023-11-29 23:47:37', 'notified'),
(11, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 24.00, 60.00, 6065.00, 6149.00, '2023-12-12', '2023-12-04 17:41:11', 'notified'),
(12, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 24.00, 60.00, 6149.00, 6233.00, '2023-12-12', '2023-12-04 17:41:56', 'notified'),
(13, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 24.00, 60.00, 6233.00, 6317.00, '2023-12-12', '2023-12-04 17:43:16', 'notified'),
(14, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 1280.00, 120.00, 6317.00, 7717.00, '2023-12-12', '2023-12-04 17:47:29', 'notified'),
(15, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 25.60, 120.00, 7717.00, 7862.60, '2023-12-12', '2023-12-04 17:48:18', 'notified'),
(16, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 25.60, 90.00, 7862.60, 7978.20, '2023-12-12', '2023-12-04 17:50:38', 'notified'),
(17, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 12.80, 30.00, 7978.20, 8021.00, '2023-12-12', '2023-12-04 17:51:34', 'notified'),
(18, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 12.80, 30.00, 8021.00, 8063.80, '2023-12-12', '2023-12-04 17:51:56', 'notified'),
(19, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 12.80, 30.00, 8063.80, 8106.60, '2023-12-12', '2023-12-04 17:54:40', 'notified'),
(20, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 12.80, 30.00, 8106.60, 8149.40, '2023-12-12', '2023-12-04 17:56:38', 'notified'),
(21, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 38.40, 225.00, 8149.40, 8412.80, '2023-12-12', '2023-12-05 05:21:26', 'notified'),
(22, 29, 'Justin Bieber', 'Mayki Neri', 'maykililingg@gmail.com', 1280.00, 1710.00, 8412.80, 11402.80, '2023-12-12', '2023-12-05 05:23:55', 'notified');

-- --------------------------------------------------------

--
-- Table structure for table `billing_setup`
--

CREATE TABLE `billing_setup` (
  `BillingCode` varchar(250) NOT NULL,
  `BillingName` varchar(250) NOT NULL,
  `Amount` float NOT NULL,
  `DateAsOf` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `billing_setup`
--

INSERT INTO `billing_setup` (`BillingCode`, `BillingName`, `Amount`, `DateAsOf`) VALUES
('ElectricBillRate', 'Electric Bill Rate', 12.8, '2023-12-05 05:23:35'),
('WaterBillRate', 'Water Bill Rate', 45, '2023-12-05 05:23:35');

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
(19, 25, 'Mayki Neri', 'SPACESHIP', 'ANTIPOLO', '656766907f293.png', '', 10, 0, '2023-11-29 16:28:09', 'approved'),
(20, 25, 'Mayki Neri', 'concourse1', 'Sta mesa', '6567eee4b08c6.png', '', 6, 0, '2023-11-30 02:10:26', 'approved'),
(25, 25, 'Mayki Neri', 'Commercial Space', 'SJDM Bulacan', '656dc503bb3bd.jpg', '', 3, 0, '2023-12-04 12:24:35', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `contract`
--

CREATE TABLE `contract` (
  `contract_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `space_id` int(11) NOT NULL,
  `tenant_name` varchar(50) NOT NULL,
  `c_start` date NOT NULL,
  `c_end` date NOT NULL,
  `eoc_sendingdate` date NOT NULL,
  `eoc_sentemailstatus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contract`
--

INSERT INTO `contract` (`contract_id`, `tenant_id`, `space_id`, `tenant_name`, `c_start`, `c_end`, `eoc_sendingdate`, `eoc_sentemailstatus`) VALUES
(38, 0, 0, 'Justin Bieber', '2023-12-08', '2024-12-08', '2024-11-08', 0),
(39, 0, 20, 'Justin Bieber', '2023-12-08', '2024-12-08', '2024-11-08', 0),
(40, 0, 21, 'Justin Bieber', '2023-12-08', '2024-12-08', '2024-11-08', 1);

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
(227, 25, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-29 16:27:04', 1, ''),
(228, 25, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-29 16:28:09', 1, ''),
(229, 26, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-11-29 16:45:52', 1, ''),
(230, 25, '', 'Your Concourse application has been approved.', 'unread', 'MAP APPLICATION', '2023-11-30 02:10:26', 1, ''),
(231, 29, '', 'Your verification has been approved.', 'unread', 'Account Verification', '2023-12-03 13:38:52', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `space`
--

CREATE TABLE `space` (
  `space_id` int(11) NOT NULL,
  `concourse_id` int(11) DEFAULT NULL,
  `space_img` varchar(255) NOT NULL,
  `space_name` varchar(255) DEFAULT NULL,
  `space_owner` varchar(50) NOT NULL,
  `space_oemail` varchar(50) NOT NULL,
  `status` enum('available','reserved','occupied') NOT NULL,
  `space_price` int(11) NOT NULL,
  `space_width` float DEFAULT NULL,
  `space_length` float DEFAULT NULL,
  `space_height` float DEFAULT NULL,
  `space_area` float GENERATED ALWAYS AS (`space_width` * `space_length`) STORED,
  `space_dimension` varchar(255) GENERATED ALWAYS AS (concat(`space_width`,' x ',`space_length`,' x ',`space_height`)) STORED,
  `space_tenant` varchar(255) NOT NULL,
  `space_bill` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `space`
--

INSERT INTO `space` (`space_id`, `concourse_id`, `space_img`, `space_name`, `space_owner`, `space_oemail`, `status`, `space_price`, `space_width`, `space_length`, `space_height`, `space_tenant`, `space_bill`) VALUES
(29, 19, 'rent1.jpg', 'Space 1', 'Mayki Neri', 'maykililingg@gmail.com', 'occupied', 2500, 12, 23, 23, 'Justin Bieber', 11402.8),
(30, 19, 'rent.jpg', 'Space 2', 'Justin Bieber', 'maykililingg@gmail.com', 'occupied', 2500, 20, 20, 32, 'Justin Bieber', 3500),
(31, 20, 'rent1.jpg', 'Space 1', 'Mayki Neri', 'maykineri@gmail.com', 'occupied', 2500, 10, 10, 10, 'Justin Bieber', NULL),
(32, 20, 'rent.jpg', 'Space 2', 'Justin Bieber', 'maykililingg@gmail.com', 'occupied', 1000, 20, 20, 20, 'Justin Bieber', NULL),
(33, 19, '396495340_304330735783490_5286833404617194206_n.jpg', 'Space 3', 'Justin Bieber', 'maykililingg@gmail.com', 'occupied', 12500000, 500, 1000, 2000, 'Justin Bieber', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `space_application`
--

CREATE TABLE `space_application` (
  `app_id` int(11) NOT NULL,
  `tenantid` int(11) NOT NULL,
  `space_id` int(11) NOT NULL,
  `spacename` varchar(50) NOT NULL,
  `owner_name` varchar(50) NOT NULL,
  `tenant_name` varchar(50) NOT NULL,
  `ap_email` varchar(50) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `space_application`
--

INSERT INTO `space_application` (`app_id`, `tenantid`, `space_id`, `spacename`, `owner_name`, `tenant_name`, `ap_email`, `status`, `created_at`) VALUES
(21, 26, 33, 'Space 3', '', 'Justin Bieber', 'joyruayana@gmail.com', 'approved', '2023-12-06 06:51:02');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `uid` int(11) NOT NULL,
  `uname` varchar(255) NOT NULL,
  `uemail` varchar(255) NOT NULL,
  `username` varchar(50) NOT NULL,
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
  `otp_expiration` datetime DEFAULT NULL,
  `activation_code` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `uname`, `uemail`, `username`, `upassword`, `uphone`, `utype`, `uimage`, `created_at`, `first_name`, `last_name`, `gender`, `birthday`, `address`, `otp`, `otp_expiration`, `activation_code`, `status`, `verified`) VALUES
(25, 'Mayki Neri', 'maykineri@gmail.com', 'maykineri', '$2y$10$GmT5u.64IXmcFQjeHPiMbO/zUjZSLqpxV/njeMEH4p1kxJVO5aWnC', 0, 'Owner', '', '2023-12-03 06:51:39', '', '', NULL, NULL, '', '51798', '2023-11-29 17:24:16', '8k4bcn2f468odhagemlji9', '', 1),
(26, 'Justin Bieber', 'maykililingg@gmail.com', 'jbee', '$2y$10$1rU39BEERRUaTK0X.ZowfOr/YgU6g0wl8UdCeqCipTXpyN5LdPit6', 0, 'Tenant', 'uploads/profile/jbid.jpg', '2023-11-29 16:51:52', '', '', NULL, NULL, '', '49287', '2023-11-29 17:44:14', 'hbaiem2cdjlk89f775g5on', '', 1),
(27, 'Rachel Nayre', 'mikeeneri6@gmail.com', 'owner01', '$2y$10$wtthtNscmf3NlLiWKiPSeOGUJPOJp17hcLIbIEE3dZcJJCugHRbzO', 0, 'Owner', '', '2023-11-30 02:08:11', '', '', NULL, NULL, '', '48567', '2023-11-30 03:11:11', '2ibdo4n1e0a2jkhlmg9c2f', '', 0),
(28, 'carla', 'cjru@gmail.com', 'cjruru', '$2y$10$zxxvrfnoGlDE8LMhkXPs5uxWl0QaSC1tX2WtDVEm68PG/DYviMCFu', 0, 'Owner', '', '2023-12-02 23:34:10', '', '', NULL, NULL, '', '42936', '2023-12-03 07:35:10', 'h6j5e2bn85lcgdok0ima6f', '', 0);

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
(7, 25, '', 'Mayki', 'Neri', 'Antipolo', 'Female', '2002-01-03', '65676631a6457.jpg', '65676631a68e3.pdf', 'approved'),
(8, 26, '', 'Justin', 'Bieber', 'Quezon', 'Male', '1995-05-01', '65676ab75c168.jpg', '65676ab75c851.pdf', 'approved'),
(9, 29, '', 'Carla Joy', 'Ruayana', 'Blk 17 Lot 30 Northridge Prime Estate', 'Female', '1999-02-01', '656c84b95790c.jpg', '656c84b957bb7.pdf', 'approved');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bill`
--
ALTER TABLE `bill`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `space_id` (`space_id`);

--
-- Indexes for table `concourse_verification`
--
ALTER TABLE `concourse_verification`
  ADD PRIMARY KEY (`concourse_id`),
  ADD KEY `FK_concourse_user` (`owner_id`);

--
-- Indexes for table `contract`
--
ALTER TABLE `contract`
  ADD PRIMARY KEY (`contract_id`);

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
-- Indexes for table `space_application`
--
ALTER TABLE `space_application`
  ADD PRIMARY KEY (`app_id`);

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
-- AUTO_INCREMENT for table `bill`
--
ALTER TABLE `bill`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `concourse_verification`
--
ALTER TABLE `concourse_verification`
  MODIFY `concourse_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `contract`
--
ALTER TABLE `contract`
  MODIFY `contract_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=232;

--
-- AUTO_INCREMENT for table `space`
--
ALTER TABLE `space`
  MODIFY `space_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `space_application`
--
ALTER TABLE `space_application`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `uid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `user_verification`
--
ALTER TABLE `user_verification`
  MODIFY `verification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bill`
--
ALTER TABLE `bill`
  ADD CONSTRAINT `bill_ibfk_1` FOREIGN KEY (`space_id`) REFERENCES `space` (`space_id`);

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
