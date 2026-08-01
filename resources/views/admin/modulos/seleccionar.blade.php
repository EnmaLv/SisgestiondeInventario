<x-app-layout>
    <x-slot name="header">
        @include('components.alert')

        {{-- CABECERA UNIFICADA DE MODULOS --}}
        <div class="dashboard-header bg-gradient-to-r from-[var(--color-primary,#c52222)] to-[var(--color-tertiary,#800000)] rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden mb-6">
            <div class="absolute -top-1/2 -right-10 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 relative z-10">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight flex items-center gap-3 m-0">
                        <i class="fas fa-layer-group"></i> Módulos del Sistema
                    </h1>
                    <p class="mt-2 mb-0 text-base opacity-95">
                        Selecciona el módulo que deseas gestionar durante la sesión actual haciendo clic directamente sobre él.
                    </p>
                </div>

                <div class="hidden md:flex items-center gap-6">
                    <div class="text-right">
                        <div class="text-xs opacity-90 mb-1">📅 Hoy es</div>
                        <div class="font-bold text-xl">
                            {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
                        </div>
                        <div class="text-xs opacity-80 capitalize">
                            {{ \Carbon\Carbon::now()->translatedFormat('l') }}
                        </div>
                    </div>

                    <div class="w-16 h-16 rounded-full overflow-hidden border-4 border-white/30 shadow-lg bg-white flex-shrink-0">
                        <img src="{{ asset('img/usuario-verificado.webp') }}" alt="Usuario" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    @php
        // Mapeo adaptado a las llaves reales de los módulos
        $moduloConfig = [
            'administracion' => ['icon' => 'fas fa-cog'],
            'comedor'        => ['icon' => 'fas fa-utensils'],
            'salud'          => ['icon' => 'fas fa-heartbeat'],
            'beca'           => ['icon' => 'fas fa-graduation-cap'],
            'transporte'     => ['icon' => 'fas fa-bus'],
        ];
        $fallback = ['icon' => 'fas fa-cubes'];
    @endphp

    {{-- GRID DE SELECCIÓN DE MÓDULOS --}}
    <form action="{{ route('admin.modulos.cambiar') }}" method="POST">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-6">
            @foreach ($modulos as $m)
                @php
                    $conf = $moduloConfig[$m->key] ?? $fallback;
                    $esActivo = session('modulo_activo') == $m->key;
                @endphp

                <button type="submit" name="modulo" value="{{ $m->key }}"
                    style="background-color: var(--bg-card); border-color: {{ $esActivo ? 'var(--color-primary, #dc2626)' : 'var(--border-color)' }}; color: var(--text-main);"
                    class="relative p-6 rounded-2xl border shadow-sm hover:shadow-lg hover:border-red-500/50 transition-all text-center flex flex-col items-center justify-center gap-3 group w-full cursor-pointer {{ $esActivo ? 'ring-2 ring-red-500/30' : '' }}">

                    {{-- Badge para módulo activo --}}
                    @if ($esActivo)
                        <span class="absolute top-3 right-3 px-2.5 py-0.5 text-[11px] font-bold rounded-full bg-red-600 text-white flex items-center gap-1 shadow-sm">
                            <i class="fas fa-check-circle"></i> Activo
                        </span>
                    @endif

                    {{-- Ícono del Módulo --}}
                    <div class="w-16 h-16 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                        <i class="{{ $conf['icon'] }}"></i>
                    </div>

                    {{-- Texto e Indicador --}}
                    <div>
                        <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">
                            {{ $m->nombre }}
                        </h5>
                        <span class="text-xs font-semibold text-red-600 dark:text-red-400 flex items-center justify-center gap-1 opacity-80 group-hover:opacity-100 group-hover:translate-x-1 transition-all">
                            Entrar <i class="fas fa-arrow-right text-[10px]"></i>
                        </span>
                    </div>
                </button>
            @endforeach
        </div>
    </form>
</x-app-layout>