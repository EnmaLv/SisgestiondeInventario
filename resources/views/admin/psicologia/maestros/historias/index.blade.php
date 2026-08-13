<x-app-layout>

    <div class="pt-12 pb-4 bg-slate-50 dark:bg-gray-900 min-h-[calc(100vh-4rem)] flex flex-col">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 flex flex-col w-full">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Historial Clínico</h1>
                    <p class="mt-2 text-slate-500 dark:text-gray-400 text-sm">Gestiona la evolución y expedientes de tus pacientes atendidos.</p>
                </div>
                <div x-data="{ openModal: false }" class="w-full sm:w-auto">
                    <form action="{{ route('admin.psicologia.maestros.historias.index') }}" method="GET" class="flex flex-col sm:flex-row sm:items-center gap-3 w-full" id="search-form">
                        <!-- Botones Reportes y Filtrar uno al lado del otro en móvil -->
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <!-- Dropdown Reportes -->
                            <div x-data="{ open: false }" class="relative flex-1 sm:flex-initial">
                                <button @click="open = !open" @click.away="open = false" type="button" class="w-full sm:w-auto justify-center group flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-bold shadow-md shadow-indigo-200 dark:shadow-indigo-900/30 transition-all">
                                    <svg class="w-5 h-5 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Reportes
                                </button>
                                
                                <div x-show="open" x-transition x-cloak class="absolute left-0 sm:right-0 sm:left-auto mt-2 w-48 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-slate-100 dark:border-gray-700 overflow-hidden z-20">
                                    <a href="{{ route('admin.psicologia.maestros.historias.exportar.pdf', ['search' => request('search'), 'pnf' => request('pnf'), 'edad' => request('edad'), 'fecha_desde' => request('fecha_desde'), 'fecha_hasta' => request('fecha_hasta'), 'enfermedad_id' => request('enfermedad_id'), 'prioridad' => request('prioridad'), 'avance_id' => request('avance_id'), 'estado_animo_id' => request('estado_animo_id')]) }}" target="_blank" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/30 flex items-center justify-center text-red-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 dark:text-gray-200">PDF</span>
                                    </a>
                                    <a href="{{ route('admin.psicologia.maestros.historias.exportar.excel', ['search' => request('search'), 'pnf' => request('pnf'), 'edad' => request('edad'), 'fecha_desde' => request('fecha_desde'), 'fecha_hasta' => request('fecha_hasta'), 'enfermedad_id' => request('enfermedad_id'), 'prioridad' => request('prioridad'), 'avance_id' => request('avance_id'), 'estado_animo_id' => request('estado_animo_id')]) }}" class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 dark:text-gray-200">Excel</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Botón Filtrar -->
                            <button type="button" @click="openModal = true" class="flex-1 sm:flex-initial justify-center px-4 py-2.5 h-11 bg-indigo-50 text-indigo-600 dark:bg-indigo-900/30 dark:text-indigo-400 font-bold text-sm rounded-2xl hover:bg-indigo-100 dark:hover:bg-indigo-900/50 transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                Filtrar
                            </button>
                        </div>

                        <!-- Barra de Búsqueda debajo en móvil -->
                        <div class="relative w-full sm:w-64 lg:w-80">
                            <input id="search-input" type="text" name="search" value="{{ request('search') }}" placeholder="Buscar paciente..." class="w-full pl-10 pr-4 py-2.5 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-gray-900 dark:text-white">
                            <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>

                            <!-- Modal de Filtros -->
                            <div x-show="openModal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm">
                                <div @click.away="openModal = false" class="bg-white dark:bg-gray-800 rounded-3xl p-6 w-full max-w-md shadow-2xl border border-slate-100 dark:border-gray-700">
                                    <div class="flex justify-between items-center mb-5">
                                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">Filtros Avanzados</h3>
                                        <button type="button" @click="openModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">PNF/Carrera</label>
                                            <select name="pnf" class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white">
                                                <option value="">Todas las carreras</option>
                                                @foreach($pnfs as $key => $label)
                                                    <option value="{{ $key }}" {{ request('pnf') == $key ? 'selected' : '' }}>{{ $label }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">Edad</label>
                                            <input type="number" name="edad" value="{{ request('edad') }}" placeholder="Ej. 25" min="1" class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white">
                                        </div>
                                        <div x-data="{ tipoFiltro: '{{ request('tipo_filtro_fecha', 'rango') }}' }">
                                            <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">Filtrar fechas por</label>
                                            <select name="tipo_filtro_fecha" x-model="tipoFiltro" class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white mb-3">
                                                <option value="rango">Rango de fechas (cualquier cita)</option>
                                                <option value="primera_cita">Fecha de primera cita realizada</option>
                                                <option value="ultima_cita">Fecha de última cita realizada</option>
                                            </select>
                                            <div class="grid grid-cols-2 gap-4" x-show="tipoFiltro !== ''" x-transition>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">Fecha desde</label>
                                                    <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white">
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">Fecha hasta</label>
                                                    <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white">
                                                </div>
                                            </div>
                                            <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-1.5 italic" x-show="tipoFiltro === 'primera_cita'">Mostrará pacientes cuya primera sesión realizada esté en este rango.</p>
                                            <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-1.5 italic" x-show="tipoFiltro === 'ultima_cita'">Mostrará pacientes cuya última sesión realizada esté en este rango.</p>
                                            <p class="text-[11px] text-slate-400 dark:text-gray-500 mt-1.5 italic" x-show="tipoFiltro === 'rango'">Mostrará pacientes que tengan cualquier cita dentro de este rango.</p>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">Enfermedad (Buscar)</label>
                                                <div x-data="{
                                                        query: '{{ $enfermedadSeleccionada ? $enfermedadSeleccionada->nombre : '' }}',
                                                        enfermedad_id: '{{ request('enfermedad_id') }}',
                                                        results: [],
                                                        loading: false,
                                                        isOpen: false,
                                                        search() {
                                                            if(this.query.length < 2) return this.results = [];
                                                            this.loading = true;
                                                            fetch(`{{ route('enfermedades.api.search') }}?q=${encodeURIComponent(this.query)}`)
                                                                .then(r => r.json()).then(d => { this.results = d; this.loading = false; this.isOpen = true; });
                                                        },
                                                        select(item) {
                                                            this.query = item.nombre;
                                                            this.enfermedad_id = item.id;
                                                            this.isOpen = false;
                                                        },
                                                        clear() {
                                                            this.query = '';
                                                            this.enfermedad_id = '';
                                                            this.results = [];
                                                            this.isOpen = false;
                                                        }
                                                     }" @click.away="isOpen = false" class="relative">
                                                    <input type="hidden" name="enfermedad_id" x-model="enfermedad_id">
                                                    <div class="relative">
                                                        <input type="text" x-model="query" @input.debounce.300ms="search()" @focus="if(query.length >= 2) isOpen = true" placeholder="Ej. Depresión..." class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white">
                                                        <button type="button" x-show="query.length > 0" @click="clear()" class="absolute right-3 top-2.5 text-slate-400 hover:text-slate-600 dark:hover:text-gray-300">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </div>
                                                    <div x-show="isOpen && query.length >= 2" x-cloak class="absolute mt-1 w-full bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-slate-100 dark:border-gray-700 p-2 z-50">
                                                        <div class="max-h-48 overflow-y-auto custom-scrollbar">
                                                            <template x-if="loading">
                                                                <div class="p-2 text-xs text-slate-400 text-center">Buscando...</div>
                                                            </template>
                                                            <template x-for="item in results" :key="item.id">
                                                                <button type="button" @click="select(item)" class="w-full text-left p-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded-lg transition-colors group flex items-center gap-2">
                                                                    <div class="w-1.5 h-1.5 rounded-full" :class="item.categoria === 'mental' ? 'bg-indigo-400' : 'bg-indigo-400'"></div>
                                                                    <div class="text-xs font-bold text-slate-700 dark:text-gray-300 group-hover:text-indigo-600" x-text="item.nombre"></div>
                                                                </button>
                                                            </template>
                                                            <template x-if="results.length === 0 && !loading">
                                                                <div class="p-2 text-xs text-slate-400 text-center italic">No hay resultados</div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">Prioridad de Atención</label>
                                                <select name="prioridad" class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white">
                                                    <option value="">Todas</option>
                                                    @foreach($prioridades as $prioridad)
                                                        <option value="{{ $prioridad->nombre }}" {{ request('prioridad') == $prioridad->nombre ? 'selected' : '' }}>{{ $prioridad->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">Avance de Sesión</label>
                                                <select name="avance_id" class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white">
                                                    <option value="">Todos</option>
                                                    @foreach($avances as $avance)
                                                        <option value="{{ $avance->id }}" {{ request('avance_id') == $avance->id ? 'selected' : '' }}>{{ $avance->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-500 dark:text-gray-400 mb-1">Estado de Ánimo</label>
                                                <select name="estado_animo_id" class="w-full py-2.5 px-4 bg-slate-50 dark:bg-gray-700/50 border border-slate-200 dark:border-gray-600 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none text-gray-900 dark:text-white">
                                                    <option value="">Todos</option>
                                                    @foreach($estadosAnimo as $estado)
                                                        <option value="{{ $estado->id }}" {{ request('estado_animo_id') == $estado->id ? 'selected' : '' }}>{{ $estado->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 flex justify-end gap-3">
                                        <a href="{{ route('admin.psicologia.maestros.historias.index') }}" class="px-4 py-2 bg-slate-100 text-slate-600 dark:bg-gray-700 dark:text-gray-300 font-bold text-sm rounded-xl hover:bg-slate-200 dark:hover:bg-gray-600 transition-colors">Limpiar</a>
                                        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white font-bold text-sm rounded-xl hover:bg-indigo-700 shadow-md shadow-indigo-200 dark:shadow-indigo-900/20 transition-all">Aplicar </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            @php
                $historias = $historias ?? collect();
            @endphp

            @if($historias->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-[32px] border-2 border-dashed border-slate-200 dark:border-gray-700 p-16 text-center shadow-sm h-fit mb-auto">
                    <div class="w-20 h-20 bg-slate-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 text-slate-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Sin expedientes activos</h3>
                    <p class="text-slate-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
                        Los pacientes aparecerán aquí automáticamente una vez que completes su primera cita.
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-2 sm:px-4 lg:px-6">
                    @foreach($historias as $historia)
                        @php
                            $paciente = $historia['paciente'];
                            $photoPath = $paciente->profile_photo_path ?? null;
                            $hasPhoto = !empty($photoPath);
                            $nombreCompleto = $paciente->name ?? '';
                            $partes = explode(' ', trim($nombreCompleto));
                            $primerNombre = $partes[0] ?? '';
                            $primerApellido = $partes[1] ?? '';
                            $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($primerApellido, 0, 1));
                        @endphp
                        <div class="paciente-card bg-white dark:bg-gray-800 rounded-[32px] border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-xl hover:shadow-indigo-500/5 transition-all duration-300 group overflow-hidden flex flex-col w-full" data-nombre="{{ strtolower($paciente->name) }}">
                            <div class="p-8 flex-1">
                                <!-- Patient Info -->
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden bg-gradient-to-br from-indigo-600 to-violet-700 flex items-center justify-center text-white font-bold text-xl shadow-lg shadow-indigo-200 dark:shadow-indigo-900/30 group-hover:scale-105 transition-transform flex-shrink-0">
                                        @if($hasPhoto)
                                            <img src="{{ route('media.profile_photos', basename($photoPath)) }}" alt="{{ $paciente->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ $iniciales }}
                                        @endif
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <h3 class="text-lg font-bold text-slate-900 dark:text-white truncate tracking-tight">{{ $paciente->name }}</h3>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">
                                            Activo
                                        </span>
                                    </div>
                                </div>

                                <!-- Stats Row -->
                                <div class="grid grid-cols-2 gap-4 mb-6">
                                    <div class="bg-slate-50 dark:bg-gray-700/50 rounded-2xl p-3">
                                        <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Sesiones</p>
                                        <p class="text-lg font-black text-slate-800 dark:text-white">{{ $historia['citas_realizadas'] }}</p>
                                    </div>
                                    <div class="bg-slate-50 dark:bg-gray-700/50 rounded-2xl p-3">
                                        <p class="text-[10px] font-bold text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-1">Última</p>
                                        <p class="text-sm font-bold text-slate-800 dark:text-white">{{ $historia['ultima_sesion'] instanceof \Carbon\Carbon ? $historia['ultima_sesion']->locale('es')->translatedFormat('d F') : \Carbon\Carbon::parse($historia['ultima_sesion'])->locale('es')->translatedFormat('d F') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Link -->
                            <a href="{{ route('admin.psicologia.maestros.historias.show', $paciente->id) }}" class="bg-slate-50/50 dark:bg-gray-700/30 group-hover:bg-indigo-600 p-4 text-center border-t border-slate-50 dark:border-gray-700 transition-colors">
                                <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400 group-hover:text-white flex items-center justify-center gap-2">
                                    Abrir Expediente
                                    <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </span>
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Mensaje de sin resultados (oculto por defecto) -->
                <div id="no-results-msg" class="hidden bg-white dark:bg-gray-800 rounded-[32px] border-2 border-dashed border-slate-200 dark:border-gray-700 p-12 text-center shadow-sm h-fit mb-auto">
                    <div class="w-16 h-16 bg-slate-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Sin coincidencias</h3>
                    <p class="text-slate-500 dark:text-gray-400 text-sm">No se encontraron pacientes que coincidan con tu búsqueda.</p>
                </div>

                <div class="mt-auto flex justify-center pb-2 pt-12">
                    {{ $historias->appends(request()->query())->links('admin.psicologia.maestros.historias.partials.pagination') }}
                </div>
            @endif
        </div>
    </div>


</x-app-layout>
