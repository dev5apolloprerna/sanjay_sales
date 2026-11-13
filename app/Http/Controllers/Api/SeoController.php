<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use App\Models\SeoPage;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * POST /api/seo – one common endpoint for all SEO data.
 *
 *   Fixed pages : { "page": "home" }   (home, about, contact, blogs, gallery, videos, download)
 *   Blog        : { "page": "blog",        "slug": "welding-tips" }
 *   Product     : { "page": "product",     "slug": "mig-350" }
 *   Category    : { "page": "category",    "slug": "welding-machines" }
 *   Subcategory : { "page": "subcategory", "category": "welding-machines", "slug": "arc-welders" }
 *
 * Response (same shape for every page):
 *   page, page_name, site_name, meta_title, meta_keywords, meta_description, head, body
 *
 * Fixed pages come from Admin > SEO Setup. Detail pages use the SEO fields saved on the
 * record and fall back to auto-generated values when they are empty (head/body are null).
 * "products" (product listing) and "clients" no longer have SEO.
 */
class SeoController extends ApiController
{
    private const DETAIL = ['category', 'subcategory', 'product', 'blog'];

    public function __invoke(Request $request)
    {
        $pages = array_merge(array_keys(SeoPage::PAGES), self::DETAIL);

        $v = $request->validate([
            'page' => ['required', 'string', 'in:'.implode(',', $pages)],
            'slug' => ['required_if:page,category,subcategory,product,blog', 'nullable', 'string', 'max:270'],
            'category' => ['required_if:page,subcategory', 'nullable', 'string', 'max:200'],
        ]);

        $seo = match ($v['page']) {
            'category' => $this->category($v['slug']),
            'subcategory' => $this->subcategory($v['category'], $v['slug']),
            'product' => $this->product($v['slug']),
            'blog' => $this->blog($v['slug']),
            default => $this->fixedPage($v['page']),
        };

        return $this->ok($seo);
    }

    // ---------- fixed pages (managed in Admin > SEO Setup) ----------

    private function fixedPage(string $key): array
    {
        $row = SeoPage::where('page_key', $key)->first();
        $name = $row?->page_name ?: SeoPage::PAGES[$key];

        return $this->format($key, [
            'page_name' => $name,
            'meta_title' => $row?->meta_title ?: $name.' | '.$this->site(),
            'meta_keywords' => $row?->meta_keywords,
            'meta_description' => $this->plain($row?->meta_description, 500),
            'head' => $row?->head,
            'body' => $row?->body,
        ]);
    }

    // ---------- detail pages ----------

    private function category(string $slug): array
    {
        $c = Category::active()->where('slug', $slug)->firstOrFail();

        return $this->format('category', [
            'page_name' => $c->name,
            'meta_title' => $c->meta_title ?: $c->name.' | '.$this->site(),
            'meta_keywords' => $c->meta_keywords,
            'meta_description' => $this->plain($c->meta_description, 500) ?: $this->plain($c->description),
        ]);
    }

    private function subcategory(string $category, string $slug): array
    {
        $s = Subcategory::active()->where('slug', $slug)
            ->whereHas('category', fn ($q) => $q->active()->where('slug', $category))
            ->with('category:id,name,slug')
            ->firstOrFail();

        return $this->format('subcategory', [
            'page_name' => $s->name,
            'meta_title' => $s->meta_title ?: $s->name.' | '.$s->category->name.' | '.$this->site(),
            'meta_keywords' => $s->meta_keywords,
            'meta_description' => $this->plain($s->meta_description, 500) ?: $this->plain($s->description),
        ]);
    }

    private function product(string $slug): array
    {
        $p = Product::active()->where('slug', $slug)->firstOrFail();

        return $this->format('product', [
            'page_name' => $p->name,
            'meta_title' => $p->meta_title ?: $p->name.' | '.$this->site(),
            'meta_keywords' => $p->meta_keywords ?: $p->brand,
            'meta_description' => $this->plain($p->meta_description, 500)
                ?: ($this->plain($p->short_description) ?: $this->plain($p->description)),
        ]);
    }

    private function blog(string $slug): array
    {
        $b = Blog::active()->where('slug', $slug)->firstOrFail();

        return $this->format('blog', [
            'page_name' => $b->title,
            'meta_title' => $b->meta_title ?: $b->title.' | '.$this->site(),
            'meta_keywords' => $b->meta_keywords,
            'meta_description' => $this->plain($b->meta_description, 500)
                ?: ($this->plain($b->excerpt) ?: $this->plain($b->content)),
        ]);
    }

    // ---------- helpers ----------

    /** One response shape for every page (same fields as Admin > SEO Setup). */
    private function format(string $page, array $d): array
    {
        return [
            'page' => $page,
            'page_name' => $d['page_name'] ?? null,
            'site_name' => $this->site(),
            'meta_title' => $d['meta_title'] ?? null,
            'meta_keywords' => $d['meta_keywords'] ?? null,
            'meta_description' => $d['meta_description'] ?? null,
            'head' => $d['head'] ?? null,
            'body' => $d['body'] ?? null,
        ];
    }

    private function site(): string
    {
        return (string) config('site.name', 'Sanjay Sales');
    }

    /** HTML -> clean plain text, max 160 characters (null when empty). */
    private function plain(?string $html, int $limit = 160): ?string
    {
        $text = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags((string) $html), ENT_QUOTES | ENT_HTML5, 'UTF-8')));

        return $text === '' ? null : Str::limit($text, $limit);
    }
}
