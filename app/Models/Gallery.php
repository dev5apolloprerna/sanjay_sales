<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasImageUrl;

    protected $fillable = ['title', 'image', 'sort_order', 'status'];

    protected $casts = ['status' => 'boolean'];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->image);
    }
}
