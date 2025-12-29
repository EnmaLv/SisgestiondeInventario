<!-- Modal Crear Estado -->
<div wire:ignore.self class="modal fade" id="modalCrear" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-modern">

            {{-- Cabecera del modal --}}
            <div class="modal-header-create">
                <div class="modal-icon-create">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <h5 class="modal-title-create" id="modalCrearLabel">Nuevo Estado</h5>
                <button type="button" class="btn-close-modal" data-bs-dismiss="modal" aria-label="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Cuerpo del modal con formulario --}}
            <div class="modal-body-create">
                <form wire:submit.prevent="store" id="formCrearEstado">
                    
                    {{-- Contenedor para alertas de validación --}}
                    <div id="contenedorAlertaCrear"></div>

                    {{-- Nombre del Estado --}}
                    <div class="form-group-modern">
                        <label for="nombre_estado_crear" class="form-label-modern">
                            <i class="fas fa-hashtag me-2"></i> Nombre del Estado
                        </label>
                        <input type="text" 
                               class="form-control-modern" 
                               id="nombre_estado_crear" 
                               wire:model.defer="nombre_estado"
                               inputmode="text"
                               maxlength="100"
                               placeholder="Ingrese el nombre del estado"
                               required>
                        @error('nombre_estado')
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

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">  
@endsection