<?php

use App\Http\Controllers\Api\CampaignController;
use App\Http\Controllers\Api\CocktailManagementController;
use App\Http\Controllers\Api\ManagerDashboardController;
use App\Http\Controllers\Api\MenuManagementController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\ServerVisitController;
use App\Http\Controllers\Api\WineManagementController;
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

        Route::get('/menu/categories', [MenuManagementController::class, 'categories']);
        Route::post('/menu/dishes', [MenuManagementController::class, 'storeDish']);
        Route::post('/menu/dishes/reorder', [MenuManagementController::class, 'reorderDishes']);
        Route::put('/menu/dishes/{dish}', [MenuManagementController::class, 'updateDish']);
        Route::delete('/menu/dishes/{dish}', [MenuManagementController::class, 'destroyDish']);
        Route::patch('/menu/dishes/{dish}/toggle', [MenuManagementController::class, 'toggleDish']);

        Route::get('/cocktails/categories', [CocktailManagementController::class, 'categories']);
        Route::post('/cocktails/items', [CocktailManagementController::class, 'store']);
        Route::post('/cocktails/items/reorder', [CocktailManagementController::class, 'reorder']);
        Route::put('/cocktails/items/{cocktail}', [CocktailManagementController::class, 'update']);
        Route::delete('/cocktails/items/{cocktail}', [CocktailManagementController::class, 'destroy']);
        Route::patch('/cocktails/items/{cocktail}/toggle', [CocktailManagementController::class, 'toggle']);

        Route::get('/wines/categories', [WineManagementController::class, 'categories']);
        Route::post('/wines/items', [WineManagementController::class, 'store']);
        Route::post('/wines/items/reorder', [WineManagementController::class, 'reorder']);
        Route::put('/wines/items/{wine}', [WineManagementController::class, 'update']);
        Route::delete('/wines/items/{wine}', [WineManagementController::class, 'destroy']);
        Route::patch('/wines/items/{wine}/toggle', [WineManagementController::class, 'toggle']);

        Route::get('/campaigns', [CampaignController::class, 'index']);
        Route::post('/campaigns', [CampaignController::class, 'store']);
        Route::put('/campaigns/{popup}', [CampaignController::class, 'update']);
        Route::delete('/campaigns/{popup}', [CampaignController::class, 'destroy']);
        Route::patch('/campaigns/{popup}/toggle', [CampaignController::class, 'toggle']);
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
