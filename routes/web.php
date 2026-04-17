<?php

use App\Http\Controllers\Api\CharacterController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('api')->group(function () {
    Route::get('/character', [CharacterController::class, 'getActive']);
    Route::post('/character/heartbeat', [CharacterController::class, 'heartbeat']);
});
