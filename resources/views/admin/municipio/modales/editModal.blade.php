<div wire:ignore.self class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" id="modalEditar" data-modal-backdrop="static" data-modal-keyboard="false" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="relative w-full flex items-center justify-center min-h-full">
        <div class="relative w-full modal-modern" style="border-radius: 50px">
            <div class="rd-card p-4">
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Crear Nueva Localidad</h3>
                </div>
                <hr>
                <form wire:submit.prevent="update" id="formEditarEstado" class="rd-prevent-double-submit">
                    <div id="contenedorAlertaEditar"></div>
                    <div class="flex flex-wrap -mx-2">
                        <div class="w-full mb-3">
                            <label class="rd-label">Estado</label>
                            <div class="flex items-stretch w-full">
                                <span><i class="fas fa-globe"></i></span>
                                <select name="estado_id" 
                                    wire:model.live="estado_id"
                                    id="estado_id" 
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-input" 
                                    data-live-search="true"
                                    title="Seleccione un estado"
                                    required>
                                    <option value="">Seleccione un estado</option>
                                    @foreach ($estados as $estado)
                                        <option value="{{ $estado->id }}">{{ $estado->nombre_estado }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="w-full mb-3">
                            <label class="rd-label">Nombre del Municipio</label>
                            <div class="flex items-stretch w-full">
                                <span><i class="fas fa-city"></i></span>
                                <input type="text" 
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-input" 
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
                        </div>
                    </div>
                    <div class="flex justify-end" style="gap:10px;">
                        <button type="button" class="rd-btn rd-btn-default" data-modal-dismiss="modal">
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
