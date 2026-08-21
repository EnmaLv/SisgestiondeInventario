<x-app-layout>

    <div class="pt-12 pb-4 bg-slate-50 dark:bg-gray-900 min-h-[calc(100vh-4rem)] flex flex-col">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex-1 flex flex-col w-full">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Mis Pacientes</h1>
                    <p class="mt-2 text-slate-500 dark:text-gray-400 text-sm">Gestiona la información personal de tus pacientes.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <input id="search-input" type="text" placeholder="Buscar pacientes..." class="w-64 pl-10 pr-4 py-2 bg-white dark:bg-gray-800 border border-slate-200 dark:border-gray-700 rounded-2xl text-sm focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none text-gray-900 dark:text-white">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-slate-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-2xl text-emerald-700 dark:text-emerald-400">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Contenedor para contenido AJAX -->
            <div id="pacientes-content" class="flex-1 flex flex-col">
                @include('pacientes.components.indexContent')
            </div>
        </div>
    </div>

    @include('pacientes.partials.modal')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search-input');
            const pacientesContent = document.getElementById('pacientes-content');

            if (searchInput && pacientesContent) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase().trim();
                    const buttons = pacientesContent.querySelectorAll('.open-patient-modal');
                    let visibleCount = 0;

                    buttons.forEach(button => {
                        const nombre = button.getAttribute('data-patient-name');
                        if (nombre && nombre.toLowerCase().includes(query)) {
                            button.closest('button').style.display = 'block';
                            visibleCount++;
                        } else {
                            button.closest('button').style.display = 'none';
                        }
                    });

                    const paginationNav = document.querySelector('nav[aria-label="Pagination Navigation"]');
                    if (paginationNav) {
                        paginationNav.style.display = query !== '' ? 'none' : '';
                    }

                    const existingNoResults = pacientesContent.querySelector('.no-results-message');
                    if (existingNoResults) {
                        existingNoResults.remove();
                    }

                    if (visibleCount === 0 && buttons.length > 0 && query !== '') {
                        const noResultsMsg = document.createElement('div');
                        noResultsMsg.className = 'no-results-message p-8 text-center';
                        noResultsMsg.innerHTML = `
                            <div class="w-16 h-16 bg-slate-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-slate-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Sin coincidencias</h3>
                            <p class="text-slate-500 dark:text-gray-400 text-sm">No se encontraron pacientes que coincidan con tu búsqueda.</p>
                        `;
                        pacientesContent.appendChild(noResultsMsg);
                    }
                });
            }
        });
    </script>
</x-app-layout>
