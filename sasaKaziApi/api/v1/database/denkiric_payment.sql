-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 11, 2023 at 11:47 PM
-- Server version: 10.5.18-MariaDB-cll-lve
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `denkiric_payment`
--

-- --------------------------------------------------------

--
-- Table structure for table `mpesa_payments`
--

CREATE TABLE `mpesa_payments` (
  `id` int(11) NOT NULL,
  `invoice_number` varchar(250) NOT NULL,
  `date` varchar(100) DEFAULT NULL,
  `amount` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `month` varchar(100) DEFAULT NULL,
  `year` varchar(100) DEFAULT NULL,
  `paymentStatus` varchar(100) DEFAULT NULL,
  `payment_details` varchar(100) DEFAULT NULL,
  `mpesa_amount` varchar(100) DEFAULT NULL,
  `MpesaReceiptNumber` varchar(100) DEFAULT NULL,
  `TransactionDate` varchar(100) DEFAULT NULL,
  `PhoneNumber` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `mpesa_payments`
--

INSERT INTO `mpesa_payments` (`id`, `invoice_number`, `date`, `amount`, `name`, `month`, `year`, `paymentStatus`, `payment_details`, `mpesa_amount`, `MpesaReceiptNumber`, `TransactionDate`, `PhoneNumber`) VALUES
(26, '0EW2VK2Q', 'February 11, 2023', '250', '7', 'February', '2023', '1', 'Payment Recieved The service request is processed successfully.', '1', 'RBB1GRSYI3', '20230211232255', '254700107838'),
(25, 'BT22N7SV', 'February 11, 2023', '200', '7', 'February', '2023', '2', 'Payment Not Recieved Request cancelled by user', NULL, NULL, NULL, '254700107838');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mobile` varchar(100) NOT NULL,
  `token` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `password`, `name`, `email`, `mobile`, `token`) VALUES
(7, '21925ba415d424c44243cb4d31abfd4f', 'Dennis ', 'dk@gmail.com', '254700107838', 'fd96e440a6a7b445ce576a76d717548e14b6ae15d3ef4b00a8c00200feb749de86f161b29639ffbfa8945bba6d484cccea0a74d3903262d5d95bc713a5f6f1c4');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `mpesa_payments`
--
ALTER TABLE `mpesa_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `name` (`name`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `mpesa_payments`
--
ALTER TABLE `mpesa_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
