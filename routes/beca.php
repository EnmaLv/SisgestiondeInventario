<?php

use Illuminate\Support\Facades\Route;
use App\Models\Becas\Beneficio;
use App\Http\Resources\BeneficioResource;

Route::prefix('beca')->group(function () {
    // Rutas de beca
    Route::get('beneficios', function () {
        return BeneficioResource::collection(Beneficio::paginate(10));
    });
});
