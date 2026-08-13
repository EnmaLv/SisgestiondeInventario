@extends('adminlte::page')
@section('content_header') <!-- ... --> @stop

@section('content')
    <div class="row">
        <div class="col-md-12 m-auto">
            <x-producto-formulario titulo="Registrar Nuevo Medicamento" :action="route('admin.salud.maestros.medicamentos.store')" :rutaVolver="route('admin.salud.maestros.medicamentos.index')" :categorias="$categorias"
                :envases="$envases" :unidades="$unidades" :esMedicamento="true" />
        </div>
    </div>
@stop
