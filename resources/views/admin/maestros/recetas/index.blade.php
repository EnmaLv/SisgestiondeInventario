@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                Recetas Registradas
            </h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <a href="{{ url('admin/maestros/recetas/create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Crear Nueva Receta
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
                    <h3 class="rd-title-sm">Recetas Registradas</h3>
                </div>
                <div class="rd-actions">
                    <div class="flex gap-3 items-center">
                        <span class="font-bold text-sm text-gray-600 dark:text-gray-300">Filtrar por estado:</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="estadoToggle" class="sr-only peer"
                                {{ request('estado', 1) == 1 ? 'checked' : '' }}>
                            <div class="w-10 h-6 bg-gray-300 dark:bg-gray-700 rounded-full peer peer-checked:bg-[var(--color-primary)] transition-colors relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-4"></div>
                        </label>
                    </div>
                    <form action="{{ route('admin.maestros.recetas.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        
                        <input type="hidden" name="estado" value="{{ request('estado', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}" class="rd-search-input"
                            placeholder="Escriba la receta" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
            <script>
                document.getElementById('estadoToggle').addEventListener('change', function() {
                    if (this.checked) {
                        window.location.href = "{!! route('admin.maestros.recetas.index', array_merge(request()->query(), ['estado' => 1])) !!}";
                    } else {
                        window.location.href = "{!! route('admin.maestros.recetas.index', array_merge(request()->query(), ['estado' => 0])) !!}";
                    }
                });
            </script>
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
                                @if ($receta->descripcion)
                                    <td class="text-center">{{ $receta->descripcion }}</td>
                                @else
                                    <td class="text-center">Ninguna</td>
                                @endif
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

                                        @if ($receta->estado == true)
                                            <form action="{{ url('admin/maestros/recetas/' . $receta->id) }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rd-action rd-btn-danger"
                                                    onclick="confirmDelete(event, this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <script>
                                                function confirmDelete(event, button) {
                                                    event.preventDefault();
                                                    Swal.fire({
                                                        title: '¿Estás seguro?',
                                                        text: "Desea inactivar la receta?",
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
                                            <form action="{{ url('admin/maestros/recetas/' . $receta->id . '/activar') }}"
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
                                                        text: "Desea activar la receta?",
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
                                <td colspan="6" class="text-center py-4">No hay Recetas</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex justify-center">
                {{ $recetas->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

