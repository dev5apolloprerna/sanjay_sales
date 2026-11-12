# SEO setup – admin + common API

## 1. Copy files into your Laravel project (same paths, overwrite)
- database/migrations/*                       (2 new migrations)
- app/Models/SeoPage.php                      (new)
- app/Models/Category|Subcategory|Product|Blog.php   (fillable updated)
- app/Http/Controllers/Admin/SeoController.php (new)
- app/Http/Controllers/Admin/Category|Subcategory|Product|Blog Controller.php (SEO validation added)
- app/Http/Controllers/Api/SeoController.php   (new – common SEO API)
- resources/views/components/seo-fields.blade.php (new reusable SEO block)
- resources/views/components/icon.blade.php   (added "seo" icon)
- resources/views/admin/seo/index + form      (new)
- resources/views/admin/categories|subcategories|products|blogs/form.blade.php (SEO block added)
- resources/views/admin/layout.blade.php      (sidebar "SEO Settings" link)
- routes/web.php, routes/api.php              (SEO routes added)

## 2. Run
    php artisan migrate
    php artisan optimize:clear

## 3. Use
- Admin > Website > SEO Settings : home, about, contact, products, blogs, gallery, videos, clients, download
- Category / Subcategory / Product / Blog forms : "SEO Settings" block at the bottom
- API: POST /api/seo  { "page": "home" }  – see docs/SEO_API_DOCUMENTATION.md

To add another fixed page, add one line to SeoPage::PAGES (app/Models/SeoPage.php).
