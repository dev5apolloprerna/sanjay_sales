<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    protected $fillable = ['name', 'email', 'phone', 'subject', 'message', 'product_id', 'source', 'ip_address', 'is_read'];

    protected $casts = ['is_read' => 'boolean'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
