@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Viajes</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.transporte.maestros.bus_viajes.create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Nuevo Viaje
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
                    <h3 class="rd-title-sm">Viajes Registrados</h3>
                </div>
                <div class="rd-actions">
                    <form action="{{ route('admin.transporte.maestros.bus_viajes.index') }}" method="GET"
                        class="d-flex gap-3 align-items-center">
                        <select name="estado" class="form-control rd-filter-input" style="width:160px;"
                            onchange="this.form.submit()">
                            <option value="">Todos los estados</option>
                            <option value="programado"  {{ request('estado') == 'programado'  ? 'selected' : '' }}>Programado</option>
                            <option value="en_curso"    {{ request('estado') == 'en_curso'    ? 'selected' : '' }}>En Curso</option>
                            <option value="finalizado"  {{ request('estado') == 'finalizado'  ? 'selected' : '' }}>Finalizado</option>
                            <option value="cancelado"   {{ request('estado') == 'cancelado'   ? 'selected' : '' }}>Cancelado</option>
                        </select>
                        <div class="rd-search-inline">
                            <input type="text" name="buscar" value="{{ request('buscar') }}"
                                class="rd-search-input" placeholder="Buscar placa o ruta..." />
                            <button class="rd-icon-btn" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th class="text-center">Vehículo</th>
                        <th class="text-center">Ruta</th>
                        <th class="text-center">Conductor</th>
                        <th class="text-center">Turno</th>
                        <th class="text-center">Inicio</th>
                        <th class="text-center">Estado</th>
                        <th style="width:150px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($viajes as $viaje)
                        <tr>
                            <td class="text-center">
                                {{ ($viajes->currentPage() - 1) * $viajes->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-center"><strong>{{ $viaje->vehiculo->placa ?? '-' }}</strong></td>
                            <td class="text-center">{{ $viaje->ruta->nombre ?? '-' }}</td>
                            <td class="text-center">{{ $viaje->conductor->nombre_usuario ?? 'Sin conductor' }}</td>
                            <td class="text-center">
                                @if($viaje->turno)
                                    <span class="rd-badge rd-badge-success">{{ ucfirst($viaje->turno) }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                {{ $viaje->fecha_inicio ? $viaje->fecha_inicio->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="text-center">{!! $viaje->estado_badge !!}</td>
                            <td class="text-center">
                                <div class="rd-action-group">
                                    <a href="{{ route('admin.transporte.maestros.bus_viajes.edit', $viaje) }}"
                                        class="rd-action" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.transporte.maestros.bus_viajes.destroy', $viaje) }}"
                                        method="POST" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="rd-action rd-btn-danger"
                                            onclick="confirmEliminar(event, this)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">No hay viajes registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $viajes->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@push('js')
<script>
function confirmEliminar(event, button) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) button.closest('form').submit();
    });
}
</script>
@endpush