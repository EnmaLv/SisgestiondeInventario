@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Beneficios de Becas</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <a href="{{ route('admin.becas.beneficios.create') }}" class="rd-btn rd-btn-primary">
            <i class="fas fa-plus"></i> Registrar Beneficio
        </a>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <h3 class="rd-title-sm">Beneficios registrados</h3>
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
                    <form action="{{ route('admin.becas.beneficios.index') }}" method="GET" class="rd-search-inline" role="search">
                        <input type="hidden" name="activo" value="{{ request('activo', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}" class="rd-search-input"
                            placeholder="Nombre o descripcion">
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th class="text-center">Estado</th>
                            <th style="width:150px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($beneficios as $beneficio)
                            <tr>
                                <td class="text-center">{{ ($beneficios->currentPage() - 1) * $beneficios->perPage() + $loop->iteration }}</td>
                                <td><strong>{{ $beneficio->nombre_beneficio }}</strong></td>
                                <td>{{ $beneficio->descripcion ?: 'Sin descripcion' }}</td>
                                <td class="text-center">
                                    <span class="rd-badge {{ $beneficio->status ? 'rd-badge-success' : 'rd-badge-danger' }}">
                                        {{ $beneficio->status ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">
                                        <a href="{{ route('admin.becas.beneficios.edit', $beneficio) }}" class="rd-action" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.becas.beneficios.toggle', $beneficio) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="rd-action {{ $beneficio->status ? 'rd-btn-danger' : 'rd-btn-success' }}"
                                                title="{{ $beneficio->status ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas {{ $beneficio->status ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No hay beneficios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 flex justify-center">
                {{ $beneficios->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        document.getElementById('estadoToggle').addEventListener('change', function() {
            const activo = this.checked ? 1 : 0;
            window.location.href = "{!! route('admin.becas.beneficios.index', array_merge(request()->query(), ['activo' => '__ACTIVO__'])) !!}".replace('__ACTIVO__', activo);
        });
    </script>
@endpush
