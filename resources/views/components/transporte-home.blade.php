<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-6">
    {{-- Marcas --}}
    @if ($visibleModules['bus_marcas'] ?? false)
        <a href="{{ url('/admin/transporte/maestros/bus_marcas') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-red-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-tag"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Marcas</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">{{ $total_bus_marcas }} registradas</p>
            </div>
        </a>
    @endif

    {{-- Modelos --}}
    @if ($visibleModules['bus_modelos'] ?? false)
        <a href="{{ url('/admin/transporte/maestros/bus_modelos') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-red-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-car"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Modelos</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">{{ $total_bus_modelos }} registrados</p>
            </div>
        </a>
    @endif

    {{-- Tipos de Combustible --}}
    @if ($visibleModules['bus_tipo_combustibles'] ?? false)
        <a href="{{ url('/admin/transporte/maestros/bus_tipo_combustibles') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-red-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-gas-pump"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Tipos de Combustible</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">{{ $total_bus_tipo_combustibles }} registrados</p>
            </div>
        </a>
    @endif

    {{-- Vehículos --}}
    @if ($visibleModules['bus_vehiculos'] ?? false)
        <a href="{{ url('/admin/transporte/maestros/bus_vehiculos') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-red-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-bus"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Vehículos</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">{{ $total_bus_vehiculos }} registrados</p>
            </div>
        </a>
    @endif

    {{-- Rutas --}}
    @if ($visibleModules['bus_rutas'] ?? false)
        <a href="{{ url('/admin/transporte/maestros/bus_rutas') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-red-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-route"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Rutas</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">{{ $total_bus_rutas }} registradas</p>
            </div>
        </a>
    @endif

    {{-- Paradas --}}
    @if ($visibleModules['bus_paradas'] ?? false)
        <a href="{{ url('/admin/transporte/maestros/bus_paradas') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-red-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-map-pin"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Paradas</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">{{ $total_bus_paradas }} registradas</p>
            </div>
        </a>
    @endif
</div>

{{-- RESUMEN DE TRANSPORTE --}}
<div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
     class="rounded-2xl p-6 border shadow-sm mb-6">
    <h5 class="font-bold text-base mb-6 flex items-center gap-2" style="color: var(--text-main);">
        📊 Resumen de Transporte
    </h5>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
        <div class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5">
            <div class="text-3xl font-black text-red-600 dark:text-red-500">
                {{ $total_bus_marcas }}
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">
                Marcas Registradas
            </div>
        </div>

        <div class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5">
            <div class="text-3xl font-black text-red-600 dark:text-red-500">
                {{ $total_bus_modelos }}
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">
                Modelos Registrados
            </div>
        </div>

        <div class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5">
            <div class="text-3xl font-black text-red-600 dark:text-red-500">
                {{ $total_bus_vehiculos }}
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">
                Vehículos Registrados
            </div>
        </div>
    </div>
</div>