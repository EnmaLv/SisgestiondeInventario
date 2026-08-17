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
        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>
            <a href="{{ url('admin/maestros/productos/create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i>
                <span class="d-none d-md-inline">Crear Producto</span>
                <span class="d-inline d-md-none">Crear</span>
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
                    <div class="d-flex gap-3 align-items-center rd-toggle-wrapper">
                        <span class="font-weight-bold rd-toggle-label">Filtrar por estado:</span>
                        <div class="toggle-container">
                            <input type="checkbox" id="estadoToggle" class="toggle-checkbox"
                                {{ request('activo', 1) == 1 ? 'checked' : '' }}>
                            <label for="estadoToggle" class="toggle-label">
                                <span class="toggle-inner"></span>
                                <span class="toggle-switch"></span>
                            </label>
                        </div>
                    </div>

                    <form action="{{ route('admin.maestros.productos.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="hidden" name="activo" value="{{ request('activo', 1) }}">
                        <input type="hidden" name="categoria" value="{{ request('categoria') }}">

                        <input type="text" name="buscar" value="{{ request('buscar') }}" class="rd-search-input"
                            placeholder="Buscar producto..." />
                        <button class="rd-icon-btn" type="submit" title="Buscar">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>
                </div>
            </div>

            <!-- FILTROS COLAPSABLES -->
            <div class="collapse {{ request('categoria') ? 'show' : '' }}" id="filters">
                <div class="rd-filters">
                    <form action="{{ route('admin.maestros.productos.index') }}" method="GET" class="rd-filters-form">
                        <input type="hidden" name="activo" value="{{ request('activo', 1) }}">
                        <input type="hidden" name="buscar" value="{{ request('buscar') }}">

                        <div class="rd-filter-row">
                            <label>Categoría</label>
                            <select name="categoria" id="categoria" class="rd-filter-input">
                                <option value="">Todas</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}" @if (request('categoria') == $categoria->id) selected @endif>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="rd-filter-actions">
                            <button class="rd-btn rd-btn-primary" type="submit">
                                <i class="fas fa-check"></i> Aplicar
                            </button>
                            <a href="{{ route('admin.maestros.productos.index', ['activo' => request('activo', 1)]) }}"
                                class="rd-btn rd-btn-default text-decoration-none d-inline-flex align-items-center justify-content-center">
                                <i class="fas fa-times"></i> Limpiar
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABLA RESPONSIVE -->
            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Categoría</th>
                            <th class="text-center">Cantidad</th>
                            <th class="text-center">Unidad</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($productos as $producto)
                            <tr>
                                <td class="text-center" data-label="Código">
                                    <span class="rd-code-badge">{{ $producto->codigo }}</span>
                                </td>
                                <td class="text-center" data-label="Nombre">
                                    <strong>{{ $producto->nombre }}</strong>
                                </td>
                                <td class="text-center" data-label="Categoría">
                                    {{ $producto->categoria->nombre }}
                                </td>
                                <td class="text-center" data-label="Cantidad">
                                    @if ($producto->cantidad_actual == null)
                                        <span class="rd-badge rd-badge-secondary">
                                            <i class="fas fa-info-circle"></i> Sin compra
                                        </span>
                                    @elseif ($producto->cantidad_actual == 0)
                                        <span class="rd-badge rd-badge-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Agotado
                                        </span>
                                    @else
                                        <span class="rd-quantity-badge">{{ $producto->cantidad_actual }}</span>
                                    @endif
                                </td>
                                <td class="text-center" data-label="Unidad">
                                    {{ $producto->unidad->nombre }}
                                </td>
                                <td class="text-center" data-label="Estado">
                                    @if ($producto->estado == true)
                                        <span class="rd-badge rd-badge-success">
                                            <i class="fas fa-check-circle"></i> Activo
                                        </span>
                                    @else
                                        <span class="rd-badge rd-badge-danger">
                                            <i class="fas fa-times-circle"></i> Inactivo
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center" data-label="Acciones">
                                    <div class="rd-action-group">
                                        <a href="{{ url('admin/maestros/productos/' . $producto->id) }}"
                                            class="rd-action" title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ url('admin/maestros/productos/' . $producto->id . '/edit') }}"
                                            class="rd-action" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if ($producto->estado == true)
                                            <form action="{{ url('admin/maestros/productos/' . $producto->id) }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rd-action rd-btn-danger"
                                                    onclick="confirmDelete(event, this, 'inactivar')" title="Inactivar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ url('admin/maestros/productos/' . $producto->id . '/activar') }}"
                                                method="POST" class="form-activate" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="rd-action rd-action-success"
                                                    onclick="confirmDelete(event, this, 'activar')" title="Activar">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="rd-empty-state">
                                        <i class="fas fa-box-open"
                                            style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"></i>
                                        <p style="color: #64748b; margin: 0;">No hay productos registrados</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINACIÓN -->
            <div class="mt-3 d-flex justify-content-center">
                {{ $productos->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        // Toggle de estado
        document.getElementById('estadoToggle').addEventListener('change', function() {
            if (this.checked) {
                // Activos
                window.location.href = "{!! route('admin.maestros.productos.index', array_merge(request()->query(), ['activo' => 1])) !!}";
            } else {
                // Inactivos
                window.location.href = "{!! route('admin.maestros.productos.index', array_merge(request()->query(), ['activo' => 0])) !!}";
            }
        });

        // Confirmación unificada para activar/inactivar
        function confirmDelete(event, button, action) {
            event.preventDefault();

            const isActivate = action === 'activar';
            const title = isActivate ? '¿Activar producto?' : '¿Inactivar producto?';
            const text = isActivate ?
                'El producto volverá a estar disponible en el sistema.' :
                'El producto dejará de estar disponible en el sistema.';
            const confirmText = isActivate ? 'Sí, activar' : 'Sí, inactivar';
            const icon = isActivate ? 'question' : 'warning';

            Swal.fire({
                title: title,
                text: text,
                icon: icon,
                showCancelButton: true,
                confirmButtonColor: isActivate ? '#10b981' : '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: confirmText,
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            });
        }
    </script>
@endpush
