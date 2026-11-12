<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

/** SEO settings for the website's fixed pages (home, about, contact, listing pages ...). */
class SeoPage extends Model
{
    use HasImageUrl;

    /** page_key => label. Add a new line here when the website gets a new fixed page. */
    public const PAGES = [
        'home' => 'Home Page',
        'about' => 'About Us',
        'contact' => 'Contact Us',
        'products' => 'Products (listing)',
        'blogs' => 'Blogs (listing)',
        'gallery' => 'Photo Gallery',
        'videos' => 'Video Gallery',
        'clients' => 'Our Clients',
        'download' => 'Download / Catalogues',
    ];

    public const ROBOTS = [
        'index, follow' => 'Index, Follow (default)',
        'noindex, follow' => 'No Index, Follow',
        'index, nofollow' => 'Index, No Follow',
        'noindex, nofollow' => 'No Index, No Follow',
    ];

    protected $fillable = ['page_key', 'meta_title', 'meta_description', 'meta_keywords', 'canonical_url',
        'robots', 'og_title', 'og_description', 'og_image'];

    protected $appends = ['og_image_url', 'label'];

    public function getOgImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->og_image);
    }

    public function getLabelAttribute(): string
    {
        return self::PAGES[$this->page_key] ?? ucfirst($this->page_key);
    }

    /** Makes sure every page in PAGES has a row (so the admin can always edit it). */
    public static function ensureDefaults(): void
    {
        $site = config('site.name', 'Sanjay Sales');
        foreach (self::PAGES as $key => $label) {
            self::firstOrCreate(['page_key' => $key], ['meta_title' => $label.' | '.$site]);
        }
    }
}
