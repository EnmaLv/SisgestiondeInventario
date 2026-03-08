@extends('adminlte::page')

@section('title','Seleccionar Módulo')

@section('content')

<div class="container py-4">

    <div class="rd-card">
        <div class="rd-card-body">

            <div class="rd-card-header">
                <h4 class="rd-title-sm">
                    <i class="fas fa-layer-group me-2"></i>
                    Seleccionar módulo del sistema
                </h4>
            </div>

            <p class="text-muted mb-4">
                Selecciona el módulo que deseas gestionar durante esta sesión.
            </p>

            <form action="{{ route('admin.modulos.cambiar') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="rd-label mb-3" for="modulo">
                        <i class="fas fa-cubes me-1"></i>
                        Módulo
                    </label>

                    <div class="rd-input-group">
                        <span>
                            <i class="fas fa-th-large"></i>
                        </span>

                        <select name="modulo" id="modulo" class="form-control rd-input-group" required>
                            <option value="" disabled selected>Selecciona un módulo</option>

                            @foreach($modulos as $m)
                                <option value="{{ $m->key }}"
                                    {{ session('modulo_activo') == $m->key ? 'selected' : '' }}>
                                    {{ $m->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button class="rd-btn rd-btn-primary">
                        <i class="fas fa-sync-alt"></i>
                        Cambiar módulo
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

@endsection