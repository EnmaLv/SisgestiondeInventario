@extends('adminlte::page')

@section('content_header')
    <h1>Crea una Nueva Categoría</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="card card-warning">
                <div class="card-header">
                    <h3 class="card-title" style="color: #fff;"><b>Llenar los campos del formulario</b></h3>

                    <div class="card-tools">
                        <a href="{{ url('admin/maestros/categorias') }}" class="btn btn-tool" style="color: #fff;">
                            <i class="fas fa-arrow-left"></i>
                            <b>Volver</b>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{ route('admin.maestros.categorias.update', $categoria->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text inline-block"><i class="fas fa-tag"></i></span>
                                </div>
                                <input type="text" value="{{ $categoria->nombre }}" class="form-control" id="nombre"
                                    name="nombre" placeholder="Ingrese el nombre de la categoría">
                            </div>
                            @error('nombre')
                                <div class="alert text-danger p-0 m-0">
                                    <b>{{ 'Este campo es obligatorio.' }}</b>
                                </div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"
                                placeholder="Ingrese la descripción de la categoría" style="resize: none;">{{ $categoria->descripcion }}</textarea>
                        </div>
                        <div class="form-group">
                            <a href="{{ url('admin/maestros/categorias') }}" class="btn btn-default"><b>Cancelar</b></a>
                            <button type="submit" class="btn btn-warning"><b>Guardar</b></button>
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

@stop
