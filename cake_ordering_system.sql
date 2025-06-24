-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 27, 2025 at 01:24 PM
-- Server version: 10.1.34-MariaDB
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cake_ordering_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `admin_logs`
--

CREATE TABLE `admin_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bakers`
--

CREATE TABLE `bakers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `role` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cakes`
--

CREATE TABLE `cakes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `flavor` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `cake_gallery`
--

CREATE TABLE `cake_gallery` (
  `id` int(11) NOT NULL,
  `cake_name` varchar(100) NOT NULL,
  `flavor` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `uploaded_at` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `cake_gallery`
--

INSERT INTO `cake_gallery` (`id`, `cake_name`, `flavor`, `price`, `image_path`, `uploaded_at`) VALUES
(1, 'Chocolate Delight', 'chocolate', '15000.00', 'uploads/1747981892_HOUSEHOLD.jpg', '2025-05-23 09:31:32'),
(2, 'Red Velvet Bliss', 'sratwaberr', '15000.00', 'uploads/1747982053_tafeslogo.png', '2025-05-23 09:34:13'),
(3, 'Chocolate Delight', 'nanasi', '18000.00', 'uploads/1747982186_Untitled Diagram.jpg', '2025-05-23 09:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `custom_requests`
--

CREATE TABLE `custom_requests` (
  `id` int(11) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `flavor` varchar(50) DEFAULT NULL,
  `instructions` text,
  `delivery_date` date DEFAULT NULL,
  `delivery_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `custom_requests`
--

INSERT INTO `custom_requests` (`id`, `first_name`, `last_name`, `flavor`, `instructions`, `delivery_date`, `delivery_type`, `created_at`) VALUES
(1, 'christian', 'mlay', 'chocolate', 'happy', '2025-05-22', 'delivery', '2025-05-22 09:28:48'),
(2, 'christian', 'mlay', 'chocolate', 'happy', '2025-05-22', 'pickup', '2025-05-22 10:11:51');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_partners`
--

CREATE TABLE `delivery_partners` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `vehicle` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `status` enum('on duty','off duty') DEFAULT 'off duty'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `ingredient` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `cake_name` varchar(100) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `price` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `required_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Ordered',
  `status_updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `cake_name`, `customer_name`, `price`, `phone`, `payment_method`, `created_at`, `required_date`, `status`, `status_updated_at`) VALUES
(1, 'Chocolate Delight', 'mlay', '15000', '0618750312', 'airtel', '2025-05-22 11:44:49', NULL, 'Ordered', NULL),
(2, 'Chocolate Delight', 'gyhn123', '15000', '1234567890', 'mpesa', '2025-05-22 11:45:12', NULL, 'Ordered', NULL),
(3, 'Chocolate Delight', 'zulfa', '15000', '0987654321', 'tigo', '2025-05-22 12:09:01', NULL, 'Ordered', NULL),
(4, 'Chocolate Delight', 'big', '15000', '0987654322', 'mpesa', '2025-05-22 12:47:03', '0000-00-00', 'Ordered', NULL),
(5, 'Red Velvet Bliss', 'habibu', '18000', '0987654322', 'airtel', '2025-05-22 12:48:48', '0000-00-00', 'Ordered', NULL),
(6, 'Chocolate Delight', 'mary', '15000', '0987654322', 'tigo', '2025-05-22 12:54:26', '0000-00-00', 'Ordered', NULL),
(7, 'Chocolate Delight', 'pet', '15000', '0987654321', 'tigo', '2025-05-22 12:58:54', '2025-05-30', 'Ordered', NULL),
(8, 'Chocolate Delight', 'janet', '15000', '0987654320', 'airtel', '2025-05-22 14:03:43', '2025-05-29', 'Ordered', NULL),
(9, 'delight strawberry', 'rebecca', '20000', '0618750312', 'mpesa', '2025-05-23 05:45:46', '2025-05-23', 'Completed', '2025-05-23 08:15:07'),
(10, 'Red Velvet Bliss', 'maki', '15000', '0718962583', 'M-Pesa', '2025-05-23 06:05:19', '2025-05-28', 'Received', '2025-05-23 09:05:53'),
(11, 'Chocolate Delight', 'kiju', '18000', '0718962583', 'Cash', '2025-05-23 06:11:00', '2025-05-31', 'Completed', '2025-05-23 09:42:55'),
(12, 'Chocolate Delight', 'petram', '18000.00', '0987654323', 'mpesa', '2025-05-23 08:43:41', '2025-05-28', 'Completed', '2025-05-23 10:44:40');

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` enum('open','in_progress','closed') DEFAULT 'open',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `customer_name`, `email`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 'christian', 'chrisbetuelmlay@gmail.com', 'badewy', 'ewy6rjutdx', 'open', '2025-05-22 09:17:54'),
(2, 'christian m', 'chrisbetuellmlay@gmail.com', 'no sugar', 'cake kjyugytdtdsrd', '', '2025-05-22 11:25:45');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `bakers`
--
ALTER TABLE `bakers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cakes`
--
ALTER TABLE `cakes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cake_gallery`
--
ALTER TABLE `cake_gallery`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `custom_requests`
--
ALTER TABLE `custom_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `delivery_partners`
--
ALTER TABLE `delivery_partners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `admin_logs`
--
ALTER TABLE `admin_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bakers`
--
ALTER TABLE `bakers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cakes`
--
ALTER TABLE `cakes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cake_gallery`
--
ALTER TABLE `cake_gallery`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `custom_requests`
--
ALTER TABLE `custom_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery_partners`
--
ALTER TABLE `delivery_partners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_logs`
--
ALTER TABLE `admin_logs`
  ADD CONSTRAINT `admin_logs_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
