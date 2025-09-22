@extends('adminlte::page')

@section('content_header')
    <h1>Compra nro {{ $compra->id }}</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="card card-info">
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
                                <div class="col-md-2 display: inline-block;">
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
                                <div class="form-group col-md-4" style="display: inline-block;">
                                    <label for="codigo">Observaciones</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-sticky-note"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="observaciones" name="observaciones"
                                            placeholder="Ingrese observaciones"
                                            value="{{ old('observaciones', $compra->observaciones) }}" readonly>
                                    </div>
                                    @error('observaciones')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="codigo">Estado</label>
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
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>Paso 2 | Agregar productos</b></h3>
                </div>
                <div class="card-body" style="display: block;">
                    <livewire:admin.movimientos.compras.items-compra :compra="$compra" />
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title"><b>Paso 3 | Finalizar Compra</b></h3>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{ route('admin.movimientos.compras.finalizarCompra', $compra) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">

                                <div class="form-group">
                                    <label for="sucursal_id">Sucursales</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i class="fas fa-tags"></i></span>
                                        </div>
                                        <select class="form-control" id="sucursal_id" name="sucursal_id">
                                            <option value="">Seleccione una sucursal</option>
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
                                <hr>
                                <div class="form-group">

                                    <a href="{{ route('admin.movimientos.compras.enviarCorreo', $compra) }}"
                                        class="btn btn-primary"><i class="fas fa-paper-plane"></i> Enviar Correo al
                                        Proveedor</a>
                                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Finalizar
                                        Compra</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



@stop

@section('css')
    @livewireStyles
@stop
@section('js')
    @livewireScripts
@stop
