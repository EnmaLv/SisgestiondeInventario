<div class="main-container">

    {{-- Modales incluidos DENTRO del componente Livewire --}}
    @include('admin.localidad.modales.createModal')
    @include('admin.localidad.modales.editModal')

    {{-- Alertas --}}

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('swal', data => {
                Swal.fire({
                    icon: data.icon,
                    title: data.title,
                    text: data.text,
                    confirmButtonColor: '#7c3aed',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        });
    </script>
    
    {{-- Tarjeta moderna --}}
    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Localidades Registradas</h3>
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

            {{-- Cuerpo con tabla moderna --}}

            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr class="text-center">
                            <th>#</th>
                            <th class="text-center">Localidad</th>
                            <th class="text-center">Municipio</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="text-center">
                        {{-- SI NO HAY LOCALIDADES --}}
                        @if ($localidades->isEmpty())
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <h4>No hay localidades registradas</h4>
                                        <p>Agrega un nuevo estado con el botón superior</p>
                                    </div>
                                </td>
                            </tr>
                        @endif

                        {{-- LISTADO --}}
                        @foreach ($localidades as $index => $datos)
                            <tr>

                                {{-- Número --}}
                                <td class="text-center">{{ ($localidades->currentPage() - 1) * $localidades->perPage() + $loop->iteration }}</td></td>

                                {{-- Nombre de la localidad --}}
                                <td class="text-center">{{ $datos->nombre_localidad }}</td>

                                {{-- Nombre del municipio --}}
                                <td class="text-center">{{ $datos->municipio->nombre_municipio }}</td>

                                {{-- Estado al que pertenece --}}
                                <td class="text-center">{{ $datos->municipio->estado->nombre_estado }}</td>

                                {{-- Badge --}}
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
                                            data-bs-target="#modalEditar">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @if ($datos->status)
                                            <button
                                                class="rd-action rd-action-danger"
                                                wire:click="confirmDestroy({{ $datos->id }})"
                                                type="button"
                                                title="Eliminar"
                                            >
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        <script>
                            document.addEventListener('livewire:init', () => {

                                Livewire.on('confirm-delete', data => {
                                    Swal.fire({
                                        title: '¿Estás seguro?',
                                        text: 'Desea inactivar la localidad?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Sí, inactivar',
                                        cancelButtonText: 'Cancelar',
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            Livewire.dispatch('destroy-localidad', { id: data.id });
                                        }
                                    });
                                });

                            });
                        </script>
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Paginación --}}
        <div class="mt-3">
            {{ $localidades->onEachSide(1)->links('components.pagination-livewire') }}
        </div>
    </div>
</div>
