{{-- GRID DE TARJETAS DE BECAS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5 mb-6">

    {{-- Jornadas de Becas --}}
    @if ($visibleModules['jornada_becas'] ?? true)
        <a href="{{ url('/admin/becas/jornada') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-red-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-user-clock"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Jornadas</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">
                    {{ $total_jornadas_becas ?? 0 }} registradas
                </p>
            </div>
        </a>
    @endif

    {{-- Beneficios --}}
    @if ($visibleModules['beneficios'] ?? true)
        <a href="{{ url('/admin/becas/beneficios') }}"
           style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
           class="p-6 rounded-2xl border shadow-sm hover:shadow-md hover:border-red-500/40 transition-all text-center flex flex-col items-center justify-center gap-3 group">
            <div class="w-14 h-14 rounded-2xl bg-red-50 dark:bg-red-950/40 flex items-center justify-center text-red-600 dark:text-red-400 text-2xl group-hover:scale-110 transition-transform">
                <i class="fas fa-hand-holding-heart"></i>
            </div>
            <div>
                <h5 class="font-bold text-base mb-1" style="color: var(--text-main);">Beneficios</h5>
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 m-0">
                    {{ $total_beneficios ?? 0 }} registrados
                </p>
            </div>
        </a>
    @endif

</div>

{{-- RESUMEN DE BECAS --}}
<div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
     class="rounded-2xl p-6 border shadow-sm mb-6">
    <h5 class="font-bold text-base mb-6 flex items-center gap-2" style="color: var(--text-main);">
        📊 Resumen de Becas
    </h5>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-center">
        <div class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5">
            <div class="text-3xl font-black text-red-600 dark:text-red-500">
                {{ $total_jornadas_becas ?? 0 }}
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">
                Jornadas Creadas
            </div>
        </div>

        <div class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5">
            <div class="text-3xl font-black text-red-600 dark:text-red-500">
                {{ $total_beneficios ?? 0 }}
            </div>
            <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 mt-1">
                Beneficios Disponibles
            </div>
        </div>
    </div>
</div>
