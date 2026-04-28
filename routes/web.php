<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PublicController,
    ScholarshipController,
    ProfileController,
    DocumentController,
    ApplicationController,
    SavedScholarshipController,
    NotificationController,
    AdminController,
    EvaluatorController,
    EvaluationController,
    SuperadminController
};

/*
Public Routes
*/
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'landing')->name('landing');
});

Route::controller(ScholarshipController::class)->group(function () {
    Route::get('/scholarships', 'index')->name('scholarships.index');
    Route::get('/scholarships/{id}', 'show')->name('scholarships.show');
});

Route::middleware(['auth', 'role:applicant'])->group(function () {

    // Profile setup (available immediately after registration)
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile/setup', 'setup')->name('profile.setup');
        Route::post('/profile/setup/step-1', 'setupStep1')->name('profile.setup.step1');
        Route::post('/profile/setup/step-2', 'setupStep2')->name('profile.setup.step2');
        Route::post('/profile/setup/step-3', 'setupStep3')->name('profile.setup.step3');
        Route::post('/profile/setup/submit', 'setupSubmit')->name('profile.setup.submit');
    });
});

/*
Applicant Routes (Role: applicant + verified)
*/
Route::middleware(['auth', 'verified', 'role:applicant'])->group(function () {

    // Dashboard & Profile Management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/applicant/dashboard', 'dashboard')->name('applicant.dashboard');
        Route::get('/profile', 'edit')->name('profile.show');
        Route::patch('/profile/update', 'update')->name('profile.update');
    });

    //Document Wallet
    Route::controller(DocumentController::class)->prefix('applicant/documents')->name('applicant.documents.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'store')->name('store');
        Route::get('/{id}/preview', 'preview')->name('preview');
    });

    // Backward-compatible route aliases used by older views/scripts
    Route::post('/applicant/documents', [DocumentController::class, 'store'])->name('applicant.documents.store');
    Route::get('/applicant/documents/{id}/preview', [DocumentController::class, 'preview'])->name('applicant.documents.preview');
    Route::get('/applicant/applications', [ApplicationController::class, 'index'])->name('applicant.applications.index');

    // Application Lifecycle
    Route::controller(ApplicationController::class)->group(function () {
        Route::get('/applicant/applications', 'index')->name('applications.index');
        Route::get('/apply/{id}', 'create')->name('applications.create');
        Route::post('/apply/{id}', 'store')->name('applications.store');
        Route::get('/applicant/applications/{id}/track', 'track')->name('applications.track');
    });

    // Saved Scholarships & Notifications
    Route::get('/applicant/saved', [SavedScholarshipController::class, 'index'])->name('applicant.saved');
    Route::post('/scholarships/{id}/save', [SavedScholarshipController::class, 'store'])->name('scholarships.save');
    Route::delete('/scholarships/{id}/unsave', [SavedScholarshipController::class, 'destroy'])->name('scholarships.unsave');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::post('/notifications/{id}/mark-read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
});

/*
Admin Routes (Role: admin)
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Scholarship CRUD & Extensions
    Route::resource('scholarships', ScholarshipController::class);
    Route::controller(ScholarshipController::class)->group(function () {
        Route::post('/scholarships/{id}/close', 'close')->name('scholarships.close');
        Route::post('/scholarships/{id}/extend', 'extendDeadline')->name('scholarships.extend');
    });

    // User Management & Analytics
    Route::controller(AdminController::class)->group(function () {
        Route::get('/users', 'users')->name('users');
        Route::post('/users/create', 'createUser')->name('users.create');
        Route::get('/analytics', 'analytics')->name('analytics');
        Route::get('/calendar', 'calendar')->name('calendar');
    });
});

/*
6.4 Evaluator Routes (Role: evaluator)
*/
Route::middleware(['auth', 'role:evaluator'])->prefix('evaluator')->name('evaluator.')->group(function () {
    Route::get('/dashboard', [EvaluatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/queue', [EvaluatorController::class, 'queue'])->name('queue');

    // Notifications & Profile
    Route::controller(EvaluatorController::class)->group(function () {
        Route::get('/notifications', 'notifications')->name('notifications');
        Route::post('/notifications/mark-all-read', 'markAllRead')->name('notifications.markAllRead');
        Route::post('/notifications/{id}/mark-read', 'markRead')->name('notifications.markRead');

        Route::get('/profile', 'profile')->name('profile');
        Route::patch('/profile', 'profileUpdate')->name('profile.update');
    });

    // Evaluation Logic
    Route::controller(EvaluationController::class)->group(function () {
        Route::get('/review/{id}', 'show')->name('review.show');
        Route::post('/review/{id}', 'store')->name('review.store');
        Route::get('/review/{id}/reject', 'reject')->name('rejection');
        Route::post('/review/{id}/reject', 'submitRejection')->name('rejection.store');
        Route::get('/completed', 'completed')->name('completed');
    });
});

/*
Superadmin Routes (Role: superadmin)
*/
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::controller(SuperadminController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');

        // Organizations
        Route::get('/organizations', 'organizations')->name('organizations');
        Route::post('/organizations', 'storeOrganization')->name('organizations.store');
        Route::put('/organizations/{id}', 'updateOrganization')->name('organizations.update');
        Route::delete('/organizations/{id}', 'destroyOrganization')->name('organizations.destroy');

        // Admin Accounts
        Route::get('/admins', 'admins')->name('admins');
        Route::post('/admins', 'storeAdmin')->name('admins.store');
        Route::put('/admins/{id}', 'updateAdmin')->name('admins.update');
        Route::patch('/admins/{id}/deactivate', 'deactivateAdmin')->name('admins.deactivate');
        Route::patch('/admins/{id}/reassign', 'reassignAdmin')->name('admins.reassign');

        // Logs & Settings
        Route::get('/logs', 'logs')->name('logs');
        Route::get('/settings', 'settings')->name('settings');
        Route::patch('/settings', 'updateSettings')->name('settings.update');
    });
});

require __DIR__.'/auth.php';
