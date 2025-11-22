@extends('adminlte::page')

@section('content_header')
    <h1>Proveedor</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        <div class="col-md-9 m-auto">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Registrar Proveedor</b></h3>

                    <div class="card-tools">
                        <a href="{{ url('admin/maestros/proveedores') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-left"></i>
                            <b>Volver</b>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{ route('admin.maestros.proveedores.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-4" style="display: inline-block;">
                                        <label for="empresa">Empresa</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-user"></i></span>
                                            </div>
                                            <input type="text" value="{{ old('empresa') }}" class="form-control"
                                                id="empresa" name="empresa" placeholder="Ingrese el nombre de la empresa">
                                        </div>
                                        @error('empresa')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4" style="display: inline-block;">
                                        <label for="direccion">Dirección</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            <input type="text" value="{{ old('direccion') }}" class="form-control"
                                                id="direccion" name="direccion"
                                                placeholder="Ingrese la dirección de la sucursal">
                                        </div>
                                        @error('direccion')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4" style="display: inline-block;">
                                        <label for="nombre">Nombre del Proveedor</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-map-marker-alt"></i></span>
                                            </div>
                                            <input type="text" value="{{ old('nombre') }}" class="form-control"
                                                id="nombre" name="nombre" placeholder="Ingrese el nombre del proveedor">
                                        </div>
                                        @error('nombre')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-row col-md-12">
                                <div class="form-group col-md-4" style="display: inline-block;">
                                    <label for="telefono">Teléfono</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" value="{{ old('telefono') }}" class="form-control"
                                            data-inputmask="'mask': '(999) 999-9999'" data-mask id="telefono"
                                            inputmode="numeric" placeholder="(123) 456-7890" name="telefono">
                                    </div>
                                    @error('telefono')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4 float-right" style="display: inline-block;">
                                    <label for="email">Email</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input type="email" value="{{ old('email') }}" class="form-control"
                                            id="email" name="email" placeholder="Ingrese el email del proveedor">
                                    </div>
                                    @error('email')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <a href="{{ url('admin/maestros/proveedores') }}" class="btn btn-default">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear</button>
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
