@php
    $moduloActivo = strtolower(session('modulo_activo', 'general'));
    $esPsicologia = in_array($moduloActivo, ['psicologia', 'psicología', 'mental']);

    $themeColor = $esPsicologia ? 'indigo' : 'red';
    $btnClass = $esPsicologia ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-red-600 hover:bg-red-700';
    $focusRingClass = $esPsicologia
        ? 'focus:ring-indigo-500/20 focus:border-indigo-500'
        : 'focus:ring-red-500/20 focus:border-red-500';
@endphp

<x-app-layout>
    <div class="pt-8 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @include('components.alert')

            @if (session('error'))
                <div
                    class="p-4 mb-6 text-sm text-rose-800 rounded-2xl bg-rose-50 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800 flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-rose-600 dark:text-rose-400 text-lg"></i>
                    <span><strong
                            class="font-black uppercase tracking-wider text-[10px] block mb-0.5">Error</strong>{{ session('error') }}</span>
                </div>
            @endif

            <!-- Encabezado de la Página -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                            @isset($grupoSeleccionado)
                                Editar grupo: <span
                                    class="text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400">{{ $grupoSeleccionado->nombre }}</span>
                            @else
                                Bloques de Horario
                            @endisset
                        </h1>

                        @if (isset($grupoActivo) && !isset($grupoSeleccionado))
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 uppercase tracking-wider">
                                <i class="fas fa-check-circle me-1 text-[9px]"></i> Horario activo:
                                {{ $grupoActivo->nombre }}
                            </span>
                        @elseif(!isset($grupoActivo) && !isset($grupoSeleccionado))
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800 uppercase tracking-wider">
                                <i class="fas fa-exclamation-triangle me-1 text-[9px]"></i> Sin horario activo
                            </span>
                        @endif
                    </div>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Gestiona la disponibilidad y los bloques de atención para <strong
                            class="text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400">Salud
                            Mental</strong>.
                    </p>
                </div>

                <!-- Botones de Acción Superiores -->
                <div class="flex flex-wrap items-center gap-2">
                    @if (!isset($grupoSeleccionado))
                        <!-- Descargar PDF -->
                        <a href="{{ route('admin.psicologia.maestros.horarios.exportarPdf', isset($grupoActivo) ? ['grupo' => $grupoActivo->id] : []) }}"
                            target="_blank"
                            class="p-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all text-xs font-bold flex items-center justify-center shadow-xs"
                            title="Descargar horario en PDF">
                            <i class="fas fa-file-pdf text-sm"></i>
                        </a>

                        <!-- Guardar Grupo de Horarios -->
                        <button id="openGroupModal" type="button"
                            {{ isset($grupoActivo) || !empty($tieneCitasPendientes) ? 'disabled' : '' }}
                            class="p-2.5 bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-700 transition-all text-xs font-bold flex items-center justify-center shadow-xs disabled:opacity-40 disabled:cursor-not-allowed"
                            title="Guardar como grupo de horarios"
                            aria-disabled="{{ isset($grupoActivo) || !empty($tieneCitasPendientes) ? 'true' : 'false' }}">
                            <i class="fas fa-save text-sm"></i>
                        </button>

                        <!-- Ver Grupos -->
                        <a href="{{ route('admin.psicologia.maestros.grupos_horarios.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 transition-all shadow-xs">
                            <i class="fas fa-layer-group text-xs"></i>
                            <span>Ver Grupos</span>
                        </a>
                    @endif

                    <!-- Crear Bloque -->
                    <a href="{{ route('admin.psicologia.maestros.horarios.create', isset($grupoSeleccionado) ? ['grupo' => $grupoSeleccionado->id] : []) }}"
                        class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl {{ $btnClass }} text-white font-bold text-xs shadow-md active:scale-95 transition-all">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Nuevo Bloque</span>
                    </a>
                </div>
            </div>

            <!-- Banner Informativo de Grupo Seleccionado -->
            @isset($grupoSeleccionado)
                <div
                    class="mb-6 p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 border border-indigo-200 dark:border-indigo-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-xs">
                    <div class="flex items-center gap-3 min-w-0">
                        <div
                            class="w-10 h-10 rounded-xl bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400 flex items-center justify-center shrink-0">
                            <i class="fas fa-calendar-week text-base"></i>
                        </div>
                        <span class="text-xs sm:text-sm font-medium text-indigo-900 dark:text-indigo-300">
                            Estás editando los bloques del grupo <strong
                                class="font-bold uppercase">{{ $grupoSeleccionado->nombre }}</strong>.
                            Los cambios afectarán únicamente a este grupo.
                        </span>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <a href="{{ route('admin.psicologia.maestros.horarios.index') }}"
                            class="px-3 py-1.5 text-xs font-bold bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-300 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            Cancelar
                        </a>
                        <a href="{{ route('admin.psicologia.maestros.grupos_horarios.index') }}"
                            class="px-3 py-1.5 text-xs font-bold bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition-all">
                            Volver a Grupos
                        </a>
                    </div>
                </div>
            @endisset

            <!-- Alerta de Citas Pendientes -->
            @if (!empty($tieneCitasPendientes))
                <div
                    class="mb-6 p-4 text-xs sm:text-sm text-amber-800 dark:text-amber-400 rounded-2xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 flex items-center gap-3 shadow-xs">
                    <i class="fas fa-lock text-amber-600 dark:text-amber-400 text-base shrink-0"></i>
                    <span><strong>Acción Restringida:</strong> No puedes editar ni eliminar bloques de horario mientras
                        tengas citas pendientes o en espera.</span>
                </div>
            @endif

            <!-- Filtro por Día -->
            <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="p-4 rounded-2xl border shadow-sm mb-6 flex items-center justify-between gap-4">
                <form method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3 w-full sm:w-auto">
                    @isset($grupoSeleccionado)
                        <input type="hidden" name="grupo" value="{{ $grupoSeleccionado->id }}">
                    @endisset
                    <label
                        class="text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 flex items-center gap-1.5">
                        <i class="fas fa-filter text-xs"></i> Filtrar por día:
                    </label>
                    <select name="dia" onchange="this.form.submit()"
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="px-3 py-2 rounded-xl border text-xs font-bold focus:outline-none focus:ring-2 {{ $focusRingClass }} transition-all">
                        <option value="">Todos los días</option>
                        @foreach ($dias as $dia)
                            <option value="{{ $dia }}" {{ $filtroDia === $dia ? 'selected' : '' }}>
                                {{ $dia }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            <!-- Grilla de Bloques por Día -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-4">
                @foreach ($horariosPorDia as $dia => $horariosDia)
                    <div
                        class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-3 border border-gray-200/60 dark:border-gray-700/60 flex flex-col">
                        <h4
                            class="text-xs font-black uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3 text-center">
                            {{ $dia }}
                        </h4>

                        @if ($horariosDia->isEmpty())
                            <p
                                class="text-[11px] text-gray-400 dark:text-gray-500 text-center my-auto py-6 font-medium italic">
                                Sin bloques</p>
                        @else
                            <div class="space-y-2">
                                @foreach ($horariosDia as $horario)
                                    <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                                        class="relative p-3 rounded-xl border shadow-2xs text-center transition-all duration-300 {{ $horario->activo == \App\Models\salud\Horario::STATUS_INACTIVE ? 'opacity-50' : '' }}">

                                        <!-- Hora -->
                                        <div class="mb-2">
                                            <span class="text-xs font-bold text-gray-800 dark:text-gray-200 block">
                                                {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('g:i A') }} -
                                                {{ \Carbon\Carbon::parse($horario->hora_fin)->format('g:i A') }}
                                            </span>
                                        </div>

                                        <div
                                            class="flex items-center justify-center gap-1.5 flex-wrap pt-2 border-t border-gray-100 dark:border-gray-800">
                                            <button type="button"
                                                onclick="openBlockModal('blockModal-{{ $horario->id }}')"
                                                class="p-2 bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 rounded-lg hover:bg-indigo-600 hover:text-white transition-all text-xs font-bold flex items-center justify-center"
                                                title="Ver detalle">
                                                <i class="fas fa-eye"></i>
                                            </button>

                                            <!-- Editar -->
                                            @if (!empty($tieneCitasPendientes))
                                                <span
                                                    class="p-2 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg text-xs font-bold flex items-center justify-center cursor-not-allowed"
                                                    title="No puedes editar mientras tengas citas pendientes">
                                                    <i class="fas fa-pen"></i>
                                                </span>
                                            @else
                                                <a href="{{ route('admin.psicologia.maestros.horarios.edit', ['horario' => $horario->id] + (isset($grupoSeleccionado) ? ['grupo' => $grupoSeleccionado->id] : [])) }}"
                                                    class="p-2 bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 rounded-lg hover:bg-sky-600 hover:text-white transition-all text-xs font-bold flex items-center justify-center"
                                                    title="Editar bloque">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                            @endif

                                            @if (!empty($tieneCitasPendientes))
                                                <span
                                                    class="p-2 bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 rounded-lg text-xs font-bold flex items-center justify-center cursor-not-allowed"
                                                    title="No puedes eliminar mientras tengas citas pendientes">
                                                    <i class="fas fa-trash-alt"></i>
                                                </span>
                                            @else
                                                <form
                                                    action="{{ route('admin.psicologia.maestros.horarios.destroy', $horario->id) }}"
                                                    method="POST" data-ajax-delete-block="true" class="m-0 p-0 inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="p-2 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 rounded-lg hover:bg-rose-600 hover:text-white transition-all text-xs font-bold flex items-center justify-center"
                                                        title="Eliminar bloque">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>

                                        <div id="blockModal-{{ $horario->id }}"
                                            class="block-modal fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-xs p-4"
                                            onclick="if(event.target === this) closeBlockModal('blockModal-{{ $horario->id }}')">
                                            <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                                                class="rounded-2xl border shadow-2xl w-full max-w-md p-6 overflow-y-auto text-left transition-all">

                                                <div
                                                    class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100 dark:border-gray-800">
                                                    <h3 class="text-lg font-extrabold tracking-tight"
                                                        style="color: var(--text-main);">
                                                        Detalle del Bloque
                                                    </h3>
                                                    <button type="button"
                                                        onclick="closeBlockModal('blockModal-{{ $horario->id }}')"
                                                        class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-white text-lg rounded-xl transition-all">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>

                                                <div
                                                    class="space-y-3 bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl border border-gray-200/60 dark:border-gray-700/60 text-xs">
                                                    @php
                                                        $statusLabel = 'Eliminado';
                                                        $statusBadge = 'bg-gray-100 text-gray-600 border-gray-200';
                                                        if (
                                                            $horario->activo == \App\Models\salud\Horario::STATUS_ACTIVE
                                                        ) {
                                                            $statusLabel = 'Activo';
                                                            $statusBadge =
                                                                'bg-emerald-50 text-emerald-600 border-emerald-200';
                                                        } elseif (
                                                            $horario->activo ==
                                                            \App\Models\salud\Horario::STATUS_INACTIVE
                                                        ) {
                                                            $statusLabel = 'Inactivo';
                                                            $statusBadge = 'bg-rose-50 text-rose-600 border-rose-200';
                                                        }
                                                    @endphp

                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="font-bold text-gray-500 uppercase tracking-wider">Día:</span>
                                                        <span
                                                            class="font-extrabold text-gray-800 dark:text-gray-200 uppercase">{{ $horario->dia }}</span>
                                                    </div>

                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="font-bold text-gray-500 uppercase tracking-wider">Hora
                                                            Inicio:</span>
                                                        <span
                                                            class="font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($horario->hora_inicio)->format('g:i A') }}</span>
                                                    </div>

                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="font-bold text-gray-500 uppercase tracking-wider">Hora
                                                            Fin:</span>
                                                        <span
                                                            class="font-bold text-gray-800 dark:text-gray-200">{{ \Carbon\Carbon::parse($horario->hora_fin)->format('g:i A') }}</span>
                                                    </div>

                                                    <div class="flex justify-between items-center">
                                                        <span
                                                            class="font-bold text-gray-500 uppercase tracking-wider">Estado:</span>
                                                        <span
                                                            class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border uppercase {{ $statusBadge }}">
                                                            {{ $statusLabel }}
                                                        </span>
                                                    </div>

                                                    @if ($horario->descripcion)
                                                        <div
                                                            class="pt-2 border-t border-gray-200/60 dark:border-gray-700/60">
                                                            <span
                                                                class="font-bold text-gray-500 uppercase tracking-wider block mb-1">Descripción:</span>
                                                            <p
                                                                class="text-gray-700 dark:text-gray-300 font-medium leading-relaxed">
                                                                {{ $horario->descripcion }}</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="mt-6 flex justify-end">
                                                    <button type="button"
                                                        onclick="closeBlockModal('blockModal-{{ $horario->id }}')"
                                                        class="px-5 py-2 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 transition-all">
                                                        Cerrar
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- Modal para Guardar Grupo de Horarios -->
            <div id="groupModal"
                class="fixed inset-0 hidden items-center justify-center bg-black/60 backdrop-blur-xs z-50 p-4">
                <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                    class="rounded-2xl border shadow-2xl w-full max-w-md p-6 transition-all">

                    <div
                        class="flex justify-between items-center pb-4 mb-4 border-b border-gray-100 dark:border-gray-800">
                        <h3 class="text-lg font-extrabold tracking-tight" style="color: var(--text-main);">
                            Guardar Grupo de Horarios
                        </h3>
                        <button type="button" id="closeGroupModal"
                            class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-white text-lg rounded-xl transition-all">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form method="POST"
                        action="{{ route('admin.psicologia.maestros.grupos_horarios.store_from_horarios') }}">
                        @csrf
                        <input type="hidden" name="action"
                            value="{{ isset($grupoActivo) ? 'update' : 'create' }}" />

                        <div class="mb-4">
                            @if (isset($grupoActivo))
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                                    Existe un grupo activo llamado <strong
                                        class="text-gray-800 dark:text-gray-200 font-bold">"{{ $grupoActivo->nombre }}"</strong>.
                                    Los cambios se aplicarán directamente ahí. Si deseas crear uno nuevo, ingresa un
                                    nombre.
                                </p>
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400 font-medium leading-relaxed">
                                    No hay un grupo activo seleccionado. Ingresa un nombre para crear un grupo nuevo con
                                    los bloques actuales.
                                </p>
                            @endif
                        </div>

                        <div class="mb-5" id="nombreField">
                            <label
                                class="block text-xs font-bold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-1"
                                for="nombre_grupo">
                                Nombre del nuevo grupo
                            </label>
                            <input id="nombre_grupo" name="nombre" type="text"
                                placeholder="Ej. Semestre 2026-I / Ocupado"
                                style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                class="w-full px-3.5 py-2.5 rounded-xl border text-sm font-medium focus:outline-none focus:ring-2 {{ $focusRingClass }} transition-all"
                                {{ !isset($grupoActivo) ? 'required' : 'disabled' }} />
                            <x-input-error :messages="$errors->get('nombre')" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-2 pt-4 border-t border-gray-100 dark:border-gray-800">
                            <button type="button" id="closeGroupModalBtn"
                                class="px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 transition-all">
                                Cancelar
                            </button>
                            <button type="submit"
                                class="px-5 py-2 rounded-xl {{ $btnClass }} text-white font-bold text-xs shadow-md active:scale-95 transition-all">
                                Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <!-- Scripts de Interacción -->
    <script>
        function openBlockModal(id) {
            var modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeBlockModal(id) {
            var modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        var openGroupBtn = document.getElementById('openGroupModal');
        if (openGroupBtn) {
            openGroupBtn.addEventListener('click', function() {
                if (this.disabled) {
                    return;
                }
                var groupModal = document.getElementById('groupModal');
                if (groupModal) {
                    groupModal.classList.remove('hidden');
                    groupModal.classList.add('flex');
                }
            });
        }

        var closeGroupBtn = document.getElementById('closeGroupModal');
        var closeGroupModalBtn = document.getElementById('closeGroupModalBtn');

        function hideGroupModal() {
            var groupModal = document.getElementById('groupModal');
            if (groupModal) {
                groupModal.classList.add('hidden');
                groupModal.classList.remove('flex');
            }
        }

        if (closeGroupBtn) closeGroupBtn.addEventListener('click', hideGroupModal);
        if (closeGroupModalBtn) closeGroupModalBtn.addEventListener('click', hideGroupModal);

        var groupModalEl = document.getElementById('groupModal');
        if (groupModalEl) {
            groupModalEl.addEventListener('click', function(event) {
                if (event.target.id === 'groupModal') {
                    hideGroupModal();
                }
            });
        }

        function updateNombreField() {
            var nombreField = document.getElementById('nombreField');
            var nombreInput = document.getElementById('nombre_grupo');
            var actionInput = document.querySelector('input[name="action"]');

            if (!actionInput) {
                return;
            }

            var action = actionInput.value;

            if (action === 'create') {
                if (nombreField) nombreField.style.display = 'block';
                if (nombreInput) {
                    nombreInput.required = true;
                    nombreInput.disabled = false;
                }
            } else {
                if (nombreField) nombreField.style.display = 'none';
                if (nombreInput) {
                    nombreInput.required = false;
                    nombreInput.disabled = true;
                }
            }
        }

        updateNombreField();

        function handleAjaxDeleteBlock(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                var performDelete = function() {
                    var token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    if (!token) {
                        if (window.AppModal) {
                            AppModal.alert('Error',
                                'No se pudo obtener CSRF token. Recarga la página e inténtalo de nuevo.');
                        } else {
                            alert('No se pudo obtener el token CSRF.');
                        }
                        return;
                    }

                    var formData = new FormData(form);
                    formData.append('_method', 'DELETE');

                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: formData
                    }).then(function(response) {
                        if (!response.ok) {
                            return response.json().then(function(body) {
                                throw new Error(body.message ||
                                    'No se pudo eliminar el bloque.');
                            }).catch(function() {
                                throw new Error('No se pudo eliminar el bloque.');
                            });
                        }
                        return response.json();
                    }).then(function(result) {
                        if (result && result.status === 'success') {
                            var blockEl = form.closest('.relative');
                            if (blockEl) {
                                blockEl.style.transition = 'opacity 0.3s, transform 0.3s';
                                blockEl.style.opacity = '0';
                                blockEl.style.transform = 'scale(0.95)';
                                setTimeout(function() {
                                    blockEl.remove();
                                }, 300);
                            }
                            if (window.showToast) {
                                window.showToast(result.message || 'Bloque eliminado correctamente.',
                                    'success');
                            }
                        } else {
                            throw new Error(result.message || 'No se pudo eliminar el bloque.');
                        }
                    }).catch(function(error) {
                        console.error('Error al eliminar el bloque:', error);
                        if (window.AppModal) {
                            AppModal.alert('Error', error.message ||
                                'Error al eliminar el bloque. Recarga la página e inténtalo nuevamente.'
                            );
                        } else {
                            alert(error.message || 'Error al eliminar el bloque.');
                        }
                    });
                };

                if (window.AppModal) {
                    AppModal.confirm('Eliminar Bloque', '¿Estás seguro de eliminar este bloque de horario?').then(
                        function(confirmed) {
                            if (confirmed) performDelete();
                        });
                } else {
                    if (confirm('¿Estás seguro de eliminar este bloque de horario?')) performDelete();
                }
            });
        }

        document.querySelectorAll('form[data-ajax-delete-block="true"]').forEach(handleAjaxDeleteBlock);
    </script>
</x-app-layout>
