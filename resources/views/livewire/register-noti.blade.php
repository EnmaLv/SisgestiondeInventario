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
        <div class="alert alert-{{ $notification['type'] }} alert-dismissible fade show my-5 fade-in" role="alert" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            {{ $notification['message'] }}
            <button type="button" class="close" @click="show = false" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
</div>

@push('js')
<script>

    document.addEventListener('livewire:initialized', () => {
        @this.on('notify-saved', () => {
            setTimeout(() => {
                @this.set('showNotification', false);
            }, 3000);
        });
    });
    
    document.getElementById('cedula').addEventListener('input', function(e) {
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
    