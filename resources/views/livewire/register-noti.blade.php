<div>
    <form wire:submit.prevent="save" class="d-flex justify-content-center flex-column align-items-center">
        @csrf
        <div class="d-flex align-items-center flex-wrap" >
            <label for="cedula" class="mb-0 mr-2">Cédula</label>
            <input 
                type="number" 
                wire:model="cedula" 
                id="cedula" 
                class="form-control mr-2 @error('cedula') is-invalid @enderror" 
                placeholder="Ejemplo: 12345678" 
                style="max-width: 220px;" 
                autofocus
            >
            <button type="submit" class="btn btn-primary">Buscar</button>
        </div>
        @error('cedula')
            <div class="w-100 text-center mt-2">
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            </div>
        @enderror
    </form>

    @if($showNotification)
        <div class="alert alert-{{ $notification['type'] }} alert-dismissible fade show my-5 fade-in" role="alert" 
            x-data="{ 
                show: true,
                init() {
                    // Garantizar que se muestre por lo menos 3 segundos
                    setTimeout(() => {
                        this.show = false;
                        setTimeout(() => @this.set('showNotification', false), 300);
                    }, 3000);
                }
            }" 
            x-show="show"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100">
            {{ $notification['message'] }}
            <button type="button" class="close" 
                    @click="show = false; setTimeout(() => @this.set('showNotification', false), 300)" 
                    aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>

@push('js')
<script>

    document.addEventListener('livewire:initialized', () => {
        let isNotificationVisible = false;
        let hideTimeout = null;
        
        @this.on('notify-saved', () => {
            // Si ya hay una notificación visible, no hacer nada
            if (isNotificationVisible) {
                return;
            }
            
            isNotificationVisible = true;
            
            // Ocultar después de 3 segundos
            hideTimeout = setTimeout(() => {
                @this.set('showNotification', false);
                isNotificationVisible = false;
            }, 3000);
        });
        
        // Limpiar el estado cuando la notificación se oculta
        @this.on('notify-hidden', () => {
            isNotificationVisible = false;
            if (hideTimeout) {
                clearTimeout(hideTimeout);
            }
        });
    });
    
    // Evento para el input de cédula
    const inputCedula = document.getElementById('cedula');

    //Focus al input
    inputCedula.focus();

    //escuchar el click en cualquier parte del documento para no perder el focus
    document.querySelector('.content-wrapper').addEventListener('click', function() {
        inputCedula.focus();
    });

    //Re-enfocar si la ventana vuelve a estar activa (ej. alt-tab y volver)
    window.addEventListener('focus', function() {
        inputCedula.focus();
    });
    
    //Limites del input
    inputCedula.addEventListener('input', function(e) {
        // Remover caracteres no numéricos
        this.value = this.value.replace(/[^0-9]/g, '');
        
        // Limitar a 8 dígitos máximo
        if (this.value.length > 8) {
            this.value = this.value.slice(0, 8);
        }
    });
</script>
@endpush

@push('css')
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fade-in 0.5s ease-in-out;
        }
    </style>
@endpush
    