@extends('adminlte::page')

@section('content_header')
    <h1>Sucursales por Lotes</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        @foreach ($sucursalesLotes as $sucursalLote)
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <a
                        href="{{ url('/admin/movimientos/sucursales_lotes/' . $sucursalLote->sucursal->id . '/' . $sucursalLote->lote->id) }}">
                        <span class="info-box-icon bg-info">
                            <img src="{{ url('/img/lote.png') }}" alt="xd">
                        </span>
                    </a>
                    <div class="info-box-content">
                        <a
                            href="{{ url('/admin/movimientos/sucursales_lotes/' . $sucursalLote->sucursal->id . '/' . $sucursalLote->lote->id) }}">
                            <span class="info-box-text"
                                style="text-decoration: none; color: #000;"><b>{{ $sucursalLote->lote->producto->nombre }}
                                    - {{ $sucursalLote->sucursal->nombre }}</b></span></a>
                        <span class="info-box-number">
                            {{ $sucursalLote->cantidad }} Unidades
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
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
                        12 Lotes
                    </span>
                </div>
            </div>
        </div>
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
    <script>
        $(function() {
            $("#example1").DataTable({
                "pageLength": 10,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Lotes",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Lotes",
                    "infoFiltered": "(Filtrado de _MAX_ total Lotes)",
                    "lengthMenu": "Mostrar _MENU_ Lotes",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscador:",
                    "searchPlaceholder": "Ingrese su búsqueda",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
                "responsive": true,
                "lengthChange": true,
                "autoWidth": false,
                buttons: [{
                        text: '<i class="fas fa-copy"></i> COPIAR',
                        extend: 'copy',
                        className: 'btn btn-default'
                    },
                    {
                        text: '<i class="fas fa-file-pdf"></i> PDF',
                        extend: 'pdf',
                        className: 'btn btn-danger'
                    },
                    {
                        text: '<i class="fas fa-file-csv"></i> CSV',
                        extend: 'csv',
                        className: 'btn btn-info'
                    },
                    {
                        text: '<i class="fas fa-file-excel"></i> EXCEL',
                        extend: 'excel',
                        className: 'btn btn-success'
                    },
                    {
                        text: '<i class="fas fa-print"></i> IMPRIMIR',
                        extend: 'print',
                        className: 'btn btn-warning'
                    }
                ]
            }).buttons().container().appendTo('#example1_wrapper .row:eq(0)');
        });
    </script>
@stop
