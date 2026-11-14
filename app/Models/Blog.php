<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasImageUrl;

    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'image', 'author', 'meta_title', 'meta_description',
        'meta_keywords', 'head', 'body', 'status', 'published_at'];

    protected $casts = ['status' => 'boolean', 'published_at' => 'date'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->image);
    }
}
