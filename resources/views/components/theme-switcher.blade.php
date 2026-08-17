@php
    $moduloActivo = strtolower(session('modulo_activo', 'general'));
    $esPsicologia = in_array($moduloActivo, ['psicologia', 'psicología', 'salud']);

    $themeOptions = [
        'light' => [
            'icon' =>
                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>',
            'label' => 'Claro',
            'description' => 'Modo de luz brillante',
        ],
        'dark' => [
            'icon' =>
                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>',
            'label' => 'Oscuro',
            'description' => 'Modo nocturno',
        ],
        'auto' => [
            'icon' =>
                '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>',
            'label' => 'Automático',
            'description' => 'Sigue al sistema',
        ],
    ];

    $dropdownBg = $esPsicologia ? 'bg-slate-900/95 border-indigo-900/60' : 'bg-[#352728] border-[#5c2028]';
    $btnHover = $esPsicologia ? 'hover:bg-indigo-600/20' : 'hover:bg-[#623739]';
    $focusRing = $esPsicologia ? 'focus:ring-indigo-500' : 'focus:ring-[#dc2626]';
    $activeClasses = $esPsicologia
        ? "['bg-indigo-600/30', 'text-white', 'border', 'border-indigo-500/50']"
        : "['bg-[#623739]', 'text-white', 'border', 'border-[#5c2028]']";
    $inactiveClasses = $esPsicologia
        ? "['text-gray-300', 'hover:bg-indigo-600/20']"
        : "['text-gray-300', 'hover:bg-[#623739]']";
@endphp

<div x-data="{ themeOpen: false }" @close-theme-dropdown.window="themeOpen = false" class="relative"
    @click.away="themeOpen = false">
    <button @click="themeOpen = !themeOpen"
        class="relative p-2 text-gray-200 hover:text-white bg-white/10 {{ $btnHover }} rounded-full transition-all duration-200 focus:outline-none focus:ring-2 {{ $focusRing }}"
        title="Cambiar tema">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
        </svg>
    </button>

    <div x-show="themeOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 -translate-y-2"
        class="absolute right-0 mt-3 w-64 {{ $dropdownBg }} backdrop-blur-md rounded-2xl shadow-2xl border z-50 overflow-hidden"
        style="display: none;">
        <div class="p-3">
            <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-2">Selecciona un tema</h3>
            <div class="space-y-1">
                @foreach ($themeOptions as $themeKey => $themeData)
                    <button id="theme-option-{{ $themeKey }}" onclick="window.changeTheme('{{ $themeKey }}');"
                        class="theme-option w-full flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 text-gray-300 {{ $btnHover }} hover:text-white">
                        <span class="text-gray-400">{!! $themeData['icon'] !!}</span>
                        <div class="flex-1 text-left">
                            <div class="text-sm font-medium">{{ $themeData['label'] }}</div>
                            <div class="text-xs text-gray-400">{{ $themeData['description'] }}</div>
                        </div>
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const activeClasses = {!! $activeClasses !!};
        const inactiveClasses = {!! $inactiveClasses !!};

        window.changeTheme = function(theme) {
            const html = document.documentElement;
            html.classList.remove('dark', 'light');

            if (theme === 'auto') {
                const isDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (isDark) html.classList.add('dark');
                localStorage.setItem('theme', 'auto');
            } else if (theme === 'dark') {
                html.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                html.classList.add('light');
                localStorage.setItem('theme', 'light');
            }

            html.setAttribute('data-theme', html.classList.contains('dark') ? 'dark' : 'light');
            html.setAttribute('data-user-theme', theme);

            updateOptionStyles(theme);
            window.dispatchEvent(new CustomEvent('close-theme-dropdown'));
        };

        function updateOptionStyles(targetTheme) {
            document.querySelectorAll('.theme-option').forEach(btn => {
                const onclickAttr = btn.getAttribute('onclick');
                if (onclickAttr) {
                    const match = onclickAttr.match(/'([^']+)'/);
                    if (match && match[1] === targetTheme) {
                        btn.classList.remove(...inactiveClasses);
                        btn.classList.add(...activeClasses);
                    } else {
                        btn.classList.remove(...activeClasses);
                        btn.classList.add(...inactiveClasses);
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'auto';
            updateOptionStyles(savedTheme);
        });
    })();
</script>