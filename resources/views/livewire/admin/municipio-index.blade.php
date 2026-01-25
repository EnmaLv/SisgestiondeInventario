<div class="main-container">

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
    @include('admin.municipio.modales.createModal')
    @include('admin.municipio.modales.editModal')
    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Municipios Registrados</h3>
                </div>

                <div class="rd-actions">
                    <div class="d-flex gap-3 align-items-center">
                        <span class="font-weight-bold" style="margin-right:10px;">Filtrar por estado:</span>
                        <div class="toggle-container">
                            <input wire:model.live="filtroEstado" type="checkbox" id="estadoToggle" class="toggle-checkbox" {{ $filtroEstado ? 'checked' : '' }}>
                            <label for="estadoToggle" class="toggle-label">
                                <span class="toggle-inner"></span>
                                <span class="toggle-switch"></span>
                            </label>
                        </div>
                    </div>
                    <form wire:submit.prevent="buscar" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ $search ?? '' }}" class="rd-search-input"
                            placeholder="Escriba un Municipio" wire:model="search"/>
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>


                </div>
            </div>

            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th class="text-center">Municipio</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($municipios as $index => $datos)
                            <tr>
                                <td class="text-center">{{ ($municipios->currentPage() - 1) * $municipios->perPage() + $loop->iteration }}</td></td>
                                <td class="text-center">{{ $datos->nombre_municipio }}</td>
                                <td class="text-center">{{ $datos->estado->nombre_estado }}</td>
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
                                <td class="text-center">
                                    <div class="rd-action-group">
                                        <button wire:click="edit({{ $datos->id }})"
                                            class="rd-action"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEditar"
                                            title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        @if ($datos->status)
                                            <button
                                                class="rd-action rd-btn-danger"
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
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-inbox"></i>
                                        </div>
                                        <h4 class="text-center">No hay municipios registrados</h4>
                                        <p class="text-center">Agrega un nuevo municipio con el botón superior</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                        <script>
                            document.addEventListener('livewire:init', () => {

                                Livewire.on('confirm-delete', data => {
                                    Swal.fire({
                                        title: '¿Estás seguro?',
                                        text: 'Desea inactivar el municipio?',
                                        icon: 'warning',
                                        showCancelButton: true,
                                        confirmButtonText: 'Sí, inactivar',
                                        cancelButtonText: 'Cancelar',
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            Livewire.dispatch('destroy-municipio', { id: data.id });
                                        }
                                    });
                                });

                            });
                        </script>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $municipios->onEachSide(1)->links('components.pagination-livewire') }}
            </div>
        </div>
    </div>
</div>