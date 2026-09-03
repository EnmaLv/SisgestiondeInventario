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
                        <span
                            class="px-2.5 py-1 text-xs font-bold rounded-full bg-sky-100 text-sky-700 dark:bg-sky-950/60 dark:text-sky-300">
                            Gestión Múltiple
                        </span>
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Bienvenido <span
                            class="font-bold text-gray-700 dark:text-gray-200">{{ auth()->user()->persona->nombre_persona ?? auth()->user()->username }}</span>
                        ·
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

            <form action="{{ route('admin.salud.movimientos.horarios.store') }}" method="POST"
                class="rd-prevent-double-submit">
                @csrf

                {{-- Card de Selección de Consultorio y Barra de Estado --}}
                <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                    class="p-2 sm:p-5 rounded-2xl border shadow-sm mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">

                    {{-- Selector de Consultorio --}}
                    <div class="w-full md:max-w-md">
                        <label class="block text-[13px] font-black uppercase tracking-wider text-gray-400 mb-1.5">
                            Seleccionar Consultorio
                        </label>
                        <div class="flex items-center rounded-xl border overflow-hidden focus-within:ring-2 focus-within:ring-sky-500 transition-all shadow-sm"
                            style="border-color: var(--border-color);">
                            <span
                                class="flex items-center justify-center px-4 py-2.5 bg-gray-50 dark:bg-black/20 text-sky-600 dark:text-sky-400 border-r"
                                style="border-color: var(--border-color);">
                                <i class="fas fa-door-open text-base"></i>
                            </span>
                            <select name="consultorio_id" id="consultorio_id" required
                                style="background-color: rgba(0,0,0,0.02); color: var(--text-main);"
                                class="w-full px-3 py-2.5 text-sm font-semibold border-none focus:ring-0 focus:outline-none transition-all cursor-pointer">
                                <option value="" disabled {{ !$consultorioSeleccionado ? 'selected' : '' }}>
                                    Seleccione un consultorio</option>
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
                    <div
                        class="flex items-center justify-between md:justify-end gap-4 self-stretch md:self-end pt-2 md:pt-0">
                        <div
                            class="px-3.5 py-2 rounded-xl bg-sky-50 dark:bg-sky-950/40 border border-sky-100 dark:border-sky-900/50 flex items-center gap-2">
                            <i class="fas fa-check-circle text-sky-600 dark:text-sky-400 text-xs"></i>
                            <span class="text-xs font-extrabold text-sky-700 dark:text-sky-300">
                                <span id="contadorSeleccion">0</span> asignación(es) realizada(s)
                            </span>
                        </div>

                        <button type="button" id="btnLimpiarSeleccion"
                            class="px-3 py-2 rounded-xl text-xs font-bold text-gray-500 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 border border-transparent hover:border-rose-200 dark:hover:border-rose-900 transition-all">
                            <i class="fas fa-eraser mr-1 text-[11px]"></i> Limpiar Todo
                        </button>
                    </div>
                </div>

                @error('horarios')
                    <div
                        class="mb-6 px-4 py-3 rounded-xl border border-rose-200 dark:border-rose-900/60 bg-rose-50 dark:bg-rose-950/30 text-xs font-bold text-rose-600 dark:text-rose-400 flex items-center gap-2">
                        <i class="fas fa-exclamation-circle text-sm"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                {{-- Contenedor Principal en Grid de 2 Columnas --}}
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">

                    {{-- Lista Lateral de Personal Disponible --}}
                    <div class="lg:col-span-1">
                        <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                            class="p-4 rounded-2xl border shadow-sm sticky top-6">

                            <div class="flex items-center justify-between mb-3">
                                <h3
                                    class="text-xs font-black uppercase tracking-wider text-gray-700 dark:text-gray-200 flex items-center gap-2">
                                    <i class="fas fa-user-md text-sky-500"></i>
                                    <span>Personal Disponible</span>
                                </h3>
                                <span
                                    class="px-2 py-0.5 text-[10px] font-extrabold rounded-full bg-sky-100 text-sky-700 dark:bg-sky-950 dark:text-sky-300">
                                    {{ count($usuariosElegibles) }}
                                </span>
                            </div>

                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mb-3">
                                Arrastra uno o varios usuarios hacia los bloques de la tabla.
                            </p>

                            {{-- Buscador de Usuarios --}}
                            <div class="relative mb-3">
                                <i class="fas fa-search absolute left-3 top-2.5 text-xs text-gray-400"></i>
                                <input type="text" id="searchUser" placeholder="Buscar personal..."
                                    class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-black/20 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-1 focus:ring-sky-500">
                            </div>

                            {{-- Tarjetas Draggable --}}
                            <div id="usersList" class="space-y-2 max-h-[460px] overflow-y-auto pr-1">
                                @forelse ($usuariosElegibles as $usr)
                                    @php
                                        // Nombre obtenido directamente desde Usuario -> id_persona -> Persona
                                        $displayNombre = $usr->nombre_completo;
                                        $rolNombre = $usr->roles->pluck('nombre')->first() ?? 'Sin rol';
                                    @endphp

                                    <div class="user-card flex items-center gap-2.5 p-2.5 rounded-xl border border-gray-200 dark:border-gray-700/80 bg-white dark:bg-gray-800/60 hover:border-sky-400 cursor-grab transition-all select-none"
                                        draggable="true" ondragstart="handleDragStart(event)"
                                        data-id="{{ $usr->id_usuario }}" data-nombre="{{ $displayNombre }}">

                                        <div
                                            class="w-7 h-7 rounded-lg bg-sky-100 dark:bg-sky-900/50 text-sky-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            <i class="fas fa-user text-[11px]"></i>
                                        </div>

                                        <div class="overflow-hidden min-w-0 flex-1">
                                            <p
                                                class="user-card-name text-xs font-bold text-gray-800 dark:text-gray-200 truncate">
                                                {{ $displayNombre }}
                                            </p>
                                            <p class="text-[10px] font-medium text-sky-600 dark:text-sky-400 truncate">
                                                {{ $rolNombre }}
                                            </p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-xs text-gray-400">
                                        No hay usuarios disponibles.
                                    </div>
                                @endforelse
                            </div>

                        </div>
                    </div>

                    {{-- Tabla de Horarios --}}
                    <div class="lg:col-span-3">
                        @php
                            $totalJornadas = count(\App\Models\salud\HorarioConsultorio::BLOQUES);
                        @endphp

                        @foreach (\App\Models\salud\HorarioConsultorio::BLOQUES as $jornada => $bloques)
                            @php
                                $jornadaIndex = $loop->index;
                            @endphp

                            <div id="jornada-block-{{ $jornadaIndex }}"
                                class="jornada-block {{ $jornadaIndex !== 0 ? 'hidden' : '' }}">

                                {{-- Encabezado con Recuadro Centrado y Paginación --}}
                                <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-4">
                                    <div class="hidden sm:block sm:w-44"></div>

                                    <div
                                        class="flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-gray-100 dark:bg-gray-800/70 border border-gray-200/80 dark:border-gray-700/60 shadow-sm text-center">
                                        <h3
                                            class="text-xs sm:text-sm font-black uppercase tracking-wider text-gray-700 dark:text-gray-200">
                                            Jornada {{ $jornada }}
                                        </h3>
                                    </div>

                                    <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                                        class="flex items-center justify-between md:justify-center gap-1 border border-gray-200 dark:border-gray-700/60 p-1 h-12 rounded-2xl shadow-sm flex-shrink-0 w-full sm:w-auto">
                                        <button type="button" onclick="cambiarJornada(-1)" title="Jornada Anterior"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </button>

                                        <span
                                            class="px-2 sm:px-4 text-[10px] sm:text-[11px] font-black text-gray-800 dark:text-gray-200 min-w-[90px] text-center uppercase tracking-wider leading-none whitespace-nowrap">
                                            {{ $loop->iteration }} / {{ $totalJornadas }}
                                        </span>

                                        <button type="button" onclick="cambiarJornada(1)" title="Siguiente Jornada"
                                            class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-white dark:hover:bg-gray-700 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-all flex-shrink-0">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                {{-- Tabla de Selección y Drop Zone --}}
                                <div style="border-color: var(--border-color); background-color: var(--bg-card);"
                                    class="rounded-2xl border shadow-sm overflow-x-auto">
                                    <div class="grid min-w-[700px]"
                                        style="grid-template-columns: 120px repeat(5, 1fr);">

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
                                            <div class="p-4 flex items-center justify-center text-center text-[11px] font-extrabold text-gray-700 dark:text-gray-200 whitespace-nowrap border-r bg-gray-50/30 dark:bg-black/10 {{ !$loop->last ? 'border-b' : '' }}"
                                                style="border-color: var(--border-color);">
                                                {{ \Carbon\Carbon::parse($bloque['inicio'])->format('g:i') }} -
                                                {{ \Carbon\Carbon::parse($bloque['fin'])->format('g:i') }}
                                            </div>

                                            @foreach (\App\Models\salud\HorarioConsultorio::DIAS as $diaKey => $diaLabel)
                                                @php
                                                    $cellKey = "{$diaKey}|{$bloque['inicio']}|{$bloque['fin']}";
                                                @endphp
                                                <div class="p-1.5 flex items-center justify-center {{ !$loop->last ? 'border-r' : '' }} {{ !$loop->parent->last ? 'border-b' : '' }}"
                                                    style="border-color: var(--border-color);">

                                                    <div class="drop-zone w-full h-full min-h-[68px] max-h-[160px] overflow-y-auto rounded-xl border border-dashed border-gray-300 dark:border-gray-700 bg-gray-50/50 dark:bg-black/10 p-1 flex flex-col gap-1 transition-all duration-200 relative group"
                                                        data-key="{{ $cellKey }}"
                                                        data-dia="{{ $diaKey }}"
                                                        data-inicio="{{ $bloque['inicio'] }}"
                                                        data-fin="{{ $bloque['fin'] }}"
                                                        ondragover="handleDragOver(event)"
                                                        ondragleave="handleDragLeave(event)"
                                                        ondrop="handleDrop(event)">

                                                        {{-- Indicador Vacío --}}
                                                        <div
                                                            class="empty-placeholder flex flex-col items-center justify-center my-auto py-2 text-[10px] font-semibold text-gray-400 dark:text-gray-500 gap-1 pointer-events-none">
                                                            <i class="fas fa-plus-circle text-xs opacity-50"></i>
                                                            <span>Arrastrar aquí</span>
                                                        </div>

                                                        {{-- Lista de Usuarios Asignados a este Bloque --}}
                                                        <div class="assigned-list w-full flex flex-col gap-1"></div>

                                                    </div>

                                                </div>
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>

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

        // Si estás usando los datos desde dataset en JS:
        const nombre = cardElement.dataset.nombre; // Obtiene el nombre completo

        // O si procesas un objeto JSON de usuario:
        console.log(usuario.nombre_completo);

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

        // --- LÓGICA DRAG AND DROP MÚLTIPLE ---
        function handleDragStart(e) {
            const id = e.currentTarget.getAttribute('data-id');
            const nombre = e.currentTarget.getAttribute('data-nombre');
            e.dataTransfer.setData('text/plain', JSON.stringify({
                id,
                nombre
            }));
            e.dataTransfer.effectAllowed = 'copy';
        }

        function handleDragOver(e) {
            e.preventDefault();
            e.dataTransfer.dropEffect = 'copy';
            e.currentTarget.classList.add('border-sky-500', 'bg-sky-50/50', 'dark:bg-sky-950/40');
        }

        function handleDragLeave(e) {
            e.currentTarget.classList.remove('border-sky-500', 'bg-sky-50/50', 'dark:bg-sky-950/40');
        }

        function handleDrop(e) {
            e.preventDefault();
            const zone = e.currentTarget;
            zone.classList.remove('border-sky-500', 'bg-sky-50/50', 'dark:bg-sky-950/40');

            try {
                const data = JSON.parse(e.dataTransfer.getData('text/plain'));
                if (data && data.id) {
                    asignarUsuarioABloque(zone, data.id, data.nombre);
                }
            } catch (err) {
                console.error("Error al procesar el objeto soltado:", err);
            }
        }

        function asignarUsuarioABloque(zone, userId, userName) {
            const key = zone.getAttribute('data-key'); // "dia|hora_inicio|hora_fin"
            const listContainer = zone.querySelector('.assigned-list');
            const placeholder = zone.querySelector('.empty-placeholder');

            if (!listContainer) return;

            // Evitar duplicados del mismo usuario en la misma celda
            if (listContainer.querySelector(`[data-user-id="${userId}"]`)) {
                return;
            }

            if (placeholder) {
                placeholder.classList.add('hidden');
            }

            // Crear elemento/pill para el usuario
            const pill = document.createElement('div');
            pill.className =
                "user-pill flex items-center justify-between w-full px-2 py-1 rounded-lg bg-sky-50 dark:bg-sky-950/80 border border-sky-200 dark:border-sky-800 text-[10px] shadow-sm";
            pill.setAttribute('data-user-id', userId);
            pill.innerHTML = `
            <div class="flex items-center gap-1 min-w-0 pr-1">
                <i class="fas fa-user-md text-sky-600 dark:text-sky-400 text-[9px]"></i>
                <span class="font-bold text-sky-900 dark:text-sky-200 truncate" title="${userName}">${userName}</span>
            </div>
            <button type="button" onclick="quitarUsuarioPill(this)" class="text-rose-500 hover:text-rose-700 dark:hover:text-rose-400 flex-shrink-0 p-0.5">
                <i class="fas fa-times"></i>
            </button>
            <input type="hidden" class="input-horario" name="horarios[]" value="${key}|${userId}">
        `;

            listContainer.appendChild(pill);
            actualizarContador();
        }

        function quitarUsuarioPill(btn) {
            const pill = btn.closest('.user-pill');
            const zone = btn.closest('.drop-zone');
            const listContainer = zone.querySelector('.assigned-list');
            const placeholder = zone.querySelector('.empty-placeholder');

            pill.remove();

            if (listContainer.children.length === 0 && placeholder) {
                placeholder.classList.remove('hidden');
            }

            actualizarContador();
        }

        function actualizarContador() {
            const asignados = document.querySelectorAll('.input-horario').length;
            const contador = document.getElementById('contadorSeleccion');
            if (contador) {
                contador.textContent = asignados;
            }
        }

        // --- BUSCADOR DE USUARIOS EN TIEMPO REAL ---
        document.getElementById('searchUser').addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            document.querySelectorAll('.user-card').forEach(function(card) {
                const nombre = card.querySelector('.user-card-name').textContent.toLowerCase();
                if (nombre.includes(query)) {
                    card.classList.remove('hidden');
                } else {
                    card.classList.add('hidden');
                }
            });
        });

        // --- BOTÓN LIMPIAR TODO ---
        document.getElementById('btnLimpiarSeleccion').addEventListener('click', function() {
            document.querySelectorAll('.drop-zone').forEach(function(zone) {
                const listContainer = zone.querySelector('.assigned-list');
                const placeholder = zone.querySelector('.empty-placeholder');
                if (listContainer) listContainer.innerHTML = '';
                if (placeholder) placeholder.classList.remove('hidden');
            });
            actualizarContador();
        });

        // --- RECARGAR AL CAMBIAR DE CONSULTORIO ---
        document.getElementById('consultorio_id').addEventListener('change', function() {
            const consultorioId = this.value;
            if (consultorioId) {
                window.location.href = "{{ route('admin.salud.movimientos.horarios.create') }}?consultorio_id=" +
                    consultorioId;
            }
        });

        // --- CARGAR PREVIAMENTE LOS HORARIOS GUARDADOS ---
        const horariosActuales = @json($horariosActuales ?? []);

        document.addEventListener('DOMContentLoaded', function() {
            if (horariosActuales && horariosActuales.length > 0) {
                horariosActuales.forEach(item => {
                    // Se busca la celda por la clave "dia|hora_inicio|hora_fin"
                    const zone = document.querySelector(`.drop-zone[data-key="${item.clave_simple}"]`);
                    if (zone) {
                        asignarUsuarioABloque(zone, item.id_usuario, item.nombre_usuario);
                    }
                });
            }
        });
    </script>
</x-app-layout>
