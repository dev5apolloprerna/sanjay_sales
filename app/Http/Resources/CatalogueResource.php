<?php

namespace App\Http\Resources;

use App\Support\Upload;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CatalogueResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $path = Upload::dir().DIRECTORY_SEPARATOR.$this->file;
        $bytes = is_file($path) ? filesize($path) : null;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'file_url' => $this->file_url,
            'file_type' => $this->file_type,
            'file_size' => $bytes,                                  // bytes, null if file missing
            'file_size_label' => $bytes ? $this->human($bytes) : null, // "2.4 MB"
            'image_url' => $this->image_url,
        ];
    }

    private function human(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = (int) min(3, floor(log($bytes, 1024)));

        return round($bytes / (1024 ** $i), 1).' '.$units[$i];
    }
}
