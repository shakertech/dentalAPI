<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Patient Routes
    Route::delete('/patients/{id}/force', [\App\Http\Controllers\PatientController::class, 'forceDelete']);
    Route::post('/patients/{id}/restore', [\App\Http\Controllers\PatientController::class, 'restore']);
    Route::apiResource('patients', \App\Http\Controllers\PatientController::class);

    // Future API routes for Visits, etc. will go here
});
