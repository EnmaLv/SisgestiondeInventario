@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Crear Nueva Receta</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
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

                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Llenar los campos del formulario</h3>

                    <div>
                        <a href="{{ url('admin/maestros/recetas') }}" class="rd-btn rd-btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.maestros.recetas.store') }}" method="POST">
                    @csrf

                    {{-- Campo Nombre --}}
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                            <input type="text" class="form-control rd-filter-input" id="nombre" name="nombre"
                                placeholder="Ingrese el nombre de la receta" value="{{ old('nombre') }}">
                        </div>
                        @error('nombre')
                            <div class="text-danger"><b>{{ $message }}</b></div>
                        @enderror
                    </div>

                    {{-- Campo Descripción --}}
                    <div class="form-group mt-3">
                        <label class="font-weight-bold">Descripción</label>
                        <textarea class="form-control rd-filter-input" id="descripcion" name="descripcion" rows="3"
                            placeholder="Ingrese la descripción de la receta" style="resize:none;">{{ old('descripcion') }}</textarea>
                    </div>

                    <div class="mt-4 d-flex justify-content-between">
                        <a href="{{ url('admin/maestros/recetas') }}" class="rd-btn rd-btn-default">
                            Cancelar
                        </a>

                        <button type="submit" class="rd-btn rd-btn-primary">
                            <i class="fas fa-check"></i> Crear
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
@push('css')
    <style>
        .rd-card .input-group {
            border: 1px solid #d8dee9;
            border-radius: 12px;
            padding-inline: 8px;
            transition: border-color .2s ease, box-shadow .2s ease;
            overflow: hidden;
        }

        .rd-card .input-group:focus-within {
            border-color: #7c3aed;
            background: #ffffff;
        }

        .rd-card .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 1.05rem;
            padding-left: 4px;
            padding-right: 4px;
        }

        .rd-card .input-group-text i {
            width: 22px;
            text-align: center;
        }

        .rd-card .rd-filter-input,
        .rd-card .form-control {
            border: none;
            background: transparent;
            box-shadow: none;
            padding-left: 6px;
        }

        .rd-card textarea.form-control {
            border: 1px solid #d8dee9;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
            min-height: 120px;
            resize: vertical;
        }

        .rd-card textarea.form-control:focus {
            border-color: #7c3aed;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
            outline: none;
        }

        /* Para el caso de textarea dentro de un input-group */
        .rd-card .input-group textarea.form-control {
            border: none;
            background: transparent;
            box-shadow: none;
            padding-left: 6px;
            min-height: 38px;
            resize: none;
        }

        .rd-card .input-group:focus-within textarea.form-control {
            background: transparent;
        }
    </style>
@endpush