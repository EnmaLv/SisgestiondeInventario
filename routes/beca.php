<?php

use Illuminate\Support\Facades\Route;
use App\Models\Becas\Beneficio;
use App\Http\Resources\BeneficioResource;
use App\Services\becas\JornadaBecasServices;
use App\Http\Resources\becas\JornadaResource;

Route::middleware('auth')->prefix('/admin')->group(function () {

    Route::prefix('/becas')->group(function () {
    // Rutas de beca
        Route::get('/beneficios', function () {
            return BeneficioResource::collection(Beneficio::paginate(10));
        });

        //Rutas de jornada
        Route::prefix('/jornada')->group(function () {
            Route::get('/', [\App\Http\Controllers\beca\JornadaBecaController::class, 'index'])->name('admin.becas.jornada.index');
            Route::get('/create', [\App\Http\Controllers\beca\JornadaBecaController::class, 'create'])->name('admin.becas.jornada.create');
            Route::post('/store', [\App\Http\Controllers\beca\JornadaBecaController::class, 'store'])->name('admin.becas.jornada.store');
            Route::get('/{jornada}/edit', [\App\Http\Controllers\beca\JornadaBecaController::class, 'edit'])->name('admin.becas.jornada.edit');
            Route::put('/{jornada}', [\App\Http\Controllers\beca\JornadaBecaController::class, 'update'])->name('admin.becas.jornada.update');
            Route::delete('/{jornada}', [\App\Http\Controllers\beca\JornadaBecaController::class, 'destroy'])->name('admin.becas.jornada.destroy');
            Route::put('/{jornada}/activar', [\App\Http\Controllers\beca\JornadaBecaController::class, 'activar'])->name('admin.becas.jornada.activar');
        });
    });
});
