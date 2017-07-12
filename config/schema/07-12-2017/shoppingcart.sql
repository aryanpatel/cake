-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jul 12, 2017 at 04:32 PM
-- Server version: 10.1.21-MariaDB
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoppingcart`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) NOT NULL,
  `firstname` char(10) DEFAULT NULL,
  `lastname` char(10) DEFAULT NULL,
  `middlename` char(10) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(10) NOT NULL,
  `password` varchar(100) NOT NULL,
  `status` enum('0','1','2','3') NOT NULL DEFAULT '0',
  `dob` date DEFAULT NULL,
  `gender` enum('0','1','','') DEFAULT NULL,
  `address` varchar(60) DEFAULT NULL,
  `city` char(20) DEFAULT NULL,
  `country` int(3) DEFAULT NULL,
  `pincode` varchar(9) DEFAULT NULL,
  `dp` varchar(255) DEFAULT NULL,
  `join_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `activation_date` datetime DEFAULT CURRENT_TIMESTAMP,
  `token` varchar(255) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
