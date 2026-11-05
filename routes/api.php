<?php

use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\InquiryController;
use App\Http\Controllers\Api\MediaController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SettingController;
use Illuminate\Support\Facades\Route;

/*
| Public JSON API for the Next.js website (sanjay-sales-next.vercel.app).
| Base URL: <your-domain>/api   – full request/response docs: API_DOCUMENTATION.md
| Only active / published records are returned.
*/

Route::middleware('throttle:api-read')->group(function () {
    Route::get('settings', SettingController::class);          // header, footer, contact info, menu
    Route::get('home', HomeController::class);                 // homepage in one call

    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('categories/{slug}', [CategoryController::class, 'show']);
    Route::get('categories/{category}/{slug}', [CategoryController::class, 'subcategory']);
    Route::get('subcategories', [CategoryController::class, 'subcategories']);

    Route::get('products', [ProductController::class, 'index']);
    Route::get('products/{slug}', [ProductController::class, 'show']);

    Route::get('blogs', [BlogController::class, 'index']);
    Route::get('blogs/{slug}', [BlogController::class, 'show']);

    Route::get('gallery', [MediaController::class, 'gallery']);
    Route::get('videos', [MediaController::class, 'videos']);
    Route::get('clients', [MediaController::class, 'clients']);
    Route::get('catalogues', [MediaController::class, 'catalogues']);
});

// Inquire Now form – 5 submissions per minute per IP
Route::post('inquiries', [InquiryController::class, 'store'])->middleware('throttle:api-inquiry');
