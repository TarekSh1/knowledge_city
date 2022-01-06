-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jan 06, 2022 at 07:24 PM
-- Server version: 5.7.29
-- PHP Version: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `knowledge_city_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(32) NOT NULL,
  `group_name` varchar(32) DEFAULT 'kctest 2022',
  `subject` varchar(255) DEFAULT 'Just a test',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `group_name`, `subject`, `created_at`, `modified`) VALUES
(1, 'Tarek', 'kctest2022', 'Test task', '2022-01-06 06:10:00', '2022-01-06 06:10:00'),
(3, 'Robert', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(4, 'John', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(5, 'Michael', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(6, 'William', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(7, 'David', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(8, 'Richard', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(9, 'Joseph', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(10, 'Thomas', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(11, 'Charles', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(12, 'Christopher', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(13, 'Daniel', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(14, 'Matthew', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(15, 'Anthony', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15'),
(16, 'Mark', 'kctest 2022', 'Just a test', '2022-01-06 06:12:15', '2022-01-06 06:12:15');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `f_name` varchar(255) NOT NULL,
  `l_name` varchar(255) DEFAULT NULL,
  `user_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `f_name`, `l_name`, `user_name`, `email`, `password`, `api_token`, `phone`, `created`, `modified`) VALUES
(1, 'tarek', 'test', 'tito232', 'tarek.shaheen.1988@gmail.com', '$2y$10$Bt7mGu7hVH6RLaJdEhHDUeSjp3ymoOmkyAT.HSAif3I0WEmci0aF6', '6ed82f2fed381f634a43e260ce61379023db81411e9250c7fdd7732ea1790c530d001bb6d7f902ac1b9998fab3febb4d102cd2822bdee14cb87747bbb0a1bf03', '', '2022-01-05 14:01:23', '2022-01-05 07:02:09'),
(2, 'admin', 'admin', 'admin', 'admin@test.com', '$2y$10$Bt7mGu7hVH6RLaJdEhHDUeSjp3ymoOmkyAT.HSAif3I0WEmci0aF6', '44bb16eed76604bedc7925161ab444c12fce0af228bdbdcfc395902122f46430a44ae9d73f92d132b5a41deea9877e40a37ddad311b63b139b2d6177fa5d917a', '', '2022-01-06 20:47:24', '2022-01-06 13:47:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
