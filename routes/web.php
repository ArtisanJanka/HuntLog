<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HuntingTypeController;
use App\Http\Controllers\GalleryItemController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| All web routes for HuntLogs.
|
*/

// Public Pages
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/contacts', fn() => view('contacts'))->name('contacts');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/gallery', fn() => view('gallery'))->name('gallery');
Route::get('/join', fn() => view('join'))->middleware(['auth', 'verified'])->name('join');
Route::get('/calendar', fn() => view('calendar'))->name('calendar');
Route::get('/maps', fn() => view('maps'))->name('maps');

// Authenticated User Routes
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    // Gallery CRUD
    Route::resource('gallery', GalleryItemController::class);

    // Hunting Types CRUD
  

});

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::resource('hunting-types', HuntingTypeController::class);
});



require __DIR__.'/auth.php';
