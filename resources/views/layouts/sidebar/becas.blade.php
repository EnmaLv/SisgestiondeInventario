{{-- MÓDULO BECAS --}}
<div class="pt-3 pb-1" :class="sidebarOpen ? 'block' : 'hidden'">
    <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Becas</span>
</div>

<a href="{{ url('admin/becas/solicitudes') }}" class="flex items-center gap-3 h-10 rounded-xl px-3 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/50 hover:text-blue-600 transition-all" :class="sidebarOpen ? 'px-3' : 'justify-center px-0'">
    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16c0 1.1.9 2 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
    <span class="text-sm font-medium whitespace-nowrap" :class="sidebarOpen ? 'block' : 'hidden'">Solicitudes</span>
</a>

<a href="{{ url('admin/becas') }}" class="flex items-center gap-3 h-10 rounded-xl px-3 text-gray-600 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/50 hover:text-green-600 transition-all mt-1" :class="sidebarOpen ? 'px-3' : 'justify-center px-0'">
    <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
    <span class="text-sm font-medium whitespace-nowrap" :class="sidebarOpen ? 'block' : 'hidden'">Crear beca</span>
</a>