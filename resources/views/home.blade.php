@extends('adminlte::page')

@section('content_header')
    <h1>Panel de Control</h1>
@stop

@section('content')
    <p>Bienvenido {{ auth()->user()->name }}.</p>
    <hr>

    <div class="row">
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('/admin/maestros/sucursales') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('/img/edificio.gif') }}" alt="xd">
                    </span>
                </a>
                <div class="info-box-content">
                    <a href="{{ url('/admin/maestros/sucursales') }}">
                        <span class="info-box-text" style="text-decoration: none; color: #000;"><b>Sucursales</b></span></a>
                    <span class="info-box-number">
                        {{ $total_sucursales }} Sucursales
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('/admin/maestros/categorias') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('/img/carpetas.gif') }}" alt="xd">
                    </span>
                </a>
                <div class="info-box-content">
                    <a href="{{ url('/admin/maestros/categorias') }}">
                        <span class="info-box-text" style="text-decoration: none; color: #000;"><b>Categorias</b></span></a>
                    <span class="info-box-number">
                        {{ $total_categorias }} categorias
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('/admin/maestros/productos') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('/img/paquete.gif') }}" alt="xd">
                    </span>
                </a>
                <div class="info-box-content">
                    <a href="{{ url('/admin/maestros/productos') }}">
                        <span class="info-box-text" style="text-decoration: none; color: #000;"><b>Productos</b></span></a>
                    <span class="info-box-number">
                        {{ $total_productos }} productos
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('/admin/maestros/proveedores') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('/img/lista-de-verificacion.gif') }}" alt="xd">
                    </span>
                </a>
                <div class="info-box-content">
                    <a href="{{ url('/admin/maestros/proveedores') }}">
                        <span class="info-box-text"
                            style="text-decoration: none; color: #000;"><b>Proveedores</b></span></a>
                    <span class="info-box-number">
                        {{ $total_proveedores }} proveedores
                    </span>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
