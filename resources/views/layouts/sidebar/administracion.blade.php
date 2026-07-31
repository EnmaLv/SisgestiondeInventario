@php
    $adminKeys = ['empleados', 'roles', 'permisos'];
@endphp

@canMenu($adminKeys)
    <div class="pt-3 pb-1" :class="sidebarOpen ? 'block' : 'hidden'">
        <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Administración</span>
    </div>

    <div class="flex flex-col">
        <button @click="activeSection = (activeSection === 'admin' ? '' : 'admin')"
            class="w-full flex items-center justify-between h-10 rounded-xl px-3 text-gray-600 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/50 hover:text-blue-600 transition-all"
            :class="sidebarOpen ? 'px-3' : 'justify-center px-0'">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                <span class="text-sm font-medium whitespace-nowrap" :class="sidebarOpen ? 'block' : 'hidden'">Usuarios & Roles</span>
            </div>
            <svg class="h-4 w-4 transition-transform" :class="[activeSection === 'admin' ? 'rotate-180' : '', sidebarOpen ? 'block' : 'hidden']" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
        </button>
        <div x-show="activeSection === 'admin' && sidebarOpen" x-collapse class="pl-9 pr-2 py-1 space-y-1">
            @canMenu('empleados')
                <a href="{{ url('admin/configuracion/empleados') }}" class="block py-1.5 px-2 text-xs rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700">Empleados</a>
            @endcanMenu
            @canMenu('roles')
                <a href="{{ url('admin/configuracion/roles') }}" class="block py-1.5 px-2 text-xs rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700">Roles</a>
            @endcanMenu
            @canMenu('permisos')
                <a href="{{ url('admin/configuracion/permisos') }}" class="block py-1.5 px-2 text-xs rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 dark:hover:bg-gray-700">Permisos</a>
            @endcanMenu
        </div>
    </div>
@endcanMenu