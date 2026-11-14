<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasImageUrl;

    protected $fillable = ['name', 'slug', 'description', 'image', 'sort_order', 'status',
        'meta_title', 'meta_description', 'meta_keywords', 'head', 'body'];

    protected $casts = ['status' => 'boolean', 'sort_order' => 'integer'];

    protected $appends = ['image_url'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class)->orderBy('sort_order')->orderBy('name');
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->image);
    }
}
