-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 22, 2025 at 06:10 PM
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
-- Database: `knightx_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL,
  `is_featured` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `parent_id`, `image`, `icon`, `is_featured`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Gaming Keyboards', 'gaming-keyboards', 'High-performance mechanical gaming keyboards', NULL, NULL, 'keyboard', 1, 'active', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(2, 'Gaming Mice', 'gaming-mice', 'Precision gaming mice with customizable buttons', NULL, NULL, 'mouse', 1, 'active', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(3, 'Gaming Headsets', 'gaming-headsets', 'Immersive gaming audio with crystal-clear communication', NULL, NULL, 'headset', 1, 'active', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(4, 'Gaming Monitors', 'gaming-monitors', 'High-refresh rate gaming displays', NULL, NULL, 'desktop', 1, 'active', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(5, 'Gift Cards', 'gift-cards', 'Digital gift cards for various gaming platforms', NULL, NULL, 'gift', 1, 'active', '2025-03-28 18:05:51', '2025-03-28 18:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` enum('active','unsubscribed') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_number` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text NOT NULL,
  `billing_address` text NOT NULL,
  `payment_method` varchar(50) NOT NULL,
  `payment_status` enum('pending','processing','completed','failed','refunded') DEFAULT 'pending',
  `order_status` enum('pending','processing','shipped','delivered','cancelled','refunded') DEFAULT 'pending',
  `shipping_method` varchar(50) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `category_id` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `gallery` text DEFAULT NULL,
  `specifications` text DEFAULT NULL,
  `features` text DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT 0.00,
  `review_count` int(11) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_new` tinyint(1) DEFAULT 1,
  `is_bestseller` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive','out_of_stock','discontinued') DEFAULT 'active',
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `price`, `sale_price`, `stock`, `category_id`, `image`, `gallery`, `specifications`, `features`, `rating`, `review_count`, `is_featured`, `is_new`, `is_bestseller`, `status`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`) VALUES
(1, 'Razer BlackWidow V3 Pro', 'razer-blackwidow-v3-pro', 'Wireless mechanical gaming keyboard with RGB lighting and premium build quality', 'Premium wireless mechanical keyboard', 229.99, NULL, 0, 1, 'assets/images/products/keyboards/razer-blackwidow-v3-pro.jpg', NULL, NULL, NULL, 0.00, 0, 1, 1, 1, 'active', NULL, NULL, NULL, '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(2, 'Logitech G Pro X Superlight', 'logitech-g-pro-x-superlight', 'Ultra-lightweight wireless gaming mouse with HERO sensor', 'Ultra-lightweight wireless mouse', 149.99, NULL, 0, 2, 'assets/images/products/mice/logitech-g-pro-x-superlight.jpg', NULL, NULL, NULL, 0.00, 0, 1, 1, 1, 'active', NULL, NULL, NULL, '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(3, 'SteelSeries Arctis Pro', 'steelseries-arctis-pro', 'Premium gaming headset with Hi-Res audio and DTS surround', 'Premium gaming headset', 199.99, NULL, 0, 3, 'assets/images/products/headsets/steelseries-arctis-pro.jpg', NULL, NULL, NULL, 0.00, 0, 1, 1, 1, 'active', NULL, NULL, NULL, '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(4, 'ASUS ROG Swift PG279QM', 'asus-rog-swift-pg279qm', '27-inch 4K HDR gaming monitor with 144Hz refresh rate', '4K HDR gaming monitor', 999.99, NULL, 0, 4, 'assets/images/products/monitors/asus-rog-swift-pg279qm.jpg', NULL, NULL, NULL, 0.00, 0, 1, 1, 1, 'active', NULL, NULL, NULL, '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(5, 'Steam Gift Card $50', 'steam-gift-card-50', 'Digital gift card for Steam platform', 'Steam gift card', 50.00, NULL, 0, 5, 'assets/images/products/gift-cards/steam-50.jpg', NULL, NULL, NULL, 0.00, 0, 1, 1, 1, 'active', NULL, NULL, NULL, '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(6, 'AdminGPON', '', 'cacsac', NULL, 210.00, NULL, 12, 1, '67e6e6b06ec44.png', NULL, NULL, NULL, 0.00, 0, 0, 1, 0, 'active', NULL, NULL, NULL, '2025-03-28 18:13:04', '2025-03-28 18:13:04');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `title` varchar(255) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `images` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'KnightX', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(2, 'site_description', 'Your Premium Gaming Destination', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(3, 'contact_email', 'support@knightx.com', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(4, 'contact_phone', '+1 (555) 123-4567', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(5, 'address', '123 Gaming Street', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(6, 'city', 'Gaming City', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(7, 'state', 'GC', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(8, 'country', 'United States', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(9, 'postal_code', '12345', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(10, 'shipping_cost', '5.00', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(11, 'free_shipping_threshold', '50.00', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(12, 'tax_rate', '8.00', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(13, 'tax_enabled', '1', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(14, 'stripe_public_key', '', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(15, 'stripe_secret_key', '', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(16, 'paypal_client_id', '', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(17, 'paypal_secret', '', '2025-03-28 18:05:51', '2025-03-28 18:05:51'),
(18, 'paypal_mode', 'sandbox', '2025-03-28 18:05:51', '2025-03-28 18:05:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `state` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `email_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `address`, `city`, `state`, `country`, `postal_code`, `avatar`, `role`, `status`, `email_verified`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@knightx.com', '$2y$10$bEB7kKuK/zY5QGFpBpCWMO6pmCzf5QSp4Z1N2jq6FroUNF3VpQq0K', 'Admin', 'Admin', '9864666601', 'Exhibition Road', 'Kathmandu', 'Bagmati', 'Nepal', '64600', NULL, 'admin', 'active', 1, '2025-03-28 18:05:51', '2025-05-22 15:58:58'),
(6, 'admin@knightx.com', '', '$2y$10$x3639GQrRBZ9d9TbjtVCaO5.b.OoAJKuBZtm0V3V8UrZjYbJ3Foka', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'admin', 'active', 0, '2025-05-22 16:01:27', '2025-05-22 16:01:27');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `parent_id` (`parent_id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `key` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
