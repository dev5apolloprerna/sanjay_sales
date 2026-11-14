<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasImageUrl;

    protected $fillable = ['category_id', 'name', 'slug', 'description', 'image', 'sort_order', 'status',
        'meta_title', 'meta_description', 'meta_keywords', 'head', 'body'];

    protected $casts = ['status' => 'boolean', 'sort_order' => 'integer'];

    protected $appends = ['image_url'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->image);
    }
}
