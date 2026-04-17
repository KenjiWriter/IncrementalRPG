<?php

use App\Http\Controllers\Api\CharacterController;
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
});
