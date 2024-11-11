-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 11, 2024 lúc 08:08 AM
-- Phiên bản máy phục vụ: 8.0.35
-- Phiên bản PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `php_project`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admins`
--

CREATE TABLE `admins` (
  `admin_id` int NOT NULL,
  `admin_name` varchar(108) NOT NULL,
  `admin_email` varchar(100) NOT NULL,
  `admin_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `admins`
--

INSERT INTO `admins` (`admin_id`, `admin_name`, `admin_email`, `admin_password`) VALUES
(1, 'Admin', 'admin@gmail.com', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `category`
--

CREATE TABLE `category` (
  `category_id` int NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `category`
--

INSERT INTO `category` (`category_id`, `category_name`) VALUES
(1, 'Shirt'),
(2, 'Shoes'),
(3, 'Trousers'),
(4, 'Jacket'),
(5, 'Jeans');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `order_id` int NOT NULL,
  `order_cost` decimal(10,2) NOT NULL,
  `order_status` enum('pending','shipped','delivered') NOT NULL DEFAULT 'delivered',
  `user_id` int NOT NULL,
  `user_phone` varchar(20) NOT NULL,
  `user_city` varchar(255) NOT NULL,
  `user_address` varchar(255) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`order_id`, `order_cost`, `order_status`, `user_id`, `user_phone`, `user_city`, `user_address`, `order_date`) VALUES
(1, 255.00, 'shipped', 1, '1705125253', 'HaNoi', 'phu dien bac tu liem', '2024-09-26 15:27:20'),
(2, 628.00, 'delivered', 1, '1705125253', 'HaNoi', 'phu dien bac tu liem', '2024-09-26 17:22:46'),
(3, 800.00, 'pending', 2, '1705125253', 'HaNoi', 'phu dien bac tu liem', '2024-09-27 04:09:51'),
(4, 560.00, 'delivered', 3, '1705125253', 'HaNoi', 'phu dien bac tu liem', '2024-09-27 04:39:30'),
(5, 255.00, 'pending', 4, '1705125253', 'HaNoi', 'phu dien bac tu liem', '2024-10-07 09:54:15'),
(6, 960.00, 'pending', 6, '1705125253', 'HaNoi', 'phu dien bac tu liem', '2024-11-11 07:59:21');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `item_id` int NOT NULL,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_quantity` int NOT NULL,
  `user_id` int NOT NULL,
  `order_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `order_items`
--

INSERT INTO `order_items` (`item_id`, `order_id`, `product_id`, `product_name`, `product_price`, `product_image`,`product_quantity`, `user_id`, `order_date`) VALUES
(1, 1, 7, 'Jogger', 225.00, 'product7.jpg',2, 1, '2024-09-26 15:27:20');


-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `product_id` int NOT NULL,
  `product_name` varchar(100) NOT NULL,
  `category_id` int NOT NULL,
  `product_description` varchar(255) NOT NULL,
  `product_size` ENUM('M','L','XL') NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `product_image2` varchar(255) DEFAULT NULL,
  `product_image3` varchar(255) DEFAULT NULL,
  `product_image4` varchar(255) DEFAULT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_color` varchar(108) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `product_description`,`product_size`, `product_image`, `product_image2`, `product_image3`, `product_image4`, `product_price`, `product_color`) VALUES
(1, 'T_shirt', 1, 'awesome T-Shirt','L', 'product10.jpg', 'product10.jpg', 'product10.jpg', 'product10.jpg', 155.00, 'white'),
(2, 'T_shirt', 1, 'awesome T-Shirt','M', 'product11.jpg', 'product11.jpg', 'product11.jpg', 'product11.jpg', 225.00, 'white'),
(3, 'T_shirt', 1, 'awesome T-Shirt','XL', 'product1.jpg', 'product1.jpg', 'product1.jpg', 'product1.jpg', 355.00, 'white'),
(4, 'T_shirt', 1, 'awesome T-Shirt','XL', 'product.png', 'product.png', 'product.png', 'product.png', 125.00, 'white'),
(5, 'T_shirt', 1, 'awesome T-Shirt','L', 'product2.jpg', 'product2.jpg', 'product2.jpg', 'product2.jpg', 299.00, 'black'),
(6, 'Jordan', 2, 'awesome shoes','XL', 'product5.jpg', 'product5.jpg', 'product5.jpg', 'product5.jpg', 125.00, 'red'),
(7, 'Jogger', 3, 'awesome trousers','XL', 'product7.jpg', 'product7.jpg', 'product7.jpg', 'product7.jpg', 225.00, 'black'),
(8, 'Jacket', 4, 'awesome Jacket','XL', 'product8.jpg', 'product8.jpg', 'product8.jpg', 'product8.jpg', 255.00, 'red'),
(9, 'Shoes Girl', 2, 'awesome Shoes ','L', 'product12.jpg', 'product12.jpg', 'product12.jpg', 'product12.jpg', 265.00, 'white'),
(10, 'T-shirt', 1, 'awesome T-shirt','M', 'product13.jpg', 'product13.jpg', 'product13.jpg', 'product13.jpg', 155.00, 'red'),
(11, 'Jeans', 4, 'awesome Jeans','XL', 'product14.jpg', 'product14.jpg', 'product14.jpg', 'product14.jpg', 215.00, 'blue'),
(12, 'T-shirt black', 1, 'awesome T-shirt','L', 'product15.jpg', 'product15.jpg', 'product15.jpg', 'product15.jpg', 105.00, 'black');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `user_id` int NOT NULL,
  `user_name` varchar(108) NOT NULL,
  `user_email` varchar(100) NOT NULL,
  `user_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_email`, `user_password`) VALUES
(1, 'tuanhvu', 'tuvux14@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'tuanhvu', 'tuvux141@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(3, 'tuanhvu', 'tuvux1411@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(4, 'tuanhvu', 'tuvu@gmail.com', '96e79218965eb72c92a549dd5a330112'),
(5, 'tuanhvu', 'tuvux1@gmail.com', '84535158dedbe815c16814f3b4b7efbb'),
(6, 'tuanhvu', 'tuvu11@gmail.com', '84535158dedbe815c16814f3b4b7efbb');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `UX_Constraint` (`admin_email`);

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
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `UX_Constraint` (`user_email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `item_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
