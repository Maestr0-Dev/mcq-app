-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2025 at 07:33 PM
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
-- Database: `interactives_mcqs`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `adm_name` text NOT NULL,
  `emails` text NOT NULL,
  `phone` int(11) NOT NULL,
  `password` text NOT NULL,
  `date` date NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `a_level_gce`
--

CREATE TABLE `a_level_gce` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` text NOT NULL,
  `subject` text NOT NULL,
  `instructions` text NOT NULL,
  `question` text NOT NULL,
  `A` text NOT NULL,
  `B` text NOT NULL,
  `C` text NOT NULL,
  `D` text NOT NULL,
  `img` blob NOT NULL,
  `img_type` text NOT NULL,
  `ans` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `a_level_mock`
--

CREATE TABLE `a_level_mock` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` text NOT NULL,
  `subject` text NOT NULL,
  `instructions` text NOT NULL,
  `question` text NOT NULL,
  `A` text NOT NULL,
  `B` text NOT NULL,
  `C` text NOT NULL,
  `D` text NOT NULL,
  `img` blob NOT NULL,
  `img_type` text NOT NULL,
  `ans` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `o_level_gce`
--

CREATE TABLE `o_level_gce` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` text NOT NULL,
  `subject` text NOT NULL,
  `instructions` text NOT NULL,
  `question` text NOT NULL,
  `A` text NOT NULL,
  `B` text NOT NULL,
  `C` text NOT NULL,
  `D` text NOT NULL,
  `img` blob NOT NULL,
  `img_type` text NOT NULL,
  `ans` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `o_level_mock`
--

CREATE TABLE `o_level_mock` (
  `id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `title` text NOT NULL,
  `subject` text NOT NULL,
  `instructions` text NOT NULL,
  `question` text NOT NULL,
  `A` text NOT NULL,
  `B` text NOT NULL,
  `C` text NOT NULL,
  `D` text NOT NULL,
  `img` blob NOT NULL,
  `img_type` text NOT NULL,
  `ans` text NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `stud_id` int(11) NOT NULL,
  `stud_name` text NOT NULL,
  `email` text NOT NULL,
  `number` int(11) NOT NULL,
  `pass` text NOT NULL,
  `date` date NOT NULL,
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `teacher_id` int(11) NOT NULL,
  `name` text NOT NULL,
  `phone` int(11) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `subject` text NOT NULL,
  `id_card_img` blob NOT NULL,
  `teaaching_cert_img` blob NOT NULL,
  `approved` text NOT NULL DEFAULT '\'no\'',
  `deleted` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `a_level_gce`
--
ALTER TABLE `a_level_gce`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `a_level_mock`
--
ALTER TABLE `a_level_mock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `o_level_gce`
--
ALTER TABLE `o_level_gce`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `o_level_mock`
--
ALTER TABLE `o_level_mock`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`stud_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`teacher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `a_level_gce`
--
ALTER TABLE `a_level_gce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `a_level_mock`
--
ALTER TABLE `a_level_mock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `o_level_gce`
--
ALTER TABLE `o_level_gce`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `o_level_mock`
--
ALTER TABLE `o_level_mock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `stud_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `teacher_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
