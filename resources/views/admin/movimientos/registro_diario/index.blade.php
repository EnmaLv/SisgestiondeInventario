@extends('adminlte::page')

@section('content_header')
    <h1>Registro Diario del Comedor</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Registro Diario</b></h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body" style="display: block;">
                    <div class="row mb-3 justify-content-between">
                        <div class="col-md-4">
                            <form action="{{ route('admin.movimientos.registro_diario.index') }}" method="GET" class="form-group mb-0">
                                <label for="busqueda_registro" class="mb-1"><strong>Buscar</strong></label>
                                <div class="d-flex">
                                    <input type="text" id="busqueda_registro" name="buscar" class="form-control form-control-sm w-50" placeholder="Buscar por nombre, apellido o PNF" value="{{ $buscar ?? '' }}">
                                    <button type="submit" class="btn btn-primary btn-sm ml-2" id="btnSerach">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 d-flex justify-content-end align-items-end mt-2 mt-md-0">
                            <button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="collapse" data-target="#filtrosRegistroDiario" aria-expanded="false" aria-controls="filtrosRegistroDiario">
                                <i class="fas fa-filter"></i> Filtros
                            </button>
                            <button type="button" class="btn btn-success btn-sm mr-2">
                                <i class="fas fa-file-excel"></i> Reporte Excel
                            </button>
                            <button type="button" class="btn btn-danger btn-sm" id="pdfBtn">
                                <i class="fas fa-file-pdf"></i> Reporte PDF
                            </button>
                        </div>
                    </div>

                    <div class="collapse mb-3" id="filtrosRegistroDiario">
                        <div class="card card-body p-3">
                            <form action="{{ route('admin.movimientos.registro_diario.index') }}" method="GET" class="form-row">
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="fecha_desde" class="mb-1"><strong>Fecha desde</strong></label>
                                        <input type="date" id="fecha_desde" class="form-control form-control-sm" name="fecha_desde">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-2">
                                        <label for="fecha_hasta" class="mb-1"><strong>Fecha hasta</strong></label>
                                        <input type="date" id="fecha_hasta" class="form-control form-control-sm" name="fecha_hasta">
                                    </div>
                                </div>
                                <div class="col-md-3 d-flex align-items-end mb-2">
                                    <button type="submit" class="btn btn-primary btn-sm mr-2">Aplicar</button>
                                    <button type="button" class="btn btn-default btn-sm" onclick="reset()">Limpiar</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <table id="example1" class="table table-bordered table-striped table-hover table-sm" border="1">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Persona</th>
                                <th>Apellido</th>
                                <th>PNF</th>
                                <th>Fecha de Registro</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $registro)
                                <tr>
                                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                                    <td>{{ $registro->nombre_persona}}</td>
                                    <td>{{ $registro->apellido_persona }}</td>
                                    <td>{{ $registro->nombre_pnf }}</td>
                                    <td>{{ Carbon\Carbon::parse($registro->fecha_regis_diario_c)->format('d/m/Y') }}</td>
                                    <td style="text-align: center;">
                                        <span class="badge badge-success">Aprobado</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <a href=""
                                            class="btn btn-info"><i class="fas fa-eye"></i></a>
                                        <a href="   "
                                            class="btn btn-warning"><i class="fas fa-edit"></i></a>
                                        <form action=""method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center;">No hay registros</td>
                                </tr>
                            @endforelse
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
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        }

        .minimalist-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .minimalist-item:last-child {
            border-bottom: none;
        }
        
        .minimalist-info {
            flex: 1;
        }
        
        .minimalist-name {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .minimalist-details {
            display: flex;
            font-size: 0.9rem;
            color: #7f8c8d;
        }
        
        .minimalist-details span {
            margin-right: 15px;
        }
        
        .minimalist-status {
            font-weight: 600;
        }
        
        .minimalist-status.success {
            color: #27ae60;
        }
        
        .minimalist-status.error {
            color: #e74c3c;
        }
        
        .minimalist-status.warning {
            color: #f39c12;
        }
    </style>
@stop

@section('js')
    <script>
        const desdeDate = document.getElementById('fecha_desde');
        const hastaDate = document.getElementById('fecha_hasta');

        // Fecha actual (máximo permitido)
        const fechaActual = new Date().toISOString().split('T')[0];

        // Establecer máximo hoy para ambos campos
        if (desdeDate) desdeDate.max = fechaActual;
        if (hastaDate) hastaDate.max = fechaActual;


        // Cuando cambie "desde", ajustar el mínimo de "hasta"
        if (desdeDate && hastaDate) {
            desdeDate.addEventListener('change', function () {
                if (!desdeDate.value) {
                    // Si se borra la fecha desde, quitamos la restricción mínima en hasta
                    hastaDate.min = '';
                    return;
                }

                // "hasta" no puede ser menor que "desde"
                hastaDate.min = desdeDate.value;

                if (hastaDate.value && hastaDate.value < desdeDate.value) {
                    hastaDate.value = desdeDate.value;
                }
            });

            // Cuando cambie "hasta", validar contra "desde"
            hastaDate.addEventListener('change', function () {
                if (!hastaDate.value || !desdeDate.value) {
                    return;
                }

                if (hastaDate.value < desdeDate.value) {
                    // Si el usuario pone una fecha hasta menor, movemos "desde" a esa fecha
                    desdeDate.value = hastaDate.value;
                }
            });
        }

        //Script para mostrar el PdfGeneratorUtil
        const pdfBtn = document.querySelector('#pdfBtn');
        const pdfRoute = `{{ route('admin.movimientos.registro_diario.export_pdf') }}`;
        if (pdfBtn) {
            pdfBtn.addEventListener('click', function () {

                const params = new URLSearchParams(window.location.search);
                const fechaDesde = params.get('fecha_desde')?? "";
                const fechaHasta = params.get('fecha_hasta')?? "";

                const url = `${pdfRoute}?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}`;
                window.open(url, '_blank');     
            });
        }

    </script>
@stop