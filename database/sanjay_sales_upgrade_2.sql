-- =====================================================================
-- Sanjay Sales Admin - UPGRADE 2 for an existing database
-- Videos are YouTube-only: removes the old upload columns from `videos`.
-- Other data is not touched. Import this ONCE in phpMyAdmin (Import tab),
-- or run `php artisan migrate` instead of this file.
-- =====================================================================

USE `sanjay_sales`;

alter table `videos` drop column `video_file`, drop column `thumbnail`;

-- record the migration as done, so `php artisan migrate` won't run it again
insert into `migrations` (`migration`, `batch`) values
('2026_11_03_000010_remove_file_columns_from_videos_table', 3);
