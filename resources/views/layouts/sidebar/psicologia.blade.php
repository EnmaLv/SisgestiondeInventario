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
@endcanMenu
