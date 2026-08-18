<x-app-layout>
    <div class="pt-6 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @include('components.alert')

            {{-- Encabezado de la página --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                        Medicamentos
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Bienvenido <span class="font-bold">{{ auth()->user()->persona->nombre_persona }}</span> ·
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ url('admin/salud/maestros/medicamentos/create') }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl {{ $btnClass ?? 'bg-sky-600 hover:bg-sky-700' }} text-white font-bold text-sm shadow-md active:scale-95 transition-all">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Crear Medicamento</span>
                    </a>
                </div>
            </div>

            {{-- Card de Buscador + Estado + Filtros --}}
            <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="p-2.5 rounded-2xl border shadow-sm mb-3 flex flex-col lg:flex-row lg:items-center gap-4">

                <form action="{{ route('admin.salud.maestros.medicamentos.index') }}" method="GET"
                    class="relative w-full">
                    <input type="hidden" name="activo" value="{{ request('activo', 1) }}">
                    <input type="hidden" name="categoria" value="{{ request('categoria') }}">

                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        placeholder="Buscar medicamento..."
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm font-medium focus:outline-none focus:ring-2 {{ $focusRingClass ?? 'focus:ring-sky-500' }} transition-all">
                </form>

                <div class="flex items-center gap-3 shrink-0">
                    {{-- Toggle estado --}}
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl border"
                        style="border-color: var(--border-color);">
                        <span class="text-[11px] font-black uppercase tracking-wider text-gray-400">Activos</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="estadoToggle" class="sr-only peer"
                                {{ request('activo', 1) == 1 ? 'checked' : '' }}>
                            <div
                                class="w-10 h-5.5 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:bg-sky-600 transition-colors relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-4">
                            </div>
                        </label>
                    </div>

                    {{-- Botón filtros --}}
                    <button type="button" id="filtersToggle"
                        class="w-10 h-10 flex items-center justify-center rounded-xl border text-gray-500 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors"
                        style="border-color: var(--border-color);" title="Filtros">
                        <i class="fas fa-filter text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- Filtros colapsables --}}
            <div id="filters" style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="{{ request('categoria') ? '' : 'hidden' }} p-2.5 rounded-2xl border shadow-sm mb-3">
                <form action="{{ route('admin.salud.maestros.medicamentos.index') }}" method="GET"
                    class="flex flex-col sm:flex-row sm:items-end gap-4">
                    <input type="hidden" name="activo" value="{{ request('activo', 1) }}">
                    <input type="hidden" name="buscar" value="{{ request('buscar') }}">

                    <div class="flex-1">
                        <label class="block text-[13px] font-black uppercase tracking-wider mb-2 ml-1">
                            Categoría
                        </label>
                        <div class="flex items-stretch rounded-xl border overflow-hidden focus-within:ring-2 {{ $focusRingClass ?? 'focus-within:ring-sky-500' }} transition-all"
                            style="border-color: var(--border-color);">
                            <span
                                class="flex items-center justify-center px-3.5 bg-gray-50 dark:bg-black/20 text-gray-400 border-r"
                                style="border-color: var(--border-color);">
                            </span>
                            <select name="categoria"
                                style="background-color: rgba(0,0,0,0.02); color: var(--text-main);"
                                class="w-full px-3 py-2 text-sm font-medium border-none focus:ring-0 focus:outline-none transition-all">
                                <option value="">Todas</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->id }}"
                                        @if (request('categoria') == $categoria->id) selected @endif>
                                        {{ $categoria->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex gap-2 mb-0.5">
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg {{ $btnClass ?? 'bg-sky-500 hover:bg-sky-700' }} text-white font-bold text-xs shadow-sm active:scale-95 transition-all">
                            <i class="fas fa-check text-[10px]"></i> Aplicar
                        </button>
                        <a href="{{ route('admin.salud.maestros.medicamentos.index', ['activo' => request('activo', 1)]) }}"
                            class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-bold hover:bg-gray-50 dark:hover:bg-white/5 transition-all"
                            style="border-color: var(--border-color); color: var(--text-main);">
                            <i class="fas fa-times text-[10px]"></i> Limpiar
                        </a>
                    </div>
                </form>
            </div>

            {{-- Barra de acciones --}}
            <div id="accionesBar" style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="p-2.5 rounded-2xl border shadow-sm mb-3 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">

                {{-- Texto e indicador de selección --}}
                <div class="flex items-center gap-2.5 text-[12px] font-black tracking-wider px-1 shrink-0">
                    <i class="fas fa-hand-pointer text-sky-500 shrink-0"></i>
                    <span id="accionesEstadoTexto">Selecciona un medicamento</span>
                </div>

                {{-- Separador vertical --}}
                <div class="hidden sm:block w-px self-stretch shrink-0" style="background-color: var(--border-color);">
                </div>

                {{-- Botones de acción tipo icono con realce semántico --}}
                <div class="flex items-center gap-2 shrink-0 justify-end">
                    {{-- Ver detalles --}}
                    <a id="btnVer" href="#" title="Ver detalles"
                        class="acciones-bar-btn pointer-events-none opacity-40 text-gray-400 w-9 h-9 inline-flex items-center justify-center rounded-xl border border-transparent transition-all duration-200">
                        <i class="fas fa-eye text-sm"></i>
                    </a>

                    {{-- Editar --}}
                    <a id="btnEditar" href="#" title="Editar"
                        class="acciones-bar-btn pointer-events-none opacity-40 text-gray-400 w-9 h-9 inline-flex items-center justify-center rounded-xl border border-transparent transition-all duration-200">
                        <i class="fas fa-edit text-sm"></i>
                    </a>

                    {{-- Inactivar / Activar --}}
                    <button type="button" id="btnToggleEstado" disabled onclick="confirmToggleEstadoSeleccionado()"
                        title="Inactivar / Activar"
                        class="acciones-bar-btn opacity-40 cursor-not-allowed text-gray-400 w-9 h-9 inline-flex items-center justify-center rounded-xl border border-transparent transition-all duration-200">
                        <i class="fas fa-trash-alt text-sm" id="btnToggleIcon"></i>
                        <span id="btnToggleLabel" class="sr-only">Inactivar</span>
                    </button>
                </div>
            </div>

            {{-- Card de la Tabla --}}
            <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="rounded-2xl border shadow-sm overflow-hidden">

                <div class="overflow-x-auto" id="printArea">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 dark:bg-black/20 border-b border-gray-100 dark:border-gray-800 text-[13px] font-black uppercase tracking-wider ">
                                <th class="px-6 py-4 text-center">Código</th>
                                <th class="px-6 py-4 text-center">Nombre</th>
                                <th class="px-6 py-4 text-center">Categoría</th>
                                <th class="px-6 py-4 text-center">Cantidad</th>
                                <th class="px-6 py-4 text-center">Unidad</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-medium">
                            @forelse($productos as $producto)
                                <tr class="fila-medicamento cursor-pointer hover:bg-gray-50/60 dark:hover:bg-white/[0.02] transition-colors border-b border-gray-100 dark:border-gray-800/60"
                                    onclick="seleccionarFila(event, this)" title="Click para seleccionar"
                                    data-id="{{ $producto->id }}" data-nombre="{{ $producto->nombre }}"
                                    data-ver-url="{{ url('admin/salud/maestros/medicamentos/' . $producto->id) }}"
                                    data-editar-url="{{ url('admin/salud/maestros/medicamentos/' . $producto->id . '/edit') }}"
                                    data-estado="{{ $producto->estado ? 1 : 0 }}"
                                    data-toggle-form="form-toggle-{{ $producto->id }}">
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-[12px] font-black rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                            {{ $producto->codigo }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap font-bold"
                                        style="color: var(--text-main);">
                                        {{ $producto->nombre }}
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ $producto->categoria->nombre }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if ($producto->cantidad_actual == null)
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 text-[10px] font-black rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-700">
                                                <i class="fas fa-info-circle"></i> Sin compra
                                            </span>
                                        @elseif ($producto->cantidad_actual == 0)
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 text-[10px] font-black rounded-lg bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-900">
                                                <i class="fas fa-exclamation-triangle"></i> Agotado
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-[10px] font-black rounded-lg bg-sky-50 dark:bg-sky-950/40 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-900">
                                                {{ $producto->cantidad_actual }}
                                            </span>
                                        @endif
                                    </td>
                                    <td
                                        class="px-6 py-4 text-center whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ $producto->unidad->nombre }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5"
                                        class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 font-bold text-xs uppercase tracking-wider">
                                        <i
                                            class="fas fa-box-open text-3xl mb-3 block text-gray-300 dark:text-gray-700"></i>
                                        No hay medicamentos registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($productos->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex justify-center">
                        {{ $productos->onEachSide(1)->appends(request()->query())->links('partials.pagination') }}
                    </div>
                @endif
            </div>

            {{-- Formularios ocultos de activar/inactivar (uno por medicamento) --}}
            <div class="hidden">
                @foreach ($productos as $producto)
                    @if ($producto->estado == true)
                        <form id="form-toggle-{{ $producto->id }}"
                            action="{{ url('admin/salud/maestros/medicamentos/' . $producto->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    @else
                        <form id="form-toggle-{{ $producto->id }}"
                            action="{{ url('admin/salud/maestros/medicamentos/' . $producto->id . '/activar') }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                        </form>
                    @endif
                @endforeach
            </div>

        </div>
    </div>

    <style>
        /* Fila seleccionada */
        .fila-medicamento.is-selected {
            background-color: rgba(2, 132, 199, 0.08);
            box-shadow: inset 4px 0 0 0 #0284c7;
        }

        .fila-medicamento.is-selected:hover {
            background-color: rgba(2, 132, 199, 0.1);
        }

        /* Estado habilitado de los botones de la barra de acciones */
        .acciones-bar-btn.is-ready {
            opacity: 1;
            pointer-events: auto;
            cursor: pointer;
        }
    </style>

    <script>
        let filaSeleccionada = null;

        function seleccionarFila(event, tr) {
            if (filaSeleccionada === tr) {
                deseleccionarFila();
                return;
            }

            if (filaSeleccionada) {
                filaSeleccionada.classList.remove('is-selected');
            }

            tr.classList.add('is-selected');
            filaSeleccionada = tr;

            const verUrl = tr.dataset.verUrl;
            const editarUrl = tr.dataset.editarUrl;
            const estaActivo = tr.dataset.estado === '1';
            const toggleForm = tr.dataset.toggleForm;
            const nombre = tr.dataset.nombre;

            const btnVer = document.getElementById('btnVer');
            const btnEditar = document.getElementById('btnEditar');
            const btnToggle = document.getElementById('btnToggleEstado');
            const btnToggleIcon = document.getElementById('btnToggleIcon');
            const btnToggleLabel = document.getElementById('btnToggleLabel');

            // Habilitar "Ver detalles" con realce Sky
            btnVer.href = verUrl;
            btnVer.classList.remove('pointer-events-none', 'opacity-50', 'text-gray-400');
            btnVer.classList.add('is-ready', 'bg-sky-50', 'text-sky-600', 'border', 'border-sky-200',
                'hover:bg-sky-100', 'hover:scale-105', 'shadow-sm',
                'dark:bg-sky-950/50', 'dark:text-sky-400', 'dark:border-sky-800/60');

            // Habilitar "Editar" con realce Amber (Amarillo)
            btnEditar.href = editarUrl;
            btnEditar.classList.remove('pointer-events-none', 'opacity-50', 'text-gray-400');
            btnEditar.classList.add('is-ready', 'bg-amber-50', 'text-amber-600', 'border', 'border-amber-200',
                'hover:bg-amber-100', 'hover:scale-105', 'shadow-sm',
                'dark:bg-amber-950/50', 'dark:text-amber-400', 'dark:border-amber-800/60');

            // Habilitar Activar/Inactivar base
            btnToggle.disabled = false;
            btnToggle.dataset.formId = toggleForm;
            btnToggle.dataset.action = estaActivo ? 'inactivar' : 'activar';
            btnToggle.classList.remove('opacity-50', 'cursor-not-allowed', 'text-gray-400');

            // Limpiar clases previas de color del toggle
            btnToggle.classList.remove(
                'bg-rose-50', 'text-rose-600', 'border-rose-200', 'hover:bg-rose-100',
                'dark:bg-rose-950/50', 'dark:text-rose-400', 'dark:border-rose-800/60',
                'bg-emerald-50', 'text-emerald-600', 'border-emerald-200', 'hover:bg-emerald-100',
                'dark:bg-emerald-950/50', 'dark:text-emerald-400', 'dark:border-emerald-800/60'
            );

            if (estaActivo) {
                btnToggleIcon.className = 'fas fa-trash-alt';
                btnToggleLabel.textContent = 'Inactivar';
                // Realce Rose (Rojo) para Inactivar
                btnToggle.classList.add('is-ready', 'bg-rose-50', 'text-rose-600', 'border', 'border-rose-200',
                    'hover:bg-rose-100', 'hover:scale-105', 'shadow-sm',
                    'dark:bg-rose-950/50', 'dark:text-rose-400', 'dark:border-rose-800/60');
            } else {
                btnToggleIcon.className = 'fas fa-check';
                btnToggleLabel.textContent = 'Activar';
                // Realce Emerald (Verde) para Activar
                btnToggle.classList.add('is-ready', 'bg-emerald-50', 'text-emerald-600', 'border', 'border-emerald-200',
                    'hover:bg-emerald-100', 'hover:scale-105', 'shadow-sm',
                    'dark:bg-emerald-950/50', 'dark:text-emerald-400', 'dark:border-emerald-800/60');
            }

            document.getElementById('accionesEstadoTexto').textContent = nombre;
        }

        function deseleccionarFila() {
            if (!filaSeleccionada) return;

            filaSeleccionada.classList.remove('is-selected');
            filaSeleccionada = null;

            const btnVer = document.getElementById('btnVer');
            const btnEditar = document.getElementById('btnEditar');
            const btnToggle = document.getElementById('btnToggleEstado');

            // Resetear "Ver"
            btnVer.removeAttribute('href');
            btnVer.classList.add('pointer-events-none', 'opacity-50', 'text-gray-400');
            btnVer.classList.remove('is-ready', 'bg-sky-50', 'text-sky-600', 'border', 'border-sky-200',
                'hover:bg-sky-100', 'hover:scale-105', 'shadow-sm',
                'dark:bg-sky-950/50', 'dark:text-sky-400', 'dark:border-sky-800/60');

            // Resetear "Editar"
            btnEditar.removeAttribute('href');
            btnEditar.classList.add('pointer-events-none', 'opacity-50', 'text-gray-400');
            btnEditar.classList.remove('is-ready', 'bg-amber-50', 'text-amber-600', 'border', 'border-amber-200',
                'hover:bg-amber-100', 'hover:scale-105', 'shadow-sm',
                'dark:bg-amber-950/50', 'dark:text-amber-400', 'dark:border-amber-800/60');

            // Resetear "Inactivar/Activar"
            btnToggle.disabled = true;
            btnToggle.classList.add('opacity-50', 'cursor-not-allowed', 'text-gray-400');
            btnToggle.classList.remove('is-ready', 'bg-rose-50', 'text-rose-600', 'border', 'border-rose-200',
                'hover:bg-rose-100', 'hover:scale-105', 'shadow-sm',
                'dark:bg-rose-950/50', 'dark:text-rose-400', 'dark:border-rose-800/60',
                'bg-emerald-50', 'text-emerald-600', 'border-emerald-200', 'hover:bg-emerald-100',
                'dark:bg-emerald-950/50', 'dark:text-emerald-400', 'dark:border-emerald-800/60');

            document.getElementById('accionesEstadoTexto').textContent = 'Selecciona un medicamento';
        }

        // Deseleccionar al hacer click fuera de la tabla y de la barra de acciones
        document.addEventListener('click', function(event) {
            if (filaSeleccionada && !event.target.closest('#printArea') && !event.target.closest('#accionesBar')) {
                deseleccionarFila();
            }
        });

        // Toggle de estado (activo/inactivo) del filtro superior
        document.getElementById('estadoToggle').addEventListener('change', function() {
            const params = new URLSearchParams(window.location.search);
            params.set('activo', this.checked ? 1 : 0);
            window.location.href = "{{ route('admin.salud.maestros.medicamentos.index') }}?" + params.toString();
        });

        // Mostrar/ocultar panel de filtros
        document.getElementById('filtersToggle').addEventListener('click', function() {
            document.getElementById('filters').classList.toggle('hidden');
        });

        // Confirmación unificada para activar/inactivar el medicamento seleccionado
        function confirmToggleEstadoSeleccionado() {
            const btnToggle = document.getElementById('btnToggleEstado');
            if (btnToggle.disabled) return;

            const action = btnToggle.dataset.action;
            const formId = btnToggle.dataset.formId;
            const isActivate = action === 'activar';

            const title = isActivate ? '¿Activar medicamento?' : '¿Inactivar medicamento?';
            const text = isActivate ?
                'El medicamento volverá a estar disponible en el sistema.' :
                'El medicamento dejará de estar disponible en el sistema.';

            window.AppModal.show(title, text, {
                type: 'confirm',
                btnText: isActivate ? 'Sí, activar' : 'Sí, inactivar',
                intent: isActivate ? 'success' : 'danger'
            }).then(result => {
                if (result) document.getElementById(formId).submit();
            });
        }
    </script>
</x-app-layout>
