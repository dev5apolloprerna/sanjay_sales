<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;

class SettingController extends ApiController
{
    /**
     * GET /api/settings
     * Header, footer, contact page and menu data in one call.
     */
    public function __invoke()
    {
        $site = config('site');

        return $this->ok([
            'name' => $site['name'],
            'tagline' => $site['tagline'],
            'contact' => $site['contact'] + [
                'whatsapp_link' => $site['contact']['whatsapp'] ? 'https://wa.me/'.$site['contact']['whatsapp'] : null,
            ],
            'social' => (object) array_filter($site['social']),
            'menu_categories' => Category::active()->orderBy('sort_order')->orderBy('name')
                ->get(['id', 'name', 'slug'])->map->only(['id', 'name', 'slug']),
        ]);
    }
}
