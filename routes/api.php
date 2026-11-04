<?php

use App\Http\Controllers\Api\ContentController;
use Illuminate\Support\Facades\Route;

/*
| Public read-only JSON API for the website front-end.
| Only active / published records are returned.
*/
Route::get('categories', [ContentController::class, 'categories']);
Route::get('categories/{slug}', [ContentController::class, 'category']);
Route::get('categories/{category}/{slug}', [ContentController::class, 'subcategory']);
Route::get('subcategories', [ContentController::class, 'subcategories']);
Route::get('products', [ContentController::class, 'products']);
Route::get('products/{slug}', [ContentController::class, 'product']);
Route::get('gallery', [ContentController::class, 'gallery']);
Route::get('clients', [ContentController::class, 'clients']);
Route::get('videos', [ContentController::class, 'videos']);
Route::get('blogs', [ContentController::class, 'blogs']);
Route::get('blogs/{slug}', [ContentController::class, 'blog']);
