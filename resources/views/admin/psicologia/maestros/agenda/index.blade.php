<x-app-layout>
    @php
        $moduloActivo = strtolower(session('modulo_activo', 'general'));
        $esPsicologia = in_array($moduloActivo, ['psicologia', 'psicología', 'mental']);
        $themeColor = $esPsicologia ? 'indigo' : 'red';
        $btnClass = $esPsicologia ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-red-600 hover:bg-red-700';
        $focusRingClass = $esPsicologia
            ? 'focus:ring-indigo-500/20 focus:border-indigo-500'
            : 'focus:ring-red-500/20 focus:border-red-500';
    @endphp

    <style>
        .invisible-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .invisible-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

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

            <x-psicologo-selector :psicologos="$psicologos" :psicologoId="$psicologoId" />

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 mt-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                        Mi Agenda
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Gestiona las citas y programación de atención para <strong
                            class="text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400">Salud Mental</strong>.
                    </p>
                </div>

                <div
                    class="flex flex-col md:flex-row md:flex-nowrap items-stretch md:items-center justify-start md:justify-end gap-2 sm:gap-3 w-full md:w-auto overflow-x-auto invisible-scrollbar pb-1">
                    <div
                        class="flex items-center justify-center gap-2 {{ $btnClass }} text-white px-4 h-11 rounded-xl shadow-md flex-shrink-0 w-full md:w-auto">
                        <i class="fas fa-calendar-day text-xs"></i>
                        <div class="flex flex-col leading-none text-left">
                            <span class="text-[8px] font-black uppercase tracking-[0.15em] opacity-80">Fecha de
                                hoy</span>
                            <span
                                class="text-[11px] font-black uppercase tracking-wide whitespace-nowrap">{{ \Carbon\Carbon::now()->locale('es')->isoFormat('ddd DD MMM, YYYY') }}</span>
                        </div>
                    </div>

                    @if ($view === 'list')
                        <button type="button"
                            onclick="document.getElementById('filterModal').classList.remove('hidden'); document.getElementById('filterModal').classList.add('flex');"
                            class="flex items-center justify-center gap-2 {{ $btnClass }} text-white px-4 h-11 rounded-xl shadow-md transition-all flex-shrink-0 w-full md:w-auto"
                            title="Filtrar Fechas">
                            <i class="fas fa-filter text-xs"></i>
                            <span class="text-xs font-bold uppercase">Filtrar</span>
                        </button>
                    @endif

                    @if ($view !== 'list')
                        <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                            class="flex items-center justify-between md:justify-center gap-1 border p-1 h-11 rounded-xl shadow-sm flex-shrink-0 w-full md:w-auto">
                            <a href="{{ route('admin.psicologia.maestros.agenda.index', ['view' => $view, 'date' => ($view === 'month' ? $currentDate->copy()->subMonth() : $currentDate->copy()->subWeek())->toDateString(), 'psicologo_id' => $psicologoId]) }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 transition-colors">
                                <i class="fas fa-chevron-left text-xs"></i>
                            </a>

                            <span
                                class="px-3 text-[10px] font-black min-w-[110px] text-center uppercase tracking-widest whitespace-nowrap"
                                style="color: var(--text-main);">
                                @if ($view === 'month')
                                    {{ $currentDate->translatedFormat('F Y') }}
                                @else
                                    Semana {{ ceil($currentDate->day / 7) }}
                                @endif
                            </span>

                            <a href="{{ route('admin.psicologia.maestros.agenda.index', ['view' => $view, 'date' => ($view === 'month' ? $currentDate->copy()->addMonth() : $currentDate->copy()->addWeek())->toDateString(), 'psicologo_id' => $psicologoId]) }}"
                                class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-400 transition-colors">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </div>
                    @endif

                    <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                        class="p-1 h-11 rounded-xl border flex items-center gap-1 flex-shrink-0 w-full md:w-auto justify-center">
                        <a href="{{ route('admin.psicologia.maestros.agenda.index', ['view' => 'month', 'date' => $currentDate->toDateString(), 'psicologo_id' => $psicologoId]) }}"
                            class="px-3 h-8 flex items-center justify-center rounded-lg text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $view === 'month' ? 'bg-' . $themeColor . '-50 dark:bg-' . $themeColor . '-950/50 text-' . $themeColor . '-600 dark:text-' . $themeColor . '-400 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Mes
                        </a>
                        <a href="{{ route('admin.psicologia.maestros.agenda.index', ['view' => 'week', 'date' => $currentDate->toDateString(), 'psicologo_id' => $psicologoId]) }}"
                            class="px-3 h-8 flex items-center justify-center rounded-lg text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap {{ $view === 'week' ? 'bg-' . $themeColor . '-50 dark:bg-' . $themeColor . '-950/50 text-' . $themeColor . '-600 dark:text-' . $themeColor . '-400 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                            Semana
                        </a>
                        <a href="{{ route('admin.psicologia.maestros.agenda.index', ['view' => 'list', 'date' => $currentDate->toDateString(), 'psicologo_id' => $psicologoId]) }}"
                            class="px-3 h-8 flex items-center justify-center rounded-lg transition-all {{ $view === 'list' ? 'bg-' . $themeColor . '-50 dark:bg-' . $themeColor . '-950/50 text-' . $themeColor . '-600 dark:text-' . $themeColor . '-400 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}"
                            title="Historial de Sesiones">
                            <i class="fas fa-list text-xs"></i>
                        </a>
                        <a href="{{ route('admin.psicologia.maestros.agenda.estadisticas', ['format' => 'html', 'psicologo_id' => $psicologoId]) }}"
                            class="px-3 h-8 flex items-center justify-center rounded-lg text-{{ $themeColor }}-600 hover:bg-{{ $themeColor }}-50 dark:hover:bg-{{ $themeColor }}-950/30 transition-all font-bold text-xs"
                            title="Panel Estadístico">
                            <i class="fas fa-chart-line text-xs"></i>
                        </a>
                        <a href="{{ route('admin.psicologia.maestros.agenda.exportarPdf', ['view' => $view, 'date' => $currentDate->toDateString(), 'psicologo_id' => $psicologoId]) }}"
                            target="_blank"
                            class="px-3 h-8 flex items-center justify-center rounded-lg text-gray-500 hover:text-rose-600 hover:bg-rose-50 transition-all font-bold text-xs"
                            title="Imprimir Agenda en PDF">
                            <i class="fas fa-file-pdf text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="grid grid-cols-1 {{ $view === 'week' ? 'xl:grid-cols-4' : '' }} gap-6 flex-col-reverse xl:flex-row">
                @if ($view === 'week')
                    <aside id="pendingRequestsPanel"
                        style="background-color: var(--bg-card); border-color: var(--border-color);"
                        class="xl:col-span-1 rounded-2xl border p-6 shadow-sm order-2 xl:order-1 flex flex-col max-h-[calc(100vh-250px)] overflow-hidden">
                        <div class="flex items-center gap-3 mb-6">
                            <h3 class="text-base font-bold uppercase tracking-wider" style="color: var(--text-main);">
                                Pendientes</h3>
                        </div>

                        <div class="space-y-4 mb-6">
                            <div class="relative">
                                <label
                                    class="block mb-1 text-[10px] font-black text-gray-400 uppercase tracking-wider">Paciente</label>
                                <input id="pendingFilter" type="text"
                                    style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                    class="w-full rounded-xl border px-3.5 py-2 text-xs font-medium focus:outline-none focus:ring-2 {{ $focusRingClass }} transition-all"
                                    placeholder="Buscar..." />
                                <div id="searchSpinner" class="absolute right-3 top-[30px] hidden">
                                    <i class="fas fa-spinner fa-spin text-{{ $themeColor }}-600 text-xs"></i>
                                </div>
                            </div>
                            <div>
                                <label
                                    class="block mb-1 text-[10px] font-black text-gray-400 uppercase tracking-wider">Prioridad</label>
                                <select id="priorityFilter"
                                    style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                    class="w-full rounded-xl border px-3.5 py-2 text-xs font-medium focus:outline-none focus:ring-2 {{ $focusRingClass }} transition-all">
                                    <option value="">Todas</option>
                                    @foreach ($prioridadesDisponibles as $prioridad)
                                        <option value="{{ $prioridad->nombre }}">{{ ucfirst($prioridad->nombre) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @include('admin.psicologia.maestros.agenda.components.pending-list')
                    </aside>
                @endif

                <section class="{{ $view === 'week' ? 'xl:col-span-3' : 'w-full' }} relative order-1 xl:order-2">
                    <div id="agendaMainView" class="transition-all duration-300 w-full rounded-2xl">
                        @if ($view === 'month')
                            <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                                class="rounded-2xl border shadow-sm overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)] invisible-scrollbar relative">
                                <div class="min-w-[700px]">
                                    <div
                                        class="grid grid-cols-7 border-b border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-800/50 sticky top-0 z-10">
                                        @foreach (['DOM', 'LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁ'] as $diaLabel)
                                            <div
                                                class="py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                                {{ $diaLabel }}</div>
                                        @endforeach
                                    </div>
                                    <div class="grid grid-cols-7">
                                        @foreach ($calendarioData as $data)
                                            <div onclick="openDailyAgenda(this, '{{ $data['date'] }}')"
                                                class="min-h-[110px] p-2 border-b border-r border-gray-100 dark:border-gray-800 relative group cursor-pointer hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-all {{ !$data['isCurrentMonth'] ? 'opacity-40' : '' }} {{ $data['isToday'] ? 'bg-' . $themeColor . '-50/30 dark:bg-' . $themeColor . '-950/20' : '' }}"
                                                data-date="{{ $data['date'] }}">
                                                <div class="flex justify-between items-start mb-1.5">
                                                    <span
                                                        class="text-xs font-black {{ $data['isToday'] ? 'w-6 h-6 ' . $btnClass . ' text-white rounded-lg flex items-center justify-center shadow-sm' : 'text-gray-700 dark:text-gray-300' }}">
                                                        {{ $data['day'] }}
                                                    </span>
                                                </div>

                                                <div
                                                    class="space-y-1 overflow-y-auto max-h-[75px] custom-scrollbar pointer-events-none">
                                                    @foreach ($data['citas']->where('estado', '!=', 'cancelada') as $cita)
                                                        <div
                                                            class="px-2 py-0.5 rounded-md text-[9px] font-bold truncate
                                                        {{ $cita->estado === 'confirmada'
                                                            ? 'bg-sky-100 dark:bg-sky-950/50 text-sky-700 dark:text-sky-300 border border-sky-200 dark:border-sky-800'
                                                            : ($cita->estado === 'realizada'
                                                                ? 'bg-emerald-100 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
                                                                : ($cita->estado === 'no_asistio'
                                                                    ? 'bg-rose-100 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800'
                                                                    : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400')) }}">
                                                            {{ $cita->hora ? \Carbon\Carbon::parse($cita->hora)->format('g:i A') : 'S/H' }}
                                                            - {{ $cita->paciente_short_name }}
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @elseif($view === 'list')
                            <!-- Vista Adaptada al Nuevo Diseño -->
                            <div id="citas-list-container"
                                style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                                class="rounded-2xl border shadow-sm overflow-hidden">

                                <div class="p-6 border-b border-gray-100 dark:border-gray-800/80 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                    <div>
                                        <h3 class="text-lg font-bold tracking-tight" style="color: var(--text-main);">
                                            Historial de Citas
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Consulta y gestiona las citas agendadas.</p>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-900/40">
                                        Total: {{ $citasCalendario->total() }} registros
                                    </span>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-gray-50/50 dark:bg-black/20 border-b border-gray-100 dark:border-gray-800 text-[11px] font-black uppercase tracking-wider text-gray-400">
                                                <th class="px-6 py-4">Paciente</th>
                                                <th class="px-6 py-4">Solicitada</th>
                                                <th class="px-6 py-4">Fecha y Hora</th>
                                                <th class="px-6 py-4">Estado</th>
                                                <th class="px-6 py-4 text-right">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 text-xs font-medium">
                                            @forelse($citasCalendario as $cita)
                                                <tr class="hover:bg-gray-50/60 dark:hover:bg-white/[0.02] transition-colors">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex items-center gap-3">
                                                            <div class="w-8 h-8 rounded-xl bg-{{ $themeColor }}-50 dark:bg-{{ $themeColor }}-950/50 text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400 flex items-center justify-center font-bold text-xs">
                                                                {{ substr($cita->paciente_nombre, 0, 1) }}
                                                            </div>
                                                            <span class="font-bold text-sm" style="color: var(--text-main);">
                                                                {{ $cita->paciente_nombre }}
                                                            </span>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex flex-col">
                                                            <span class="text-xs font-medium text-gray-600 dark:text-gray-300">
                                                                {{ $cita->created_at ? \Carbon\Carbon::parse($cita->created_at)->translatedFormat('d M, Y') : 'N/A' }}
                                                            </span>
                                                            <span class="text-[10px] text-gray-400">
                                                                {{ $cita->created_at ? \Carbon\Carbon::parse($cita->created_at)->format('g:i A') : '' }}
                                                            </span>
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="flex flex-col">
                                                            @if (!$cita->hora)
                                                                <span class="text-xs font-medium text-gray-400 italic">Sin horario asignado</span>
                                                            @else
                                                                <span class="font-bold text-xs" style="color: var(--text-main);">
                                                                    {{ $cita->fecha ? $cita->fecha->translatedFormat('d M, Y') : 'Sin fecha' }}
                                                                </span>
                                                                <span class="text-[10px] text-gray-400">
                                                                    {{ \Carbon\Carbon::parse($cita->hora)->format('g:i A') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                                            {{ $cita->estado === 'realizada'
                                                                ? 'bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800'
                                                                : ($cita->estado === 'cancelada'
                                                                    ? 'bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800'
                                                                    : ($cita->estado === 'rechazada'
                                                                        ? 'bg-amber-50 dark:bg-amber-950/50 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-800'
                                                                        : ($cita->estado === 'confirmada'
                                                                            ? 'bg-sky-50 dark:bg-sky-950/50 text-sky-600 dark:text-sky-400 border border-sky-200 dark:border-sky-800'
                                                                            : ($cita->estado === 'no_asistio'
                                                                                ? 'bg-orange-50 dark:bg-orange-950/50 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-800'
                                                                                : 'bg-gray-100 dark:bg-gray-800 text-gray-500 border border-gray-200 dark:border-gray-700')))) }}">
                                                            {{ str_replace('_', ' ', $cita->estado) }}
                                                        </span>
                                                    </td>

                                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                                        <div class="flex items-center justify-end gap-1">
                                                            <button type="button"
                                                                onclick="abrirDetalleCita({{ json_encode([
                                                                    'paciente' => $cita->paciente_nombre,
                                                                    'estado' => $cita->estado,
                                                                    'motivo' => $cita->motivo ?? 'No especificado',
                                                                    'prioridad' => $cita->prioridad ?? 'Normal',
                                                                    'fecha_solicitud' => $cita->created_at
                                                                        ? \Carbon\Carbon::parse($cita->created_at)->translatedFormat('d M, Y - g:i A')
                                                                        : 'N/A',
                                                                    'fecha_programada' =>
                                                                        $cita->fecha && $cita->hora
                                                                            ? $cita->fecha->translatedFormat('d M, Y') . ' - ' . \Carbon\Carbon::parse($cita->hora)->format('g:i A')
                                                                            : 'Sin horario asignado',
                                                                    'fecha_programada_iso' => $cita->fecha ? $cita->fecha->format('Y-m-d') : null,
                                                                    'hora_programada_iso' => $cita->hora ? \Carbon\Carbon::parse($cita->hora)->format('H:i') : null,
                                                                    'cancelado_por' => $cita->cancelado_por ?? null,
                                                                    'motivo_rechazo' => $cita->motivo_rechazo_propuesta ?? null,
                                                                ]) }})"
                                                                class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/50 transition-all"
                                                                title="Ver Detalles">
                                                                <i class="fas fa-eye text-sm"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                                        <div class="flex flex-col items-center gap-2">
                                                            <i class="fas fa-folder-open text-3xl opacity-40 mb-1"></i>
                                                            <p class="text-xs font-semibold">No se encontraron registros de citas.</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                @if ($citasCalendario->hasPages())
                                    <div id="citas-pagination"
                                        class="px-6 py-4 flex justify-center border-t border-gray-100 dark:border-gray-800">
                                        {{ $citasCalendario->appends(request()->query())->links('admin.psicologia.maestros.agenda.partials.pagination') }}
                                    </div>
                                @endif
                            </div>
                        @else
                            @if (isset($grupoActivo) && $horarios->isNotEmpty())
                                @php
                                    $currentDate =
                                        $currentDate->dayOfWeek === \Carbon\Carbon::SUNDAY
                                            ? $currentDate->copy()->next(\Carbon\Carbon::MONDAY)
                                            : $currentDate->copy()->startOfWeek(\Carbon\Carbon::MONDAY);

                                    $normalizeBlock = function ($text) {
                                        $value = trim($text ?? '');
                                        $value = preg_replace_callback(
                                            '/(\d{1,2}):(\d{2})\s*(am|pm)\b/i',
                                            function ($matches) {
                                                $hours = (int) $matches[1];
                                                $ampm = strtolower($matches[3]);
                                                if ($ampm === 'pm' && $hours < 12) {
                                                    $hours += 12;
                                                }
                                                if ($ampm === 'am' && $hours === 12) {
                                                    $hours = 0;
                                                }
                                                return sprintf('%02d:%s', $hours, $matches[2]);
                                            },
                                            $value,
                                        );
                                        $value = preg_replace(
                                            ['/\s*[-–—]\s*/u', '/(\d{1,2}:\d{2}):\d{2}/', '/\s+/'],
                                            ['-', '$1', ' '],
                                            $value,
                                        );
                                        $value = preg_replace('/(^|\s|-)(\d):/', '${1}0$2:', $value);
                                        return strtolower($value);
                                    };

                                    $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];

                                    $defaultIntervalos = collect([
                                        ['inicio' => '07:00', 'fin' => '08:15'],
                                        ['inicio' => '08:15', 'fin' => '09:20'],
                                        ['inicio' => '09:20', 'fin' => '10:00'],
                                        ['inicio' => '10:00', 'fin' => '10:45'],
                                        ['inicio' => '10:45', 'fin' => '11:30'],
                                        ['inicio' => '11:30', 'fin' => '12:20'],
                                        ['inicio' => '12:20', 'fin' => '13:00'],
                                        ['inicio' => '13:00', 'fin' => '13:45'],
                                        ['inicio' => '13:45', 'fin' => '14:25'],
                                        ['inicio' => '14:25', 'fin' => '15:05'],
                                        ['inicio' => '15:05', 'fin' => '15:45'],
                                        ['inicio' => '16:00', 'fin' => '16:40'],
                                        ['inicio' => '16:40', 'fin' => '17:20'],
                                        ['inicio' => '17:20', 'fin' => '18:00'],
                                        ['inicio' => '18:00', 'fin' => '18:35'],
                                        ['inicio' => '18:35', 'fin' => '19:10'],
                                        ['inicio' => '19:10', 'fin' => '19:45'],
                                        ['inicio' => '19:45', 'fin' => '20:20'],
                                        ['inicio' => '20:20', 'fin' => '20:55'],
                                        ['inicio' => '20:55', 'fin' => '21:30'],
                                    ]);

                                    $intervalos = $defaultIntervalos
                                        ->sortBy(fn($item) => \Carbon\Carbon::parse($item['inicio'])->timestamp)
                                        ->values()
                                        ->all();
                                @endphp
                                <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                                    class="overflow-x-auto overflow-y-auto max-h-[calc(100vh-250px)] rounded-2xl border shadow-sm invisible-scrollbar relative">
                                    <table
                                        class="min-w-[800px] w-full divide-y divide-gray-100 dark:divide-gray-800 text-sm">
                                        <thead
                                            class="bg-gray-50/80 dark:bg-gray-800/80 backdrop-blur-md sticky top-0 z-10">
                                            <tr>
                                                <th
                                                    class="px-4 py-3 text-center text-[10px] font-black text-gray-400 uppercase tracking-wider border-r border-gray-100 dark:border-gray-800">
                                                    Hora
                                                </th>
                                                @foreach ($dias as $diaHeaderIndex => $dia)
                                                    @php
                                                        $fechaColumna = $currentDate->copy()->addDays($diaHeaderIndex);
                                                        $esHoy = $fechaColumna->isToday();
                                                    @endphp
                                                    <th
                                                        class="px-4 py-3 text-center uppercase tracking-wider {{ $esHoy ? 'bg-' . $themeColor . '-50/50 dark:bg-' . $themeColor . '-950/20' : '' }}">
                                                        <div class="flex flex-col items-center gap-0.5">
                                                            <span
                                                                class="text-[9px] font-black {{ $esHoy ? 'text-' . $themeColor . '-600 dark:text-' . $themeColor . '-400' : 'text-gray-400' }} tracking-widest">{{ $dia }}</span>
                                                            <span
                                                                class="@if ($esHoy) w-6 h-6 {{ $btnClass }} text-white rounded-md flex items-center justify-center text-xs font-bold shadow-sm @else text-xs font-bold text-gray-700 dark:text-gray-300 @endif">
                                                                {{ $fechaColumna->day }}
                                                            </span>
                                                        </div>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                                            @php $sectionActual = null; @endphp
                                            @foreach ($intervalos as $intervalo)
                                                @php
                                                    $t = \Carbon\Carbon::parse($intervalo['inicio']);
                                                    $seccion = $t->lt(\Carbon\Carbon::parse('12:30'))
                                                        ? 'Mañana'
                                                        : ($t->lt(\Carbon\Carbon::parse('18:00'))
                                                            ? 'Vespertino'
                                                            : 'Nocturno');
                                                @endphp

                                                @if ($sectionActual !== $seccion)
                                                    <tr class="bg-gray-50/50 dark:bg-gray-800/30">
                                                        <td colspan="6"
                                                            class="px-4 py-1.5 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">
                                                            {{ $seccion }}
                                                        </td>
                                                    </tr>
                                                    @php $sectionActual = $seccion; @endphp
                                                @endif

                                                <tr>
                                                    <td
                                                        class="px-3 py-3 text-center text-[10px] font-bold text-gray-400 border-r border-gray-100 dark:border-gray-800 bg-gray-50/20 dark:bg-gray-800/20">
                                                        {{ \Carbon\Carbon::parse($intervalo['inicio'])->format('g:i') }}
                                                        - {{ \Carbon\Carbon::parse($intervalo['fin'])->format('g:i') }}
                                                    </td>
                                                    @foreach ($dias as $diaIndex => $dia)
                                                        @php
                                                            $horaInicio = $intervalo['inicio'];
                                                            $horaFin = $intervalo['fin'];
                                                            $horarioBloque = $horarios
                                                                ->where('dia', $dia)
                                                                ->first(function ($h) use ($horaInicio, $horaFin) {
                                                                    $inicioConfig = \Carbon\Carbon::parse($h->hora_inicio)->format('H:i');
                                                                    $finConfig = \Carbon\Carbon::parse($h->hora_fin)->format('H:i');

                                                                    return $inicioConfig <= $horaInicio && $finConfig >= $horaFin;
                                                                });
                                                            $bloqueLabel = $horarioBloque
                                                                ? $dia .
                                                                    ' ' .
                                                                    \Carbon\Carbon::parse(
                                                                        $horarioBloque->hora_inicio,
                                                                    )->format('H:i') .
                                                                    ' - ' .
                                                                    \Carbon\Carbon::parse(
                                                                        $horarioBloque->hora_fin,
                                                                    )->format('H:i')
                                                                : "$dia $horaInicio - $horaFin";
                                                            $normalizedSlotText = $normalizeBlock($bloqueLabel);
                                                            $fechaDelDia = $currentDate
                                                                ->copy()
                                                                ->addDays($diaIndex)
                                                                ->toDateString();

                                                            $citasConfirmadasEnSlot = $citasCalendario->filter(
                                                                fn($cita) => in_array($cita->estado, [
                                                                    'confirmada',
                                                                    'realizada',
                                                                    'no_asistio',
                                                                ]) &&
                                                                    $cita->fecha->isSameDay($fechaDelDia) &&
                                                                    $cita->bloque_propuesto &&
                                                                    str_contains(
                                                                        $normalizeBlock($cita->bloque_propuesto),
                                                                        $normalizedSlotText,
                                                                    ),
                                                            );
                                                            $assignedCita = $citasConfirmadasEnSlot->first();

                                                            $citasCanceladasEnSlot = $citasCalendario->filter(
                                                                fn($cita) => $cita->estado === 'cancelada' &&
                                                                    $cita->fecha &&
                                                                    $cita->fecha->isSameDay($fechaDelDia) &&
                                                                    $cita->bloque_propuesto &&
                                                                    str_contains(
                                                                        $normalizeBlock($cita->bloque_propuesto),
                                                                        $normalizedSlotText,
                                                                    ),
                                                            );
                                                            $canceladaCita = $citasCanceladasEnSlot
                                                                ->sortByDesc('updated_at')
                                                                ->first();

                                                            $citasEnSlot = $citasPendientes->filter(function (
                                                                $cita,
                                                            ) use (
                                                                $normalizedSlotText,
                                                                $normalizeBlock,
                                                                $dia,
                                                                $horaInicio,
                                                                $horaFin,
                                                                $fechaDelDia,
                                                            ) {
                                                                if (!$cita->bloques_sugeridos) {
                                                                    return false;
                                                                }
                                                                $raw = $cita->bloques_sugeridos;
                                                                $excepcionesStr = '';
                                                                $horariosStr = $raw;
                                                                if (str_contains($raw, '|')) {
                                                                    $parts = explode('|', $raw);
                                                                    $leftPart = trim($parts[0]);
                                                                    $rightPart = trim($parts[1]);
                                                                    if (str_contains($leftPart, 'exceptuados')) {
                                                                        $excepcionesStr = trim(
                                                                            str_replace(
                                                                                'Días exceptuados:',
                                                                                '',
                                                                                $leftPart,
                                                                            ),
                                                                        );
                                                                    }
                                                                    $horariosStr = preg_replace(
                                                                        '/^\s*Horarios\s*(propuestos)?\s*:\s*/i',
                                                                        '',
                                                                        $rightPart,
                                                                    );
                                                                }
                                                                if ($excepcionesStr !== '') {
                                                                    $excepcionesArray = array_map(
                                                                        'trim',
                                                                        explode(',', $excepcionesStr),
                                                                    );
                                                                    if (in_array($fechaDelDia, $excepcionesArray)) {
                                                                        return false;
                                                                    }
                                                                }
                                                                if (
                                                                    \Carbon\Carbon::parse($fechaDelDia)->isBefore(
                                                                        \Carbon\Carbon::today(),
                                                                    )
                                                                ) {
                                                                    return false;
                                                                }
                                                                if (
                                                                    \Carbon\Carbon::parse($fechaDelDia)->isAfter(
                                                                        \Carbon\Carbon::today()->addMonth(),
                                                                    )
                                                                ) {
                                                                    return false;
                                                                }

                                                                $bloques = array_filter(
                                                                    array_map(
                                                                        'trim',
                                                                        explode(
                                                                            ';',
                                                                            str_replace(',', ';', $horariosStr),
                                                                        ),
                                                                    ),
                                                                );
                                                                foreach ($bloques as $bloque) {
                                                                    if (
                                                                        str_contains($bloque, $fechaDelDia) ||
                                                                        str_contains(
                                                                            $normalizeBlock($bloque),
                                                                            strtolower($dia),
                                                                        )
                                                                    ) {
                                                                        if (
                                                                            preg_match(
                                                                                '/(\d{1,2}:\d{2}(?:\s*[aApP][mM])?)\s*[-\x96\x97]\s*(\d{1,2}:\d{2}(?:\s*[aApP][mM])?)/i',
                                                                                $bloque,
                                                                                $m,
                                                                            )
                                                                        ) {
                                                                            $sI = \Carbon\Carbon::parse($m[1]);
                                                                            $sF = \Carbon\Carbon::parse($m[2]);
                                                                            if (
                                                                                \Carbon\Carbon::parse($horaInicio)->lt(
                                                                                    $sF,
                                                                                ) &&
                                                                                \Carbon\Carbon::parse($horaFin)->gt($sI)
                                                                            ) {
                                                                                return true;
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                                return false;
                                                            });
                                                        @endphp
                                                        <td class="px-1.5 py-2">
                                                            @if ($horarioBloque)
                                                                <button type="button"
                                                                    class="block-slot-button w-full rounded-xl border p-2 text-center transition-all group
                                                                {{ $assignedCita
                                                                    ? 'bg-indigo-50 dark:bg-indigo-950/40 border-indigo-200 dark:border-indigo-800 text-indigo-700 dark:text-indigo-400 shadow-sm'
                                                                    : ($horarioBloque->activo === \App\Models\salud\Horario::STATUS_ACTIVE
                                                                        ? 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:border-' .
                                                                            $themeColor .
                                                                            '-500 hover:shadow-sm'
                                                                        : 'bg-orange-50 dark:bg-orange-950/40 border-orange-200 dark:border-orange-800 text-orange-700 dark:text-orange-400') }}"
                                                                    data-block-label="{{ $bloqueLabel }}"
                                                                    data-block-date="{{ $fechaDelDia }}"
                                                                    data-block-time="{{ $horarioBloque->hora_inicio }}"
                                                                    data-block-active="{{ $horarioBloque->activo === \App\Models\salud\Horario::STATUS_ACTIVE ? 'true' : 'false' }}"
                                                                    @if ($assignedCita) data-assigned-cita-id="{{ $assignedCita->id }}" data-assigned-paciente="{{ $assignedCita->paciente_short_name }}" data-assigned-estado="{{ $assignedCita->estado }}" data-assigned-block="true" @endif>

                                                                    <div
                                                                        class="flex items-center justify-center mb-0.5">
                                                                        <span
                                                                            class="text-[9px] font-black uppercase tracking-wider text-gray-400">
                                                                            {{ \Carbon\Carbon::parse($horarioBloque->hora_inicio)->format('g:i') }}
                                                                            -
                                                                            {{ \Carbon\Carbon::parse($horarioBloque->hora_fin)->format('g:i') }}
                                                                        </span>
                                                                    </div>

                                                                    <div
                                                                        class="block-slot-status flex flex-col items-center gap-0.5">
                                                                        @if ($assignedCita)
                                                                            <div class="flex items-center gap-1">
                                                                                @if ($assignedCita->estado === 'realizada')
                                                                                    <span
                                                                                        class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                                                @elseif($assignedCita->estado === 'no_asistio')
                                                                                    <span
                                                                                        class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                                                @else
                                                                                    <span
                                                                                        class="w-1.5 h-1.5 rounded-full bg-sky-500"></span>
                                                                                @endif
                                                                                <p
                                                                                    class="text-[10px] font-black truncate text-indigo-700 dark:text-indigo-400">
                                                                                    {{ $assignedCita->paciente_short_name }}
                                                                                </p>
                                                                            </div>
                                                                        @else
                                                                            @if ($citasEnSlot->isNotEmpty())
                                                                                <div class="flex items-center gap-1">
                                                                                    <span
                                                                                        class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                                                    <p
                                                                                        class="text-[10px] font-bold text-amber-600 dark:text-amber-400">
                                                                                        {{ $citasEnSlot->count() }}
                                                                                        Sol.
                                                                                    </p>
                                                                                </div>
                                                                            @else
                                                                                <p
                                                                                    class="text-[9px] font-bold text-gray-300 dark:text-gray-600 uppercase">
                                                                                    Libre</p>
                                                                            @endif

                                                                            @if ($canceladaCita)
                                                                                <div class="flex items-center justify-center gap-1 w-full mt-0.5"
                                                                                    onclick="event.stopPropagation()">
                                                                                    <span
                                                                                        class="w-1.5 h-1.5 rounded-full bg-gray-400 flex-shrink-0"></span>
                                                                                    <p
                                                                                        class="text-[9px] font-bold truncate text-gray-400 line-through">
                                                                                        {{ $canceladaCita->paciente_short_name }}
                                                                                    </p>
                                                                                    <div onclick="dismissCancelMessage(event, {{ $canceladaCita->id }})"
                                                                                        class="ml-0.5 text-[9px] font-black text-gray-400 hover:text-gray-600 cursor-pointer">
                                                                                        ✕</div>
                                                                                </div>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                </button>
                                                            @else
                                                                <div class="h-8 flex items-center justify-center">
                                                                    <div
                                                                        class="w-1 h-1 bg-gray-200 dark:bg-gray-700 rounded-full">
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                                    class="rounded-2xl border-2 border-dashed p-12 text-center shadow-sm">
                                    <div
                                        class="w-16 h-16 bg-{{ $themeColor }}-50 dark:bg-{{ $themeColor }}-950/50 text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400 rounded-2xl flex items-center justify-center mx-auto mb-4 text-2xl">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>
                                    <h3 class="text-lg font-bold mb-1" style="color: var(--text-main);">Sin Horarios
                                        Activos</h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-sm mx-auto">
                                        Configura o activa un grupo de horarios para empezar a agendar citas.
                                    </p>
                                </div>
                            @endif
                        @endif
                    </div>

                    <div id="agendaBlockManagerView"
                        style="background-color: var(--bg-card); border-color: var(--border-color);"
                        class="hidden opacity-0 transition-all duration-300 w-full rounded-2xl border shadow-sm p-6">
                        <div class="flex flex-col h-full min-h-[380px]">
                            <div
                                class="flex items-center justify-between border-b border-gray-100 dark:border-gray-800 pb-4 mb-6">
                                <div>
                                    <button type="button" onclick="closeBlockManager()"
                                        class="flex items-center gap-2 text-{{ $themeColor }}-600 hover:text-{{ $themeColor }}-700 font-bold text-xs uppercase tracking-wider mb-1">
                                        <i class="fas fa-arrow-left"></i> Volver a la Agenda
                                    </button>
                                    <h3 class="text-xl font-bold tracking-tight" id="blockManagerTitle"
                                        style="color: var(--text-main);"></h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="blockManagerPrevBtn" onclick="navigateBlock(-1)"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                        <i class="fas fa-chevron-left text-xs"></i>
                                    </button>
                                    <button type="button" id="blockManagerNextBtn" onclick="navigateBlock(1)"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl border border-gray-200 dark:border-gray-700 text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                        <i class="fas fa-chevron-right text-xs"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 flex-1" id="blockManagerGrid">
                                <div class="flex flex-col h-full transition-all duration-300" id="colCandidatos">
                                    <div class="flex justify-between items-center px-1 mb-3">
                                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                            Candidatos Disponibles</h4>
                                        <span
                                            class="text-[9px] font-bold px-2 py-0.5 rounded-md bg-gray-100 dark:bg-gray-800 text-gray-500 uppercase">Lista
                                            de Espera</span>
                                    </div>
                                    <div
                                        class="w-full h-[300px] rounded-xl border border-gray-100 dark:border-gray-800 bg-gray-50/50 dark:bg-gray-900/30 p-3 flex flex-col">
                                        <ul id="blockRequestsList"
                                            class="space-y-2.5 custom-scrollbar overflow-y-auto flex-1 pr-1"></ul>
                                    </div>
                                </div>

                                <div class="flex flex-col h-full transition-all duration-300" id="colEstado">
                                    <div class="flex justify-between items-center px-1 mb-3">
                                        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-wider">
                                            Estado de Cita</h4>
                                        <span id="blockConfirmationBadge"
                                            class="text-[9px] font-bold px-2.5 py-0.5 rounded-md bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 uppercase hidden">Confirmado</span>
                                    </div>
                                    <div id="blockConfirmedContainer"
                                        class="w-full h-[300px] rounded-xl border-2 border-dashed border-gray-200 dark:border-gray-800 flex flex-col items-center justify-center p-6 text-center">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const CONFIG = {
                endpoints: {
                    json: (id) => `{{ url('citas') }}/${id}/json`,
                    prioridad: (id) => `{{ url('citas') }}/${id}/prioridad`,
                    rechazar: (id) => `{{ url('citas') }}/${id}/rechazar`,
                    aceptar: (id) => `{{ url('citas') }}/${id}/aceptar`,
                    proponer: (id) => `{{ url('citas') }}/${id}/proponer`,
                    quitarPropuesta: (id) => `{{ url('citas') }}/${id}/quitar-propuesta`,
                    enviarPropuesta: (id) => `{{ url('citas') }}/${id}/enviar-propuesta`,
                    realizar: (id) => `{{ url('citas') }}/${id}/realizar`,
                    noAsistio: (id) => `{{ url('citas') }}/${id}/no-asistio`,
                    posponer: (id) => `{{ url('citas') }}/${id}/posponer`,
                    cancelar: (id) => `{{ url('citas') }}/${id}/cancelar-psicologo`,
                    pendingList: '{{ route('admin.psicologia.maestros.agenda.pending.list') }}',
                    dailyCitas: '{{ route('admin.psicologia.maestros.agenda.daily_citas') }}'
                },
                csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            };

            let state = {
                currentCitaId: null,
                currentCitaIndex: -1,
                pendingCitaIds: [],
                currentBlockLabel: null,
                currentBlockDate: null
            };

            const Utils = {
                escapeHtml: (str) => {
                    const div = document.createElement('div');
                    div.textContent = str || '';
                    return div.innerHTML;
                },
                formatAmPm: (label) => {
                    if (!label) return 'En espera';
                    return label.replace(/(\d{1,2}):(\d{2})/g, (m, h, min) => {
                        let hh = parseInt(h);
                        return `${hh % 12 || 12}:${min} ${hh >= 12 ? 'PM' : 'AM'}`;
                    });
                },
                normalize: (l) => {
                    let s = (l || '').trim().toLowerCase()
                        .replace(/(\d{1,2}):(\d{2})\s*(am|pm)\b/g, (m, h, min, ampm) => {
                            let hh = parseInt(h);
                            if (ampm === 'pm' && hh < 12) hh += 12;
                            if (ampm === 'am' && hh === 12) hh = 0;
                            return `${hh < 10 ? '0' : ''}${hh}:${min}`;
                        });
                    return s.replace(/(\d{1,2}:\d{2}):\d{2}/g, '$1').replace(/\s*[-–—]\s*/g, '-').replace(
                        /\s+/g, ' ').replace(/(^|\s|-)(\d):/g, '$10$2:');
                },
                api: (url, method = 'GET', body = null) => {
                    const options = {
                        method,
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': CONFIG.csrfToken
                        }
                    };
                    if (body) options.body = JSON.stringify(body);
                    return fetch(url, options).then(res => res.ok ? res.json() : Promise.reject(res));
                }
            };

            function openBlockManager(cell) {
                if (!cell) return;
                state.currentBlockLabel = cell.dataset.blockLabel;
                state.currentBlockDate = cell.dataset.blockDate;

                const title = document.getElementById('blockManagerTitle');
                if (title) {
                    const parsedDate = new Date(state.currentBlockDate + 'T12:00:00');
                    const dateStr = isNaN(parsedDate.getTime()) ? '' : parsedDate.toLocaleDateString('es-ES', {
                        weekday: 'long',
                        day: 'numeric',
                        month: 'long'
                    });
                    const timeOnly = (state.currentBlockLabel || '').replace(/^[a-záéíóúñü]+\s+/i, '');
                    title.textContent = dateStr.charAt(0).toUpperCase() + dateStr.slice(1) + ' · ' + Utils
                        .formatAmPm(timeOnly);
                }

                renderBlockRequests(cell);
                const mainView = document.getElementById('agendaMainView');
                const blockView = document.getElementById('agendaBlockManagerView');

                if (mainView && blockView && blockView.classList.contains('hidden')) {
                    mainView.classList.add('opacity-0');
                    setTimeout(() => {
                        mainView.classList.add('hidden');
                        blockView.classList.remove('hidden');
                        void blockView.offsetWidth;
                        blockView.classList.remove('opacity-0');
                    }, 300);
                }
            }

            window.closeBlockManager = function() {
                const mainView = document.getElementById('agendaMainView');
                const blockView = document.getElementById('agendaBlockManagerView');

                if (mainView && blockView) {
                    blockView.classList.add('opacity-0');
                    setTimeout(() => {
                        blockView.classList.add('hidden');
                        mainView.classList.remove('hidden');
                        void mainView.offsetWidth;
                        mainView.classList.remove('opacity-0');
                    }, 300);
                }
            };

            window.navigateBlock = function(dir) {
                let allButtons = Array.from(document.querySelectorAll('.block-slot-button'));
                if (!allButtons.length) return;

                let uniqueBlocksMap = new Map();
                allButtons.forEach(b => {
                    const key = b.dataset.blockDate + '|' + b.dataset.blockLabel;
                    if (!uniqueBlocksMap.has(key)) uniqueBlocksMap.set(key, b);
                });
                let buttons = Array.from(uniqueBlocksMap.values());

                buttons.sort((a, b) => {
                    const dateA = new Date(a.dataset.blockDate + 'T' + (a.dataset.blockTime ||
                        '00:00:00'));
                    const dateB = new Date(b.dataset.blockDate + 'T' + (b.dataset.blockTime ||
                        '00:00:00'));
                    return dateA.getTime() - dateB.getTime();
                });

                let currentIndex = buttons.findIndex(b => b.dataset.blockLabel === state.currentBlockLabel && b
                    .dataset.blockDate === state.currentBlockDate);
                if (currentIndex === -1) currentIndex = 0;

                let nextIndex = currentIndex + dir;
                if (nextIndex < 0) nextIndex = buttons.length - 1;
                if (nextIndex >= buttons.length) nextIndex = 0;

                if (buttons[nextIndex]) openBlockManager(buttons[nextIndex]);
            };

            document.querySelectorAll('.block-slot-button').forEach(btn => {
                btn.addEventListener('click', function() {
                    openBlockManager(this);
                });
            });

            function renderBlockRequests(cell) {
                const list = document.getElementById('blockRequestsList');
                const assignedList = document.getElementById('blockConfirmedContainer');
                const badge = document.getElementById('blockConfirmationBadge');
                const colCandidatos = document.getElementById('colCandidatos');
                const colEstado = document.getElementById('colEstado');

                if (!list || !assignedList) return;

                list.innerHTML = '';
                assignedList.innerHTML = '';
                const assignedPac = cell.dataset.assignedPaciente;
                const assignedId = cell.dataset.assignedCitaId;
                const assignedEstado = cell.dataset.assignedEstado;

                if (assignedPac) {
                    if (colCandidatos) colCandidatos.classList.add('hidden');
                    if (colEstado) {
                        colEstado.classList.remove('hidden');
                        colEstado.classList.add('lg:col-span-2');
                    }
                    if (badge) {
                        badge.classList.remove('hidden');
                        badge.textContent = assignedEstado === 'realizada' ? 'Realizada' : (assignedEstado ===
                            'no_asistio' ? 'Ausente' : 'Confirmado');
                    }

                    assignedList.innerHTML = `
                    <div class="w-14 h-14 rounded-2xl bg-{{ $themeColor }}-50 dark:bg-{{ $themeColor }}-950/50 text-{{ $themeColor }}-600 flex items-center justify-center mb-3 text-xl font-bold shadow-sm">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Paciente Asignado</span>
                    <h3 class="text-lg font-bold" style="color: var(--text-main);">${Utils.escapeHtml(assignedPac)}</h3>
                `;
                } else {
                    if (colEstado) colEstado.classList.add('hidden');
                    if (colCandidatos) {
                        colCandidatos.classList.remove('hidden');
                        colCandidatos.classList.add('lg:col-span-2');
                    }
                    if (badge) badge.classList.add('hidden');

                    list.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-full text-center py-8 text-gray-400">
                        <i class="fas fa-user-clock text-2xl mb-2"></i>
                        <p class="text-xs font-bold uppercase tracking-wider">Sin pacientes asignados</p>
                    </div>
                `;
                }
            }
        });
    </script>

    <!-- Modal: Filtro de Fechas -->
    <div id="filterModal"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div style="background-color: var(--bg-card); border-color: var(--border-color);"
            class="w-full max-w-md rounded-2xl border shadow-xl p-6 relative">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                <h3 class="text-base font-extrabold uppercase tracking-wider" style="color: var(--text-main);">Filtrar
                    Historial</h3>
                <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form method="GET" action="{{ route('admin.psicologia.maestros.agenda.index') }}"
                class="mt-4 space-y-4">
                <input type="hidden" name="view" value="list">
                <input type="hidden" name="psicologo_id" value="{{ $psicologoId }}">

                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Fecha
                        Desde</label>
                    <input type="date" name="from_date" value="{{ request('from_date') }}"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Fecha
                        Hasta</label>
                    <input type="date" name="to_date" value="{{ request('to_date') }}"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3.5 py-2 text-xs">
                </div>
                <div>
                    <label
                        class="block text-[10px] font-black text-gray-400 uppercase tracking-wider mb-1">Estado</label>
                    <select name="estado"
                        class="w-full rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 px-3.5 py-2 text-xs">
                        <option value="">Todos los estados</option>
                        <option value="confirmada" {{ request('estado') === 'confirmada' ? 'selected' : '' }}>
                            Confirmada</option>
                        <option value="realizada" {{ request('estado') === 'realizada' ? 'selected' : '' }}>Realizada
                        </option>
                        <option value="no_asistio" {{ request('estado') === 'no_asistio' ? 'selected' : '' }}>No
                            Asistió</option>
                        <option value="cancelada" {{ request('estado') === 'cancelada' ? 'selected' : '' }}>Cancelada
                        </option>
                    </select>
                </div>
                <div class="flex items-center justify-end gap-2 pt-4">
                    <button type="button" onclick="document.getElementById('filterModal').classList.add('hidden')"
                        class="px-4 h-9 rounded-xl text-xs font-bold text-gray-500 hover:bg-gray-100 dark:hover:bg-gray-800">Cancelar</button>
                    <button type="submit"
                        class="px-4 h-9 rounded-xl text-xs font-bold text-white {{ $btnClass }}">Aplicar
                        Filtros</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Detalle de Cita -->
    <div id="detalleCitaModal"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
        <div style="background-color: var(--bg-card); border-color: var(--border-color);"
            class="w-full max-w-lg rounded-2xl border shadow-xl p-6 relative">
            <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800 mb-4">
                <h3 class="text-base font-extrabold uppercase tracking-wider" style="color: var(--text-main);">Detalle
                    de la Cita</h3>
                <button type="button" onclick="document.getElementById('detalleCitaModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="space-y-3 text-xs" id="detalleCitaContent">
                <!-- Renderizado dinámico vía JS -->
            </div>
        </div>
    </div>

    <script>
        function abrirDetalleCita(data) {
            const container = document.getElementById('detalleCitaContent');
            if (!container) return;

            container.innerHTML = `
            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl space-y-1">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Paciente</span>
                <p class="font-bold text-sm text-gray-800 dark:text-gray-200">${data.paciente}</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Estado</span>
                    <p class="font-bold text-gray-700 dark:text-gray-300 uppercase">${data.estado}</p>
                </div>
                <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                    <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Prioridad</span>
                    <p class="font-bold text-gray-700 dark:text-gray-300">${data.prioridad}</p>
                </div>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Fecha y Hora Programada</span>
                <p class="font-bold text-gray-800 dark:text-gray-200">${data.fecha_programada}</p>
            </div>
            <div class="p-3 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Motivo de Atención</span>
                <p class="text-gray-600 dark:text-gray-400 mt-0.5">${data.motivo}</p>
            </div>
        `;

            const modal = document.getElementById('detalleCitaModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function openDailyAgenda(el, date) {
            window.location.href =
                `{{ route('admin.psicologia.maestros.agenda.index') }}?view=week&date=${date}&psicologo_id={{ $psicologoId }}`;
        }

        function dismissCancelMessage(event, citaId) {
            event.stopPropagation();
            const parent = event.target.closest('[onclick="event.stopPropagation()"]');
            if (parent) parent.remove();
        }
    </script>
</x-app-layout>