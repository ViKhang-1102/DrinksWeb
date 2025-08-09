-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 08, 2025 at 06:05 AM
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
-- Database: `drinks`
--

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Soft drink'),
(2, 'Juice'),
(3, 'Coffee');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_code` varchar(255) NOT NULL,
  `note` text DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL,
  `tax_fee` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `note`, `status`, `subtotal`, `tax_fee`, `total`, `created_at`, `updated_at`, `phone`, `address`) VALUES
(11, 2, 'ORD-20250808053852-4e50e', 'My first order from my website', 'processing', 50.00, 5.00, 55.00, '2025-08-08 05:38:52', '2025-08-08 05:38:52', '12345678910', 'Can Tho'),
(13, 2, 'ORD-20250808054443-b5e0d', NULL, 'pending', 80.00, 5.00, 85.00, '2025-08-08 05:44:43', '2025-08-08 05:44:43', '12345678910', 'Can Tho');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `price`, `quantity`, `subtotal`) VALUES
(31, 11, 10, 15.00, 2, 30.00),
(32, 11, 11, 20.00, 1, 20.00),
(35, 13, 11, 20.00, 2, 40.00),
(36, 13, 7, 20.00, 2, 40.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `descriptions` text DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `descriptions`, `thumbnail`, `stock`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 'Watermelon juice', 15.00, 'Delicious and natural watermelon juice', 'thumb_689351c30a120.jpg', 1000, 2, '2025-08-06 14:32:17', '2025-08-06 21:06:56'),
(2, 'Orange juice', 15.00, 'Delicious and sweet orange juice', 'thumb_6893800ab1c79.jpg', 900, 2, '2025-08-06 18:17:14', '2025-08-06 21:06:46'),
(3, 'Strawberry juice', 15.00, 'Sweet and nutritious strawberry juice', 'thumb_6893811b4a91c.jpg', 800, 2, '2025-08-06 18:21:47', '2025-08-06 21:06:38'),
(4, 'Pepsi', 10.00, 'Pepsi is as refreshing as Coca', 'thumb_6893a72d2bec7.jpg', 700, 1, '2025-08-06 21:04:13', '2025-08-06 21:29:29'),
(5, 'Coca cola', 10.00, 'Coca cola is as refreshing as Pepsi', 'thumb_6893a7677f7c4.jpg', 750, 1, '2025-08-06 21:05:11', '2025-08-06 21:29:39'),
(6, '7up', 10.00, '7up has a mild spicy taste, refreshing the spirit.', 'thumb_6893a831c255d.jpg', 800, 1, '2025-08-06 21:08:33', '2025-08-06 21:29:53'),
(7, 'Black coffee', 20.00, 'Black coffee tastes strong', 'thumb_6893ad00c1c37.jpg', 150, 3, '2025-08-06 21:29:04', '2025-08-06 21:29:04'),
(8, 'Cappuccino', 25.00, 'Cappuccino coffee is rich in milk and strong in coffee', 'thumb_6893ada3157a7.jpg', 200, 3, '2025-08-06 21:31:47', '2025-08-06 21:31:47'),
(9, 'Orange Fanta', 10.00, 'Delicious flavor and bright orange color', 'thumb_68954d06efbb0.jpg', 800, 1, '2025-08-08 03:04:06', '2025-08-08 03:09:46'),
(10, 'Banana juice', 15.00, 'Delicious flavor and creamy banana flavor', 'thumb_6895520ab58be.jpg', 500, 2, '2025-08-08 03:11:19', '2025-08-08 03:25:30'),
(11, 'Americano', 20.00, 'Made by adding hot water to espresso.', 'thumb_68955175816b9.jpg', 300, 3, '2025-08-08 03:23:01', '2025-08-08 03:23:01');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `product_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `image_url`, `product_id`) VALUES
(1, 'img_68934b6cec2ed.jpg', 1),
(52, 'img_689351c30a62f.jpg', 1),
(54, 'img_6893807cd97ad.jpg', 2),
(55, 'img_6893813858c2f.jpg', 3),
(56, 'img_6893ad00c3bfe.jpg', 7),
(57, 'img_6893ad193b148.jpg', 4),
(58, 'img_6893ad238f238.jpg', 5),
(59, 'img_6893ad31b42e8.jpg', 6),
(60, 'img_6893ada3166d6.jpg', 8),
(61, 'img_68954e5a76189.jpg', 9),
(62, 'img_68954f573f8ed.jpg', 10),
(63, 'img_6895517582ae9.jpg', 11);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `role`, `address`) VALUES
(1, 'admin', 'admin@gmail.com', '123456789', '$2y$10$rAMrJVQJyLHEViiHJaTQfui6j2k6wEM/9vrz6D0WepIbGkhwveKcG', 'admin', NULL),
(2, 'user', 'user@gmail.com', '12345678910', '$2y$10$LKB2zEvYdc4ejqNoTgovN.YxGrDiNTVhfn6ECtZbSYdwT.qg3.XKe', 'user', 'Can Tho');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
