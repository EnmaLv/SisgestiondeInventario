@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Despacho y Control de Viajes</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona ?? auth()->user()->name }}</strong>. Gestione
                los viajes activos y programados.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.transporte.maestros.bus_viajes.create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-bus"></i> Programar Nuevo Viaje
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
                    <h3 class="rd-title-sm">Listado de Viajes</h3>
                </div>
                <div class="rd-actions">
                    <!-- Filtro por Estado -->
                    <form action="{{ route('admin.transporte.maestros.bus_viajes.index') }}" method="GET" id="filterForm"
                        class="flex items-center gap-2">
                        <select name="estado" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input" style="min-width: 160px; height: 38px;"
                            onchange="this.form.submit()">
                            <option value="todos"
                                {{ request('estado') == 'todos' || !request('estado') ? 'selected' : '' }}>Todos los estados
                            </option>
                            <option value="programado" {{ request('estado') == 'programado' ? 'selected' : '' }}>Programados
                            </option>
                            <option value="en_curso" {{ request('estado') == 'en_curso' ? 'selected' : '' }}>En Curso
                            </option>
                            <option value="finalizado" {{ request('estado') == 'finalizado' ? 'selected' : '' }}>Finalizados
                            </option>
                            <option value="cancelado" {{ request('estado') == 'cancelado' ? 'selected' : '' }}>Cancelados
                            </option>
                        </select>

                        <!-- Buscador -->
                        <div class="rd-search-inline">
                            <input type="text" name="buscar" value="{{ request('buscar') }}" class="rd-search-input"
                                placeholder="Buscar bus, ruta, chofer..." />
                            <button class="rd-icon-btn" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th style="width:50px" class="text-center">#</th>
                        <th class="text-center">Vehículo</th>
                        <th class="text-center">Ruta Asignada</th>
                        <th class="text-center">Conductor</th>
                        <th class="text-center">Turno</th>
                        <th class="text-center">Inicio / Registro</th>
                        <th style="width:130px" class="text-center">Estado</th>
                        <th style="width:140px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($viajes as $viaje)
                        <tr>
                            <td class="text-center font-weight-bold">
                                {{ ($viajes->currentPage() - 1) * $viajes->perPage() + $loop->iteration }}
                            </td>

                            <!-- Unidad -->
                            <td class="text-center">
                                <span class="font-weight-bold text-dark">
                                    <i class="fas fa-bus mr-1" style="color:var(--color-primary)"></i>
                                    {{ $viaje->vehiculo->placa ?? 'N/A' }}
                                </span>
                            </td>

                            <!-- Ruta -->
                            <td class="text-center">
                                <span class="font-weight-bold" style="color:#0f172a;">
                                    {{ $viaje->ruta->nombre ?? 'N/A' }}
                                </span>
                                <br>
                                <small class="text-muted">{{ $viaje->ruta->distancia_km ?? '0' }} km</small>
                            </td>

                            <!-- Conductor -->
                            <td class="text-center">
                                @if ($viaje->conductor && $viaje->conductor->persona)
                                    {{ $viaje->conductor->persona->nombre_persona }}
                                    {{ $viaje->conductor->persona->apellido_persona }}
                                @else
                                    <span class="text-muted" style="font-size:0.85rem;">Por asignar</span>
                                @endif
                            </td>

                            <!-- Turno -->
                            <td class="text-center">
                                @if ($viaje->turno === 'mañana')
                                    <span class="rd-badge rd-badge-warning"
                                        style="background:#fef3c7; color:#d97706;">Mañana</span>
                                @elseif($viaje->turno === 'tarde')
                                    <span class="rd-badge rd-badge-info"
                                        style="background:#e0f2fe; color:#0284c7;">Tarde</span>
                                @elseif($viaje->turno === 'noche')
                                    <span class="rd-badge" style="background:#1e293b; color:#f8fafc;">Noche</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <!-- Fecha inicio -->
                            <td class="text-center" style="font-size:0.85rem;">
                                {{ $viaje->fecha_inicio ? $viaje->fecha_inicio->format('d/m/Y h:i A') : $viaje->created_at->format('d/m/Y h:i A') }}
                            </td>

                            <!-- Estado con animación para En Curso -->
                            <td class="text-center">
                                @if ($viaje->estado === 'programado')
                                    <span class="rd-badge rd-badge-warning">Programado</span>
                                @elseif($viaje->estado === 'en_curso')
                                    <span class="rd-badge rd-badge-success pulse-badge">
                                        <i class="fas fa-satellite-dish mr-1"></i> En Curso
                                    </span>
                                @elseif($viaje->estado === 'finalizado')
                                    <span class="rd-badge" style="background:#f1f5f9; color:#475569;">Finalizado</span>
                                @elseif($viaje->estado === 'cancelado')
                                    <span class="rd-badge rd-badge-danger">Cancelado</span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="text-center">
                                <div class="rd-action-group">
                                    <!-- Ver Mapa / Detalle del Viaje -->
                                    <a href="{{ route('admin.transporte.maestros.bus_viajes.show', $viaje->id) }}"
                                        class="rd-action" title="Ver Monitoreo / Detalle">
                                        <i class="fas fa-map-marked-alt"></i>
                                    </a>

                                    <a href="{{ route('admin.transporte.maestros.bus_viajes.edit', $viaje->id) }}"
                                        class="rd-action" title="Editar Viaje">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if ($viaje->estado === 'programado')
                                        <form
                                            action="{{ route('admin.transporte.maestros.bus_viajes.destroy', $viaje->id) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rd-action rd-btn-danger"
                                                onclick="confirmAccion(event, this, 'cancelar', 'este viaje')">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No hay viajes registrados con el filtro
                                seleccionado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 flex justify-center">
                {{ $viajes->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
    <style>
        /* Animación suave para el indicador En Curso */
        .pulse-badge {
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0px rgba(34, 197, 94, 0.4);
            }

            70% {
                box-shadow: 0 0 0 8px rgba(34, 197, 94, 0);
            }

            100% {
                box-shadow: 0 0 0 0px rgba(34, 197, 94, 0);
            }
        }
    </style>
@stop

@push('js')
    <script>
        function confirmAccion(event, button, accion, entidad) {
            event.preventDefault();
            Swal.fire({
                title: '¿Estás seguro?',
                text: `¿Desea ${accion} ${entidad}?`,
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
    </script>
@endpush
