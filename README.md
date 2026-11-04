# Sanjay Sales – Admin Panel (Laravel 12)

This is an admin panel for the Sanjay Sales website. It is built with **Laravel 12 + MySQL** and uses Blade views with plain CSS/JS, so you don't need to run npm or Vite.

## Features
- **Login / Logout**: uses a separate `admin` guard and `admins` table, has a "remember me" option, and allows 10 login attempts per minute
- **Profile update**: name, email, phone and profile photo
- **Change password**: you must enter the current password, and the new password has to be confirmed and be at least 6 characters
- **Product Categories**: name, slug, description, image, display order and status. A category that still has products cannot be deleted.
- **Subcategories**: each one belongs to a category. It has a name, slug, image, display order and status. The same name can be used under different categories.
- **Products**: **category, then a subcategory from that category only**, **multiple images** (add new ones or remove existing ones), brand, price, short description, rich-text description, a featured flag and status
- **Gallery**: title, image upload, display order and status
- **Our Clients**: logo images only. You can upload many at once, and the name is optional and can be edited later.
- **Video Gallery**: title and YouTube link. The thumbnail and embed link come from YouTube automatically.
- **Blogs**: title, slug, author, publish date, featured image, excerpt, rich-text content, SEO meta fields and a Published/Draft status
- **Dashboard**: counts plus the latest products and blogs. List pages have search and pagination, and products can also be filtered by category.
- When you replace or delete a record, its old files are deleted from storage.
- The theme uses the Sanjay Sales logo colours (red `#c4161c`, grey `#808285`). The colours are set at the top of `public/css/admin.css`.

## Installation
Requirements: **PHP 8.2+** and MySQL 5.7+ / MariaDB 10.3+ (XAMPP/WAMP/Laragon all work)

The `vendor` folder and a ready `.env` file are already included, so you **don't need to run `composer install`**.

1. Create an empty MySQL database called **`sanjay_sales`**, for example in phpMyAdmin.
   **Or skip the migrate step:** import `database/sanjay_sales.sql` in phpMyAdmin. It creates the database, all tables, the admin user and the categories.
2. Open `.env` and check the database settings. The defaults match XAMPP (`root` with no password):
   ```
   DB_DATABASE=sanjay_sales
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. In the project folder, run:
   ```bash
   php artisan migrate --seed      # creates the tables, the admin user and 14 categories
   php artisan serve
   ```
4. Open **http://localhost:8000/admin**

**Default login:** `admin@sanjaysales.com` / `Admin@123`. Change it after you first log in.

Uploaded files are saved in `public/uploads/`, so you don't need `php artisan storage:link`.

On a live server, change these lines in `.env`: `APP_ENV=production`, `APP_DEBUG=false`, and `APP_URL=https://your-domain.com`.
(Optional: `composer install` will refresh the `vendor` folder if you ever need to.)

### Upload size
PHP's default upload limit (2 MB) is lower than the 5 MB image limit. To allow images up to 5 MB, set `upload_max_filesize = 8M` and `post_max_size = 64M` in `php.ini`.

### Shared hosting / cPanel
Upload the whole folder and point the domain's document root to the `public/` folder. Make sure the `storage/`, `bootstrap/cache/` and `public/uploads/` folders are writable.

## Upgrading an existing installation
If your database is already set up, apply new changes in **one** of these ways. Your existing data is kept.
- run `php artisan migrate`. It only runs the steps that haven't been applied yet, **or**
- import the upgrade files in phpMyAdmin, in order. Skip any you have already imported:
  1. `database/sanjay_sales_upgrade.sql`: adds Subcategories, product subcategory and Clients
  2. `database/sanjay_sales_upgrade_2.sql`: makes videos YouTube-only by removing the upload columns

## Public JSON API (for the website)
These endpoints return only active or published records:

| URL | Description |
|---|---|
| `GET /api/categories` | categories with their subcategories (for menus) |
| `GET /api/categories/{slug}` | one category with its products and their images |
| `GET /api/subcategories?category=welding-machines` | subcategories of a category |
| `GET /api/categories/{category-slug}/{subcategory-slug}` | one subcategory with its products |
| `GET /api/products?category=welding-machines&subcategory=mig-welding-machines&featured=1&q=esab` | products with their category, subcategory and images |
| `GET /api/products/{slug}` | one product |
| `GET /api/gallery` | gallery images (`image_url`) |
| `GET /api/clients` | client logos (`image_url`) |
| `GET /api/videos` | videos (`youtube_url`, `embed_url`, `thumbnail_url`) |
| `GET /api/blogs` | blog list (without content) |
| `GET /api/blogs/{slug}` | one blog with its content |

Every file field also comes with a full URL, such as `image_url` or `images[].url`.

If the website itself is built in the same Laravel app, you can use the models directly:
```php
$products = \App\Models\Product::active()->with('images', 'category')->latest()->get();
```

## Main files
```
app/Http/Controllers/Admin/   Auth, Dashboard, Profile, Category, Subcategory, Product, Gallery, Client, Video, Blog
app/Http/Controllers/Api/     ContentController (public JSON API)
app/Models/                   Admin, Category, Subcategory, Product, ProductImage, Gallery, Client, Video, Blog
app/Support/Upload.php        stores, replaces and deletes uploaded files (in public/uploads)
database/migrations/          table definitions
database/seeders/             admin user and categories
resources/views/admin/        Blade pages
resources/views/components/   form field, upload, switch and icon components
public/css/admin.css          theme
public/js/admin.js            previews, slug, rich-text editor and delete confirmation
routes/web.php, routes/api.php
```
# sanjay_sales
