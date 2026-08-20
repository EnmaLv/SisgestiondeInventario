<x-app-layout>
    <div class="py-6 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (!empty($tieneCitaPendiente))
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl border border-blue-200 dark:border-blue-800 overflow-hidden">
                    <div class="p-8 text-center space-y-4">
                        <div
                            class="mx-auto w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/40 flex items-center justify-center">
                            <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Ya tienes una solicitud activa</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm max-w-md mx-auto">No puedes enviar otra
                            solicitud hasta que tu cita actual sea procesada, cancelada o finalizada.</p>
                        <a href="{{ route('admin.psicologia.maestros.citas.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold text-sm transition shadow-lg shadow-blue-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Ver mis citas
                        </a>
                    </div>
                </div>
            @elseif($psicologos->isEmpty())
                <div
                    class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl border border-yellow-200 dark:border-yellow-800 overflow-hidden">
                    <div class="p-8 text-center space-y-4">
                        <div
                            class="mx-auto w-16 h-16 rounded-full bg-yellow-100 dark:bg-yellow-900/40 flex items-center justify-center">
                            <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white">Sin psicólogos disponibles</h2>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">No hay psicólogos con horarios activos en
                            este momento. Vuelve más tarde.</p>
                        <a href="{{ route('admin.psicologia.maestros.citas.index') }}"
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-semibold text-sm transition">
                            ← Volver
                        </a>
                    </div>
                </div>
            @else
                {{-- Formulario principal --}}
                <form method="POST" action="{{ route('admin.psicologia.maestros.citas.store') }}" id="citaForm">
                    @csrf
                    <input type="hidden" name="fecha_solicitada" id="fecha_solicitada"
                        value="{{ old('fecha_solicitada') }}">
                    <input type="hidden" name="bloques_sugeridos" id="bloques_sugeridos"
                        value="{{ old('bloques_sugeridos') }}">

                    <div class="space-y-5">

                        {{-- Errores --}}
                        @if ($errors->any())
                            <div
                                class="p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-2xl text-sm">
                                <strong>Corrige los errores:</strong>
                                <ul class="mt-1 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- PASO 1: Selección de Psicólogo --}}
                        <div
                            class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700">
                            <div class="p-6 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-black">
                                        1</div>
                                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Elige tu psicólogo</h3>
                                </div>

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="psicologoCards">
                                    @foreach ($psicologos as $psicologo)
                                        @php
                                            $firstName = explode(' ', trim($psicologo->nombre_persona ?? ''))[0] ?? '';
                                            $firstLastName = explode(' ', trim($psicologo->apellido_persona ?? ''))[0] ?? '';
                                            $fullName = trim("$firstName $firstLastName") ?: $psicologo->username;

                                            $photoPath = $psicologo->profile_photo_path ?? null;
                                            $hasPhoto = !empty($photoPath);

                                            $initialN = mb_substr($firstName ?: $psicologo->username, 0, 1);
                                            $initialA = mb_substr($firstLastName, 0, 1);
                                            $initials = strtoupper($initialN . $initialA);
                                        @endphp
                                        <button type="button"
                                            class="psicologo-card group relative flex items-center gap-4 p-4 rounded-2xl border-2 border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-700/50 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50/50 dark:hover:bg-blue-900/20 transition-all duration-200 text-left"
                                            data-psicologo-id="{{ $psicologo->id_usuario }}"
                                            data-psicologo-name="{{ $fullName }}"
                                            data-dias="{{ json_encode($psicologo->dias_laborables ?? []) }}"
                                            data-slots="{{ json_encode($psicologo->slots ?? []) }}">
                                            {{-- Avatar --}}
                                            <div class="flex-shrink-0">
                                                @if ($hasPhoto)
                                                    <img class="h-14 w-14 rounded-2xl object-cover ring-2 ring-white dark:ring-gray-700 shadow"
                                                        src="{{ route('media.profile_photos', basename($photoPath)) }}"
                                                        alt="{{ $fullName }}">
                                                @else
                                                    <div
                                                        class="h-14 w-14 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white text-lg font-bold shadow ring-2 ring-white dark:ring-gray-700">
                                                        {{ $initials }}
                                                    </div>
                                                @endif
                                            </div>
                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="font-bold text-gray-800 dark:text-white text-sm truncate">
                                                    {{ $fullName }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Psicólogo</p>
                                            </div>
                                            {{-- Checkmark --}}
                                            <div
                                                class="psicologo-check hidden flex-shrink-0 w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-500/30">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="3" d="M5 13l4 4L19 7" />
                                                </svg>
                                            </div>
                                        </button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="psicologo_id" id="psicologo_id"
                                    value="{{ old('psicologo_id') }}">
                            </div>
                        </div>

                        {{-- PASO 2: Selección de Fecha (Calendario Mensual) --}}
                        <div id="paso2"
                            class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300"
                            style="display:none;">
                            <div class="p-6 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-black">
                                        2</div>
                                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Selecciona los días</h3>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Selecciona los días en los que
                                    podrías asistir. Por defecto no hay días seleccionados. Haz clic en los días del
                                    calendario para marcarlos.</p>
                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4">
                                    <h4 id="calMonthLabel"
                                        class="text-center font-bold text-gray-700 dark:text-gray-200 uppercase tracking-wide mb-4">
                                    </h4>
                                    <div
                                        class="grid grid-cols-7 gap-1 text-center text-xs font-bold text-gray-400 dark:text-gray-500 mb-2">
                                        <div>Dom</div>
                                        <div>Lun</div>
                                        <div>Mar</div>
                                        <div>Mié</div>
                                        <div>Jue</div>
                                        <div>Vie</div>
                                        <div>Sáb</div>
                                    </div>
                                    <div id="calendarGrid" class="grid grid-cols-7 gap-1"></div>
                                </div>
                            </div>
                        </div>

                        {{-- PASO 3: Selección de Horarios Preferidos --}}
                        <div id="paso3"
                            class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300"
                            style="display:none;">
                            <div class="p-6 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-black">
                                        3</div>
                                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Tus horarios preferidos
                                    </h3>
                                </div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Sugiérenos en qué horario te es más
                                    factible una cita. Puedes seleccionar varios. Esto no asegura que te pueda atender
                                    en el momento exacto, pero nos ayuda a ubicarte.</p>

                                <div id="slotsContainer" class="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-4"></div>
                                <div id="slotsLoading" class="hidden flex items-center justify-center py-6">
                                    <svg class="animate-spin h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Cargando
                                        horarios...</span>
                                </div>
                                <p id="slotsEmpty"
                                    class="hidden text-sm text-gray-400 dark:text-gray-500 text-center py-4 italic">El
                                    psicólogo no tiene horarios definidos.</p>
                            </div>
                        </div>

                        {{-- PASO 4: Motivo --}}
                        <div id="paso4"
                            class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300"
                            style="display:none;">
                            <div class="p-6 space-y-4">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-600 text-white text-sm font-black">
                                        4</div>
                                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">Motivo de consulta</h3>
                                </div>

                                <textarea id="motivo" name="motivo" rows="3" maxlength="100"
                                    class="w-full bg-white dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm p-4 rounded-xl border border-gray-200 dark:border-gray-600 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all resize-none shadow-sm"
                                    placeholder="Describe brevemente el motivo de tu consulta..." required>{{ old('motivo') }}</textarea>
                                <p class="text-xs text-gray-400 dark:text-gray-500 text-right"><span
                                        id="motivoCount">0</span>/100</p>
                            </div>
                        </div>

                        {{-- Resumen y Enviar --}}
                        <div id="pasoResumen"
                            class="bg-white dark:bg-gray-800 shadow-xl rounded-3xl overflow-hidden border border-gray-100 dark:border-gray-700 transition-all duration-300"
                            style="display:none;">
                            <div class="p-6 space-y-4">
                                <h3 class="font-bold text-gray-800 dark:text-white text-lg flex items-center gap-2">
                                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Resumen de tu solicitud
                                </h3>

                                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-2xl p-4 space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Psicólogo</span>
                                        <span id="resumenPsicologo"
                                            class="font-semibold text-gray-800 dark:text-white"></span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Días propuestos</span>
                                        <span id="resumenExcepciones"
                                            class="font-semibold text-gray-800 dark:text-white text-right break-words max-w-[60%]">Ninguno</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 dark:text-gray-400">Horarios</span>
                                        <span id="resumenBloques"
                                            class="font-semibold text-gray-800 dark:text-white text-right break-words max-w-[60%]"></span>
                                    </div>
                                </div>

                                <div class="flex flex-col items-end gap-2 pt-2">
                                    <p id="minBlocksHelpText"
                                        class="text-xs text-red-500 dark:text-red-400 font-medium hidden">
                                        Debes sugerir al menos 2 bloques horarios para darle opciones al psicólogo.
                                    </p>
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.psicologia.maestros.citas.index') }}"
                                            class="px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-xl font-semibold text-sm hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                            Cancelar
                                        </a>
                                        <button type="submit" id="submitBtn"
                                            class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold text-sm transition shadow-lg shadow-blue-500/20 disabled:opacity-50 disabled:cursor-not-allowed"
                                            disabled>
                                            Enviar solicitud
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
            @endif

            {{-- Modal de advertencia de cambios no guardados --}}
            <div x-data="{ showUnsavedModal: false, pendingUrl: '' }"
                @trigger-unsaved.window="showUnsavedModal = true; pendingUrl = $event.detail.url"
                x-show="showUnsavedModal" class="fixed inset-0 overflow-y-auto" style="z-index: 9999;" x-cloak>
                <div class="flex items-center justify-center min-h-screen px-4 text-center">
                    <div x-show="showUnsavedModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        class="fixed inset-0 transition-opacity bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm"
                        @click="showUnsavedModal = false"></div>

                    <div x-show="showUnsavedModal" x-transition:enter="ease-out duration-300"
                        x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="ease-in duration-200"
                        x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                        class="relative inline-block w-full max-w-sm p-8 overflow-hidden text-center transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-[32px] border border-slate-100 dark:border-gray-700 z-10">

                        <div
                            class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-50 dark:bg-amber-900/30 mb-6 text-amber-500 dark:text-amber-400">
                            <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                                </path>
                            </svg>
                        </div>

                        <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">¿Estás seguro
                            que deseas salir?</h3>
                        <p class="text-sm text-slate-500 dark:text-gray-400 mb-8 font-medium">Hay información aún no
                            guardada. Si sales ahora, perderás los cambios realizados.</p>

                        <div class="flex justify-center gap-4">
                            <button type="button" @click="showUnsavedModal = false"
                                class="px-6 py-3 bg-slate-50 dark:bg-gray-700 text-slate-600 dark:text-gray-300 font-bold text-sm rounded-xl hover:bg-slate-100 dark:hover:bg-gray-600 transition-colors uppercase tracking-widest w-full">
                                Cancelar
                            </button>
                            <button type="button" @click="if (pendingUrl) window.location.href = pendingUrl"
                                class="px-6 py-3 bg-amber-500 dark:bg-amber-600 hover:bg-amber-600 dark:hover:bg-amber-700 text-white font-bold text-sm rounded-xl transition-colors shadow-lg shadow-amber-500/20 uppercase tracking-widest w-full">
                                Salir
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            const oneMonthLater = new Date(today);
            oneMonthLater.setMonth(oneMonthLater.getMonth() + 1);

            const state = {
                psicologoId: document.getElementById('psicologo_id')?.value || null,
                psicologoName: '',
                startDate: today,
                endDate: oneMonthLater,
                diasSeleccionados: [],
                selectedSlotsByDate: {},
                disponibilidad: {},
                activeDay: null
            };

            let isFormSubmitting = false;

            const paso2 = document.getElementById('paso2');
            const paso3 = document.getElementById('paso3');
            const paso4 = document.getElementById('paso4');
            const pasoResumen = document.getElementById('pasoResumen');
            const calendarGrid = document.getElementById('calendarGrid');
            const calMonthLabel = document.getElementById('calMonthLabel');
            const slotsContainer = document.getElementById('slotsContainer');
            const slotsLoading = document.getElementById('slotsLoading');
            const slotsEmpty = document.getElementById('slotsEmpty');
            const hiddenFecha = document.getElementById('fecha_solicitada');
            const hiddenBloques = document.getElementById('bloques_sugeridos');
            const hiddenPsicologoId = document.getElementById('psicologo_id');
            const submitBtn = document.getElementById('submitBtn');
            const motivoInput = document.getElementById('motivo');
            const motivoCount = document.getElementById('motivoCount');
            const form = document.getElementById('citaForm');

            const MESES = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre',
                'Octubre', 'Noviembre', 'Diciembre'
            ];

            function pad(n) {
                return n < 10 ? '0' + n : '' + n;
            }

            function toYMD(d) {
                return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
            }

            function isWeekday(d) {
                const day = d.getDay();
                return day >= 1 && day <= 5;
            }

            function showStep(el) {
                if (el && el.style.display === 'none') {
                    el.style.display = '';
                    el.style.opacity = '0';
                    requestAnimationFrame(() => {
                        el.style.transition = 'opacity 0.3s ease';
                        el.style.opacity = '1';
                    });
                }
            }

            function hideStep(el) {
                if (el) el.style.display = 'none';
            }

            function updateSummary() {
                document.getElementById('resumenPsicologo').textContent = state.psicologoName || '-';

                if (state.diasSeleccionados.length > 0) {
                    document.getElementById('resumenExcepciones').textContent = state.diasSeleccionados.length +
                        ' días seleccionados';
                } else {
                    document.getElementById('resumenExcepciones').textContent = 'Ninguno';
                }

                let allSelectedSlotsCount = 0;
                let daysWithSlotsCount = 0;
                let slotsForResumen = [];
                let blocksStringParts = [];

                state.diasSeleccionados.sort().forEach(ymd => {
                    if (state.selectedSlotsByDate[ymd] && state.selectedSlotsByDate[ymd].length > 0) {
                        allSelectedSlotsCount += state.selectedSlotsByDate[ymd].length;
                        daysWithSlotsCount++;
                        slotsForResumen.push(`${ymd} (${state.selectedSlotsByDate[ymd].length} bloques)`);
                        blocksStringParts.push(`${ymd}: ${state.selectedSlotsByDate[ymd].join(', ')}`);
                    }
                });

                if (allSelectedSlotsCount > 0) {
                    document.getElementById('resumenBloques').textContent = slotsForResumen.join(', ');
                } else {
                    document.getElementById('resumenBloques').textContent = '-';
                }

                const isValidDays = state.diasSeleccionados.length >= 2;
                const isValidSlots = state.diasSeleccionados.length > 0 && daysWithSlotsCount === state
                    .diasSeleccionados.length;
                const isValidMotivo = motivoInput?.value.trim().length > 0;

                const canSubmit = state.psicologoId && isValidDays && isValidSlots && isValidMotivo;
                if (submitBtn) submitBtn.disabled = !canSubmit;

                const helpText = document.getElementById('minBlocksHelpText');
                if (helpText) {
                    if (!isValidDays || !isValidSlots) {
                        helpText.classList.remove('hidden');
                        helpText.textContent =
                            "Debes seleccionar al menos 2 días, y elegir mínimo un bloque de horario por cada día.";
                    } else {
                        helpText.classList.add('hidden');
                    }
                }

                const daysStr = state.diasSeleccionados.length > 0 ? "Días propuestos: " + state.diasSeleccionados
                    .sort().join(', ') + " | " : "Días propuestos: Ninguno | ";
                const slotsStr = "Horarios propuestos: " + (blocksStringParts.length > 0 ? blocksStringParts.join(
                    '; ') : "Ninguno");
                hiddenBloques.value = daysStr + slotsStr;
                hiddenFecha.value = state.diasSeleccionados.length > 0 ? state.diasSeleccionados[0] : toYMD(state
                    .startDate);
            }

            document.addEventListener('click', (e) => {
                let link = e.target.closest('a');
                if (link && link.href && !link.href.includes('#') && link.target !== '_blank' && !link
                    .hasAttribute('download')) {
                    if (e.target.closest('[x-show="showUnsavedModal"]')) return;

                    if (state.psicologoId && !isFormSubmitting) {
                        e.preventDefault();
                        e.stopPropagation();
                        window.dispatchEvent(new CustomEvent('trigger-unsaved', {
                            detail: {
                                url: link.href
                            }
                        }));
                    }
                }
            }, {
                capture: true
            });

            document.querySelectorAll('.psicologo-card').forEach(card => {
                card.addEventListener('click', function() {
                    document.querySelectorAll('.psicologo-card').forEach(c => {
                        c.classList.remove('border-blue-500', 'dark:border-blue-400',
                            'bg-blue-50', 'dark:bg-blue-900/20', 'ring-2',
                            'ring-blue-500/30');
                        c.classList.add('border-gray-100', 'dark:border-gray-700');
                        c.querySelector('.psicologo-check')?.classList.add('hidden');
                    });

                    this.classList.remove('border-gray-100', 'dark:border-gray-700');
                    this.classList.add('border-blue-500', 'dark:border-blue-400', 'bg-blue-50',
                        'dark:bg-blue-900/20', 'ring-2', 'ring-blue-500/30');
                    this.querySelector('.psicologo-check')?.classList.remove('hidden');

                    state.psicologoId = this.dataset.psicologoId;
                    state.psicologoName = this.dataset.psicologoName;
                    hiddenPsicologoId.value = state.psicologoId;

                    state.diasSeleccionados = [];
                    state.selectedSlotsByDate = {};
                    state.disponibilidad = {};
                    state.activeDay = null;

                    showStep(paso2);
                    if (calendarGrid) {
                        calendarGrid.innerHTML =
                            '<div class="col-span-7 text-center py-4 text-gray-500 text-sm">Cargando disponibilidad...</div>';
                    }

                    fetch(`{{ route('admin.psicologia.maestros.citas.available_slots') }}?psicologo_id=${state.psicologoId}`, {
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(async r => {
                            if (!r.ok) {
                                const errText = await r.text();
                                console.error("HTTP Error", r.status, errText);
                                throw new Error("HTTP " + r.status);
                            }
                            return r.json();
                        })
                        .then(data => {
                            state.disponibilidad = data.disponibilidad || {};
                            renderCalendar();
                            renderSlotsForActiveDay();

                            showStep(paso4);
                            showStep(pasoResumen);
                            updateSummary();

                            setTimeout(() => paso2.scrollIntoView({
                                behavior: 'smooth',
                                block: 'start'
                            }), 100);
                        })
                        .catch(err => {
                            console.error('Error fetching availability:', err);
                            if (calendarGrid) {
                                calendarGrid.innerHTML =
                                    '<div class="col-span-7 text-center py-4 text-red-500 text-sm">Error cargando disponibilidad.</div>';
                            }
                        });
                });
            });

            function renderCalendar() {
                if (!calendarGrid) return;
                calendarGrid.innerHTML = '';

                calMonthLabel.textContent =
                    `${state.startDate.getDate()} de ${MESES[state.startDate.getMonth()]} - ${state.endDate.getDate()} de ${MESES[state.endDate.getMonth()]}`;

                let dCounter = new Date(state.startDate);
                const allDays = [];

                while (dCounter <= state.endDate) {
                    allDays.push(new Date(dCounter));
                    dCounter.setDate(dCounter.getDate() + 1);
                }

                const firstDayOfWeek = allDays[0].getDay();
                for (let i = 0; i < firstDayOfWeek; i++) {
                    const emptyCell = document.createElement('div');
                    emptyCell.className = 'py-2';
                    calendarGrid.appendChild(emptyCell);
                }

                allDays.forEach(d => {
                    const ymd = toYMD(d);
                    const isWkday = isWeekday(d);
                    const isAvailable = state.disponibilidad[ymd] && state.disponibilidad[ymd].length > 0;
                    const isActive = state.activeDay === ymd;
                    const isSelected = state.diasSeleccionados.includes(ymd);

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.dataset.date = ymd;

                    let baseClasses =
                        'relative flex items-center justify-center h-10 w-full rounded-xl text-sm font-bold transition-all duration-200 ';

                    if (!isAvailable) {
                        baseClasses +=
                            'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed opacity-50';
                    } else if (isSelected) {
                        baseClasses +=
                            'bg-blue-100 dark:bg-blue-900/40 text-blue-700 dark:text-blue-300 hover:bg-blue-200 dark:hover:bg-blue-800/60 cursor-pointer shadow-sm border-2 border-blue-400';
                    } else {
                        baseClasses +=
                            'bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400 border-2 border-gray-200 dark:border-gray-600 hover:border-gray-300 cursor-pointer';
                    }

                    if (isActive) {
                        baseClasses += ' ring-2 ring-blue-500 ring-offset-2 dark:ring-offset-gray-900';
                    }

                    btn.className = baseClasses;
                    btn.innerHTML = `<span>${d.getDate()}</span>`;

                    if (isSelected && state.selectedSlotsByDate[ymd] && state.selectedSlotsByDate[ymd]
                        .length > 0) {
                        btn.innerHTML +=
                            '<span class="absolute top-1 right-1 w-2 h-2 bg-green-500 rounded-full"></span>';
                    }

                    if (isAvailable) {
                        btn.addEventListener('click', () => toggleDate(ymd));
                    }
                    calendarGrid.appendChild(btn);
                });
            }

            function toggleDate(ymd) {
                const isSelected = state.diasSeleccionados.includes(ymd);
                if (isSelected) {
                    if (state.activeDay === ymd) {
                        state.diasSeleccionados = state.diasSeleccionados.filter(d => d !== ymd);
                        delete state.selectedSlotsByDate[ymd];
                        state.activeDay = state.diasSeleccionados.length > 0 ? state.diasSeleccionados[state
                            .diasSeleccionados.length - 1] : null;
                    } else {
                        state.activeDay = ymd;
                    }
                } else {
                    state.diasSeleccionados.push(ymd);
                    state.activeDay = ymd;
                }

                renderCalendar();
                renderSlotsForActiveDay();
                updateSummary();
            }

            function renderSlotsForActiveDay() {
                slotsContainer.innerHTML = '';
                slotsEmpty.classList.add('hidden');
                showStep(paso3);

                const titlePaso3 = paso3.querySelector('h3');
                if (!state.activeDay || state.diasSeleccionados.length === 0) {
                    if (titlePaso3) titlePaso3.textContent = 'Tus horarios preferidos';
                    const msg = document.getElementById('slotsEmpty');
                    if (msg) {
                        msg.textContent =
                            'Selecciona un día en el calendario arriba para ver y elegir sus horarios disponibles.';
                        msg.classList.remove('hidden');
                    }
                    return;
                }

                const d = new Date(state.activeDay + 'T12:00:00');
                const diasLargo = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
                const dayName = diasLargo[d.getDay()];
                if (titlePaso3) titlePaso3.textContent = `Horarios para el ${dayName} ${d.getDate()}`;

                const slots = state.disponibilidad[state.activeDay] || [];

                if (slots.length === 0) {
                    slotsEmpty.classList.remove('hidden');
                    return;
                }

                if (!state.selectedSlotsByDate[state.activeDay]) {
                    state.selectedSlotsByDate[state.activeDay] = [];
                }

                slots.forEach(slot => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.dataset.slot = slot;
                    btn.className =
                        'slot-btn flex items-center justify-center gap-2 px-4 py-3 rounded-2xl border-2 border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50 text-sm font-semibold text-gray-700 dark:text-gray-200 hover:border-blue-400 dark:hover:border-blue-500 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200';

                    const icon = document.createElement('span');
                    icon.innerHTML =
                        '<svg class="w-4 h-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';

                    const text = document.createElement('span');
                    text.textContent = slot;

                    btn.appendChild(icon);
                    btn.appendChild(text);

                    if (state.selectedSlotsByDate[state.activeDay].includes(slot)) {
                        btn.classList.remove('border-gray-100', 'dark:border-gray-700', 'bg-gray-50',
                            'dark:bg-gray-700/50', 'text-gray-700', 'dark:text-gray-200');
                        btn.classList.add('border-blue-500', 'bg-blue-600', 'text-white', 'shadow-lg',
                            'shadow-blue-500/30', 'scale-[1.03]');
                        icon.classList.replace('text-gray-400', 'text-white');
                        icon.classList.replace('dark:text-gray-500', 'text-white');
                    }

                    btn.addEventListener('click', () => toggleSlot(slot));
                    slotsContainer.appendChild(btn);
                });
            }

            function toggleSlot(slot) {
                const ad = state.activeDay;
                if (!ad) return;

                if (!state.selectedSlotsByDate[ad]) {
                    state.selectedSlotsByDate[ad] = [];
                }

                if (state.selectedSlotsByDate[ad].includes(slot)) {
                    state.selectedSlotsByDate[ad] = state.selectedSlotsByDate[ad].filter(s => s !== slot);
                } else {
                    state.selectedSlotsByDate[ad].push(slot);
                }

                renderCalendar();

                document.querySelectorAll('.slot-btn').forEach(btn => {
                    const btnSlot = btn.dataset.slot;
                    if (state.selectedSlotsByDate[ad].includes(btnSlot)) {
                        btn.classList.remove('border-gray-100', 'dark:border-gray-700', 'bg-gray-50',
                            'dark:bg-gray-700/50', 'text-gray-700', 'dark:text-gray-200');
                        btn.classList.add('border-blue-500', 'bg-blue-600', 'text-white', 'shadow-lg',
                            'shadow-blue-500/30', 'scale-[1.03]');
                        btn.querySelector('svg')?.classList.replace('text-gray-400', 'text-white');
                        btn.querySelector('svg')?.classList.replace('dark:text-gray-500', 'text-white');
                    } else {
                        btn.classList.remove('border-blue-500', 'bg-blue-600', 'text-white', 'shadow-lg',
                            'shadow-blue-500/30', 'scale-[1.03]');
                        btn.classList.add('border-gray-100', 'dark:border-gray-700', 'bg-gray-50',
                            'dark:bg-gray-700/50', 'text-gray-700', 'dark:text-gray-200');
                        btn.querySelector('svg')?.classList.replace('text-white', 'text-gray-400');
                        btn.querySelector('svg')?.classList.add('dark:text-gray-500');
                    }
                });

                updateSummary();
            }

            if (motivoInput && motivoCount) {
                motivoCount.textContent = motivoInput.value.length;
                motivoInput.addEventListener('input', () => {
                    motivoCount.textContent = motivoInput.value.length;
                    updateSummary();
                });
            }

            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (window.AppModal) {
                        window.AppModal.show(
                            'Tu bienestar es nuestra prioridad, ¿revisamos los datos una última vez?',
                            'Antes de enviar el formulario, te sugerimos confirmar que todo esté en orden. Para cuidar el tiempo de todos y garantizar que sigas recibiendo una atención prioritaria, es importante asistir a tus citas o reportar cualquier cambio con anticipación. ¡Muchas gracias por tu responsabilidad!', {
                                type: 'confirm',
                                btnText: 'Sí, quedarme',
                                icon: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>',
                                iconColor: 'bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400',
                                btnColor: 'bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200 dark:shadow-none'
                            }
                        ).then(result => {
                            if (result === false) {
                                isFormSubmitting = true;
                                form.submit();
                            }
                        });

                        const cancelBtn = document.getElementById('globalAppModalCancel');
                        if (cancelBtn) cancelBtn.textContent = 'Enviar Solicitud';

                    } else {
                        isFormSubmitting = true;
                        form.submit();
                    }
                });
            }

            if (hiddenPsicologoId?.value) {
                const card = document.querySelector(
                    `.psicologo-card[data-psicologo-id="${hiddenPsicologoId.value}"]`);
                if (card) card.click();
            }
        });
    </script>
</x-app-layout>
