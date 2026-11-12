<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CatalogueResource;
use App\Http\Resources\ClientResource;
use App\Http\Resources\GalleryResource;
use App\Http\Resources\VideoResource;
use App\Models\Catalogue;
use App\Models\Client;
use App\Models\Gallery;
use App\Models\Video;

class MediaController extends ApiController
{
    /** POST /api/gallery – "Photo Gallery" tab */
    public function gallery()
    {
        return $this->ok(GalleryResource::collection(Gallery::active()->orderBy('sort_order')->latest()->get()));
    }

    /** POST /api/videos – "Video Gallery" tab */
    public function videos()
    {
        return $this->ok(VideoResource::collection(Video::active()->orderBy('sort_order')->latest()->get()));
    }

    /** POST /api/clients – "Trusted By Industry Leaders" logos */
    public function clients()
    {
        return $this->ok(ClientResource::collection(Client::active()->orderBy('sort_order')->latest()->get()));
    }

    /** POST /api/catalogues – /download page */
    public function catalogues()
    {
        return $this->ok(CatalogueResource::collection(Catalogue::active()->orderBy('sort_order')->latest()->get()));
    }
}
