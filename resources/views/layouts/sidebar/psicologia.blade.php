@php
    $psicologiaKeys = ['enfermedades'];
@endphp

@canMenu($psicologiaKeys)
{{-- Título de Sección --}}
<div class="pt-3 pb-1" :class="sidebarOpen ? 'block' : 'hidden'">
    <span class="px-3 text-[11px] font-bold uppercase tracking-wider text-white/50">
        Psicología
    </span>
</div>

{{-- Enfermedades --}}
@canMenu('enfermedades')
<a href="{{ route('admin.enfermedades.index') }}"
    class="w-full flex items-center h-10 rounded-lg px-3 gap-2.5 text-white/90 hover:bg-[#623739] hover:text-white transition-all min-w-0"
    :class="sidebarOpen ? 'px-3' : 'justify-center px-0'" title="Enfermedades">
    <i class="fas fa-disease text-base w-5 text-center flex-shrink-0 text-white"></i>
    <span class="text-sm font-medium truncate" :class="sidebarOpen ? 'block' : 'hidden'">
        Enfermedades
    </span>
</a>
@endcanMenu

{{-- Estado de Animo --}}
@canMenu('estado_animos')
<a href="{{ route('admin.psicologia.maestros.estado_animos.index') }}"
    class="w-full flex items-center h-10 rounded-lg px-3 gap-2.5 text-white/90 hover:bg-[#623739] hover:text-white transition-all min-w-0"
    :class="sidebarOpen ? 'px-3' : 'justify-center px-0'" title="Estado de Ánimo">
    <i class="fa-solid fa-face-smile text-base w-5 text-center flex-shrink-0 text-white"></i>
    <span class="text-sm font-medium truncate" :class="sidebarOpen ? 'block' : 'hidden'">
        Estado de Ánimo
    </span>
</a>
@endcanMenu

{{-- Avances de Sesión --}}
@canMenu('avances_sesion')
<a href="{{ route('admin.psicologia.maestros.avances_sesion.index') }}"
    class="w-full flex items-center h-10 rounded-lg px-3 gap-2.5 text-white/90 hover:bg-[#623739] hover:text-white transition-all min-w-0"
    :class="sidebarOpen ? 'px-3' : 'justify-center px-0'" title="Avances de Sesión">
    <i class="fa-solid fa-chart-line text-base w-5 text-center flex-shrink-0 text-white"></i>
    <span class="text-sm font-medium truncate" :class="sidebarOpen ? 'block' : 'hidden'">
        Avances de Sesión
    </span>
</a>
@endcanMenu

@canMenu('horarios')
<a href="{{ route('admin.psicologia.maestros.horarios.index') }}"
    class="w-full flex items-center h-10 rounded-lg px-3 gap-2.5 text-white/90 hover:bg-[#623739] hover:text-white transition-all min-w-0"
    :class="sidebarOpen ? 'px-3' : 'justify-center px-0'" title="Horarios">
    <i class="fa-solid fa-clock text-base w-5 text-center flex-shrink-0 text-white"></i>
    <span class="text-sm font-medium truncate" :class="sidebarOpen ? 'block' : 'hidden'">
        Horarios
    </span>
</a>
@endcanMenu

@canMenu('grupo_horarios')
<a href="{{ route('admin.psicologia.maestros.grupos_horarios.index') }}"
    class="w-full flex items-center h-10 rounded-lg px-3 gap-2.5 text-white/90 hover:bg-[#623739] hover:text-white transition-all min-w-0"
    :class="sidebarOpen ? 'px-3' : 'justify-center px-0'" title="Grupos de Horarios">
    <i class="fa-solid fa-calendar-alt text-base w-5 text-center flex-shrink-0 text-white"></i>
    <span class="text-sm font-medium truncate" :class="sidebarOpen ? 'block' : 'hidden'">
        Grupos de Horarios
    </span>
</a>
@endcanMenu


@endcanMenu
