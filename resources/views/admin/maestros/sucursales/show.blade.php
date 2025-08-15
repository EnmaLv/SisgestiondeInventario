@extends('adminlte::page')

@section('content_header')
    <h1>Crea una Nueva Sucursal</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8 m-auto">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>Sucursales Registradas</b></h3>

                    <div class="card-tools">
                        <a href="{{ url('admin/maestros/sucursales') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-left"></i>
                            <b>Volver</b>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{ route('admin.maestros.sucursales.store') }}" method="POST">
                        @csrf
                        <div class="form-group col-md-5 m-auto" style="display: inline-block;">
                            <label for="nombre">Nombre de la Sucursal</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text inline-block"><i class="fas fa-tag"></i></span>
                                </div>
                                <input type="text" class="form-control" id="nombre" name="nombre"
                                    placeholder="Ingrese el nombre de la sucursal" value="{{ $sucursal->nombre }}" readonly>
                            </div>
                            @error('nombre')
                                <div class="alert text-danger p-0 m-0">
                                    <b>{{ 'Este campo es obligatorio.' }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-7 float-right" style="display: inline-block;">
                            <label for="direccion">Dirección</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text inline-block"><i class="fas fa-map-marker-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" id="direccion" name="direccion"
                                    placeholder="Ingrese la dirección de la sucursal" value="{{ $sucursal->direccion }}"
                                    readonly>
                            </div>
                            @error('direccion')
                                <div class="alert text-danger p-0 m-0">
                                    <b>{{ 'Este campo es obligatorio.' }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="form-row col-md-12">
                            <div class="form-group col-md-5" style="display: inline-block;">
                                <label for="telefono">Teléfono</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inline-block"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" data-inputmask="'mask': '(999) 999-9999'"
                                        data-mask id="telefono" inputmode="numeric" placeholder="(123) 456-7890"
                                        name="telefono" value="{{ $sucursal->telefono }}" readonly>
                                </div>
                                @error('telefono')
                                    <div class="alert text-danger p-0 m-0">
                                        <b>{{ 'Este campo es obligatorio.' }}</b>
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-7" style="display: inline-block;">
                                <label for="activo">Estado</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text inline-block"><i class="fas fa-toggle-on"></i></span>
                                    </div>
                                    <select name="activo" id="activo" class="form-control" disabled>
                                        <option value="">Seleccione un estado</option>
                                        <option value="1" {{ $sucursal->activo == '1' ? 'selected' : '' }}>Activo
                                        </option>
                                        <option value="0" {{ $sucursal->activo == '0' ? 'selected' : '' }}>
                                            Inactivo
                                        </option>
                                    </select>
                                </div>
                                @error('activo')
                                    <div class="alert text-danger p-0 m-0">
                                        <b>{{ 'Este campo es obligatorio.' }}</b>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ url('admin/maestros/sucursales') }}" class="btn btn-default">Cancelar</a>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')
    <script>
        $(document).ready(function() {
            $("[data-mask]").inputmask();
        });
        $(function() {
            $("#telefono").inputmask("(999) 999-9999");
        });
    </script>
@stop
