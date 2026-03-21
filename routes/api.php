<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BusMarcaApiController;

Route::get('/ping', fn() => response()->json(['ok' => true]));

Route::prefix('auth')->group(function () {
    Route::post('/login',  [AuthApiController::class, 'login']);
    Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me',      [AuthApiController::class, 'me']);
    Route::get('/modulos', [AuthApiController::class, 'modulos']);

    Route::prefix('/')->group(function () {
        Route::get('marcas',               [BusMarcaApiController::class, 'index']);
        Route::post('marcas',               [BusMarcaApiController::class, 'store']);
        Route::get('marcas/{marca}',       [BusMarcaApiController::class, 'show']);
        Route::put('marcas/{marca}',       [BusMarcaApiController::class, 'update']);
        Route::patch('marcas/{marca}/toggle', [BusMarcaApiController::class, 'toggle']);
        Route::delete('marcas/{marca}',       [BusMarcaApiController::class, 'destroy']);
    });
});
