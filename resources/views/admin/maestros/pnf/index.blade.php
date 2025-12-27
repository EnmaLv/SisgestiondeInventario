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
                PNF
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div>
            <a href="{{ url('admin/maestros/pnf/create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Crear Nuevo PNF
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
                    <h3 class="rd-title-sm">PNF Registrados</h3>
                </div>

                <div class="rd-actions">
                    <form action="{{ route('admin.maestros.pnf.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ request('buscar') ?? '' }}" class="rd-search-input"
                            placeholder="Escriba el PNF" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>

                </div>
            </div>

            {{-- Filtros --}}
            <div class="collapse" id="filters">
                <div class="rd-filters">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <span class="font-weight-bold" style="margin-right:5px; ">Filtrar por estado:</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.maestros.pnf.index', array_merge(request()->query(), ['activo' => 1])) }}"
                                class="btn {{ request('activo', 1) == 1 ? 'btn-primary' : 'btn-outline-primary' }}">
                                Activos
                            </a>
                            <a href="{{ route('admin.maestros.pnf.index', array_merge(request()->query(), ['activo' => 2])) }}"
                                class="btn {{ request('activo', 1) == 2 ? 'btn-danger' : 'btn-outline-danger' }}">
                                Inactivos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div id="printArea">
                <table class="rd-table" >
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Nombre Del PNF</th>
                            <th style="width:120px">Estado</th>
                            <th style="width:150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pnfs as $pnf)
                            <tr>
                                <td class="text-center">
                                    {{ ($pnfs->currentPage() - 1) * $pnfs->perPage() + $loop->iteration }}</td>
                                <td>{{ $pnf->nombre_pnf }}</td>
                                <td class="text-center">
                                    @if ($pnf->id_estatus == 1)
                                        <span class="rd-badge rd-badge-success">Activo</span>
                                    @else
                                        <span class="rd-badge rd-badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">

                                        <a href="{{ route('admin.maestros.pnf.edit', ['id'=>$pnf->id_pnf]) }}"
                                            class="rd-action" title="Editar"><i class="fas fa-edit"></i></a>

                                        @if ($pnf->id_estatus == 1)
                                            <form action="{{ route('admin.maestros.pnf.destroy', ['id'=>$pnf->id_pnf]) }}"
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
                                                action="{{ route('admin.maestros.pnf.activar', $pnf->id_pnf) }}"
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
                {{ $pnfs->onEachSide(1)->links('components.pagination') }}
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

