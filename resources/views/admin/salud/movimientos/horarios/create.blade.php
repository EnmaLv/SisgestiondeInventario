<x-app-layout>
    <div class="pt-6 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @include('components.alert')

            {{-- Encabezado Principal --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-black tracking-tight flex items-center gap-3"
                        style="color: var(--text-main);">
                        <span>Asignar Horario</span>
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300">
                            Gestión de Asignación
                        </span>
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Bienvenido <span class="font-bold text-gray-700 dark:text-gray-200">{{ auth()->user()->persona->nombre_persona }}</span> ·
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.salud.movimientos.horarios.index', ['consultorio_id' => $consultorioSeleccionado]) }}"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border text-xs font-bold hover:bg-gray-50 dark:hover:bg-white/5 transition-all shadow-sm"
                        style="border-color: var(--border-color); color: var(--text-main);">
                        <i class="fas fa-arrow-left text-[10px]"></i>
                        <span>Volver</span>
                    </a>
                </div>
            </div>

            <form action="{{ route('admin.salud.movimientos.horarios.store') }}" method="POST" class="rd-prevent-double-submit">
                @csrf

                {{-- Card de Selección de Consultorio y Barra de Estado --}}
                <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                    class="p-2 sm:p-5 rounded-2xl border shadow-sm mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    
                    {{-- Selector de Consultorio --}}
                    <div class="w-full md:max-w-md">
                        <label class="block text-[13px] font-black uppercase tracking-wider text-gray-400 mb-1.5">
                            Seleccionar Consultorio
                        </label>
                        <div class="flex items-center rounded-xl border overflow-hidden focus-within:ring-2 focus-within:ring-sky-500 transition-all shadow-sm"
                            style="border-color: var(--border-color);">
                            <span class="flex items-center justify-center px-4 py-2.5 bg-gray-50 dark:bg-black/20 text-sky-600 dark:text-sky-400 border-r"
                                style="border-color: var(--border-color);">
                                <i class="fas fa-door-open text-base"></i>
                            </span>
                            <select name="consultorio_id" id="consultorio_id" required
                                style="background-color: rgba(0,0,0,0.02); color: var(--text-main);"
                                class="w-full px-3 py-2.5 text-sm font-semibold border-none focus:ring-0 focus:outline-none transition-all cursor-pointer">
                                <option value="" disabled {{ !$consultorioSeleccionado ? 'selected' : '' }}>Seleccione un consultorio</option>
                                @foreach ($consultorios as $consultorio)
                                    <option value="{{ $consultorio->id }}"
                                        {{ old('consultorio_id', $consultorioSeleccionado) == $consultorio->id ? 'selected' : '' }}>
                                        {{ $consultorio->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('consultorio_id')
                            <p class="mt-1.5 text-xs font-semibold text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Contador y Limpieza de Selección --}}
                    <div class="flex items-center justify-between md:justify-end gap-4 self-stretch md:self-end pt-2 md:pt-0">
                        <div class="px-3.5 py-2 rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-100 dark:border-sky-900/50 flex items-center gap-2">
                            <i class="fas fa-check-circle text-sky-600 dark:text-sky-400 text-xs"></i>
                            <span class="text-xs font-extrabold text-sky-700 dark:text-sky-300">
                                <span id="contadorSeleccion">0</span> bloque(s) seleccionado(s)
                            </span>
                        </div>

                        <button type="button" id="btnLimpiarSeleccion"
                            class="px-3 py-2 rounded-xl text-xs font-bold text-gray-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-transparent hover:border-rose-200 dark:hover:border-rose-900 transition-all">
                            <i class="fas fa-eraser mr-1 text-[11px]"></i> Limpiar
                        </button>
                    </div>
                </div>

                @error('horarios')
                    <div class="mb-6 px-4 py-3 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50 dark:bg-rose-950/30 text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-sm"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                @php
                    $totalJornadas = count(\App\Models\salud\HorarioConsultorio::BLOQUES);
                @endphp

                {{-- Cuadrilla de bloques por jornada --}}
                @foreach (\App\Models\salud\HorarioConsultorio::BLOQUES as $jornada => $bloques)
                    @php
                        $iconoJornada = $jornadaIconos[$jornada] ?? 'fa-clock text-sky-500';
                        $jornadaIndex = $loop->index;
                    @endphp

                    <div id="jornada-block-{{ $jornadaIndex }}" class="jornada-block mb-8 {{ $jornadaIndex !== 0 ? 'hidden' : '' }}">
                        
                        {{-- Encabezado con Recuadro Centrado y Paginación --}}
                        <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-4">
                            <div class="hidden sm:block sm:w-44"></div>

                            {{-- Nombre de Jornada en recuadro centrado --}}
                            <div class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gray-100 dark:bg-gray-800/70 border border-gray-200/80 dark:border-gray-700/60 shadow-sm text-center">
                                <i class="fas text-sm"></i>
                                <h3 class="text-xs sm:text-sm font-black uppercase tracking-wider text-gray-700 dark:text-gray-200">
                                    Jornada {{ $jornada }}
                                </h3>
                            </div>

                            {{-- Paginación --}}
                            <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                                class="flex items-center justify-between md:justify-center gap-1 border border-gray-200 dark:border-gray-700/60 p-1 h-12 rounded-2xl shadow-sm flex-shrink-0 w-full sm:w-auto">
                                <button type="button" onclick="cambiarJornada(-1)" title="Jornada Anterior"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </button>
                                
                                <span class="px-2 sm:px-4 text-[10px] sm:text-[11px] font-black text-gray-800 dark:text-gray-200 min-w-[90px] text-center uppercase tracking-wider leading-none whitespace-nowrap">
                                    {{ $loop->iteration }} / {{ $totalJornadas }}
                                </span>

                                <button type="button" onclick="cambiarJornada(1)" title="Siguiente Jornada"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all flex-shrink-0">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Tabla de Selección --}}
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
                                    {{-- Columna Hora (p-8) --}}
                                    <div class="p-8 flex items-center justify-center text-center text-xs font-extrabold text-gray-700 dark:text-gray-200 whitespace-nowrap border-r bg-gray-50/30 dark:bg-black/10 {{ !$loop->last ? 'border-b' : '' }}"
                                        style="border-color: var(--border-color);">
                                        {{ \Carbon\Carbon::parse($bloque['inicio'])->format('g:i') }} - {{ \Carbon\Carbon::parse($bloque['fin'])->format('g:i') }}
                                    </div>

                                    {{-- Celdas Seleccionables por Día --}}
                                    @foreach (\App\Models\salud\HorarioConsultorio::DIAS as $diaKey => $diaLabel)
                                        <div class="p-2 flex items-center justify-center {{ !$loop->last ? 'border-r' : '' }} {{ !$loop->parent->last ? 'border-b' : '' }}"
                                            style="border-color: var(--border-color);">
                                            
                                            <label class="bloque-card w-full h-full min-h-[46px] relative block cursor-pointer">
                                                <input type="checkbox" name="horarios[]"
                                                    value="{{ $diaKey }}|{{ $bloque['inicio'] }}|{{ $bloque['fin'] }}"
                                                    data-dia="{{ $diaKey }}" data-inicio="{{ $bloque['inicio'] }}"
                                                    data-fin="{{ $bloque['fin'] }}"
                                                    class="bloque-checkbox sr-only peer">
                                                
                                                <div class="bloque-visual w-full h-full min-h-[46px] rounded-xl border border-dashed border-gray-300 dark:border-gray-700/80 bg-gray-50/50 dark:bg-black/10 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300 transition-all peer-checked:bg-gradient-to-r peer-checked:from-sky-500/20 peer-checked:to-sky-600/10 peer-checked:dark:from-sky-500/30 peer-checked:dark:to-sky-600/20 peer-checked:border peer-checked:border-sky-300 peer-checked:dark:border-sky-800 px-3 py-2 peer-checked:hover:border-sky-400 peer-checked:dark:hover:border-sky-700 peer-checked:border-solid peer-checked:hover:border-solid select-none peer-checked:[&_.estado-disponible]:hidden peer-checked:[&_.estado-asignado]:flex">
                                                    
                                                    {{-- Estado Disponible --}}
                                                    <span class="estado-disponible flex items-center gap-1.5">
                                                        <i class="fas fa-plus text-[12px] opacity-60"></i>
                                                        <span class="text-[12px]">Disponible</span>
                                                    </span>

                                                    {{-- Estado Asignado --}}
                                                    <span class="estado-asignado hidden items-center gap-1.5 text-sky-700 dark:text-sky-300 ">
                                                        <i class="fas fa-check text-[10px]"></i>
                                                        <span class="text-[12px]">Asignado</span>
                                                    </span>

                                                </div>
                                            </label>

                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Botones de Acción --}}
                <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                    class="p-4 rounded-2xl border shadow-sm flex items-center justify-end gap-3">
                    <a href="{{ route('admin.salud.movimientos.horarios.index', ['consultorio_id' => $consultorioSeleccionado]) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl border text-xs font-bold hover:bg-gray-50 dark:hover:bg-white/5 transition-all"
                        style="border-color: var(--border-color); color: var(--text-main);">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="rd-submit-btn inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-sky-600 hover:bg-sky-700 active:scale-95 text-white font-bold text-xs shadow-md shadow-sky-500/20 transition-all">
                        <i class="fas fa-save text-xs"></i>
                        <span>Guardar Horarios</span>
                    </button>
                </div>
            </form>

        </div>
    </div>

    <script>
        let jornadaActual = 0;
        const totalJornadas = {{ count(\App\Models\salud\HorarioConsultorio::BLOQUES) }};

        function cambiarJornada(direccion) {
            const bloqueActual = document.getElementById(`jornada-block-${jornadaActual}`);
            if (bloqueActual) {
                bloqueActual.classList.add('hidden');
            }

            jornadaActual = (jornadaActual + direccion + totalJornadas) % totalJornadas;

            const siguienteBloque = document.getElementById(`jornada-block-${jornadaActual}`);
            if (siguienteBloque) {
                siguienteBloque.classList.remove('hidden');
            }
        }

        const ocupadosPorConsultorio = @json($ocupadosPorConsultorio);

        function actualizarOcupados() {
            const consultorioId = document.getElementById('consultorio_id').value;
            const lista = ocupadosPorConsultorio[consultorioId] || [];

            document.querySelectorAll('.bloque-checkbox').forEach(function(cb) {
                const key = cb.dataset.dia + '|' + cb.dataset.inicio + '|' + cb.dataset.fin;
                cb.checked = lista.includes(key);
            });

            actualizarContador();
        }

        function actualizarContador() {
            const seleccionados = document.querySelectorAll('.bloque-checkbox:checked').length;
            document.getElementById('contadorSeleccion').textContent = seleccionados;
        }

        document.getElementById('consultorio_id').addEventListener('change', actualizarOcupados);

        document.querySelectorAll('.bloque-checkbox').forEach(function(cb) {
            cb.addEventListener('change', actualizarContador);
        });

        document.getElementById('btnLimpiarSeleccion').addEventListener('click', function() {
            document.querySelectorAll('.bloque-checkbox:checked').forEach(function(cb) {
                cb.checked = false;
            });
            actualizarContador();
        });

        document.addEventListener('DOMContentLoaded', actualizarOcupados);
    </script>
</x-app-layout>