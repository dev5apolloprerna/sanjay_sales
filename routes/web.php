<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SubcategoryController;
use App\Http\Controllers\Admin\VideoController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest-only
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.submit');
    });

    // Logged-in admin
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', DashboardController::class)->name('dashboard');

        Route::get('profile', [ProfileController::class, 'edit'])->name('profile');
        Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('change-password', [ProfileController::class, 'password'])->name('password');
        Route::put('change-password', [ProfileController::class, 'updatePassword'])->name('password.update');

        Route::resource('categories', CategoryController::class)->except('show');
        Route::resource('subcategories', SubcategoryController::class)->except('show');
        Route::resource('products', ProductController::class)->except('show');
        Route::resource('gallery', GalleryController::class)->except('show')->parameters(['gallery' => 'gallery']);
        Route::resource('clients', ClientController::class)->except('show');
        Route::resource('videos', VideoController::class)->except('show');
        Route::resource('blogs', BlogController::class)->except('show');
    });
});
