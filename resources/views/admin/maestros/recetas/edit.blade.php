@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Editar Receta</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>

        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>

            <div
                style="width:46px;height:46px;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(15,23,42,0.08);">
                <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
    </div>
@stop


@section('content')
    <div class="row">
        <div class="col-md-12 m-auto">

            <div class="rd-card p-4">

                {{-- Header --}}
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Editar Receta</h3>

                    <a href="{{ url('admin/maestros/recetas') }}" class="rd-btn rd-btn-default">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                {{-- Formulario --}}
                <form action="{{ route('admin.maestros.recetas.update', $receta->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Nombre --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Nombre</label>

                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control rd-filter-input" id="nombre" name="nombre"
                                value="{{ old('nombre', $receta->nombre) }}" placeholder="Nombre de la receta">
                        </div>

                        @error('nombre')
                            <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="form-group mb-3">
                        <label class="font-weight-bold">Descripción</label>
                        <textarea name="descripcion" id="descripcion" rows="4" class="form-control rd-filter-input"
                            placeholder="Descripción de la receta" style="resize:none;">{{ old('descripcion', $receta->descripcion) }}</textarea>
                    </div>

                    <hr>

                    {{-- Botones --}}
                    <div class="d-flex justify-content-end" style="gap:12px;">
                        <a href="{{ url('admin/maestros/recetas') }}" class="rd-btn rd-btn-default">
                            Cancelar
                        </a>

                        <button type="submit" class="rd-btn rd-btn-warning" style="color:white;">
                            <i class="fas fa-save"></i> Guardar cambios
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop
