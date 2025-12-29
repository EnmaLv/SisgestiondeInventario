<!-- Modal Editar Estado -->
<div wire:ignore.self class="modal fade" id="modalEditar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern">

            {{-- Cabecera del modal --}}
            <div class="modal-header-edit">
                <div class="modal-icon-edit">
                    <i class="fas fa-pen"></i>
                </div>
                <h5 class="modal-title-edit" id="modalEditarLabel">Editar Municipio</h5>
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Cuerpo del modal con formulario --}}
            <div class="modal-body-edit">
                <form wire:submit.prevent="update" id="formEditarEstado">

                    {{-- Contenedor para alertas de validación --}}
                    <div id="contenedorAlertaEditar"></div>
                    {{-- Select del estado --}}
                    <div class="form-group-modern">
                        <label for="estado_id" class="form-label-modern">
                            <i class="fas fa-tags"></i>
                            Estado
                        </label>
                        <select name="estado_id" 
                            wire:model.live="estado_id"
                            id="estado_id" 
                            class="form-control-modern " 
                            data-live-search="true"
                            title="Seleccione un estado"
                            required>
                            <option value="">Seleccione un estado</option>
                            @foreach ($estados as $estado)
                                <option value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Nombre del Municipio --}}
                    <div class="form-group-modern">
                        <label for="nombre_municipio_editar" class="form-label-modern">
                            <i class="fas fa-hashtag me-2"></i> Nombre del Municipio
                        </label>
                        <input type="text" 
                               class="form-control-modern" 
                               id="nombre_municipio_editar" 
                               wire:model.live="nombre_municipio"
                               inputmode="text"
                               maxlength="100"
                               placeholder="Edite el nombre del municipio"
                               required>
                        @error('nombre_municipio')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="modal-footer-edit">
                        <div class="footer-buttons">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn-modal-edit">
                                Guardar Cambios
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>
    </div>
</div>
