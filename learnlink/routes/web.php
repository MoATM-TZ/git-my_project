<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReputationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| The routes below map the legacy procedural endpoints (index.php, login.php,
| search.php, upload_process.php, resource-detail.php, vote_process.php, etc.)
| onto structured controllers.
|
*/

// Public landing page (legacy index.php)
Route::get('/', HomeController::class)->name('home');

// Public search (legacy search.php + search_live.php)
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/live', [SearchController::class, 'live'])->name('search.live');

// Public reputation standings (legacy reputation.php)
Route::get('/reputation', [ReputationController::class, 'index'])->name('reputation.index');

// Guest-only auth screens (legacy login.php / register.php)
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// Authenticated actions (legacy logout.php, profile.php, upload_process.php,
// vote_process.php, comment_process.php, report_process.php)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Upload (legacy upload.php / upload_process.php)
    Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');

    // Interactions on a resource
    Route::post('/resources/{resource}/votes', [VoteController::class, 'store'])->name('resources.votes.store');
    Route::post('/resources/{resource}/comments', [CommentController::class, 'store'])->name('resources.comments.store');
    Route::post('/resources/{resource}/reports', [ReportController::class, 'store'])->name('resources.reports.store');

    // Owner-only deletion
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
});

// Public resource detail + download (legacy resource-detail.php / download_resource.php)
// Declared after /resources/create so "create" is never captured as {resource}.
Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download');
