<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/signin', [AuthController::class, 'signIn']);
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/stations', [StationController::class, 'index']);
    Route::get('/stations/{station}', [StationController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('stations', StationController::class)->except(['index', 'show']);
    });
});
