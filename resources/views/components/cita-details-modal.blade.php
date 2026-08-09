<div id="citaDetailsModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm px-3 transition-all duration-300">
    <div class="w-full max-w-5xl rounded-3xl bg-white dark:bg-gray-800 shadow-2xl overflow-hidden border border-slate-100 dark:border-gray-700 transform transition-all flex flex-col max-h-[90vh]">
        <!-- Header Compacto -->
        <div class="bg-sky-700 dark:bg-sky-900 text-white px-6 py-4 relative shadow-md shrink-0">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button id="prevCitaBtn" type="button" class="flex items-center justify-center text-sky-100 hover:text-white bg-sky-800/40 hover:bg-sky-600/60 rounded-xl h-10 w-10 transition-all active:scale-95 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <div class="flex flex-col">
                        <span class="text-[10px] font-black uppercase tracking-widest text-sky-100 opacity-80">Gestión de Cita</span>
                        <h3 class="text-xl font-black tracking-tight leading-tight">Expediente Rápido</h3>
                    </div>
                    <button id="nextCitaBtn" type="button" class="flex items-center justify-center text-sky-100 hover:text-white bg-sky-800/40 hover:bg-sky-600/60 rounded-xl h-10 w-10 transition-all active:scale-95 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
                <button id="closeCitaModal" type="button" class="h-10 w-10 flex items-center justify-center rounded-xl bg-sky-800/40 hover:bg-rose-500 hover:text-white text-sky-100 transition-colors shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <div class="p-6 lg:p-8 overflow-y-auto custom-scrollbar flex-1">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">

                <!-- COLUMNA IZQUIERDA: Perfil y Motivo -->
                <div class="lg:col-span-5 space-y-6">
                    <div class="flex items-center gap-5 p-3 bg-slate-50/50 dark:bg-gray-700/50 rounded-3xl border border-slate-100/80 dark:border-gray-700 shadow-sm">
                        <div class="relative shrink-0">
                            <div class="flex items-center justify-center h-16 w-16 rounded-2xl overflow-hidden bg-gradient-to-br from-sky-500 to-blue-600 text-white font-black text-xl shadow-lg shadow-sky-100 dark:shadow-sky-900/30 border-2 border-white dark:border-gray-700" id="citaAvatarContainer">
                                <img id="citaAvatarImg" src="" alt="Foto de perfil" class="w-full h-full object-cover hidden">
                                <span id="citaAvatarText" class="text-white font-black text-xl">RT</span>
                            </div>
                            <div class="absolute -bottom-1 -right-1 h-5 w-5 bg-emerald-500 border-2 border-white dark:border-gray-700 rounded-full shadow-sm"></div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="px-2 py-0.5 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-400 rounded-lg text-[9px] font-black uppercase tracking-wider mb-1 inline-block">Paciente</span>
                            <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight leading-none truncate" id="citaPacienteName">-</h2>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 border border-slate-100 dark:border-gray-700 rounded-3xl p-6 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21L14.017 18C14.017 16.8954 14.9124 16 16.017 16H19.017C19.5693 16 20.017 15.5523 20.017 15V9C20.017 8.44772 19.5693 8 19.017 8H16.017C15.4647 8 15.017 8.44772 15.017 9V12C15.017 12.5523 14.5693 13 14.017 13H13.017V21H14.017ZM6.017 21L6.017 18C6.017 16.8954 6.91243 16 8.017 16H11.017C11.5693 16 12.017 15.5523 12.017 15V9C12.017 8.44772 11.5693 8 11.017 8H8.017C7.46472 8 7.017 8.44772 7.017 9V12C7.017 12.5523 6.5693 13 6.017 13H5.017V21H6.017Z" /></svg>
                        </div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-sky-600 dark:text-sky-400 mb-3 flex items-center gap-2">
                            <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
                            Motivo de la consulta
                        </h4>
                        <p id="citaMotivo" class="text-sm text-slate-600 dark:text-gray-300 leading-relaxed font-medium italic border-l-2 border-sky-100 dark:border-sky-800 pl-4">-</p>
                    </div>

                    <!-- Botón de Horario del Paciente -->
                    <div id="citaHorarioContainer" class="hidden">
                        <a id="citaHorarioLink" href="#" target="_blank" class="flex items-center justify-center gap-2 w-full bg-slate-50 hover:bg-slate-100 text-slate-700 dark:bg-gray-800/50 dark:hover:bg-gray-700/50 dark:text-gray-300 border border-slate-200 dark:border-gray-700 py-3 rounded-2xl font-black text-[11px] uppercase tracking-widest transition-all shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            Ver Horario del Paciente
                        </a>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: Detalles y Prioridad -->
                <div class="lg:col-span-7 space-y-6">
                    <div class="grid grid-cols-3 gap-3">
                        <div class="bg-slate-50/50 dark:bg-gray-700/30 border border-slate-100/50 dark:border-gray-700 p-4 rounded-3xl transition-all hover:bg-white dark:hover:bg-gray-700 hover:shadow-sm">
                            <div class="text-sky-500 dark:text-sky-400 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Solicitud</p>
                            <p id="citaFechaSolicitud" class="text-xs font-black text-slate-700 dark:text-gray-300">-</p>
                        </div>
                        <div class="bg-slate-50/50 dark:bg-gray-700/30 border border-slate-100/50 dark:border-gray-700 p-4 rounded-3xl transition-all hover:bg-white dark:hover:bg-gray-700 hover:shadow-sm">
                            <div class="text-emerald-500 dark:text-emerald-400 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Cita</p>
                            <p id="citaFechaConfirmada" class="text-xs font-black text-slate-700 dark:text-gray-300">-</p>
                        </div>
                        <div class="bg-slate-50/50 dark:bg-gray-700/30 border border-slate-100/50 dark:border-gray-700 p-4 rounded-3xl transition-all hover:bg-white dark:hover:bg-gray-700 hover:shadow-sm">
                            <div class="text-amber-500 dark:text-amber-400 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            </div>
                            <p class="text-[9px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Bloque</p>
                            <p id="citaBloqueConfirmado" class="text-xs font-black text-slate-700 dark:text-gray-300">En espera</p>
                        </div>
                    </div>

                    <div class="bg-slate-50/30 dark:bg-gray-700/20 border border-slate-100 dark:border-gray-700 rounded-3xl p-5 space-y-4">
                        <div class="flex items-center justify-between">
                            <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-gray-500">Prioridad de Atención</h4>
                            <div class="flex items-center gap-2 bg-white dark:bg-gray-800 px-3 py-1 rounded-full border border-slate-100 dark:border-gray-700 shadow-sm" id="citaPrioridadDisplay">
                                <span id="citaPrioridadDot" class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                <span id="citaPrioridadTexto" class="text-[9px] font-black text-slate-600 dark:text-gray-300 uppercase tracking-tighter">Media</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2" id="citaPrioridadRadios">
                            @foreach($prioridadesDisponibles as $prioridad)
                                @php
                                    $colorClass = match(strtolower($prioridad->nombre)) {
                                        'crítica' => 'text-rose-700 dark:text-rose-400 group-has-[:checked]:text-rose-700 dark:group-has-[:checked]:text-rose-400 border-rose-500 bg-rose-50 dark:bg-rose-900/30',
                                        'alta' => 'text-amber-700 dark:text-amber-400 group-has-[:checked]:text-amber-700 dark:group-has-[:checked]:text-amber-400 border-amber-500 bg-amber-50 dark:bg-amber-900/30',
                                        'media' => 'text-sky-700 dark:text-sky-400 group-has-[:checked]:text-sky-700 dark:group-has-[:checked]:text-sky-400 border-sky-500 bg-sky-50 dark:bg-sky-900/30',
                                        'baja' => 'text-emerald-700 dark:text-emerald-400 group-has-[:checked]:text-emerald-700 dark:group-has-[:checked]:text-emerald-400 border-emerald-500 bg-emerald-50 dark:bg-emerald-900/30',
                                        default => 'text-indigo-700 dark:text-indigo-400 group-has-[:checked]:text-indigo-700 dark:group-has-[:checked]:text-indigo-400 border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30',
                                    };
                                @endphp
                                <label class="group relative flex flex-col items-center justify-center p-2.5 rounded-xl border-2 border-white dark:border-gray-700 bg-white dark:bg-gray-800 cursor-pointer transition-all hover:border-slate-200 dark:hover:border-gray-600 shadow-sm has-[:checked]:border-{{ strtolower($prioridad->nombre) === 'crítica' ? 'rose' : (strtolower($prioridad->nombre) === 'alta' ? 'amber' : (strtolower($prioridad->nombre) === 'baja' ? 'emerald' : (strtolower($prioridad->nombre) === 'media' ? 'sky' : 'indigo'))) }}-500 has-[:checked]:bg-{{ strtolower($prioridad->nombre) === 'crítica' ? 'rose' : (strtolower($prioridad->nombre) === 'alta' ? 'amber' : (strtolower($prioridad->nombre) === 'baja' ? 'emerald' : (strtolower($prioridad->nombre) === 'media' ? 'sky' : 'indigo'))) }}-50 dark:has-[:checked]:bg-{{ strtolower($prioridad->nombre) === 'crítica' ? 'rose' : (strtolower($prioridad->nombre) === 'alta' ? 'amber' : (strtolower($prioridad->nombre) === 'baja' ? 'emerald' : (strtolower($prioridad->nombre) === 'media' ? 'sky' : 'indigo'))) }}-900/30">
                                    <input type="radio" name="prioridad" value="{{ $prioridad->nombre }}" class="prioridad-radio hidden">
                                    <span class="text-[9px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-tight group-has-[:checked]:{{ explode(' ', $colorClass)[0] }} {{ explode(' ', $colorClass)[1] ?? '' }}">{{ $prioridad->nombre }}</span>
                                </label>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <div id="prioridadMensaje" class="flex items-center gap-1.5 text-[10px] font-black text-emerald-600 dark:text-emerald-400 hidden animate-pulse">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                Guardado
                            </div>
                            <button id="guardarPrioridadBtn" class="flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-4 py-2 rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md shadow-sky-100 dark:shadow-sky-900/30 transition-all active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                                Guardar Cambios
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sección de Sugerencias Única -->
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-gray-700">
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <div class="h-4 w-1 bg-sky-500 rounded-full"></div>
                        <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-gray-400">Bloques Sugeridos por el Paciente</h4>
                    </div>
                    <div id="citaBloquesSugeridos" class="flex flex-wrap gap-2 min-h-[32px]"></div>
                </div>
            </div>

            <!-- Sección de Propuesta del Psicólogo y Respuestas -->
            <div class="mt-6 pt-6 border-t border-slate-100 dark:border-gray-700 space-y-4">
                <div class="flex items-center gap-2">
                    <div class="h-4 w-1 bg-indigo-500 rounded-full"></div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-gray-400">Propuestas de Cambio de Horario</h4>
                </div>
                
                <div id="citaPropuestaInfo" class="p-4 rounded-2xl border hidden text-xs font-semibold">
                    <!-- Contenido dinámico desde JS -->
                </div>

                <div id="citaPropuestaAcciones" class="flex gap-2 hidden">
                    <button type="button" id="enviarPropuestaBtn" class="flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl font-black text-[9px] uppercase tracking-widest shadow-md shadow-sky-100 dark:shadow-sky-900/30 transition-all active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Enviar propuesta al paciente
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
