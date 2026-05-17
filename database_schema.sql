-- ============================================
-- PlatePal Database Schema Export
-- Database: project_it9
-- Generated: 2024
-- Laravel Version: 11.x
-- ============================================

SET FOREIGN_KEY_CHECKS=0;

-- ============================================
-- DROP EXISTING TABLES
-- ============================================

DROP TABLE IF EXISTS `booking_items`;
DROP TABLE IF EXISTS `reviews`;
DROP TABLE IF EXISTS `messages`;
DROP TABLE IF EXISTS `saved_caterers`;
DROP TABLE IF EXISTS `system_feedback`;
DROP TABLE IF EXISTS `bookings`;
DROP TABLE IF EXISTS `packages`;
DROP TABLE IF EXISTS `menu_items`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;

-- ============================================
-- CORE TABLES
-- ============================================

-- Users Table
CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('client','caterer','admin') NOT NULL DEFAULT 'client',
  `phone` varchar(255) DEFAULT NULL,
  `business_name` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `rejection_reason` text DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `reviews_count` int(11) NOT NULL DEFAULT 0,
  `average_rating` decimal(3,2) DEFAULT NULL,
  `auto_feature_reviews` tinyint(1) NOT NULL DEFAULT 1,
  `about` text DEFAULT NULL,
  `gallery` json DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  KEY `users_role_index` (`role`),
  KEY `users_status_index` (`status`),
  KEY `users_is_featured_index` (`is_featured`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Password Reset Tokens Table
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Sessions Table
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bookings Table
CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `caterer_id` bigint(20) UNSIGNED NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `guests` int(11) NOT NULL DEFAULT 0,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `decline_reason` text DEFAULT NULL,
  `package_id` bigint(20) UNSIGNED DEFAULT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `package_price` decimal(10,2) DEFAULT NULL,
  `price_per_head` decimal(10,2) DEFAULT NULL,
  `final_price` decimal(10,2) DEFAULT NULL,
  `client_budget` decimal(10,2) DEFAULT NULL,
  `viewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_user_id_foreign` (`user_id`),
  KEY `bookings_caterer_id_foreign` (`caterer_id`),
  KEY `bookings_status_index` (`status`),
  KEY `bookings_event_date_index` (`event_date`),
  CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bookings_caterer_id_foreign` FOREIGN KEY (`caterer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Packages Table
CREATE TABLE `packages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `caterer_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `min_guests` int(11) NOT NULL DEFAULT 1,
  `includes` text DEFAULT NULL,
  `status` enum('draft','live') NOT NULL DEFAULT 'draft',
  `category` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `packages_caterer_id_foreign` (`caterer_id`),
  KEY `packages_status_index` (`status`),
  KEY `packages_category_index` (`category`),
  CONSTRAINT `packages_caterer_id_foreign` FOREIGN KEY (`caterer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Menu Items Table
CREATE TABLE `menu_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `caterer_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `unit` varchar(255) NOT NULL DEFAULT 'head',
  `type` enum('menu','addon') NOT NULL DEFAULT 'menu',
  `category` varchar(255) NOT NULL DEFAULT 'main',
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_items_caterer_id_foreign` (`caterer_id`),
  KEY `menu_items_type_index` (`type`),
  KEY `menu_items_category_index` (`category`),
  CONSTRAINT `menu_items_caterer_id_foreign` FOREIGN KEY (`caterer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Booking Items Table
CREATE TABLE `booking_items` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `menu_item_id` bigint(20) UNSIGNED NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `item_type` varchar(255) NOT NULL,
  `item_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `booking_items_booking_id_foreign` (`booking_id`),
  KEY `booking_items_menu_item_id_foreign` (`menu_item_id`),
  CONSTRAINT `booking_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  CONSTRAINT `booking_items_menu_item_id_foreign` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Reviews Table
CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `client_id` bigint(20) UNSIGNED DEFAULT NULL,
  `caterer_id` bigint(20) UNSIGNED NOT NULL,
  `reviewer_name` varchar(255) DEFAULT NULL,
  `package_name` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `body` text NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `status` enum('public','hidden') NOT NULL DEFAULT 'public',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_auto_featured` tinyint(1) NOT NULL DEFAULT 0,
  `caterer_reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `reported_at` timestamp NULL DEFAULT NULL,
  `report_reason` text DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reviews_booking_id_unique` (`booking_id`),
  KEY `reviews_client_id_foreign` (`client_id`),
  KEY `reviews_caterer_id_foreign` (`caterer_id`),
  KEY `reviews_caterer_id_status_index` (`caterer_id`,`status`),
  KEY `reviews_caterer_id_is_featured_index` (`caterer_id`,`is_featured`),
  CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reviews_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reviews_caterer_id_foreign` FOREIGN KEY (`caterer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Messages Table
CREATE TABLE `messages` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `caterer_id` bigint(20) UNSIGNED NOT NULL,
  `body` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `sender` enum('client','caterer') NOT NULL,
  `attachments` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_user_id_foreign` (`user_id`),
  KEY `messages_caterer_id_foreign` (`caterer_id`),
  KEY `messages_user_id_caterer_id_index` (`user_id`,`caterer_id`),
  KEY `messages_is_read_index` (`is_read`),
  CONSTRAINT `messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_caterer_id_foreign` FOREIGN KEY (`caterer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Saved Caterers Table
CREATE TABLE `saved_caterers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `caterer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `saved_caterers_user_id_caterer_id_unique` (`user_id`,`caterer_id`),
  KEY `saved_caterers_caterer_id_foreign` (`caterer_id`),
  CONSTRAINT `saved_caterers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `saved_caterers_caterer_id_foreign` FOREIGN KEY (`caterer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- System Feedback Table
CREATE TABLE `system_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `role` varchar(30) DEFAULT NULL,
  `type` varchar(30) NOT NULL,
  `rating` tinyint(3) UNSIGNED DEFAULT NULL,
  `message` text NOT NULL,
  `page_url` varchar(500) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'new',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `system_feedback_user_id_foreign` (`user_id`),
  KEY `system_feedback_status_index` (`status`),
  KEY `system_feedback_type_index` (`type`),
  CONSTRAINT `system_feedback_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- SYSTEM TABLES (Cache, Queue, Jobs)
-- ============================================

-- Cache Table
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cache Locks Table
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Jobs Table
CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Job Batches Table
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
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Failed Jobs Table
CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS=1;

-- ============================================
-- SAMPLE DATA (Optional - for testing)
-- ============================================

-- Insert Admin User
INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `status`, `email_verified_at`, `created_at`, `updated_at`) 
VALUES (1, 'Admin', 'admin@platepal.com', '$2y$12$LQv3c1yycaaNVpYnXYqQqeXVsJ5exsqdsitwQzWWxIBWppTUAB.3u', 'admin', 'approved', NOW(), NOW(), NOW());
-- Password: password

-- ============================================
-- NOTES
-- ============================================
-- 1. Run Laravel migrations for automatic schema management
-- 2. Use seeders for sample data: php artisan db:seed
-- 3. Default admin password: password (change immediately)
-- 4. Ensure proper indexes for performance
-- 5. Regular backup recommended for production
