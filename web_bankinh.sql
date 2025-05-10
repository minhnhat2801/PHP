-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 10, 2025 lúc 08:32 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `web_bankinh`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brand`
--

CREATE TABLE `brand` (
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brand`
--

INSERT INTO `brand` (`brand_id`, `category_id`, `brand_name`) VALUES
(1, 1, 'Hàng Mới Về'),
(2, 1, 'Hàng Cao Cấp'),
(9, 9, 'Hàng Mới Về');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Kính Thời Trang'),
(4, 'Gọng Kính Cận'),
(5, 'Luxury'),
(6, 'Học Sinh Sinh Viên'),
(9, 'Học Sinh ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` datetime DEFAULT current_timestamp(),
  `status` enum('pending','processing','completed','canceled') DEFAULT 'pending',
  `total_price` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `status`, `total_price`) VALUES
(1, 1, '2025-03-17 11:56:32', 'completed', 250000),
(2, 4, '2025-03-17 21:24:58', 'completed', 500000),
(3, 4, '2025-03-17 21:28:34', 'completed', 250000),
(4, 4, '2025-03-18 15:08:09', 'completed', 250000),
(5, 4, '2025-03-20 08:05:44', 'completed', 250000),
(6, 4, '2025-03-20 08:08:56', 'completed', 250000),
(7, 4, '2025-03-20 08:17:53', 'completed', 250000),
(8, 1, '2025-03-20 08:23:08', 'pending', 250000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 21, 1, 250000),
(2, 2, 21, 1, 250000),
(4, 3, 15, 1, 250000),
(5, 4, 22, 1, 250000),
(6, 5, 22, 1, 250000),
(7, 6, 12, 1, 250000),
(8, 7, 23, 1, 250000),
(9, 8, 23, 1, 250000);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product`
--

CREATE TABLE `product` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `brand_id` int(11) DEFAULT NULL,
  `product_price` decimal(12,0) DEFAULT NULL,
  `product_price_new` decimal(12,0) DEFAULT NULL,
  `product_desc` varchar(5000) DEFAULT NULL,
  `product_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product`
--

INSERT INTO `product` (`product_id`, `product_name`, `category_id`, `brand_id`, `product_price`, `product_price_new`, `product_desc`, `product_img`) VALUES
(12, 'Kinh đẹp', 5, 2, 300000, 250000, 'Quá đẹp', 'upload/sp1.jpg'),
(13, 'Kính Cận', 4, 2, 300000, 250000, 'Ok', 'upload/sp2.jpg'),
(14, 'Kính Chất', 1, 2, 300000, 250000, 'ok', 'upload/sp5.jpg'),
(15, 'Kính Xịn', 4, 1, 300000, 250000, 'OK', 'upload/sp7.jpg'),
(16, 'Kính Chất', 6, 2, 300000, 250000, 'Ok', 'upload/sp9_3.jpg'),
(17, 'Kính Chất', 6, 2, 300000, 250000, 'ad', 'upload/sp3.jpg'),
(18, 'Kinh đẹp', 6, 2, 300000, 250000, 'ok', 'upload/sp4.jpg'),
(19, 'Kính Xịn', 6, 2, 300000, 250000, 'ok', 'upload/sp6.jpg'),
(21, 'Kinh đẹp', 6, 2, 300000, 250000, 'ok', 'upload/sp8.jpg'),
(22, 'Kinh đẹp', 6, 1, 300000, 250000, 'ok', 'upload/sp10.jpg'),
(23, 'Kính Chất', 6, 2, 300000, 250000, 'ok', 'upload/sp10_1.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_img_desc`
--

CREATE TABLE `product_img_desc` (
  `product_img_desc_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_img_desc` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_img_desc`
--

INSERT INTO `product_img_desc` (`product_img_desc_id`, `product_id`, `product_img_desc`) VALUES
(3, 21, 'upload/sp3.jpg'),
(4, 21, 'upload/sp4.jpg'),
(5, 21, 'upload/sp5.jpg'),
(6, 21, 'upload/sp8.jpg'),
(19, 16, 'upload/sp9.jpg'),
(20, 16, 'upload/sp9_1.jpg'),
(21, 16, 'upload/sp9_2.jpg'),
(22, 16, 'upload/sp9_4.jpg'),
(39, 22, 'upload/sp10_1.jpg'),
(40, 22, 'upload/sp10_2.jpg'),
(41, 22, 'upload/sp10_3.jpg'),
(42, 22, 'upload/sp10_4.jpg'),
(47, 23, 'upload/sp10.jpg'),
(48, 23, 'upload/sp10_2.jpg'),
(49, 23, 'upload/sp10_3.jpg'),
(50, 23, 'upload/sp10_4.jpg');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','customer') DEFAULT 'customer'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `phone`, `address`, `role`) VALUES
(1, 'nhatdeptrai', '$2y$10$nCQMWz0cu41nhTMfTOFyGePiprGHV.nibOf2ZpmNjOYGD4T8dBrJ.', 'minhnhat28012004@gmail.com', '0337989904', 'Thái Bình', 'admin'),
(4, 'nhatok', '$2y$10$cZ5JKNtLb.ssXD83lGi/F.n3A7m9mVjeNUX0r8A4Mz9WDFzjn4IiW', 'nhatad2k4@gmail.com', '0337989904', 'HN', 'customer');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`brand_id`);

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Chỉ mục cho bảng `product_img_desc`
--
ALTER TABLE `product_img_desc`
  ADD PRIMARY KEY (`product_img_desc_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brand`
--
ALTER TABLE `brand`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT cho bảng `product_img_desc`
--
ALTER TABLE `product_img_desc`
  MODIFY `product_img_desc_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `product_img_desc`
--
ALTER TABLE `product_img_desc`
  ADD CONSTRAINT `product_img_desc_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`product_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
