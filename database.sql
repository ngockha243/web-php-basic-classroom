-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 02, 2020 at 03:40 PM
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
-- Database: `database`
--
CREATE DATABASE IF NOT EXISTS `database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `database`;

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
('giaoviene', 'Nguyễn Thị E', '1995-05-05', 'giaoviene@gmail.com', '0354452812', '$2y$10$c3cQ8zI1q2b3Mr4tgP0Gue.lGOY7.QzXpE1SD5POjlf/BWG.9j2Eu', '2', b'1', ''),
('giaovienf', 'Nguyễn Văn F', '1995-06-06', 'nguyendadada4123@gmail.com', '0354452812', '$2y$10$9nPOJ6bswcHU3ZNmR/ZY..keDYEFrImJl.a29ep8nufOlSU6IZ3aK', '2', b'1', ''),
('usera', 'Nguyễn Văn A', '2000-01-01', 'sinhviena@gmail.com', '0354452812', '$2y$10$g2wDE/adZQkd8HJdjF3vie/yNIMgHIXhJH020olcP2zhvpfrD78Hy', '3', b'1', ''),
('userb', 'Nguyễn Văn B', '2000-02-02', 'sinhvienb.classroom@gmail.com', '0354452812', '$2y$10$Q/9QyXAH.c8/qZzuJowBkOSNHAnj1brh1Jv6LAvgJhzG8bcN3oxv2', '3', b'1', ''),
('userc', 'Nguyễn Văn C', '2000-03-03', 'sinhvienc.classroom@gmail.com', '0354452812', '$2y$10$ZYkF8x1Vd4MEgtL1Hu0QPOLa0D0AOk5SV84/9Y48HhS4UpibWYhrC', '3', b'1', ''),
('userd', 'Nguyễn Thị D', '2000-04-04', 'sinhviend.classroom@gmail.com', '0354452812', '$2y$10$Ji4chYPcS4561SKDJPRcMunmomyD3djOADGRjV.N1kFtgx1DDj9iW', '3', b'1', '');

-- --------------------------------------------------------

--
-- Table structure for table `class`
--

CREATE TABLE `class` (
  `name` varchar(100) NOT NULL,
  `monhoc` varchar(100) NOT NULL,
  `phong` varchar(5) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `code` varchar(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `class`
--

INSERT INTO `class` (`name`, `monhoc`, `phong`, `avatar`, `code`) VALUES
('HK1-2020-2021 Phát triển trò chơi', 'Phát triển trò chơi', 'C0210', 'images/avata1.jpg', '33isz2nd'),
('HK1-2020-2021 Hệ cơ sở dữ liệu', 'Hệ cơ sở dữ liệu', 'B0201', 'images/avata1.jpg', '80zr653u'),
('HK1-2020-2021 Lập trình web nâng cao', 'Lập trình web nâng cao', 'C0409', 'images/avata3.jpg', 'rdrh7ceq'),
('HK1-2020-2021 Lập trình web và ứng dụng', 'Lập trình web và ứng dụng', 'C0201', 'images/avata1.jpg', 'rkgy5onj');

-- --------------------------------------------------------

--
-- Table structure for table `comment_info`
--

CREATE TABLE `comment_info` (
  `comment_date` datetime NOT NULL,
  `post_code` varchar(8) NOT NULL,
  `comment_code` varchar(8) NOT NULL,
  `content` varchar(500) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `comment_info`
--

INSERT INTO `comment_info` (`comment_date`, `post_code`, `comment_code`, `content`, `username`) VALUES
('2020-12-02 20:40:09', '7yqxzgjc', 'mx6527fm', 'fdsfsd', 'giaovienf'),
('2020-12-02 20:27:38', '7yqxzgjc', 'wqmjexa3', 'Ok', 'giaovienf'),
('2020-12-02 20:26:12', 'i7kazhub', 'hqzse7f6', 'từ ngày 2.12', 'giaoviene'),
('2020-12-02 20:26:40', 'i7kazhub', 'vyyfcsig', 'đến ngày 6.12', 'userb'),
('2020-12-02 20:27:07', 'yrcyvhbm', '4wn7mys6', 'Hai người', 'giaovienf'),
('2020-12-02 20:27:03', 'yrcyvhbm', 'bszya5xw', 'Một người ', 'giaovienf'),
('2020-12-02 20:55:10', 'yrcyvhbm', 'kxcojcur', 'sdcasdfdsfdsfd', 'usera'),
('2020-12-02 20:55:07', 'yrcyvhbm', 'r0zti53o', 'sdcasdfdsfdsfd', 'usera');

-- --------------------------------------------------------

--
-- Table structure for table `invite_student`
--

CREATE TABLE `invite_student` (
  `class_code` varchar(8) NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `post_info`
--

CREATE TABLE `post_info` (
  `date_post` datetime NOT NULL,
  `class_code` varchar(8) NOT NULL,
  `post_code` varchar(8) NOT NULL,
  `content` varchar(1000) DEFAULT NULL,
  `attachment` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `edit` varchar(2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `post_info`
--

INSERT INTO `post_info` (`date_post`, `class_code`, `post_code`, `content`, `attachment`, `username`, `edit`) VALUES
('2020-12-02 20:27:26', '33isz2nd', '7yqxzgjc', 'Các bạn chuẩn bị làm bài báo cáo cuối kì 30.12', '', 'giaovienf', '0'),
('2020-12-02 20:13:07', '80zr653u', '60nuzxge', 'Ngày mai các bạn được nghỉ dịch nha.', '', 'giaovienf', '0'),
('2020-12-02 20:13:32', '80zr653u', 'yrcyvhbm', 'Lịch thi đã được dời sang tuần sau nữa. Các bạn check mail.', '', 'giaovienf', '0'),
('2020-12-02 20:19:59', 'rdrh7ceq', 'i4k88j2j', 'Các bạn chuẩn bị thi nha.', '', 'giaoviene', '0'),
('2020-12-02 20:18:50', 'rdrh7ceq', 'yh5ow578', 'Các bạn nghỉ dịch từ ngày 02.12 đến ngày 06.12', '', 'giaoviene', '0'),
('2020-12-02 20:25:56', 'rkgy5onj', 'i7kazhub', 'Các bạn được nghỉ dịch nha', '', 'giaoviene', '0');

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
('admin@gmail.com', '123456', 0);

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
('usera', '80zr653u', '1'),
('usera', 'rkgy5onj', '1'),
('userb', '33isz2nd', '1'),
('userb', 'rkgy5onj', '1'),
('userc', '80zr653u', '1'),
('userc', 'rdrh7ceq', '1'),
('userd', '33isz2nd', '1'),
('userd', 'rdrh7ceq', '1');

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
('giaoviene', 'rdrh7ceq'),
('giaoviene', 'rkgy5onj'),
('giaovienf', '33isz2nd'),
('giaovienf', '80zr653u');

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
-- Indexes for table `comment_info`
--
ALTER TABLE `comment_info`
  ADD PRIMARY KEY (`post_code`,`comment_code`);

--
-- Indexes for table `invite_student`
--
ALTER TABLE `invite_student`
  ADD PRIMARY KEY (`class_code`,`username`);

--
-- Indexes for table `post_info`
--
ALTER TABLE `post_info`
  ADD PRIMARY KEY (`class_code`,`post_code`);

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
