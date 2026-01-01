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
                Sedes
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div>
            <a href="{{ url('admin/maestros/sucursales/create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Crear Nuevo
            </a>
        </div>

    </div>
@stop

@section('content')
    @include('components.alert')
    <div class="rd-card rd-card-full">

        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Sedes Registradas</h3>
                </div>

                <div class="rd-actions">
                    <form action="{{ route('admin.maestros.sucursales.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Escriba la sucursal" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>

                </div>
            </div>

            <div class="collapse" id="filters">
                <div class="rd-filters">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <span class="font-weight-bold" style="margin-right:5px; ">Filtrar por estado:</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.maestros.sucursales.index', array_merge(request()->query(), ['activo' => 1])) }}"
                                class="btn {{ request('activo', 1) == 1 ? 'btn-primary' : 'btn-outline-primary' }}">
                                Activos
                            </a>
                            <a href="{{ route('admin.maestros.sucursales.index', array_merge(request()->query(), ['activo' => 0])) }}"
                                class="btn {{ request('activo', 1) == 0 ? 'btn-danger' : 'btn-outline-danger' }}">
                                Inactivos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Nombre</th>
                            <th>Dirección</th>
                            <th>Teléfono</th>
                            <th style="width:120px">Estado</th>
                            <th style="width:150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sucursales as $sucursal)
                            <tr>
                                <td class="text-center">
                                    {{ ($sucursales->currentPage() - 1) * $sucursales->perPage() + $loop->iteration }}</td>
                                <td>{{ $sucursal->nombre }}</td>
                                <td>{{ $sucursal->direccion }}</td>
                                <td>{{ $sucursal->telefono }}</td>
                                <td class="text-center">
                                    @if ($sucursal->activo)
                                        <span class="rd-badge rd-badge-success">Activo</span>
                                    @else
                                        <span class="rd-badge rd-badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">

                                        <a href="{{ url('admin/maestros/sucursales/' . $sucursal->id . '/edit') }}"
                                            class="rd-action" title="Editar"><i class="fas fa-edit"></i></a>

                                        @if ($sucursal->activo == true)
                                            <form action="{{ url('admin/maestros/sucursales/' . $sucursal->id) }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rd-action rd-action-danger btn-delete"
                                                    onclick="confirmDelete(event, this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

                                            <script>
                                                function confirmDelete(event, button) {
                                                    event.preventDefault();
                                                    Swal.fire({
                                                        title: '¿Estás seguro?',
                                                        text: "Desea inactivar la sucursal?",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Sí, inactivar',
                                                        cancelButtonText: 'Cancelar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            button.closest('form').submit();
                                                        }
                                                    });
                                                }
                                            </script>
                                        @else
                                            <form
                                                action="{{ url('admin/maestros/sucursales/' . $sucursal->id . '/activar') }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="rd-action rd-action-success btn-delete"
                                                    onclick="confirmDelete(event, this)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <script>
                                                function confirmDelete(event, button) {
                                                    event.preventDefault();
                                                    Swal.fire({
                                                        title: '¿Estás seguro?',
                                                        text: "Desea activar la sucursal?",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Sí, activar',
                                                        cancelButtonText: 'Cancelar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            button.closest('form').submit();
                                                        }
                                                    });
                                                }
                                            </script>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No hay sucursales</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación del servidor --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $sucursales->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

@push('js')
    @if (session('success'))
        <script>
            Swal.fire({
                title: '¡Hecho!',
                text: '{{ session('success') }}',
                icon: '{{ session('icono', 'success') }}',
                confirmButtonText: 'Aceptar'
            });
        </script>
    @endif

@endpush

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
    <style>
        /* Filtros */
        .rd-filters {
            padding: 16px;
            background: #f8f9fa;
            border-radius: 8px;
            margin: 12px 0;
        }

        /* Botones de acción */
        .rd-actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .rd-search-inline {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .rd-search-input {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e6eef6;
            min-width: 200px;
        }

        .rd-icon-btn {
            background: #fff;
            border: 1px solid #e6eef6;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            color: #374151;
            transition: all 0.2s;
        }

        .rd-icon-btn:hover {
            background: #f8f9fa;
        }

        /* Tabla */
        .rd-table {
            width: 100%;
            border-collapse: collapse;
        }

        .rd-table th {
            background: #f8fafc;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #4b5563;
            border-bottom: 2px solid #e5e7eb;
        }

        .rd-table td {
            padding: 12px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: middle;
        }

        /* Badges de estado */
        .rd-badge {
            padding: 4px 8px;
            border-radius: 9999px;
            font-size: 12px;
            font-weight: 600;
        }

        .rd-badge-success {
            background-color: #ecfdf5;
            color: #065f46;
        }

        .rd-badge-danger {
            background-color: #fef2f2;
            color: #b91c1c;
        }

        /* Acciones */
        .rd-action-group {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .rd-action {
            padding: 6px;
            border-radius: 6px;
            color: #4b5563;
            transition: all 0.2s;
        }

        .rd-action:hover {
            background: #f3f4f6;
        }

        .rd-action-danger {
            color: #dc2626;
        }

        .rd-action-danger:hover {
            background: #fee2e2;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .rd-actions {
                flex-wrap: wrap;
            }

            .rd-search-input {
                min-width: 150px;
            }
        }
    </style>
@stop
