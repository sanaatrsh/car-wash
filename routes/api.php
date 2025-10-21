<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\WashTypeController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Authenticated routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Stations - Public listing
    Route::get('/stations', [StationController::class, 'index']);

    // Wash Types - Public listing
    Route::get('/wash-types', [WashTypeController::class, 'index']);

    // Bookings - User actions
    Route::prefix('bookings')->group(function () {
        Route::get('/my-booking', [BookingController::class, 'userIndex']);
        Route::post('/', [BookingController::class, 'store']);
        Route::put('/{booking}/cancel', [BookingController::class, 'cancel']);
    });

    // Admin routes
    Route::middleware('role:admin')->group(function () {
        Route::apiResource('stations', StationController::class)->except(['index']);
        Route::apiResource('wash-types', WashTypeController::class)->except(['index']);
        Route::apiResource('bookings', BookingController::class)->only(['index', 'update', 'destroy']);
    });
});
