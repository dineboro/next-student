<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\EmailVerificationController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Instructor\DashboardController as InstructorDashboardController;
use App\Http\Controllers\Instructor\ClassSectionController;
use App\Http\Controllers\HelpRequestController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserApprovalController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ForgotEmailController;

// ============================================================================
// ROOT
// ============================================================================

Route::get('/', fn() => redirect()->route('login'));

// ============================================================================
// AUTH
// ============================================================================

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

// Email Verification
Route::get('/verify-email', [EmailVerificationController::class, 'notice'])->name('verification.notice');
Route::post('/verify-email', [EmailVerificationController::class, 'verify'])->name('verification.verify');
Route::post('/verify-email/resend', [EmailVerificationController::class, 'resend'])->name('verification.resend');
Route::get('/pending-approval', [EmailVerificationController::class, 'pendingApproval'])->name('verification.pending-approval');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Forgot Password
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])->name('password.forgot');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.forgot.send');
Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
Route::post('/reset-password', [ForgotPasswordController::class, 'reset'])->name('password.reset');

// Forgot Email
Route::get('/forgot-email', [ForgotEmailController::class, 'showForm'])->name('email.forgot');
Route::post('/forgot-email', [ForgotEmailController::class, 'lookup'])->name('email.lookup');

// ============================================================================
// STUDENT ROUTES
// ============================================================================

Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    Route::get('/my-requests', [StudentDashboardController::class, 'myRequests'])->name('my-requests');

    // Create / submit request
    Route::get('/requests/create', [HelpRequestController::class, 'create'])->name('requests.create');
    Route::post('/requests', [HelpRequestController::class, 'store'])->name('requests.store');

    // View request
    Route::get('/requests/{helpRequest}', [HelpRequestController::class, 'show'])->name('requests.show');

    // Edit request (pending only)
    Route::get('/requests/{helpRequest}/edit', [HelpRequestController::class, 'edit'])->name('requests.edit');
    Route::put('/requests/{helpRequest}', [HelpRequestController::class, 'update'])->name('requests.update');

    // Cancel request
    Route::get('/requests/{helpRequest}/cancel', [HelpRequestController::class, 'cancelForm'])->name('requests.cancel-form');
    Route::post('/requests/{helpRequest}/cancel', [HelpRequestController::class, 'cancel'])->name('requests.cancel');
});

// ============================================================================
// INSTRUCTOR ROUTES
// ============================================================================

Route::middleware(['auth', 'role:instructor'])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/dashboard', [InstructorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/history', [InstructorDashboardController::class, 'history'])->name('history');

    // Class section management
    Route::get('/sections/search-student', [ClassSectionController::class, 'searchStudent'])->name('sections.search-student');
    Route::resource('sections', ClassSectionController::class)->names('sections');

// Roster management
    Route::post('/sections/{section}/students', [ClassSectionController::class, 'addStudent'])->name('sections.students.add');
    Route::delete('/sections/{section}/students/{student}', [ClassSectionController::class, 'removeStudent'])->name('sections.students.remove');
    // Request actions
    Route::get('/requests/{helpRequest}', [HelpRequestController::class, 'show'])->name('requests.show');
    Route::post('/requests/{helpRequest}/complete', [HelpRequestController::class, 'markComplete'])->name('requests.complete');
    Route::post('/requests/{helpRequest}/cancel', [HelpRequestController::class, 'instructorCancel'])->name('requests.cancel');
});

// ============================================================================
// SHARED AUTH ROUTES
// ============================================================================

Route::middleware('auth')->group(function () {
    // Comment — post new comment (returns JSON)
    Route::post('/help-requests/{helpRequest}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Poll comments for near-real-time updates (returns JSON)
    Route::get('/help-requests/{helpRequest}/comments/poll', [HelpRequestController::class, 'pollComments'])->name('comments.poll');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update-password');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::put('/settings/notifications', [SettingsController::class, 'updateNotifications'])->name('settings.update-notifications');
    Route::put('/settings/theme', [SettingsController::class, 'updateTheme'])->name('settings.update-theme');
    Route::delete('/settings/account', [SettingsController::class, 'deleteAccount'])->name('settings.delete-account');

    // Profile email update
    Route::put('/profile/email', [ProfileController::class, 'updateEmail'])->name('profile.update-email');

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.mark-read');
});

// ============================================================================
// ADMIN ROUTES
// ============================================================================

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Instructor approvals (only instructors need manual admin approval)
    Route::get('/users/pending', [UserApprovalController::class, 'pending'])->name('users.pending');
    Route::post('/users/{user}/approve', [UserApprovalController::class, 'approve'])->name('users.approve');
    Route::post('/users/{user}/reject', [UserApprovalController::class, 'reject'])->name('users.reject');

    // User lists
    Route::get('/students', [AdminUserController::class, 'students'])->name('students');
    Route::get('/instructors', [AdminUserController::class, 'instructors'])->name('instructors');

    // Request lists
    Route::get('/requests', [AdminDashboardController::class, 'requests'])->name('requests');
});
