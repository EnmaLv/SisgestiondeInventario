@php
    $moduloActivo = session('modulo_activo', 'general');
    $esPsicologia = in_array($moduloActivo, ['psicologia', 'mental']);
    $avatarBg = $esPsicologia ? 'bg-blue-600 ring-blue-400' : 'bg-red-600 ring-red-400';
@endphp

<style>
    /* Estilos Unificados para el Breadcrumb en Navbar */
    #top-navbar .breadcrumb {
        background: transparent !important;
        margin-bottom: 0 !important;
        padding: 0 !important;
        display: flex !important;
        flex-wrap: nowrap !important;
        align-items: center !important;
        font-size: 0.875rem !important;
        color: rgba(255, 255, 255, 0.7) !important;
    }

    #top-navbar .breadcrumb a {
        color: rgba(255, 255, 255, 0.7) !important;
        text-decoration: none !important;
        transition: var(--trans-default, all 0.2s ease) !important;
    }

    #top-navbar .breadcrumb a:hover {
        color: #ffffff !important;
    }

    #top-navbar .breadcrumb .breadcrumb-item.active {
        color: #ffffff !important;
        font-weight: 600 !important;
    }

    #top-navbar .breadcrumb .breadcrumb-item+.breadcrumb-item::before {
        content: "/" !important;
        padding: 0 0.5rem !important;
        color: rgba(255, 255, 255, 0.4) !important;
    }
</style>

<nav id="top-navbar" x-data="{ open: false }" class="relative z-50 border-b">
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">

            <!-- Logo, Marca & Breadcrumb -->
            <div class="flex items-center gap-3 overflow-hidden">
                <a href="{{ Route::has('home') ? route('home') : url('/') }}"
                    class="flex items-center gap-3 flex-shrink-0">
                    <img src="{{ asset('img/Logo.webp') }}" alt="Logo" class="h-8 w-auto object-contain"
                        onerror="this.style.display='none'" />
                    <span class="font-black text-lg text-white tracking-tight">Bienestar Estudiantil</span>
                </a>

                @if (class_exists(\Diglactic\Breadcrumbs\Breadcrumbs::class) && \Diglactic\Breadcrumbs\Breadcrumbs::exists())
                    <!-- Separador vertical -->
                    <span class="text-white/30 font-light hidden md:inline-block select-none">/</span>

                    <!-- Breadcrumb Renderizado -->
                    <nav aria-label="breadcrumb"
                        class="hidden md:flex items-center overflow-x-auto invisible-scrollbar">
                        {!! \Diglactic\Breadcrumbs\Breadcrumbs::render() !!}
                    </nav>
                @endif
            </div>

            <!-- Acciones Derecha -->
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-3">

                @if (View::exists('components.theme-switcher'))
                    <x-theme-switcher />
                @endif

                <!-- Menú Usuario Dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="group flex items-center gap-3 px-3 py-1.5 rounded-full hover:bg-[var(--sidebar-active-bg,#623739)] transition-all border border-white/10 focus:outline-none">
                            <div class="text-right">
                                <div class="text-sm font-semibold text-white leading-tight">
                                    {{ Auth::user()->persona?->nombre_persona ?? (Auth::user()->nombres ?? Auth::user()->name) }}
                                </div>
                                <div class="text-xs text-gray-300 leading-tight">
                                    {{ ucfirst(Auth::user()->role ?? 'Usuario') }}
                                </div>
                            </div>

                            <div class="flex-shrink-0">
                                @php
                                    $user = Auth::user();
                                    $initials = strtoupper(mb_substr($user->name ?? ($user->nombres ?? 'U'), 0, 1));
                                @endphp
                                <div
                                    class="h-9 w-9 rounded-full {{ $avatarBg }} flex items-center justify-center text-white text-sm font-black shadow-md ring-2">
                                    {{ $initials }}
                                </div>
                            </div>

                            <svg class="h-4 w-4 text-gray-300 group-hover:text-white" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @if (Route::has('profile.edit'))
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Perfil') }}
                            </x-dropdown-link>
                        @endif

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Botón Móvil -->
            <div class="-me-2 flex items-center sm:hidden gap-1">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-300 hover:text-white hover:bg-[var(--sidebar-active-bg,#623739)]">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</nav>