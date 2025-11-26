@extends('adminlte::page')


@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">

        <!-- Texto principal -->
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                Pagina de Inicio
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>

            <div
                style="
                width:46px;
                height:46px;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 4px 12px rgba(15,23,42,0.08);
            ">
                <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>

    </div>
@stop

@section('content')

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
                        <img src="{{ url('/img/camion.gif') }}" alt="xd">
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

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('/admin/movimientos/compras') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('/img/lista-de-verificacion.gif') }}" alt="xd">
                    </span>
                </a>
                <div class="info-box-content">
                    <a href="{{ url('/admin/movimientos/compras') }}">
                        <span class="info-box-text" style="text-decoration: none; color: #000;"><b>Compras</b></span></a>
                    <span class="info-box-number">
                        {{ $total_compras }} Compras
                    </span>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <a href="{{ url('/admin/movimientos/lotes') }}">
                    <span class="info-box-icon bg-info">
                        <img src="{{ url('/img/alerta.gif') }}" alt="xd">
                    </span>
                </a>
                <div class="info-box-content">
                    <a href="{{ url('/admin/movimientos/lotes') }}">
                        <span class="info-box-text" style="text-decoration: none; color: #000;"><b>Lotes
                                Vencidos</b></span></a>
                    <span class="info-box-number">
                        {{ $total_lotes_vencidos }} Lotes
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
