@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                Productos
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <a href="{{ url('admin/maestros/productos/create') }}" class="rd-btn rd-btn-primary">
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
                    <h3 class="rd-title-sm">Productos Registrados</h3>
                </div>
                <div class="rd-actions">
                    <div>
                        <div>
                            <div class="d-flex gap-3 align-items-center">
                                <span class="font-weight-bold" style="margin-right:10px;">Filtrar por estado:</span>     
                                <div class="toggle-container">
                                    <input type="checkbox" id="estadoToggle" class="toggle-checkbox" {{ request('activo', 1) == 1 ? 'checked' : '' }}>
                                    <label for="estadoToggle" class="toggle-label">
                                        <span class="toggle-inner"></span>
                                        <span class="toggle-switch"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('admin.maestros.productos.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Escriba el producto" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>
                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>
            <style>
                .toggle-container {
                    position: relative;
                    width: 60px;
                    height: 30px;
                }

                .toggle-checkbox {
                    display: none;
                }

                .toggle-label {
                    display: block;
                    overflow: hidden;
                    cursor: pointer;
                    border-radius: 999px;
                    background-color: #dc2626; /* rojo por defecto */
                    position: relative;
                    transition: background-color 0.25s;
                    height: 100%;
                }

                .toggle-inner {
                    display: block;
                    width: 200%;
                    margin-left: -100%;
                    transition: margin 0.25s;
                }

                .toggle-switch {
                    display: block;
                    width: 26px;
                    height: 26px;
                    background: white;
                    position: absolute;
                    top: 2px;
                    left: 2px;
                    border-radius: 50%;
                    transition: all 0.25s;
                }

                .toggle-checkbox:checked + .toggle-label {
                    background-color: #16a34a; /* verde cuando está activo */
                }

                .toggle-checkbox:checked + .toggle-label .toggle-switch {
                    transform: translateX(30px);
                }

            </style>
            <script>
                document.getElementById('estadoToggle').addEventListener('change', function() {
                    if (this.checked) {
                        // Activos
                        window.location.href = "{{ route('admin.maestros.productos.index', array_merge(request()->query(), ['activo' => 1])) }}";
                    } else {
                        // Inactivos
                        window.location.href = "{{ route('admin.maestros.productos.index', array_merge(request()->query(), ['activo' => 0])) }}";
                    }
                });
            </script>
            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th class="text-center">Codigo</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Categoria</th>
                            <th class="text-center">Cantidad Actual</th>
                            <th class="text-center">Unidad</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td class="text-center">{{ $producto->codigo }}</td>
                                <td class="text-center">{{ $producto->nombre }}</td>
                                <td class="text-center">{{ $producto->categoria->nombre }}</td>
                                @if ($producto->cantidad_actual == null)
                                    <td class="text-center"><span class="rd-badge rd-badge-secondary">No hay compra registrada</span></td>
                                @elseif ($producto->cantidad_actual == 0)
                                    <td class="text-center"><span class="rd-badge rd-badge-secondary">Agotado</span></td>
                                @else
                                    <td class="text-center">{{ $producto->cantidad_actual }}</td>
                                @endif
                                <td class="text-center">{{ $producto->unidad->nombre }}</td>
                                <td class="text-center">
                                    @if ($producto->estado == true)
                                        <span class="rd-badge rd-badge-success">Activo</span>
                                    @else
                                        <span class="rd-badge rd-badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">
                                        <a href="{{ url('admin/maestros/productos/' . $producto->id) }}" class="rd-action"
                                            title="Ver"><i class="fas fa-eye"></i></a>
                                        <a href="{{ url('admin/maestros/productos/' . $producto->id . '/edit') }}"
                                            class="rd-action" title="Editar"><i class="fas fa-edit"></i></a>
                                        @if ($producto->estado == true)
                                            <form action="{{ url('admin/maestros/productos/' . $producto->id) }}"
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
                                                        text: "Desea inactivar el producto?",
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
                                                action="{{ url('admin/maestros/productos/' . $producto->id . '/activar') }}"
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
                                                        text: "Desea activar el producto?",
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
                                <td colspan="8" class="text-center py-4">No hay productos</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 d-flex justify-content-center">
                {{ $productos->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop
