@extends('adminlte::page')

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
                Crear Sucursal
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>

            <div
                style="
                width:46px;
                height:46px;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 4px 12px rgba(15,23,42,0.08);
            ">
                <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>

    </div>
@stop

@section('content')
    <div class="rd-card rd-card-form">
        <div class="rd-card-body">

            {{-- Header --}}
            <div class="rd-card-header mb-3">
                <div>
                    <h3 class="rd-title-sm">Crear Sucursal</h3>
                    <small class="text-muted">Complete la información requerida</small>
                </div>

                <a href="{{ url('admin/maestros/sucursales') }}" class="rd-btn rd-btn-default">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>

            {{-- Formulario --}}
            <form action="{{ route('admin.maestros.sucursales.store') }}" method="POST">
                @csrf

                <div class="row g-3">

                    {{-- Nombre --}}
                    <div class="col-md-6">
                        <label class="rd-label">Nombre de la Sucursal</label>
                        <div class="rd-input-group">
                            <span class="rd-input-icon"><i class="fas fa-tag"></i></span>
                            <input type="text" name="nombre" class="rd-input" placeholder="Ingrese el nombre"
                                value="{{ old('nombre') }}">
                        </div>
                        @error('nombre')
                            <span class="rd-error">Este campo es obligatorio.</span>
                        @enderror
                    </div>

                    {{-- Dirección --}}
                    <div class="col-md-6">
                        <label class="rd-label">Dirección</label>
                        <div class="rd-input-group">
                            <span class="rd-input-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="direccion" class="rd-input" placeholder="Ingrese la dirección"
                                value="{{ old('direccion') }}">
                        </div>
                        @error('direccion')
                            <span class="rd-error">Este campo es obligatorio.</span>
                        @enderror
                    </div>

                    {{-- Teléfono --}}
                    <div class="col-md-6">
                        <label class="rd-label">Teléfono</label>
                        <div class="rd-input-group">
                            <span class="rd-input-icon"><i class="fas fa-phone"></i></span>
                            <input type="text" name="telefono" id="telefono" class="rd-input"
                                placeholder="(123) 456-7890" value="{{ old('telefono') }}"
                                data-inputmask="'mask': '(999) 999-9999'" data-mask>
                        </div>
                        @error('telefono')
                            <span class="rd-error">Este campo es obligatorio.</span>
                        @enderror
                    </div>

                    {{-- Estado --}}
                    <div class="col-md-6">
                        <label class="rd-label">Estado</label>
                        <div class="rd-input-group">
                            <span class="rd-input-icon"><i class="fas fa-toggle-on"></i></span>
                            <select name="activo" class="rd-input">
                                <option value="" selected disabled>Seleccione...</option>
                                <option value="1" {{ old('activo') == '1' ? 'selected' : '' }}>Activo</option>
                                <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>Inactivo</option>
                            </select>
                        </div>
                        @error('activo')
                            <span class="rd-error">Este campo es obligatorio.</span>
                        @enderror
                    </div>

                </div>

                {{-- Botones --}}
                <div class="mt-4 d-flex gap-2 justify-content-end">
                    <a href="{{ url('admin/maestros/sucursales') }}" class="rd-btn rd-btn-default">
                        Cancelar
                    </a>

                    <button type="submit" class="rd-btn rd-btn-primary">
                        Crear
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection


@section('css')
    <style>
        a:hover{
            color: inherit;
        }
        .rd-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #eef2f6;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.05);
        }

        .rd-title-sm {
            font-size: 1.2rem;
            font-weight: 700;
            margin: 0;
        }

        .rd-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .rd-input-group {
            position: relative;
        }

        .rd-input {
            width: 100%;
            padding: 10px 12px 10px 38px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            transition: .2s;
        }

        .rd-input:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, .15);
        }

        .rd-input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .rd-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: scale .2s ease;

            &:active {
                scale: .95;
            }
        }

        .rd-btn-primary {
            background: #4f46e5;
            color: white;
        }

        .rd-btn-default {
            background: transparent;
            color: #4a5568;
            border: 1px solid #e2e8f0;
            transition: background-color 0.2s ease;
        }


        .rd-btn-default:hover {
            background-color: #f8f9fa;
        }

        .rd-label {
            font-weight: 600;
            margin-bottom: 4px;
            display: block;
        }

        .rd-error {
            color: #dc2626;
            font-size: 0.85rem;
        }

        .gap-2 {
            gap: 10px;
        }
        /*Estilos de select */
        select {
            &, &::picker(select) {
                appearance: base-select;    
            }

            &>option{
                padding: 10px 0;
                margin: 0 10px;
                border-radius: 12px;
                &:checked{
                    background: hsl(0, 0%, 88%);
                }
            }

            &::picker(select){
                border: 1px solid hsl(0, 0%, 81%);
                border-radius: 12px;
                background: hsl(0, 0%, 95%);
                margin: 10px 0
            }
        }
    </style>
@endsection


@section('js')
    <script>
        $(document).ready(function() {
            $("[data-mask]").inputmask();
        });
    </script>
@endsection
