@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Rutas de Transporte</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.transporte.maestros.bus_rutas.create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Nueva Ruta
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
                    <h3 class="rd-title-sm">Rutas Registradas</h3>
                </div>
                <div class="rd-actions">
                    <div class="d-flex gap-3 align-items-center">
                        <span class="font-weight-bold" style="margin-right:10px;">Filtrar por estado:</span>
                        <div class="toggle-container">
                            <input type="checkbox" id="estadoToggle" class="toggle-checkbox"
                                {{ request('estado', 1) == 1 ? 'checked' : '' }}>
                            <label for="estadoToggle" class="toggle-label">
                                <span class="toggle-inner"></span>
                                <span class="toggle-switch"></span>
                            </label>
                        </div>
                    </div>
                    <form action="{{ route('admin.transporte.maestros.bus_rutas.index') }}" method="GET"
                        class="rd-search-inline" role="search">
                        <input type="hidden" name="estado" value="{{ request('estado', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}" class="rd-search-input"
                            placeholder="Buscar ruta..." />
                        <button class="rd-icon-btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Origen → Destino</th>
                        <th class="text-center">Distancia</th>
                        <th class="text-center">Horarios</th>
                        <th style="width:120px" class="text-center">Estado</th>
                        <th style="width:150px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rutas as $ruta)
                        <tr>
                            <td class="text-center">
                                {{ ($rutas->currentPage() - 1) * $rutas->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-center">{{ $ruta->nombre }}</td>

                            <td class="text-center">
                                @if ($ruta->paradas && $ruta->paradas->isNotEmpty())
                                    {{ $ruta->paradas->first()->nombre }}
                                    <i class="fas fa-arrow-right mx-1" style="color:var(--color-primary)"></i>
                                    {{ $ruta->paradas->last()->nombre }}
                                @else
                                    <span class="text-muted" style="font-size: 0.85rem;">Sin paradas trazadas</span>
                                @endif
                            </td>

                            <td class="text-center">{{ $ruta->distancia_km }} km</td>
                            <td class="text-center" style="font-size:0.85rem;">
                                @forelse($ruta->horarios as $horario)
                                    <span
                                        class="rd-badge {{ $horario->tipo_viaje === 'entrada' ? 'rd-badge-success' : 'rd-badge-warning' }} m-1"
                                        title="Viaje de {{ $horario->tipo_viaje }}">
                                        {{ substr($horario->hora_salida, 0, 5) }}
                                        {{ $horario->tipo_viaje === 'entrada' ? '☀️' : '🏠' }}
                                    </span>
                                @empty
                                    <span class="text-muted">Sin horarios</span>
                                @endforelse
                            </td>

                            <td class="text-center">
                                @if ($ruta->estado)
                                    <span class="rd-badge rd-badge-success">Activa</span>
                                @else
                                    <span class="rd-badge rd-badge-danger">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="rd-action-group">
                                    <a href="{{ route('admin.transporte.maestros.bus_rutas.edit', $ruta) }}"
                                        class="rd-action" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($ruta->estado)
                                        <form action="{{ route('admin.transporte.maestros.bus_rutas.destroy', $ruta) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rd-action rd-btn-danger"
                                                onclick="confirmAccion(event, this, 'inactivar', 'ruta')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.transporte.maestros.bus_rutas.activar', $ruta) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="rd-action rd-btn-success"
                                                onclick="confirmAccion(event, this, 'activar', 'ruta')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No hay rutas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $rutas->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@push('js')
    <script>
        function confirmAccion(event, button, accion, entidad) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Desea ${accion} la ${entidad}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Sí, ${accion}`,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) button.closest('form').submit();
            });
        }

        document.getElementById('estadoToggle').addEventListener('change', function() {
            const params = new URLSearchParams(window.location.search);
            params.set('estado', this.checked ? 1 : 0);
            window.location.href = "{{ route('admin.transporte.maestros.bus_rutas.index') }}?" + params.toString();
        });
    </script>
@endpush
