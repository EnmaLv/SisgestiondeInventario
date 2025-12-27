@extends('adminlte::page')

@section('content_header')
    @include('components.alert')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Error:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

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
                Requisicion nro {{ $compra->id }}
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
@include('components.alert')
    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>Paso 1 | Requisicion creada</b></h3>

                    <div class="card-tools">
                        <form action="{{ route('admin.movimientos.compras.cancelar', $compra) }}"
                            method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-tool text-danger" onclick="confirmDelete(event, this)">
                                <i class="fas fa-arrow-left"></i>
                                <b>Cancelar y volver</b>
                            </button>
                            <script>
                                function confirmDelete(event, button) {
                                    event.preventDefault();
                                    Swal.fire({
                                        title: '¿Estás seguro?',
                                        text: "Se perderán todos los productos agregados.",
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'Sí',
                                        cancelButtonText: 'Cancelar'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            button.closest('form').submit();
                                        }
                                    });
                                }
                            </script>
                        </form>

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
                                    <label for="codigo">Fecha de la Requisicion</label>
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
                                    <label for="codigo">Requisicion</label>
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
            <div class="col-md-12 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><b>Paso 3 | Registrar Fechas de Vencimiento</b></h3>
                    </div>
                    <div class="card-body">
                        <livewire:admin.movimientos.compras.fechas-compra :compra="$compra" />
                    </div>
                </div>
            </div>
        </div>
    @endif


    @if ($compra->estado == 'Enviado al proveedor')
        <div class="row">
            <div class="col-md-12 m-auto">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><b>Paso 4 | Finalizar Requisicion</b></h3>
                    </div>
                    <div class="card-body" style="display: block;">
                        <form action="{{ route('admin.movimientos.compras.finalizarCompra', $compra) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="sucursal_id">Sedes</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-tags"></i></span>
                                            </div>
                                            <select class="form-control" id="sucursal_id" name="sucursal_id">
                                                <option value="" selected disabled>Seleccione una Sede</option>
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
                                            Requisicion</button>
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

@section('css')
    @livewireStyles
@stop
@section('js')
    @livewireScripts
@stop
