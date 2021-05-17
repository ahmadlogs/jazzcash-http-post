-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 09, 2020 at 06:22 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jazzcash_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `disputed_transaction`
--

CREATE TABLE `disputed_transaction` (
  `pp_TxnRefNo` text NOT NULL,
  `pp_TxnDateTime` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `pp_TxnCurrency` text NOT NULL,
  `pp_SecureHash` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `product_price` float(10,2) NOT NULL,
  `total` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `created_date` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `modified_date` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `status` varchar(25) COLLATE utf8_unicode_ci NOT NULL COMMENT '0=Failed, 1=Success'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_validation`
--

CREATE TABLE `payment_validation` (
  `pp_TxnRefNo` text NOT NULL,
  `pp_TxnDateTime` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `pp_TxnCurrency` text NOT NULL,
  `pp_SecureHash` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `price` float(10,2) NOT NULL,
  `status` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`product_id`, `name`, `description`, `image`, `price`, `status`) VALUES
(1, 'Product One Shoes', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy', 'images/1.jpg', 10.00, 1),
(2, 'Product Two Dress', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy', 'images/2.jpg', 20.00, 1),
(3, 'Product Three Bag', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy', 'images/3.jpg', 30.00, 1),
(4, 'Product Four LCD', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy', 'images/4.jpg', 50.00, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `disputed_transaction`
--
ALTER TABLE `disputed_transaction`
  ADD PRIMARY KEY (`pp_TxnRefNo`(16));

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_validation`
--
ALTER TABLE `payment_validation`
  ADD PRIMARY KEY (`pp_TxnRefNo`(16));

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
