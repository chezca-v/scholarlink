<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    PublicController,
    ScholarshipController,
    AIController,
    ProfileController,
    DocumentController,
    ApplicationController,
    SavedScholarshipController,
    NotificationController,
    AdminController,
    EvaluatorController,
    EvaluationController,
    SuperadminController,
    StaticPageController
};
use App\Http\Controllers\Admin\ScholarshipController as AdminScholarshipController;

/*
Public Routes
*/
Route::controller(PublicController::class)->group(function () {
    Route::get('/', 'landing')->name('landing');
});

// Static Public Pages
Route::get('/about', [StaticPageController::class, 'about'])->name('about');
Route::get('/organizations', [StaticPageController::class, 'organizations'])->name('organizations');

// Legacy/alias redirects
Route::get('/about.php', fn() => redirect()->route('about'));
Route::get('/organizations.php', fn() => redirect()->route('organizations'));

// Static Legal Pages
Route::get('/terms', [StaticPageController::class, 'terms'])->name('terms');
Route::get('/privacy', [StaticPageController::class, 'privacy'])->name('privacy');
Route::get('/data-privacy', [StaticPageController::class, 'dataPrivacy'])->name('data-privacy');

Route::controller(ScholarshipController::class)->group(function () {
    Route::get('/scholarships', 'index')->name('scholarships.index');
    Route::get('/scholarships/{id}', 'show')->name('scholarships.show');
});
Route::post('/ai/chat', [AIController::class, 'chat'])->name('ai.chat');
Route::get('/scholarships/{id}/ai-insight', [AIController::class, 'getScholarshipAIInsight'])->name('scholarships.ai-insight')->middleware('auth');

Route::middleware(['auth', 'role:applicant'])->group(function () {
    Route::get('/profile/setup', [ProfileController::class, 'setup'])->name('profile.setup');
    Route::post('/profile/setup/step-1', [ProfileController::class, 'setupStep1'])->name('profile.setup.step1');
    Route::post('/profile/setup/step-2', [ProfileController::class, 'setupStep2'])->name('profile.setup.step2');
    Route::post('/profile/setup/step-3', [ProfileController::class, 'setupStep3'])->name('profile.setup.step3');
    Route::post('/profile/setup', [ProfileController::class, 'setupSubmit'])->name('profile.setup.submit');
});

/*
Applicant Routes (Role: applicant)
*/
Route::middleware(['auth', 'verified', 'role:applicant', 'profile.completed'])->group(function () {

    // Dashboard & Profile Management
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/applicant/dashboard', 'dashboard')->name('applicant.dashboard');
        
        // Profile Setup Routes
        Route::get('/applicant/setup', 'setup')->name('profile.setup');
        Route::post('/applicant/setup/step1', 'setupStep1')->name('profile.setup.step1');
        Route::post('/applicant/setup/step2', 'setupStep2')->name('profile.setup.step2');
        Route::post('/applicant/setup/step3', 'setupStep3')->name('profile.setup.step3');
        Route::post('/applicant/setup/submit', 'setupSubmit')->name('profile.setup.submit');

        Route::get('/profile', 'edit')->name('profile.show');
        Route::patch('/profile/update', 'update')->name('profile.update');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });

    //Document Wallet
    Route::controller(DocumentController::class)->prefix('applicant/documents')->name('applicant.documents.')->group(function () {
        Route::get('', 'index')->name('index');
        Route::post('', 'store')->name('store');
        Route::get('/{id}/preview', 'preview')->name('preview');
        Route::delete('/{id}', 'destroy')->name('destroy');
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
        Route::get('/applicant/applications/{id}', 'show')->name('applications.show');
        Route::get('/applicant/applications/{id}/track', 'track')->name('applications.track');
        Route::post('/applicant/applications/{id}/offer', 'respondToOffer')->name('applicant.offer.respond');
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
    Route::get('/', function () { return redirect()->route('admin.dashboard'); });
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Scholarship CRUD & Extensions
    Route::resource('scholarships', AdminScholarshipController::class);
    Route::controller(AdminScholarshipController::class)->group(function () {
        Route::post('/scholarships/{id}/close', 'close')->name('scholarships.close');
        Route::post('/scholarships/{id}/extend', 'extendDeadline')->name('scholarships.extend');
        Route::patch('/scholarships/{id}/toggle', 'toggle')->name('scholarships.toggle');
        Route::get('/scholarships/{id}/applications/export', 'exportApplications')->name('scholarships.applications.export');
    });

    // User Management & Analytics
    Route::controller(AdminController::class)->group(function () {
        Route::get('/users', 'users')->name('users');
        Route::post('/users/create', 'createUser')->name('users.create');
        Route::get('/analytics', 'analytics')->name('analytics');
        Route::get('/analytics/export', 'exportAnalytics')->name('analytics.export');
        Route::get('/calendar', 'calendar')->name('calendar');
        Route::get('/applications', 'applications')->name('applications');
        Route::get('/applications/{id}', 'showApplication')->name('applications.show');
        Route::patch('/applications/bulk-assign', 'bulkAssign')->name('applications.bulk-assign');
        Route::patch('/applications/{id}/assign', 'assign')->name('applications.assign');
        Route::patch('/applications/bulk-approve', 'bulkApprove')->name('applications.bulk-approve');
        Route::patch('/applications/bulk-reject', 'bulkReject')->name('applications.bulk-reject');
        Route::patch('/applications/{id}/approve', 'approveApplication')->name('applications.approve');
        Route::get('/applications/{id}/reject', 'rejectForm')->name('applications.reject-form');
        Route::get('/reviews', 'reviews')->name('reviews');
        Route::get('/settings', 'settings')->name('settings');
        Route::patch('/settings', 'updateSettings')->name('settings.update');
    });
});

/*
6.4 Evaluator Routes (Role: evaluator)
*/
Route::middleware(['auth', 'role:evaluator'])->prefix('evaluator')->name('evaluator.')->group(function () {
    Route::get('/', function () { return redirect()->route('evaluator.dashboard'); });
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
    Route::get('/', function () { return redirect()->route('superadmin.dashboard'); });
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

        // Notifications
        Route::get('/notifications', 'notifications')->name('notifications');
        Route::post('/notifications/mark-all-read', 'markAllReadNotifications')->name('notifications.markAllRead');
        Route::post('/notifications/{id}/mark-read', 'markReadNotification')->name('notifications.markRead');
    });
});

require __DIR__.'/auth.php';
