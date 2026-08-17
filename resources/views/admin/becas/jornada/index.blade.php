<x-app-layout>

    <div class="pt-8 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- CABECERA UNIFICADA --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                        Jornadas de Becas
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ url('admin/becas/jornada/create') }}"
                        class="inline-flex shrink-0 whitespace-nowrap items-center px-5 py-2.5 bg-red-700 hover:bg-red-600 text-white text-sm font-bold rounded-2xl transition-all shadow-md shadow-red-100 dark:shadow-red-900/30">
                        <i class="fas fa-plus text-xs mr-2"></i>
                        <span>Crear Nueva Jornada</span>
                    </a>
                </div>
            </div>

            {{-- CONTENEDOR PRINCIPAL CON EL ESTILO UNIFICADO DE MEDICINA/PSICOLOGIA --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-3xl border-l-8 border-red-700 overflow-hidden">
                <div class="p-8 text-gray-900 dark:text-gray-100">

                    <div
                        class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b pb-6 dark:border-gray-700">
                        <h3
                            class="text-xl font-black text-slate-800 dark:text-white uppercase tracking-tight text-left">
                            Jornadas Registradas
                        </h3>
                        <div class="rd-actions">
                            <form action="{{ url('admin/becas/jornada') }}" method="GET" class="flex items-center gap-2"
                                role="search">
                                <input type="hidden" name="activa" value="{{ request('activa', 1) }}">
                                <input type="text" name="buscar" value="{{ request('buscar') }}"
                                    class="w-64 bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm px-4 py-2 rounded-xl border border-gray-200 dark:border-gray-600 focus:ring-2 focus:ring-red-500/20 focus:border-red-500 transition-all outline-none"
                                    placeholder="Escriba la jornada" />
                                <button
                                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-600 dark:text-gray-300 transition-all border border-gray-200 dark:border-gray-600"
                                    type="submit" title="Buscar">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div
                        class="overflow-x-auto rounded-[24px] border border-slate-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
                        <table class="min-w-full divide-y divide-slate-100 dark:divide-gray-700 text-sm">
                            <thead class="bg-slate-50/50 dark:bg-gray-700/30">
                                <tr>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                                        #</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                                        Nombre</th>
                                    <th
                                        class="px-6 py-4 text-left text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                                        Beneficio</th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                                        Fechas Solicitud</th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                                        Cupos</th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                                        Estado</th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black text-slate-400 dark:text-slate-400 uppercase tracking-widest">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-slate-50 dark:divide-gray-700 text-slate-700 dark:text-slate-300">
                                @forelse($jornadas ?? [] as $jornada)
                                    @php
                                        $expirada = $jornada->fecha_fin_solicitud && $jornada->fecha_fin_solicitud->isPast();
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-gray-700/20 transition-all">
                                        <td class="px-6 py-4 text-center font-semibold text-slate-700 dark:text-slate-350">
                                            {{ (isset($jornadas) && method_exists($jornadas, 'currentPage')) ? ($jornadas->currentPage() - 1) * $jornadas->perPage() + $loop->iteration : $loop->iteration }}
                                        </td>
                                        <td class="px-6 py-4 font-bold text-slate-800 dark:text-white">
                                            {{ $jornada->nombre_jornada }}
                                        </td>
                                        <td class="px-6 py-4 font-medium text-slate-700 dark:text-slate-200">
                                            {{ $jornada->beneficio->nombre_beneficio ?? 'N/A' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                            {{ $jornada->fecha_inicio_solicitud ? $jornada->fecha_inicio_solicitud->format('d/m/Y') : '' }}
                                            -
                                            {{ $jornada->fecha_fin_solicitud ? $jornada->fecha_fin_solicitud->format('d/m/Y') : '' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center font-bold text-slate-800 dark:text-slate-200 whitespace-nowrap">
                                            {{ $jornada->cupos_asignados }} / {{ $jornada->cupos_maximos }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if ($jornada->activa)
                                                <span
                                                    class="text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest border bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-800">Activo</span>
                                            @elseif($expirada)
                                                <span
                                                    class="text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest border bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 border-amber-200 dark:border-amber-800">Finalizado</span>
                                            @else
                                                <span
                                                    class="text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest border bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 border-rose-200 dark:border-rose-800">Inactivo</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center gap-2">
                                                <a href="{{ url('admin/becas/jornada/' . $jornada->id . '/edit') }}"
                                                    class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-slate-100 dark:bg-gray-700 dark:hover:bg-gray-600 border border-slate-200 dark:border-gray-600 flex items-center justify-center text-slate-500 dark:text-gray-300 transition-all"
                                                    title="{{ $expirada ? 'Ver Detalles' : 'Editar' }}">
                                                    @if($expirada)
                                                        <i class="fas fa-eye"></i>
                                                    @else
                                                        <i class="fas fa-edit"></i>
                                                    @endif
                                                </a>
                                                @if (!$expirada)
                                                    @if ($jornada->activa == true)
                                                        <form action="{{ url('admin/becas/jornada/' . $jornada->id) }}"
                                                            method="POST" class="form-delete inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="w-8 h-8 rounded-lg bg-rose-50 hover:bg-rose-100 dark:bg-rose-950/20 dark:hover:bg-rose-900/30 border border-rose-200 dark:border-rose-900 flex items-center justify-center text-rose-600 dark:text-rose-450 transition-all"
                                                                onclick="confirmDelete(event, this)">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                        <script>
                                                            function confirmDelete(event, button) {
                                                                event.preventDefault();
                                                                AppModal.show('¿Estás seguro?', 'Desea inactivar la jornada?', {
                                                                    type: 'confirm',
                                                                    icon: 'warning',
                                                                    confirmText: 'Sí, inactivar',
                                                                    cancelText: 'Cancelar'
                                                                }).then((confirmed) => {
                                                                    if (confirmed) {
                                                                        button.closest('form').submit();
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    @else
                                                        <form action="{{ url('admin/becas/jornada/' . $jornada->id . '/activar') }}"
                                                            method="POST" class="form-delete inline">
                                                            @csrf
                                                            @method('PUT')
                                                            <button type="submit"
                                                                class="w-8 h-8 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/20 dark:hover:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-900 flex items-center justify-center text-emerald-600 dark:text-emerald-450 transition-all"
                                                                onclick="confirmActivate(event, this)">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>

                                                        <script>
                                                            function confirmActivate(event, button) {
                                                                event.preventDefault();
                                                                AppModal.show('¿Estás seguro?', 'Desea activar la jornada?', {
                                                                    type: 'confirm',
                                                                    icon: 'warning',
                                                                    confirmText: 'Sí, activar',
                                                                    cancelText: 'Cancelar'
                                                                }).then((confirmed) => {
                                                                    if (confirmed) {
                                                                        button.closest('form').submit();
                                                                    }
                                                                });
                                                            }
                                                        </script>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-10 text-center text-slate-400 dark:text-gray-500 italic">No hay
                                            jornadas registradas</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if(isset($jornadas) && method_exists($jornadas, 'onEachSide'))
                        <div class="mt-6 flex justify-center">
                            {{ $jornadas->onEachSide(1)->links('components.pagination') }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>