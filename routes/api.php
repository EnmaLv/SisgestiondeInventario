<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BusMarcaApiController;
use App\Http\Controllers\Api\BusModeloApiController;
use App\Http\Controllers\Api\BusTipoCombustibleApiController;

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

        Route::get('modelos',               [BusModeloApiController::class, 'index']);
        Route::post('modelos',               [BusModeloApiController::class, 'store']);
        Route::get('modelos/{modelo}',      [BusModeloApiController::class, 'show']);
        Route::put('modelos/{modelo}',      [BusModeloApiController::class, 'update']);
        Route::patch('modelos/{modelo}/toggle', [BusModeloApiController::class, 'toggle']);
        Route::delete('modelos/{modelo}',      [BusModeloApiController::class, 'destroy']);

        Route::get('combustibles',                  [BusTipoCombustibleApiController::class, 'index']);
        Route::post('combustibles',                 [BusTipoCombustibleApiController::class, 'store']);
        Route::get('combustibles/{combustible}',    [BusTipoCombustibleApiController::class, 'show']);
        Route::put('combustibles/{combustible}',    [BusTipoCombustibleApiController::class, 'update']);
        Route::patch('combustibles/{combustible}/toggle', [BusTipoCombustibleApiController::class, 'toggle']);
        Route::delete('combustibles/{combustible}', [BusTipoCombustibleApiController::class, 'destroy']);
    });
});
