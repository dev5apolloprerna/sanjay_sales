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
 *   Fixed pages : { "page": "home" }   (home, about, contact, products, blogs, gallery, videos, clients, download)
 *   Blog        : { "page": "blog",        "slug": "welding-tips" }
 *   Product     : { "page": "product",     "slug": "mig-350" }
 *   Category    : { "page": "category",    "slug": "welding-machines" }
 *   Subcategory : { "page": "subcategory", "category": "welding-machines", "slug": "arc-welders" }
 *
 * Detail pages use the SEO fields saved on the record and fall back to
 * auto-generated values (name / description / image) when they are empty.
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

    // ---------- fixed pages (managed in Admin > SEO Settings) ----------

    private function fixedPage(string $key): array
    {
        $row = SeoPage::where('page_key', $key)->first();
        $label = SeoPage::PAGES[$key];

        return $this->format($key, [
            'meta_title' => $row?->meta_title ?: $label.' | '.$this->site(),
            'meta_description' => $row?->meta_description,
            'meta_keywords' => $row?->meta_keywords,
            'canonical_url' => $row?->canonical_url,
            'robots' => $row?->robots ?: 'index, follow',
            'og_title' => $row?->og_title ?: $row?->meta_title,
            'og_description' => $row?->og_description ?: $row?->meta_description,
            'og_image' => $row?->og_image_url,
            'og_type' => 'website',
        ]);
    }

    // ---------- detail pages ----------

    private function category(string $slug): array
    {
        $c = Category::active()->where('slug', $slug)->firstOrFail();

        return $this->format('category', [
            'meta_title' => $c->meta_title ?: $c->name.' | '.$this->site(),
            'meta_description' => $c->meta_description ?: $this->plain($c->description),
            'meta_keywords' => $c->meta_keywords,
            'og_image' => $c->image_url,
        ]);
    }

    private function subcategory(string $category, string $slug): array
    {
        $s = Subcategory::active()->where('slug', $slug)
            ->whereHas('category', fn ($q) => $q->active()->where('slug', $category))
            ->with('category:id,name,slug')
            ->firstOrFail();

        return $this->format('subcategory', [
            'meta_title' => $s->meta_title ?: $s->name.' | '.$s->category->name.' | '.$this->site(),
            'meta_description' => $s->meta_description ?: $this->plain($s->description),
            'meta_keywords' => $s->meta_keywords,
            'og_image' => $s->image_url,
        ]);
    }

    private function product(string $slug): array
    {
        $p = Product::active()->where('slug', $slug)->with('images')->firstOrFail();

        return $this->format('product', [
            'meta_title' => $p->meta_title ?: $p->name.' | '.$this->site(),
            'meta_description' => $p->meta_description ?: ($this->plain($p->short_description) ?: $this->plain($p->description)),
            'meta_keywords' => $p->meta_keywords ?: $p->brand,
            'og_image' => $p->main_image_url,
            'og_type' => 'product',
        ]);
    }

    private function blog(string $slug): array
    {
        $b = Blog::active()->where('slug', $slug)->firstOrFail();

        return $this->format('blog', [
            'meta_title' => $b->meta_title ?: $b->title.' | '.$this->site(),
            'meta_description' => $b->meta_description ?: ($this->plain($b->excerpt) ?: $this->plain($b->content)),
            'meta_keywords' => $b->meta_keywords,
            'og_image' => $b->image_url,
            'og_type' => 'article',
        ]);
    }

    // ---------- helpers ----------

    /** One response shape for every page. og_* default to the meta values. */
    private function format(string $page, array $d): array
    {
        return [
            'page' => $page,
            'site_name' => $this->site(),
            'meta_title' => $d['meta_title'] ?? null,
            'meta_description' => $d['meta_description'] ?? null,
            'meta_keywords' => $d['meta_keywords'] ?? null,
            'canonical_url' => $d['canonical_url'] ?? null,
            'robots' => $d['robots'] ?? 'index, follow',
            'og_title' => $d['og_title'] ?? $d['meta_title'] ?? null,
            'og_description' => $d['og_description'] ?? $d['meta_description'] ?? null,
            'og_image' => $d['og_image'] ?? null,
            'og_type' => $d['og_type'] ?? 'website',
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
