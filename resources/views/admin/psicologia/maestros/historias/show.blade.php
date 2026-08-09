<x-app-layout>

    <script>
        const _routeDesvincular = @js(route('admin.psicologia.maestros.historias.enfermedad.desvincular'));
        const _routeSeccionDestroy = @js(route('admin.psicologia.maestros.historias.secciones.destroy', 'PLACEHOLDER'));
        const _routeSeccionReorder = @js(route('admin.psicologia.maestros.historias.secciones.reorder', 'PLACEHOLDER'));
        const _csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
    </script>
    <style>
        .seccion-dinamica:first-of-type .btn-subir {
            display: none !important;
        }
        .seccion-dinamica:last-of-type .btn-bajar {
            display: none !important;
        }
        .dark .seccion-dinamica textarea {
            color: #e5e7eb !important;
        }
        .dark .seccion-dinamica input {
            color: #e5e7eb !important;
        }
    </style>
    @php $tab = request()->query('tab', 'expediente'); @endphp
    <div class="py-12 bg-slate-50 dark:bg-gray-900 min-h-screen overflow-x-hidden" x-data="{
        showStats: false,
        isEditing: true,
        hasUnsavedChanges: false,
        showUnsavedModal: false,
        pendingUrl: null,
        vinculados: @js($enfermedadesVinculadas->mapWithKeys(fn($items, $key) => [$key => $items->map(fn($v) => ['link_id' => $v->link_id, 'nombre' => $v->nombre])])),
        searchQuery: '',
        seccionesTitulos: @js($seccionesPersonalizadas->pluck('titulo')->values()->toArray()),
        matchesSearch(title) {
            if (!this.searchQuery) return true;
            const normalize = (str) => str.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            return normalize(title).includes(normalize(this.searchQuery));
        },
        hasVisibleSections() {
            if (!this.searchQuery) return this.seccionesTitulos.length > 0;
            const normalize = (str) => str.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const q = normalize(this.searchQuery);
            return this.seccionesTitulos.some(title => normalize(title).includes(q));
        },

        init() {
            window.addEventListener('beforeunload', (e) => {
                if (this.hasUnsavedChanges) {
                    e.preventDefault();
                    e.returnValue = '';
                }
            });

            document.addEventListener('click', (e) => {
                let link = e.target.closest('a');
                if (link && link.href && !link.href.includes('#') && link.target !== '_blank' && !link.hasAttribute('download')) {
                    if (e.target.closest('[x-show=\'showUnsavedModal\']')) return;
                    if (this.hasUnsavedChanges) {
                        e.preventDefault();
                        e.stopPropagation();
                        this.pendingUrl = link.href;
                        this.showUnsavedModal = true;
                    }
                }
            }, { capture: true });
        },
        confirmLeave() {
            this.hasUnsavedChanges = false;
            if (this.pendingUrl) {
                window.location.href = this.pendingUrl;
            }
        },
        desvincular(linkId) {
            AppModal.confirm('Confirmar', '¿Desvincular esta condición?').then((confirmed) => {
                if(!confirmed) return;
                fetch(_routeDesvincular, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': _csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ link_id: linkId })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        this.hasUnsavedChanges = true;
                        for (let key in this.vinculados) {
                            this.vinculados[key] = this.vinculados[key].filter(v => v.link_id !== linkId);
                        }
                    }
                });
            });
        },
        deleteSection(id) {
            AppModal.confirm('Atención', '¿Estás seguro de eliminar esta sección? Se perderán todos los segmentos y datos guardados.').then((confirmed) => {
                if (!confirmed) return;
                let url = _routeSeccionDestroy.replace('PLACEHOLDER', id);
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': _csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    this.hasUnsavedChanges = false;
                    window.location.reload();
                });
            });
        },
        reorderSection(id, direction) {
            let url = _routeSeccionReorder.replace('PLACEHOLDER', id);
            fetch(url, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': _csrfToken,
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ direccion: direction })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    let seccionActual = document.getElementById('seccion-' + id);
                    if (seccionActual) {
                        if (direction === 'up' && seccionActual.previousElementSibling && seccionActual.previousElementSibling.classList.contains('seccion-dinamica')) {
                            seccionActual.parentNode.insertBefore(seccionActual, seccionActual.previousElementSibling);
                        } else if (direction === 'down' && seccionActual.nextElementSibling && seccionActual.nextElementSibling.classList.contains('seccion-dinamica')) {
                            seccionActual.parentNode.insertBefore(seccionActual.nextElementSibling, seccionActual);
                        }
                    }
                } else {
                    AppModal.alert('Error', 'Error al reordenar la sección.');
                }
            });
        },
        vincular(enfermedadId, contexto) {
            fetch(@js(route('admin.psicologia.maestros.historias.enfermedad.vincular')), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': _csrfToken,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    historia_clinica_id: {{ $historia->id }},
                    enfermedad_id: enfermedadId,
                    contexto: contexto
                })
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    if(!this.vinculados[contexto]) this.vinculados[contexto] = [];
                    this.vinculados[contexto].push({
                        link_id: data.link_id,
                        nombre: data.nombre
                    });
                    this.hasUnsavedChanges = true;
                    this.$dispatch('linked-' + contexto);
                } else {
                    AppModal.alert('Error', 'Error al vincular: ' + (data.message || 'Desconocido'));
                }
            });
        }
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Breadcrumbs & Back -->
            <div class="mb-8">
                <a href="{{ route('admin.psicologia.maestros.historias.index') }}" class="inline-flex items-center gap-2 text-slate-500 dark:text-gray-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors font-bold text-sm group">
                    <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Volver al listado
                </a>
            </div>

            <!-- Profile Header -->
            <div class="bg-white dark:bg-gray-800 rounded-[32px] p-8 shadow-sm border border-slate-100 dark:border-gray-700 mb-8 overflow-visible relative">
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-50 dark:bg-indigo-900/20 rounded-full -mr-32 -mt-32 opacity-50"></div>

                <div class="relative flex flex-col md:flex-row md:items-center gap-8">
                    @php
                        // Datos para el modal
                        $fechaCita = $paciente->primera_cita ? \Carbon\Carbon::parse($paciente->primera_cita)->format('d/m/Y') : 'No disponible';
                        $edad = $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->age : 'No disponible';
                        $nacimiento = $paciente->fecha_nacimiento ? \Carbon\Carbon::parse($paciente->fecha_nacimiento)->format('d/m/Y') : 'No disponible';
                        $nombreCompleto = $paciente->name ?? '';
                        $partes = explode(' ', trim($nombreCompleto));
                        $primerNombre = $partes[0] ?? '';
                        $primerApellido = $partes[1] ?? '';
                        $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($primerApellido, 0, 1));
                        $photoPath = $paciente->profile_photo_path ?? null;
                        $hasPhoto = !empty($photoPath);
                    @endphp
                    <button type="button"
                            class="open-patient-modal w-24 h-24 rounded-3xl flex items-center justify-center text-white font-bold text-3xl shadow-xl shadow-indigo-200 dark:shadow-indigo-900/30 hover:scale-105 transition-transform active:scale-95 overflow-hidden"
                            style="background: linear-gradient(135deg, #4f46e5, #6d28d9)"
                            data-patient-type="user"
                            data-patient-name="{{ $paciente->name }}"
                            data-patient-email="{{ $paciente->email ?? 'No disponible' }}"
                            data-patient-phone="{{ $paciente->telefono ?? 'No disponible' }}"
                            data-patient-created="{{ $fechaCita }}"
                            data-patient-cedula="{{ $paciente->cedula ?? 'No disponible' }}"
                            data-patient-genero="{{ $paciente->genero ?? 'No disponible' }}"
                            data-patient-nacimiento="{{ $nacimiento }}"
                            data-patient-ubicacion="{{ $paciente->ubicacion ?? 'No disponible' }}"
                            data-patient-discapacidad="{{ ($paciente->discapacidad ?? 'No') == 'Si' ? $paciente->tipo_discapacidad : 'Ninguna' }}"
                            data-patient-hijos="{{ ($paciente->tiene_hijos ?? 'No') == 'Si' ? $paciente->numero_hijos : 'Ninguno' }}"
                            data-patient-civil="{{ $paciente->estado_civil ?? 'No disponible' }}"
                            data-patient-perfil-academico="{{ $paciente->perfil_academico ?? 'Sin definir' }}"
                            data-patient-pnf="{{ $paciente->pnf ?? 'No aplica' }}"
                            data-patient-semestre="{{ $paciente->semestre ? $paciente->semestre . '° Semestre' : 'No aplica' }}"
                            data-patient-horario="{{ $paciente->horario_path ? asset('storage/' . $paciente->horario_path) : '' }}"
                            data-patient-edad="{{ $edad }}"
                            data-patient-photo="{{ $hasPhoto ? route('media.profile_photos', basename($photoPath)) : '' }}"
                            title="Ver perfil completo">
                        @if($hasPhoto)
                            <img src="{{ route('media.profile_photos', basename($photoPath)) }}" alt="{{ $paciente->name }}" class="w-full h-full object-cover">
                        @else
                            {{ $iniciales }}
                        @endif
                    </button>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h2 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">{{ $paciente->name }}</h2>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 uppercase tracking-widest">Paciente Activo</span>
                        </div>
                        <div class="flex flex-wrap gap-4 text-sm text-slate-500 dark:text-gray-400 font-medium tracking-wide">
                            <div class="flex items-center gap-3 bg-indigo-50 dark:bg-indigo-900/30 px-4 py-2 rounded-2xl border border-indigo-100/50 dark:border-indigo-800 shadow-sm">
                                <span class="flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-black" title="Sesiones completadas con éxito">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    {{ $stats['realizadas'] }} Sesiones Realizadas
                                </span>
                                <div class="w-px h-4 bg-indigo-200 dark:bg-indigo-700"></div>
                                <button @click="showStats = true" class="flex items-center gap-1.5 text-indigo-500 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors group/btn" title="Ver historial de inasistencias y cancelaciones">
                                    <svg class="w-5 h-5 group-hover/btn:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    <span class="text-[10px] uppercase font-black tracking-widest">Resumen de Actividad</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div x-data="{ openTopExport: false }" class="relative flex gap-3">
                        <button @click="openTopExport = !openTopExport" @click.away="openTopExport = false" type="button" title="Exportar Expediente Completo" class="p-3 bg-slate-50 dark:bg-gray-700 text-slate-600 dark:text-gray-300 rounded-2xl hover:bg-slate-100 dark:hover:bg-gray-600 transition shadow-sm border border-slate-200 dark:border-gray-600 inline-flex items-center gap-2 text-sm font-bold">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            
                        </button>
                        <div x-show="openTopExport" x-transition x-cloak class="absolute right-0 top-full mt-2 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-slate-100 dark:border-gray-700 overflow-hidden z-30">
                            <a href="{{ route('admin.psicologia.maestros.historias.expedienteCompletoPdf', $paciente->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-700 dark:text-gray-200 block">PDF</span>
                                    <span class="text-xs text-slate-400 dark:text-gray-500">Expediente completo</span>
                                </div>
                            </a>
                            <a href="{{ route('admin.psicologia.maestros.historias.expedienteCompletoWord', $paciente->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div>
                                    <span class="text-sm font-bold text-slate-700 dark:text-gray-200 block">Word</span>
                                    <span class="text-xs text-slate-400 dark:text-gray-500">Expediente Completo</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dashboard Navigation & Search -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div class="flex gap-1 bg-white dark:bg-gray-800 p-1.5 rounded-[24px] shadow-sm border border-slate-100 dark:border-gray-700 w-fit">
                    <a href="{{ route('admin.psicologia.maestros.historias.show', ['paciente' => $paciente->id, 'tab' => 'expediente']) }}" class="px-8 py-3 rounded-2xl text-sm font-bold transition-all duration-300 {{ $tab === 'expediente' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30' : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700' }}">
                        Expediente General
                    </a>
                    <a href="{{ route('admin.psicologia.maestros.historias.show', ['paciente' => $paciente->id, 'tab' => 'evolucion']) }}" class="px-8 py-3 rounded-2xl text-sm font-bold transition-all duration-300 {{ $tab === 'evolucion' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30' : 'text-slate-500 dark:text-gray-400 hover:bg-slate-50 dark:hover:bg-gray-700' }}">
                        Línea de Evolución
                    </a>
                </div>

                <!-- Buscador de secciones y boton imprimir -->
                @if($tab === 'expediente')
                <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
                    <div x-data="{ openExport: false }" class="relative z-10 w-full md:w-auto">
                        <button @click="openExport = !openExport" @click.away="openExport = false" type="button" class="w-full md:w-auto group flex items-center justify-center gap-2 px-6 py-3 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-600 dark:hover:bg-indigo-500 text-indigo-600 dark:text-indigo-400 hover:text-white rounded-[20px] text-sm font-bold transition-all shadow-sm border border-indigo-100/50 dark:border-indigo-800 focus:ring-2 focus:ring-indigo-500/20 whitespace-nowrap">
                            <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v6a1 1 0 001 1h6"></path>
                            </svg>
                            Reportes
                        </button>
                        
                        <div x-show="openExport" x-transition x-cloak class="absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-slate-100 dark:border-gray-700 overflow-hidden">
                            <a href="{{ route('admin.psicologia.maestros.historias.reportePdf', $paciente->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-700 dark:text-gray-200">PDF</span>
                            </a>
                            <a href="{{ route('admin.psicologia.maestros.historias.reporteWord', $paciente->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-slate-700 dark:text-gray-200">Word</span>
                            </a>
                        </div>
                    </div>

                    <div class="relative w-full md:w-80">
                        <span class="absolute inset-y-0 left-0 flex items-center rounded-full pl-4 text-slate-400 dark:text-gray-500 pointer-events-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text"
                               id="buscador-secciones"
                               x-model="searchQuery"
                               @input="searchQuery = $event.target.value"
                               placeholder="Buscar sección..."
                               class="w-full bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-[20px] py-3 pl-12 pr-4 text-sm font-medium focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all shadow-sm placeholder-slate-400 dark:placeholder-gray-500 text-gray-900 dark:text-white">
                        <span x-show="searchQuery" x-cloak class="absolute inset-y-0 right-0 flex items-center pr-4">
                            <button type="button" @click="searchQuery = ''" class="text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </span>
                    </div>
                </div>
                @endif
            </div>

            <!-- Tab Content: Expediente -->
            @if($tab === 'expediente')
            <div>
                <form action="{{ route('admin.psicologia.maestros.historias.update', $paciente->id) }}" method="POST" @input="hasUnsavedChanges = true" @submit="hasUnsavedChanges = false">
                    @csrf
                    @method('PATCH')
                    <div class="space-y-12">
                        @foreach($seccionesPersonalizadas as $indexKey => $seccion)
                            <div id="seccion-{{ $seccion->id }}"
                                 class="bg-white dark:bg-gray-800 rounded-[32px] p-8 shadow-sm border border-slate-100 dark:border-gray-700 relative group seccion-dinamica"
                                 x-show="matchesSearch('{{ addslashes($seccion->titulo) }}')"
                                 x-transition>
                                <div class="flex items-center justify-between mb-8">
                                    <div>
                                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">{{ $seccion->titulo }}</h3>
                                        <p class="text-xs font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">{{ $seccion->descripcion_general ?? 'Sección Personalizada' }}</p>
                                    </div>

                                    <!-- Reorder & Delete Buttons (Only in edit mode) -->
                                    <div x-show="isEditing" class="flex items-center gap-1">
                                        <!-- Reorder Up -->
                                        <button type="button" @click.stop.prevent="reorderSection({{ $seccion->id }}, 'up')" class="btn-subir p-2 text-slate-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-all" title="Subir sección">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                        <!-- Reorder Down -->
                                        <button type="button" @click.stop.prevent="reorderSection({{ $seccion->id }}, 'down')" class="btn-bajar p-2 text-slate-400 dark:text-gray-500 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-all" title="Bajar sección">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                        <!-- Delete -->
                                        <button type="button" @click.stop.prevent="deleteSection({{ $seccion->id }})" class="p-2 text-rose-400 dark:text-rose-500 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-900/30 rounded-xl transition-all" title="Eliminar sección">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 {{ $seccion->segmentos->count() > 1 ? 'md:grid-cols-2' : '' }} gap-10">
                                    @foreach($seccion->segmentos as $segmento)
                                        <div class="space-y-4">
                                            <div class="mb-4">
                                                <div x-show="isEditing" x-cloak>
                                                    <input type="text" name="segmentos_metadata[{{ $segmento->id }}][titulo]" value="{{ $segmento->titulo }}"
                                                           class="bg-transparent border-none p-0 text-xs font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest focus:ring-0 w-full" placeholder="Título del segmento">
                                                </div>
                                                <div x-show="!isEditing" x-cloak>
                                                    <label class="text-xs font-black text-indigo-500 dark:text-indigo-400 uppercase tracking-widest">{{ $segmento->titulo ?? 'Información' }}</label>
                                                </div>
                                            </div>

                                            {{-- Etiquetas de Enfermedades Dinámicas --}}
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                <template x-for="vinculo in (vinculados['seg_{{ $segmento->id }}'] || [])" :key="vinculo.link_id">
                                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-[10px] font-black uppercase tracking-wider rounded-full border border-indigo-100 dark:border-indigo-800 group/tag">
                                                        <span x-text="vinculo.nombre"></span>
                                                        <button type="button" @click="desvincular(vinculo.link_id)" x-show="isEditing" class="hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </span>
                                                </template>
                                            </div>

                                            <textarea name="segmentos_extra[{{ $segmento->id }}]" rows="4" :readonly="!isEditing" :class="isEditing ? 'bg-white dark:bg-gray-700 border-indigo-200 dark:border-indigo-800' : 'bg-slate-50 dark:bg-gray-700/50 border-none pointer-events-none text-slate-600 dark:text-gray-300'" class="w-full border rounded-2xl p-5 text-sm focus:ring-2 focus:ring-indigo-500/20 transition-all placeholder-slate-300 dark:placeholder-gray-500 text-gray-900 dark:text-white">{{ $segmento->contenido }}</textarea>

                                            <!-- Disease Searcher para Segmento Dinámico -->
                                            <div class="flex justify-end -mt-2 pr-2" x-show="isEditing" x-transition
                                                 x-data="{
                                                    isOpen: false, query: '', results: [], loading: false,
                                                    search() {
                                                        if(this.query.length < 2) return this.results = [];
                                                        this.loading = true;
                                                        fetch(`{{ route('enfermedades.api.search') }}?q=${encodeURIComponent(this.query)}`)
                                                            .then(r => r.json()).then(d => { this.results = d; this.loading = false; });
                                                    }
                                                 }" @linked-seg-{{ $segmento->id }}.window="query = ''; results = []; isOpen = false" @click.away="isOpen = false">

                                                <div class="flex items-center gap-2">
                                                    <div x-show="isOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 -translate-x-4" x-transition:enter-end="opacity-100 scale-100 translate-x-0" class="relative">
                                                        <input type="text" x-model="query"
                                                               @input.debounce.300ms="search()"
                                                               @keydown.enter.prevent="if(results.length > 0) vincular(results[0].id, 'seg_{{ $segmento->id }}')"
                                                               placeholder="Buscar..."
                                                               class="w-40 border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-full py-1.5 px-4 text-xs focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-400 transition-all shadow-sm text-gray-900 dark:text-white">

                                                        <div x-show="query.length >= 2" x-cloak
                                                             class="absolute bottom-full right-0 mb-3 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-gray-700 p-2 z-30">
                                                            <div class="max-h-48 overflow-y-auto custom-scrollbar">
                                                                <template x-if="loading">
                                                                    <div class="p-2 text-[10px] text-slate-400 dark:text-gray-500 text-center">Buscando...</div>
                                                                </template>
                                                                <template x-for="item in results" :key="item.id">
                                                                    <button type="button" @click="vincular(item.id, 'seg_{{ $segmento->id }}')"
                                                                            class="w-full text-left p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-xl transition-colors group">
                                                                        <div class="flex items-center gap-2">
                                                                            <div class="w-1.5 h-1.5 rounded-full" :class="item.categoria === 'mental' ? 'bg-indigo-400' : 'bg-indigo-400'"></div>
                                                                            <div class="text-[10px] font-bold text-slate-700 dark:text-gray-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400" x-text="item.nombre"></div>
                                                                        </div>
                                                                    </button>
                                                                </template>
                                                                <template x-if="results.length === 0 && !loading">
                                                                    <div class="p-2 text-[10px] text-slate-400 dark:text-gray-500 text-center italic">No hay resultados</div>
                                                                </template>
                                                            </div>
                                                            <div class="mt-2 pt-2 border-t border-slate-50 dark:border-gray-700">
                                                                <a href="{{ route('enfermedades.create', ['tipo' => 'mental', 'return_to' => $paciente->id, 'editing' => 1]) }}" class="block text-center text-[9px] font-black text-indigo-500 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 uppercase tracking-widest">¿No aparece? Crear nueva</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <button type="button" @click="isOpen = !isOpen"
                                                            class="w-8 h-8 rounded-full flex items-center justify-center shadow-lg transition-all transform hover:scale-110 active:scale-95"
                                                            :class="isOpen ? 'bg-slate-100 dark:bg-gray-700 text-slate-400' : 'bg-indigo-600 text-white shadow-indigo-100 dark:shadow-indigo-900/30'"
                                                            title="Añadir condición">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        <!-- Mensaje de no resultados -->
                        <div x-show="!hasVisibleSections()" x-transition class="bg-white dark:bg-gray-800 rounded-[32px] border-2 border-dashed border-slate-200 dark:border-gray-700 p-16 text-center shadow-sm w-full">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Sin historia encontrada</h3>
                            <p class="text-slate-500 dark:text-gray-400 max-w-sm mx-auto">No se encontraron secciones en el expediente general que coincidan con tu búsqueda.</p>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="fixed bottom-6 right-6 z-30 flex items-center gap-3 bg-white/80 dark:bg-gray-800/80 backdrop-blur-md p-3 rounded-full shadow-2xl border border-slate-100/80 dark:border-gray-700">
                        <!-- Add Section Button -->
                        <button type="button"
                                x-show="isEditing"
                                x-transition
                                @click="$dispatch('open-modal-seccion')"
                                class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 hover:bg-emerald-600 dark:hover:bg-emerald-700 text-emerald-600 dark:text-emerald-400 hover:text-white rounded-full flex items-center justify-center transition-all shadow-lg border-2 border-white dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/20"
                                title="Añadir Anexo Clínico">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </button>

                        <!-- Save Button -->
                        <button type="submit"
                                x-show="isEditing"
                                x-transition
                                class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/30 hover:bg-indigo-600 dark:hover:bg-indigo-700 text-indigo-600 dark:text-indigo-400 hover:text-white rounded-full flex items-center justify-center transition-all shadow-lg border-2 border-white dark:border-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/20"
                                title="Actualizar Expediente">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
            @endif

            <!-- Tab Content: Evolucion -->
            @if($tab === 'evolucion')
            <div x-data="{
                showExportModal: false,
                showDeleteModal: false,
                deleteTarget: null,
                exportFormat: 'pdf',
                modoDescarga: 'unificado',
                selectAll: true,
                selectedNotes: @js($citasPaciente->pluck('id')->toArray()),
                allNoteIds: @js($citasPaciente->pluck('id')->toArray()),
                toggleAll() {
                    if (this.selectAll) {
                        this.selectedNotes = [...this.allNoteIds];
                    } else {
                        this.selectedNotes = [];
                    }
                },
                toggleNote(id) {
                    const idx = this.selectedNotes.indexOf(id);
                    if (idx > -1) {
                        this.selectedNotes.splice(idx, 1);
                    } else {
                        this.selectedNotes.push(id);
                    }
                    this.selectAll = this.selectedNotes.length === this.allNoteIds.length;
                },
                openExport(format) {
                    this.exportFormat = format;
                    this.selectAll = true;
                    this.selectedNotes = [...this.allNoteIds];
                    this.showExportModal = true;
                },
                submitExport() {
                    const form = this.$refs.exportForm;
                    form.action = this.exportFormat === 'pdf'
                        ? '{{ route('admin.psicologia.maestros.historias.evolucion.pdf', $paciente->id) }}'
                        : '{{ route('admin.psicologia.maestros.historias.evolucion.word', $paciente->id) }}';
                    form.submit();
                }
            }">
                <div class="mb-6 flex items-center justify-end gap-3">
                    <!-- Botón Exportar con dropdown -->
                    @if(!$citasPaciente->isEmpty())
                    <div class="relative group" x-data="{ dropOpen: false }" @mouseenter="dropOpen = true" @mouseleave="dropOpen = false">
                        <button type="button" class="flex items-center gap-2 px-5 py-2.5 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-600 dark:hover:bg-indigo-500 text-indigo-600 dark:text-indigo-400 hover:text-white rounded-xl text-sm font-bold transition-all shadow-sm border border-indigo-100/50 dark:border-indigo-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Reportes
                            <svg class="w-4 h-4 transition-transform" :class="dropOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="dropOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-1" class="absolute right-0 mt-1 w-56 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-slate-100 dark:border-gray-700 z-30 overflow-hidden" x-cloak>
                            <div class="p-2">
                                <p class="px-3 py-2 text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Tipo de archivo</p>
                                <button type="button" @click="openExport('pdf'); dropOpen = false" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors group">
                                    <div class="w-9 h-9 bg-red-50 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-sm font-bold text-slate-800 dark:text-white block">PDF</span>
                                        <span class="text-[10px] text-slate-400 dark:text-gray-500">Documento portable</span>
                                    </div>
                                </button>
                                <button type="button" @click="openExport('word'); dropOpen = false" class="w-full flex items-center gap-3 px-3 py-3 rounded-xl hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-colors group">
                                    <div class="w-9 h-9 bg-blue-50 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <span class="text-sm font-bold text-slate-800 dark:text-white block">Word</span>
                                        <span class="text-[10px] text-slate-400 dark:text-gray-500">Documento editable (.docx)</span>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Botón Nueva Evolución -->
                    <form action="{{ route('admin.psicologia.maestros.historias.evolucion.store', $paciente->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="group flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold shadow-md shadow-indigo-200 dark:shadow-indigo-900/30 transition-all">
                            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            
                        </button>
                    </form>
                </div>

                @if($citasPaciente->isEmpty())
                     <div class="bg-white dark:bg-gray-800 rounded-[32px] border-2 border-dashed border-slate-200 dark:border-gray-700 p-16 text-center shadow-sm">
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">No hay sesiones aún</h3>
                        <p class="text-slate-500 dark:text-gray-400 max-w-sm mx-auto">Las sesiones aparecerán aquí como una línea de tiempo a medida que se completen.</p>
                    </div>
                @else
                    <div class="space-y-8 relative before:absolute before:inset-y-0 before:left-4 md:before:left-1/2 before:w-1 before:bg-slate-100 dark:before:bg-gray-700 before:-translate-x-1/2">
                        @foreach($citasPaciente as $index => $cita)
                            <div class="relative flex flex-col md:flex-row gap-8 md:gap-0 items-start md:items-center">
                                <!-- Marker -->
                                <div class="absolute left-4 md:left-1/2 w-8 h-8 bg-white dark:bg-gray-800 border-4 {{ $cita->is_manual ? 'border-amber-500' : 'border-indigo-600' }} rounded-full z-10 -translate-x-1/2 shadow-lg {{ $cita->is_manual ? 'shadow-amber-100 dark:shadow-amber-900/30' : 'shadow-indigo-100 dark:shadow-indigo-900/30' }} animate-pulse"></div>
                                                                <!-- Content Card -->
                                <div class="w-full md:w-[45%] {{ $index % 2 == 0 ? 'md:mr-auto md:pr-12 text-left md:text-right' : 'md:ml-auto md:pl-12 order-last md:order-none' }}">
                                    <div class="bg-white dark:bg-gray-800 rounded-[32px] p-6 shadow-sm border border-slate-100 dark:border-gray-700 hover:shadow-md transition-shadow group">
                                        <div class="flex {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }} items-center gap-3 mb-4">
                                            <span class="text-xs font-black {{ $cita->is_manual ? 'text-amber-600 dark:text-amber-400' : 'text-indigo-600 dark:text-indigo-400' }} uppercase tracking-widest">{{ $cita->fecha?->translatedFormat('d M, Y') }}</span>
                                            @if(!$cita->is_manual)
                                                <span class="w-1 h-1 bg-slate-300 dark:bg-gray-600 rounded-full"></span>
                                                <span class="text-xs font-bold text-slate-400 dark:text-gray-500">Sesión #{{ $cita->session_number }}</span>
                                            @else
                                                <span class="px-2 py-0.5 bg-amber-50 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 text-[9px] font-black uppercase tracking-widest rounded-full border border-amber-200 dark:border-amber-800">Manual</span>
                                            @endif
                                        </div>
                                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-3 tracking-tight">{{ $cita->display_title ?? 'Consulta General' }}</h4>
                                        <p class="text-sm text-slate-600 dark:text-gray-300 leading-relaxed italic mb-4">
                                            "{{ Str::limit($cita->notas_limpias, 150) ?: 'No se registraron notas específicas.' }}"
                                        </p>
                                        <div class="flex {{ $index % 2 == 0 ? 'md:flex-row-reverse' : '' }} flex-wrap gap-2">
                                            {{-- Botón Editar con ícono lápiz --}}
                                            <a href="{{ route('admin.psicologia.maestros.historias.citas.edit.note', $cita->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-400 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-colors" title="Editar Nota">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                Editar
                                            </a>
                                            {{-- Botón Constancia solo para notas de citas reales --}}
                                            @if(!$cita->is_manual)
                                                <a href="{{ route('admin.psicologia.maestros.historias.citas.constancia.pdf', $cita->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-emerald-600 hover:text-white transition-colors" title="Descargar Constancia">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Constancia
                                                </a>
                                            @endif
                                            {{-- Botón Eliminar solo para notas manuales --}}
                                            @if($cita->is_manual)
                                                <button type="button"
                                                        @click="deleteTarget = {{ $cita->id }}; showDeleteModal = true"
                                                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-50 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-full text-[10px] font-black uppercase tracking-widest hover:bg-rose-600 hover:text-white transition-colors" title="Eliminar Nota Manual">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Eliminar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    {{-- Paginación --}}
                    @if($citasPaciente->hasPages())
                        <div class="mt-8">
                            {{ $citasPaciente->appends(['tab' => 'evolucion'])->links('admin.psicologia.maestros.historias.partials.pagination') }}
                        </div>
                    @endif
                @endif

                <!-- Modal tipo Canva para selección de notas -->
                <div x-show="showExportModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <!-- Overlay -->
                        <div x-show="showExportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm" @click="showExportModal = false"></div>

                        <!-- Modal Content -->
                        <div x-show="showExportModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative bg-white dark:bg-gray-800 rounded-[28px] shadow-2xl w-full max-w-md p-0 overflow-hidden border border-slate-100 dark:border-gray-700 z-10">

                            <!-- Header -->
                            <div class="flex items-center justify-between px-6 pt-6 pb-4">
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="showExportModal = false" class="p-1 text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                    </button>
                                    <h3 class="text-lg font-black text-slate-900 dark:text-white">Reportes</h3>
                                </div>
                                <button type="button" @click="showExportModal = false" class="p-2 text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="px-6 pb-6 space-y-5">
                                <!-- Tipo de archivo -->
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-gray-400 mb-2">Tipo de archivo</p>
                                    <div class="flex items-center gap-2 p-3 bg-indigo-50 dark:bg-indigo-900/30 rounded-2xl border border-indigo-100 dark:border-indigo-800">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center" :class="exportFormat === 'pdf' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-blue-100 dark:bg-blue-900/30'">
                                            <svg x-show="exportFormat === 'pdf'" class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                            <svg x-show="exportFormat === 'word'" class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <div class="flex-1">
                                            <span class="text-sm font-bold text-slate-800 dark:text-white" x-text="exportFormat === 'pdf' ? 'PDF' : 'Word (.docx)'"></span>
                                        </div>
                                        <div class="flex gap-1">
                                            <button type="button" @click="exportFormat = 'pdf'" class="px-3 py-1 rounded-lg text-xs font-bold transition-all" :class="exportFormat === 'pdf' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-slate-500 dark:text-gray-400 hover:bg-indigo-100 dark:hover:bg-gray-600'">PDF</button>
                                            <button type="button" @click="exportFormat = 'word'" class="px-3 py-1 rounded-lg text-xs font-bold transition-all" :class="exportFormat === 'word' ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-700 text-slate-500 dark:text-gray-400 hover:bg-indigo-100 dark:hover:bg-gray-600'">Word</button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modo de descarga -->
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-gray-400 mb-2">Modo de descarga</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <button type="button" @click="modoDescarga = 'unificado'" class="flex flex-col items-center gap-2 p-3 rounded-2xl border transition-all" :class="modoDescarga === 'unificado' ? 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-200 dark:border-indigo-800' : 'bg-white dark:bg-gray-700 border-slate-100 dark:border-gray-600 hover:border-indigo-100 dark:hover:border-gray-500'">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors" :class="modoDescarga === 'unificado' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400' : 'bg-slate-50 dark:bg-gray-600 text-slate-400 dark:text-gray-500'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div class="text-center">
                                                <span class="text-xs font-bold block" :class="modoDescarga === 'unificado' ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-600 dark:text-gray-300'">Un solo documento</span>
                                                <span class="text-[9px] text-slate-400 dark:text-gray-500">Todas en 1 archivo</span>
                                            </div>
                                        </button>
                                        <button type="button" @click="modoDescarga = 'individuales'" class="flex flex-col items-center gap-2 p-3 rounded-2xl border transition-all" :class="modoDescarga === 'individuales' ? 'bg-indigo-50 dark:bg-indigo-900/30 border-indigo-200 dark:border-indigo-800' : 'bg-white dark:bg-gray-700 border-slate-100 dark:border-gray-600 hover:border-indigo-100 dark:hover:border-gray-500'">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-colors" :class="modoDescarga === 'individuales' ? 'bg-indigo-100 dark:bg-indigo-900/50 text-indigo-600 dark:text-indigo-400' : 'bg-slate-50 dark:bg-gray-600 text-slate-400 dark:text-gray-500'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                            </div>
                                            <div class="text-center">
                                                <span class="text-xs font-bold block" :class="modoDescarga === 'individuales' ? 'text-indigo-700 dark:text-indigo-300' : 'text-slate-600 dark:text-gray-300'">Archivos individuales</span>
                                                <span class="text-[9px] text-slate-400 dark:text-gray-500">Carpeta ZIP</span>
                                            </div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Selección de notas -->
                                <div>
                                    <p class="text-xs font-bold text-slate-500 dark:text-gray-400 mb-2">Selecciona notas</p>

                                    <!-- Select all -->
                                    <label class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-gray-700/50 rounded-2xl border border-slate-100 dark:border-gray-700 cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors mb-3">
                                        <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="w-5 h-5 rounded-lg border-2 border-indigo-300 text-indigo-600 focus:ring-indigo-500 focus:ring-2 cursor-pointer">
                                        <div>
                                            <span class="text-sm font-bold text-slate-800 dark:text-white">Todas las notas</span>
                                            <span class="text-xs text-slate-400 dark:text-gray-500 ml-1">({{ $citasPaciente->total() }})</span>
                                        </div>
                                    </label>

                                    <!-- Individual notes -->
                                    <div class="max-h-60 overflow-y-auto space-y-1.5 pr-1 scrollbar-thin">
                                        @foreach($citasPaciente as $index => $cita)
                                            <label class="flex items-center gap-3 p-2.5 rounded-xl cursor-pointer hover:bg-indigo-50 dark:hover:bg-indigo-900/20 transition-colors" :class="selectedNotes.includes({{ $cita->id }}) ? 'bg-indigo-50/50 dark:bg-indigo-900/10' : ''">
                                                <input type="checkbox" value="{{ $cita->id }}" :checked="selectedNotes.includes({{ $cita->id }})" @change="toggleNote({{ $cita->id }})" class="w-4 h-4 rounded border-2 border-slate-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <span class="text-xs font-black text-indigo-600 dark:text-indigo-400 shrink-0">Nota {{ $citasPaciente->total() - (($citasPaciente->currentPage() - 1) * $citasPaciente->perPage()) - $index }}</span>
                                                        <span class="text-[10px] text-slate-400 dark:text-gray-500 truncate">{{ $cita->fecha?->translatedFormat('d M, Y') }}</span>
                                                    </div>
                                                    <p class="text-[11px] text-slate-500 dark:text-gray-400 truncate">{{ $cita->display_title ?? 'Consulta General' }}</p>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Summary -->
                                <div class="flex items-center justify-between text-xs text-slate-500 dark:text-gray-400 bg-slate-50 dark:bg-gray-700/50 rounded-xl px-4 py-2.5">
                                    <span>Notas seleccionadas</span>
                                    <span class="font-black text-indigo-600 dark:text-indigo-400" x-text="selectedNotes.length + ' de ' + allNoteIds.length"></span>
                                </div>

                                <!-- Submit Form -->
                                <form x-ref="exportForm" method="POST" action="" target="_blank">
                                    @csrf
                                    <input type="hidden" name="modo_descarga" :value="modoDescarga">
                                    <template x-for="noteId in selectedNotes" :key="noteId">
                                        <input type="hidden" name="citas_ids[]" :value="noteId">
                                    </template>
                                    <button type="button" @click="submitExport()" :disabled="selectedNotes.length === 0" class="w-full py-3.5 bg-indigo-600 hover:bg-indigo-700 disabled:bg-slate-300 dark:disabled:bg-gray-600 text-white disabled:text-slate-500 dark:disabled:text-gray-400 rounded-2xl text-sm font-black uppercase tracking-wider transition-all shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 disabled:shadow-none">
                                    Descargar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                {{-- Modal de Confirmación para Eliminar Nota Manual --}}
                <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm" @click="showDeleteModal = false"></div>
                        <div x-show="showDeleteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4" class="relative bg-white dark:bg-gray-800 rounded-[32px] shadow-2xl w-full max-w-sm p-8 text-center border border-slate-100 dark:border-gray-700 z-10">
                            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-rose-50 dark:bg-rose-900/30 mb-6 text-rose-500 dark:text-rose-400 shadow-md shadow-rose-100 dark:shadow-rose-900/30">
                                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-black text-slate-900 dark:text-white mb-2 tracking-tight">¿Eliminar nota manual?</h3>
                            <p class="text-xs font-bold text-slate-500 dark:text-gray-400 mb-8 leading-relaxed">
                                Esta acción eliminará permanentemente la nota de evolución manual y no podrá deshacerse.
                            </p>
                            <div class="flex justify-center gap-4">
                                <button type="button" @click="showDeleteModal = false; deleteTarget = null" class="flex-1 py-4 px-6 bg-slate-50 dark:bg-gray-700 hover:bg-slate-100 dark:hover:bg-gray-600 text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 rounded-2xl font-black text-xs uppercase tracking-widest transition-all">
                                    Cancelar
                                </button>
                                <form :action="'/citas/' + deleteTarget" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full py-4 px-6 bg-rose-500 hover:bg-rose-600 text-white rounded-2xl font-black text-xs uppercase tracking-widest shadow-lg shadow-rose-100 dark:shadow-rose-900/30 transition-all">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


        </div>
            @endif

        <!-- Modal para añadir secciones personalizadas -->
        <div x-data="{
                isOpen: false,
                search: '',
                descripcion: '',
                numCampos: 1,
                segmentos: [''],
                mostrarMensaje: false,

                actualizarSegmentos() {
                    let n = parseInt(this.numCampos);
                    if (n < 1) n = 1;
                    if (n > 4) n = 4;
                    this.numCampos = n;

                    while (this.segmentos.length < n) {
                        this.segmentos.push('');
                    }
                    while (this.segmentos.length > n) {
                        this.segmentos.pop();
                    }

                    if (n >= 4) {
                        this.mostrarMensaje = true;
                        setTimeout(() => {
                            this.mostrarMensaje = false;
                        }, 5000);
                    }
                },

                selectTemplate(t) {
                    this.search = t.titulo;
                }
             }"
             @open-modal-seccion.window="isOpen = true"
             x-show="isOpen"
             class="fixed inset-0 z-[110] overflow-y-auto"
             x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="isOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm"
                     @click="isOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

                <div x-show="isOpen"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-[32px] sm:my-8 sm:align-middle sm:max-w-xl sm:w-full sm:p-8 border border-slate-100 dark:border-gray-700">

                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-indigo-600 text-white rounded-2xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Añadir Anexo Clínico</h3>
                                <p class="text-xs font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Configuración del Historial</p>
                            </div>
                        </div>
                        <button @click="isOpen = false" class="p-2 text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    <form x-ref="formSeccion" action="{{ route('admin.psicologia.maestros.historias.secciones.store', $paciente->id) }}" method="POST">
                        @csrf
                        <div class="space-y-6 max-h-[60vh] overflow-y-auto px-2 custom-scrollbar">

                            <!-- Datos Principales -->
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Título del Anexo Clínico</label>
                                <input type="text" name="titulo" x-model="search" required
                                       class="w-full border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-xl p-3 text-sm font-bold text-slate-700 dark:text-white focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all"
                                       placeholder="Ej: Prueba de Inteligencia">
                            </div>

                            <div>
                                <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Descripción General (Opcional)</label>
                                <input type="text" name="descripcion_general" x-model="descripcion"
                                       class="w-full border-slate-200 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-gray-900 dark:text-white"
                                       placeholder="Ej: Evaluación cognitiva detallada">
                            </div>

                            <hr class="border-slate-100 dark:border-gray-700">

                            <!-- Configuración de Segmentos -->
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <label class="block text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">¿Cuántos campos (segmentos)?</label>
                                    <div class="flex items-center gap-2">
                                        <button type="button" @click="numCampos--; actualizarSegmentos()" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-gray-700 flex items-center justify-center text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-gray-600">-</button>
                                        <span class="text-sm font-black text-slate-900 dark:text-white w-4 text-center" x-text="numCampos"></span>
                                        <button type="button" @click="numCampos++; actualizarSegmentos()" :class="{'opacity-50 cursor-not-allowed': numCampos >= 4}" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-gray-700 flex items-center justify-center text-slate-400 dark:text-gray-500 hover:bg-slate-100 dark:hover:bg-gray-600">+</button>
                                    </div>
                                </div>

                                <template x-if="mostrarMensaje">
                                    <div x-show="mostrarMensaje"
                                         x-transition:enter="transition ease-out duration-500"
                                         x-transition:enter-start="opacity-0 transform translate-y-4"
                                         x-transition:enter-end="opacity-100 transform translate-y-0"
                                         x-transition:leave="transition ease-in duration-1000"
                                         x-transition:leave-start="opacity-100 transform translate-y-0"
                                         x-transition:leave-end="opacity-0 transform translate-y-4"
                                         class="bg-red-50 dark:bg-red-950 border border-red-200 dark:border-red-900 text-red-700 dark:text-red-300 px-4 py-3 rounded-xl relative mb-4" role="alert">
                                        <span class="block sm:inline">No puedes crear más de 4 segmentos por cada anexo clínico.</span>
                                    </div>
                                </template>

                                <div class="space-y-3">
                                    <template x-for="(seg, index) in segmentos" :key="index">
                                        <div class="flex items-center gap-3 animate-fade-in-up">
                                            <div class="w-8 h-8 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-500 dark:text-indigo-400 rounded-lg flex items-center justify-center text-[10px] font-black" x-text="index + 1"></div>
                                            <input type="text" :name="'segmentos_titulos[]'" x-model="segmentos[index]" required
                                                   class="flex-1 border-slate-100 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-xl p-3 text-xs font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-gray-900 dark:text-white"
                                                   :placeholder="'Título del campo ' + (index + 1)">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <!-- Sugerencias de Plantillas -->
                            @if($plantillas->count() > 0)
                                <div class="pt-2" 
                                     x-data="{
                                        queryPlantilla: '',
                                        plantillasData: @js($plantillas->map(function($p) {
                                            return [
                                                'id' => $p->id,
                                                'titulo' => $p->titulo,
                                                'descripcion_general' => $p->descripcion_general ?? "",
                                                'segmentos' => $p->segmentos ?? "[]"
                                            ];
                                        })->values()->toArray()),
                                        openDropdown: false,
                                        get filteredPlantillas() {
                                            if (this.queryPlantilla === '') {
                                                return this.plantillasData.slice(0, 5);
                                            }
                                            return this.plantillasData.filter(p => p.titulo.toLowerCase().includes(this.queryPlantilla.toLowerCase()));
                                        }
                                     }">
                                    <h4 class="text-[9px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-3">Reutilizar títulos de mis plantillas</h4>
                                    <div class="relative" @click.away="openDropdown = false">
                                        <input type="text" 
                                               x-model="queryPlantilla" 
                                               @focus="openDropdown = true" 
                                               placeholder="Escriba para buscar o haga clic para ver recientes..." 
                                               class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white mb-2">
                                        
                                        <div x-show="openDropdown" 
                                             class="absolute bottom-full mb-1 z-50 w-full bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-xl shadow-lg max-h-60 overflow-y-auto custom-scrollbar" 
                                             x-cloak>
                                             
                                             <div class="px-4 py-2 bg-slate-50 dark:bg-gray-900/50 text-[10px] font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-widest border-b border-slate-100 dark:border-gray-700">
                                                <span x-show="queryPlantilla === ''">Últimas disponibles agregadas:</span>
                                                <span x-show="queryPlantilla !== ''">Resultados encontrados:</span>
                                             </div>
                                             
                                            <template x-if="filteredPlantillas.length === 0">
                                                <div class="p-4 text-xs font-bold text-red-500">No hay resultados encontrados.</div>
                                            </template>
                                            
                                            <template x-for="plantilla in filteredPlantillas" :key="plantilla.id">
                                                <button type="button" @click="
                                                    search = plantilla.titulo;
                                                    descripcion = plantilla.descripcion_general;
                                                    let segs = [];
                                                    try { segs = JSON.parse(plantilla.segmentos || '[]'); } catch(e) {}
                                                    segmentos = segs;
                                                    numCampos = segs.length > 0 ? segs.length : 1;
                                                    if (segmentos.length === 0) segmentos = [''];
                                                    
                                                    openDropdown = false;
                                                    queryPlantilla = '';
                                                " class="w-full text-left px-4 py-3 text-sm font-bold text-slate-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 border-b border-slate-50 dark:border-gray-700/50 last:border-0 transition-colors flex flex-col gap-1">
                                                    <span x-text="plantilla.titulo"></span>
                                                    <span x-show="plantilla.descripcion_general" class="text-xs font-normal text-slate-400" x-text="plantilla.descripcion_general"></span>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="pt-8 flex justify-end">
                            <button type="submit" class="w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-black py-2 px-6 rounded-2xl shadow-lg shadow-indigo-100 dark:shadow-indigo-900/30 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                Anexar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de Estadísticas Detalladas -->
        <div x-show="showStats"
             class="fixed inset-0 z-[100] overflow-y-auto"
             x-cloak>
            <div class="flex items-center justify-center min-h-screen px-4 py-8">
                <div x-show="showStats"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 transition-opacity bg-slate-900/60 dark:bg-black/70 backdrop-blur-sm"
                     @click="showStats = false"></div>

                <div x-show="showStats"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 scale-95"
                     class="relative bg-white dark:bg-gray-800 shadow-2xl rounded-[28px] w-full max-w-md p-6 border border-slate-100 dark:border-gray-700 z-10">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-slate-100 dark:bg-gray-700/50 text-slate-600 dark:text-gray-300 rounded-xl flex items-center justify-center border border-slate-200 dark:border-gray-600 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-black text-slate-900 dark:text-white tracking-tight">Resumen de Actividad</h3>
                                <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest">Historial del paciente</p>
                            </div>
                        </div>
                        <button @click="showStats = false" class="p-1.5 text-slate-400 dark:text-gray-500 hover:text-slate-600 dark:hover:text-gray-300 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-lg transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Total destacado --}}
                    <div class="bg-slate-50 dark:bg-gray-700/50 rounded-2xl p-4 mb-4 border border-slate-100 dark:border-gray-700 relative overflow-hidden flex items-center justify-between shadow-sm">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-gray-400">Total de actividades</p>
                            <p class="text-sm font-medium text-slate-700 dark:text-gray-300 mt-0.5">Todas las interacciones registradas</p>
                        </div>
                        <span class="text-4xl font-black text-indigo-600 dark:text-indigo-400">{{ $stats['total'] }}</span>
                    </div>

                    {{-- Grid de estadísticas --}}
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        {{-- Sesiones Logradas --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 border border-slate-200 dark:border-gray-700 shadow-sm flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Sesiones logradas</p>
                                <svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <span class="text-2xl font-black text-slate-800 dark:text-white">{{ $stats['realizadas'] }}</span>
                        </div>

                        {{-- Inasistencias --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 border border-slate-200 dark:border-gray-700 shadow-sm flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Inasistencias</p>
                                <svg class="w-3.5 h-3.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </div>
                            <span class="text-2xl font-black text-slate-800 dark:text-white">{{ $stats['inasistencias'] }}</span>
                        </div>

                        {{-- Canceladas paciente --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 border border-slate-200 dark:border-gray-700 shadow-sm flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Canc. paciente</p>
                                <svg class="w-3.5 h-3.5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01"></path></svg>
                            </div>
                            <span class="text-2xl font-black text-slate-800 dark:text-white">{{ $stats['paciente_cancel_post'] }}</span>
                        </div>

                        {{-- Canceladas psicólogo --}}
                        <div class="bg-white dark:bg-gray-800 rounded-xl p-3 border border-slate-200 dark:border-gray-700 shadow-sm flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest">Canc. psicólogo</p>
                                <svg class="w-3.5 h-3.5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-2xl font-black text-slate-800 dark:text-white">{{ $stats['psicologo_cancel'] }}</span>
                        </div>
                    </div>

                    {{-- Fila extra compacta --}}
                    <div class="flex gap-3">
                        <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl p-3 border border-slate-200 dark:border-gray-700 shadow-sm flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-wider">Sin horario</span>
                            </div>
                            <span class="text-xl font-black text-slate-800 dark:text-white">{{ $stats['paciente_cancel_pre'] }}</span>
                        </div>
                        <div class="flex-1 bg-white dark:bg-gray-800 rounded-xl p-3 border border-slate-200 dark:border-gray-700 shadow-sm flex flex-col justify-center">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-[10px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-wider">Rechazadas</span>
                            </div>
                            <span class="text-xl font-black text-slate-800 dark:text-white">{{ $stats['rechazadas'] }}</span>
                        </div>
                    </div>
                       </div>
            </div>
        </div>

        <!-- Modal Salir sin guardar -->
        <div x-show="showUnsavedModal"
             class="fixed inset-0 z-[9999] overflow-y-auto"
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
                        <button type="button" @click="showUnsavedModal = false" class="px-6 py-3 bg-slate-50 dark:bg-gray-700 text-slate-600 dark:text-gray-300 font-bold text-sm rounded-xl hover:bg-slate-100 dark:hover:bg-gray-600 transition-colors uppercase tracking-widest w-full">
                            Cancelar
                        </button>
                        <button type="button" @click="confirmLeave()" class="px-6 py-3 bg-amber-500 dark:bg-amber-600 hover:bg-amber-600 dark:hover:bg-amber-700 text-white font-bold text-sm rounded-xl transition-colors shadow-lg shadow-amber-500/20 uppercase tracking-widest w-full">
                            Salir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('pacientes.partials.modal')
</x-app-layout>
