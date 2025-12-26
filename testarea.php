-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 25, 2021 at 10:40 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.2.32

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nasaka_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `misresponsibility`
--

CREATE TABLE `misresponsibility` (
  `transactionid` bigint(20) NOT NULL,
  `misresponsibilityid` bigint(20) NOT NULL,
  `misresponsibilityname` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `misactive` tinyint(1) NOT NULL,
  `crdatetime` datetime NOT NULL DEFAULT current_timestamp(),
  `dldatetime` datetime DEFAULT NULL,
  `cruserid` bigint(20) DEFAULT NULL,
  `dluserid` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `misresponsibility`
--

INSERT INTO RESPONSIBILITY (responsibilitycode, responsibilitynameeng,) VALUES
(157, 123984740, 'Payroll Master Addition', 1, '2021-01-25 09:22:19', NULL, 621754328954127, NULL),
(158, 123985057, 'Payroll Master Alteration', 1, '2021-01-25 09:22:30', NULL, 621754328954127, NULL),
(159, 123985374, 'Payroll Transaction Addition', 1, '2021-01-25 09:22:41', NULL, 621754328954127, NULL),
(160, 123985691, 'Payroll Transaction Alteration', 1, '2021-01-25 09:22:58', NULL, 621754328954127, NULL),
(161, 123986008, 'Payroll MIS Reports', 1, '2021-01-25 09:23:10', NULL, 621754328954127, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `misresponsibility`
--
ALTER TABLE `misresponsibility`
  ADD PRIMARY KEY (`transactionid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `misresponsibility`
--
ALTER TABLE `misresponsibility`
  MODIFY `transactionid` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=162;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
