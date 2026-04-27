<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;

// AI & Matching Endpoints
Route::post('/match', [AIController::class, 'match']);
Route::get('/chatbot', [AIController::class, 'match']);


// Authenticated User Data
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
