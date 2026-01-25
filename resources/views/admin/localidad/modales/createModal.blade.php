<div wire:ignore.self class="modal fade" id="modalCrear" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern" style="border-radius: 50px">
            <div class="rd-card p-4">
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Crear Nueva Localidad</h3>
                </div>
                <hr>
                <form wire:submit.prevent="store" id="formCrearLocalidad" class="rd-prevent-double-submit">
                    <input type="hidden" wire:model="from">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="rd-label">Estado</label>
                            <div class="rd-input-group">
                                <span><i class="fas fa-globe"></i></span>
                                <select name="estado_id" 
                                    wire:model.live="estado_id"
                                    id="estado_id" 
                                    class="form-control rd-input" 
                                    data-live-search="true"
                                    title="Seleccione un estado"
                                    required>
                                    <option value="">Seleccione un estado</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
                                    @endforeach
                                </select>
                                @error('estado_id')
                                    <div class="error-message">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="rd-label">Municipio</label>
                            <div class="rd-input-group">
                                <span><i class="fas fa-city"></i></span>
                                <select name="municipio_id" 
                                    wire:model.live="municipio_id"
                                    id="municipio_id" 
                                    class="form-control rd-input" 
                                    data-live-search="true"
                                    title="Seleccione un municipio"
                                    required>
                                    <option value="">Seleccione un municipio</option>
                                    @foreach ($municipios as $municipio)
                                        <option value="{{ $municipio->id }}">{{ $municipio->nombre_municipio }}</option>
                                    @endforeach
                                </select>
                                @error('municipio_id')
                                    <div class="error-message">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="rd-label">Nombre de la Localidad</label>
                        <div class="rd-input-group">
                            <span><i class="fas fa-home me-2"></i></span>
                            <input type="text" 
                                class="form-control rd-input" 
                                id="nombre_localidad_crear" 
                                wire:model.defer="nombre_localidad"
                                inputmode="text"
                                maxlength="100"
                                placeholder="Ingrese el nombre de la localidad"
                                required>
                            @error('nombre_localidad')
                                <div class="rd-error">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>                    
                    <div class="d-flex justify-content-end" style="gap:10px;">
                        <button type="button" class="rd-btn rd-btn-default" data-bs-dismiss="modal">
                            Cancelar
                        </button>
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn">
                            Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
