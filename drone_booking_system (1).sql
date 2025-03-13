-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 03:53 AM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `drone_booking_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `air_corridors`
--

CREATE TABLE `air_corridors` (
  `id` int(11) NOT NULL,
  `start_point` varchar(100) DEFAULT NULL,
  `end_point` varchar(100) DEFAULT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `status` enum('available','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `air_corridors`
--

INSERT INTO `air_corridors` (`id`, `start_point`, `end_point`, `height`, `status`) VALUES
(1, 'point 1', 'point 2', '45.00', 'available'),
(2, 'Point 2', 'Point 3', '80.00', 'available');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `drone_id` int(11) DEFAULT NULL,
  `corridor_id` int(11) DEFAULT NULL,
  `load_weight` decimal(5,2) DEFAULT NULL,
  `risk_level` enum('low','medium','high') DEFAULT NULL,
  `urgency` enum('low','medium','high') DEFAULT NULL,
  `status` enum('pending','approved','completed') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `drone_id`, `corridor_id`, `load_weight`, `risk_level`, `urgency`, `status`) VALUES
(2, 2, 3, 2, '5.00', 'high', 'medium', 'approved'),
(3, 4, 1, 1, '7.00', 'medium', 'medium', 'approved'),
(5, 6, 1, 2, '2.00', 'high', 'medium', 'approved'),
(6, 8, 1, 1, '80.00', 'high', 'medium', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `drones`
--

CREATE TABLE `drones` (
  `id` int(11) NOT NULL,
  `type` varchar(100) DEFAULT NULL,
  `manufacturer` varchar(100) DEFAULT NULL,
  `max_load` decimal(5,2) DEFAULT NULL,
  `speed` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `drones`
--

INSERT INTO `drones` (`id`, `type`, `manufacturer`, `max_load`, `speed`) VALUES
(1, 'Draganfly Heavy Lift Drone', 'DroneBot Private Limited', '31.00', '79.20'),
(3, 'Harris Aerial Carrier H6 HL', 'Flying Rijin Drones', '40.00', '54.00');

-- --------------------------------------------------------

--
-- Table structure for table `flight_status`
--

CREATE TABLE `flight_status` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Eddy Gomez', 'eddy01@gmail.comm', '$2y$10$eZdGtn/2X2xgdodIAM5VVOztLbi0eEhaYmWISrOP7CoRjeAhXYiMi', 'user'),
(2, 'Jhon Smith', 'jhon01@gmail.com', '$2y$10$sxbYlwlnTBYe88Xwie5dVe/CObmsBStUkzpXU4ManAqM5EELSrQ.6', 'admin'),
(4, 'Dolly Harlod', 'dolly@gmail.com', '$2y$10$DsmXu7cEoogVrWPcVMfa9OyfWJ5SrqktNNofs2yzulId4j.DH.uAO', 'user'),
(5, 'Fatima Khan', 'fatima01@gmail.com', '$2y$10$AjZvIiOMA/Qe8mXo2O8KSOVAzJEvuiZYrVjXYhnKrRtRY3IMiksv6', 'user'),
(6, 'Dorothi ', 'doro01@gmail.com', '$2y$10$KNEMk5OLYXiTHqTnV/SKX.AvvAUD1BJdfOKivFu7iGqEsBiKmsgGa', 'user'),
(7, 'User', 'user@gmail.com', '$2y$10$zdi1raX2/RmDDGNEV6I.ruZgANz7JTIJynSyCDkacU1bClkEIbPnS', 'user'),
(8, 'sex', 'sex@gmail.com', '', 'admin'),
(9, 'kaushik', 'kaushik0077dey@gmail.com', '$2y$10$zWQQo86MSC6giyPzcRaSherSvFsDumFiKygTeL13ZfCzpOTgs7dH2', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `air_corridors`
--
ALTER TABLE `air_corridors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `drone_id` (`drone_id`),
  ADD KEY `corridor_id` (`corridor_id`);

--
-- Indexes for table `drones`
--
ALTER TABLE `drones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `flight_status`
--
ALTER TABLE `flight_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `air_corridors`
--
ALTER TABLE `air_corridors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `drones`
--
ALTER TABLE `drones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `flight_status`
--
ALTER TABLE `flight_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`drone_id`) REFERENCES `drones` (`id`),
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`corridor_id`) REFERENCES `air_corridors` (`id`);

--
-- Constraints for table `flight_status`
--
ALTER TABLE `flight_status`
  ADD CONSTRAINT `flight_status_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
