-- =====================================================================
-- Sanjay Sales Admin - UPGRADE 3 for an existing database
-- Website API support:
--   * products: features, applications, specifications, faqs (JSON)
--   * inquiries: "Inquire Now" form submissions
--   * catalogues: PDF catalogues for the /download page
-- Other data is not touched. Import this ONCE in phpMyAdmin (Import tab),
-- or run `php artisan migrate` instead of this file.
-- =====================================================================

USE `sanjay_sales`;

alter table `products`
  add `features` json null after `description`,
  add `applications` json null after `features`,
  add `specifications` json null after `applications`,
  add `faqs` json null after `specifications`;

create table `inquiries` (
  `id` bigint unsigned not null auto_increment primary key,
  `name` varchar(150) not null,
  `email` varchar(150) null,
  `phone` varchar(30) not null,
  `subject` varchar(200) null,
  `message` text not null,
  `product_id` bigint unsigned null,
  `source` varchar(30) not null default 'contact',
  `ip_address` varchar(45) null,
  `is_read` tinyint(1) not null default '0',
  `created_at` timestamp null,
  `updated_at` timestamp null,
  constraint `inquiries_product_id_foreign` foreign key (`product_id`) references `products` (`id`) on delete set null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

create table `catalogues` (
  `id` bigint unsigned not null auto_increment primary key,
  `title` varchar(191) not null,
  `file` varchar(191) not null,
  `image` varchar(191) null,
  `sort_order` int not null default '0',
  `status` tinyint(1) not null default '1',
  `created_at` timestamp null,
  `updated_at` timestamp null
) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

-- record the migrations as done, so `php artisan migrate` won't run them again
insert into `migrations` (`migration`, `batch`) values
('2026_11_04_000001_add_detail_columns_to_products_table', 4),
('2026_11_04_000002_create_inquiries_table', 4),
('2026_11_04_000003_create_catalogues_table', 4);
