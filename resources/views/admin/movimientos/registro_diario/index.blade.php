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
                Registro Diario del Comedor
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

    <div class="rd-section-wrapper">
        {{-- Ahoremos un blur a esta seccion en el caso de que no se haya seleccionado una comida --}}
        @if (!$receta_diario)
            <div class="rd-blur">
                <div class="rd-blur-content">
                    <h2 class="rd-blur-title">Debes seleccionar una comida</h2>
                    <p class="rd-blur-text">Selecciona primero la comida del día y la cantidad servida para poder
                        registrar
                        a los estudiantes.</p>
                    <a href="{{ route('admin.movimientos.registro_comida.index') }}" type="button" class="rd-btn rd-btn-primary rd-blur-btn">Ir a la sección de comida</a>
                </div>
            </div>
        @endif

        @include('components.alert')
        <livewire:register-noti />
    </div>

@stop

@push('css')
    <style>
        .rd-section-wrapper {
            position: relative;
            width: 100%;

        }


        .rd-blur {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(15, 23, 42, 0.274);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            padding: 16px;
            border-radius: 14px
        }

        .rd-blur-content {
            max-width: 480px;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 24px 22px;
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.116);
            border: 1px solid rgba(226, 232, 240, 0.9);
            text-align: center;
        }

        .rd-blur-title {
            margin: 0 0 8px 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
        }

        .rd-blur-text {
            margin: 0 0 18px 0;
            font-size: 0.95rem;
            color: #4b5563;
        }

        .rd-blur-btn {
            padding-left: 18px;
            padding-right: 18px;
        }
    </style>
@endpush
