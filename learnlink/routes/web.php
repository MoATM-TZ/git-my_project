<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LecturerAuthController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CourseClassController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminResourceController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AcademicController;
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

Route::prefix('lecturer')->name('lecturer.')->middleware('guest')->group(function () {
    Route::get('login', [LecturerAuthController::class, 'createLogin'])->name('login');
    Route::post('login', [LecturerAuthController::class, 'login'])->name('login.store');
    Route::get('register', [LecturerAuthController::class, 'createRegister'])->name('register');
    Route::post('register', [LecturerAuthController::class, 'register'])->name('register.store');
});
Route::prefix('student')->name('student.')->middleware('guest')->group(function () {
    Route::get('login', [StudentAuthController::class, 'createLogin'])->name('login');
    Route::post('login', [StudentAuthController::class, 'login'])->name('login.store');
    Route::get('register', [StudentAuthController::class, 'createRegister'])->name('register');
    Route::post('register', [StudentAuthController::class, 'register'])->name('register.store');
});
Route::prefix('admin')->name('admin.')->middleware('guest')->group(function () {
    Route::get('login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('login', [AdminAuthController::class, 'store'])->name('login.store');
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
    Route::patch('users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('academic-structure', [AcademicController::class, 'index'])->name('academic.index');
    Route::post('academic-structure/departments', [AcademicController::class, 'storeDepartment'])->name('academic.departments.store');
    Route::post('academic-structure/programs', [AcademicController::class, 'storeProgram'])->name('academic.programs.store');
    Route::post('academic-structure/settings', [AcademicController::class, 'storeSetting'])->name('academic.settings.store');
    Route::patch('classes/{courseClass}', [AcademicController::class, 'updateClass'])->name('classes.update');
    Route::delete('classes/{courseClass}', [AcademicController::class, 'destroyClass'])->name('classes.destroy');
    Route::get('reports', [AdminResourceController::class, 'reports'])->name('reports.index');
    Route::patch('reports/{report}/keep', [AdminResourceController::class, 'keep'])->name('reports.keep');
    Route::patch('reports/{report}/remove', [AdminResourceController::class, 'remove'])->name('reports.remove');
    Route::patch('reports/{report}/warn', [AdminResourceController::class, 'warn'])->name('reports.warn');
});
Route::prefix('lecturer')->name('lecturer.')->middleware(['auth', 'role:lecturer'])->group(function () {
    Route::get('dashboard', [CourseClassController::class, 'dashboard'])->name('dashboard');
    Route::post('classes', [CourseClassController::class, 'store'])->name('classes.store');
    Route::get('classes/{courseClass}', [CourseClassController::class, 'show'])->name('classes.show');
    Route::patch('classes/{courseClass}/archive', [CourseClassController::class, 'archive'])->name('classes.archive');
    Route::post('classes/{courseClass}/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
});
Route::prefix('student')->name('student.')->middleware(['auth', 'role:student'])->group(function () {
    Route::get('dashboard', StudentDashboardController::class)->name('dashboard');
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
Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->middleware('auth')->name('resources.download');
