@extends('adminlte::page')

@section('title','Seleccionar Módulo')

@section('content')
<div class="container py-4">
    <h3>Seleccionar Módulo</h3>
    <p>Selecciona el módulo que quieres gestionar en esta sesión.</p>

    <form action="{{ route('admin.modulos.cambiar') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="modulo">Módulo</label>
            <select name="modulo" id="modulo" class="form-control" required>
                <option value="" disabled selected>-- Selecciona un módulo --</option>
                @foreach($modulos as $m)
                    <option value="{{ $m->key }}" {{ session('modulo_activo') == $m->key ? 'selected' : '' }}>
                        {{ $m->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary mt-2">Cambiar módulo</button>
    </form>
</div>
@endsection