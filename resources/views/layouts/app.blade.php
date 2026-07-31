<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-modulo="{{ session('modulo_activo', 'general') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bienestar Estudiantil') }}</title>

    <!-- 1. Script Anti-FOUC -->
    <script>
        (function() {
            const getStoredTheme = () => localStorage.getItem('theme');
            const getSystemTheme = () => window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';

            let theme = getStoredTheme() || 'auto';
            const appliedTheme = theme === 'auto' ? getSystemTheme() : theme;

            if (appliedTheme === 'dark') {
                document.documentElement.classList.add('dark');
                document.documentElement.setAttribute('data-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.setAttribute('data-theme', 'light');
            }
            document.documentElement.setAttribute('data-user-theme', theme);

            let sidebarOpen = localStorage.getItem('sidebarOpen');
            if (sidebarOpen === null) sidebarOpen = 'true';
            document.documentElement.classList.add(sidebarOpen === 'true' ? 'sidebar-expanded' : 'sidebar-collapsed');
        })();
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('css')

    <style>
        /* ========== PALETA UNIFICADA Y LIMPIA BIENESTAR ESTUDIANTIL ========== */
        :root {
            --color-primary: #dc2626;

            /* Fondo Vinotinto Uniforme en Navbar y Sidebar */
            --header-sidebar-bg: #352728;
            --header-sidebar-border: #5c2028;
            --header-sidebar-text: #ffffff;

            /* Estructura Base */
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --border-color: #e2e8f0;
            --text-main: #0f172a;
            --input-bg: #ffffff;
            --input-border: #cbd5e1;

            --trans-default: all 0.2s ease;
        }

        html.dark {
            /* Vinotinto para modo oscuro */
            --header-sidebar-bg: #352728;
            --header-sidebar-border: #5c2028;

            --bg-body: #0d0708;
            --bg-card: #160c0e;
            --border-color: #271418;
            --text-main: #f8fafc;
            --input-bg: #160c0e;
            --input-border: #271418;
        }

        /* ELIMINAR SOBREESCRITURAS DE MÓDULO */
        html[data-modulo] {
            --color-primary: #dc2626 !important;
        }

        body {
            background-color: var(--bg-body) !important;
            color: var(--text-main) !important;
        }

        /* Navbar y Sidebar compartiendo EXACTAMENTE el mismo color de fondo */
        #top-navbar,
        #main-sidebar,
        nav.top-navbar {
            background-color: var(--header-sidebar-bg) !important;
            border-color: var(--header-sidebar-border) !important;
            color: var(--header-sidebar-text) !important;
        }

        /* Menú Limpio: Sin cajas redondeadas tipo píldora (Tal cual como la segunda imagen) */
        #main-sidebar nav a,
        #main-sidebar nav button {
            background-color: transparent !important;
            color: rgba(255, 255, 255, 0.9) !important;
            border-radius: 0.5rem !important;
            margin-bottom: 0.15rem;
            padding: 0.5rem 0.75rem !important;
        }

        /* Sombra ligera solo al pasar el cursor (Hover) y al estar activo */
        #main-sidebar nav a:hover,
        #main-sidebar nav button:hover {
            background-color: #623739 !important;
            color: #ffffff !important;
        }

        #main-sidebar nav a.active,
        #main-sidebar nav button.active,
        #main-sidebar .sidebar-item-active {
            background-color: #623739 !important;
            color: #ffffff !important;
            font-weight: 600 !important;
        }

        /* Íconos blancos sin cajas traseras */
        #main-sidebar nav a svg,
        #main-sidebar nav button svg {
            color: #ffffff !important;
        }

        /* Neutralizar remanentes de clases Tailwind */
        .bg-blue-50,
        .bg-blue-100,
        .bg-blue-600,
        .dark\:bg-blue-900 {
            background-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }

        * {
            transition: var(--trans-default);
        }

        .no-transition,
        .preload,
        .preload * {
            transition: none !important;
        }

        .invisible-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .invisible-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Inputs */
        input:not([type="radio"]):not([type="checkbox"]):not([type="file"]),
        select,
        textarea {
            background-color: var(--input-bg) !important;
            border-color: var(--input-border) !important;
            color: var(--text-main) !important;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--color-primary) !important;
            outline: none;
            box-shadow: 0 0 0 2px #dc262640;
        }

        /* Sidebar Colapsable */
        html.sidebar-collapsed #main-sidebar {
            width: 4rem !important;
        }

        html.sidebar-expanded #main-sidebar {
            width: 14rem !important;
        }
    </style>
</head>

<body
    class="preload font-sans antialiased overflow-hidden bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    <div class="w-full flex flex-col overflow-hidden" style="height: 100dvh;" x-data="{ isChatOpen: false, sidebarOpen: localStorage.getItem('sidebarOpen') !== 'false' }"
        x-init="$watch('sidebarOpen', val => {
            localStorage.setItem('sidebarOpen', val);
            document.documentElement.classList.toggle('sidebar-expanded', val);
            document.documentElement.classList.toggle('sidebar-collapsed', !val);
        })">

        <header
            class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm z-40 flex-shrink-0 relative">
            @includeIf('layouts.navigation')
        </header>

        @if (session('success') || session('error') || $errors->any())
            <div id="toast" class="fixed top-6 right-6 z-50">
                <div
                    class="max-w-sm w-full {{ session('success') ? 'bg-green-600' : 'bg-red-600' }} text-white shadow-lg rounded-2xl border border-black/10 px-4 py-3">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p class="font-semibold">{{ session('success') ? '¡Listo!' : 'Atención' }}</p>
                            <p class="text-sm mt-1">{!! session('success') ?? (session('error') ?? $errors->first()) !!}</p>
                        </div>
                        <button onclick="document.getElementById('toast')?.remove()"
                            class="text-white opacity-70 hover:opacity-100">✕</button>
                    </div>
                </div>
            </div>
            <script>
                setTimeout(() => document.getElementById('toast')?.remove(), 5000);
            </script>
        @endif

        <div class="flex flex-1 overflow-hidden relative" style="min-height: 0;">
            @includeIf('layouts.sidebar')

            <main class="flex-1 overflow-y-auto invisible-scrollbar p-6 scroll-smooth">
                @isset($header)
                    <div class="max-w-7xl mx-auto mb-6">
                        {{ $header }}
                    </div>
                @endisset

                @if (isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </main>

            @if (View::exists('components.chat-window'))
                <x-chat-window />
            @endif
        </div>
    </div>

    <div id="globalAppModal"
        class="fixed inset-0 z-[200] hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 transition-all duration-200">
        <div
            class="bg-white dark:bg-gray-800 w-full max-w-sm rounded-[32px] shadow-2xl flex flex-col overflow-hidden border border-slate-100 dark:border-gray-700 p-8 text-center">
            <div id="globalAppModalIconBox"
                class="w-16 h-16 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-2xl flex items-center justify-center mb-6 mx-auto">
                <svg id="globalAppModalIconSvg" class="w-8 h-8" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <h3 id="globalAppModalTitle"
                class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase mb-2">Aviso</h3>
            <p id="globalAppModalText" class="text-sm text-slate-500 dark:text-gray-400 font-medium mb-8"></p>
            <div class="flex gap-3">
                <button id="globalAppModalCancel"
                    class="flex-1 py-4 px-6 bg-slate-100 dark:bg-gray-700 hover:bg-slate-200 text-slate-600 dark:text-gray-300 rounded-2xl font-black text-xs uppercase tracking-widest transition-colors">Cancelar</button>
                <button id="globalAppModalAccept"
                    class="flex-1 py-4 px-6 bg-red-600 hover:bg-red-700 text-white rounded-2xl font-black text-xs uppercase tracking-widest transition-all">Aceptar</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            window.AppModal = {
                show: function(title, text, options = {}) {
                    return new Promise((resolve) => {
                        const m = document.getElementById('globalAppModal');
                        document.getElementById('globalAppModalTitle').innerText = title || 'Aviso';
                        document.getElementById('globalAppModalText').innerText = text || '';
                        const y = document.getElementById('globalAppModalAccept');
                        const n = document.getElementById('globalAppModalCancel');
                        n.style.display = (options.type === 'alert') ? 'none' : 'block';
                        m.classList.remove('hidden');
                        m.classList.add('flex');

                        const cleanup = (val) => {
                            m.classList.add('hidden');
                            m.classList.remove('flex');
                            resolve(val);
                        };
                        y.onclick = () => cleanup(true);
                        n.onclick = () => cleanup(false);
                    });
                },
                confirm: function(title, text) {
                    return this.show(title, text, {
                        type: 'confirm'
                    });
                },
                alert: function(title, text) {
                    return this.show(title, text, {
                        type: 'alert'
                    });
                }
            };
        });

        window.addEventListener('load', () => setTimeout(() => document.body.classList.remove('preload'), 150));
    </script>
    @stack('scripts')
</body>

</html>
