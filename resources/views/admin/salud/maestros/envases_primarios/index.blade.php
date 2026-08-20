<x-app-layout>
    <div class="pt-6 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @include('components.alert')

            {{-- Encabezado de la página --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                        Envases Primarios
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Bienvenido <span class="font-bold">{{ auth()->user()->persona->nombre_persona }}</span> ·
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="abrirModalCrearEnvasePrimario()"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl {{ $btnClass ?? 'bg-sky-600 hover:bg-sky-700' }} text-white font-bold text-sm shadow-md active:scale-95 transition-all">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Crear Envase Primario</span>
                    </button>
                </div>
            </div>

            {{-- Card de Buscador + Estado --}}
            <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="p-2.5 rounded-2xl border shadow-sm mb-3 flex flex-col lg:flex-row lg:items-center gap-4">

                <form action="{{ route('admin.salud.maestros.envases_primarios.index') }}" method="GET"
                    class="relative w-full">
                    <input type="hidden" name="activo" value="{{ request('activo', 1) }}">

                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <input type="text" name="buscar" value="{{ request('buscar', $buscar ?? '') }}"
                        placeholder="Buscar envase primario..."
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm font-medium focus:outline-none focus:ring-2 {{ $focusRingClass ?? 'focus:ring-sky-500' }} transition-all">
                </form>

                <div class="flex items-center gap-3 shrink-0">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl border"
                        style="border-color: var(--border-color);">
                        <span class="text-[11px] font-black uppercase tracking-wider text-gray-400">Activos</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="estadoToggle" class="sr-only peer"
                                {{ request('activo', request('estado', 1)) == 1 ? 'checked' : '' }}>
                            <div
                                class="w-10 h-5.5 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:bg-sky-600 transition-colors relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-4">
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Barra de acciones --}}
            <div id="accionesBar" style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="p-2.5 rounded-2xl border shadow-sm mb-3 flex flex-col sm:flex-row sm:items-center sm:justify-end gap-3">

                <div class="flex items-center gap-2.5 text-[12px] font-black tracking-wider px-1 shrink-0">
                    <i class="fas fa-hand-pointer text-sky-500 shrink-0"></i>
                    <span id="accionesEstadoTexto">Selecciona un envase primario</span>
                </div>

                <div class="hidden sm:block w-px self-stretch shrink-0" style="background-color: var(--border-color);">
                </div>

                <div class="flex items-center gap-2 shrink-0 justify-end">
                    {{-- Ver detalles 
                    <a id="btnVer" href="#" title="Ver detalles"
                        class="acciones-bar-btn pointer-events-none opacity-40 text-gray-400 w-9 h-9 inline-flex items-center justify-center rounded-xl border border-transparent transition-all duration-200">
                        <i class="fas fa-eye text-sm"></i>
                    </a>--}}

                    {{-- Editar en Modal --}}
                    <button type="button" id="btnEditar" disabled onclick="ejecutarEdicionSeleccionada()" title="Editar"
                        class="acciones-bar-btn opacity-40 cursor-not-allowed text-gray-400 w-9 h-9 inline-flex items-center justify-center rounded-xl border border-transparent transition-all duration-200">
                        <i class="fas fa-edit text-sm"></i>
                    </button>

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
                                class="bg-gray-50/50 dark:bg-black/20 border-b border-gray-100 dark:border-gray-800 text-[13px] font-black uppercase tracking-wider">
                                <th class="px-6 py-4 text-center w-24">#</th>
                                <th class="px-6 py-4 text-center">Nombre</th>
                                <th class="px-6 py-4 text-center w-40">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-medium">
                            @forelse($envases as $envase)
                                <tr class="fila-envase cursor-pointer hover:bg-gray-50/60 dark:hover:bg-white/[0.02] transition-colors border-b border-gray-100 dark:border-gray-800/60"
                                    onclick="seleccionarFila(event, this)" title="Click para seleccionar"
                                    data-id="{{ $envase->id }}"
                                    data-nombre="{{ $envase->nombre }}"
                                    data-json="{{ json_encode($envase) }}"
                                    data-ver-url="{{ url('admin/salud/maestros/envases_primarios/' . $envase->id) }}"
                                    data-editar-url="{{ url('admin/salud/maestros/envases_primarios/' . $envase->id) }}"
                                    data-estado="{{ $envase->estado ? 1 : 0 }}"
                                    data-toggle-form="form-toggle-{{ $envase->id }}">
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-[12px] font-black rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                            {{ ($envases->currentPage() - 1) * $envases->perPage() + $loop->iteration }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap font-bold"
                                        style="color: var(--text-main);">
                                        {{ $envase->nombre }}
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if ($envase->estado)
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-[10px] font-black rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900">
                                                Activo
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 text-[10px] font-black rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3"
                                        class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 font-bold text-xs uppercase tracking-wider">
                                        <i
                                            class="fas fa-box-open text-3xl mb-3 block text-gray-300 dark:text-gray-700"></i>
                                        No hay envases primarios registrados
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($envases->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex justify-center">
                        {{ $envases->onEachSide(1)->appends(request()->query())->links('partials.pagination') }}
                    </div>
                @endif
            </div>

            {{-- Formularios ocultos de activar/inactivar --}}
            <div class="hidden">
                @foreach ($envases as $envase)
                    @if ($envase->estado == true)
                        <form id="form-toggle-{{ $envase->id }}"
                            action="{{ url('admin/salud/maestros/envases_primarios/' . $envase->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                        </form>
                    @else
                        <form id="form-toggle-{{ $envase->id }}"
                            action="{{ url('admin/salud/maestros/envases_primarios/' . $envase->id . '/activar') }}"
                            method="POST">
                            @csrf
                            @method('PUT')
                        </form>
                    @endif
                @endforeach
            </div>

        </div>
    </div>

    {{-- Modal Crear/Editar --}}
    @include('admin.salud.maestros.envases_primarios.modal-create-edit')

    <style>
        .fila-envase.is-selected {
            background-color: rgba(2, 132, 199, 0.08);
            box-shadow: inset 4px 0 0 0 #0284c7;
        }

        .fila-envase.is-selected:hover {
            background-color: rgba(2, 132, 199, 0.1);
        }

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
            const envaseData = JSON.parse(tr.dataset.json);
            const estaActivo = tr.dataset.estado === '1';
            const toggleForm = tr.dataset.toggleForm;
            const nombre = tr.dataset.nombre;

            const btnEditar = document.getElementById('btnEditar');
            const btnToggle = document.getElementById('btnToggleEstado');
            const btnToggleIcon = document.getElementById('btnToggleIcon');
            const btnToggleLabel = document.getElementById('btnToggleLabel');


            // Habilitar "Editar"
            btnEditar.disabled = false;
            btnEditar.dataset.editarUrl = editarUrl;
            btnEditar.dataset.json = tr.dataset.json;
            btnEditar.classList.remove('opacity-40', 'cursor-not-allowed', 'text-gray-400');
            btnEditar.classList.add('is-ready', 'bg-amber-50', 'text-amber-600', 'border', 'border-amber-200',
                'hover:bg-amber-100', 'hover:scale-105', 'shadow-sm',
                'dark:bg-amber-950/50', 'dark:text-amber-400', 'dark:border-amber-800/60');

            // Habilitar Activar/Inactivar
            btnToggle.disabled = false;
            btnToggle.dataset.formId = toggleForm;
            btnToggle.dataset.action = estaActivo ? 'inactivar' : 'activar';
            btnToggle.classList.remove('opacity-40', 'cursor-not-allowed', 'text-gray-400');

            btnToggle.classList.remove(
                'bg-rose-50', 'text-rose-600', 'border-rose-200', 'hover:bg-rose-100',
                'dark:bg-rose-950/50', 'dark:text-rose-400', 'dark:border-rose-800/60',
                'bg-emerald-50', 'text-emerald-600', 'border-emerald-200', 'hover:bg-emerald-100',
                'dark:bg-emerald-950/50', 'dark:text-emerald-400', 'dark:border-emerald-800/60'
            );

            if (estaActivo) {
                btnToggleIcon.className = 'fas fa-trash-alt';
                btnToggleLabel.textContent = 'Inactivar';
                btnToggle.classList.add('is-ready', 'bg-rose-50', 'text-rose-600', 'border', 'border-rose-200',
                    'hover:bg-rose-100', 'hover:scale-105', 'shadow-sm',
                    'dark:bg-rose-950/50', 'dark:text-rose-400', 'dark:border-rose-800/60');
            } else {
                btnToggleIcon.className = 'fas fa-check';
                btnToggleLabel.textContent = 'Activar';
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

            const btnEditar = document.getElementById('btnEditar');
            const btnToggle = document.getElementById('btnToggleEstado');

            // Resetear "Editar"
            btnEditar.disabled = true;
            btnEditar.removeAttribute('data-editar-url');
            btnEditar.removeAttribute('data-json');
            btnEditar.classList.add('opacity-40', 'cursor-not-allowed', 'text-gray-400');
            btnEditar.classList.remove('is-ready', 'bg-amber-50', 'text-amber-600', 'border', 'border-amber-200',
                'hover:bg-amber-100', 'hover:scale-105', 'shadow-sm',
                'dark:bg-amber-950/50', 'dark:text-amber-400', 'dark:border-amber-800/60');

            // Resetear "Inactivar/Activar"
            btnToggle.disabled = true;
            btnToggle.classList.add('opacity-40', 'cursor-not-allowed', 'text-gray-400');
            btnToggle.classList.remove('is-ready', 'bg-rose-50', 'text-rose-600', 'border', 'border-rose-200',
                'hover:bg-rose-100', 'hover:scale-105', 'shadow-sm',
                'dark:bg-rose-950/50', 'dark:text-rose-400', 'dark:border-rose-800/60',
                'bg-emerald-50', 'text-emerald-600', 'border-emerald-200', 'hover:bg-emerald-100',
                'dark:bg-emerald-950/50', 'dark:text-emerald-400', 'dark:border-emerald-800/60');

            document.getElementById('accionesEstadoTexto').textContent = 'Selecciona un envase primario';
        }

        function ejecutarEdicionSeleccionada() {
            const btnEditar = document.getElementById('btnEditar');
            if (btnEditar.disabled) return;

            const updateUrl = btnEditar.dataset.editarUrl;
            const envase = JSON.parse(btnEditar.dataset.json);

            abrirModalEditarEnvasePrimario(envase, updateUrl);
        }

        // Deseleccionar al hacer click fuera
        document.addEventListener('click', function(event) {
            if (filaSeleccionada && !event.target.closest('#printArea') && !event.target.closest('#accionesBar') && !event.target.closest('#modalEnvasePrimario')) {
                deseleccionarFila();
            }
        });

        // Toggle de estado del filtro superior
        document.getElementById('estadoToggle').addEventListener('change', function() {
            const params = new URLSearchParams(window.location.search);
            params.set('estado', this.checked ? 1 : 0);
            window.location.href = "{{ route('admin.salud.maestros.envases_primarios.index') }}?" + params.toString();
        });

        // Confirmación para activar/inactivar
        function confirmToggleEstadoSeleccionado() {
            const btnToggle = document.getElementById('btnToggleEstado');
            if (btnToggle.disabled) return;

            const action = btnToggle.dataset.action;
            const formId = btnToggle.dataset.formId;
            const isActivate = action === 'activar';

            const title = isActivate ? '¿Activar envase primario?' : '¿Inactivar envase primario?';
            const text = isActivate ?
                'El envase primario volverá a estar disponible en el sistema.' :
                'El envase primario dejará de estar disponible en el sistema.';

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