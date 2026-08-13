
<div class="py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="flex flex-col gap-6 h-full">
                <a href="{{ route('admin.psicologia.maestros.agenda.index') }}"
                    class="group block bg-white dark:bg-slate-800 hover:shadow-lg transition-all rounded-3xl p-6 border border-gray-100 dark:border-slate-700 flex-col justify-between flex-1">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3
                                    class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-blue-900 dark:group-hover:text-blue-400">
                                    Mi Agenda</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Próximas citas confirmadas.
                                </p>
                            </div>
                            <div
                                class="p-2 bg-cyan-50 dark:bg-cyan-900/30 rounded-2xl text-cyan-600 dark:text-cyan-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="space-y-2 mb-4 overflow-hidden max-h-[100px]">
                            @if (isset($confirmadasHoy) && $confirmadasHoy->count() > 0)
                                @foreach ($confirmadasHoy as $cita)
                                    <div
                                        class="flex items-center text-xs p-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                                        <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                        <span
                                            class="flex-1 font-semibold text-gray-700 dark:text-gray-300 truncate">{{ optional($cita->paciente)->name ?: 'Paciente confirmado' }}</span>
                                        <span
                                            class="text-gray-500 dark:text-gray-400 font-medium">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div
                                    class="text-xs p-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl text-gray-500 dark:text-gray-400">
                                    Sin citas programadas para hoy.
                                </div>
                            @endif
                        </div>
                    </div>
                    <div
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-xl text-sm font-bold text-center transition-colors mt-auto">
                        Ver Agenda
                    </div>
                </a>

                <div class="group bg-white dark:bg-slate-800 hover:shadow-lg transition-all rounded-3xl p-6 border border-gray-100 dark:border-slate-700 flex flex-col justify-between flex-1"
                    x-data="{ query: '', results: [], open: false, isSearching: false, search() { if (this.query.length < 2) { this.results = [];
                                this.open = false; return; } this.isSearching = true;
                            fetch(`/historias/buscar/paciente?q=${this.query}`).then(res => res.json()).then(data => { this.results = data;
                                this.open = true;
                                this.isSearching = false; }).catch(() => { this.isSearching = false; }) } }">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3
                                    class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-blue-900 dark:group-hover:text-blue-400">
                                    Historias Clínicas</h3>
                                <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Consulta de expedientes.
                                </p>
                            </div>
                            <div
                                class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-2xl text-blue-600 dark:text-blue-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="relative mb-4" @click.away="open = false">
                            <input type="text" x-model="query" @input.debounce.300ms="search"
                                placeholder="Buscar paciente..."
                                class="block w-full pl-3 pr-8 py-2 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-xl text-xs focus:bg-white dark:focus:bg-gray-600 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 transition-all"
                                autocomplete="off">
                            <div class="absolute inset-y-0 right-0 py-2 pr-3 flex items-center pointer-events-none">
                                <svg x-show="!isSearching" class="h-4 w-4 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                <svg x-show="isSearching" class="animate-spin h-4 w-4 text-blue-500"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    style="display: none;">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                    </path>
                                </svg>
                            </div>
                            <div x-show="open" x-transition.opacity
                                class="absolute z-10 w-full mt-1 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-gray-100 dark:border-slate-700 overflow-hidden"
                                style="display: none;">
                                <ul class="max-h-40 overflow-y-auto">
                                    <template x-for="paciente in results" :key="paciente.id">
                                        <li>
                                            <a :href="`/historias/${paciente.id}`"
                                                class="block px-3 py-2 hover:bg-blue-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs border-b border-gray-50 dark:border-slate-700 last:border-0 transition-colors">
                                                <div class="flex items-center">
                                                    <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mr-2"></span>
                                                    <span class="font-semibold" x-text="paciente.name"></span>
                                                </div>
                                            </a>
                                        </li>
                                    </template>
                                    <li x-show="results.length === 0"
                                        class="px-3 py-2 text-gray-500 dark:text-gray-400 text-xs text-center">
                                        No se encontraron resultados
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.psicologia.maestros.historias.index') }}"
                        class="block w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-xl text-sm font-bold text-center transition-colors mt-auto">
                        Ver Todas
                    </a>
                </div>
            </div>

            <div
                class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl p-10 border border-gray-100 dark:border-slate-700 flex flex-col justify-between min-h-[500px]">
                <div class="grid grid-cols-2 grid-rows-2 h-full relative flex-1 gap-2">
                    <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                        <div class="w-full h-px bg-gray-200 dark:bg-gray-700"></div>
                        <div class="h-full w-px bg-gray-200 dark:bg-gray-700 absolute"></div>
                    </div>

                    <div class="p-3 z-10 flex flex-col">
                        <h4 class="text-sm font-bold text-slate-500 mb-3 text-center">Últimas citas confirmadas</h4>
                        <div
                            class="space-y-2 overflow-y-auto max-h-[160px] pr-1 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                            @if (isset($ultimasConfirmadas) && $ultimasConfirmadas->count() > 0)
                                @foreach ($ultimasConfirmadas as $cita)
                                    @php
                                        $colorPrioridad = match (strtolower($cita->prioridad ?? '')) {
                                            'alta' => 'bg-amber-500',
                                            'crítica', 'critica' => 'bg-rose-500',
                                            'media' => 'bg-sky-500',
                                            'baja' => 'bg-emerald-500',
                                            default => 'bg-indigo-500',
                                        };
                                    @endphp
                                    <div
                                        class="citas-item text-xs p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex justify-between items-center shadow-sm">
                                        <div class="flex items-center w-2/3">
                                            <span
                                                class="w-2 h-2 rounded-full {{ $colorPrioridad }} mr-2 shrink-0"></span>
                                            <span
                                                class="paciente-nombre font-semibold text-gray-700 dark:text-gray-300 truncate"
                                                title="{{ $cita->paciente_nombre_corto }}">{{ $cita->paciente_nombre_corto }}</span>
                                        </div>
                                        <span
                                            class="fecha-label text-gray-500 text-[10px]">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m') }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-xs text-gray-400 text-center mt-2">Sin citas</p>
                            @endif
                        </div>
                    </div>
                    <div class="p-3 z-10 flex flex-col items-center justify-center">
                        <h4 class="text-sm font-bold text-slate-500 mb-10 text-center">Diagrama de citas</h4>
                        <div class="w-full h-[130px] relative">
                            <canvas id="chartCanceladasRealizadas"></canvas>
                        </div>

                    </div>

                    <div class="p-3 z-10 flex flex-col items-center justify-center">
                        <h4 class="text-sm font-bold text-slate-500 dark:text-gray-400 mb-10 text-center">Tendencia
                            Semanal</h4>
                        <div class="w-full h-[120px] relative">
                            <canvas id="chartTendenciaSemanal"></canvas>
                        </div>
                    </div>

                    <div class="p-3 z-10 flex flex-col relative">
                        <h4 class="text-sm font-bold text-slate-500 mb-3 text-center ">Solicitudes más antiguas
                        </h4>
                        <div
                            class="space-y-2 overflow-y-auto max-h-[160px] pl-1 [&::-webkit-scrollbar]:hidden [-ms-overflow-style:none] [scrollbar-width:none]">
                            @if (isset($citasPendientesAntiguas) && $citasPendientesAntiguas->count() > 0)
                                @foreach ($citasPendientesAntiguas as $cita)
                                    @php
                                        $colorPrioridad = match (strtolower($cita->prioridad ?? '')) {
                                            'alta' => 'bg-amber-500',
                                            'crítica', 'critica' => 'bg-rose-500',
                                            'media' => 'bg-sky-500',
                                            'baja' => 'bg-emerald-500',
                                            default => 'bg-indigo-500',
                                        };
                                    @endphp
                                    <div
                                        class="citas-item text-xs p-2 bg-gray-50 dark:bg-gray-700/50 rounded-lg flex justify-between items-center shadow-sm">
                                        <div class="flex items-center w-2/3">
                                            <span
                                                class="w-2 h-2 rounded-full {{ $colorPrioridad }} mr-2 shrink-0"></span>
                                            <span
                                                class="paciente-nombre font-semibold text-gray-700 dark:text-gray-300 truncate"
                                                title="{{ $cita->paciente_nombre_corto }}">{{ $cita->paciente_nombre_corto }}</span>
                                        </div>
                                        <span
                                            class="fecha-label text-gray-500 text-[10px]">{{ \Carbon\Carbon::parse($cita->created_at)->format('d/m/y') }}</span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-xs text-gray-400 text-center mt-2">Sin pendientes</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mt-4 flex justify-center">
                    <a href="{{ route('admin.psicologia.maestros.agenda.index') }}"
                        class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-10 rounded-xl transition-colors shadow-md text-sm">ir
                        allá</a>
                </div>
            </div>

            <div class="flex flex-col gap-6 h-full">
                <a href="{{ route('admin.psicologia.maestros.horarios.index') }}"
                    class="group block bg-cyan-50 dark:bg-cyan-900/20 hover:shadow-lg transition-all rounded-3xl p-6 border border-blue-100 dark:border-blue-800 flex-col justify-between flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-blue-800 dark:text-blue-400">Horarios</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Ajusta o crea bloques de
                                tiempo.</p>
                        </div>
                        <div
                            class="p-2 bg-blue dark:bg-slate-800 rounded-2xl shadow-sm text-blue-600 dark:text-blue-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-xl text-sm font-bold text-center transition-colors mt-auto">
                        Gestionar Horario
                    </div>
                </a>

                <a href="{{ route('admin.psicologia.maestros.agenda.estadisticas') }}?format=html"
                    class="group block bg-white dark:bg-slate-800 hover:shadow-lg transition-all rounded-3xl p-6 border border-gray-100 dark:border-slate-700 flex-col justify-between flex-1">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3
                                class="text-lg font-bold text-gray-800 dark:text-white group-hover:text-blue-900 dark:group-hover:text-blue-400 transition-colors">
                                Reportes y Estadísticas</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-xs mt-1">Seguimiento estadístico de
                                citas y evoluciones clínicas.</p>
                        </div>
                        <div class="p-2 bg-gray-50 dark:bg-gray-700 rounded-2xl text-gray-600 dark:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                    </div>
                    <div
                        class="block w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-xl text-sm font-bold text-center transition-colors mt-auto">
                        Consultar
                    </div>
                </a>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        const textColor = isDarkMode ? '#9CA3AF' : '#4B5563';

        const ctxDonut = document.getElementById('chartCanceladasRealizadas');
        if (ctxDonut) {
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Realizadas', 'Canceladas'],
                    datasets: [{
                        data: [{{ $estadisticasCitas['realizada'] ?? 0 }},
                            {{ $estadisticasCitas['cancelada'] ?? 0 }}
                        ],
                        backgroundColor: ['#2C7BF1', '#EF4444'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 10
                                },
                                color: textColor
                            }
                        }
                    },
                    cutout: '65%'
                }
            });
        }

        const ctxLine = document.getElementById('chartTendenciaSemanal');
        if (ctxLine) {
            const tendenciaData = {!! json_encode($tendenciaPacientes ?? []) !!};
            const labels = tendenciaData.map(item => item.semana);
            const data = tendenciaData.map(item => item.total);

            new Chart(ctxLine, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Pacientes Vistos',
                        data: data,
                        borderColor: '#3B82F6',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        fill: true,
                        tension: 0.4,
                        borderWidth: 2,
                        pointBackgroundColor: '#3B82F6',
                        pointRadius: 3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1,
                                font: {
                                    size: 9
                                },
                                color: textColor
                            },
                            grid: {
                                display: false
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 9
                                },
                                color: textColor
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    });
</script>
