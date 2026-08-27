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
                Sedes
            </h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.maestros.sedes.create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Crear Nueva Sede
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
                    <div class="flex gap-3 items-center">
                        <span class="font-weight-bold" style="margin-right:10px;">Filtrar por estado:</span>
                        <div class="toggle-container">
                            <input type="checkbox" id="estadoToggle" class="toggle-checkbox"
                                {{ request('activo', 1) == 1 ? 'checked' : '' }}>
                            <label for="estadoToggle" class="toggle-label">
                                <span class="toggle-inner"></span>
                                <span class="toggle-switch"></span>
                            </label>
                        </div>
                    </div>
                    <form action="{{ route('admin.maestros.sedes.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="hidden" name="activo" value="{{ request('activo', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}" class="rd-search-input"
                            placeholder="Escriba la Sede/Anexo" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>
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
                        @forelse($sedes as $sede)
                            <tr>
                                <td class="text-center">
                                    {{ ($sedes->currentPage() - 1) * $sedes->perPage() + $loop->iteration }}</td>
                                <td>{{ $sede->nombre }}</td>
                                <td>{{ $sede->direccion }}</td>
                                <td>{{ $sede->telefono }}</td>
                                <td class="text-center">
                                    @if ($sede->activo)
                                        <span class="rd-badge rd-badge-success">Activo</span>
                                    @else
                                        <span class="rd-badge rd-badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">
                                        <a href="{{ route('admin.maestros.sedes.edit', $sede->id) }}"
                                            class="rd-action" title="Editar"><i class="fas fa-edit"></i></a>
                                        @if ($sede->activo)
                                            <form action="{{ route('admin.maestros.sedes.destroy', $sede->id) }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rd-action rd-btn-danger"
                                                    onclick="confirmDelete(event, this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ url('admin/maestros/sedes/' . $sede->id . '/activar') }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="rd-action rd-action-success btn-delete"
                                                    onclick="confirmActivate(event, this)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No hay Sedes</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3 flex justify-center">
                {{ $sedes->onEachSide(1)->links('components.pagination') }}
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

    <script>
        function confirmDelete(event, button) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Desea inactivar la sede?",
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

        function confirmActivate(event, button) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Desea activar la sede?",
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

        document.getElementById('estadoToggle').addEventListener('change', function() {
            if (this.checked) {
                window.location.href = "{!! route('admin.maestros.sedes.index', array_merge(request()->query(), ['activo' => 1])) !!}";
            } else {
                window.location.href = "{!! route('admin.maestros.sedes.index', array_merge(request()->query(), ['activo' => 0])) !!}";
            }
        });
    </script>
@endpush