<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminResourceController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AcademicController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\CourseClassController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReputationController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');
Route::get('/search/live', [SearchController::class, 'live'])->name('search.live');
Route::get('/reputation', [ReputationController::class, 'index'])->name('reputation.index');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
});

// Retain bookmarked role-specific login and registration URLs during transition.
Route::get('/student/login', fn () => redirect()->route('login'))->name('student.login');
Route::get('/lecturer/login', fn () => redirect()->route('login'))->name('lecturer.login');
Route::get('/admin/login', fn () => redirect()->route('login'))->name('admin.login');
Route::get('/student/register', fn () => redirect()->route('register'))->name('student.register');
Route::get('/lecturer/register', fn () => redirect()->route('register'))->name('lecturer.register');

Route::middleware(['auth', 'active'])->group(function (): void {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');
    Route::get('/approval-notice', fn () => view('auth.approval-notice'))
        ->middleware('approved')
        ->name('approval.notice');
});

Route::middleware(['auth', 'active', 'approved'])->group(function (): void {
    // Profile completion must be reachable before profile.complete is enforced.
    Route::prefix('onboarding')->name('onboarding.')->group(function (): void {
        Route::get('/student', [OnboardingController::class, 'student'])->name('student.edit');
        Route::post('/student', [OnboardingController::class, 'saveStudent'])->name('student.save');
        Route::get('/lecturer', [OnboardingController::class, 'lecturer'])->name('lecturer.edit');
        Route::post('/lecturer', [OnboardingController::class, 'saveLecturer'])->name('lecturer.save');
    });
    Route::middleware('profile.complete')->group(function (): void {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
    Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
    Route::post('/resources/{resource}/votes', [VoteController::class, 'store'])->name('resources.votes.store');
    Route::post('/resources/{resource}/comments', [CommentController::class, 'store'])->name('resources.comments.store');
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');
    Route::post('/resources/{resource}/reports', [ReportController::class, 'store'])->name('resources.reports.store');
    Route::post('/comments/{comment}/reports', [ReportController::class, 'storeComment'])->name('comments.reports.store');
    Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
    Route::get('/resources/{resource}/download', [ResourceController::class, 'download'])->name('resources.download');

    Route::prefix('student')->name('student.')->middleware('role:student')->group(function (): void {
        Route::get('/dashboard', StudentDashboardController::class)->name('dashboard');
    });

    Route::prefix('lecturer')->name('lecturer.')->middleware('role:lecturer')->group(function (): void {
        Route::get('/dashboard', [CourseClassController::class, 'dashboard'])->name('dashboard');
        Route::post('/classes', [CourseClassController::class, 'store'])->name('classes.store');
        Route::get('/classes/{courseClass}', [CourseClassController::class, 'show'])->name('classes.show');
        Route::patch('/classes/{courseClass}/archive', [CourseClassController::class, 'archive'])->name('classes.archive');
        Route::post('/classes/{courseClass}/announcements', [AnnouncementController::class, 'store'])->name('announcements.store');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function (): void {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/approvals', [AdminController::class, 'approvals'])->name('approvals.index');
        Route::post('/approvals/{user}/approve', [AdminController::class, 'approve'])->name('approvals.approve');
        Route::post('/approvals/{user}/reject', [AdminController::class, 'reject'])->name('approvals.reject');
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [AdminUserController::class, 'updateRole'])->name('users.role.update');
        Route::patch('/users/{user}/status', [AdminUserController::class, 'toggleStatus'])->name('users.status.toggle');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
        Route::get('/academic-structure', [AcademicController::class, 'index'])->name('academic.index');
        Route::post('/academic-structure/departments', [AcademicController::class, 'storeDepartment'])->name('academic.departments.store');
        Route::post('/academic-structure/programs', [AcademicController::class, 'storeProgram'])->name('academic.programs.store');
        Route::post('/academic-structure/settings', [AcademicController::class, 'storeSetting'])->name('academic.settings.store');
        Route::patch('/classes/{courseClass}', [AcademicController::class, 'updateClass'])->name('classes.update');
        Route::delete('/classes/{courseClass}', [AcademicController::class, 'destroyClass'])->name('classes.destroy');
        Route::get('/reports', [AdminResourceController::class, 'reports'])->name('reports.index');
        Route::patch('/reports/{report}/keep', [AdminResourceController::class, 'keep'])->name('reports.keep');
        Route::patch('/reports/{report}/remove', [AdminResourceController::class, 'remove'])->name('reports.remove');
        Route::patch('/reports/{report}/warn', [AdminResourceController::class, 'warn'])->name('reports.warn');
    });
    });
});

Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
