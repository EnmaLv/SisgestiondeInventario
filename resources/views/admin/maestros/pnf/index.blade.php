@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">

        <!-- Texto principal -->
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                PNF
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div>
            <button type="button" class="rd-btn rd-btn-primary" data-toggle="modal" data-target="#modalCrearPnf">
                <i class="fas fa-plus"></i> Crear Nuevo
            </button>
        </div>

    </div>
@stop

@section('content')
    @include('components.alert')
    <div class="rd-card rd-card-full">

        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">PNF Registrados</h3>
                </div>

                <div class="rd-actions">
                    <div class="d-flex gap-3 align-items-center">
                        <span class="font-weight-bold" style="margin-right:10px;">Filtrar por estado:</span>
                        
                        <div class="toggle-container">
                            <input type="checkbox" id="estadoToggle" class="toggle-checkbox" {{ request('activo', 1) == 1 ? 'checked' : '' }}>
                            <label for="estadoToggle" class="toggle-label">
                                <span class="toggle-inner"></span>
                                <span class="toggle-switch"></span>
                            </label>
                        </div>
                    </div>
                    <form action="{{ route('admin.maestros.pnf.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ request('buscar') ?? '' }}" class="rd-search-input"
                            placeholder="Escriba el PNF" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                </div>
            </div>
            


            {{-- Tabla --}}
            <div id="printArea">
                <table class="rd-table" >
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Nombre Del PNF</th>
                            <th style="width:120px">Estado</th>
                            <th style="width:150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pnfs as $pnf)
                            <tr>
                                <td class="text-center">
                                    {{ ($pnfs->currentPage() - 1) * $pnfs->perPage() + $loop->iteration }}</td>
                                <td>{{ $pnf->nombre_pnf }}</td>
                                <td class="text-center">
                                    @if ($pnf->id_estatus == 1)
                                        <span class="rd-badge rd-badge-success">Activo</span>
                                    @else
                                        <span class="rd-badge rd-badge-danger">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">

                                        <a href="{{ route('admin.maestros.pnf.edit', ['id'=>$pnf->id_pnf]) }}"
                                            class="rd-action" title="Editar"><i class="fas fa-edit"></i></a>

                                        @if ($pnf->id_estatus == 1)
                                            <form action="{{ route('admin.maestros.pnf.destroy', ['id'=>$pnf->id_pnf]) }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rd-action rd-action-danger btn-delete"
                                                    onclick="confirmDelete(event, this)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>

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
                                            </script>
                                        @else
                                            <form
                                                action="{{ route('admin.maestros.pnf.activar', $pnf->id_pnf) }}"
                                                method="POST" class="form-delete" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="rd-action rd-action-success btn-delete"
                                                    onclick="confirmDelete(event, this)">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>

                                            <script>
                                                function confirmDelete(event, button) {
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
                                            </script>
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

            {{-- Paginación del servidor --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $pnfs->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
    {{-- Modal Crear PNF --}}
    <div class="modal fade" id="modalCrearPnf" tabindex="-1" role="dialog" aria-labelledby="modalCrearPnfLabel" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header" style="border-bottom: 1px solid #e5e7eb;">
                    <h5 class="modal-title rd-title-sm" id="modalCrearPnfLabel">Crear un PNF</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form action="{{ route('admin.maestros.pnf.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        {{-- Nombre --}}
                        <div>
                            <label class="rd-label">Nombre del PNF</label>
                            <div class="rd-input-group">
                                <span class="rd-input-icon"><i class="fas fa-tag"></i></span>
                                <input type="text" name="nombre" class="form-control rd-filter-input" 
                                    placeholder="Ingrese el nombre" value="{{ old('nombre') }}" required>
                            </div>
                            @error('nombre')
                                <span class="rd-error">Este campo es obligatorio.</span>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="modal-footer" style="border-top: 1px solid #e5e7eb; background: #f8fafc; border-bottom-left-radius: 14px; border-bottom-right-radius: 14px;">
                        <button type="button" class="rd-btn rd-btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="rd-btn rd-btn-primary">Guardar PNF</button>
                    </div>
                </form>
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
        
        document.getElementById('estadoToggle').addEventListener('change', function() {
            if (this.checked) {
                // Activos
                window.location.href = "{{ route('admin.maestros.pnf.index', array_merge(request()->query(), ['activo' => 1])) }}";
            } else {
                // Inactivos
                window.location.href = "{{ route('admin.maestros.pnf.index', array_merge(request()->query(), ['activo' => 2])) }}";
            }
        });

    </script>

@endpush

