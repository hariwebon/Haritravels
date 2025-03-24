-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 24, 2025 at 10:22 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `picture_password_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `drivers`
--

CREATE TABLE `drivers` (
  `id` int(11) NOT NULL,
  `driver_name` varchar(100) NOT NULL,
  `shift` varchar(20) NOT NULL,
  `basic_salary` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `drivers`
--

INSERT INTO `drivers` (`id`, `driver_name`, `shift`, `basic_salary`) VALUES
(1, 'AYYANAR G', 'Night', 17000.00),
(2, 'VELMURUGAN S', 'Night', 16000.00),
(3, 'VINOTH BABU J', 'Night', 16000.00),
(4, 'KALAIYARASAN B ', 'Night', 17000.00),
(5, 'SELVAGANAPATHI', 'Morning', 16000.00);

-- --------------------------------------------------------

--
-- Table structure for table `trip_details`
--

CREATE TABLE `trip_details` (
  `id` int(11) NOT NULL,
  `trip_date` date NOT NULL,
  `vehicle_no` varchar(50) NOT NULL,
  `driver_name` varchar(100) NOT NULL,
  `trip_type` enum('Drop','Pickup') NOT NULL,
  `location_from` varchar(100) NOT NULL DEFAULT 'Unknown',
  `location_to` varchar(100) NOT NULL DEFAULT 'Unknown'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trip_details`
--

INSERT INTO `trip_details` (`id`, `trip_date`, `vehicle_no`, `driver_name`, `trip_type`, `location_from`, `location_to`) VALUES
(1, '2025-01-01', 'TN 11 AA 4789', 'AYYANAR G', 'Pickup', 'mecheri', 'vee5'),
(2, '2025-01-01', 'TN 11 AA 4789', 'AYYANAR G', 'Drop', 'vee5', 'mecheri'),
(3, '2025-01-01', 'TN 14 C 0699', 'VELMURUGAN S', 'Pickup', 'new bus stand', 'vee7'),
(4, '2025-01-02', 'TN 14 C 0699', 'VELMURUGAN S', 'Drop', 'vee7', 'new bus stand');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `sequence` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `sequence`) VALUES
(1, 'admin', '[[3,4],[3,4],[3,4]]'),
(2, 'hari', '[[5,8],[5,8],[5,8]]'),
(3, 'admin1', '[[3,4],[3,4],[4,4]]'),
(4, 'aa', '[[3,4],[3,4],[4,4]]'),
(5, 'hariharan', '[[5,4],[5,5],[5,7]]'),
(6, 'RAM', '[[3,4],[3,4],[4,4]]');

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `id` int(11) NOT NULL,
  `vehicle_no` varchar(20) NOT NULL,
  `vehicle_age` int(11) NOT NULL,
  `insurance` varchar(3) NOT NULL,
  `pucc` varchar(3) NOT NULL,
  `tax` varchar(3) NOT NULL,
  `permit` varchar(3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vehicles`
--

INSERT INTO `vehicles` (`id`, `vehicle_no`, `vehicle_age`, `insurance`, `pucc`, `tax`, `permit`) VALUES
(1, 'TN 11 AA 4789', 7, 'Yes', 'Yes', 'Yes', 'Yes'),
(2, 'TN 14 C 0699', 9, 'Yes', 'Yes', 'Yes', 'Yes'),
(3, 'TN 14 D 4208', 9, 'Yes', 'Yes', 'Yes', 'Yes'),
(4, 'TN 90 E 8121', 5, 'Yes', 'Yes', 'Yes', 'Yes'),
(5, 'TN 11 V 8548', 8, 'Yes', 'Yes', 'Yes', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_driver_assignments`
--

CREATE TABLE `vehicle_driver_assignments` (
  `id` int(11) NOT NULL,
  `vehicle_no` varchar(50) NOT NULL,
  `driver_name` varchar(100) NOT NULL,
  `assigned_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `drivers`
--
ALTER TABLE `drivers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trip_details`
--
ALTER TABLE `trip_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vehicle_driver_assignments`
--
ALTER TABLE `vehicle_driver_assignments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `drivers`
--
ALTER TABLE `drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `trip_details`
--
ALTER TABLE `trip_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vehicle_driver_assignments`
--
ALTER TABLE `vehicle_driver_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
