@extends('adminlte::page')

@section('title', 'Seleccionar Módulo')

@section('content')
    <div class="container py-4">

        <div class="rd-card fade-in">
            <div class="rd-card-body">

                <div class="rd-card-header border-bottom pb-3 mb-4">
                    <h4 class="rd-title-sm">
                        <i class="fas fa-layer-group me-2 text-secondary" style="color: var(--color-secondary);"></i>
                        Seleccionar módulo del sistema
                    </h4>
                </div>

                <p class="text-muted mb-4">
                    Selecciona el módulo que deseas gestionar durante la sesión actual haciendo clic directamente sobre él.
                </p>

                @php
                    // Mapeo adaptado a las llaves reales de tus módulos
                    $moduloConfig = [
                        'administracion' => ['icon' => 'fas fa-cog'],
                        'comedor' => ['icon' => 'fas fa-utensils'],
                        'salud' => ['icon' => 'fas fa-heartbeat'],
                        'beca' => ['icon' => 'fas fa-graduation-cap'],
                    ];
                    $fallback = ['icon' => 'fas fa-cubes'];
                @endphp

                <form action="{{ route('admin.modulos.cambiar') }}" method="POST">
                    @csrf

                    <div class="row">
                        @foreach ($modulos as $m)
                            @php
                                $conf = $moduloConfig[$m->key] ?? $fallback;
                                $esActivo = session('modulo_activo') == $m->key;
                            @endphp

                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                <button type="submit" name="modulo" value="{{ $m->key }}"
                                    class="rd-module-btn {{ $esActivo ? 'active-card' : '' }}">

                                    @if ($esActivo)
                                        <span class="badge rd-badge-success active-badge">
                                            <i class="fas fa-check-circle mr-1"></i> Activo
                                        </span>
                                    @endif

                                    <div class="btn-icon">
                                        <i class="{{ $conf['icon'] }}"></i>
                                    </div>

                                    <span class="btn-text font-weight-bold">{{ $m->nombre }}</span>

                                    <small class="hover-action-text">
                                        Entrar <i class="fas fa-arrow-right ml-1"></i>
                                    </small>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </form>

            </div>
        </div>

    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop
