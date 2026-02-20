<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\RequestManagementController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\InstructorListController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SchoolRegistrationController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\SchoolApprovalController;


// ============================================================================
// PUBLIC ROUTES
// ============================================================================

Route::get('/', function () {
    return redirect()->route('login');
});

// School Registration
Route::get('/register-school', [SchoolRegistrationController::class, 'showForm'])->name('school-registration.create');
Route::post('/register-school', [SchoolRegistrationController::class, 'store'])->name('school-registration.store');
Route::get('/school-registration/success', [SchoolRegistrationController::class, 'success'])->name('school-registration.success');

// ============================================================================
// AUTHENTICATION ROUTES
// ============================================================================

Route::middleware('guest')->group(function () {
    // Registration
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    // Login
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Email Verification
Route::get('/verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::post('/verify-email', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('/verify-email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
Route::get('/pending-approval', [EmailVerificationController::class, 'pendingApproval'])->name('verification.pending-approval');

// Logout
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================================
// STUDENT ROUTES
// ============================================================================

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-requests', [StudentDashboardController::class, 'myRequests'])->name('my-requests');

    // Request Management
    Route::get('/requests/{helpRequest}/edit', [RequestManagementController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{helpRequest}', [RequestManagementController::class, 'update'])->name('requests.update');
    Route::get('/requests/{helpRequest}/cancel', [RequestManagementController::class, 'cancelForm'])->name('requests.cancel-form');
    Route::post('/requests/{helpRequest}/cancel', [RequestManagementController::class, 'cancel'])->name('requests.cancel');
});

// ============================================================================
// INSTRUCTOR ROUTES
// ============================================================================

Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-availability', [InstructorDashboardController::class, 'toggleAvailability'])->name('toggle-availability');
    Route::get('/active-requests', [InstructorDashboardController::class, 'activeRequests'])->name('active-requests');
    Route::get('/history', [InstructorDashboardController::class, 'history'])->name('history');

    // Instructor List
    Route::get('/instructors', [InstructorListController::class, 'index'])->name('instructors-list');
    Route::get('/instructors/{instructor}', [InstructorListController::class, 'show'])->name('instructors.show');
});

// ============================================================================
// SHARED ROUTES (AUTHENTICATED USERS)
// ============================================================================

Route::middleware('auth')->group(function () {
    // Help Requests
    Route::resource('help-requests', HelpRequestController::class)->except(['edit']);

    // Comments
    Route::post('/help-requests/{helpRequest}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.update-notifications');
    Route::put('/settings/privacy', [SettingsController::class, 'updatePrivacy'])->name('settings.update-privacy');
    Route::put('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.update-theme');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete-account');
});

// ============================================================================
// ADMIN ROUTES
// ============================================================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Approvals
    Route::get('/users/pending', [UserApprovalController::class, 'pending'])->name('users.pending');
    Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');

    // School Approvals
    Route::get('/schools/pending', [SchoolApprovalController::class, 'pending'])->name('schools.pending');
    Route::post('/schools/{request}/approve', [SchoolApprovalController::class, 'approve'])->name('schools.approve');
    Route::post('/schools/{request}/reject', [SchoolApprovalController::class, 'reject'])->name('schools.reject');

    // School Registration Requests
    Route::get('/school-requests/pending', [SchoolApprovalController::class, 'pendingRequests'])->name('school-requests.pending');
});
