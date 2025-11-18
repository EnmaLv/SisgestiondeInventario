@extends('adminlte::page')

@section('content_header')
    <h1>Lotes</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Lotes Registrados</b></h3>

                    <div class="card-tools">
                        <a class="btn btn-primary" href=" {{ url('admin/movimientos/lotes/create') }}" class="btn btn-tool">
                            <i class="fas fa-plus"></i>
                            <b>Crear Nuevo</b>
                        </a>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: block;">
                    <table id="example1" class="table table-bordered table-striped table-hover table-sm" border="1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Codigo Lote</th>
                                <th>Producto</th>
                                <th>Proveedor</th>
                                <th>Fecha Entrada</th>
                                <th>Fecha Vencimiento</th>
                                <th>Cantidad Actual</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lotes as $lote)
                                <tr class="{{ $lote->is_expired ? 'table-danger' : '' }}">
                                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                                    <td>{{ $lote->codigo_lote }}</td>
                                    <td>{{ $lote->producto->nombre }}</td>
                                    <td>{{ $lote->proveedor->nombre }}</td>
                                    <td>{{ $lote->fecha_entrada }}</td>
                                    <td>{{ $lote->fecha_vencimiento }}</td>
                                    <td>{{ $lote->cantidad_actual }}</td>
                                    <td style="text-align: center;">
                                        @if ($lote->is_expired)
                                            <span class="badge badge-danger">Vencido</span>
                                        @else
                                            <span class="badge badge-success">Vigente</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
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
