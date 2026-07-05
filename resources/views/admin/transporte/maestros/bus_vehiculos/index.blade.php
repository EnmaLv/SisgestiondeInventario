@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Vehículos</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.transporte.maestros.bus_vehiculos.create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Nuevo Vehículo
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
                    <h3 class="rd-title-sm">Vehículos Registrados</h3>
                </div>
                <div class="rd-actions">
                    <div class="d-flex gap-3 align-items-center">
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
                    <form action="{{ route('admin.transporte.maestros.bus_vehiculos.index') }}" method="GET"
                        class="rd-search-inline" role="search">
                        <input type="hidden" name="activo" value="{{ request('activo', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                            class="rd-search-input" placeholder="Buscar placa o color..." />
                        <button class="rd-icon-btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th class="text-center">Placa</th>
                        <th class="text-center">Marca / Modelo</th>
                        <th class="text-center">Año</th>
                        <th class="text-center">Color</th>
                        <th class="text-center">Combustible</th>
                        <th class="text-center">Pasajeros</th>
                        <th class="text-center">Estado</th>
                        <th style="width:150px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($vehiculos as $vehiculo)
                        <tr>
                            <td class="text-center">
                                {{ ($vehiculos->currentPage() - 1) * $vehiculos->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-center"><strong>{{ $vehiculo->placa }}</strong></td>
                            <td class="text-center">
                                {{ $vehiculo->modelo->busMarca->nombre ?? '-' }} / {{ $vehiculo->modelo->nombre ?? '-' }}
                            </td>
                            <td class="text-center">{{ $vehiculo->anio }}</td>
                            <td class="text-center">{{ $vehiculo->color }}</td>
                            <td class="text-center">{{ $vehiculo->tipoCombustible->nombre ?? '-' }}</td>
                            <td class="text-center">{{ $vehiculo->cantidad_pasajeros }}</td>
                            <td class="text-center">{!! $vehiculo->estado_badge !!}</td>
                            <td class="text-center">
                                <div class="rd-action-group">
                                    <a href="{{ route('admin.transporte.maestros.bus_vehiculos.edit', $vehiculo) }}"
                                        class="rd-action" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if ($vehiculo->activo)
                                        <form action="{{ route('admin.transporte.maestros.bus_vehiculos.destroy', $vehiculo) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rd-action rd-btn-danger"
                                                onclick="confirmAccion(event, this, 'inactivar', 'vehículo')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.transporte.maestros.bus_vehiculos.activar', $vehiculo) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="rd-action rd-btn-success"
                                                onclick="confirmAccion(event, this, 'activar', 'vehículo')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $vehiculos->onEachSide(1)->links('components.pagination') }}
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
        text: `¿Desea ${accion} el ${entidad}?`,
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
    params.set('activo', this.checked ? 1 : 0);
    window.location.href = "{{ route('admin.transporte.maestros.bus_vehiculos.index') }}?" + params.toString();
});
</script>
@endpush