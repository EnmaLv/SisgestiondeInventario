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
                Compra nro {{ $compra->id }}
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
        <div class="col-md-12 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>Paso 1 | Compra creada</b></h3>

                    <div class="card-tools">
                        <a href="{{ url('admin/movimientos/compras') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-left"></i>
                            <b>Volver</b>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3 display: inline-block;">
                                    <div class="form-group">
                                        <label for="nombre">Proveedor</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-tags"></i></span>
                                            </div>
                                            <select class="form-control" id="proveedor_id" name="proveedor_id" disabled>
                                                <option value="">Seleccione un proveedor</option>
                                                @foreach ($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->id }}"
                                                        {{ old('proveedor_id', $compra->proveedor_id) == $proveedor->id ? 'selected' : '' }}>
                                                        {{ $proveedor->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('proveedor_id')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-3" style="display: inline-block;">
                                    <label for="codigo">Fecha de Compra</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="datetime-local"
                                            value="{{ \Carbon\Carbon::now('America/Caracas')->format('Y-m-d\TH:i') }}"
                                            class="form-control" id="fecha" name="fecha"
                                            value="{{ old('fecha', $compra->fecha) }}" disabled>
                                    </div>
                                    @error('fecha')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4" style="display: inline-block;">
                                    <label for="codigo">Observaciones</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-sticky-note"></i></span>
                                        </div>
                                        @if ($compra->observaciones == !null)
                                            <input type="text" class="form-control" id="observaciones"
                                                name="observaciones" placeholder="Ingrese observaciones"
                                                value="{{ old('observaciones', $compra->observaciones) }}" readonly>
                                        @else
                                            <input type="text" class="form-control" id="observaciones"
                                                name="observaciones" placeholder="Ingrese observaciones"
                                                value="Sin observaciones" readonly>
                                        @endif
                                    </div>
                                    @error('observaciones')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="codigo">Compra</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-sticky-note"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="estado" name="estado"
                                            placeholder="Ingrese estado" value="{{ old('estado', $compra->estado) }}"
                                            readonly>
                                    </div>
                                    @error('estado')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>Paso 2 | Agregar productos</b></h3>
                </div>
                <div class="card-body" style="display: block;">
                    <livewire:admin.movimientos.compras.items-compra :compra="$compra" />

                </div>
            </div>
        </div>
    </div>

    @if ($compra->estado == 'Enviado al proveedor')
        <div class="row">
            <div class="col-md-5 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><b>Paso 3 | Finalizar Compra</b></h3>
                    </div>
                    <div class="card-body" style="display: block;">
                        <form action="{{ route('admin.movimientos.compras.finalizarCompra', $compra) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="sucursal_id">Sucursales</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-tags"></i></span>
                                            </div>
                                            <select class="form-control" id="sucursal_id" name="sucursal_id">
                                                <option value="" selected disabled>Seleccione una sucursal</option>
                                                @foreach ($sucursales as $sucursal)
                                                    <option value="{{ $sucursal->id }}"
                                                        {{ old('sucursal_id') == $sucursal->id ? 'selected' : '' }}>
                                                        {{ $sucursal->nombre }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('sucursal_id')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group" style="text-align: right;">
                                        <button type="submit" class="btn btn-success"><i class="fas fa-check"></i>
                                            Finalizar
                                            Compra</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif



@stop

@push('css')
<style>
    /* Estilos base para las tarjetas */
    .rd-card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        border: 1px solid #e5e7eb;
        margin-bottom: 1.5rem;
    }

    /* Header de la tarjeta */
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        background: #ffffff;
        border-bottom: 1px solid #e5e7eb;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1a202c;
        margin: 0;
    }

    /* Estilos para los grupos de formulario */
    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #4a5568;
        font-size: 0.875rem;
    }

    /* Estilos para los inputs */
    .input-group {
        border: 1px solid #d8dee9;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.2s ease;
    }

    .input-group-text {
        background: transparent;
        border: none;
        color: #64748b;
        padding: 0.5rem 0.75rem;
    }

    .form-control {
        border: none;
        background: transparent;
        box-shadow: none;
        padding: 0.5rem 0.75rem;
        height: auto;
    }

    .form-control:disabled,
    .form-control[readonly] {
        background-color: #f8f9fa;
        color: #6c757d;
        cursor: not-allowed;
    }

    /* Estilos para la tabla */
    .table {
        width: 100%;
        margin-bottom: 1.5rem;
        background-color: #fff;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
    }

    .table thead th {
        background-color: #f8f9fa;
        color: #4a5568;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        padding: 0.75rem 1.5rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .table tbody td {
        padding: 1rem 1.5rem;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
        color: #4a5568;
    }

    /* Botones */
    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background-color: #f1f5f9;
        color: #4b5563;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        text-decoration: none;
    }
    .card-tools {
    margin-left: auto; /* Empuja el botón a la derecha */
    display: flex;
    align-items: center;
    }

    .btn i {
        margin-right: 0.5rem;
    }

    .btn-primary {
        background-color: #0069d9;
        color: white;
        border: none;
    }


    .btn-tool {
        background: transparent;
        color: #4a5568;
        border: 1px solid #e2e8f0;
    }

    .btn-tool:hover {
        background-color: #f8f9fa;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }
        
        .table {
            display: block;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }

    /* Estilos para mensajes de error */
    .alert.alert-danger {
        background-color: #fef2f2;
        border: 1px solid #fecaca;
        color: #b91c1c;
        padding: 0.5rem 1rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    /* Mejoras visuales para el contenedor principal */
    .card-body {
        padding: 1.5rem;
    }

    /* Estilos para el encabezado personalizado */
    .content-header {
        padding: 1.5rem 1.5rem 0;
    }

    /* Ajustes para la imagen de perfil */
    .profile-image {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
    }

    .profile-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
    }
</style>
@endpush

@section('css')
    @livewireStyles
@stop
@section('js')
    @livewireScripts
@stop
