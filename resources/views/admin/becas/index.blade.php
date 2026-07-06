@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Becas Generales</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <a href="{{ route('admin.becas.create') }}" class="rd-btn rd-btn-primary">
            <i class="fas fa-plus"></i> Crear Beca
        </a>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <h3 class="rd-title-sm">Becas registradas</h3>
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
                    <form action="{{ route('admin.becas.index') }}" method="GET" class="rd-search-inline" role="search">
                        <input type="hidden" name="activo" value="{{ request('activo', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}" class="rd-search-input"
                            placeholder="Codigo o nombre">
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Codigo</th>
                            <th>Nombre</th>
                            <th>Beneficios</th>
                            <th class="text-center">Estado</th>
                            <th style="width:170px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($becas as $beca)
                            <tr>
                                <td class="text-center">{{ ($becas->currentPage() - 1) * $becas->perPage() + $loop->iteration }}</td>
                                <td><strong>{{ $beca->codigo }}</strong></td>
                                <td>{{ $beca->nombre }}</td>
                                <td>{{ $beca->beneficios->count() }}</td>
                                <td class="text-center">
                                    <span class="rd-badge {{ $beca->activo ? 'rd-badge-success' : 'rd-badge-danger' }}">
                                        {{ $beca->activo ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">
                                        <a href="{{ route('admin.becas.edit', $beca) }}" class="rd-action" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.becas.toggle', $beca) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="rd-action {{ $beca->activo ? 'rd-btn-danger' : 'rd-btn-success' }}"
                                                title="{{ $beca->activo ? 'Desactivar' : 'Activar' }}">
                                                <i class="fas {{ $beca->activo ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">No hay becas registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $becas->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop

@push('js')
    <script>
        document.getElementById('estadoToggle').addEventListener('change', function() {
            const activo = this.checked ? 1 : 0;
            window.location.href = "{!! route('admin.becas.index', array_merge(request()->query(), ['activo' => '__ACTIVO__'])) !!}".replace('__ACTIVO__', activo);
        });
    </script>
@endpush
