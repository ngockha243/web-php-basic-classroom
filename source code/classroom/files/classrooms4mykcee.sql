-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 01, 2020 at 03:45 AM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `classroom`
--
CREATE DATABASE IF NOT EXISTS `classroom` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `classroom`;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `username` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `fullname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(13) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `activated` bit(1) DEFAULT b'0',
  `activate_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`username`, `fullname`, `birthday`, `email`, `phone`, `password`, `role`, `activated`, `activate_token`) VALUES
('admin', 'admin', '2000-01-01', 'admin@gmail.com', '0354452812', '$2y$10$UA6d8dqFhh5T1WWWNZGeDetmVrMw8rGwndxxQijdKfBdte8z4l9wm', '1', b'1', '123456'),
('ngockha41234', 'Nguyễn Ngọc Kha', '2020-11-10', 'ngockha41234@gmail.com', '0354452812', '$2y$10$NzKv0ihK58ivySIt8gePWuU96Fs14FBRRn97poPoLHCN33BsEQnM.', '3', b'1', '4c8809bcbe9f5fb0977b8ce0676ae244'),
('nguyenkha4123', 'Nguyễn Ngọc Kha', '2020-11-18', 'nguyenkha4123@gmail.com', '0354452812', '$2y$10$6y5IicuEL/ycA1GzDaDP5O29RmtCo3AE1NFZ9jMWFhHITZet2Xpem', '1', b'1', '');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `name` varchar(100) NOT NULL,
  `monhoc` varchar(20) NOT NULL,
  `phong` varchar(5) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `code` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`name`, `monhoc`, `phong`, `avatar`, `code`) VALUES
('Tiếng Anh', 'Anh văn', 'E0101', 'images/(19)1.jpg', '7a7bm6gi'),
('Hệ cơ sở dữ liệu', 'Hệ cơ sở dữ liệu 1', 'A0101', 'images/(4)249.jpg', 'ztg8af83');

-- --------------------------------------------------------

--
-- Table structure for table `reset_token`
--

CREATE TABLE `reset_token` (
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expire_on` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `reset_token`
--

INSERT INTO `reset_token` (`email`, `token`, `expire_on`) VALUES
('admin@gmail.com', '123456', 0),
('ngockha4123@gmail.com', '8c7eb567c24d42cd1657c070c76856e0', 1606559905),
('nguyenkha4123@gmail.com', '8c576b7ee2ce98f64dee6a96b651e7b5', 1606632720);

-- --------------------------------------------------------

--
-- Table structure for table `student_class`
--

CREATE TABLE `student_class` (
  `username` varchar(64) NOT NULL,
  `code` varchar(8) NOT NULL,
  `is_join` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student_class`
--

INSERT INTO `student_class` (`username`, `code`, `is_join`) VALUES
('nguyenkha4123', '7a7bm6gi', '1');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_class`
--

CREATE TABLE `teacher_class` (
  `username_teacher` varchar(255) NOT NULL,
  `code_class` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teacher_class`
--

INSERT INTO `teacher_class` (`username_teacher`, `code_class`) VALUES
('admin', '7a7bm6gi'),
('admin', 'juw7dqx0'),
('admin', 'ztg8af83');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `reset_token`
--
ALTER TABLE `reset_token`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `student_class`
--
ALTER TABLE `student_class`
  ADD PRIMARY KEY (`username`,`code`);

--
-- Indexes for table `teacher_class`
--
ALTER TABLE `teacher_class`
  ADD PRIMARY KEY (`username_teacher`,`code_class`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
