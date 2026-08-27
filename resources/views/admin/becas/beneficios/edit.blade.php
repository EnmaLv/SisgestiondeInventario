@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Editar Beneficio</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                {{ $beneficio->nombre_beneficio }}
            </p>
        </div>
        <a href="{{ route('admin.becas.beneficios.index') }}" class="rd-btn rd-btn-default">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card p-4">
        <div class="rd-card-header mb-3">
            <h3 class="rd-title-sm">Datos del beneficio</h3>
        </div>
        <form action="{{ route('admin.becas.beneficios.update', $beneficio) }}" method="POST" class="rd-prevent-double-submit">
            @csrf
            @method('PUT')
            @include('admin.becas.beneficios._form')
            <hr>
            <div class="flex justify-end" style="gap:12px;">
                <a href="{{ route('admin.becas.beneficios.index') }}" class="rd-btn rd-btn-default">Cancelar</a>
                <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn">
                    <i class="fas fa-save"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>
@stop
