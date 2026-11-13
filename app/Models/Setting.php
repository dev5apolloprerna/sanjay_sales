<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/** Key/value settings managed from the admin panel. */
class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    protected $casts = ['value' => 'array'];

    public static function get(string $key, mixed $default = null): mixed
    {
        $value = Cache::rememberForever("setting.$key", fn () => static::where('key', $key)->value('value'));

        return $value === null ? $default : (is_string($value) ? json_decode($value, true) : $value);
    }

    public static function put(string $key, mixed $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget("setting.$key");
    }

    /**
     * Homepage "Numbers That Define Us" block.
     * Falls back to config/site.php until it is saved from the admin.
     */
    public static function homeStats(): array
    {
        $saved = static::get('home_stats');

        return [
            'title' => $saved['title'] ?? config('site.stats_title', 'Numbers That Define Us'),
            'subtitle' => $saved['subtitle'] ?? config('site.stats_subtitle'),
            'items' => array_values($saved['items'] ?? config('site.stats', [])),
        ];
    }
}
