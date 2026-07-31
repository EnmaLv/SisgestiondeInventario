@php
    $comedorKeys = ['recetas', 'receta_ingredientes', 'registro_comida', 'registro_diario'];
@endphp

@canMenu($comedorKeys)
    {{-- Título de la Sección --}}
    <div class="pt-2 pb-1" :class="sidebarOpen ? 'block' : 'hidden'">
        <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Gestión de Comedor</span>
    </div>

    {{-- Submenú: Recetas --}}
    @canMenu(['recetas', 'receta_ingredientes'])
    <div class="flex flex-col">
        <button @click="activeSection = (activeSection === 'recetas' ? '' : 'recetas')"
            class="w-full flex items-center justify-between h-10 rounded-xl px-3 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/50 hover:text-blue-600 transition-all"
            :class="sidebarOpen ? 'px-3' : 'justify-center px-0'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                <span class="text-sm font-medium whitespace-nowrap" :class="sidebarOpen ? 'block' : 'hidden'">Recetas y Platos</span>
            </div>
            <svg class="h-4 w-4 transition-transform" :class="[activeSection === 'recetas' ? 'rotate-180' : '', sidebarOpen ? 'block' : 'hidden']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="activeSection === 'recetas' && sidebarOpen" x-collapse class="pl-9 pr-2 py-1 space-y-1">
            @canMenu('recetas')
                <a href="{{ url('admin/maestros/recetas') }}" class="block py-1.5 px-2 text-xs rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700">Recetas</a>
            @endcanMenu
            @canMenu('receta_ingredientes')
                <a href="{{ url('admin/maestros/receta_ingredientes') }}" class="block py-1.5 px-2 text-xs rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700">Ingredientes</a>
            @endcanMenu
        </div>
    </div>
    @endcanMenu

    {{-- Submenú: Registro de Comidas --}}
    @canMenu(['registro_comida', 'registro_diario'])
    <div class="flex flex-col">
        <button @click="activeSection = (activeSection === 'registro_comida' ? '' : 'registro_comida')"
            class="w-full flex items-center justify-between h-10 rounded-xl px-3 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/50 hover:text-blue-600 transition-all"
            :class="sidebarOpen ? 'px-3' : 'justify-center px-0'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                <span class="text-sm font-medium whitespace-nowrap" :class="sidebarOpen ? 'block' : 'hidden'">Registro de Comidas</span>
            </div>
            <svg class="h-4 w-4 transition-transform" :class="[activeSection === 'registro_comida' ? 'rotate-180' : '', sidebarOpen ? 'block' : 'hidden']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="activeSection === 'registro_comida' && sidebarOpen" x-collapse class="pl-9 pr-2 py-1 space-y-1">
            @canMenu('registro_comida')
                <a href="{{ url('admin/movimientos/registro_comida') }}" class="block py-1.5 px-2 text-xs rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700">Registrar Comida</a>
            @endcanMenu
            @canMenu('registro_diario')
                <a href="{{ url('admin/movimientos/registro_diario') }}" class="block py-1.5 px-2 text-xs rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700">Registro Diario</a>
            @endcanMenu
        </div>
    </div>
    @endcanMenu
@endcanMenu