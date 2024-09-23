-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2024 at 09:14 AM
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
-- Database: `api_e`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` bigint(10) NOT NULL,
  `fullname` varchar(50) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(60) NOT NULL DEFAULT '',
  `password` varchar(60) NOT NULL DEFAULT '',
  `code` int(6) NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp(),
  `gender_id` tinyint(1) NOT NULL DEFAULT 0,
  `role_id` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `fullname`, `email`, `username`, `password`, `code`, `updated`, `created`, `gender_id`, `role_id`) VALUES
(25, 'eeshan', 'eeshan.vaghjiani@strathmore.edu', 'Eeshan04', '$2y$10$MoF0gYwHEA7FOUtz5eqsz.nckWwy1kDExYP6yWDUM7iHeS0EEUYgC', 427488, '2024-09-23 09:54:03', '2024-09-21 15:37:41', 0, 0),
(27, 'eeshan', 'evaghjiani@gmail.com', 'Eeshan', '$2y$10$RjPoqH.E4ZgxjwdBWOnj9Onc5Wd8dmqbVSvXlfU1Y2z6ms/k3r.qK', 727343, '2024-09-23 10:13:31', '2024-09-21 16:06:27', 0, 0),
(28, 'bhavin', 'bhavin.mepani@strathmore.edu', 'bhavin@lodu', '$2y$10$Iy35imTO2Kj6QEzar8mskuE24lYyfSdvt72cY943uLJ7e3fLMPHQ6', 459590, '2024-09-23 10:14:40', '2024-09-21 16:21:05', 1, 0),
(29, 'dhruvin', 'dhruvin.bhudia@strathmore.edu', 'dhruvin', '$2y$10$7ZEb.y85yLCy6igeTd.gpuWlw7I8cR85Wv8f/EtlGFkRNIdcrUuOW', 403929, '2024-09-23 09:53:41', '2024-09-21 16:26:35', 0, 0),
(30, 'Bipin Vaghjiani', 'bvaghjiani@gmail.com', 'bvaghjiani', '$2y$10$/zekEiXuxfHNvryywWgwlu38DjOAIZQy2YHjy3R7jfKbUdImqO8C6', 0, '2024-09-21 16:36:39', '2024-09-21 16:35:25', 0, 0),
(31, 'Krishna', 'krishina.madhaparia@strathmore.edu', 'krishna', '$2y$10$WI3Zo13Y4AkJUISTioQcdOqcLsFqo3dFefIJ7.e/4ue9jzNL7O3wO', 0, '2024-09-21 16:52:59', '2024-09-21 16:52:59', 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `FK1` (`gender_id`),
  ADD KEY `FK2` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK1` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`genderid`),
  ADD CONSTRAINT `FK2` FOREIGN KEY (`role_id`) REFERENCES `roles` (`roleid`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
