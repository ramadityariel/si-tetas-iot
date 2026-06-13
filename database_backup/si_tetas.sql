-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 13, 2026 at 07:10 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `si_tetas`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtitle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `caption` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `subtitle`, `caption`, `slug`, `content`, `thumbnail`, `category`, `author_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Pengenalan Inkubator Pintar', 'Mengenal lebih dekat teknologi inkubator penetas telur.', NULL, 'pengenalan-inkubator-pintar', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula.</p>', 'blog_thumbnails/fwgBt8IFLAzib7swPqfSsioVszAC68mjv6plGnBP.png', 'TEKNOLOGI', 1, '2026-05-14 02:01:26', '2026-05-14 08:56:14', 'published'),
(2, 'Cara Menjaga Suhu', 'Tips dan trik menjaga suhu agar penetasan berhasil optimal.', NULL, 'cara-menjaga-suhu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'dummy-thumbnail.jpg', 'PANDUAN', 1, '2026-05-14 02:01:26', '2026-05-14 02:01:26', 'draft'),
(3, 'Pentingnya Kelembapan', 'Mengapa kelembapan sangat berpengaruh dalam proses penetasan.', NULL, 'pentingnya-kelembapan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.', 'dummy-thumbnail.jpg', 'EDUKASI', 1, '2026-05-14 02:01:26', '2026-05-14 02:01:26', 'draft'),
(4, 'aaa', 'awawa', NULL, 'aaa', '<p>aaa</p>', 'blog_thumbnails/6bLJgreCCYeEnc9iF8onIVOc156WYH3vkykgpgGQ.jpg', 'Teknologi', 1, '2026-05-14 08:26:52', '2026-05-14 08:26:52', 'published'),
(5, 'ini judul', 'ini subjudul', NULL, 'ini-judul', '<p>ini adalah isinya yang panjanggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggggg</p>', 'blog_thumbnails/wEZgjk0loVFuu0r9JYYvLpBZ08LKu32qcYc2OrxI.png', 'Teknologi', 1, '2026-05-14 08:30:20', '2026-05-14 08:30:20', 'published'),
(6, 'artikel ke 6', 'iya', NULL, 'artikel-ke-6', '<p>panjanggggggggggggggggggggggggggggggggggggggggbangettttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttttpokoknyaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa</p>', 'blog_thumbnails/GcmTc1YVqopy5sL3NWfTV7Dz1505M0smTknvNQrh.png', 'Tips & Trik', 1, '2026-05-14 08:57:16', '2026-05-14 08:57:16', 'published'),
(7, 'AA', 'AA', NULL, 'aa', '<p>pada bagian manajemen blog, saat saya klik buat artikel baru dan saya klik terbitkan tidak muncul apa apa (tidak ada pop up berhasil atau tidak). lalu di page manajemen blognya juga ga muncul artikel yg baru saya tulis dan di landing page ataupun page pusat artikel dan panduan juga ga muncul. sama saya juga mau nanya ke kamu, menurut kamu menu yang ada di page tulis artikel baru udah sesuai belum sama kebutuhan artikel kita nantinya?</p>', NULL, 'Tips & Trik', 1, '2026-05-14 09:05:25', '2026-05-14 20:39:58', 'draft'),
(8, 'artikel ke7', 'abcdefg', NULL, 'artikel-ke7', '<p>pada bagian manajemen blog, saat saya klik buat artikel baru dan saya klik terbitkan tidak muncul apa apa (tidak ada pop up berhasil atau tidak). lalu di page manajemen blognya juga ga muncul artikel yg baru saya tulis dan di landing page ataupun page pusat artikel dan panduan juga ga muncul. sama saya juga mau nanya ke kamu, menurut kamu menu yang ada di page tulis artikel baru udah sesuai belum sama kebutuhan artikel kita nantinya?</p>', 'blog_thumbnails/80fzNgbMB8LccTe26Sx0W2HdqcQJF1VcXDTSER0h.png', 'Teknologi', 1, '2026-05-14 20:42:10', '2026-05-14 20:42:10', 'published'),
(9, 'artikel ke8', 'iya', NULL, 'artikel-ke8', '<p>pada bagian manajemen blog, saat saya klik buat artikel baru dan saya klik terbitkan tidak muncul apa apa (tidak ada pop up berhasil atau tidak). lalu di page manajemen blognya juga ga muncul artikel yg baru saya tulis dan di landing page ataupun page pusat artikel dan panduan juga ga muncul. sama saya juga mau nanya ke kamu, menurut kamu menu yang ada di page tulis artikel baru udah sesuai belum sama kebutuhan artikel kita nantinya?pada bagian manajemen blog, saat saya klik buat artikel baru dan saya klik terbitkan tidak muncul apa apa (tidak ada pop up berhasil atau tidak). lalu di page manajemen blognya juga ga muncul artikel yg baru saya tulis dan di landing page ataupun page pusat artikel dan panduan juga ga muncul. sama saya juga mau nanya ke kamu, menurut kamu menu yang ada di page tulis artikel baru udah sesuai belum sama kebutuhan artikel kita nantinya?pada bagian manajemen blog, saat saya klik buat artikel baru dan saya klik terbitkan tidak muncul apa apa (tidak ada pop up berhasil atau tidak). lalu di page manajemen blognya juga ga muncul artikel yg baru saya tulis dan di landing page ataupun page pusat artikel dan panduan juga ga muncul. sama saya juga mau nanya ke kamu, menurut kamu menu yang ada di page tulis artikel baru udah sesuai belum sama kebutuhan artikel kita nantinya?pada bagian manajemen blog, saat saya klik buat artikel baru dan saya klik terbitkan tidak muncul apa apa (tidak ada pop up berhasil atau tidak). lalu di page manajemen blognya juga ga muncul artikel yg baru saya tulis dan di landing page ataupun page pusat artikel dan panduan juga ga muncul. sama saya juga mau nanya ke kamu, menurut kamu menu yang ada di page tulis artikel baru udah sesuai belum sama kebutuhan artikel kita nantinya?</p>', 'blog_thumbnails/CrTVcLYpJoGZ1SFqLI0YJL9iiHEpzcR2HUBKjsGJ.png', 'Teknologi', 1, '2026-05-14 20:45:33', '2026-05-14 20:45:33', 'published'),
(10, 'artikel8', 'subjudul', NULL, 'artikel8', '<p><strong>pada bagian</strong> manajemen blog, saat saya klik buat artikel baru dan saya klik terbitkan tidak muncul apa apa (tidak ada pop up berhasil atau tidak). lalu di page manajemen blognya juga ga muncul artikel yg baru saya tulis dan di landing page ataupun page pusat artikel dan panduan juga ga muncul. sama saya juga mau nanya ke kamu, menurut kamu menu yang ada di page tulis artikel baru udah sesuai belum sama kebutuhan artikel kita nantinya?</p>', 'blog_thumbnails/xzbX3OudP4P3bckFMDbtkxoCnv7aA8yLHeyVpUMD.png', 'Teknologi', 1, '2026-05-15 07:12:52', '2026-05-15 07:13:09', 'published');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candling_histories`
--

CREATE TABLE `candling_histories` (
  `id` bigint UNSIGNED NOT NULL,
  `snapshot_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prediction_result` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `confidence_score` int NOT NULL,
  `admin_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Selesai',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candling_histories`
--

INSERT INTO `candling_histories` (`id`, `snapshot_path`, `prediction_result`, `confidence_score`, `admin_name`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Fertil', 95, 'admin1', 'Selesai', '2026-05-20 15:27:29', '2026-05-20 15:27:29'),
(2, NULL, 'Infertil', 88, 'admin1', 'Selesai', '2026-05-19 15:27:29', '2026-05-19 15:27:29'),
(3, NULL, 'Fertil', 92, 'admin1', 'Selesai', '2026-05-18 15:27:29', '2026-05-18 15:27:29');

-- --------------------------------------------------------

--
-- Table structure for table `egg_candling_details`
--

CREATE TABLE `egg_candling_details` (
  `id` bigint UNSIGNED NOT NULL,
  `candling_id` bigint UNSIGNED NOT NULL,
  `egg_id` varchar(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prediction_result` enum('fertil','infertil','kosong') COLLATE utf8mb4_unicode_ci NOT NULL,
  `confidence_score` decimal(5,2) DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `egg_candling_details`
--

INSERT INTO `egg_candling_details` (`id`, `candling_id`, `egg_id`, `prediction_result`, `confidence_score`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, '01', 'infertil', 93.13, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(2, 1, '02', 'fertil', 92.51, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(3, 1, '03', 'infertil', 97.84, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(4, 1, '04', 'infertil', 94.93, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(5, 1, '05', 'fertil', 91.82, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(6, 1, '06', 'infertil', 98.86, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(7, 1, '07', 'fertil', 92.88, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(8, 1, '08', 'infertil', 96.75, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(9, 1, '09', 'fertil', 98.34, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(10, 1, '10', 'infertil', 94.88, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(11, 1, '11', 'infertil', 90.17, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(12, 1, '12', 'fertil', 96.36, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(13, 1, '13', 'fertil', 98.52, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(14, 1, '14', 'infertil', 95.91, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(15, 1, '15', 'infertil', 99.57, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(16, 1, '16', 'fertil', 96.89, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(17, 1, '17', 'fertil', 98.01, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(18, 1, '18', 'infertil', 98.33, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(19, 1, '19', 'fertil', 97.15, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(20, 1, '20', 'fertil', 95.90, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(21, 1, '21', 'fertil', 92.25, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(22, 1, '22', 'infertil', 91.12, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(23, 1, '23', 'infertil', 99.90, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(24, 1, '24', 'infertil', 97.63, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(25, 1, '25', 'fertil', 99.22, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(26, 1, '26', 'fertil', 93.23, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(27, 1, '27', 'fertil', 92.18, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(28, 1, '28', 'fertil', 99.63, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(29, 1, '29', 'infertil', 93.59, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(30, 1, '30', 'fertil', 98.14, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(31, 1, '31', 'fertil', 98.58, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(32, 1, '32', 'fertil', 96.50, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(33, 1, '33', 'fertil', 98.72, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(34, 1, '34', 'infertil', 96.56, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(35, 1, '35', 'fertil', 98.53, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(36, 1, '36', 'infertil', 95.87, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(37, 1, '37', 'infertil', 99.18, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(38, 1, '38', 'infertil', 91.56, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(39, 1, '39', 'infertil', 98.28, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(40, 1, '40', 'fertil', 95.27, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(41, 1, '41', 'infertil', 91.32, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(42, 1, '42', 'infertil', 94.66, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(43, 1, '43', 'fertil', 90.63, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(44, 1, '44', 'fertil', 97.90, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(45, 1, '45', 'infertil', 93.10, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(46, 1, '46', 'fertil', 91.47, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(47, 1, '47', 'infertil', 91.98, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(48, 1, '48', 'fertil', 97.49, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(49, 1, '49', 'fertil', 93.20, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(50, 1, '50', 'infertil', 93.58, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(51, 1, '51', 'fertil', 98.21, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(52, 1, '52', 'fertil', 97.74, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(53, 1, '53', 'fertil', 97.58, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(54, 1, '54', 'fertil', 95.97, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(55, 1, '55', 'infertil', 93.09, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(56, 1, '56', 'fertil', 93.68, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(57, 1, '57', 'infertil', 99.99, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(58, 1, '58', 'infertil', 93.44, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(59, 1, '59', 'fertil', 95.03, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(60, 1, '60', 'infertil', 92.99, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(61, 1, '61', 'infertil', 97.38, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(62, 1, '62', 'infertil', 95.70, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(63, 1, '63', 'infertil', 92.47, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(64, 1, '64', 'fertil', 97.88, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(65, 1, '65', 'fertil', 96.44, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(66, 1, '66', 'infertil', 90.03, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(67, 1, '67', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(68, 1, '68', 'infertil', 99.94, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(69, 1, '69', 'fertil', 90.44, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(70, 1, '70', 'infertil', 93.06, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(71, 1, '71', 'fertil', 96.87, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(72, 1, '72', 'fertil', 90.94, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(73, 1, '73', 'fertil', 95.37, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(74, 1, '74', 'infertil', 95.02, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(75, 1, '75', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(76, 1, '76', 'infertil', 93.48, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(77, 1, '77', 'infertil', 96.96, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(78, 1, '78', 'fertil', 91.39, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(79, 1, '79', 'infertil', 95.43, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(80, 1, '80', 'infertil', 96.38, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(81, 1, '81', 'fertil', 98.86, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(82, 1, '82', 'fertil', 99.46, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(83, 1, '83', 'fertil', 91.09, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(84, 1, '84', 'fertil', 90.33, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(85, 1, '85', 'infertil', 90.19, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(86, 1, '86', 'infertil', 98.13, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(87, 1, '87', 'infertil', 90.85, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(88, 1, '88', 'infertil', 91.59, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(89, 2, '01', 'fertil', 94.28, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(90, 2, '02', 'fertil', 91.04, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(91, 2, '03', 'infertil', 97.99, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(92, 2, '04', 'fertil', 95.94, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(93, 2, '05', 'fertil', 97.67, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(94, 2, '06', 'fertil', 98.23, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(95, 2, '07', 'infertil', 93.25, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(96, 2, '08', 'infertil', 93.45, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(97, 2, '09', 'infertil', 90.98, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(98, 2, '10', 'fertil', 98.97, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(99, 2, '11', 'infertil', 90.56, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(100, 2, '12', 'fertil', 92.19, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(101, 2, '13', 'infertil', 91.11, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(102, 2, '14', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(103, 2, '15', 'infertil', 91.69, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(104, 2, '16', 'infertil', 99.39, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(105, 2, '17', 'fertil', 99.44, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(106, 2, '18', 'infertil', 98.15, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(107, 2, '19', 'infertil', 98.55, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(108, 2, '20', 'infertil', 99.46, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(109, 2, '21', 'fertil', 94.93, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(110, 2, '22', 'fertil', 95.08, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(111, 2, '23', 'infertil', 95.34, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(112, 2, '24', 'infertil', 97.50, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(113, 2, '25', 'infertil', 94.05, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(114, 2, '26', 'fertil', 93.90, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(115, 2, '27', 'infertil', 91.98, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(116, 2, '28', 'infertil', 91.01, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(117, 2, '29', 'fertil', 91.27, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(118, 2, '30', 'fertil', 92.44, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(119, 2, '31', 'fertil', 90.02, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(120, 2, '32', 'fertil', 92.32, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(121, 2, '33', 'infertil', 92.28, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(122, 2, '34', 'infertil', 94.05, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(123, 2, '35', 'fertil', 92.32, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(124, 2, '36', 'fertil', 95.66, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(125, 2, '37', 'fertil', 99.25, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(126, 2, '38', 'fertil', 90.17, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(127, 2, '39', 'infertil', 94.69, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(128, 2, '40', 'infertil', 97.67, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(129, 2, '41', 'fertil', 95.30, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(130, 2, '42', 'fertil', 95.58, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(131, 2, '43', 'infertil', 90.33, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(132, 2, '44', 'fertil', 94.20, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(133, 2, '45', 'fertil', 90.24, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(134, 2, '46', 'infertil', 93.02, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(135, 2, '47', 'infertil', 95.46, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(136, 2, '48', 'fertil', 94.20, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(137, 2, '49', 'infertil', 91.70, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(138, 2, '50', 'infertil', 97.61, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(139, 2, '51', 'infertil', 99.43, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(140, 2, '52', 'fertil', 93.53, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(141, 2, '53', 'fertil', 98.11, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(142, 2, '54', 'infertil', 94.82, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(143, 2, '55', 'infertil', 92.98, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(144, 2, '56', 'infertil', 99.14, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(145, 2, '57', 'infertil', 90.89, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(146, 2, '58', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(147, 2, '59', 'infertil', 90.10, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(148, 2, '60', 'fertil', 91.43, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(149, 2, '61', 'fertil', 92.35, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(150, 2, '62', 'infertil', 96.16, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(151, 2, '63', 'fertil', 92.77, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(152, 2, '64', 'fertil', 91.73, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(153, 2, '65', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(154, 2, '66', 'infertil', 90.27, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(155, 2, '67', 'fertil', 93.45, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(156, 2, '68', 'fertil', 92.74, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(157, 2, '69', 'fertil', 99.53, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(158, 2, '70', 'infertil', 94.96, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(159, 2, '71', 'fertil', 95.43, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(160, 2, '72', 'infertil', 98.79, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(161, 2, '73', 'infertil', 95.10, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(162, 2, '74', 'fertil', 92.86, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(163, 2, '75', 'infertil', 94.89, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(164, 2, '76', 'infertil', 91.17, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(165, 2, '77', 'fertil', 96.76, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(166, 2, '78', 'infertil', 95.13, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(167, 2, '79', 'infertil', 94.00, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(168, 2, '80', 'infertil', 98.31, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(169, 2, '81', 'fertil', 95.12, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(170, 2, '82', 'infertil', 97.71, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(171, 2, '83', 'infertil', 94.96, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(172, 2, '84', 'infertil', 95.61, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(173, 2, '85', 'infertil', 98.25, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(174, 2, '86', 'infertil', 96.01, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(175, 2, '87', 'infertil', 97.10, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(176, 2, '88', 'infertil', 99.19, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(177, 3, '01', 'fertil', 93.62, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(178, 3, '02', 'infertil', 95.81, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(179, 3, '03', 'infertil', 94.11, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(180, 3, '04', 'infertil', 92.47, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(181, 3, '05', 'infertil', 93.45, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(182, 3, '06', 'infertil', 98.45, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(183, 3, '07', 'infertil', 95.22, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(184, 3, '08', 'infertil', 98.60, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(185, 3, '09', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(186, 3, '10', 'fertil', 90.03, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(187, 3, '11', 'infertil', 90.42, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(188, 3, '12', 'infertil', 93.80, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(189, 3, '13', 'infertil', 96.48, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(190, 3, '14', 'infertil', 98.14, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(191, 3, '15', 'infertil', 94.69, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(192, 3, '16', 'infertil', 90.46, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(193, 3, '17', 'infertil', 91.60, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(194, 3, '18', 'fertil', 99.68, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(195, 3, '19', 'fertil', 92.40, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(196, 3, '20', 'infertil', 92.92, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(197, 3, '21', 'infertil', 99.30, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(198, 3, '22', 'infertil', 98.00, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(199, 3, '23', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(200, 3, '24', 'infertil', 91.20, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(201, 3, '25', 'infertil', 93.90, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(202, 3, '26', 'infertil', 99.08, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(203, 3, '27', 'infertil', 98.58, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(204, 3, '28', 'fertil', 99.44, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(205, 3, '29', 'infertil', 97.48, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(206, 3, '30', 'infertil', 92.93, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(207, 3, '31', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(208, 3, '32', 'fertil', 96.71, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(209, 3, '33', 'fertil', 97.10, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(210, 3, '34', 'fertil', 96.93, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(211, 3, '35', 'infertil', 98.38, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(212, 3, '36', 'fertil', 91.71, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(213, 3, '37', 'fertil', 94.20, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(214, 3, '38', 'infertil', 93.80, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(215, 3, '39', 'infertil', 90.72, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(216, 3, '40', 'infertil', 95.45, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(217, 3, '41', 'fertil', 98.25, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(218, 3, '42', 'infertil', 91.87, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(219, 3, '43', 'infertil', 92.15, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(220, 3, '44', 'infertil', 95.89, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(221, 3, '45', 'infertil', 93.20, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(222, 3, '46', 'infertil', 94.05, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(223, 3, '47', 'fertil', 93.20, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(224, 3, '48', 'infertil', 98.17, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(225, 3, '49', 'infertil', 95.95, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(226, 3, '50', 'infertil', 95.18, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(227, 3, '51', 'fertil', 92.49, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(228, 3, '52', 'fertil', 96.71, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(229, 3, '53', 'infertil', 91.38, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(230, 3, '54', 'fertil', 90.51, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(231, 3, '55', 'infertil', 97.08, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(232, 3, '56', 'fertil', 93.44, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(233, 3, '57', 'infertil', 91.75, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(234, 3, '58', 'fertil', 98.79, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(235, 3, '59', 'fertil', 92.54, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(236, 3, '60', 'fertil', 97.78, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(237, 3, '61', 'infertil', 96.13, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(238, 3, '62', 'infertil', 95.24, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(239, 3, '63', 'fertil', 96.60, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(240, 3, '64', 'infertil', 91.78, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(241, 3, '65', 'fertil', 99.24, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(242, 3, '66', 'infertil', 90.09, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(243, 3, '67', 'infertil', 93.36, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(244, 3, '68', 'fertil', 94.44, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(245, 3, '69', 'infertil', 94.36, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(246, 3, '70', 'kosong', NULL, 'Tidak ada objek terdeteksi', '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(247, 3, '71', 'fertil', 90.26, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(248, 3, '72', 'fertil', 93.74, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(249, 3, '73', 'fertil', 90.91, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(250, 3, '74', 'fertil', 91.45, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(251, 3, '75', 'infertil', 92.39, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(252, 3, '76', 'fertil', 95.64, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(253, 3, '77', 'infertil', 98.48, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(254, 3, '78', 'infertil', 92.29, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(255, 3, '79', 'fertil', 90.24, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(256, 3, '80', 'fertil', 98.68, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(257, 3, '81', 'fertil', 97.62, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(258, 3, '82', 'infertil', 90.37, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(259, 3, '83', 'fertil', 98.75, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(260, 3, '84', 'fertil', 92.02, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(261, 3, '85', 'fertil', 95.22, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(262, 3, '86', 'infertil', 97.67, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(263, 3, '87', 'infertil', 97.87, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46'),
(264, 3, '88', 'infertil', 92.11, NULL, '2026-05-21 13:46:46', '2026-05-21 13:46:46');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hatch_predictions`
--

CREATE TABLE `hatch_predictions` (
  `id` bigint UNSIGNED NOT NULL,
  `batch_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `estimated_date` date NOT NULL,
  `confidence_score` double NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hatch_predictions`
--

INSERT INTO `hatch_predictions` (`id`, `batch_id`, `estimated_date`, `confidence_score`, `status`, `created_at`, `updated_at`) VALUES
(1, 'B-20260514-01', '2026-05-17', 92, 'Mulai Pipping (Retakan)', '2026-05-14 02:01:26', '2026-05-14 02:01:26'),
(2, 'B-20260514-01', '2026-05-20', 85, 'Puncak Penetasan', '2026-05-14 02:01:26', '2026-05-14 02:01:26'),
(3, 'B-20260514-01', '2026-05-21', 78, 'Finalisasi & Pembersihan', '2026-05-14 02:01:26', '2026-05-14 02:01:26');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_13_000000_create_articles_table', 1),
(5, '2026_05_13_000001_create_sensor_logs_table', 1),
(6, '2026_05_13_000002_create_hatch_predictions_table', 1),
(7, '2026_05_14_141824_add_status_to_articles_table', 2),
(8, '2026_05_14_160000_drop_tags_from_articles_table', 3),
(9, '2026_05_14_170000_make_articles_thumbnail_nullable', 4),
(10, '2026_05_14_180000_add_caption_to_articles_table', 5),
(11, '2026_05_15_033526_drop_tags_from_articles_table', 6),
(12, '2026_05_15_105911_add_last_login_at_to_users_table', 7),
(13, '2026_05_15_170412_add_phone_and_address_to_users_table', 8),
(14, '2026_05_20_000000_create_candling_histories_table', 8),
(15, '2026_05_21_203905_create_egg_candling_details_table', 9);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sensor_logs`
--

CREATE TABLE `sensor_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `temperature` double NOT NULL,
  `humidity` double NOT NULL,
  `fan_status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sensor_logs`
--

INSERT INTO `sensor_logs` (`id`, `temperature`, `humidity`, `fan_status`, `created_at`, `updated_at`) VALUES
(1, 36.9, 64, 0, '2026-05-14 01:41:26', '2026-05-14 01:41:26'),
(2, 38.4, 60, 1, '2026-05-14 01:42:26', '2026-05-14 01:42:26'),
(3, 36.9, 58, 0, '2026-05-14 01:43:26', '2026-05-14 01:43:26'),
(4, 37.4, 64, 1, '2026-05-14 01:44:26', '2026-05-14 01:44:26'),
(5, 36.8, 63, 0, '2026-05-14 01:45:26', '2026-05-14 01:45:26'),
(6, 37.5, 65, 0, '2026-05-14 01:46:26', '2026-05-14 01:46:26'),
(7, 37.3, 61, 0, '2026-05-14 01:47:26', '2026-05-14 01:47:26'),
(8, 38.2, 65, 0, '2026-05-14 01:48:26', '2026-05-14 01:48:26'),
(9, 36.7, 58, 0, '2026-05-14 01:49:26', '2026-05-14 01:49:26'),
(10, 37.9, 59, 0, '2026-05-14 01:50:26', '2026-05-14 01:50:26'),
(11, 37.8, 59, 0, '2026-05-14 01:51:26', '2026-05-14 01:51:26'),
(12, 37.7, 56, 0, '2026-05-14 01:52:26', '2026-05-14 01:52:26'),
(13, 36.8, 57, 1, '2026-05-14 01:53:26', '2026-05-14 01:53:26'),
(14, 36.9, 64, 0, '2026-05-14 01:54:26', '2026-05-14 01:54:26'),
(15, 36.5, 58, 0, '2026-05-14 01:55:26', '2026-05-14 01:55:26'),
(16, 37.3, 58, 1, '2026-05-14 01:56:26', '2026-05-14 01:56:26'),
(17, 37.3, 63, 1, '2026-05-14 01:57:26', '2026-05-14 01:57:26'),
(18, 37.7, 64, 0, '2026-05-14 01:58:26', '2026-05-14 01:58:26'),
(19, 36.8, 64, 1, '2026-05-14 01:59:26', '2026-05-14 01:59:26'),
(20, 36.6, 61, 1, '2026-05-14 02:00:26', '2026-05-14 02:00:26');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6IS3B9lc0S0zBF0vSd0hlgF8HrrQRfsKQ5PwdrKh', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'eyJfdG9rZW4iOiI5Z1VyZWlRV3kzSDZuUmpCc3FiYUF5bVJmWU1xVFRQbEhhYWFjYm5YIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL3NpLXRldGFzLWFwcC50ZXN0Iiwicm91dGUiOiJob21lIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9jYWxlIjoiZW4ifQ==', 1781331164),
('rhJrBnCPRO0Akajp3AYMJsgNqw05pJqkVr036J8S', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'eyJfdG9rZW4iOiJSQUt0TTdLRGhYN0xMYVBTQWhGTzU0aWlBYWRyNmJYOTlHbWt4MmxGIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvc2ktdGV0YXMtYXBwLnRlc3QiLCJyb3V0ZSI6ImhvbWUifX0=', 1780728611);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'admin',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`, `last_login_at`) VALUES
(1, 'TNK SV IPB', 'admin@sitetas.id', NULL, '$2y$12$x9fXTttfaPkRvDiJuxUP7euyrQMkMd2/KQck6OfdTIMvdxh6jYda2', 'super_admin', NULL, '2026-05-14 02:01:25', '2026-06-06 06:44:52', '2026-06-06 06:44:52'),
(2, 'admin1', 'admin1@sitetas.id', NULL, '$2y$12$pRK.fo246LAq6kgmrwvg8uKR36EYUaE4yOG07pW/K/doZ9sl7JxAW', 'admin', NULL, '2026-05-14 06:42:43', '2026-05-22 04:24:22', '2026-05-22 04:24:22'),
(3, 'tikkum', 'admin2@sitetas.id', NULL, '$2y$12$DtlG9bBifgwhw5Irm82LLuu4vY3vhYLT9Zb55fzVOnaB5JfJLs/z.', 'admin', NULL, '2026-06-06 04:52:06', '2026-06-06 04:54:13', '2026-06-06 04:54:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `articles_slug_unique` (`slug`),
  ADD KEY `articles_author_id_foreign` (`author_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `candling_histories`
--
ALTER TABLE `candling_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `egg_candling_details`
--
ALTER TABLE `egg_candling_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `egg_candling_details_candling_id_foreign` (`candling_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `hatch_predictions`
--
ALTER TABLE `hatch_predictions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `candling_histories`
--
ALTER TABLE `candling_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `egg_candling_details`
--
ALTER TABLE `egg_candling_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=265;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hatch_predictions`
--
ALTER TABLE `hatch_predictions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `egg_candling_details`
--
ALTER TABLE `egg_candling_details`
  ADD CONSTRAINT `egg_candling_details_candling_id_foreign` FOREIGN KEY (`candling_id`) REFERENCES `candling_histories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
