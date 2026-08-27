@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Crear Nueva Receta</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>

        <div class="flex items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>

            <div
                style="width:46px;height:46px;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(15,23,42,0.08);">
                <img src="{{ asset('img/usuario-verificado.webp') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="flex flex-wrap -mx-2">
        <div class="w-full mx-auto">
            <div class="rd-card p-4">
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Llenar los campos del formulario</h3>
                    <div>
                        <a href="{{ url('admin/maestros/recetas') }}" class="rd-btn rd-btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.maestros.recetas.store') }}" method="POST" class="rd-prevent-double-submit">
                    @csrf
                    <input type="hidden" name="from" value="{{ request('from') }}">
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre</label>
                        <div class="flex items-stretch w-full mb-2">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-tag"></i></span>
                            <input type="text" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input" id="nombre" name="nombre"
                                placeholder="Ingrese el nombre de la receta" value="{{ old('nombre') }}">
                        </div>
                        @error('nombre')
                            <div class="text-danger"><b>{{ $message }}</b></div>
                        @enderror
                    </div>
                    <div class="form-group mt-3">
                        <label class="font-weight-bold">Descripción</label>
                        <textarea class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input" id="descripcion" name="descripcion" rows="3"
                            placeholder="Ingrese la descripción de la receta" style="resize:none;">{{ old('descripcion') }}</textarea>
                    </div>
                    <div class="mt-4 flex justify-end gap-2">
                        <a href="{{ url('admin/maestros/recetas') }}" class="rd-btn rd-btn-default">
                            Cancelar
                        </a>
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn rd-submit-btn">
                            <i class="fas fa-check"></i> Guardar
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
