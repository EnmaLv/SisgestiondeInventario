@if (request()->has('avisoAtencionCita'))
    @php
        $citaAviso = \App\Models\salud\Cita::find(request('avisoAtencionCita'));
    @endphp
    @if ($citaAviso && $citaAviso->paciente)
        <div id="avisoAtencionModal"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 dark:bg-black/70 px-3 backdrop-blur-sm transition-opacity">
            <div class="w-full max-w-xl rounded-3xl bg-white dark:bg-gray-800 shadow-2xl overflow-hidden relative">
                <div class="absolute top-0 left-0 w-full h-2 bg-yellow-500"></div>

                <div class="p-8">
                    <button onclick="document.getElementById('avisoAtencionModal').style.display='none'"
                        class="absolute right-5 top-5 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition text-2xl leading-none">✕</button>

                    <div class="text-center mb-6 mt-4">
                        <div
                            class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-yellow-100 dark:bg-yellow-900/30 mb-4">
                            <svg class="h-8 w-8 text-yellow-600 dark:text-yellow-400" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white">Aviso de Atención</h3>
                    </div>

                    <div class="text-gray-700 dark:text-gray-300 space-y-3 mb-8 text-center px-2">
                        <p class="text-lg">
                            Has <strong>rechazado o cancelado</strong> múltiples citas relacionadas al paciente <strong
                                class="text-sky-700 dark:text-sky-400">{{ $citaAviso->paciente->name }}</strong>.
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Este es un recordatorio opcional del sistema. Si consideras que el paciente merece
                            preferencia por la recurrencia de cancelaciones de tu parte, puedes elevar su prioridad.
                        </p>
                        <div
                            class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-4 mt-4 border border-gray-100 dark:border-gray-700">
                            <p class="font-semibold text-gray-800 dark:text-gray-200">
                                ¿Deseas cambiar la prioridad de sus solicitudes pendientes a "Alta"?
                            </p>
                        </div>
                    </div>

                    <div class="flex sm:flex-row flex-col justify-center gap-3 mt-8">
                        <form method="POST" action="{{ route('citas.update_alerta_prioridad', $citaAviso) }}"
                            class="w-full sm:w-1/2">
                            @csrf
                            <input type="hidden" name="prioridad" value="alta">
                            <button type="submit"
                                class="w-full px-4 py-3 bg-yellow-500 text-white font-bold text-sm rounded-xl hover:bg-yellow-600 transition shadow-sm">
                                Sí, cambiar a Alta
                            </button>
                        </form>

                        <button type="button"
                            onclick="document.getElementById('avisoAtencionModal').style.display='none'"
                            class="w-full sm:w-1/2 px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 font-bold text-sm rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                            No, ignorar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif
