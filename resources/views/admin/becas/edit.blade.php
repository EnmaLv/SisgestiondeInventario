@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Editar Beca</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Codigo <strong>{{ $beca->codigo }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.becas.index') }}" class="rd-btn rd-btn-default">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card p-4">
        <div class="rd-card-header mb-3">
            <h3 class="rd-title-sm">Datos de la beca</h3>
        </div>
        <form action="{{ route('admin.becas.update', $beca) }}" method="POST" class="rd-prevent-double-submit">
            @csrf
            @method('PUT')

            <div class="tab-content">
                <div id="tab-config" class="tab-pane fade show active">
                    @include('admin.becas._form_fields')
                    <hr>
                    <div class="d-flex justify-content-end" style="gap:12px;">
                        <a href="{{ route('admin.becas.index') }}" class="rd-btn rd-btn-default">Cancelar</a>
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@stop

@if(session('beneficios_alerta'))
    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Beneficios modificados',
                    text: 'La configuracion de beneficios de esta beca fue actualizada.',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endpush
@endif
