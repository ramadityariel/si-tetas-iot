-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 25, 2026 at 07:49 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.17

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
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `subtitle`, `slug`, `content`, `thumbnail`, `category`, `author_id`, `created_at`, `updated_at`, `status`) VALUES
(1, 'Pengenalan Inkubator Pintar', 'Mengenal lebih dekat teknologi inkubator penetas telur.', 'pengenalan-inkubator-pintar', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in dui mauris. Vivamus hendrerit arcu sed erat molestie vehicula.', 'dummy-thumbnail.jpg', 'TEKNOLOGI', 1, '2026-05-25 07:38:47', '2026-05-25 07:38:47', 'draft'),
(2, 'Cara Menjaga Suhu', 'Tips dan trik menjaga suhu agar penetasan berhasil optimal.', 'cara-menjaga-suhu', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'dummy-thumbnail.jpg', 'PANDUAN', 1, '2026-05-25 07:38:47', '2026-05-25 07:38:47', 'draft'),
(3, 'Pentingnya Kelembapan', 'Mengapa kelembapan sangat berpengaruh dalam proses penetasan.', 'pentingnya-kelembapan', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi.', 'dummy-thumbnail.jpg', 'EDUKASI', 1, '2026-05-25 07:38:47', '2026-05-25 07:38:47', 'draft');

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
(1, 'B-20260525-01', '2026-05-28', 92, 'Mulai Pipping (Retakan)', '2026-05-25 07:38:47', '2026-05-25 07:38:47'),
(2, 'B-20260525-01', '2026-05-31', 85, 'Puncak Penetasan', '2026-05-25 07:38:47', '2026-05-25 07:38:47'),
(3, 'B-20260525-01', '2026-06-01', 78, 'Finalisasi & Pembersihan', '2026-05-25 07:38:47', '2026-05-25 07:38:47');

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
(7, '2026_05_14_141824_add_status_to_articles_table', 1),
(8, '2026_05_15_033526_drop_tags_from_articles_table', 1),
(9, '2026_05_15_105911_add_last_login_at_to_users_table', 1),
(10, '2026_05_15_170412_add_phone_and_address_to_users_table', 1),
(11, '2026_05_20_000000_create_candling_histories_table', 1),
(12, '2026_05_21_203905_create_egg_candling_details_table', 1);

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
(1, 37.2, 57, 0, '2026-05-25 07:18:47', '2026-05-25 07:18:47'),
(2, 38.1, 62, 1, '2026-05-25 07:19:47', '2026-05-25 07:19:47'),
(3, 36.5, 62, 0, '2026-05-25 07:20:47', '2026-05-25 07:20:47'),
(4, 36.8, 59, 1, '2026-05-25 07:21:47', '2026-05-25 07:21:47'),
(5, 37.9, 62, 1, '2026-05-25 07:22:47', '2026-05-25 07:22:47'),
(6, 38.3, 55, 1, '2026-05-25 07:23:47', '2026-05-25 07:23:47'),
(7, 38.3, 65, 1, '2026-05-25 07:24:47', '2026-05-25 07:24:47'),
(8, 36.6, 57, 0, '2026-05-25 07:25:47', '2026-05-25 07:25:47'),
(9, 38.3, 61, 0, '2026-05-25 07:26:47', '2026-05-25 07:26:47'),
(10, 37.9, 65, 1, '2026-05-25 07:27:47', '2026-05-25 07:27:47'),
(11, 38, 56, 1, '2026-05-25 07:28:47', '2026-05-25 07:28:47'),
(12, 36.7, 63, 1, '2026-05-25 07:29:47', '2026-05-25 07:29:47'),
(13, 37.7, 58, 1, '2026-05-25 07:30:47', '2026-05-25 07:30:47'),
(14, 38.1, 65, 1, '2026-05-25 07:31:47', '2026-05-25 07:31:47'),
(15, 37.4, 59, 1, '2026-05-25 07:32:47', '2026-05-25 07:32:47'),
(16, 36.6, 65, 1, '2026-05-25 07:33:47', '2026-05-25 07:33:47'),
(17, 38.5, 58, 1, '2026-05-25 07:34:47', '2026-05-25 07:34:47'),
(18, 38.5, 59, 1, '2026-05-25 07:35:47', '2026-05-25 07:35:47'),
(19, 37.1, 61, 0, '2026-05-25 07:36:47', '2026-05-25 07:36:47'),
(20, 37.6, 58, 0, '2026-05-25 07:37:47', '2026-05-25 07:37:47');

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
('wMemJfZPwWUageBX5vI13NV63TWDzUQAcM7HhEGJ', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36 Edg/148.0.0.0', 'eyJfdG9rZW4iOiI3MW9zSEhhU1hueUFnWlpHNHVTY1R1M0h1SDM5ZnZoUUlEZjZGWGs4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pblwvcHJlZGlrc2kiLCJyb3V0ZSI6InByZWRpa3NpIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjF9', 1779694932);

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
(1, 'TNK SV IPB', 'admin@sitetas.id', NULL, '$2y$12$EegmXjh1TfPS/I8Ql9xflupxUDn3QBeG1Aez0vPP6GDn5RUcXUR92', 'super_admin', NULL, '2026-05-25 07:38:47', '2026-05-25 07:42:08', '2026-05-25 07:42:08');

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
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `candling_histories`
--
ALTER TABLE `candling_histories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `egg_candling_details`
--
ALTER TABLE `egg_candling_details`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sensor_logs`
--
ALTER TABLE `sensor_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
