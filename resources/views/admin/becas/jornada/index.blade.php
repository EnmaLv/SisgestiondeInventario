@extends('adminlte::page')

@section('content_header')
<div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center" style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">
    <div>
        <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
            Jornada de Becas
        </h1>
        <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
            Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
        </p>
    </div>
    <div>
        <a href="{{ url('admin/becas/jornada/create') }}" class="rd-btn rd-btn-primary">
            <i class="fas fa-plus"></i> Crear Nueva Jornada
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
                <h3 class="rd-title-sm">Jornadas Registradas</h3>
            </div>
            <div class="rd-actions">
                <form action="{{ url('admin/becas/jornada') }}" method="GET" class="rd-search-inline" role="search">

                    <input type="hidden" name="activa" value="{{ request('activa', 1) }}">

                    <input type="text" name="buscar" value="{{ request('buscar') }}" class="rd-search-input"
                        placeholder="Escriba la jornada" />
                    <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                </form>
            </div>
        </div>
        <div id="printArea">
            <table class="rd-table">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Beneficio</th>
                        <th class="text-center">Fechas Solicitud</th>
                        <th class="text-center">Cupos</th>
                        <th style="width:120px" class="text-center">Estado</th>
                        <th style="width:150px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jornadas ?? [] as $jornada)
                        <tr>
                            <td class="text-center">
                                {{ (isset($jornadas) && method_exists($jornadas, 'currentPage')) ? ($jornadas->currentPage() - 1) * $jornadas->perPage() + $loop->iteration : $loop->iteration }}
                            </td>
                            <td class="text-center">{{ $jornada->nombre_jornada }}</td>
                            <td class="text-center">{{ $jornada->beneficio->nombre_beneficio ?? 'N/A' }}</td>
                            <td class="text-center">
                                {{ $jornada->fecha_inicio_solicitud ? $jornada->fecha_inicio_solicitud->format('d/m/Y') : '' }}
                                -
                                {{ $jornada->fecha_fin_solicitud ? $jornada->fecha_fin_solicitud->format('d/m/Y') : '' }}
                            </td>
                            <td class="text-center">
                                {{ $jornada->cupos_asignados }} / {{ $jornada->cupos_maximos }}
                            </td>
                            <td class="text-center">
                                @if ($jornada->activa)
                                    <span class="rd-badge rd-badge-success">Activo</span>
                                @else
                                    <span class="rd-badge rd-badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="rd-action-group">
                                    <a href="{{ url('admin/becas/jornada/' . $jornada->id . '/edit') }}" class="rd-action"
                                        title="Editar"><i class="fas fa-edit"></i></a>
                                    @if ($jornada->activa == true)
                                        <form action="{{ url('admin/becas/jornada/' . $jornada->id) }}" method="POST"
                                            class="form-delete" style="display:inline;">
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
                                                    text: "Desea inactivar la jornada?",
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
                                        <form action="{{ url('admin/becas/jornada/' . $jornada->id . '/activar') }}"
                                            method="POST" class="form-delete" style="display:inline;">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="rd-action rd-btn-success btn-delete"
                                                onclick="confirmActivate(event, this)">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>

                                        <script>
                                            function confirmActivate(event, button) {
                                                event.preventDefault();
                                                Swal.fire({
                                                    title: '¿Estás seguro?',
                                                    text: "Desea activar la jornada?",
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
                            <td colspan="7" class="text-center py-4">No hay jornadas registradas</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if(isset($jornadas) && method_exists($jornadas, 'onEachSide'))
            <div class="mt-3 d-flex justify-content-center">
                {{ $jornadas->onEachSide(1)->links('components.pagination') }}
            </div>
        @endif
    </div>
</div>
@stop

@push('js')
    <script>
        document.getElementById('estadoToggle').addEventListener('change', function () {
            if (this.checked) {
                window.location.href = "{!! url('admin/becas/jornada') !!}?activa=1" + (new URLSearchParams(window.location.search).has('buscar') ? "&buscar=" + new URLSearchParams(window.location.search).get('buscar') : "");
            } else {
                window.location.href = "{!! url('admin/becas/jornada') !!}?activa=0" + (new URLSearchParams(window.location.search).has('buscar') ? "&buscar=" + new URLSearchParams(window.location.search).get('buscar') : "");
            }
        });
    </script>
@endpush