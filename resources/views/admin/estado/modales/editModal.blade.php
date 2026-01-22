<div wire:ignore.self class="modal fade" id="modalEditar" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern" style="border-radius: 50px">
            <div class="rd-card p-4">
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Crear Nuevo Estado</h3>
                </div>
                <hr>
                <form wire:submit.prevent="update" id="formEditarEstado" class="rd-prevent-double-submit">
                    <div id="contenedorAlertaEditar"></div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="rd-label">Nombre del Estado</label>
                            <div class="rd-input-group">
                                <span><i class="fas fa-globe me-2"></i></span>
                                <input type="text" 
                                    class="form-control rd-input" 
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
                    <div class="d-flex justify-content-end" style="gap:10px;">
                        <button type="button" class="rd-btn rd-btn-default" data-bs-dismiss="modal">
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