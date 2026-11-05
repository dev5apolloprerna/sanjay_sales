<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BlogCardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $date = $this->published_at ?? $this->created_at;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'image_url' => $this->image_url,
            'author' => $this->author,
            'published_at' => $date?->toDateString(),   // 2024-10-28
            'published_date' => $date?->format('d M Y'), // 28 Oct 2024 (ready to show)
        ];
    }
}
