@extends('adminlte::page')

@section('title', 'Gestión de Municipios')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">

        <!-- Texto principal -->
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                Municipios
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            {{-- Botón crear --}}
            <button type="button" 
                    class="rd-btn rd-btn-primary" 
                    data-bs-toggle="modal" 
                    data-bs-target="#modalCrear">
                <i class="fas fa-plus"></i>
                <span>Crear Nuevo</span>
            </button>
        </div>
    </div>
@stop


{{-- Estilos --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@section('content')
    @livewire('admin.municipio-index')
@endsection

@section('js')
    <!-- Incluir el archivo de validaciones -->
    <script src="{{ asset('js/validations/municipio.js') }}"></script>
    
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('cerrarModal', () => {
                const modales = document.querySelectorAll('.modal.show');
                modales.forEach(modal => {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                    modalInstance.hide();
                }
            });

            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());

            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    });
</script>
@stop