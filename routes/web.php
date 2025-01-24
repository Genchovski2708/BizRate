<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\MetadataController;
use App\Http\Controllers\HomeController;


Route::view('/', 'welcome');

// Public route for users (accessible without login)
Route::resource('users', UserController::class);

// Authenticated routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Admin-only routes
    Route::middleware(['auth', 'verified'])->group(function () {
        Route::resource('categories', CategoryController::class);
    });


    // Other authenticated routes
    Route::resource('businesses', BusinessController::class);
    Route::resource('reviews', ReviewController::class);
    Route::resource('favorites', FavoritesController::class);
    Route::resource('metadata', MetadataController::class);
    Route::middleware('auth')->get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');

});

require __DIR__.'/auth.php';
