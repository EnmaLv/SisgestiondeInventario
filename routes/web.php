<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');


//Rutas para Maestros

//Categorias
Route::get('/admin/maestros/categorias', [App\Http\Controllers\CategoriaController::class, 'index'])->name('admin.maestros.categorias.index')->middleware('auth');
Route::get('/admin/maestros/categorias/create', [App\Http\Controllers\CategoriaController::class, 'create'])->name('admin.maestros.categorias.create')->middleware('auth');
Route::post('/admin/maestros/categorias/store', [App\Http\Controllers\CategoriaController::class, 'store'])->name('admin.maestros.categorias.store')->middleware('auth');
Route::get('/admin/maestros/categorias/{categoria}', [App\Http\Controllers\CategoriaController::class, 'show'])->name('admin.maestros.categorias.show')->middleware('auth');
Route::get('/admin/maestros/categorias/{categoria}/edit', [App\Http\Controllers\CategoriaController::class, 'edit'])->name('admin.maestros.categorias.edit')->middleware('auth');
Route::put('/admin/maestros/categorias/{categoria}', [App\Http\Controllers\CategoriaController::class, 'update'])->name('admin.maestros.categorias.update')->middleware('auth');
Route::delete('/admin/maestros/categorias/{categoria}', [App\Http\Controllers\CategoriaController::class, 'destroy'])->name('admin.maestros.categorias.destroy');

//Sucursales
Route::get('/admin/maestros/sucursales', [App\Http\Controllers\SucursalController::class, 'index'])->name('admin.maestros.sucursales.index')->middleware('auth');
Route::get('/admin/maestros/sucursales/create', [App\Http\Controllers\SucursalController::class, 'create'])->name('admin.maestros.sucursales.create')->middleware('auth');
Route::post('/admin/maestros/sucursales/store', [App\Http\Controllers\SucursalController::class, 'store'])->name('admin.maestros.sucursales.store')->middleware('auth');
Route::get('/admin/maestros/sucursales/{sucursal}', [App\Http\Controllers\SucursalController::class, 'show'])->name('admin.maestros.sucursales.show')->middleware('auth');
Route::get('/admin/maestros/sucursales/{sucursal}/edit', [App\Http\Controllers\SucursalController::class, 'edit'])->name('admin.maestros.sucursales.edit')->middleware('auth');
Route::put('/admin/maestros/sucursales/{sucursal}', [App\Http\Controllers\SucursalController::class, 'update'])->name('admin.maestros.sucursales.update')->middleware('auth');
Route::delete('/admin/maestros/sucursales/{sucursal}', [App\Http\Controllers\SucursalController::class, 'destroy'])->name('admin.maestros.sucursales.destroy');

//Productos
Route::get('/admin/maestros/productos', [App\Http\Controllers\ProductoController::class, 'index'])->name('admin.maestros.productos.index')->middleware('auth');
Route::get('/admin/maestros/productos/create', [App\Http\Controllers\ProductoController::class, 'create'])->name('admin.maestros.productos.create')->middleware('auth');
Route::post('/admin/maestros/productos/store', [App\Http\Controllers\ProductoController::class, 'store'])->name('admin.maestros.productos.store')->middleware('auth');
Route::get('/admin/maestros/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'show'])->name('admin.maestros.productos.show')->middleware('auth');
Route::get('/admin/maestros/productos/{producto}/edit', [App\Http\Controllers\ProductoController::class, 'edit'])->name('admin.maestros.productos.edit')->middleware('auth');
Route::put('/admin/maestros/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'update'])->name('admin.maestros.productos.update')->middleware('auth');
Route::delete('/admin/maestros/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'destroy'])->name('admin.maestros.productos.destroy');

//Rutas para Movimientos
Route::get('/admin/movimientos/transacciones', [App\Http\Controllers\TransaccionController::class, 'index'])->name('admin.movimientos.transacciones.index')->middleware('auth');

//Rutas para Consultas
Route::get('/admin/consultas/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('admin.consultas.reportes.index')->middleware('auth');

//Rutas para Configuracion
Route::get('/admin/configuracion', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.configuracion.index')->middleware('auth');

Route::get('/admin/configuracion/indexar', [App\Http\Controllers\IndexarController::class, 'index'])->name('admin.configuracion.indexar.index')->middleware('auth');
