<!-- Modal Crear Estado -->
<div wire:ignore.self class="modal fade" id="modalCrear" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern">

            {{-- Cabecera del modal --}}
            <div class="modal-header-create">
                <div class="modal-icon-create">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h5 class="modal-title-create" id="modalCrearLabel">Nuevo Municipio</h5>
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Cuerpo del modal con formulario --}}
            <div class="modal-body-create">
                <form wire:submit.prevent="store" id="formCrearMunicipio">
                    
                    {{-- Contenedor para alertas de validación --}}
                    <div id="contenedorAlertaCrear"></div>

                    {{-- Select del estado --}}
                    <div class="form-group-modern">
                        <label for="estado_id" class="form-label-modern">
                            <i class="fas fa-tags"></i>
                            Estado
                        </label>
                        <select name="estado_id" 
                            wire:model.defer="estado_id"
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
                        <label for="nombre_municipio_crear" class="form-label-modern">
                            <i class="fas fa-hashtag me-2"></i> Nombre del Municipio
                        </label>
                        <input type="text" 
                               class="form-control-modern" 
                               id="nombre_municipio_crear" 
                               wire:model.defer="nombre_municipio"
                               inputmode="text"
                               maxlength="100"
                               placeholder="Ingrese el nombre del municipio"
                               required>
                        @error('nombre_municipio')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    {{-- Botones --}}
                    <div class="modal-footer-create">
                        <div class="footer-buttons">
                            <button type="button" class="btn-modal-cancel" data-bs-dismiss="modal">
                                Cancelar
                            </button>
                            <button type="submit" class="btn-modal-create">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>