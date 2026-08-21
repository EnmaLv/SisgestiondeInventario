
<div class="py-2">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="flex flex-col gap-6 h-full">
                <a href="{{ route('admin.psicologia.maestros.agenda.index') }}"
                    style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                    class="group block rounded-2xl border shadow-sm hover:shadow-md transition-all p-6 flex flex-col justify-between flex-1">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-extrabold tracking-tight" style="color: var(--text-main);">
                                    Mi Agenda
                                </h3>
                                <p class="mt-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Próximas citas confirmadas.
                                </p>
                            </div>
                            <div class="p-2.5 bg-cyan-500/10 rounded-xl text-cyan-600 dark:text-cyan-400">
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
                                    <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color);"
                                        class="flex items-center text-xs p-2.5 rounded-xl border">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2.5"></span>
                                        <span class="flex-1 font-semibold truncate" style="color: var(--text-main);">{{ optional($cita->paciente)->name ?: 'Paciente confirmado' }}</span>
                                        <span class="text-gray-400 font-mono text-[11px] font-bold">{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}</span>
                                    </div>
                                @endforeach
                            @else
                                <div style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color);"
                                    class="text-xs p-3 rounded-xl border text-gray-400 italic text-center">
                                    Sin citas programadas para hoy.
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-bold text-center shadow-md active:scale-95 transition-all mt-auto flex items-center justify-center gap-2">
                        <i class="fas fa-calendar-alt text-xs"></i>
                        <span>Ver Agenda</span>
                    </div>
                </a>

                <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                    class="group rounded-2xl border shadow-sm hover:shadow-md transition-all p-6 flex flex-col justify-between flex-1"
                    x-data="{ query: '', results: [], open: false, isSearching: false, search() { if (this.query.length < 2) { this.results = [];
                                this.open = false; return; } this.isSearching = true;
                            fetch(`admin//psicologia/maestros/historias/buscar/paciente?q=${this.query}`).then(res => res.json()).then(data => { this.results = data;
                                this.open = true;
                                this.isSearching = false; }).catch(() => { this.isSearching = false; }) } }">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-extrabold tracking-tight" style="color: var(--text-main);">
                                    Historias Clínicas
                                </h3>
                                <p class="mt-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Consulta de expedientes.
                                </p>
                            </div>
                            <div class="p-2.5 bg-indigo-500/10 rounded-xl text-indigo-600 dark:text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('admin.psicologia.maestros.historias.index') }}"
                        class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-bold text-center shadow-md active:scale-95 transition-all mt-auto flex items-center justify-center gap-2">
                        <i class="fas fa-folder-open text-xs"></i>
                        <span>Ver Todas</span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-2 rounded-2xl p-6 sm:p-8 border shadow-sm flex flex-col justify-between min-h-[500px]"
                style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 h-full relative flex-1">
                    <div class="p-4 rounded-xl border flex flex-col justify-between"
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color);">
                        <div>
                            <div class="flex items-center justify-between mb-3 pb-2 border-b" style="border-color: var(--border-color);">
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-400">
                                    Últimas Citas Confirmadas
                                </h4>
                                <span class="p-1.5 bg-emerald-500/10 rounded-lg text-emerald-600 dark:text-emerald-400">
                                    <i class="fas fa-calendar-check text-xs"></i>
                                </span>
                            </div>
                            <div class="space-y-2 overflow-y-auto max-h-[160px] pr-1 custom-scrollbar">
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
                                        <div class="citas-item text-xs p-2.5 rounded-xl border flex justify-between items-center transition-all hover:translate-x-0.5"
                                            style="background-color: var(--bg-card); border-color: var(--border-color);">
                                            <div class="flex items-center w-2/3">
                                                <span class="w-2 h-2 rounded-full {{ $colorPrioridad }} mr-2.5 shrink-0"></span>
                                                <span class="paciente-nombre font-semibold truncate" style="color: var(--text-main);"
                                                    title="{{ $cita->paciente_nombre_corto }}">{{ $cita->paciente_nombre_corto }}</span>
                                            </div>
                                            <span class="fecha-label text-gray-400 font-mono text-[11px] font-bold">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m') }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-xs p-3 rounded-xl border text-gray-400 italic text-center"
                                        style="background-color: var(--bg-card); border-color: var(--border-color);">
                                        Sin citas confirmadas.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border flex flex-col justify-between items-center"
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color);">
                        <div class="w-full flex items-center justify-between mb-3 pb-2 border-b" style="border-color: var(--border-color);">
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-400">
                                Diagrama de Citas
                            </h4>
                            <span class="p-1.5 bg-indigo-500/10 rounded-lg text-indigo-600 dark:text-indigo-400">
                                <i class="fas fa-chart-pie text-xs"></i>
                            </span>
                        </div>
                        <div class="w-full h-[140px] relative my-auto flex items-center justify-center">
                            <canvas id="chartCanceladasRealizadas"></canvas>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border flex flex-col justify-between items-center"
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color);">
                        <div class="w-full flex items-center justify-between mb-3 pb-2 border-b" style="border-color: var(--border-color);">
                            <h4 class="text-xs font-black uppercase tracking-wider text-gray-400">
                                Tendencia Semanal
                            </h4>
                            <span class="p-1.5 bg-cyan-500/10 rounded-lg text-cyan-600 dark:text-cyan-400">
                                <i class="fas fa-chart-line text-xs"></i>
                            </span>
                        </div>
                        <div class="w-full h-[140px] relative my-auto flex items-center justify-center">
                            <canvas id="chartTendenciaSemanal"></canvas>
                        </div>
                    </div>

                    <div class="p-4 rounded-xl border flex flex-col justify-between"
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color);">
                        <div>
                            <div class="flex items-center justify-between mb-3 pb-2 border-b" style="border-color: var(--border-color);">
                                <h4 class="text-xs font-black uppercase tracking-wider text-gray-400">
                                    Solicitudes más Antiguas.
                                </h4>
                                <span class="p-1.5 bg-amber-500/10 rounded-lg text-amber-600 dark:text-amber-400">
                                    <i class="fas fa-clock text-xs"></i>
                                </span>
                            </div>
                            <div class="space-y-2 overflow-y-auto max-h-[160px] pr-1 custom-scrollbar">
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
                                        <div class="citas-item text-xs p-2.5 rounded-xl border flex justify-between items-center transition-all hover:translate-x-0.5"
                                            style="background-color: var(--bg-card); border-color: var(--border-color);">
                                            <div class="flex items-center w-2/3">
                                                <span class="w-2 h-2 rounded-full {{ $colorPrioridad }} mr-2.5 shrink-0"></span>
                                                <span class="paciente-nombre font-semibold truncate" style="color: var(--text-main);"
                                                    title="{{ $cita->paciente->persona->nombre_persona }}">{{ $cita->paciente->persona->nombre_persona }}</span>
                                            </div>
                                            <span class="fecha-label text-gray-400 font-mono text-[11px] font-bold">{{ \Carbon\Carbon::parse($cita->created_at)->format('d/m/y') }}</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="text-xs p-3 rounded-xl border text-gray-400 italic text-center"
                                        style="background-color: var(--bg-card); border-color: var(--border-color);">
                                        Sin pendientes.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6 h-full">
                <a href="{{ route('admin.psicologia.maestros.horarios.index') }}"
                    style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                    class="group block rounded-2xl border shadow-sm hover:shadow-md transition-all p-6 flex flex-col justify-between flex-1">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-extrabold tracking-tight" style="color: var(--text-main);">
                                    Horarios
                                </h3>
                                <p class="mt-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Ajusta o crea bloques de tiempo.
                                </p>
                            </div>
                            <div class="p-2.5 bg-indigo-500/10 rounded-xl text-indigo-600 dark:text-indigo-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-bold text-center shadow-md active:scale-95 transition-all mt-auto flex items-center justify-center gap-2">
                        <i class="fas fa-clock text-xs"></i>
                        <span>Gestionar Horario</span>
                    </div>
                </a>

                <a href="{{ route('admin.psicologia.maestros.agenda.estadisticas') }}?format=html"
                    style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                    class="group block rounded-2xl border shadow-sm hover:shadow-md transition-all p-6 flex flex-col justify-between flex-1">
                    <div>
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-extrabold tracking-tight" style="color: var(--text-main);">
                                    Reportes y Estadísticas
                                </h3>
                                <p class="mt-1 text-xs font-medium text-gray-500 dark:text-gray-400">
                                    Seguimiento estadístico de citas y evoluciones clínicas.
                                </p>
                            </div>
                            <div class="p-2.5 bg-cyan-500/10 rounded-xl text-cyan-600 dark:text-cyan-400">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl text-xs font-bold text-center shadow-md active:scale-95 transition-all mt-auto flex items-center justify-center gap-2">
                        <i class="fas fa-chart-bar text-xs"></i>
                        <span>Consultar</span>
                    </div>
                </a>
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
