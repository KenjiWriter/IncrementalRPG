<?php

use App\Http\Controllers\Api\CharacterController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // Protected Game Engine Routes
    Route::get('/active-character', [CharacterController::class, 'getActive']);
    Route::post('/character/heartbeat', [CharacterController::class, 'heartbeat']);
    Route::post('/character/location', [CharacterController::class, 'changeLocation']);

    // Inventory Routes
    Route::get('/inventory', [InventoryController::class, 'index']);
    Route::post('/inventory/equip', [InventoryController::class, 'equip']);
    Route::post('/inventory/unequip', [InventoryController::class, 'unequip']);

    Route::get('/locations', [LocationController::class, 'index']);
});
