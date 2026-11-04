-- =====================================================================
-- Sanjay Sales Admin - UPGRADE for an existing database
-- Adds: subcategories table, products.subcategory_id, clients table.
-- Your existing data is NOT touched. Import this ONCE in phpMyAdmin
-- (Import tab), or run `php artisan migrate` instead of this file.
-- =====================================================================

USE `sanjay_sales`;
SET NAMES utf8mb4;

-- 2026_11_03_000007_create_subcategories_table
create table `subcategories` (`id` bigint unsigned not null auto_increment primary key, `category_id` bigint unsigned not null, `name` varchar(191) not null, `slug` varchar(191) not null, `description` text null, `image` varchar(191) null, `sort_order` int not null default '0', `status` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
alter table `subcategories` add constraint `subcategories_category_id_foreign` foreign key (`category_id`) references `categories` (`id`) on delete restrict;
alter table `subcategories` add unique `subcategories_category_id_slug_unique`(`category_id`, `slug`);

-- 2026_11_03_000008_add_subcategory_id_to_products_table
alter table `products` add `subcategory_id` bigint unsigned null after `category_id`;
alter table `products` add constraint `products_subcategory_id_foreign` foreign key (`subcategory_id`) references `subcategories` (`id`) on delete restrict;

-- 2026_11_03_000009_create_clients_table
create table `clients` (`id` bigint unsigned not null auto_increment primary key, `name` varchar(191) null, `image` varchar(191) not null, `sort_order` int not null default '0', `status` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci' engine = InnoDB;
-- record the migrations as done, so `php artisan migrate` won't run them again
insert into `migrations` (`migration`, `batch`) values
('2026_11_03_000007_create_subcategories_table', 2),
('2026_11_03_000008_add_subcategory_id_to_products_table', 2),
('2026_11_03_000009_create_clients_table', 2);
