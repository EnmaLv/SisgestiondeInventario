@php
    $moduloActivo = strtolower(session('modulo_activo', 'general'));
    $isPsico = in_array($moduloActivo, ['psicologia', 'psicología', 'salud']);

    $hoverClass = $isPsico
        ? 'hover:bg-indigo-600/30 focus:bg-indigo-600/30'
        : 'hover:bg-[#623739] focus:bg-[#623739]';
@endphp

<a
    {{ $attributes->merge([
        'class' => "block w-full px-4 py-2 text-start text-sm leading-5 text-gray-200 hover:text-white {$hoverClass} focus:outline-none transition duration-150 ease-in-out"
    ]) }}>
    {{ $slot }}
</a>