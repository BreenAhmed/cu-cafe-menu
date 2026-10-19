-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 11, 2026 at 12:23 AM
-- Server version: 11.8.9-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u100517965_cumenu`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$12$8XiQSpeJcB.yGkjailXfhe9J5gICUqwAmDUTkRbiXrGm/YWcxVCXW', '2026-07-21 23:11:44');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(10) UNSIGNED NOT NULL,
  `name_en` varchar(120) NOT NULL,
  `name_ar` varchar(120) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name_en`, `name_ar`, `sort_order`, `status`, `created_at`) VALUES
(11, 'Burgers', 'برغر', 1, 1, '2026-07-24 13:58:52'),
(12, 'Salads', 'السلطات', 2, 1, '2026-07-24 13:59:24'),
(13, 'Cold Drinks', 'مشروبات باردة', 3, 1, '2026-07-24 13:59:56'),
(14, 'Hot Drinks', 'مشروبات ساخنة', 4, 1, '2026-07-24 14:00:24'),
(15, 'Milk Shakes', 'ميلك شيك', 6, 1, '2026-07-24 14:01:08'),
(16, 'Hookah', 'أراكيل', 8, 1, '2026-07-24 14:01:30'),
(17, 'Mojito', 'موهيتو', 5, 1, '2026-08-27 00:58:32'),
(18, 'Slush', 'سلاش', 7, 1, '2026-08-27 01:12:47'),
(19, 'Sweets', 'حلويات', 2, 1, '2026-10-06 23:57:45'),
(20, 'Soups', 'الشوربات', 2, 1, '2026-10-09 03:53:53');

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(10) UNSIGNED NOT NULL,
  `category_id` int(10) UNSIGNED NOT NULL,
  `name_en` varchar(160) NOT NULL,
  `name_ar` varchar(160) NOT NULL,
  `description_en` text DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `category_id`, `name_en`, `name_ar`, `description_en`, `description_ar`, `price`, `status`, `sort_order`, `created_at`) VALUES
(47, 11, 'Halloumi Burger with Honey', 'برغر حلوم بالعسل', 'Watermelon, Halloumi cheese, Mint and Honey', 'بطيخ, جبنة حلوم, نعناع وعسل', 35000.00, 1, 1, '2026-07-24 14:18:13'),
(48, 11, 'Local Cheese Burger with honey', 'برغر بلدي بالعسل', 'Watermelon, Local cheese, Mint and Honey', 'بطيخ, جبنة بلدي, نعناع وعسل', 30000.00, 1, 2, '2026-07-24 14:19:54'),
(49, 11, 'Plain Burger', 'برغر سادة', 'Watermelon, Mint and Honey', 'بطيخ, نعناع وعسل', 20000.00, 1, 3, '2026-07-24 14:23:14'),
(51, 12, 'Halloumi Salad', 'سلطة حلوم', 'Watermelon, Halloumi cheese, Mint and Honey', 'بطيخ, جبنة حلوم, نعناع وعسل', 35000.00, 1, 5, '2026-07-24 14:28:17'),
(52, 12, 'Local Salad', 'سلطة بلدي', 'Watermelon, Local cheese, Mint and Honey', 'بطيخ, جبن بلدي, نعناع وعسل', 30000.00, 1, 6, '2026-07-24 14:29:41'),
(53, 12, 'Mshallal Salad', 'سلطة مشلل', 'Watermelon, Mshallal cheese, Mint and Honey', 'بطيخ, جبنة مشللة, نعناع وعسل', 30000.00, 1, 7, '2026-07-24 14:32:37'),
(54, 12, 'Plain Salad', 'سادة عالسكين', 'Watermelon and Honey', 'بطيخ وعسل', 20000.00, 1, 8, '2026-07-24 14:35:46'),
(55, 13, 'Watermelon Juice', 'عصير بطيخ', 'Watermelon and Ice', 'بطيخ وثلج', 25000.00, 1, 9, '2026-07-24 14:53:10'),
(56, 13, 'Orange Juice', 'عصير برتقال', 'Orange and Ice', 'برتقال وثلج', 30000.00, 1, 10, '2026-07-24 14:53:56'),
(61, 17, 'Watermelon Mojito', 'موهيتو بطيخ', 'Watermelon, Lemon, Sparkling water, Mint and Ice', 'بطيخ, ليمون, مياه غازية, نعناع وثلج', 30000.00, 1, 40, '2026-07-24 15:08:36'),
(62, 13, 'Banana Milk Cocktail', 'كوكتيل موز وحليب', 'Banana, Milk and Ice', 'موز, حليب وثلج', 35000.00, 1, 11, '2026-07-24 15:11:17'),
(63, 13, 'Iced Americano', 'ايس امريكانو', 'Double Shot Espresso, Water and Ice', 'دبل شوت اسبرسو, ماء وثلج', 25000.00, 1, 12, '2026-07-24 15:13:48'),
(64, 13, 'Iced Latte', 'ايس لاتيه', 'Double Shot Espresso, Milk and Ice', 'دبل شوت اسبرسو, حليب وثلج', 35000.00, 1, 13, '2026-07-24 15:14:39'),
(65, 14, 'Turkish Coffee', 'قهوة تركية', 'ground coffee', 'بن مطحون', 15000.00, 1, 22, '2026-07-25 19:18:28'),
(66, 14, 'Espresso', 'أسبرسو', 'Espresso coffee', 'قهوة اسبرسو', 10000.00, 1, 23, '2026-07-25 19:20:37'),
(67, 14, 'Tea', 'شاي', 'Hot Tea', 'شاي ساخن', 10000.00, 1, 26, '2026-07-25 19:24:28'),
(68, 14, 'Milk', 'حليب', 'Hot Milk', 'حليب ساخن', 15000.00, 1, 33, '2026-07-25 19:25:00'),
(69, 14, 'Hot Chocolate', 'هوت جوكليت', 'Hot Chocolate Drink', 'شوكولا ساخنة', 15000.00, 1, 32, '2026-07-25 19:26:05'),
(70, 14, 'Americano', 'امريكانو', 'Espresso shot and Hot water', 'شوت اسبرسو وماء ساخن', 12000.00, 1, 24, '2026-07-25 19:26:56'),
(71, 14, 'Milk Tea', 'شاي بحليب', 'Milk and Tea', 'شاي وحليب', 15000.00, 1, 27, '2026-07-25 19:27:36'),
(72, 14, 'Green Tea', 'شاي اخضر', 'Green Tea Drink', 'شاي اخضر ساخن', 15000.00, 1, 27, '2026-07-25 19:28:31'),
(73, 14, 'Cappuccino', 'كابتشينو', 'Cappuccino Drink', 'كابتشينو ساخن', 10000.00, 1, 29, '2026-07-25 19:29:48'),
(74, 14, 'Nescafe', 'نسكافيه', 'Nescafe Drink', 'نسكافيه ساخن', 10000.00, 1, 31, '2026-07-25 19:30:42'),
(77, 15, 'Chocolate Shake', 'شوكولا شيك', 'Chocolate Flavor Milk shake', 'ميلك شيك بطعم الشوكولا', 35000.00, 1, 48, '2026-07-25 19:34:04'),
(78, 15, 'Fruits Shake', 'فواكه شيك', 'Fruits Flavor Milk Shake', 'ميلك شيك بطعم الفواكه', 35000.00, 1, 46, '2026-07-25 19:36:00'),
(79, 15, 'Strawberry Shake', 'فريز شيك', 'Strawberry flavor Milk Shake', 'ميلك شيك بطعم الفراولة', 35000.00, 1, 47, '2026-07-25 19:37:08'),
(80, 14, 'Milo', 'ميلو', 'Hot milo', 'ميلو ساخن', 10000.00, 1, 34, '2026-08-12 01:55:16'),
(81, 14, 'Coffee Milk', 'قهوة بحليب', 'Turkish Coffee and Milk', 'قهوة تركية وحليب', 20000.00, 1, 25, '2026-08-12 01:56:10'),
(82, 14, 'Italian Cappuccino', 'كابتشينو ايطالي', 'Hot Italian Cappuccino', 'كابتشينو ايطالي ساخن', 15000.00, 1, 30, '2026-08-12 01:57:21'),
(83, 14, 'Ginger', 'زنجبيل', 'Hot Ginger', 'زنجبيل ساخن', 10000.00, 1, 37, '2026-08-12 01:58:38'),
(84, 16, 'love Hookah', 'اركيلة لوف', 'Love Flavor', 'بطعم لوف', 25000.00, 1, 100, '2026-08-12 02:00:29'),
(85, 16, 'Gum Hookah', 'اركيلة شكلس', 'Gum Flavor', 'بطعم علكة شكلس', 25000.00, 1, 101, '2026-08-12 02:01:56'),
(86, 16, 'Polo Hookah', 'اركيلة بولو', 'Polo Flavor', 'بطعم البولو', 25000.00, 1, 102, '2026-08-12 02:02:30'),
(87, 16, 'Two Apples - Mazaya Hookah', 'اركيلة تفاحتين - مزايا', 'Two Apples - Mazaya Flavor', 'بطعم تفاحتين مزايا', 25000.00, 1, 103, '2026-08-12 02:04:03'),
(88, 16, 'Two Apples - Fakher Hookah', 'اركيلة تفاحتين - فاخر', 'Two Apples - Fakher Flavor', 'بطعم تفاحتين فاخر', 25000.00, 1, 104, '2026-08-12 02:05:32'),
(89, 16, 'Two Apples - Nakhla Hookah', 'اركيلة تفاحتين - نخلة', 'Two Apples - Nakhla Flavor', 'بطعم تفاحتين نخلة', 25000.00, 1, 105, '2026-08-12 02:06:29'),
(90, 16, 'Mint&Gum Hookah', 'اركيلة علك ونعناع', 'Mint&Gum Flavor', 'بطعم علك ونعناع', 25000.00, 1, 106, '2026-08-12 02:07:24'),
(91, 13, 'Large Baison', 'بايسون كبير', 'Large Baison Drink and Ice', 'مشروب بايسون كبير و ثلج', 12000.00, 1, 15, '2026-08-15 16:53:29'),
(93, 13, 'Soda', 'صودا سادة', 'Soda Drink and Ice', 'مشروب صودا سادة وثلج', 10000.00, 1, 17, '2026-08-15 17:15:52'),
(94, 13, 'Lemon Soda', 'صودا ليمون', 'Lemon Soda Drink and Ice', 'مشروب صودا ليمون وثلج', 12000.00, 1, 18, '2026-08-15 17:16:43'),
(95, 14, 'Cumin and lemon', 'كمون وليمون', 'Cumin and lemon Drink', 'كمون وليمون ساخن', 10000.00, 1, 36, '2026-08-16 18:07:15'),
(96, 14, 'Ginger and honey', 'زنجبيل وعسل', 'Ginger and honey Drink', 'زنجبيل وعسل ساخن', 10000.00, 1, 38, '2026-08-16 18:07:58'),
(97, 14, 'Ginger and Lemon', 'زنجبيل وليمون', 'Ginger and Lemon Drink', 'زنجبيل وليمون ساخن', 10000.00, 1, 39, '2026-08-16 18:09:13'),
(98, 14, 'Herbs', 'زهورات', 'Herbs Drink', 'زهورات ساخنة', 10000.00, 1, 35, '2026-08-16 18:15:40'),
(99, 14, 'Teapot', 'إبريق شاي', 'Hot Teapot', 'إبريق شاي ساخن', 30000.00, 1, 28, '2026-08-16 23:16:06'),
(100, 13, 'Polo', 'بولو', 'Lemon&mint Drink and Ice', 'ليمون ونعناع وثلج', 30000.00, 1, 13, '2026-08-18 00:00:59'),
(101, 13, 'Water', 'ماء', 'Water Bottle', 'مياه باردة', 3000.00, 1, 21, '2026-08-18 00:09:39'),
(102, 13, 'Small Baison', 'بايسون صغير', 'Small Baison Drink and Ice', 'مشروب بايسون صغير وثلج', 10000.00, 1, 14, '2026-08-22 06:21:56'),
(103, 13, '7UP', 'سفن أب', '7UP Drink and Ice', 'علبة سفن أب و ثلج', 10000.00, 1, 19, '2026-08-22 08:33:51'),
(104, 17, 'Strawberry Mojito', 'موهيتو فراولة', 'Strawberry, Lemon, Sparkling water, Mint and Ice', 'فراولة, ليمون, مياه غازية, نعناع وثلج', 30000.00, 1, 41, '2026-08-22 16:01:02'),
(105, 17, 'Berries Mojito', 'موهيتو توت', 'Berries, Lemon, Sparkling water, Mint and Ice', 'توت, ليمون, مياه غازية, نعناع وثلج', 30000.00, 1, 42, '2026-08-22 16:02:47'),
(106, 17, 'Pineapple Mojito', 'موهيتو اناناس', 'Pineapple, Lemon, Sparkling water, Mint and Ice', 'اناناس, ليمون, مياه غازية, نعناع وثلج', 30000.00, 1, 43, '2026-08-22 16:04:18'),
(107, 17, 'Pomegranate Mojito', 'موهيتو رمان', 'Pomegranate, Lemon, Sparkling water, Mint and Ice', 'رمان, ليمون, مياه غازية, نعناع وثلج', 30000.00, 1, 44, '2026-08-22 16:06:20'),
(108, 17, 'Polo Mojito', 'موهيتو بولو', 'Lemon, Sparkling water, Mint and Ice', 'ليمون, مياه غازية, نعناع وثلج', 30000.00, 1, 45, '2026-08-22 16:07:56'),
(110, 19, 'Pancake', 'بان كيك', 'Sauce-Dipped Cake', 'كيك مغطس بالصوص', 15000.00, 1, 1, '2026-08-24 17:18:19'),
(111, 13, 'Maxican Drink', 'مكسيكي', 'Lemon, Carrot, Mint, and Ice.', 'ليمون, جزر،نعناع وثلج', 30000.00, 1, 13, '2026-08-25 09:24:40'),
(112, 13, 'Pepsi', 'بيبسي', 'Pepsi Drink and Ice', 'بيبسي وثلج', 10000.00, 1, 20, '2026-08-25 17:17:38'),
(113, 18, 'Watermelon Slush', 'سلاش بطيخ', 'Refreshing Watermelon Slush', 'سلاش بطيخ منعش', 20000.00, 1, 49, '2026-08-27 01:18:14'),
(114, 18, 'Orange Slush', 'سلاش برتقال', 'Refreshing Orange Slush', 'سلاش برتقال منعش', 20000.00, 1, 50, '2026-08-27 01:20:02'),
(115, 18, 'Pineapple Slush', 'سلاش أناناس', 'Refreshing Pineapple Slush', 'سلاش أناناس منعش', 20000.00, 1, 51, '2026-08-27 01:21:15'),
(116, 18, 'Fruits Slush', 'سلاش فواكه', 'Refreshing Fruits Slush', 'سلاش فواكه منعش', 20000.00, 1, 52, '2026-08-27 01:22:43'),
(117, 13, 'Iced Coffee', 'آيس كوفي', 'Coffee Shot, Milk and Ice', 'شوت قهوة, حليب وثلج', 25000.00, 1, 12, '2026-09-02 19:37:35'),
(118, 14, 'Yerba Mate', 'متة', 'Hot Yerba Mate', 'متة ساخنة', 10000.00, 1, 40, '2026-10-03 15:14:16'),
(119, 14, 'Vanilla Sahlab', 'سحلب بالفانيليا', 'Vanilla Sahlab Hot Drink', 'سحلب بالفانيليا ساخن', 10000.00, 1, 41, '2026-10-03 15:30:09'),
(120, 14, 'Cinnamon Sahlab', 'سحلب بالقرفة', 'Cinnamon Sahlab Hot Drink', 'سحلب بالقرفة ساخن', 10000.00, 1, 42, '2026-10-03 15:30:59'),
(121, 14, 'Coconut Sahlab', 'سحلب بجوز الهند', 'Coconut Sahlab Hot Drink', 'سحلب بجوز الهند ساخن', 10000.00, 1, 43, '2026-10-03 15:31:43'),
(122, 14, 'Classic Hot Chocolate', 'هوت جوكليت كلاسيكي', 'Classic Hot Chocolate Drink', 'هوت جوكليت كلاسيكي ساخن', 15000.00, 1, 32, '2026-10-03 15:34:08'),
(123, 19, 'CU Biscuit', 'بسكويت سي يو', 'Two Pieces of chocolate-Dipped biscuits', 'قطعتين بسكويت مغطس بالشوكولا', 20000.00, 1, 2, '2026-10-07 00:08:28'),
(124, 20, 'Lentil Soup', 'شوربة عدس', 'Hot Lentil Soup', 'شوربة عدس ساخنة', 12000.00, 1, 1, '2026-10-09 03:55:25'),
(125, 20, 'Mushroom Soup', 'شوربة كريم فطر', 'Hot Mushroom Soup', 'شوربة كريم فطر ساخنة', 12000.00, 1, 2, '2026-10-09 03:56:13'),
(126, 20, 'Chicken Soup', 'شوربة كريم دجاج', 'Hot Chicken Soup', 'شوربة كريم دجاج ساخنة', 12000.00, 1, 3, '2026-10-09 03:56:55'),
(127, 20, 'Onion Soup', 'شوربة كريم بصل', 'Hot Onion Soup', 'شوربة كريم بصل ساخنة', 12000.00, 1, 4, '2026-10-09 03:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `cafe_name` varchar(160) NOT NULL DEFAULT 'CU Cafe',
  `cafe_name_ar` varchar(160) DEFAULT 'سي يو',
  `logo` varchar(255) DEFAULT 'cu_logo.png',
  `whatsapp` varchar(40) DEFAULT NULL,
  `facebook` varchar(255) DEFAULT NULL,
  `instagram` varchar(255) DEFAULT NULL,
  `primary_color` varchar(20) NOT NULL DEFAULT '#226F54',
  `secondary_color` varchar(20) NOT NULL DEFAULT '#D33C3C',
  `welcome_text` text DEFAULT NULL,
  `welcome_text_ar` text DEFAULT NULL,
  `contact_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `cafe_name`, `cafe_name_ar`, `logo`, `whatsapp`, `facebook`, `instagram`, `primary_color`, `secondary_color`, `welcome_text`, `welcome_text_ar`, `contact_info`) VALUES
(1, 'CU Cafe', 'سي يو', 'cu_logo.png', '963993386301', '', 'https://www.instagram.com/cu.qamishlo?utm_source=ig_web_button_share_sheet&igsh=ZDNlZDc0MzIxNw==', '#226f54', '#d33c3c', 'we know you\'ll come back (:', 'بلا حب، بس مو بلا بطيخ (:', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_admins_username` (`username`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_categories_status_sort` (`status`,`sort_order`);

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_items_category` (`category_id`),
  ADD KEY `idx_menu_items_status_sort` (`status`,`sort_order`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=128;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `fk_menu_items_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
