<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\FavoritesController;
use App\Http\Controllers\DashboardController;

// Public routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/businesses/create', [BusinessController::class, 'create'])->name('businesses.create')->middleware('auth');


Route::get('/businesses/{business}', [WelcomeController::class, 'show'])->name('businesses.show');



// Routes for logged-in users
Route::middleware(['auth'])->group(function () {
    Route::resource('businesses', BusinessController::class)->except(['index', 'show']);
    Route::get('/user/businesses', [BusinessController::class, 'userBusinesses'])->name('user.businesses');


    Route::post('/businesses/{business}/favorites', [FavoritesController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/favorites', [FavoritesController::class, 'index'])->name('favorites.index');
    Route::post('/favorites/{business}', [FavoritesController::class, 'store'])->name('favorites.store');
    Route::delete('/favorites/{business}', [FavoritesController::class, 'destroy'])->name('favorites.destroy');

    Route::resource('reviews', ReviewController::class);
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
});

// Admin-only routes
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
});

require __DIR__.'/auth.php';
