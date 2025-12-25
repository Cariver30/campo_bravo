<?php

use App\Http\Controllers\Api\ServerAuthController;
use App\Http\Controllers\Api\ServerVisitController;
use Illuminate\Support\Facades\Route;

Route::prefix('servers')->group(function () {
    Route::post('/login', [ServerAuthController::class, 'login']);

    Route::middleware('server.api')->group(function () {
        Route::post('/logout', [ServerAuthController::class, 'logout']);
        Route::get('/visits/summary', [ServerVisitController::class, 'summary']);
        Route::post('/visits', [ServerVisitController::class, 'store']);
    });
});
