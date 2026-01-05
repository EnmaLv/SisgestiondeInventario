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
                Categorias
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div>
            <a href="{{ url('admin/maestros/categorias/create') }}" class="rd-btn rd-btn-primary">
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
                    <h3 class="rd-title-sm">Categorias Registradas</h3>
                </div>

                <div class="rd-actions">
                    <div class="d-flex gap-3 align-items-center">
                        <span class="font-weight-bold" style="margin-right:10px;">Filtrar por estado:</span>
                        
                        <div class="toggle-container">
                            <input type="checkbox" id="estadoToggle" class="toggle-checkbox" {{ request('estado', 1) == 1 ? 'checked' : '' }}>
                            <label for="estadoToggle" class="toggle-label">
                                <span class="toggle-inner"></span>
                                <span class="toggle-switch"></span>
                            </label>
                        </div>
                    </div>
                    <form action="{{ route('admin.maestros.categorias.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Escriba la categoria" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>


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
                        @forelse($categorias as $categoria)
                            <tr>
                                <td class="text-center">
                                    {{ ($categorias->currentPage() - 1) * $categorias->perPage() + $loop->iteration }}</td>
                                <td class="text-center">{{ $categoria->nombre }}</td>
                                @if ($categoria->descripcion)
                                    <td class="text-center">{{ $categoria->descripcion }}</td>
                                @else
                                    <td class="text-center">Ninguna</td>
                                @endif
                                <td class="text-center">
                                    @if ($categoria->estado)
                                        <span class="rd-badge rd-badge-success">Activo</span>
                                    @else
                                        <span class="rd-badge rd-badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">

                                        <a href="{{ url('admin/maestros/categorias/' . $categoria->id . '/edit') }}"
                                            class="rd-action" title="Editar"><i class="fas fa-edit"></i></a>

                                        @if ($categoria->estado == true)
                                            <form action="{{ url('admin/maestros/categorias/' . $categoria->id) }}"
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
                                                        text: "Desea inactivar la categoria?",
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
                                                action="{{ url('admin/maestros/categorias/' . $categoria->id . '/activar') }}"
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
                                                        text: "Desea activar la categoria?",
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
                                <td colspan="5" class="text-center py-4">No hay categorias</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación del servidor --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $categorias->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop



@push('js')
    <script>
        document.getElementById('estadoToggle').addEventListener('change', function() {
            if (this.checked) {
                // Activos
                window.location.href = "{{ route('admin.maestros.categorias.index', array_merge(request()->query(), ['estado' => 1])) }}";
            } else {
                // Inactivos
                window.location.href = "{{ route('admin.maestros.categorias.index', array_merge(request()->query(), ['estado' => 0])) }}";
            }
        });
    </script>
@endpush