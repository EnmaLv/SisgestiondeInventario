<x-app-layout>
    <div class="pt-6 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @include('components.alert')

            {{-- Encabezado principal --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center gap-3"
                        style="color: var(--text-main);">
                        <span>Horarios de Consultorios</span>
                        <span
                            class="px-2.5 py-1 text-xs font-bold rounded-full bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300">
                            Gestión Semanal
                        </span>
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Bienvenido <span
                            class="font-bold text-gray-700 dark:text-gray-200">{{ auth()->user()->persona->nombre_persona }}</span>
                        ·
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.salud.movimientos.horarios.create', ['consultorio_id' => $consultorioId]) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 active:scale-95 text-white font-bold text-sm shadow-md shadow-sky-500/20 transition-all">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Asignar Horario</span>
                    </a>
                </div>
            </div>

            {{-- Selector de consultorio y Filtros dinámicos --}}
            <form id="filtro-form" action="{{ route('admin.salud.movimientos.horarios.index') }}" method="GET"
                style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="p-4 rounded-2xl border shadow-sm mb-6 flex flex-col lg:flex-row lg:items-center justify-between gap-4">

                <input type="hidden" name="estado" id="estado-input" value="{{ $estadoFilter }}">

                {{-- Selector de Consultorio --}}
                <div class="flex items-center rounded-xl border overflow-hidden focus-within:ring-2 focus-within:ring-sky-500 transition-all w-full lg:max-w-md shadow-sm"
                    style="border-color: var(--border-color);">
                    <span
                        class="flex items-center justify-center px-4 py-2.5 bg-gray-50 dark:bg-black/20 text-sky-600 dark:text-sky-400 border-r"
                        style="border-color: var(--border-color);">
                        <i class="fas fa-door-open text-base"></i>
                    </span>
                    <select name="consultorio_id" onchange="document.getElementById('filtro-form').submit()"
                        style="background-color: rgba(0,0,0,0.02); color: var(--text-main);"
                        class="w-full px-3 py-2.5 text-sm font-semibold border-none focus:ring-0 focus:outline-none transition-all cursor-pointer">
                        @forelse ($consultorios as $consultorio)
                            <option value="{{ $consultorio->id }}"
                                {{ $consultorioId === $consultorio->id ? 'selected' : '' }}>
                                {{ $consultorio->nombre }}
                            </option>
                        @empty
                            <option value="">No hay consultorios registrados</option>
                        @endforelse
                    </select>
                </div>

                <div class="flex flex-wrap items-center justify-start lg:justify-end gap-3 w-full lg:w-auto">

                    {{-- Botones de Filtro por Estado --}}
                    <div class="flex items-center gap-1.5 p-1 bg-gray-100 dark:bg-black/40 rounded-xl border"
                        style="border-color: var(--border-color);">

                        <button type="button" onclick="aplicarFiltroEstado('todos')"
                            class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $estadoFilter === 'todos' ? 'bg-white dark:bg-gray-800 text-sky-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            Todos
                        </button>

                        <button type="button" onclick="aplicarFiltroEstado('ocupado')"
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $estadoFilter === 'ocupado' ? 'bg-white dark:bg-gray-800 text-sky-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                            Ocupados
                        </button>

                        <button type="button" onclick="aplicarFiltroEstado('disponible')"
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold rounded-lg transition-all {{ $estadoFilter === 'disponible' ? 'bg-white dark:bg-gray-800 text-sky-600 shadow-sm' : 'text-gray-500 hover:text-gray-700 dark:hover:text-gray-300' }}">
                            <span class="w-2 h-2 rounded-full bg-gray-300 dark:bg-gray-600"></span>
                            Disponibles
                        </button>
                    </div>

                    {{-- Botón PDF --}}
                    <a href="#" target="_blank"
                        class="p-2 rounded-xl text-gray-500 dark:text-gray-400 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition-all flex items-center justify-center flex-shrink-0"
                        title="Imprimir Agenda en PDF">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </a>

                </div>

            </form>

            @if ($consultorios->isEmpty())
                <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                    class="rounded-2xl border shadow-sm p-12 text-center">
                    <div
                        class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-gray-400">
                        <i class="fas fa-door-closed text-2xl"></i>
                    </div>
                    <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">No hay consultorios registrados
                    </h3>
                    <p class="text-xs text-gray-400 mt-1">Registra un consultorio para comenzar a gestionar sus
                        horarios.</p>
                </div>
            @else
                {{-- cuadrilla semanal por jornada --}}
                @foreach (\App\Models\salud\HorarioConsultorio::BLOQUES as $jornada => $bloques)
                    @php
                        $jornadaIconos = [
                            'Matutino' => 'fa-sun text-amber-500',
                            'Vespertino' => 'fa-cloud-sun text-orange-500',
                            'Nocturno' => 'fa-moon text-indigo-400',
                        ];
                        $iconoJornada = $jornadaIconos[$jornada] ?? 'fa-clock text-sky-500';
                    @endphp

                    <div class="mb-8">
                        {{-- Titular de Jornada --}}
                        <div class="flex items-center gap-2 mb-3 px-1">
                            <i class="fas {{ $iconoJornada }} text-sm"></i>
                            <h3 class="text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-300">
                                Jornada {{ $jornada }}
                            </h3>
                            <div class="flex-1 h-px bg-gray-200 dark:bg-gray-800 ml-2"></div>
                        </div>

                        {{-- Tabla responsiva --}}
                        <div style="border-color: var(--border-color); background-color: var(--bg-card);"
                            class="rounded-2xl border shadow-sm overflow-x-auto">
                            <div class="grid min-w-[750px]" style="grid-template-columns: 130px repeat(5, 1fr);">

                                {{-- Header Días --}}
                                <div class="p-3 border-b border-r bg-gray-50/70 dark:bg-black/30 flex items-center justify-center font-bold text-[11px] text-gray-400 uppercase tracking-wider"
                                    style="border-color: var(--border-color);">
                                    <i class="far fa-clock mr-1.5"></i> Bloque
                                </div>
                                @foreach (\App\Models\salud\HorarioConsultorio::DIAS as $diaKey => $diaLabel)
                                    <div class="p-3 text-center text-[11px] font-black uppercase tracking-wider text-gray-600 dark:text-gray-300 border-b bg-gray-50/70 dark:bg-black/30 {{ !$loop->last ? 'border-r' : '' }}"
                                        style="border-color: var(--border-color);">
                                        {{ $diaLabel }}
                                    </div>
                                @endforeach

                                {{-- Filas por Bloque de Horario --}}
                                @foreach ($bloques as $bloque)
                                    {{-- Columna Hora --}}
                                    <div class="p-8 flex items-center justify-center text-center text-xs font-extrabold text-gray-700 dark:text-gray-200 whitespace-nowrap border-r bg-gray-50/30 dark:bg-black/10 {{ !$loop->last ? 'border-b' : '' }}"
                                        style="border-color: var(--border-color);">
                                        {{ \Carbon\Carbon::parse($bloque['inicio'])->format('g:i') }} -
                                        {{ \Carbon\Carbon::parse($bloque['fin'])->format('g:i') }}
                                    </div>

                                    {{-- Celdas por Día --}}
                                    @foreach (\App\Models\salud\HorarioConsultorio::DIAS as $diaKey => $diaLabel)
                                        @php
                                            $registro = \App\Models\salud\HorarioConsultorio::buscarRegistroEnBloque(
                                                $horarios->get($diaKey),
                                                $bloque['inicio'],
                                                $bloque['fin'],
                                            );

                                            $mostrarOcupado =
                                                $registro && in_array($estadoFilter, ['todos', 'ocupado']);
                                            $mostrarDisponible =
                                                !$registro && in_array($estadoFilter, ['todos', 'disponible']);
                                        @endphp

                                        <div class="p-2 flex items-center justify-center {{ !$loop->last ? 'border-r' : '' }} {{ !$loop->parent->last ? 'border-b' : '' }}"
                                            style="border-color: var(--border-color);">

                                            @if ($mostrarOcupado)
                                                <div
                                                    class="w-full h-full min-h-[46px] rounded-xl bg-gradient-to-r from-sky-500/10 to-sky-600/10 dark:from-sky-500/20 dark:to-sky-600/20 border border-sky-300 dark:border-sky-800 px-3 py-2 flex items-center justify-between gap-2 shadow-sm group hover:border-sky-400 transition-all">
                                                    <div class="flex items-center gap-1.5">
                                                        <span class="relative flex h-2 w-2">
                                                            <span
                                                                class="absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                                                            <span
                                                                class="relative inline-flex rounded-full h-2 w-2 bg-sky-500"></span>
                                                        </span>
                                                        <span
                                                            class="text-[11px] font-black uppercase tracking-wider text-sky-700 dark:text-sky-300">
                                                            Ocupado
                                                        </span>
                                                    </div>

                                                    <form id="form-delete-{{ $registro->id }}"
                                                        action="{{ route('admin.salud.movimientos.horarios.destroy', $registro->id) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmToggleEstado('{{ $registro->id }}', 'inactivar', 'form-delete-')"
                                                            title="Eliminar Horario"
                                                            class="w-6 h-6 flex items-center justify-center rounded-lg text-sky-400 hover:text-rose-600 hover:bg-rose-100 dark:hover:bg-rose-950/60 transition-all active:scale-90">
                                                            <i class="fas fa-trash-alt text-xs"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif ($mostrarDisponible)
                                                <div
                                                    class="w-full h-full min-h-[46px] rounded-xl border border-dashed border-gray-400 dark:border-gray-700 bg-gray-100/50 dark:bg-black/10 flex items-center justify-center text-[11px] font-bold text-gray-400 dark:text-gray-700 select-none">
                                                    Disponible
                                                </div>
                                            @else
                                                <div
                                                    class="w-full h-full min-h-[46px] rounded-xl bg-gray-50/20 dark:bg-black/5 opacity-30 flex items-center justify-center text-[10px] text-gray-600">
                                                    —
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>
    </div>

    <script>
        function aplicarFiltroEstado(estado) {
            document.getElementById('estado-input').value = estado;
            document.getElementById('filtro-form').submit();
        }
    </script>
</x-app-layout>
