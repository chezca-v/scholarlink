<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AIController;
use App\Http\Controllers\SMSController;

// AI & Matching Endpoints
Route::post('/match', [AIController::class, 'match']);
Route::get('/chatbot', [AIController::class, 'match']);

// Hardware Gateway (ESP32 + SIM800L)
Route::post('/send-sms', [SMSController::class, 'send']);

// Authenticated User Data
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
