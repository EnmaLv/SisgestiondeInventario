<?php

use App\Http\Controllers\salud\HorarioController;
use App\Http\Controllers\salud\AvanceSesionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\salud\EnfermedadController;
use App\Http\Controllers\salud\EstadoAnimoController;
use App\Http\Controllers\salud\GrupoHorarioController;

Route::get('enfermedades', [EnfermedadController::class, 'index'])->name('admin.enfermedades.index');
Route::get('enfermedades/create', [EnfermedadController::class, 'create'])->name('admin.enfermedades.create');
Route::post('enfermedades/store', [EnfermedadController::class, 'store'])->name('admin.enfermedades.store');
Route::get('enfermedades/{enfermedad}/edit', [EnfermedadController::class, 'edit'])->name('admin.enfermedades.edit');
Route::put('enfermedades/{enfermedad}', [EnfermedadController::class, 'update'])->name('admin.enfermedades.update');
Route::delete('enfermedades/{enfermedad}', [EnfermedadController::class, 'destroy'])->name('admin.enfermedades.destroy');


Route::get('/psicologia/maestros/estado_animos', [EstadoAnimoController::class, 'index'])->name('admin.psicologia.maestros.estado_animos.index');
Route::get('/psicologia/maestros/estado_animos/create', [EstadoAnimoController::class, 'create'])->name('admin.psicologia.maestros.estado_animos.create');
Route::post('/psicologia/maestros/estado_animos/store', [EstadoAnimoController::class, 'store'])->name('admin.psicologia.maestros.estado_animos.store');
Route::get('/psicologia/maestros/estado_animos/{estado_animo}/edit', [EstadoAnimoController::class, 'edit'])->name('admin.psicologia.maestros.estado_animos.edit');
Route::put('/psicologia/maestros/estado_animos/{estado_animo}', [EstadoAnimoController::class, 'update'])->name('admin.psicologia.maestros.estado_animos.update');
Route::delete('/psicologia/maestros/estado_animos/{estado_animo}', [EstadoAnimoController::class, 'destroy'])->name('admin.psicologia.maestros.estado_animos.destroy');


Route::get('/psicologia/maestros/avances_sesion', [AvanceSesionController::class, 'index'])->name('admin.psicologia.maestros.avances_sesion.index');
Route::get('/psicologia/maestros/avances_sesion/create', [AvanceSesionController::class, 'create'])->name('admin.psicologia.maestros.avances_sesion.create');
Route::post('/psicologia/maestros/avances_sesion/store', [AvanceSesionController::class, 'store'])->name('admin.psicologia.maestros.avances_sesion.store');
Route::get('/psicologia/maestros/avances_sesion/{avance}/edit', [AvanceSesionController::class, 'edit'])->name('admin.psicologia.maestros.avances_sesion.edit');
Route::put('/psicologia/maestros/avances_sesion/{avance}', [AvanceSesionController::class, 'update'])->name('admin.psicologia.maestros.avances_sesion.update');
Route::delete('/psicologia/maestros/avances_sesion/{avance}', [AvanceSesionController::class, 'destroy'])->name('admin.psicologia.maestros.avances_sesion.destroy');


Route::get('/psicologia/maestros/horarios', [HorarioController::class, 'index'])->name('admin.psicologia.maestros.horarios.index');
Route::get('/psicologia/maestros/horarios/create', [HorarioController::class, 'create'])->name('admin.psicologia.maestros.horarios.create');
Route::post('/psicologia/maestros/horarios/store', [HorarioController::class, 'store'])->name('admin.psicologia.maestros.horarios.store');
Route::get('/psicologia/maestros/horarios/{horario}/edit', [HorarioController::class, 'edit'])->name('admin.psicologia.maestros.horarios.edit');
Route::put('/psicologia/maestros/horarios/{horario}', [HorarioController::class, 'update'])->name('admin.psicologia.maestros.horarios.update');
Route::delete('/psicologia/maestros/horarios/{horario}', [HorarioController::class, 'destroy'])->name('admin.psicologia.maestros.horarios.destroy');
Route::get('/psicologia/maestros/horarios/exportar-pdf', [HorarioController::class, 'exportarPdf'])->name('admin.psicologia.maestros.horarios.exportarPdf');
Route::patch('/psicologia/maestros/horarios/{horario}/activate', [HorarioController::class, 'activate'])->name('admin.psicologia.maestros.horarios.activate');
Route::patch('/psicologia/maestros/horarios/{horario}/deactivate', [HorarioController::class, 'deactivate'])->name('admin.psicologia.maestros.horarios.deactivate');

Route::get('/psicologia/maestros/grupos_horarios', [GrupoHorarioController::class, 'index'])->name('admin.psicologia.maestros.grupos_horarios.index');
Route::get('/psicologia/maestros/grupos_horarios/create', [GrupoHorarioController::class, 'create'])->name('admin.psicologia.maestros.grupos_horarios.create');
Route::post('/psicologia/maestros/grupos_horarios/store', [GrupoHorarioController::class, 'store'])->name('admin.psicologia.maestros.grupos_horarios.store');
Route::get('/psicologia/maestros/grupos_horarios/{grupo_horario}/edit', [GrupoHorarioController::class, 'edit'])->name('admin.psicologia.maestros.grupos_horarios.edit');
Route::put('/psicologia/maestros/grupos_horarios/{grupo_horario}', [GrupoHorarioController::class, 'update'])->name('admin.psicologia.maestros.grupos_horarios.update');
Route::delete('/psicologia/maestros/grupos_horarios/{grupo_horario}', [GrupoHorarioController::class, 'destroy'])->name('admin.psicologia.maestros.grupos_horarios.destroy');
Route::post('/psicologia/maestros/grupos_horarios/store-from-horarios', [GrupoHorarioController::class, 'storeFromHorarios'])->name('admin.psicologia.maestros.grupos_horarios.store_from_horarios');
Route::patch('/psicologia/maestros/grupos_horarios/{id}/activate', [GrupoHorarioController::class, 'activate'])->name('admin.psicologia.maestros.grupos_horarios.activate');
Route::patch('/psicologia/maestros/grupos_horarios/{id}/deactivate', [GrupoHorarioController::class, 'deactivate'])->name('admin.psicologia.maestros.grupos_horarios.deactivate');
