{{-- GRID DE TARJETAS DE SALUD --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-6">

    {{-- Envases Primarios --}}
    @if ($visibleModules['envases_primarios'] ?? true)
        <a href="{{ url('/admin/salud/maestros/envases_primarios') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-blue-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-box"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Envases Primarios</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">
                    {{ $total_envases_primarios ?? 0 }} registrados
                </p>
            </div>
        </a>
    @endif

    {{-- Categorías de Medicamentos --}}
    @if ($visibleModules['categorias_medicamentos'] ?? true)
        <a href="{{ url('/admin/salud/maestros/categorias') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:bg-blue-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-tags"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Categorías</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">
                    {{ $total_categorias_medicamentos ?? $total_categorias ?? 0 }} registradas
                </p>
            </div>
        </a>
    @endif

    {{-- Medicamentos --}}
    @if ($visibleModules['medicamentos'] ?? true)
        <a href="{{ url('/admin/salud/maestros/medicamentos') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:bg-blue-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-pills"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Medicamentos</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">
                    {{ $total_medicamentos ?? 0 }} registrados
                </p>
            </div>
        </a>
    @endif

    {{-- Agenda Psicológica --}}
    @if (($visibleModules['agenda_psicologica'] ?? true) && Route::has('agenda.index'))
        <a href="{{ route('agenda.index') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:bg-blue-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-blue-500 dark:bg-blue-950/40 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Agenda Psicológica</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">Citas y Atención</p>
            </div>
        </a>
    @endif

</div>

{{-- RESUMEN DE SALUD Y FARMACIA --}}
<div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
     class="rounded-2xl p-6 border shadow-sm mb-6">
    <h5 class="font-bold text-base mb-6 flex items-center gap-2" style="color: var(--text-main);">
        📊 Resumen de Salud y Farmacia
    </h5>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
        <div class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5">
            <div class="text-3xl font-black text-blue-600 dark:text-blue-500">
                {{ $total_envases_primarios ?? 0 }}
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">
                Envases Primarios
            </div>
        </div>

        <div class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5">
            <div class="text-3xl font-black text-blue-600 dark:text-blue-500">
                {{ $total_categorias_medicamentos ?? $total_categorias ?? 0 }}
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">
                Categorías Registradas
            </div>
        </div>

        <div class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5">
            <div class="text-3xl font-black text-blue-600 dark:text-blue-500">
                {{ $total_medicamentos ?? 0 }}
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">
                Medicamentos en Catálogo
            </div>
        </div>
    </div>
</div>