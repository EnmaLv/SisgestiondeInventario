@extends('adminlte::page')

@section('content_header')
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
                Recetas Registradas
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div>
            <a href="{{ url('admin/maestros/recetas/create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Crear Nuevo
            </a>
        </div>

    </div>
@stop

@section('content')
    <div class="rd-card rd-card-full">

        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Recetas Registradas</h3>
                </div>

                <div class="rd-actions">
                    <form action="{{ route('admin.maestros.recetas.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Escriba la receta" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>

                    <div class="rd-export-group">
                        <button class="rd-btn rd-btn-success" title="Exportar Excel"><i class="fas fa-file-excel"></i>
                            Excel</button>
                        <button class="rd-btn rd-btn-danger" title="Exportar PDF"><i class="fas fa-file-pdf"></i>
                            PDF</button>
                    </div>
                </div>
            </div>

            {{-- <div class="collapse" id="filters">
                <div class="rd-filters">
                    <form action="{{ route('admin.movimientos.registro_diario.index') }}" method="GET"
                        class="rd-filters-form">
                        <div class="rd-filter-row">
                            <label>Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="rd-filter-input" />
                        </div>
                        <div class="rd-filter-row">
                            <label>Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="rd-filter-input" />
                        </div>
                        <div class="rd-filter-row rd-filter-actions">
                            <button class="rd-btn rd-btn-primary" type="submit">Aplicar</button>
                            <button type="button" class="rd-btn rd-btn-default"
                                onclick="document.getElementById('fecha_desde').value=''; document.getElementById('fecha_hasta').value='';">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div> --}}

            {{-- Tabla --}}
            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Descripcion</th>
                            <th style="width:120px" class="text-center">Estado</th>
                            <th style="width:150px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recetas as $receta)
                            <tr>
                                <td class="text-center">
                                    {{ ($recetas->currentPage() - 1) * $recetas->perPage() + $loop->iteration }}</td>
                                <td class="text-center">{{ $receta->nombre }}</td>
                                <td class="text-center">{{ $receta->descripcion }}</td>
                                <td class="text-center">
                                    @if ($receta->estado)
                                        <span class="rd-badge rd-badge-success">Activo</span>
                                    @else
                                        <span class="rd-badge rd-badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">

                                        <a href="{{ url('admin/maestros/recetas/' . $receta->id . '/edit') }}"
                                            class="rd-action" title="Editar"><i class="fas fa-edit"></i></a>

                                        <form action="{{ url('admin/maestros/recetas/' . $receta->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rd-action rd-action-danger btn-delete"
                                                onclick="preguntar{{ $receta->id }}(event)"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                        <script>
                                            function preguntar{{ $receta->id }}(event) {
                                                event.preventDefault();
                                                Swal.fire({
                                                    title: '¿Estás seguro?',
                                                    text: "No podrás deshacer esta acción",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#3085d6',
                                                    cancelButtonColor: '#d33',
                                                    confirmButtonText: 'Sí, eliminar',
                                                    cancelButtonText: 'Cancelar'
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        event.target.form.submit();
                                                    }
                                                });
                                            }
                                        </script>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No hay Recetas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación del servidor --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $recetas->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop


@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop
