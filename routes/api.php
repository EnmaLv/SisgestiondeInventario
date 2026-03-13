<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;

/*
|--------------------------------------------------------------------------
| API Routes - Aplicación Móvil Flutter
|--------------------------------------------------------------------------
*/

// Health check
Route::get('/ping', fn() => response()->json(['ok' => true]));

// ── Autenticación ─────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('/login',  [AuthApiController::class, 'login']);
    Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');
});

// ── Rutas protegidas ──────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Usuario autenticado
    Route::get('/me', [AuthApiController::class, 'me']);

    // Módulos permitidos del usuario
    Route::get('/modulos', [AuthApiController::class, 'modulos']);
});