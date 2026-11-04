<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasImageUrl;

    protected $fillable = ['title', 'youtube_url', 'sort_order', 'status'];

    protected $casts = ['status' => 'boolean'];

    protected $appends = ['youtube_id', 'embed_url', 'thumbnail_url'];

    public function getYoutubeIdAttribute(): ?string
    {
        if (! $this->youtube_url) {
            return null;
        }
        preg_match('~(?:youtu\.be/|v=|embed/|shorts/|live/)([\w-]{11})~', $this->youtube_url, $m);

        return $m[1] ?? null;
    }

    public function getEmbedUrlAttribute(): ?string
    {
        return $this->youtube_id ? 'https://www.youtube.com/embed/'.$this->youtube_id : null;
    }

    /** Thumbnail taken automatically from YouTube. */
    public function getThumbnailUrlAttribute(): ?string
    {
        return $this->youtube_id ? 'https://img.youtube.com/vi/'.$this->youtube_id.'/hqdefault.jpg' : null;
    }
}
