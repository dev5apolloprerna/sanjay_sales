<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductFaq extends Model
{
    protected $fillable = ['product_id', 'question', 'answer', 'sort_order', 'status'];

    protected $casts = ['status' => 'boolean', 'sort_order' => 'integer'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeActive($q)
    {
        return $q->where('status', true);
    }
}
