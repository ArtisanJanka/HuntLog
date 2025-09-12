<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HuntingTypeController;
use App\Http\Controllers\GalleryItemController;
use App\Http\Controllers\WaypointController;
use Illuminate\Support\Facades\Route;

// Public Pages
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/contacts', fn() => view('contacts'))->name('contacts');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/gallery', fn() => view('gallery'))->name('gallery');
Route::get('/calendar', fn() => view('calendar'))->name('calendar');

// Protected Pages
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/join', fn() => view('join'))->name('join');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Maps & waypoints
    
});

Route::middleware(['auth'])->group(function () {
    Route::get('/maps', [WaypointController::class, 'index'])->name('maps.index');
    Route::post('/waypoints', [WaypointController::class, 'store'])->name('waypoints.store');
});


// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::resource('gallery', GalleryItemController::class);
    Route::resource('hunting-types', HuntingTypeController::class);
});

require __DIR__.'/auth.php';
