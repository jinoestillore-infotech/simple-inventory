-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Jan 31, 2026 at 07:13 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `entity_type` varchar(50) NOT NULL,
  `entity_name` varchar(100) DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `entity_type`, `entity_name`, `action`, `created_at`) VALUES
(1, 'Category', 'Category Bs', 'Created', '2026-01-30 08:51:46'),
(2, 'Category', 'Category Bada', 'Created', '2026-01-30 08:51:55'),
(3, 'Category', 'Category B', 'Created', '2026-01-30 08:52:05'),
(4, 'Category', 'Category B', 'Updated', '2026-01-30 08:55:33'),
(5, 'Items', 'Item 1', 'Updated', '2026-01-30 09:02:00'),
(6, 'Items', 'Item 1', 'Updated', '2026-01-30 09:02:04'),
(7, 'Items', 'Item 1', 'Updated', '2026-01-30 09:02:09'),
(8, 'Items', 'Item 2', 'Added', '2026-01-30 09:02:20'),
(9, 'Category', 'For Testing', 'Added', '2026-01-30 23:53:12'),
(10, 'Category', 'For Testing', 'Added', '2026-01-30 23:54:53'),
(11, 'Category', 'asd', 'Added', '2026-01-30 23:55:39'),
(12, 'Category', 'asdasdasfas', 'Added', '2026-01-30 23:57:28'),
(13, 'Category', 'asdasdasfas', 'Deleted', '2026-01-30 23:57:34'),
(14, 'Category', 'Stationery', 'Deleted', '2026-01-31 00:37:57'),
(15, 'Category', 'Clothing', 'Deleted', '2026-01-31 00:37:59'),
(16, 'Category', 'Groceries', 'Deleted', '2026-01-31 00:38:01'),
(17, 'Category', 'Electronics', 'Deleted', '2026-01-31 00:38:01'),
(18, 'Category', 'Stationery', 'Deleted', '2026-01-31 00:38:02'),
(19, 'Category', 'Clothing', 'Deleted', '2026-01-31 00:38:03'),
(20, 'Category', 'Groceries', 'Deleted', '2026-01-31 00:38:03'),
(21, 'Category', 'Electronics', 'Deleted', '2026-01-31 00:38:04'),
(22, 'Category', 'Category B', 'Deleted', '2026-01-31 00:38:04'),
(23, 'Category', 'Category A', 'Deleted', '2026-01-31 00:38:05'),
(24, 'Items', 'Item 1', 'Added', '2026-01-31 00:56:49'),
(25, 'Items', 'ACER', 'Added', '2026-01-31 05:35:05');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(250) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'Electronics', 'Devices and gadgets', '2026-01-31 00:42:36'),
(2, 'Groceries', 'Everyday food and household items', '2026-01-31 00:42:36'),
(3, 'Clothing', 'Apparel for men, women, and children', '2026-01-31 00:42:36'),
(4, 'Stationery', 'Office and school supplies', '2026-01-31 00:42:36'),
(5, 'Hardware', 'Tools, screws, and building materials', '2026-01-31 02:10:20'),
(6, 'Office Supplies', 'Paper, pens, and office essentials', '2026-01-31 02:10:20'),
(7, 'Furniture', 'Chairs, desks, and cabinets', '2026-01-31 02:10:20'),
(8, 'Kitchenware', 'Utensils, appliances, and cookware', '2026-01-31 02:10:20'),
(9, 'Sports Equipment', 'Gear for indoor and outdoor sports', '2026-01-31 02:10:20'),
(10, 'Automotive', 'Car parts, accessories, and maintenance items', '2026-01-31 02:10:20'),
(11, 'Health & Beauty', 'Personal care and cosmetic products', '2026-01-31 02:10:20'),
(12, 'Books & Stationery', 'Books, notebooks, and learning materials', '2026-01-31 02:10:20');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `price` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`id`, `name`, `category_id`, `supplier_id`, `quantity`, `price`, `created_at`) VALUES
(1, 'Smartphone X10', 1, 1, 50, 19999.99, '2026-01-31 00:42:36'),
(2, 'Laptop Pro 15\"', 1, 1, 20, 54999.50, '2026-01-31 00:42:36'),
(3, 'Organic Rice 5kg', 2, 2, 100, 450.75, '2026-01-31 00:42:36'),
(4, 'Men\'s T-Shirt', 3, 3, 150, 299.99, '2026-01-31 00:42:36'),
(5, 'Notebook A4 100 pages', 4, 4, 200, 75.50, '2026-01-31 00:42:36'),
(6, 'LED Desk Lamp', 1, 1, 35, 1299.00, '2026-01-31 00:42:36'),
(7, 'Women\'s Jeans', 3, 3, 80, 899.00, '2026-01-31 00:42:36');

-- --------------------------------------------------------

--
-- Table structure for table `item_assets`
--

CREATE TABLE `item_assets` (
  `id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `asset_code` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item_assets`
--

INSERT INTO `item_assets` (`id`, `item_id`, `asset_code`, `created_at`) VALUES
(6, 7, 'FM60061960179', '2026-01-31 02:40:58'),
(7, 7, 'FM60061960178', '2026-01-31 02:46:27'),
(8, 7, 'FM60061960172', '2026-01-31 02:46:43'),
(9, 7, 'FM60061960177', '2026-01-31 02:52:14'),
(10, 7, 'FM60061960176', '2026-01-31 02:52:21'),
(12, 7, 'FM60061960173', '2026-01-31 03:22:44'),
(14, 7, 'FM60061960170', '2026-01-31 03:22:55'),
(16, 7, 'FM60061960160', '2026-01-31 04:07:26'),
(17, 7, 'FM60061960161', '2026-01-31 04:07:41'),
(18, 7, 'FM60061960162', '2026-01-31 04:10:00'),
(20, 7, 'FM60061960169', '2026-01-31 04:11:30'),
(21, 7, 'FM60061920172', '2026-01-31 04:15:22'),
(22, 7, 'FM60061920006', '2026-01-31 04:15:48'),
(23, 7, 'FM60061920004', '2026-01-31 04:16:13'),
(24, 7, 'FM60061920002', '2026-01-31 04:16:20'),
(25, 7, 'CON70022229', '2026-01-31 04:20:56');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `contact`, `created_at`) VALUES
(1, 'TechSource Inc.', '0917-123-4567 - techsourceinc@contactus.com', '2026-01-31 00:42:36'),
(2, 'FreshMart Co.', '0922-234-5678 - fmart@martmail.com', '2026-01-31 00:42:36'),
(3, 'FashionHub', '0933-345-6789 - fhcontact@fashion.com', '2026-01-31 00:42:36'),
(4, 'OfficePro Supplies', '0944-456-7890 - ops@companymail.com', '2026-01-31 00:42:36'),
(6, 'Global Tech Supplies', '0912-323-456 - contact@globaltech.com', '2026-01-31 01:37:29'),
(7, 'Sunrise Industrial', '0900-923-456 - info@sunriseind.com', '2026-01-31 01:37:29'),
(8, 'Oceanic Traders', '0900-123-156 - sales@oceanic.com', '2026-01-31 01:37:29'),
(9, 'Prime Electronics', '0900-143-456 - support@primeelectronics.com', '2026-01-31 01:37:29'),
(10, 'Greenfield Hardware', '0905-123-556 - hello@greenfieldhw.com', '2026-01-31 01:37:29'),
(11, 'Skyline Materials', '0901-123-356 - contact@skylinematerials.com', '2026-01-31 01:37:29'),
(12, 'Bright Future Supplies', '0920-123-456 - info@brightfuture.com', '2026-01-31 01:37:29'),
(13, 'Metro Industrial Co.', '0900-123-436 - sales@metroind.com', '2026-01-31 01:37:29'),
(14, 'NextGen Components', '0910-123-456 - support@nextgen.com', '2026-01-31 01:37:29'),
(15, 'Everest Tools', '0900-123-456 - contact@everesttools.com', '2026-01-31 01:37:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_category` (`category_id`),
  ADD KEY `fk_supplier` (`supplier_id`);

--
-- Indexes for table `item_assets`
--
ALTER TABLE `item_assets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `asset_code` (`asset_code`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `item_assets`
--
ALTER TABLE `item_assets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `item_assets`
--
ALTER TABLE `item_assets`
  ADD CONSTRAINT `item_assets_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
