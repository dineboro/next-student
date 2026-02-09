<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\CommentController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Student routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-requests', [StudentDashboardController::class, 'myRequests'])->name('my-requests');
});

// Instructor routes
Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-availability', [InstructorDashboardController::class, 'toggleAvailability'])->name('toggle-availability');
    Route::get('/active-requests', [InstructorDashboardController::class, 'activeRequests'])->name('active-requests');
    Route::get('/history', [InstructorDashboardController::class, 'history'])->name('history');
});

// Help Request routes
Route::middleware('auth')->group(function () {
    Route::resource('help-requests', HelpRequestController::class)->except(['edit']);

    // Comments
    Route::post('/help-requests/{helpRequest}/comments', [CommentController::class, 'store'])->name('comments.store');
});

