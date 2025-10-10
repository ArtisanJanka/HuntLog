<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaypointController;
use App\Http\Controllers\PolygonController;

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\GalleryItemController;
use App\Http\Controllers\Admin\HuntingTypeController;

use App\Http\Controllers\Leader\LeaderDashboardController;

use App\Http\Controllers\ContactController;
use App\Http\Controllers\GroupRequestController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupEventController;

use App\Http\Controllers\MapLinkController; // <-- ADDED
use App\Models\GroupEvent;

/*
|--------------------------------------------------------------------------
| Public pages
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => view('welcome'))->name('home');
Route::get('/about', fn () => view('about'))->name('about');
Route::get('/gallery', fn () => view('gallery'))->name('gallery');
Route::get('/join', fn () => view('join'))->name('join');

// Contacts (single GET route + POST store)
Route::get('/contacts', [ContactController::class, 'create'])->name('contacts');
Route::post('/contacts', [ContactController::class, 'store'])->name('contacts.store');

// Calendar (now safely provides $events to the view)
Route::get('/calendar', function () {
    $user = Auth::user();

    if (!$user) {
        // Not logged in -> empty list keeps the view happy
        return view('calendar', ['events' => collect()]);
    }

    // Adjust relation name if needed (e.g. $user->memberGroups())
    $groupIds = $user->groups()->pluck('groups.id');

    $events = GroupEvent::with(['group:id,name', 'polygon:id,name'])
        ->whereIn('group_id', $groupIds)
        ->orderBy('start_at')
        ->get();

    return view('calendar', compact('events'));
})->name('calendar');

// Zoo demo
Route::get('/zoo', fn () => view('zoo.index'))->name('zoo');

// Serve videos from storage/app/public/videos/*
Route::get('/media/videos/{filename}', function (string $filename) {
    $path = "videos/{$filename}";
    abort_unless(Storage::disk('public')->exists($path), 404);

    return response()->file(
        Storage::disk('public')->path($path),
        ['Content-Type' => 'video/mp4']
    );
})->where('filename', '.*')->name('media.videos');

/*
|--------------------------------------------------------------------------
| Authenticated (verified) user area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');

    // Profile
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Authenticated routes (general users)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Map
    Route::get('/map', [PolygonController::class, 'userPolygons'])->name('map.index');
    Route::get('/map/{waypoint}', [WaypointController::class, 'show'])->name('map.show');

    // ----- Pattern B: Signed link -> redirects to /map?polygon=ID -----
    Route::get('/poly/open/{polygon}', [MapLinkController::class, 'open'])
        ->middleware(['signed', 'can:view,polygon']) // requires valid signature + policy
        ->name('polygon.open');
    // ------------------------------------------------------------------

    // Create a polygon from the map
    Route::post('/waypoints', [WaypointController::class, 'storePolygon'])->name('waypoints.store');

    // Polygons
    Route::post('/polygons', [PolygonController::class, 'store'])->name('polygons.store');

    // View a single polygon (policy enforced at route-level)
    Route::get('/polygons/{polygon}', [PolygonController::class, 'show'])
        ->middleware('can:view,polygon')
        ->name('polygons.show');

    Route::get('/polygons/{polygon}/download', [PolygonController::class, 'download'])
        ->middleware(['auth', 'can:view,polygon'])
        ->name('polygons.download');

    // Group join requests
    Route::post('/join-group', [GroupRequestController::class, 'store'])->name('join-group.store');

    // Groups
    Route::post('/groups',        [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/groups/{group}/join', [GroupController::class, 'requestJoin'])->name('groups.request-join');

    // Group events (member-facing REST)
    Route::get('/groups/{group}/events',            [GroupEventController::class, 'index'])->name('groups.events.index');
    Route::post('/groups/{group}/events',           [GroupEventController::class, 'store'])->name('groups.events.store');
    Route::get('/groups/{group}/events/{event}',    [GroupEventController::class, 'show'])->name('groups.events.show');
    Route::put('/groups/{group}/events/{event}',    [GroupEventController::class, 'update'])->name('groups.events.update');
    Route::delete('/groups/{group}/events/{event}', [GroupEventController::class, 'destroy'])->name('groups.events.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin
|--------------------------------------------------------------------------
*/
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'is_admin'])
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::resource('gallery', GalleryItemController::class)->names([
            'index'   => 'gallery.index',
            'create'  => 'gallery.create',
            'store'   => 'gallery.store',
            'edit'    => 'gallery.edit',
            'update'  => 'gallery.update',
            'destroy' => 'gallery.destroy',
        ]);

        Route::resource('hunting-types', HuntingTypeController::class)->names([
            'index'   => 'hunting-types.index',
            'create'  => 'hunting-types.create',
            'store'   => 'hunting-types.store',
            'edit'    => 'hunting-types.edit',
            'update'  => 'hunting-types.update',
            'destroy' => 'hunting-types.destroy',
        ]);

        Route::post('/users/{user}/make-leader',   [AdminDashboardController::class, 'makeLeader'])->name('users.makeLeader');
        Route::post('/users/{user}/remove-leader', [AdminDashboardController::class, 'removeLeader'])->name('users.removeLeader');

        Route::get('/messages', [ContactController::class, 'index'])->name('messages.index');
    });

/*
|--------------------------------------------------------------------------
| Leader area
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'leader'])
    ->prefix('leader')
    ->name('leader.')
    ->group(function () {
        // Dashboard
        Route::get('/dashboard', [LeaderDashboardController::class, 'index'])->name('dashboard');

        // Quick user management from dashboard
        Route::post('/users', [LeaderDashboardController::class, 'storeUser'])->name('users.store');

        // Add user directly to a group (from dashboard)
        Route::post('/groups/{group}/members', [LeaderDashboardController::class, 'addUserToGroup'])
            ->name('groups.add-user');

        // Join requests moderation
        Route::post('/requests/{groupRequest}/approve', [LeaderDashboardController::class, 'acceptRequest'])->name('requests.approve');
        Route::post('/requests/{groupRequest}/reject',  [LeaderDashboardController::class, 'rejectRequest'])->name('requests.reject');

        // Events created/removed via the leader dashboard modal
        Route::post('/events',           [LeaderDashboardController::class, 'storeEvent'])->name('events.store');
        Route::delete('/events/{event}', [LeaderDashboardController::class, 'destroyEvent'])->name('events.destroy');
    });

require __DIR__ . '/auth.php';
