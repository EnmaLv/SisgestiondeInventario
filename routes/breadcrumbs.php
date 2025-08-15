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

// Maestros > Sucursales
Breadcrumbs::for('admin.maestros.sucursales.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Sucursales', route('admin.maestros.sucursales.index'));
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

// Movimientos > Transacciones
Breadcrumbs::for('admin.movimientos.transacciones.index', function (Trail $trail) {
    $trail->parent('home');
    $trail->push('Transacciones', route('admin.movimientos.transacciones.index'));
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
