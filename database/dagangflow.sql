-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 26 Bulan Mei 2026 pada 09.50
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dagangflow`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `channel` varchar(255) DEFAULT NULL,
  `total_orders` int(11) NOT NULL DEFAULT 0,
  `total_spent` int(11) NOT NULL DEFAULT 0,
  `last_order_date` date DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `name`, `phone`, `channel`, `total_orders`, `total_spent`, `last_order_date`, `note`, `created_at`, `updated_at`) VALUES
(1, 4, 'Bu Tya', '08976484856', 'GrabFood', 2, 3, '2026-05-24', NULL, '2026-05-22 11:45:03', '2026-05-22 11:45:03'),
(2, 5, 'adelia putri', '08999999999', 'Instagram', 0, 0, '2026-05-14', NULL, '2026-05-25 07:34:40', '2026-05-25 07:34:40');

-- --------------------------------------------------------

--
-- Struktur dari tabel `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `category` varchar(255) NOT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `expense_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `category`, `amount`, `payment_method`, `note`, `expense_date`, `created_at`, `updated_at`) VALUES
(2, 5, 'Iklan', 500000, 'E-Wallet', NULL, '2026-05-25', '2026-05-25 07:22:08', '2026-05-25 07:22:08'),
(3, 5, 'Operasional', 3000000, 'Transfer', 'Gaji Karyawan 1 Bulan', '2026-05-25', '2026-05-25 07:24:08', '2026-05-25 07:24:08'),
(4, 6, 'Operasional', 1000000000, 'Cash', 'Korupsi Karyawan', '2026-05-25', '2026-05-25 09:22:34', '2026-05-25 09:35:47'),
(5, 7, 'Bahan Baku', 350000, 'Transfer', 'Belanja bahan kopi, susu, coklat, dan roti', '2026-05-24', '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(6, 7, 'Packaging', 150000, 'Cash', 'Cup, plastik, sedotan, dan stiker', '2026-05-23', '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(7, 7, 'Iklan', 200000, 'E-Wallet', 'Promosi marketplace dan konten sosial media', '2026-05-22', '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(8, 7, 'Operasional', 125000, 'Cash', 'Biaya operasional harian toko', '2026-05-21', '2026-05-25 21:56:32', '2026-05-25 21:56:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_05_17_103313_add_business_fields_to_users_table', 2),
(5, '2026_05_17_112825_create_products_table', 3),
(6, '2026_05_17_114125_create_sales_table', 4),
(7, '2026_05_17_115203_create_expenses_table', 5),
(9, '2026_05_17_115927_create_customers_table', 6),
(10, '2026_05_22_053150_create_password_reset_tokens_table', 7),
(11, '2026_05_25_161835_change_amount_column_type_on_expenses_table', 8),
(12, '2026_05_26_052950_add_superadmin_fields_to_users_table', 9);

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`email`, `token`, `created_at`) VALUES
('mhildannf@gmail.com', '$2y$12$akxBpGXd2.u/zQDTWOnCRebGvfK2dy8TjdZo4Cl5jGMuon0V9qK8O', '2026-05-25 10:45:52');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `selling_price` int(11) NOT NULL DEFAULT 0,
  `cost_price` int(11) NOT NULL DEFAULT 0,
  `stock` int(11) NOT NULL DEFAULT 0,
  `low_stock_limit` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `user_id`, `name`, `category`, `selling_price`, `cost_price`, `stock`, `low_stock_limit`, `created_at`, `updated_at`) VALUES
(1, 4, 'Kopi Susu 250ml', 'Minuman', 15000, 8000, 200, 2, '2026-05-17 04:37:48', '2026-05-18 16:09:19'),
(2, 4, 'Nutrisari Jeruk Peras', 'Minuman', 15000, 10000, 5, 2, '2026-05-17 04:39:48', '2026-05-18 16:07:52'),
(3, 4, 'Joshua', 'Minuman', 10000, 9000, 9, 2, '2026-05-17 19:19:13', '2026-05-21 19:29:44'),
(4, 5, 'Retainer Gigi', 'Barang', 1500000, 950000, 30, 5, '2026-05-25 07:12:12', '2026-05-25 07:12:12'),
(5, 6, 'Sabu', 'Obat', 100000, 80000, 0, 5, '2026-05-25 09:04:16', '2026-05-25 10:23:02'),
(6, 7, 'Kopi Susu Aren', 'Minuman', 18000, 9000, 104, 10, '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(7, 7, 'Es Coklat Premium', 'Minuman', 16000, 7500, 72, 10, '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(8, 7, 'Roti Bakar Coklat Keju', 'Makanan', 22000, 11000, 56, 8, '2026-05-25 21:56:32', '2026-05-25 21:56:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `channel` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `selling_price` int(11) NOT NULL DEFAULT 0,
  `gross_total` int(11) NOT NULL DEFAULT 0,
  `platform_fee` int(11) NOT NULL DEFAULT 0,
  `net_total` int(11) NOT NULL DEFAULT 0,
  `status` varchar(255) NOT NULL DEFAULT 'Selesai',
  `note` text DEFAULT NULL,
  `sale_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sales`
--

INSERT INTO `sales` (`id`, `user_id`, `product_id`, `channel`, `quantity`, `selling_price`, `gross_total`, `platform_fee`, `net_total`, `status`, `note`, `sale_date`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'Offline', 8, 15000, 120000, 2500, 117500, 'Selesai', NULL, '2026-05-17', '2026-05-17 04:49:16', '2026-05-18 16:08:33'),
(2, 4, 2, 'GoFood', 5, 15000, 75000, 2000, 73000, 'Selesai', NULL, '2026-05-17', '2026-05-17 04:50:56', '2026-05-18 16:07:52'),
(3, 4, 3, 'TikTok Shop', 1, 10000, 10000, 0, 10000, 'Selesai', NULL, '2026-05-22', '2026-05-21 19:29:44', '2026-05-21 19:29:44'),
(4, 6, 5, 'GrabFood', 1, 100000, 100000, 0, 100000, 'Belum Bayar', NULL, '2026-05-25', '2026-05-25 09:08:31', '2026-05-25 09:08:31'),
(5, 6, 5, 'Instagram', 19, 100000, 1900000, 10000, 1890000, 'Diproses', NULL, '2026-05-25', '2026-05-25 10:22:37', '2026-05-25 10:23:02'),
(6, 7, 6, 'Shopee', 8, 18000, 144000, 6000, 138000, 'Selesai', 'Data contoh akun demo', '2026-05-25', '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(7, 7, 6, 'GrabFood', 5, 18000, 90000, 7500, 82500, 'Selesai', 'Data contoh akun demo', '2026-05-24', '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(8, 7, 7, 'TikTok Shop', 6, 16000, 96000, 5000, 91000, 'Selesai', 'Data contoh akun demo', '2026-05-23', '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(9, 7, 8, 'WhatsApp', 4, 22000, 88000, 0, 88000, 'Selesai', 'Data contoh akun demo', '2026-05-22', '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(10, 7, 7, 'Offline', 7, 16000, 112000, 0, 112000, 'Selesai', 'Data contoh akun demo', '2026-05-21', '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(11, 7, 6, 'Website', 3, 18000, 54000, 0, 54000, 'Selesai', 'Data contoh akun demo', '2026-05-20', '2026-05-25 21:56:32', '2026-05-25 21:56:32');

-- --------------------------------------------------------

--
-- Struktur dari tabel `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fMIX5uBCw0AAObfqsPUFpv8MJUolhNV3Tr69D3fj', NULL, '127.0.0.1', 'Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/131.0.0.0 Mobile Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWHJpdnprYU5qVUFQVUFoRnhnZWRMR0NmbEhBRlhnckQzWHhEeWR0cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779776477),
('Lh0ogHR9bmVB21ps8ygGHKqtBUAP7CGM8yjBHAq2', NULL, '127.0.0.1', 'Mozilla/5.0 (iPhone; CPU iPhone OS 18_7 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/18.7.6 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoid0J1Zk1WTmFuaVVDWGxONkcxczhydVZDVERweFhNdGt0dGJudXFXQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1779776481),
('VANeYbptXkLDN2DHfKPcK7opItypjBvgTgsssruh', 9, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMXJlaGdjWTg2WExDTzB6WkVYY0dnRFZhaDRLZHJWQkZ4TVVEaWV2VyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6ImFkbWluLmRhc2hib2FyZCI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjk7fQ==', 1779781335);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `business_type` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'owner',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `plan_name` varchar(255) NOT NULL DEFAULT 'Free',
  `subscription_status` varchar(255) NOT NULL DEFAULT 'active',
  `subscription_started_at` timestamp NULL DEFAULT NULL,
  `subscription_ends_at` timestamp NULL DEFAULT NULL,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `name`, `business_name`, `business_type`, `email`, `email_verified_at`, `password`, `role`, `status`, `plan_name`, `subscription_status`, `subscription_started_at`, `subscription_ends_at`, `last_login_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(4, 'Hildan', 'Siren Desk', 'service', 'mhildannf@gmail.com', NULL, '$2y$12$HuY.tJG4ywOoHHmDsTDnwORnx4yOZzF1XXMhSByyPx33qJ4WUAiDm', 'owner', 'active', 'Free', 'active', NULL, NULL, NULL, 'xCr28uPT6kGqGoydB9ezjZDPIdEHje3A6zRP3ShY26IWUk9YHCGYS0CcoE0M', '2026-05-17 03:45:27', '2026-05-21 22:47:23'),
(5, 'adelia putri', 'dental care', NULL, 'adelia@unmer.id', NULL, '$2y$12$wzoKLmA.4srpJ8kyeUsx5.IDrDagrCCC2cD8oHBErLRZAEKv3aEry', 'owner', 'active', 'Free', 'active', NULL, NULL, NULL, NULL, '2026-05-25 07:09:23', '2026-05-25 07:32:35'),
(6, 'Ridho', 'Ridho Audio', 'other', 'ridho@gmail.com', NULL, '$2y$12$qWXzy08mu0qNfLmFRMJhTukpu7os1Pyq0X84/ITfD6DuTzhtVtJlS', 'owner', 'active', 'Free', 'active', NULL, NULL, NULL, NULL, '2026-05-25 09:02:34', '2026-05-25 09:02:34'),
(7, 'Demo Owner', 'Kopi Demo Nusantara', 'Food & Beverage', 'demo@dagangflow.test', NULL, '$2y$12$/uV3/2CQ2IY2clN5Irxwhe7GkNv5wHDO/S1iGXd1H0fe2xGM9IAia', 'owner', 'active', 'Free', 'active', NULL, NULL, NULL, NULL, '2026-05-25 21:56:32', '2026-05-25 21:56:32'),
(8, 'DagangFlow Superadmin', 'DagangFlow', 'Platform', 'admin@dagangflow.test', NULL, '$2y$12$oPoPHU/F.gX/pBa0HErxnOtZgVb6u7Brk/u5pWnpBXkh.x3n.9NiG', 'superadmin', 'active', 'Internal', 'active', '2026-05-25 23:07:10', NULL, NULL, NULL, '2026-05-25 23:07:10', '2026-05-25 23:07:10'),
(9, 'SirenDesk Superadmin', 'DagangFlow', 'Platform', 'sirendeskstudio@gmail.com', NULL, '$2y$12$rzue5.iRt/zG72dFZox7y.gqfdc/HxzbRAqKD/HPtEy0Luq5X9Dlu', 'superadmin', 'active', 'Internal', 'active', '2026-05-25 23:08:55', NULL, '2026-05-25 23:20:11', NULL, '2026-05-25 23:08:55', '2026-05-25 23:20:11');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indeks untuk tabel `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_user_id_foreign` (`user_id`),
  ADD KEY `sales_product_id_foreign` (`product_id`);

--
-- Indeks untuk tabel `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
