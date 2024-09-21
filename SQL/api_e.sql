-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 21, 2024 at 03:40 PM
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
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `genderid` tinyint(1) NOT NULL,
  `gender` varchar(20) NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`genderid`, `gender`, `updated`, `created`) VALUES
(0, 'Male', '2024-09-12 21:47:46', '2024-09-12 21:47:46'),
(1, 'Female', '2024-09-12 21:47:46', '2024-09-12 21:47:46'),
(2, 'Other', '2024-09-12 21:48:31', '2024-09-12 21:48:31');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `roleid` tinyint(1) NOT NULL,
  `role` varchar(15) NOT NULL,
  `updated` datetime NOT NULL DEFAULT current_timestamp(),
  `created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`roleid`, `role`, `updated`, `created`) VALUES
(0, 'User', '2024-09-12 21:52:54', '2024-09-12 21:52:54'),
(1, 'Admin', '2024-09-12 21:52:54', '2024-09-12 21:52:54');

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
(25, 'eeshan', 'eeshan.vaghjiani@strathmore.edu', 'Eeshan04', '$2y$10$MoF0gYwHEA7FOUtz5eqsz.nckWwy1kDExYP6yWDUM7iHeS0EEUYgC', 0, '2024-09-21 15:59:59', '2024-09-21 15:37:41', 0, 0),
(27, 'eeshan', 'evaghjiani@gmail.com', 'Eeshan', '$2y$10$RjPoqH.E4ZgxjwdBWOnj9Onc5Wd8dmqbVSvXlfU1Y2z6ms/k3r.qK', 0, '2024-09-21 16:06:27', '2024-09-21 16:06:27', 0, 0),
(28, 'bhavin', 'bhavin.mepani@strathmore.edu', 'bhavin@lodu', '$2y$10$Iy35imTO2Kj6QEzar8mskuE24lYyfSdvt72cY943uLJ7e3fLMPHQ6', 0, '2024-09-21 16:30:35', '2024-09-21 16:21:05', 1, 0),
(29, 'dhruvin', 'dhruvin.bhudia@strathmore.edu', 'dhruvin', '$2y$10$7ZEb.y85yLCy6igeTd.gpuWlw7I8cR85Wv8f/EtlGFkRNIdcrUuOW', 0, '2024-09-21 16:26:35', '2024-09-21 16:26:35', 0, 0),
(30, 'Bipin Vaghjiani', 'bvaghjiani@gmail.com', 'bvaghjiani', '$2y$10$/zekEiXuxfHNvryywWgwlu38DjOAIZQy2YHjy3R7jfKbUdImqO8C6', 0, '2024-09-21 16:36:39', '2024-09-21 16:35:25', 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`genderid`,`gender`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`roleid`,`role`);

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
  MODIFY `user_id` bigint(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

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
