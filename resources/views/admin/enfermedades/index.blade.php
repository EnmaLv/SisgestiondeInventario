<x-app-layout>
    <div class="pt-8 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                        Catálogo de Enfermedades y Diagnósticos
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Gestión y administración del catálogo para el módulo de <strong
                            class="text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400">{{ $categoriaTexto }}</strong>.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.enfermedades.create', ['tipo' => $tipo, 'return_to' => $returnTo, 'editing' => $editing]) }}"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl {{ $btnClass }} text-white font-bold text-sm shadow-md active:scale-95 transition-all">
                        <i class="fas fa-plus text-xs"></i>
                        <span>Nueva Enfermedad</span>
                    </a>
                </div>
            </div>

            <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="p-4 rounded-2xl border shadow-sm mb-6 flex items-center justify-between gap-4">

                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i id="search-icon-disease" class="fas fa-search text-sm"></i>
                        <div id="search-spinner-disease"
                            class="hidden animate-spin h-4 w-4 border-2 {{ $spinnerColor }} border-t-transparent rounded-full">
                        </div>
                    </div>
                    <input type="text" id="disease-search" value="{{ $search }}"
                        placeholder="Buscar por código CIE-10/DSM-5 o nombre del diagnóstico..."
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm font-medium focus:outline-none focus:ring-2 {{ $focusRingClass }} transition-all">
                </div>
            </div>

            <div id="disease-content">
                @include('admin.enfermedades.components.disease_list')
            </div>

        </div>
    </div>

    <!-- Modal de Detalles -->
    <div id="modalDetalles" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm transition-opacity" onclick="cerrarModal()"></div>

            <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform border shadow-2xl rounded-2xl relative z-10">

                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-800">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-10 h-10 rounded-xl bg-{{ $themeColor }}-50 dark:bg-{{ $themeColor }}-950/50 flex items-center justify-center text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400">
                            <i class="fas {{ $esPsicologia ? 'fa-brain' : 'fa-file-medical' }} text-lg"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold" style="color: var(--text-main);">Detalles del Diagnóstico
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Ficha técnica maestra</p>
                        </div>
                    </div>
                    <button onclick="cerrarModal()"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition-colors">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                </div>

                <div class="py-5 space-y-4">
                    <div>
                        <span class="text-[10px] font-black uppercase tracking-wider text-gray-400">
                            Nombre / Diagnóstico
                        </span>
                        <h4 class="text-lg font-extrabold mt-0.5" id="modalNombre" style="color: var(--text-main);">-
                        </h4>
                    </div>

                    <div class="flex flex-wrap gap-2">
                        <span id="modalCodigo"
                            class="px-3 py-1 rounded-lg text-xs font-mono font-bold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                            CÓDIGO: -
                        </span>
                        <span id="modalCategoria"
                            class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">
                            Categoría
                        </span>
                    </div>

                    <div
                        class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-white/5 space-y-2">
                        <div class="flex justify-between items-center text-xs">
                            <span class="text-gray-500 dark:text-gray-400 font-medium">Nivel de Gravedad (0-5):</span>
                            <span id="modalNivel" class="font-bold" style="color: var(--text-main);">0</span>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 dark:border-gray-800 flex justify-end">
                    <button onclick="cerrarModal()"
                        class="px-5 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-xs font-bold rounded-xl transition-all">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script de Buscador AJAX y Modal -->
    <script>
        const searchInput = document.getElementById('disease-search');
        const searchIcon = document.getElementById('search-icon-disease');
        const searchSpinner = document.getElementById('search-spinner-disease');
        let searchTimeout;

        const performDiseaseFilter = () => {
            const query = searchInput.value.toLowerCase().trim();
            const rows = document.querySelectorAll('.disease-row');
            const noResultsRow = document.getElementById('no-results-disease');
            const pagination = document.getElementById('disease-pagination');
            let visibleCount = 0;

            if (pagination) {
                pagination.classList.toggle('hidden', query.length > 0);
            }

            rows.forEach(row => {
                const searchText = row.getAttribute('data-search');
                if (searchText.includes(query)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noResultsRow) {
                noResultsRow.classList.toggle('hidden', visibleCount > 0 || rows.length === 0);
            }

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                searchIcon?.classList.add('hidden');
                searchSpinner?.classList.remove('hidden');

                fetch(`{{ route('admin.enfermedades.index') }}?search=${encodeURIComponent(query)}&tipo={{ $tipo }}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        document.getElementById('disease-content').innerHTML = html;
                    })
                    .finally(() => {
                        searchIcon?.classList.remove('hidden');
                        searchSpinner?.classList.add('hidden');
                    });
            }, 400);
        };

        searchInput?.addEventListener('input', performDiseaseFilter);

        function verEnfermedad(enfermedad) {
            const modal = document.getElementById('modalDetalles');
            document.getElementById('modalNombre').textContent = enfermedad.nombre;

            const codEl = document.getElementById('modalCodigo');
            codEl.textContent = enfermedad.codigo ? `CÓDIGO: ${enfermedad.codigo}` : 'SIN CÓDIGO';

            const catEl = document.getElementById('modalCategoria');
            const catMap = {
                'mental': 'Psiquiátrica / Mental',
                'fisica': 'Médica / General',
                'biopsicosocial': 'Biopsicosocial'
            };
            const catClasses = {
                'mental': 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800',
                'fisica': 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-800',
                'biopsicosocial': 'bg-teal-50 dark:bg-teal-950/50 text-teal-600 dark:text-teal-400 border border-teal-200 dark:border-teal-800'
            };
            catEl.textContent = catMap[enfermedad.categoria] || 'General';
            catEl.className = 'px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider ' + (catClasses[
                enfermedad.categoria] || 'bg-gray-100 dark:bg-gray-800 text-gray-600');

            document.getElementById('modalNivel').textContent = enfermedad.nivel ?? 0;

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function cerrarModal() {
            document.getElementById('modalDetalles').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') cerrarModal();
        });
    </script>
</x-app-layout>
