@php
    $saludKeys = ['medicamentos', 'agenda_psicologica'];
@endphp

@canMenu($saludKeys)
    <div class="pt-3 pb-1" :class="sidebarOpen ? 'block' : 'hidden'">
        <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Salud y Farmacia</span>
    </div>

    @canMenu('medicamentos')
        <a href="{{ url('admin/salud/maestros/medicamentos') }}" class="flex items-center gap-3 h-10 rounded-xl px-3 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/50 hover:text-blue-600 transition-all" :class="sidebarOpen ? 'px-3' : 'justify-center px-0'">
            <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m10.5 20.5 10-10a4.95 4.95 0 1 0-7-7l-10 10a4.95 4.95 0 1 0 7 7Z"/><path d="m8.5 8.5 7 7"/></svg>
            <span class="text-sm font-medium whitespace-nowrap" :class="sidebarOpen ? 'block' : 'hidden'">Medicamentos</span>
        </a>
    @endcanMenu

    @canMenu('agenda_psicologica')
        @if(Route::has('agenda.index'))
            <a href="{{ route('agenda.index') }}" class="flex items-center gap-3 h-10 rounded-xl px-3 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/50 hover:text-blue-600 transition-all" :class="sidebarOpen ? 'px-3' : 'justify-center px-0'">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/></svg>
                <span class="text-sm font-medium whitespace-nowrap" :class="sidebarOpen ? 'block' : 'hidden'">Agenda Psicológica</span>
            </a>
        @endif
    @endcanMenu
@endcanMenu