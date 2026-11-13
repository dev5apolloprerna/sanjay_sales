<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * SEO Setup for the website's fixed pages (home, about, contact, blog listing ...).
 * Fields: page_name, meta_title, meta_keywords, meta_description, head, body.
 */
class SeoPage extends Model
{
    /** page_key => default page name. Add a new line here when the website gets a new fixed page. */
    public const PAGES = [
        'home' => 'Home',
        'about' => 'About Us',
        'contact' => 'Contact Us',
        'blogs' => 'Blog',
        'gallery' => 'Photo Gallery',
        'videos' => 'Video Gallery',
        'download' => 'Download',
    ];

    protected $fillable = ['page_key', 'page_name', 'meta_title', 'meta_keywords', 'meta_description', 'head', 'body'];

    protected $appends = ['label'];

    public function getLabelAttribute(): string
    {
        return $this->page_name ?: (self::PAGES[$this->page_key] ?? ucfirst($this->page_key));
    }

    /** Only pages that are still listed in PAGES (old rows like "clients" are ignored). */
    public function scopeManaged($query)
    {
        return $query->whereIn('page_key', array_keys(self::PAGES));
    }

    /** Makes sure every page in PAGES has a row (so the admin can always edit it). */
    public static function ensureDefaults(): void
    {
        $site = config('site.name', 'Sanjay Sales');
        foreach (self::PAGES as $key => $name) {
            self::firstOrCreate(['page_key' => $key], ['page_name' => $name, 'meta_title' => $name.' | '.$site]);
        }
    }
}
