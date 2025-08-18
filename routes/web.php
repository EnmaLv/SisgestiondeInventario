<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\ProductoController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');


//Rutas para Maestros

//Categorias

Route::prefix('/admin/maestros/categorias')->group(function () {
    //Index 
    Route::get('/', [CategoriaController::class, 'index'])->name('admin.maestros.categorias.index');
    //Crear una nueva categoría
    Route::get('/create', [CategoriaController::class, 'create'])->name('admin.maestros.categorias.create');
    //Almacenar una nueva categoría
    Route::post('/store', [CategoriaController::class, 'store'])->name('admin.maestros.categorias.store');
    //Mostrar una categoría específica
    Route::get('/{categoria}', [CategoriaController::class, 'show'])->name('admin.maestros.categorias.show');
    //Editar una categoría específica
    Route::get('/{categoria}/edit', [CategoriaController::class, 'edit'])->name('admin.maestros.categorias.edit');
    //Actualizar una categoría específica
    Route::put('/{categoria}', [CategoriaController::class, 'update'])->name('admin.maestros.categorias.update');
    //Eliminar una categoria especifica
    Route::delete('/{categoria}', [CategoriaController::class, 'destroy'])->name('admin.maestros.categorias.destroy');

})->middleware('auth');



//Sucursales
Route::prefix('/admin/maestros/sucursales')->middleware('auth')->group(function () {
    Route::get('/', [SucursalController::class, 'index'])->name('admin.maestros.sucursales.index');
    Route::get('/create', [SucursalController::class, 'create'])->name('admin.maestros.sucursales.create');
    Route::post('/store', [SucursalController::class, 'store'])->name('admin.maestros.sucursales.store');
    Route::get('/{sucursal}', [SucursalController::class, 'show'])->name('admin.maestros.sucursales.show');
    Route::get('/{sucursal}/edit', [SucursalController::class, 'edit'])->name('admin.maestros.sucursales.edit');
    Route::put('/{sucursal}', [SucursalController::class, 'update'])->name('admin.maestros.sucursales.update');
    Route::delete('/{sucursal}', [SucursalController::class, 'destroy'])->name('admin.maestros.sucursales.destroy');
});

//Productos

Route::prefix('/admin/maestros/productos')->middleware('auth')->group(function () {
    Route::get('/', [ProductoController::class, 'index'])->name('admin.maestros.productos.index');
    Route::get('/create', [ProductoController::class, 'create'])->name('admin.maestros.productos.create');
    Route::post('/store', [ProductoController::class, 'store'])->name('admin.maestros.productos.store');
    Route::get('/{producto}', [ProductoController::class, 'show'])->name('admin.maestros.productos.show');
    Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->name('admin.maestros.productos.edit');
    Route::put('/{producto}', [ProductoController::class, 'update'])->name('admin.maestros.productos.update');
    Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('admin.maestros.productos.destroy');
});

// Route::get('/admin/maestros/productos', [App\Http\Controllers\ProductoController::class, 'index'])->name('admin.maestros.productos.index')->middleware('auth');

// Route::get('/admin/maestros/productos/create', [App\Http\Controllers\ProductoController::class, 'create'])->name('admin.maestros.productos.create')->middleware('auth');
// Route::post('/admin/maestros/productos/store', [App\Http\Controllers\ProductoController::class, 'store'])->name('admin.maestros.productos.store')->middleware('auth');
// Route::get('/admin/maestros/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'show'])->name('admin.maestros.productos.show')->middleware('auth');
// Route::get('/admin/maestros/productos/{producto}/edit', [App\Http\Controllers\ProductoController::class, 'edit'])->name('admin.maestros.productos.edit')->middleware('auth');
// Route::put('/admin/maestros/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'update'])->name('admin.maestros.productos.update')->middleware('auth');
// Route::delete('/admin/maestros/productos/{producto}', [App\Http\Controllers\ProductoController::class, 'destroy'])->name('admin.maestros.productos.destroy');

//Rutas para Movimientos
Route::get('/admin/movimientos/transacciones', [App\Http\Controllers\TransaccionController::class, 'index'])->name('admin.movimientos.transacciones.index')->middleware('auth');

//Rutas para Consultas
Route::get('/admin/consultas/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('admin.consultas.reportes.index')->middleware('auth');

//Rutas para Configuracion
Route::get('/admin/configuracion', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.configuracion.index')->middleware('auth');

Route::get('/admin/configuracion/indexar', [App\Http\Controllers\IndexarController::class, 'index'])->name('admin.configuracion.indexar.index')->middleware('auth');
