<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasImageUrl;

    protected $fillable = ['category_id', 'subcategory_id', 'name', 'slug', 'brand', 'price', 'short_description', 'description',
        'features', 'applications', 'featured', 'status',
        'meta_title', 'meta_description', 'meta_keywords', 'head', 'body'];

    protected $casts = [
        'status' => 'boolean',
        'featured' => 'boolean',
        // features, applications = HTML from CKEditor
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Technical specification rows (managed from Products › Specifications action).
     * Named specRows because the old "specifications" text column still exists in the table.
     */
    public function specRows()
    {
        return $this->hasMany(ProductSpecification::class)->orderBy('group_order')->orderBy('sort_order')->orderBy('id');
    }

    /** FAQs (managed from Products › FAQ action) */
    public function faqs()
    {
        return $this->hasMany(ProductFaq::class)->orderBy('sort_order')->orderBy('id');
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
