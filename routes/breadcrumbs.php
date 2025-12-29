<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as Trail;

// Inicio (Home)
Breadcrumbs::for('home', function (Trail $trail) {
    $trail->push('Inicio', route('home'));
});

// Maestros > Categorías
Breadcrumbs::for('admin.maestros.categorias.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Categorías', route('admin.maestros.categorias.index'));
});

Breadcrumbs::for('admin.maestros.categorias.create', function (Trail $trail) {
    $trail->parent('admin.maestros.categorias.index');
    $trail->push('Crear', route('admin.maestros.categorias.create'));
});

Breadcrumbs::for('admin.maestros.categorias.show', function (Trail $trail, $categoria) {
    $trail->parent('admin.maestros.categorias.index');
    $trail->push('Ver Mas', route('admin.maestros.categorias.show', $categoria));
});

Breadcrumbs::for('admin.maestros.categorias.edit', function (Trail $trail, $categoria) {
    $trail->parent('admin.maestros.categorias.index');
    $trail->push('Editar', route('admin.maestros.categorias.edit', $categoria));
});

// Maestros > Sedes
Breadcrumbs::for('admin.maestros.sucursales.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Sedes', route('admin.maestros.sucursales.index'));
});

Breadcrumbs::for('admin.maestros.sucursales.create', function (Trail $trail) {
    $trail->parent('admin.maestros.sucursales.index');
    $trail->push('Crear', route('admin.maestros.sucursales.create'));
});

Breadcrumbs::for('admin.maestros.sucursales.show', function (Trail $trail, $sucursal) {
    $trail->parent('admin.maestros.sucursales.index');
    $trail->push('Ver Mas', route('admin.maestros.sucursales.show', $sucursal));
});

Breadcrumbs::for('admin.maestros.sucursales.edit', function (Trail $trail, $sucursal) {
    $trail->parent('admin.maestros.sucursales.index');
    $trail->push('Editar', route('admin.maestros.sucursales.edit', $sucursal));
});

// Maestros > Productos
Breadcrumbs::for('admin.maestros.productos.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Productos', route('admin.maestros.productos.index'));
});


Breadcrumbs::for('admin.maestros.productos.create', function (Trail $trail) {
    $trail->parent('admin.maestros.productos.index');
    $trail->push('Crear', route('admin.maestros.productos.create'));
});

Breadcrumbs::for('admin.maestros.productos.show', function (Trail $trail, $producto) {
    $trail->parent('admin.maestros.productos.index');
    $trail->push('Ver Mas', route('admin.maestros.productos.show', $producto));
});

Breadcrumbs::for('admin.maestros.productos.edit', function (Trail $trail, $producto) {
    $trail->parent('admin.maestros.productos.index');
    $trail->push('Editar', route('admin.maestros.productos.edit', $producto));
});

// Maestros > Proveedores
Breadcrumbs::for('admin.maestros.proveedores.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Proveedores', route('admin.maestros.proveedores.index'));
});

Breadcrumbs::for('admin.maestros.proveedores.create', function (Trail $trail) {
    $trail->parent('admin.maestros.proveedores.index');
    $trail->push('Crear', route('admin.maestros.proveedores.create'));
});

Breadcrumbs::for('admin.maestros.proveedores.show', function (Trail $trail, $proveedor) {
    $trail->parent('admin.maestros.proveedores.index');
    $trail->push('Ver Mas', route('admin.maestros.proveedores.show', $proveedor));
});

Breadcrumbs::for('admin.maestros.proveedores.edit', function (Trail $trail, $proveedor) {
    $trail->parent('admin.maestros.proveedores.index');
    $trail->push('Editar', route('admin.maestros.proveedores.edit', $proveedor));
});

// Maestros > Recetas
Breadcrumbs::for('admin.maestros.recetas.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Recetas', route('admin.maestros.recetas.index'));
});

Breadcrumbs::for('admin.maestros.recetas.create', function (Trail $trail) {
    $trail->parent('admin.maestros.recetas.index');
    $trail->push('Crear', route('admin.maestros.recetas.create'));
});

Breadcrumbs::for('admin.maestros.recetas.edit', function (Trail $trail, $receta) {
    $trail->parent('admin.maestros.recetas.index');
    $trail->push('Editar', route('admin.maestros.recetas.edit', $receta));
});

// Mestros > Receta Ingredientes
Breadcrumbs::for('admin.maestros.receta_ingredientes.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Ingredientes de Recetas', route('admin.maestros.receta_ingredientes.index'));
});

Breadcrumbs::for('admin.maestros.receta_ingredientes.create', function (Trail $trail) {
    $trail->parent('admin.maestros.receta_ingredientes.index');
    $trail->push('Crear', route('admin.maestros.receta_ingredientes.create'));
});

Breadcrumbs::for('admin.maestros.receta_ingredientes.edit', function (Trail $trail, $ingrediente) {
    $trail->parent('admin.maestros.receta_ingredientes.index');
    $trail->push('Editar', route('admin.maestros.receta_ingredientes.edit', $ingrediente));
});

// Movimientos > Compras
Breadcrumbs::for('admin.movimientos.compras.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Compras', route('admin.movimientos.compras.index'));
});

Breadcrumbs::for('admin.movimientos.compras.create', function (Trail $trail) {
    $trail->parent('admin.movimientos.compras.index');
    $trail->push('Crear', route('admin.movimientos.compras.create'));
});

Breadcrumbs::for('admin.movimientos.compras.edit', function (Trail $trail, $id) {
    $trail->parent('admin.movimientos.compras.index');
    $trail->push('Editar', route('admin.movimientos.compras.edit', $id));
});

Breadcrumbs::for('admin.movimientos.compras.show', function (Trail $trail, $id) {
    $trail->parent('admin.movimientos.compras.index');
    $trail->push('Ver Mas', route('admin.movimientos.compras.show', $id));
});

Breadcrumbs::for('admin.movimientos.compras.enviarCorreo', function (Trail $trail, $id) {
    $trail->parent('admin.movimientos.compras.index');
    $trail->push('Enviar Correo', route('admin.movimientos.compras.enviarCorreo', $id));
});

// Movimientos > Lotes
Breadcrumbs::for('admin.movimientos.lotes.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Lotes', route('admin.movimientos.lotes.index'));
});

Breadcrumbs::for('admin.movimientos.lotes.create', function (Trail $trail) {
    $trail->parent('admin.movimientos.lotes.index');
    $trail->push('Crear', route('admin.movimientos.lotes.create'));
});

Breadcrumbs::for('admin.movimientos.lotes.edit', function (Trail $trail, $id) {
    $trail->parent('admin.movimientos.lotes.index');
    $trail->push('Editar', route('admin.movimientos.lotes.edit', $id));
});

Breadcrumbs::for('admin.movimientos.lotes.show', function (Trail $trail, $id) {
    $trail->parent('admin.movimientos.lotes.index');
    $trail->push('Ver Mas', route('admin.movimientos.lotes.show', $id));
});

Breadcrumbs::for('admin.movimientos.registro_diario.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Registro Diario', route('admin.movimientos.registro_diario.index'));
});

Breadcrumbs::for('admin.movimientos.registro_diario.show', function (Trail $trail, $id) {
    $trail->parent('admin.movimientos.registro_diario.index');
    $trail->push('Ver Registro', route('admin.movimientos.registro_diario.show', $id));
});

// Movimientos > Sucursales por Lotes
Breadcrumbs::for('admin.movimientos.sucursales_lotes', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Lotes por Sedes', route('admin.movimientos.sucursales_lotes'));
});

Breadcrumbs::for('admin.movimientos.sucursales_lotes.show', function (Trail $trail, $id) {
    $trail->parent('admin.movimientos.sucursales_lotes');
    $trail->push('Ver Mas', route('admin.movimientos.sucursales_lotes.show', $id));
});

// Movimientos > Historial de Movimientos
Breadcrumbs::for('admin.movimientos.historial_movimientos.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Historial de Movimientos', route('admin.movimientos.historial_movimientos.index'));
});

Breadcrumbs::for('admin.movimientos.registro_comida.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Registro Comida', route('admin.movimientos.registro_comida.index'));
});

Breadcrumbs::for('admin.maestros.pnf.index', function(Trail $trail){
    $trail->parent('home');
    $trail->push('Pnf', route('admin.maestros.pnf.index')); 
});

Breadcrumbs::for('admin.maestros.pnf.create', function(Trail $trail){
    $trail->parent('admin.maestros.pnf.index');
    $trail->push('Crear', route('admin.maestros.pnf.create')); 
});

Breadcrumbs::for('admin.maestros.pnf.edit', function(Trail $trail, $id){
    $trail->parent('admin.maestros.pnf.index');
    $trail->push('Editar', route('admin.maestros.pnf.edit', $id)); 
});

// Estado
Breadcrumbs::for('admin.estado.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Estado', route('admin.estado.index'));
});

// Municipio
Breadcrumbs::for('admin.municipio.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Municipio', route('admin.municipio.index'));
});

// Localidad
Breadcrumbs::for('admin.localidad.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Localidad', route('admin.localidad.index'));
});

// Consultas > Reportes
Breadcrumbs::for('admin.consultas.reportes.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Reportes', route('admin.consultas.reportes.index'));
});

// Configuración
Breadcrumbs::for('admin.configuracion.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Configuración', route('admin.configuracion.index'));
});

// Configuración > Indexar
Breadcrumbs::for('admin.configuracion.indexar.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Indexar', route('admin.configuracion.indexar.index'));
});
