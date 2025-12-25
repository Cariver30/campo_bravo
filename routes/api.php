<?php

use App\Http\Controllers\Api\ManagerDashboardController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\ServerVisitController;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function () {
    Route::post('/login', [MobileAuthController::class, 'login']);

    Route::middleware('mobile.api')->group(function () {
        Route::post('/logout', [MobileAuthController::class, 'logout']);
    });

    Route::prefix('servers')->middleware('mobile.api:server')->group(function () {
        Route::get('/visits/summary', [ServerVisitController::class, 'summary']);
        Route::post('/visits', [ServerVisitController::class, 'store']);
    });

    Route::prefix('managers')->middleware('mobile.api:manager')->group(function () {
        Route::get('/dashboard', [ManagerDashboardController::class, 'summary']);
        Route::get('/servers', [ManagerDashboardController::class, 'servers']);
        Route::patch('/servers/{user}/toggle', [ManagerDashboardController::class, 'toggleServer']);
    });
});

// Rutas legacy para compatibilidad con versiones anteriores de la app.
Route::prefix('servers')->group(function () {
    Route::post('/login', [MobileAuthController::class, 'login']);

    Route::middleware('mobile.api:server')->group(function () {
        Route::post('/logout', [MobileAuthController::class, 'logout']);
        Route::get('/visits/summary', [ServerVisitController::class, 'summary']);
        Route::post('/visits', [ServerVisitController::class, 'store']);
    });
});
