-- =====================================================================
-- Sanjay Sales Admin Panel - MySQL / MariaDB database
-- Import in phpMyAdmin (Import tab) or run:  mysql -u root -p < sanjay_sales.sql
-- Generated from the Laravel migrations. All tables use InnoDB and
-- indexed text columns use varchar(191), so it works on MySQL 5.5+ / MariaDB.
-- Admin login: admin@sanjaysales.com / Admin@123  (change after first login)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS `sanjay_sales` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sanjay_sales`;

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- Clean up tables left over from earlier (failed) attempts.
-- WARNING: this removes these tables and their data if they already exist.
-- ---------------------------------------------------------------------
DROP TABLE IF EXISTS `migrations`;
DROP TABLE IF EXISTS `clients`;
DROP TABLE IF EXISTS `subcategories`;
DROP TABLE IF EXISTS `blogs`;
DROP TABLE IF EXISTS `videos`;
DROP TABLE IF EXISTS `galleries`;
DROP TABLE IF EXISTS `product_images`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `failed_jobs`;
DROP TABLE IF EXISTS `job_batches`;
DROP TABLE IF EXISTS `jobs`;
DROP TABLE IF EXISTS `cache_locks`;
DROP TABLE IF EXISTS `cache`;
DROP TABLE IF EXISTS `sessions`;
DROP TABLE IF EXISTS `password_reset_tokens`;
DROP TABLE IF EXISTS `users`;

-- ---------------------------------------------------------------------
-- Tables
-- ---------------------------------------------------------------------
-- 0001_01_01_000000_create_users_table
create table `users` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `email` varchar(191) not null, `email_verified_at` timestamp null, `password` varchar(191) not null, `remember_token` varchar(100) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `users` add unique `users_email_unique`(`email`);
create table `password_reset_tokens` (`email` varchar(191) not null, `token` varchar(191) not null, `created_at` timestamp null, primary key (`email`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
create table `sessions` (`id` varchar(191) not null, `user_id` bigint unsigned null, `ip_address` varchar(45) null, `user_agent` text null, `payload` longtext not null, `last_activity` int not null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `sessions` add index `sessions_user_id_index`(`user_id`);
alter table `sessions` add index `sessions_last_activity_index`(`last_activity`);

-- 0001_01_01_000001_create_cache_table
create table `cache` (`key` varchar(191) not null, `value` mediumtext not null, `expiration` int not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `cache` add index `cache_expiration_index`(`expiration`);
create table `cache_locks` (`key` varchar(191) not null, `owner` varchar(191) not null, `expiration` int not null, primary key (`key`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `cache_locks` add index `cache_locks_expiration_index`(`expiration`);

-- 0001_01_01_000002_create_jobs_table
create table `jobs` (`id` bigint unsigned not null auto_increment primary key, `queue` varchar(191) not null, `payload` longtext not null, `attempts` tinyint unsigned not null, `reserved_at` int unsigned null, `available_at` int unsigned not null, `created_at` int unsigned not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `jobs` add index `jobs_queue_index`(`queue`);
create table `job_batches` (`id` varchar(191) not null, `name` varchar(191) not null, `total_jobs` int not null, `pending_jobs` int not null, `failed_jobs` int not null, `failed_job_ids` longtext not null, `options` mediumtext null, `cancelled_at` int null, `created_at` int not null, `finished_at` int null, primary key (`id`)) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
create table `failed_jobs` (`id` bigint unsigned not null auto_increment primary key, `uuid` varchar(191) not null, `connection` text not null, `queue` text not null, `payload` longtext not null, `exception` longtext not null, `failed_at` timestamp not null default CURRENT_TIMESTAMP) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `failed_jobs` add unique `failed_jobs_uuid_unique`(`uuid`);

-- 2026_11_03_000001_create_admins_table
create table `admins` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `email` varchar(191) not null, `phone` varchar(30) null, `avatar` varchar(191) null, `password` varchar(191) not null, `remember_token` varchar(100) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `admins` add unique `admins_email_unique`(`email`);

-- 2026_11_03_000002_create_categories_table
create table `categories` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) not null, `slug` varchar(191) not null, `description` text null, `image` varchar(191) null, `sort_order` int not null default '0', `status` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `categories` add unique `categories_slug_unique`(`slug`);

-- 2026_11_03_000003_create_products_table
create table `products` (`id` bigint unsigned not null auto_increment primary key, `category_id` bigint unsigned not null, `name` varchar(191) not null, `slug` varchar(191) not null, `brand` varchar(191) null, `price` varchar(191) null, `short_description` text null, `description` longtext null, `featured` tinyint(1) not null default '0', `status` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `products` add constraint `products_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) on delete restrict;
alter table `products` add unique `products_slug_unique`(`slug`);
create table `product_images` (`id` bigint unsigned not null auto_increment primary key, `product_id` bigint unsigned not null, `path` varchar(191) not null, `sort_order` int not null default '0', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `product_images` add constraint `product_images_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete cascade;

-- 2026_11_03_000004_create_galleries_table
create table `galleries` (`id` bigint unsigned not null auto_increment primary key, `title` varchar(191) not null, `image` varchar(191) not null, `sort_order` int not null default '0', `status` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;

-- 2026_11_03_000005_create_videos_table
create table `videos` (`id` bigint unsigned not null auto_increment primary key, `title` varchar(191) not null, `youtube_url` varchar(191) null, `sort_order` int not null default '0', `status` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;

-- 2026_11_03_000006_create_blogs_table
create table `blogs` (`id` bigint unsigned not null auto_increment primary key, `title` varchar(191) not null, `slug` varchar(191) not null, `excerpt` text null, `content` longtext not null, `image` varchar(191) null, `author` varchar(191) null, `meta_title` varchar(191) null, `meta_description` varchar(500) null, `status` tinyint(1) not null default '1', `published_at` date null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `blogs` add unique `blogs_slug_unique`(`slug`);

-- 2026_11_03_000007_create_subcategories_table
create table `subcategories` (`id` bigint unsigned not null auto_increment primary key, `category_id` bigint unsigned not null, `name` varchar(191) not null, `slug` varchar(191) not null, `description` text null, `image` varchar(191) null, `sort_order` int not null default '0', `status` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `subcategories` add constraint `subcategories_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) on delete restrict;
alter table `subcategories` add unique `subcategories_category_id_slug_unique`(`category_id`, `slug`);

-- 2026_11_03_000008_add_subcategory_id_to_products_table
alter table `products` add `subcategory_id` bigint unsigned null after `category_id`;
alter table `products` add constraint `products_subcategory_id_foreign` foreign key (`subcategory_id`) references `subcategories` (`id`) on delete restrict;

-- 2026_11_03_000009_create_clients_table
create table `clients` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) null, `image` varchar(191) not null, `sort_order` int not null default '0', `status` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;

-- 2026_11_03_000010_remove_file_columns_from_videos_table
select column_name as `name`, data_type as `type_name`, column_type as `type`, collation_name as `collation`, is_nullable as `nullable`, column_default as `default`, column_comment as `comment`, generation_expression as `expression`, extra as `extra` from information_schema.columns where table_schema = schema() and table_name = 'videos' order by ordinal_position asc;
select column_name as `name`, data_type as `type_name`, column_type as `type`, collation_name as `collation`, is_nullable as `nullable`, column_default as `default`, column_comment as `comment`, generation_expression as `expression`, extra as `extra` from information_schema.columns where table_schema = schema() and table_name = 'videos' order by ordinal_position asc;

-- Laravel migrations table (marks all migrations as already run)
create table `migrations` (`id` int unsigned not null auto_increment primary key, `migration` varchar(191) not null, `batch` int not null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
insert into `migrations` (`migration`, `batch`) values
('0001_01_01_000000_create_users_table', 1),
('0001_01_01_000001_create_cache_table', 1),
('0001_01_01_000002_create_jobs_table', 1),
('2026_11_03_000001_create_admins_table', 1),
('2026_11_03_000002_create_categories_table', 1),
('2026_11_03_000003_create_products_table', 1),
('2026_11_03_000004_create_galleries_table', 1),
('2026_11_03_000005_create_videos_table', 1),
('2026_11_03_000006_create_blogs_table', 1),
('2026_11_03_000007_create_subcategories_table', 1),
('2026_11_03_000008_add_subcategory_id_to_products_table', 1),
('2026_11_03_000009_create_clients_table', 1),
('2026_11_03_000010_remove_file_columns_from_videos_table', 1);

-- ---------------------------------------------------------------------
-- Default data
-- ---------------------------------------------------------------------
-- Admin user (password: Admin@123, bcrypt)
insert into `admins` (`name`, `email`, `password`, `created_at`, `updated_at`) values ('Administrator', 'admin@sanjaysales.com', '$2y$12$C6tmM./L2E3X444owSSbhu17JWRFjp3XSvLu0pGyGZJW3cFLaBjgu', '2026-11-03 00:00:00', '2026-11-03 00:00:00');

-- Product categories
insert into `categories` (`name`, `slug`, `sort_order`, `status`, `created_at`, `updated_at`) values
('Welding Machines', 'welding-machines', 1, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('CNC Cutting Machines', 'cnc-cutting-machines', 2, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Plasma Cutting', 'plasma-cutting', 3, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Power Tools', 'power-tools', 4, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Hand Tools', 'hand-tools', 5, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Safety PPE', 'safety-ppe', 6, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Hand Gloves', 'hand-gloves', 7, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Cut-off Wheels', 'cut-off-wheels', 8, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Grinding Wheels', 'grinding-wheels', 9, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Measuring Tools', 'measuring-tools', 10, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Plain Wheel', 'plain-wheel', 11, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Pulling & Lifting Materials', 'pulling-and-lifting-materials', 12, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Welding Electrodes', 'welding-electrodes', 13, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00'),
('Accessories', 'accessories', 14, 1, '2026-11-03 00:00:00', '2026-11-03 00:00:00');

SET FOREIGN_KEY_CHECKS = 1;
