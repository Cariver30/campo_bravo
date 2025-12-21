<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DishController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CocktailController;
use App\Http\Controllers\CocktailCategoryController;
use App\Http\Controllers\WineCategoryController;
use App\Http\Controllers\WineController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\FoodPairingController;
use App\Http\Controllers\WineTypeController;
use App\Http\Controllers\Admin\EventManagementController;
use App\Http\Controllers\EventPublicController;
use App\Http\Controllers\EventNotificationController;
use App\Http\Controllers\Admin\EventPromotionController;

use App\Http\Controllers\HomeController;

// Rutas públicas
Route::get('/', [HomeController::class, 'cover'])->name('cover');
Route::get('/menu', [MenuController::class, 'index']);
Route::get('/cocktails', [CocktailController::class, 'index'])->name('cocktails.index');
Route::get('/reservations', function () {
    return redirect()->away('https://asador-1293f.web.app/');
})->name('reservations.app');
Route::resource('categories', CategoryController::class);
Route::resource('dishes', DishController::class);
Route::resource('cocktails', CocktailController::class);
Route::resource('cocktail-categories', CocktailCategoryController::class);
Route::post('/admin/dishes/reorder', [DishController::class, 'reorder'])->name('dishes.reorder');
Route::post('/admin/cocktails/reorder', [CocktailController::class, 'reorder'])->name('cocktails.reorder');
Route::post('/admin/wines/reorder', [WineController::class, 'reorder'])->name('wines.reorder');

Route::prefix('experiencias')->name('experiences.')->group(function () {
    Route::get('/', [EventPublicController::class, 'index'])->name('index');
    Route::get('/{event:slug}', [EventPublicController::class, 'show'])->name('show');
    Route::post('/{event:slug}/tickets', [EventPublicController::class, 'purchase'])->name('purchase');
    Route::post('/registro', [EventNotificationController::class, 'subscribeGeneral'])->name('notify.general');
    Route::post('/registro/cover', [EventNotificationController::class, 'subscribeFromCover'])->name('notify.cover');
    Route::post('/{event:slug}/notify', [EventNotificationController::class, 'subscribe'])->name('notify');
});

Route::middleware('auth')->group(function () {
    Route::get('/admin/panel', [AdminController::class, 'panel'])->name('admin.panel');
    Route::get('/admin', [AdminController::class, 'panel'])->name('admin');
    Route::get('/admin/categories', [CategoryController::class, 'showCategories'])->name('admin.categories');
    Route::get('/admin/new-panel', [AdminController::class, 'newAdminPanel'])->name('admin.new-panel');

    Route::post('/update-category-order', [CategoryController::class, 'updateOrder'])->name('categories.reorder');
    Route::get('/categories/json', [CategoryController::class, 'getCategoriesJson']);

    Route::get('/admin/settings/edit', [SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/admin/settings/update', [SettingController::class, 'update'])->name('settings.update');

    Route::post('/admin/update-background', [AdminController::class, 'updateBackground'])->name('admin.updateBackground');

    Route::prefix('admin/events')->name('admin.events.')->group(function () {
        Route::get('/', [EventManagementController::class, 'index'])->name('index');
        Route::get('/create', [EventManagementController::class, 'create'])->name('create');
        Route::post('/', [EventManagementController::class, 'store'])->name('store');
        Route::get('/{event}/edit', [EventManagementController::class, 'edit'])->name('edit');
        Route::put('/{event}', [EventManagementController::class, 'update'])->name('update');
        Route::delete('/{event}', [EventManagementController::class, 'destroy'])->name('destroy');

        Route::post('/{event}/sections', [EventManagementController::class, 'storeSection'])->name('sections.store');
        Route::delete('/sections/{section}', [EventManagementController::class, 'destroySection'])->name('sections.destroy');

        Route::get('/notifications', [EventNotificationController::class, 'index'])->name('notifications');

        Route::get('/promotions', [EventPromotionController::class, 'index'])->name('promotions.index');
        Route::get('/promotions/create', [EventPromotionController::class, 'create'])->name('promotions.create');
        Route::post('/promotions', [EventPromotionController::class, 'store'])->name('promotions.store');
    });

    Route::patch('dishes/{dish}/toggle-visibility', [DishController::class, 'toggleVisibility'])->name('dishes.toggleVisibility');
    Route::patch('cocktails/{cocktail}/toggle-visibility', [CocktailController::class, 'toggleVisibility'])->name('cocktails.toggleVisibility');
    Route::patch('wines/{wine}/toggle-visibility', [WineController::class, 'toggleVisibility'])->name('wines.toggleVisibility');

    Route::post('/admin/dishes/reorder', [DishController::class, 'reorder'])->name('dishes.reorder');
    Route::post('/admin/cocktails/reorder', [CocktailController::class, 'reorder'])->name('cocktails.reorder');
    Route::post('/admin/wines/reorder', [WineController::class, 'reorder'])->name('wines.reorder');
    Route::post('/admin/cocktail-categories/reorder', [CocktailCategoryController::class, 'reorder'])->name('cocktail-categories.reorder');
    Route::post('/admin/wine-categories/reorder', [WineCategoryController::class, 'reorder'])->name('wine-categories.reorder');

    Route::resource('wine-categories', WineCategoryController::class);
    Route::resource('wines', WineController::class);
    Route::resource('wine-types', WineTypeController::class);
    Route::resource('regions', App\Http\Controllers\RegionController::class);
    Route::resource('food-pairings', FoodPairingController::class);
    Route::resource('grapes', App\Http\Controllers\GrapeController::class);

    Route::get('/admin/popups', [AdminController::class, 'indexPopups'])->name('admin.popups.index');
    Route::get('/admin/popups/create', [AdminController::class, 'createPopup'])->name('admin.popups.create');
    Route::post('/admin/popups', [AdminController::class, 'storePopup'])->name('admin.popups.store');
    Route::get('/admin/popups/{popup}/edit', [AdminController::class, 'editPopup'])->name('admin.popups.edit');
    Route::put('/admin.popups/{popup}', [AdminController::class, 'updatePopup'])->name('admin.popups.update');
    Route::delete('/admin.popups/{popup}', [AdminController::class, 'destroyPopup'])->name('admin.popups.destroy');
    Route::patch('/admin/popups/{popup}/toggle-visibility', [AdminController::class, 'toggleVisibility'])->name('admin.popups.toggleVisibility');
});

// Rutas protegidas para usuarios autenticados sin middleware
Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

// Rutas de autenticación
require __DIR__.'/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
