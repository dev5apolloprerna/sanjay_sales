<?php

namespace App\Models;

use App\Models\Concerns\HasImageUrl;
use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
    use HasImageUrl;

    protected $fillable = ['title', 'file', 'image', 'sort_order', 'status'];

    protected $casts = ['status' => 'boolean', 'sort_order' => 'integer'];

    protected $appends = ['file_url', 'image_url'];

    public function getFileUrlAttribute(): ?string
    {
        return $this->fileUrl($this->file);
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->fileUrl($this->image);
    }

    /** Lowercase extension, e.g. "pdf". */
    public function getFileTypeAttribute(): ?string
    {
        return $this->file ? strtolower(pathinfo($this->file, PATHINFO_EXTENSION)) : null;
    }
}
