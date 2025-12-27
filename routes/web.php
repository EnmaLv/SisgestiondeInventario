<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SucursalController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\RegistroDiarioController;
use App\Http\Controllers\DetalleRegistroDiarioController;


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
    Route::put('/{categoria}/activar', [CategoriaController::class, 'activar'])->name('admin.maestros.productos.activar');
})->middleware('auth');



//Sucursales
Route::prefix('/admin/maestros/sucursales')->middleware('auth')->group(function () {
    Route::get('/', [SucursalController::class, 'index'])->name('admin.maestros.sucursales.index');
    Route::get('/create', [SucursalController::class, 'create'])->name('admin.maestros.sucursales.create');
    Route::post('/store', [SucursalController::class, 'store'])->name('admin.maestros.sucursales.store');
    Route::get('/{sucursal}', [SucursalController::class, 'show'])->name('admin.maestros.sucursales.show');
    Route::get('/{sucursal}/edit', [SucursalController::class, 'edit'])->name('admin.maestros.sucursales.edit');
    Route::put('/{sucursal}', [SucursalController::class, 'update'])->name('admin.maestros.sucursales.update');
    Route::delete('/{id}', [SucursalController::class, 'destroy'])->name('admin.maestros.sucursales.destroy');
    Route::put('/{sucursal}/activar', [SucursalController::class, 'activar'])->name('admin.maestros.sucursales.activar');
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
    Route::put('/{producto}/activar', [ProductoController::class, 'activar'])->name('admin.maestros.productos.activar');
});

//Registro Diario
Route::prefix('/admin/movimientos/registro_diario')->middleware('auth')->group(function () {
    Route::get('/', [RegistroDiarioController::class, 'index'])->name('admin.movimientos.registro_diario.index');
    Route::get('/registro/{id}', [RegistroDiarioController::class, 'show'])->name('admin.movimientos.registro_diario.show');
});


//Proveedores
Route::get('/admin/maestros/proveedores', [App\Http\Controllers\ProveedorController::class, 'index'])->name('admin.maestros.proveedores.index')->middleware('auth');
Route::get('/admin/maestros/proveedores/create', [App\Http\Controllers\ProveedorController::class, 'create'])->name('admin.maestros.proveedores.create')->middleware('auth');
Route::post('/admin/maestros/proveedores/store', [App\Http\Controllers\ProveedorController::class, 'store'])->name('admin.maestros.proveedores.store')->middleware('auth');
Route::get('/admin/maestros/proveedores/{proveedor}', [App\Http\Controllers\ProveedorController::class, 'show'])->name('admin.maestros.proveedores.show')->middleware('auth');
Route::get('/admin/maestros/proveedores/{proveedor}/edit', [App\Http\Controllers\ProveedorController::class, 'edit'])->name('admin.maestros.proveedores.edit')->middleware('auth');
Route::put('/admin/maestros/proveedores/{proveedor}', [App\Http\Controllers\ProveedorController::class, 'update'])->name('admin.maestros.proveedores.update')->middleware('auth');
Route::delete('/admin/maestros/proveedores/{proveedor}', [App\Http\Controllers\ProveedorController::class, 'destroy'])->name('admin.maestros.proveedores.destroy')->middleware('auth');
Route::put('/admin/maestros/proveedores/{proveedor}/activar', [App\Http\Controllers\ProveedorController::class, 'activar'])->name('admin.maestros.proveedores.activar');
//Recetas
Route::get('/admin/maestros/recetas', [App\Http\Controllers\RecetaController::class, 'index'])->name('admin.maestros.recetas.index')->middleware('auth');
Route::get('/admin/maestros/recetas/create', [App\Http\Controllers\RecetaController::class, 'create'])->name('admin.maestros.recetas.create')->middleware('auth');
Route::post('/admin/maestros/recetas/store', [App\Http\Controllers\RecetaController::class, 'store'])->name('admin.maestros.recetas.store')->middleware('auth');
Route::get('/admin/maestros/recetas/{receta}/edit', [App\Http\Controllers\RecetaController::class, 'edit'])->name('admin.maestros.recetas.edit')->middleware('auth');
Route::put('/admin/maestros/recetas/{receta}', [App\Http\Controllers\RecetaController::class, 'update'])->name('admin.maestros.recetas.update')->middleware('auth');
Route::delete('/admin/maestros/recetas/{receta}', [App\Http\Controllers\RecetaController::class, 'destroy'])->name('admin.maestros.recetas.destroy')->middleware('auth');
Route::put('/admin/maestros/recetas/{receta}/activar', [App\Http\Controllers\RecetaController::class, 'activar'])->name('admin.maestros.recetas.activar');

//Receta Ingredientes
Route::get('/admin/maestros/receta_ingredientes', [App\Http\Controllers\RecetaIngredienteController::class, 'index'])->name('admin.maestros.receta_ingredientes.index')->middleware('auth');
Route::get('/admin/maestros/receta_ingredientes/create', [App\Http\Controllers\RecetaIngredienteController::class, 'create'])->name('admin.maestros.receta_ingredientes.create')->middleware('auth');
Route::post('/admin/maestros/receta_ingredientes/store', [App\Http\Controllers\RecetaIngredienteController::class, 'store'])->name('admin.maestros.receta_ingredientes.store')->middleware('auth');
Route::get('/admin/maestros/receta_ingredientes/{id}/edit', [App\Http\Controllers\RecetaIngredienteController::class, 'edit'])->name('admin.maestros.receta_ingredientes.edit')->middleware('auth');
Route::put('admin/maestros/receta_ingredientes/receta/{id}', [App\Http\Controllers\RecetaIngredienteController::class, 'update'])->name('admin.maestros.receta_ingredientes.update');
Route::delete('/admin/maestros/receta_ingredientes/{id}', [App\Http\Controllers\RecetaIngredienteController::class, 'destroy'])->name('admin.maestros.receta_ingredientes.destroy')->middleware('auth');
Route::put('admin/maestros/receta_ingredientes/{id}/activar', [App\Http\Controllers\RecetaIngredienteController::class, 'activar'])->name('admin.maestros.receta_ingredientes.activar');

//Rutas para Movimientos

//Compras
Route::get('/admin/movimientos/compras', [App\Http\Controllers\CompraController::class, 'index'])->name('admin.movimientos.compras.index')->middleware('auth');
Route::get('/admin/movimientos/compras/create', [App\Http\Controllers\CompraController::class, 'create'])->name('admin.movimientos.compras.create')->middleware('auth');
Route::post('/admin/movimientos/compras/store', [App\Http\Controllers\CompraController::class, 'store'])->name('admin.movimientos.compras.store')->middleware('auth');
Route::get('/admin/movimientos/compras/{id}', [App\Http\Controllers\CompraController::class, 'show'])->name('admin.movimientos.compras.show')->middleware('auth');
Route::get('/admin/movimientos/compras/{id}/edit', [App\Http\Controllers\CompraController::class, 'edit'])->name('admin.movimientos.compras.edit')->middleware('auth');
Route::get('/admin/movimientos/compras/{compra}/enviar-correo', [App\Http\Controllers\CompraController::class, 'enviarCorreo'])->name('admin.movimientos.compras.enviarCorreo')->middleware('auth');
Route::post('/admin/movimientos/compras/{compra}/finalizar-compra', [App\Http\Controllers\CompraController::class, 'finalizarCompra'])->name('admin.movimientos.compras.finalizarCompra')->middleware('auth');
Route::delete('/admin/movimientos/compras/{id}', [App\Http\Controllers\CompraController::class, 'destroy'])->name('admin.movimientos.compras.destroy')->middleware('auth');
Route::get('/admin/movimientos/compras/e/export-pdf', [App\Http\Controllers\CompraController::class, 'exportPdf'])->name('admin.movimientos.compras.export_pdf')->middleware('auth');
Route::post('admin/movimientos/compras/{compra}/cancelar',[App\Http\Controllers\CompraController::class, 'cancelar'])->name('admin.movimientos.compras.cancelar');

//Lotes
Route::get('/admin/movimientos/lotes', [App\Http\Controllers\LoteController::class, 'index'])->name('admin.movimientos.lotes.index')->middleware('auth');
Route::get('/admin/movimientos/lotes/create', [App\Http\Controllers\LoteController::class, 'create'])->name('admin.movimientos.lotes.create')->middleware('auth');
Route::post('/admin/movimientos/lotes/store', [App\Http\Controllers\LoteController::class, 'store'])->name('admin.movimientos.lotes.store')->middleware('auth');
Route::get('/admin/movimientos/lotes/{id}', [App\Http\Controllers\LoteController::class, 'show'])->name('admin.movimientos.lotes.show')->middleware('auth');
Route::get('/admin/movimientos/lotes/{id}/edit', [App\Http\Controllers\LoteController::class, 'edit'])->name('admin.movimientos.lotes.edit')->middleware('auth');
Route::put('/admin/movimientos/lotes/{id}', [App\Http\Controllers\LoteController::class, 'update'])->name('admin.movimientos.lotes.update')->middleware('auth');
Route::delete('/admin/movimientos/lotes/{id}', [App\Http\Controllers\LoteController::class, 'destroy'])->name('admin.movimientos.lotes.destroy')->middleware('auth');
Route::post('admin/movimientos/lotes/mermar-vencidos', [App\Http\Controllers\LoteController::class, 'mermarVencidos'])->name('admin.movimientos.lotes.mermar');


// Sucursales por Lotes
Route::get('/admin/movimientos/sucursales_lotes', [App\Http\Controllers\InventarioSucursalLoteController::class, 'index'])->name('admin.movimientos.sucursales_lotes')->middleware('auth');
Route::get('/admin/movimientos/sucursales_lotes/show/{id}', [App\Http\Controllers\InventarioSucursalLoteController::class, 'show'])->name('admin.movimientos.sucursales_lotes.show')->middleware('auth');

// Historial de Movimientos
Route::get('/admin/movimientos/historial_movimientos', [App\Http\Controllers\MovimientoInventarioController::class, 'index'])->name('admin.movimientos.historial_movimientos.index')->middleware('auth');

// Registro diario
Route::prefix('/admin/movimientos/registro_diario')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\RegistroDiarioController::class, 'index'])->name('admin.movimientos.registro_diario.index');
    Route::get('/export-pdf', [App\Http\Controllers\RegistroDiarioController::class, 'exportPdf'])->name('admin.movimientos.registro_diario.export_pdf');
    Route::get('/export-excel', [App\Http\Controllers\RegistroDiarioController::class, 'exportExcel'])->name('admin.movimientos.registro_diario.export_excel');
});

Route::prefix('admin/movimientos/registro_comida')->middleware('auth')->group(function () {
    Route::get('/',[DetalleRegistroDiarioController::class, 'index'])->name('admin.movimientos.registro_comida.index');
});

//Rutas para Consultas
Route::get('/admin/consultas/reportes', [App\Http\Controllers\ReporteController::class, 'index'])->name('admin.consultas.reportes.index')->middleware('auth');

//Rutas para Configuracion
Route::get('/admin/configuracion', [App\Http\Controllers\AdminController::class, 'index'])->name('admin.configuracion.index')->middleware('auth');

Route::get('/admin/configuracion/indexar', [App\Http\Controllers\IndexarController::class, 'index'])->name('admin.configuracion.indexar.index')->middleware('auth');

Route::post('admin/maestros/productos/actualizar-tasa',[ProductoController::class, 'actualizarTasaDolar'])->name('productos.actualizar.tasa');
