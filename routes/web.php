<?php

use App\Http\Controllers\PublicController;
use App\Http\Controllers\ScholarshipController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EvaluatorController;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\SuperadminController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', [PublicController::class, 'landing'])->name('landing');
Route::get('/scholarships', [ScholarshipController::class, 'index'])->name('scholarships.index');
Route::get('/scholarships/{id}', [ScholarshipController::class, 'show'])->name('scholarships.show');

// Applicant Routes (Role: applicant)
Route::middleware(['auth', 'verified', 'role:applicant'])->group(function () {
    Route::get('/dashboard', [ProfileController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile/setup', [ProfileController::class, 'setup'])->name('profile.setup');
    Route::get('/applicant/documents', [DocumentController::class, 'index'])->name('documents.index');
});

// Admin Routes (Role: admin)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('scholarships', ScholarshipController::class)->except(['index', 'show']);
});

// Evaluator Routes (Role: evaluator)
Route::middleware(['auth', 'role:evaluator'])->prefix('evaluator')->name('evaluator.')->group(function () {
    Route::get('/dashboard', [EvaluatorController::class, 'dashboard'])->name('dashboard');
    Route::get('/review/{id}', [EvaluationController::class, 'show'])->name('review');
});

// Superadmin Routes (Role: superadmin)
Route::middleware(['auth', 'role:superadmin'])->prefix('superadmin')->name('superadmin.')->group(function () {
    Route::get('/dashboard', [SuperadminController::class, 'dashboard'])->name('dashboard');
    Route::get('/organizations', [SuperadminController::class, 'organizations'])->name('organizations');
});

require __DIR__.'/auth.php';
