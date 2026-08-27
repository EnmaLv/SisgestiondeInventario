@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Paradas</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <a href="{{ route('admin.transporte.maestros.bus_paradas.create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i> Nueva Parada
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
                    <h3 class="rd-title-sm">Paradas Registradas</h3>
                </div>
                <div class="rd-actions">
                    <div class="flex gap-3 items-center">
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
                    <form action="{{ route('admin.transporte.maestros.bus_paradas.index') }}" method="GET"
                        class="rd-search-inline" role="search">
                        <input type="hidden" name="estado" value="{{ request('estado', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                            class="rd-search-input" placeholder="Buscar parada..." />
                        <button class="rd-icon-btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Dirección</th>
                        <th class="text-center">Coordenadas</th>
                        <th style="width:120px" class="text-center">Estado</th>
                        <th style="width:150px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paradas as $parada)
                        <tr>
                            <td class="text-center">
                                {{ ($paradas->currentPage() - 1) * $paradas->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-center">{{ $parada->nombre }}</td>
                            <td class="text-center">{{ $parada->direccion }}</td>
                            <td class="text-center">
                                <small class="text-muted">{{ $parada->lat }}, {{ $parada->lng }}</small>
                            </td>
                            <td class="text-center">
                                @if ($parada->estado)
                                    <span class="rd-badge rd-badge-success">Activa</span>
                                @else
                                    <span class="rd-badge rd-badge-danger">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="rd-action-group">
                                    <a href="{{ route('admin.transporte.maestros.bus_paradas.edit', $parada) }}" 
                                       class="rd-action" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if ($parada->estado)
                                        <form action="{{ route('admin.transporte.maestros.bus_paradas.destroy', $parada) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rd-action rd-btn-danger"
                                                onclick="confirmAccion(event, this, 'inactivar', 'parada')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.transporte.maestros.bus_paradas.activar', $parada) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="rd-action rd-btn-success"
                                                onclick="confirmAccion(event, this, 'activar', 'parada')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No hay paradas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 flex justify-center">
                {{ $paradas->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@push('js')
<script>
const CSRF = '{{ csrf_token() }}';

function toastExito(mensaje) {
    Swal.fire({
        toast: true, position: 'top-end', icon: 'success',
        title: mensaje, showConfirmButton: false,
        timer: 3000, timerProgressBar: true,
    });
}

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
        if (!result.isConfirmed) return;
        const form = button.closest('form');
        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: new FormData(form),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) { 
                button.closest('tr').remove(); 
                toastExito(res.message); 
            }
        });
    });
}

document.getElementById('estadoToggle').addEventListener('change', function() {
    const params = new URLSearchParams(window.location.search);
    params.set('estado', this.checked ? 1 : 0);
    window.location.href = "{{ route('admin.transporte.maestros.bus_paradas.index') }}?" + params.toString();
});
</script>
@endpush