<?php

namespace App\Models\Concerns;

trait HasImageUrl
{
    /** Turns a stored path ("products/abc.jpg") into a full public URL. */
    protected function fileUrl(?string $path): ?string
    {
        return $path ? asset('uploads/'.$path) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
