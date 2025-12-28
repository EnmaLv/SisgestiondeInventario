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
                    <h3 class="card-title"><b>Compra creada</b></h3>

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
                                <div class="col-md-3" style="display: inline-block;">
                                    <div class="form-group">
                                        <label for="nombre">Proveedor</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-tags"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="proveedor_id" name="proveedor_id"
                                                placeholder="Seleccione proveedor"
                                                value="{{ old('proveedor_id', $compra->proveedor_nombre) }}" readonly>


                                        </div>
                                        @error('proveedor_id')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
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
                                <div class="form-group col-md-3" style="display: inline-block;">
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
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="codigo">Sucursal de Destino</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-sticky-note"></i></span>
                                        </div>
                                        @if ($sucursal_destino != null)
                                            <input type="text" class="form-control" id="estado" name="estado"
                                                placeholder="Ingrese estado"
                                                value="{{ old('estado', $sucursal_destino ? $sucursal_destino->nombre : '') }}"
                                                readonly>
                                        @else
                                            <input type="text" class="form-control" id="sucursal_destino"
                                                name="sucursal_destino" placeholder="Ingrese sucursal de destino"
                                                value="Sin concluir" readonly>
                                        @endif
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
                    <h3 class="card-title"><b>Productos Agregados</b></h3>
                </div>
                <div class="card-body" style="display: block;">

                    <div class="row">
                        <div class="col-md-12">

                            @if ($detalles->count() > 0)
                                <h2 class="my-4">Detalles de la Orden de Compra</h2>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Código de Lote</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($detalles as $detalle)
                                            <tr>
                                                <td>{{ $detalle->producto_nombre }}</td>
                                                <td>{{ $detalle->codigo_lote }}</td>
                                                <td>{{ $detalle->cantidad }} {{ $detalle->unidad_abreviatura }}</td>
                                                <td>{{ number_format($detalle->precio_unitario, 2, ',', '.') }}.BS</td>
                                                <td>{{ number_format($detalle->subtotal, 2, ',', '.') }}.BS</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <hr>
                            @else
                                <h4>No hay productos agregados a la compra.</h4>

                            @endif
                            <h3 align="right"><b>Total de la Compra: </b>{{ number_format($compra->total, 2, ',', '.') }}.BS</h3>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


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
        background-color: #7c3aed;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #6d28d9;
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
</style>
@endpush
