@extends('adminlte::page')

@section('content_header')
    @include('components.alert')
    <div class="dashboard-header"
        style="
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-tertiary) 100%);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(197, 34, 34, 0.3);
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
         ">
        <div
            style="
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            filter: blur(40px);
        ">
        </div>

        <div class="d-flex justify-content-between align-items-center" style="position: relative; z-index: 1;">
            <div>
                <h1 class="m-0 text-white" style="font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">
                    🏠 Panel de Control
                </h1>
                <p class="mt-2 mb-0 text-white" style="font-size: 1.1rem; opacity: 0.95;">
                    Bienvenido de nuevo,
                    <strong>{{ auth()->user()->persona?->nombre_persona ?? auth()->user()->name }}</strong>
                </p>
                <p class="mb-0 text-white" style="font-size: 0.9rem; opacity: 0.8;">
                    Gestiona tu inventario de manera eficiente
                </p>
            </div>
            <div class="d-none d-md-flex align-items-center" style="gap: 1.5rem;">
                <div class="text-right text-white">
                    <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.25rem;">
                        📅 Hoy es
                    </div>
                    <div style="font-weight: 700; font-size: 1.3rem;">
                        {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
                    </div>
                    <div style="font-size: 0.85rem; opacity: 0.8;">
                        {{ \Carbon\Carbon::now()->translatedFormat('l') }}
                    </div>
                </div>

                <div
                    style="
                    width: 70px;
                    height: 70px;
                    border-radius: 50%;
                    overflow: hidden;
                    border: 4px solid rgba(255,255,255,0.3);
                    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
                    background: white;
                ">
                    <img src="{{ asset('img/usuario-verificado.webp') }}" alt="Usuario"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    @switch(session('modulo_activo'))
        @case('comedor')
            @include('components.comedor-home')
        @break

        @case('salud')
            @include('components.salud-home')
        @break

        @case('becas')
            {{-- @include('admin.dashboard.modules.becas') --}}
        @break

        @case('transporte')
            @include('components.transporte-home')
        @break

        @default
            <div class="rd-alert fade-in">
                <div class="rd-alert-icon">
                    <i class="fas fa-chart-pie"></i>
                </div>
                <div class="rd-alert-content">
                    Estadísticas en desarrollo.
                </div>
            </div>
    @endswitch
@stop

@push('css')
    @section('css')
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @stop
@endpush

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    @include('components.alert-home')
    @yield('grafica')
@endsection
