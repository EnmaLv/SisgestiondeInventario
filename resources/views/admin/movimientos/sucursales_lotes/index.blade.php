@extends('adminlte::page')

@section('content_header')
    <h1>Sucursales por Lotes</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        @foreach ($sucursales as $sucursalLote)
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <a href="{{ url('admin/movimientos/sucursales_lotes/show/' . $sucursalLote->id) }}">
                        <span class="info-box-icon bg-info">
                            <img src="{{ url('/img/restaurante.gif') }}" alt="xd">
                        </span>
                    </a>
                    <div class="info-box-content">
                        <a href="{{ url('admin/movimientos/sucursales_lotes/show/' . $sucursalLote->id) }}">
                            <span class="info-box-text"
                                style="text-decoration: none; color: #000;"><b>{{ $sucursalLote->nombre }}</b></span></a>
                        <span class="info-box-number">
                            {{ $sucursalLote->totalInventarioSucursalLotes }} Unidades
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop

@section('css')
    <style>
        /* Fondo transparente y sin borde en el contenedor */
        #example1_wrapper .dt-buttons {
            background-color: transparent;
            box-shadow: none;
            border: none;
            display: flex;
            justify-content: center;
            /* Centrar los botones */
            gap: 10px;
            /* Espaciado entre botones */
            margin-bottom: 15px;
            /* Separar botones de la tabla */
        }

        /* Estilo personalizado para los botones */
        #example1_wrapper .btn {
            color: #fff;
            /* Color del texto en blanco */
            border-radius: 4px;
            /* Bordes redondeados */
            padding: 5px 15px;
            /* Espaciado interno */
            font-size: 14px;
            /* TamaÃ±o de fuente */
        }

        /* Colores por tipo de botÃ³n */
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
            border: none;
        }

        .btn-default {
            background-color: #6e7176;
            color: #212529;
            border: none;
        }
    </style>
@stop

@section('js')

@stop
