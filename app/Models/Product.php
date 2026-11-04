<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasImageUrl;

    protected $fillable = ['category_id', 'subcategory_id', 'name', 'slug', 'brand', 'price', 'short_description', 'description', 'featured', 'status'];

    protected $casts = ['status' => 'boolean', 'featured' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function getMainImageUrlAttribute(): ?string
    {
        return $this->images->first()?->url;
    }
}
