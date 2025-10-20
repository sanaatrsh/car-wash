<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\WashTypeController;
use Illuminate\Support\Facades\Route;

Route::post('/signin', [AuthController::class, 'signIn']);
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {
    //stations
    Route::get('/stations', [StationController::class, 'index']);

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('stations', StationController::class)->except(['index']);
    });

    //wash-types
    Route::get('/wash-types', [WashTypeController::class, 'index']);

    Route::middleware('role:admin')->group(function () {
        Route::apiResource('wash-types', WashTypeController::class)->except(['index']);
    });
});
