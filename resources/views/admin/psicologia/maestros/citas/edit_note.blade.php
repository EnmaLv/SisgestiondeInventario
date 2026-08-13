<x-app-layout>
    <style>
        /* Forzar contraste notorio entre fondo y contenedores en modo oscuro */
        html.dark .en-bg { background-color: #020617 !important; }
        html.dark .en-header { background-color: #1e293b !important; border-bottom-color: #334155 !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.5) !important; }
        html.dark .en-card { background-color: #0f172a !important; border-color: #1e293b !important; }
    </style>
    <div class="min-h-screen bg-[#f8fafc] en-bg pb-20" x-data="clinicalNoteEditor()">
        {{-- Cabecera Contextual --}}
        <div class="bg-white en-header border-b border-slate-100 mb-8 shadow-sm">
            <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        @php
                            $paciente = $cita->paciente;
                            $nombreCompleto = $paciente->name ?? '';
                            $partes = explode(' ', trim($nombreCompleto));
                            $primerNombre = $partes[0] ?? '';
                            $primerApellido = $partes[1] ?? '';
                            $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($primerApellido, 0, 1));
                            
                            $isManual = ($cita->motivo === 'Nota de Evolución (Manual)');

                            // Datos para el modal
                            $fechaCita = ($paciente->primera_cita ?? null) ? \Carbon\Carbon::parse($paciente->primera_cita)->format('d/m/Y') : 'No disponible';
                            $edad = ($paciente->fecha_nacimiento ?? null) ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age : 'No disponible';
                            $nacimiento = ($paciente->fecha_nacimiento ?? null) ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') : 'No disponible';
                        @endphp
                        
                        <button type="button" 
                                class="open-patient-modal shrink-0 w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-indigo-100 hover:scale-105 active:scale-95 transition-all cursor-pointer"
                                data-patient-type="user"
                                data-patient-name="{{ $paciente->name }}" 
                                data-patient-email="{{ $paciente->email ?? 'No disponible' }}" 
                                data-patient-phone="{{ $paciente->telefono ?? 'No disponible' }}" 
                                data-patient-created="{{ $fechaCita }}"
                                data-patient-cedula="{{ $paciente->cedula ?? 'No disponible' }}"
                                data-patient-genero="{{ $paciente->genero ?? 'No disponible' }}"
                                data-patient-nacimiento="{{ $nacimiento }}"
                                data-patient-ubicacion="{{ $paciente->ubicacion ?? 'No disponible' }}"
                                data-patient-discapacidad="{{ ($paciente->discapacidad ?? 'No') == 'Si' ? ($paciente->tipo_discapacidad ?? '') : 'Ninguna' }}"
                                data-patient-hijos="{{ ($paciente->tiene_hijos ?? 'No') == 'Si' ? ($paciente->numero_hijos ?? 0) : 'Ninguno' }}"
                                data-patient-civil="{{ $paciente->estado_civil ?? 'No disponible' }}"
                                data-patient-perfil-academico="{{ $paciente->perfil_academico ?? 'Sin definir' }}"
                                data-patient-pnf="{{ $paciente->pnf ?? 'No aplica' }}"
                                data-patient-semestre="{{ ($paciente->semestre ?? null) ? $paciente->semestre . '° Semestre' : 'No aplica' }}"
                                data-patient-edad="{{ $edad }}"
                                title="Ver perfil del paciente">
                            <span class="text-xl font-black">{{ $iniciales }}</span>
                        </button>

                        <div class="flex-1 min-w-0">
                            <h2 class="text-base md:text-lg font-black text-slate-900 tracking-tight flex flex-wrap items-center gap-2 leading-tight mb-1">
                                <span class="text-slate-900 dark:text-white">Nota de Sesión: {{ $cita->paciente->name }}</span>
                            </h2>
                            <p class="text-[10px] font-bold text-slate-400 dark:text-gray-400 uppercase tracking-[0.2em]">
                                {{ $cita->fecha?->translatedFormat('d M, Y') ?? 'S/F' }} ({{ $cita->hora ? \Carbon\Carbon::parse($cita->hora)->format('g:i A') : 'S/H' }})
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <a href="{{ route('historias.show', $cita->user_id) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-white en-header hover:bg-slate-50 text-slate-600 dark:text-gray-300 rounded-xl text-xs font-bold border border-slate-200 transition-all active:scale-95">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Volver al Historial
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 rounded-3xl p-5 mb-8 flex gap-4 items-start shadow-sm animate-in fade-in slide-in-from-top-4 duration-200">
                    <div class="w-10 h-10 bg-rose-500 rounded-2xl flex items-center justify-center text-white flex-shrink-0 shadow-md shadow-rose-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-xs font-black text-rose-800 uppercase tracking-widest mb-1">Nota Clínica Obligatoria</h4>
                        <p class="text-xs font-bold text-rose-600/90 leading-relaxed">{{ $errors->first() }}</p>
                    </div>
                </div>
            @endif
            <form action="{{ route('admin.psicologia.maestros.citas.update.notas', $cita->id) }}" method="POST" id="form-notas-evolucion" class="relative">
                @csrf
                @method('PATCH')
                <input type="hidden" name="structured" value="1">
                <input type="hidden" name="is_manual" value="{{ $isManual ? '1' : '0' }}">
                <input type="hidden" name="titulo_manual" x-model="data.titulo_manual">

                <div class="space-y-8">
                    
                    {{-- CAMPOS DINÁMICOS (Filas largas) --}}
                    <div class="space-y-4 md:space-y-8" id="campos-dinamicos-container">
                        
                        @if($cita->motivo === 'Nota de Evolución (Manual)')
                        {{-- Título Manual --}}
                        <div class="bg-white dark:bg-gray-800 rounded-[24px] p-4 md:p-6 shadow-sm border border-slate-100 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-4 text-slate-800 dark:text-gray-200">
                                <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                <h4 class="text-[11px] md:text-xs font-black uppercase tracking-widest">Título de la Nota (Opcional)</h4>
                            </div>
                            <input type="text" name="titulo_manual" 
                                   class="w-full border-slate-100 dark:border-gray-600 bg-slate-50/30 dark:bg-gray-700/50 rounded-xl p-3 md:p-4 text-sm text-slate-700 dark:text-white focus:ring-4 focus:ring-sky-500/5 focus:border-sky-500 transition-all font-medium"
                                   x-model="data.titulo_manual"
                                   placeholder="Ej: Seguimiento Mensual...">
                        </div>
                        @endif

                        {{-- CAMPOS DINÁMICOS DE EVOLUCIÓN --}}
                        <template x-for="(campo, index) in data.campos_dinamicos" :key="campo.campo_id">
                            <div class="bg-white en-card rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/50 p-6 md:p-8 relative overflow-hidden group">
                                <div class="flex items-start justify-between gap-2 mb-3 md:mb-4 text-slate-800 dark:text-gray-200">
                                    <!-- Icon and Title -->
                                    <div class="flex items-start gap-2 flex-1 min-w-0 mt-1 md:mt-0 md:items-center">
                                        <svg class="w-4 h-4 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                        <h4 class="text-[11px] md:text-xs font-black uppercase tracking-widest leading-tight break-words">
                                            <span x-text="index + 1"></span>. <span x-text="campo.titulo"></span> <span x-show="!isManual && campo.campo_id <= 3" class="text-rose-500">*</span>
                                        </h4>
                                    </div>
                                    <!-- Controles de campo (Arriba, Abajo, Eliminar) -->
                                    <div class="shrink-0 flex items-center gap-1 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-all">
                                        <button type="button" @click="moveCampoUp(index)" x-show="index > 0" class="p-1 md:p-1.5 text-slate-400 hover:text-indigo-600 bg-slate-50 hover:bg-indigo-50 rounded-lg transition-colors" title="Subir campo">
                                            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                        <button type="button" @click="moveCampoDown(index)" x-show="index < data.campos_dinamicos.length - 1" class="p-1 md:p-1.5 text-slate-400 hover:text-indigo-600 bg-slate-50 hover:bg-indigo-50 rounded-lg transition-colors" title="Bajar campo">
                                            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <div class="w-px h-4 bg-slate-200 mx-1"></div>
                                        <button type="button" @click="removeCampoDinamico(index)" x-show="isManual || campo.campo_id > 3" class="p-1 md:p-1.5 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Quitar campo">
                                            <svg class="w-3.5 h-3.5 md:w-4 md:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <textarea :name="'campos_dinamicos[' + campo.campo_id + ']'" rows="4" 
                                          class="w-full border-slate-100 dark:border-gray-600 bg-slate-50/30 dark:bg-gray-700/50 rounded-xl p-3 md:p-4 text-sm text-slate-700 dark:text-white focus:ring-4 focus:ring-indigo-500/5 transition-all resize-none font-medium leading-relaxed"
                                          x-model="campo.contenido"
                                          :required="!isManual && campo.campo_id <= 3"
                                          placeholder="Escribe los detalles aquí..."></textarea>
                            </div>
                        </template>
                    </div>

                    {{-- CAMPOS ESTRUCTURADOS / INTELIGENTES (Alineados abajo en 2 columnas) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        {{-- Diagnósticos CIE-10 (Diseño homologado con Expediente General) --}}
                        <div class="bg-white en-card dark:bg-gray-800 rounded-[24px] p-6 shadow-sm border border-slate-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2 text-slate-800 dark:text-gray-200">
                                    <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                    <h4 class="text-[11px] font-black uppercase tracking-widest">Diagnósticos Oficiales</h4>
                                </div>
                            </div>
                            
                            {{-- Etiquetas de Diagnóstico (Style: Expediente General) --}}
                            <div class="flex flex-wrap gap-2 mb-4">
                                <template x-for="(diag, index) in data.diagnosticos" :key="index">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 text-indigo-700 text-[10px] font-black uppercase tracking-wider rounded-full border border-indigo-100 group/tag">
                                        <span x-text="diag.nombre"></span>
                                        <button type="button" @click="removeDiagnostico(index)" class="hover:text-rose-500 transition-colors">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </span>
                                </template>
                                <template x-if="data.diagnosticos.length === 0">
                                    <span class="text-[10px] font-bold text-slate-300 uppercase tracking-widest italic">Sin diagnósticos asociados</span>
                                </template>
                            </div>

                            {{-- Buscador (Style: Expediente General) --}}
                            <div class="relative" x-data="{ search: '', results: [], loading: false, open: false }">
                                <div class="flex items-center px-4 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-full focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-400 transition-all shadow-sm">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    <input type="text" x-model="search" 
                                           @input.debounce.300ms="
                                                if(search.length < 2) { results = []; open = false; return; }
                                                loading = true;
                                                fetch(`{{ route('enfermedades.api.search') }}?q=${encodeURIComponent(search)}`)
                                                    .then(r => r.json()).then(d => { results = d; loading = false; open = true; });
                                           "
                                           class="w-full border-none bg-transparent text-xs font-bold text-slate-700 dark:text-white focus:ring-0 placeholder-slate-400 py-2.5" 
                                           placeholder="Buscar diagnóstico o condición...">
                                </div>
                                
                                <div x-show="open" @click.away="open = false" x-cloak
                                     class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-gray-700 p-2">
                                    <div class="max-h-48 overflow-y-auto custom-scrollbar">
                                        <template x-if="loading">
                                            <div class="p-3 text-[10px] text-slate-400 text-center font-bold uppercase tracking-widest animate-pulse">Buscando...</div>
                                        </template>
                                        <template x-for="res in results" :key="res.id">
                                            <button type="button" @click="addDiagnostico(res); open = false; search = ''"
                                                    class="w-full text-left px-4 py-2.5 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-xl border-b border-slate-50 dark:border-gray-700 last:border-none transition-colors group">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full bg-indigo-400 group-hover:scale-110 transition-transform"></div>
                                                    <span class="text-[10px] font-bold text-slate-700 dark:text-gray-200 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" x-text="res.nombre"></span>
                                                    <span class="text-[9px] font-black text-slate-300 dark:text-gray-500 ml-auto" x-text="res.codigo"></span>
                                                </div>
                                            </button>
                                        </template>
                                        <template x-if="results.length === 0 && !loading">
                                            <div class="p-3 text-[10px] text-slate-400 text-center italic font-bold">No se encontraron resultados</div>
                                        </template>
                                    </div>
                                    <div class="mt-2 pt-2 border-t border-slate-50">
                                        <a href="{{ route('enfermedades.create', ['tipo' => 'mental', 'return_to' => $cita->user_id]) }}" 
                                           class="block text-center text-[9px] font-black text-indigo-500 hover:text-indigo-700 uppercase tracking-widest py-1">
                                            ¿No aparece? Crear nueva condición
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- [NUEVO] Avances de Sesión y Estado de Ánimo --}}
                        <div class="bg-white en-card dark:bg-gray-800 rounded-[24px] p-6 shadow-sm border border-slate-100 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-4 text-slate-800 dark:text-gray-200">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                <h4 class="text-[11px] font-black uppercase tracking-widest">Avances y Estado del Paciente</h4>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Estado de Ánimo del Paciente @if(!$isManual)<span class="text-rose-500">*</span>@endif</label>
                                    <select name="estado_animo_id" x-model="data.estado_animo_id" @if(!$isManual) required @endif
                                            class="w-full bg-slate-50 dark:bg-gray-700/50 border-slate-100 dark:border-gray-600 rounded-xl text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all cursor-pointer">
                                        <option value="">Seleccionar estado de ánimo...</option>
                                        @foreach($estadosAnimo as $animo)
                                            <option value="{{ $animo->id }}">{{ $animo->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <textarea name="estado_animo_detalle" rows="2" @if(!$isManual) required @endif
                                              class="w-full mt-2 border-slate-100 dark:border-gray-600 bg-slate-50/30 dark:bg-gray-700/50 rounded-xl p-3 text-[11px] text-slate-600 dark:text-gray-300 focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all resize-none font-medium"
                                              x-model="data.estado_animo_detalle"
                                              placeholder="Describe observaciones sobre su estado de ánimo..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Estado de Evolución @if(!$isManual)<span class="text-rose-500">*</span>@endif</label>
                                    <select name="avance_estado" x-model="data.avance_estado" @if(!$isManual) required @endif
                                            class="w-full bg-slate-50 dark:bg-gray-700/50 border-slate-100 dark:border-gray-600 rounded-xl text-xs font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all cursor-pointer">
                                        <option value="">Seleccionar estado de avance...</option>
                                        @foreach($avances as $avance)
                                            <option value="{{ $avance->id }}">{{ $avance->nombre }}</option>
                                        @endforeach
                                    </select>
                                    <textarea name="avance_detalle" rows="2" @if(!$isManual) required @endif
                                              class="w-full mt-2 border-slate-100 dark:border-gray-600 bg-slate-50/30 dark:bg-gray-700/50 rounded-xl p-3 text-[11px] text-slate-600 dark:text-gray-300 focus:ring-2 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all resize-none font-medium"
                                              x-model="data.avance_detalle"
                                              placeholder="Describe los avances o retrocesos observados..."></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Plan de Tratamiento --}}
                        <div class="bg-white en-card dark:bg-gray-800 rounded-[24px] p-5 shadow-sm border border-slate-100 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-4 text-slate-800 dark:text-gray-200">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <h4 class="text-[11px] font-black uppercase tracking-widest">Plan de Tratamiento</h4>
                            </div>
                            <textarea name="plan_tratamiento" rows="4" 
                                      class="w-full border-slate-100 dark:border-gray-600 bg-slate-50/20 dark:bg-gray-700/50 rounded-xl p-3 text-[12px] text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500/10 transition-all resize-none font-medium"
                                      x-model="data.plan_tratamiento"
                                      placeholder="Asignar tareas para la casa..."></textarea>
                        </div>

                        {{-- Próxima Cita --}}
                        <div class="bg-white en-card dark:bg-gray-800 rounded-[24px] p-5 shadow-sm border border-slate-100 dark:border-gray-700">
                            <div class="flex items-center gap-2 mb-4 text-slate-800 dark:text-gray-200">
                                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <h4 class="text-[11px] font-black uppercase tracking-widest">Próxima Cita Recomendada</h4>
                            </div>
                            <input type="date" name="proxima_cita_fecha" 
                                   class="w-full border-slate-100 dark:border-gray-600 bg-slate-50/20 dark:bg-gray-700/50 rounded-xl p-3 text-xs font-bold text-slate-700 dark:text-white mb-3"
                                   x-model="data.proxima_cita_fecha">
                            <textarea name="proxima_cita_razon" rows="2" 
                                      class="w-full border-slate-100 dark:border-gray-600 bg-slate-50/20 dark:bg-gray-700/50 rounded-xl p-3 text-[11px] text-slate-600 dark:text-gray-300 focus:ring-2 focus:ring-indigo-500/10 transition-all resize-none font-medium"
                                      x-model="data.proxima_cita_razon"
                                      placeholder="Razón de la próxima cita..."></textarea>
                        </div>

                    </div>
                </div>

                {{-- FLOATING BUTTONS --}}
                <div class="fixed bottom-6 left-1/2 -translate-x-1/2 md:left-auto md:translate-x-0 md:bottom-8 md:right-8 z-30 flex items-center gap-2 md:gap-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md p-2 md:p-3 rounded-full shadow-2xl border border-slate-100/80 dark:border-gray-700">
                    <button type="button" @click="showModalCampos = true" title="Añadir Campo" class="w-12 h-12 md:w-14 md:h-14 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-600 dark:hover:bg-indigo-700 text-indigo-600 dark:text-indigo-400 hover:text-white rounded-full flex items-center justify-center transition-all shadow-lg border-2 border-white dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </button>
                    <a href="{{ route('historias.show', $cita->user_id) }}" title="Cancelar" class="w-12 h-12 md:w-14 md:h-14 bg-rose-100 dark:bg-rose-900/30 hover:bg-rose-600 dark:hover:bg-rose-700 text-rose-600 dark:text-rose-400 hover:text-white rounded-full flex items-center justify-center transition-all shadow-lg border-2 border-white dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-rose-500/20">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </a>
                    <button type="submit" @click="syncStructured()" title="Guardar Nota" class="w-12 h-12 md:w-14 md:h-14 bg-indigo-100 dark:bg-indigo-900/30 hover:bg-indigo-600 dark:hover:bg-indigo-700 text-indigo-600 dark:text-indigo-400 hover:text-white rounded-full flex items-center justify-center transition-all shadow-lg border-2 border-white dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20">
                        <svg class="w-6 h-6 md:w-7 md:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </button>
                </div>
                </div>

                {{-- Hidden input for diagnosticos array --}}
                <template x-for="(diag, index) in data.diagnosticos" :key="'hidden-'+index">
                    <input type="hidden" :name="'diagnosticos['+index+'][id]'" :value="diag.id">
                </template>
                <template x-for="(diag, index) in data.diagnosticos" :key="'hidden-cod-'+index">
                    <input type="hidden" :name="'diagnosticos['+index+'][codigo]'" :value="diag.codigo">
                </template>
                <template x-for="(diag, index) in data.diagnosticos" :key="'hidden-nom-'+index">
                    <input type="hidden" :name="'diagnosticos['+index+'][nombre]'" :value="diag.nombre">
                </template>
            </form>

            {{-- Modal de advertencia de cambios no guardados --}}
        <div x-show="showUnsavedModal" 
             class="fixed inset-0 overflow-y-auto" 
             style="z-index: 9999;"
             x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                <div x-show="showUnsavedModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm" 
                     @click="showUnsavedModal = false"></div>

                <div x-show="showUnsavedModal" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="relative inline-block w-full max-w-sm p-8 overflow-hidden text-center transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-[32px] border border-slate-100 dark:border-gray-700 z-10">
                    
                    <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-amber-50 dark:bg-amber-900/30 mb-6 text-amber-500 dark:text-amber-400">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    
                    <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">¿Estás seguro que deseas salir?</h3>
                    <p class="text-sm text-slate-500 dark:text-gray-400 mb-8 font-medium">Hay información aún no guardada. Si sales ahora, perderás los cambios realizados.</p>
                    
                    <div class="flex justify-center gap-4">
                        <button type="button" 
                                @click="showUnsavedModal = false"
                                class="px-6 py-3 bg-slate-50 dark:bg-gray-700 text-slate-600 dark:text-gray-300 font-bold text-sm rounded-xl hover:bg-slate-100 dark:hover:bg-gray-600 transition-colors uppercase tracking-widest w-full">
                            Cancelar
                        </button>
                        <button type="button" 
                                @click="confirmLeave()"
                                class="px-6 py-3 bg-amber-500 dark:bg-amber-600 hover:bg-amber-600 dark:hover:bg-amber-700 text-white font-bold text-sm rounded-xl transition-colors shadow-lg shadow-amber-500/20 uppercase tracking-widest w-full">
                            Salir
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Modal para añadir Campos (Réplica del modal de Expediente Clínico) --}}
        <div x-show="showModalCampos" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 overflow-y-auto" 
             style="z-index: 9999;"
             x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 text-center">
                
                <div x-show="showModalCampos"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm"
                     @click="showModalCampos = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="showModalCampos"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-[32px] sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-8 border border-slate-100 dark:border-gray-700">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Añadir Campo a la Sesión</h3>
                                <p class="text-xs font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Configuración de la Nota</p>
                            </div>
                        </div>
                        <button @click="showModalCampos = false" class="p-2 text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form id="formNuevoCampo" action="{{ route('campos.store.ajax') }}" method="POST" @submit.prevent="submitNuevoCampo">
                        @csrf
                        <div class="space-y-6 px-2">
                            {{-- Título del Campo --}}
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Título del Campo</label>
                                <input type="text" name="titulo" required
                                       class="w-full border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-xl p-3 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                       placeholder="Ej: Antecedentes Familiares">
                            </div>

                            <hr class="border-slate-100 dark:border-gray-700">

                            {{-- Reutilizar Campos Existentes --}}
                            <div>
                                <h4 class="text-[9px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-3">Reutilizar campos existentes</h4>
                                <div class="relative" x-data="{ openDropdownCampos: false }" @click.away="openDropdownCampos = false">
                                    <div class="flex items-center px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl focus-within:ring-2 focus-within:ring-indigo-500/20 focus-within:border-indigo-500 transition-all">
                                        <svg class="w-5 h-5 text-slate-400 dark:text-gray-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        <input type="text" x-model="searchCampo"
                                               @focus="openDropdownCampos = true"
                                               class="w-full border-none bg-transparent text-sm font-bold text-slate-700 dark:text-white focus:ring-0 placeholder-slate-400 dark:placeholder-gray-500 py-2.5" 
                                               placeholder="Escriba para buscar o haga clic para ver disponibles...">
                                    </div>
                                    
                                    <div x-show="openDropdownCampos"
                                         class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl shadow-lg max-h-60 overflow-y-auto custom-scrollbar"
                                         x-cloak>
                                         
                                         <div class="px-4 py-2 bg-slate-50 dark:bg-gray-900/50 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border-b border-slate-100 dark:border-gray-700">
                                            <span x-show="searchCampo === ''">Campos disponibles:</span>
                                            <span x-show="searchCampo !== ''">Resultados encontrados:</span>
                                         </div>
                                         
                                        <template x-if="camposFiltrados.length === 0">
                                            <div class="p-4 text-xs font-bold text-red-500">No hay resultados encontrados.</div>
                                        </template>
                                        
                                        <template x-for="campo in camposFiltrados" :key="campo.id">
                                            <button type="button" @click="
                                                addCampoFromModal(campo.id, campo.titulo);
                                                openDropdownCampos = false;
                                                searchCampo = '';
                                            " class="w-full text-left px-4 py-3 text-sm font-bold text-slate-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 border-b border-slate-50 dark:border-gray-700/50 last:border-0 transition-colors flex items-center justify-between group">
                                                <div class="flex flex-col gap-1">
                                                    <span x-text="campo.titulo"></span>
                                                    <span class="text-[9px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest" x-text="campo.psicologo_id ? 'Personalizado' : 'Sistema'"></span>
                                                </div>
                                                <svg class="w-5 h-5 text-slate-300 dark:text-gray-600 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Botón Guardar y Añadir --}}
                        <div class="pt-8 flex justify-end">
                            <button type="submit" class="w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2 px-6 rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-indigo-900/30 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Añadir
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function clinicalNoteEditor() {
            let initialData = {
                titulo_manual: '',
                diagnosticos: [],
                estado_animo_id: '',
                estado_animo_detalle: '',
                avance_estado: '',
                avance_detalle: '',
                plan_tratamiento: '',
                proxima_cita_fecha: '',
                proxima_cita_razon: '',
                campos_dinamicos: @json($camposGuardados)
            };

            const rawNotas = @json($cita->notas);
            try {
                const parsed = JSON.parse(rawNotas);
                if (typeof parsed === 'object' && parsed !== null) {
                    initialData = { ...initialData, ...parsed };
                }
            } catch(e) {
                // fall back si era texto plano no hace falta porque los nuevos son dinamicos
            }

            const initialSnapshot = JSON.stringify(initialData);
            const isRealizada = @json($cita->estado === 'realizada');

            return {
                data: initialData,
                isManual: @json($isManual),
                hasUnsavedChanges: !isRealizada,
                showUnsavedModal: false,
                showModalCampos: false,
                searchCampo: '',
                camposDisponibles: @json($camposDisponibles),
                pendingUrl: '',
                isSubmitting: false,

                async submitNuevoCampo(e) {
                    const form = e.target;
                    const btn = form.querySelector('button[type="submit"]');
                    const originalText = btn.innerHTML;
                    btn.innerHTML = 'Guardando...';
                    btn.disabled = true;

                    const formData = new FormData(form);
                    
                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: formData
                        });
                        
                        const data = await response.json();
                        
                        if(data.success) {
                            // Alpine natively tracks this update
                            this.camposDisponibles.push(data.campo);
                            this.addCampoFromModal(data.campo.id, data.campo.titulo);
                            
                            form.reset();
                            this.showModalCampos = false;

                            let t = document.createElement('div');
                            t.innerHTML = `<div id="toast" class="fixed top-6 right-6 z-50">
                                <div class="max-w-sm w-full bg-green-600 text-white shadow-lg rounded-2xl border border-green-700 px-4 py-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div>
                                            <p class="font-semibold">¡Listo!</p>
                                            <p class="text-sm mt-1">Anexo guardado exitosamente.</p>
                                        </div>
                                        <button onclick="document.getElementById('toast')?.remove()" class="text-white opacity-70 hover:opacity-100">✕</button>
                                    </div>
                                </div>
                            </div>`;
                            document.body.appendChild(t);
                            setTimeout(() => { document.getElementById('toast')?.remove() }, 4000);
                        } else {
                            AppModal.alert('Error', data.message || 'Error al guardar el campo');
                        }
                    } catch (error) {
                        AppModal.alert('Error', 'Error de conexión');
                        console.error(error);
                    } finally {
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                },

                get camposFiltrados() {
                    if (this.searchCampo.length === 0) return this.camposDisponibles;
                    return this.camposDisponibles.filter(c => c.titulo.toLowerCase().includes(this.searchCampo.toLowerCase()));
                },

                init() {
                    // Detectar cambios profundos en los datos del formulario
                    this.$watch('data', (value) => {
                        this.hasUnsavedChanges = !isRealizada || JSON.stringify(value) !== initialSnapshot;
                    });

                    // Advertencia de navegador nativa al recargar o cerrar pestaña
                    window.addEventListener('beforeunload', (e) => {
                        if (this.hasUnsavedChanges && !this.isSubmitting) {
                            e.preventDefault();
                            e.returnValue = '';
                        }
                    });

                    // Capturar clics en enlaces dentro de la aplicación para advertencia personalizada
                    document.addEventListener('click', (e) => {
                        let link = e.target.closest('a');
                        if (link && link.href && !link.href.includes('#') && link.target !== '_blank' && !link.hasAttribute('download')) {
                            // Ignorar si hace click dentro del propio modal
                            if (e.target.closest('[x-show="showUnsavedModal"]')) return;
                            
                            if (this.hasUnsavedChanges && !this.isSubmitting) {
                                e.preventDefault();
                                e.stopPropagation();
                                this.pendingUrl = link.href;
                                this.showUnsavedModal = true;
                            }
                        }
                    }, { capture: true });
                },

                markAsChanged() {
                    this.hasUnsavedChanges = true;
                },

                confirmLeave() {
                    this.hasUnsavedChanges = false;
                    if (this.pendingUrl) {
                        window.location.href = this.pendingUrl;
                    }
                },

                addDiagnostico(item) {
                    if (!this.data.diagnosticos.some(d => d.id === item.id)) {
                        this.data.diagnosticos.push(item);
                        // Asegurar el disparo de cambios en el snapshot
                        this.hasUnsavedChanges = JSON.stringify(this.data) !== initialSnapshot;
                    }
                },

                removeDiagnostico(index) {
                    this.data.diagnosticos.splice(index, 1);
                    // Asegurar el disparo de cambios en el snapshot
                    this.hasUnsavedChanges = JSON.stringify(this.data) !== initialSnapshot;
                },

                removeCampoDinamico(index) {
                    this.data.campos_dinamicos.splice(index, 1);
                    this.hasUnsavedChanges = true;
                },

                moveCampoUp(index) {
                    if (index > 0) {
                        const temp = this.data.campos_dinamicos[index];
                        this.data.campos_dinamicos[index] = this.data.campos_dinamicos[index - 1];
                        this.data.campos_dinamicos[index - 1] = temp;
                        this.hasUnsavedChanges = true;
                    }
                },

                moveCampoDown(index) {
                    if (index < this.data.campos_dinamicos.length - 1) {
                        const temp = this.data.campos_dinamicos[index];
                        this.data.campos_dinamicos[index] = this.data.campos_dinamicos[index + 1];
                        this.data.campos_dinamicos[index + 1] = temp;
                        this.hasUnsavedChanges = true;
                    }
                },

                addCampoFromModal(campoId, titulo) {
                    // Check if exists
                    const exists = this.data.campos_dinamicos.find(c => c.campo_id == campoId);
                    if(exists) {
                        AppModal.alert('Acción no permitida', 'Este campo ya está en la nota de evolución.');
                        return;
                    }
                    this.data.campos_dinamicos.push({
                        campo_id: campoId,
                        titulo: titulo,
                        contenido: ''
                    });
                    this.hasUnsavedChanges = true;
                    this.showModalCampos = false;
                },

                syncStructured() {
                    this.isSubmitting = true;
                    this.hasUnsavedChanges = false;
                }
            };
        }
    </script>
    @include('pacientes.partials.modal')

</x-app-layout>
