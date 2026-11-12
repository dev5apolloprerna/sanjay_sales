<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SeoController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

/*
| Public JSON API – every endpoint is POST, all parameters go in the request body.
| Only active / published records are returned.
*/

Route::middleware('throttle:api-read')->group(function () {
    Route::post('settings', SettingController::class);
    Route::post('seo', SeoController::class);                                         // body: page (+ slug / category)
    Route::post('home', HomeController::class);

    Route::post('categories', [CategoryController::class, 'index']);
    Route::post('categories/detail', [CategoryController::class, 'show']);            // body: slug
    Route::post('subcategories', [CategoryController::class, 'subcategories']);       // body: category (optional)
    Route::post('subcategories/detail', [CategoryController::class, 'subcategory']);  // body: category, slug

    Route::post('products', [ProductController::class, 'index']);
    Route::post('products/detail', [ProductController::class, 'show']);               // body: slug

    Route::post('blogs', [BlogController::class, 'index']);
    Route::post('blogs/detail', [BlogController::class, 'show']);                     // body: slug

    Route::post('gallery', [MediaController::class, 'gallery']);
    Route::post('videos', [MediaController::class, 'videos']);
    Route::post('clients', [MediaController::class, 'clients']);
    Route::post('catalogues', [MediaController::class, 'catalogues']);
});

// Inquire Now form – 5 submissions per minute per IP
Route::post('inquiries', [InquiryController::class, 'store'])->middleware('throttle:api-inquiry');
