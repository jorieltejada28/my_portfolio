<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeControllr;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoriesController;

Route::middleware(['guess.auth'])->group(function () {
    Route::get('/', [HomeControllr::class, 'index'])->name('home');

    Route::get('/sign-in', [HomeControllr::class, 'sign_in'])->name('sign_in');
    Route::post('/sign-in', [HomeControllr::class, 'sign_in_post'])->name('sign_in_post');

    Route::get('/sign-up', [HomeControllr::class, 'sign_up'])->name('sign_up');
    Route::post('/sign-up', [HomeControllr::class, 'sign_up_post'])->name('sign_up_post');
});

Route::middleware(['auth', 'prevent.back'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    // Inventory Routes
    Route::get('/inventory', [AuthController::class, 'inventory_get'])->name('inventory_get');
    Route::get('/inventory-archive', [AuthController::class, 'inventory_archive'])->name('inventory_archive');
    Route::post('/inventory', [InventoriesController::class, 'inventory_post'])->name('inventory_post');
    Route::put('/inventory/update/{id}', [InventoriesController::class, 'inventory_update']);
    Route::put('/inventory/archive/{id}', [InventoriesController::class, 'inventory_archive']);
    Route::put('/inventory/restore/{id}', [InventoriesController::class, 'inventory_restore']);
    Route::delete('/inventory/delete/{id}', [InventoriesController::class, 'inventory_delete']);

    Route::post('/sign-out', [HomeControllr::class, 'sign_out'])->name('sign_out');
});
