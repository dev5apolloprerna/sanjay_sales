<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class BlogResource extends BlogCardResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'content' => $this->content,
            'meta_title' => $this->meta_title ?: $this->title,
            'meta_description' => $this->meta_description ?: $this->excerpt,
        ];
    }
}
