<div class="main-container">

    @include('admin.localidad.modales.createModal')
    @include('admin.localidad.modales.editModal')

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
    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Localidades Registradas</h3>
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
                            placeholder="Escriba una localidad" wire:model="search"/>
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                </div>
            </div>
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
                        @foreach ($localidades as $index => $datos)
                            <tr>
                                <td class="text-center">{{ ($localidades->currentPage() - 1) * $localidades->perPage() + $loop->iteration }}</td></td>
                                <td class="text-center">{{ $datos->nombre_localidad }}</td>
                                <td class="text-center">{{ $datos->municipio->nombre_municipio }}</td>
                                <td class="text-center">{{ $datos->municipio->estado->nombre_estado }}</td>
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
        <div class="mt-3">
            {{ $localidades->onEachSide(1)->links('components.pagination-livewire') }}
        </div>
    </div>
</div>
