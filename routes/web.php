<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\HuntingTypeController;
use App\Http\Controllers\Admin\GalleryItemController;
use App\Http\Controllers\WaypointController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Leader\LeaderDashboardController;
use Illuminate\Support\Facades\Route;

// -------------------
// Public Pages
// -------------------
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/contacts', fn() => view('contacts'))->name('contacts');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/gallery', fn() => view('gallery'))->name('gallery');
Route::get('/calendar', fn() => view('calendar'))->name('calendar');

// -------------------
// Authenticated Pages
// -------------------
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/join', fn() => view('join'))->name('join');
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');

    // Profile management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -------------------
// Maps & Waypoints
// -------------------
Route::middleware(['auth'])->group(function () {
    Route::get('/maps', [WaypointController::class, 'index'])->name('maps.index');
    Route::post('/waypoints', [WaypointController::class, 'store'])->name('waypoints.store');
});

// -------------------
// Admin Routes
// -------------------
Route::prefix('admin')->name('admin.')->middleware(['auth', 'is_admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Gallery management
    Route::resource('gallery', GalleryItemController::class)->names([
        'index' => 'gallery.index',
        'create' => 'gallery.create',
        'store' => 'gallery.store',
        'edit' => 'gallery.edit',
        'update' => 'gallery.update',
        'destroy' => 'gallery.destroy',
    ]);

    // Hunting Types management
    Route::resource('hunting-types', HuntingTypeController::class)->names([
        'index' => 'hunting-types.index',
        'create' => 'hunting-types.create',
        'store' => 'hunting-types.store',
        'edit' => 'hunting-types.edit',
        'update' => 'hunting-types.update',
        'destroy' => 'hunting-types.destroy',
    ]);

    // User leader management
    Route::post('/users/{user}/make-leader', [AdminDashboardController::class, 'makeLeader'])->name('users.makeLeader');
    Route::post('/users/{user}/remove-leader', [AdminDashboardController::class, 'removeLeader'])->name('users.removeLeader');
});

// -------------------
// Leader Routes
// -------------------
Route::middleware(['auth', 'leader'])->prefix('leader')->name('leader.')->group(function () {
    Route::get('/dashboard', [LeaderDashboardController::class, 'index'])->name('dashboard');
    Route::post('/users', [LeaderDashboardController::class, 'storeUser'])->name('users.store');
    Route::post('/requests/{user}/accept', [LeaderDashboardController::class, 'acceptRequest'])->name('acceptRequest');
    Route::delete('/requests/{user}/reject', [LeaderDashboardController::class, 'rejectRequest'])->name('rejectRequest');
});

require __DIR__.'/auth.php';
