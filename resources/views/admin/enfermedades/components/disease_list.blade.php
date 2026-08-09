<div id="disease-list-container"
    style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
    class="rounded-2xl border shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr
                    class="bg-gray-50/50 dark:bg-black/20 border-b border-gray-100 dark:border-gray-800 text-[11px] font-black uppercase tracking-wider text-gray-400">
                    <th class="px-6 py-4">Código CIE-10</th>
                    <th class="px-6 py-4">Diagnóstico / Nombre</th>
                    <th class="px-6 py-4">Categoría</th>
                    <th class="px-6 py-4 text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 dark:divide-gray-800/60 text-xs font-medium">
                @forelse($enfermedades as $enfermedad)
                    <tr class="disease-row hover:bg-gray-50/60 dark:hover:bg-white/[0.02] transition-colors"
                        data-search="{{ strtolower(($enfermedad->codigo ?? '') . ' ' . $enfermedad->nombre . ' ' . $enfermedad->categoria) }}">

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-mono font-bold bg-blue-50 dark:bg-blue-950/40 text-blue-700 dark:text-blue-300 border border-blue-100 dark:border-blue-900/40">
                                {{ $enfermedad->codigo ?: 'S/C' }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="font-bold text-sm" style="color: var(--text-main);">
                                {{ $enfermedad->nombre }}
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider
                                {{ $enfermedad->categoria === 'mental' ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800' : ($enfermedad->categoria === 'biopsicosocial' ? 'bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-800' : 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800') }}">
                                <i
                                    class="fas {{ $enfermedad->categoria === 'mental' ? 'fa-brain' : ($enfermedad->categoria === 'biopsicosocial' ? 'fa-users-between-lines' : 'fa-notes-medical') }} text-[9px]"></i>
                                @if ($enfermedad->categoria === 'mental')
                                    Psiquiátrica
                                @elseif($enfermedad->categoria === 'biopsicosocial')
                                    Biopsicosocial
                                @else
                                    Médica
                                @endif
                            </span>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" onclick="verEnfermedad({{ json_encode($enfermedad) }})"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/50 transition-all"
                                    title="Ver Detalles">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>

                                <a href="{{ route('admin.enfermedades.edit', $enfermedad->id) }}"
                                    class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 transition-all"
                                    title="Editar">
                                    <i class="fas fa-pen-to-square text-sm"></i>
                                </a>

                                <form action="{{ route('admin.enfermedades.destroy', $enfermedad->id) }}" method="POST"
                                    class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/50 transition-all"
                                        onclick="AppModal.confirm('Confirmar eliminación', '¿Deseas eliminar este registro de enfermedad?').then(c => { if(c) this.closest('form').submit(); })"
                                        title="Eliminar">
                                        <i class="fas fa-trash-can text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <i class="fas fa-folder-open text-3xl opacity-40 mb-1"></i>
                                <p class="text-xs font-semibold">No se encontraron registros de enfermedades que
                                    coincidan con el filtro.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse

                <tr id="no-results-disease" class="hidden">
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                        <div class="flex flex-col items-center gap-2">
                            <i class="fas fa-magnifying-glass text-3xl opacity-40 mb-1"></i>
                            <p class="text-xs font-semibold">Sin coincidencias para la búsqueda.</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @if ($enfermedades->hasPages())
        <div id="disease-pagination"
            class="px-6 py-4 flex justify-center border-t border-gray-100 dark:border-gray-800">
            {{ $enfermedades->appends(request()->query())->links('admin.enfermedades.partials.pagination') }}
        </div>
    @endif
</div>
