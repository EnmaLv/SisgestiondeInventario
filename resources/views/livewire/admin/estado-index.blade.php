<div class="main-container">

    {{-- Modales incluidos DENTRO del componente Livewire --}}
    @include('admin.estado.modales.createModal')
    @include('admin.estado.modales.editModal')

    {{-- Alertas --}}
    @if (session('success') || session('error'))
        <div class="alerts-container">
            @if (session('success'))
                <div class="alert-modern alert-success alert alert-dismissible fade show">
                    <div class="alert-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Éxito</h4>
                        <p>{{ session('success') }}</p>
                    </div>
                    <button type="button" class="alert-close btn-close" data-bs-dismiss="alert">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert-modern alert-error alert alert-dismissible fade show">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <h4>Error</h4>
                        <p>{{ session('error') }}</p>
                    </div>
                    <button type="button" class="alert-close btn-close" data-bs-dismiss="alert">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif
        </div>
    @endif

    @include('components.alert')
    <div class="rd-card rd-card-full">

        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Estados Registrados</h3>
                </div>

                <div class="rd-actions">
                    <form action="{{ route('admin.maestros.categorias.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Escriba la categoria" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>

                </div>
            </div>

            <div class="collapse" id="filters">
                <div class="rd-filters">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <span class="font-weight-bold" style="margin-right:5px; ">Filtrar por estado:</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.maestros.categorias.index', array_merge(request()->query(), ['estado' => 1])) }}"
                                class="btn {{ request('estado', 1) == 1 ? 'btn-primary' : 'btn-outline-primary' }}">
                                Activos
                            </a>
                            <a href="{{ route('admin.maestros.categorias.index', array_merge(request()->query(), ['estado' => 0])) }}"
                                class="btn {{ request('estado', 1) == 0 ? 'btn-danger' : 'btn-outline-danger' }}">
                                Inactivos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Estado</th>
                            <th style="width:150px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estados as $index => $datos)
                            <tr>
                                <td class="text-center">{{ ($estados->currentPage() - 1) * $estados->perPage() + $loop->iteration }}</td></td>
                                <td class="text-center">{{ $datos->nombre_estado }}</td>
                                <td class="text-center">
                                    @if ($datos->status)
                                        <span class="status-badge status-active">
                                            <span class="status-dot"></span> Activo
                                        </span>
                                    @else
                                        <span class="status-badge status-inactive">
                                            <span class="status-dot"></span> Inactivo
                                        </span>
                                    @endif
                                </td>

                                {{-- ACCIONES --}}
                                <td class="text-center">
                                    <div class="rd-action-group">
                                        {{-- Botón Editar --}}
                                        <button wire:click="edit({{ $datos->id }})"
                                            class="rd-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        {{-- Botón Eliminar --}}
                                        @if ($datos->status == true)
                                            <form action="{{ url('admin/maestros/categorias/' . $datos->id) }}"
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
                                                        text: "Desea inactivar la categoria?",
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
                                                action="{{ url('admin/maestros/categorias/' . $datos->id . '/activar') }}"
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
                                                        text: "Desea activar la categoria?",
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Paginación del servidor --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $estados->onEachSide(1)->links('components.pagination-livewire') }}
            </div>
        </div>
    </div>
</div>