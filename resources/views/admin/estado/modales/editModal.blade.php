<div wire:ignore.self class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" id="modalEditar" data-modal-backdrop="static" data-modal-keyboard="false" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="relative w-full flex items-center justify-center min-h-full">
        <div class="relative w-full modal-modern" style="border-radius: 50px">
            <div class="rd-card p-4">
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Crear Nuevo Estado</h3>
                </div>
                <hr>
                <form wire:submit.prevent="update" id="formEditarEstado" class="rd-prevent-double-submit">
                    <div id="contenedorAlertaEditar"></div>
                    <div class="flex flex-wrap -mx-2">
                        <div class="w-full mb-3">
                            <label class="rd-label">Nombre del Estado</label>
                            <div class="flex items-stretch w-full">
                                <span><i class="fas fa-globe me-2"></i></span>
                                <input type="text" 
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-input" 
                                    id="nombre_estado_editar" 
                                    wire:model.defer="nombre_estado"
                                    inputmode="text"
                                    maxlength="100"
                                    placeholder="Edite el nombre del estado"
                                    required>
                                @error('nombre_estado')
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
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">  
@endsection