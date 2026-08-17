<x-app-layout>
    <div class="pt-6 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            @include('components.alert')

            {{-- Alertas --}}
            @if (session('exito') || session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Operación exitosa!',
                                text: "{{ session('exito') ?? session('success') }}",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3500,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'rounded-2xl shadow-xl'
                                }
                            });
                        }
                    }); 
                </script>
            @endif
            @if (session('error') || session('danger'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: '¡Error!',
                                text: "{{ session('error') ?? session('danger') }}",
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3500,
                                timerProgressBar: true,
                                customClass: {
                                    popup: 'rounded-2xl shadow-xl'
                                }
                            });
                        }
                    });
                </script>
            @endif

            {{-- Encabezado de la página --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                        Categorías
                    </h1>
                    <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                        Bienvenido <span class="font-bold">{{ auth()->user()->persona->nombre_persona }}</span> ·
                        {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button type="button" onclick="abrirModalCrearCategoria()"
                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl {{ $btnClass ?? 'bg-red-800 hover:bg-red-900' }} text-white font-bold text-sm shadow-md active:scale-95 transition-all">
                        <i class="fas fa-plus"></i> Nueva Categoría
                    </button>
                </div>
            </div>

            {{-- Card de Buscador + Estado --}}
            <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="p-2.5 rounded-2xl border shadow-sm mb-3 flex flex-col lg:flex-row lg:items-center gap-4">

                <form action="{{ route('admin.maestros.categorias.index') }}" method="GET" class="relative w-full">
                    <input type="hidden" name="activo" value="{{ request('activo', 1) }}">

                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-search text-sm"></i>
                    </div>
                    <input type="text" name="buscar" value="{{ request('buscar') }}"
                        placeholder="Buscar categoría..."
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm font-medium focus:outline-none focus:ring-2 {{ $focusRingClass ?? 'focus:ring-red-500' }} transition-all">
                </form>

                <div class="flex items-center gap-3 shrink-0">
                    {{-- Toggle estado --}}
                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl border"
                        style="border-color: var(--border-color);">
                        <span class="text-[11px] font-black uppercase tracking-wider text-gray-400">Activos</span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" id="estadoToggle" class="sr-only peer"
                                {{ request('activo', 1) == 1 ? 'checked' : '' }}>
                            <div
                                class="w-10 h-6 bg-gray-200 dark:bg-gray-700 rounded-full peer peer-checked:bg-red-700 transition-colors relative after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-4">
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- Card de la Tabla --}}
            <div style="background-color: var(--bg-card); border-color: var(--border-color);"
                class="rounded-2xl border shadow-sm overflow-hidden">

                <div class="overflow-x-auto" id="printArea">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/50 dark:bg-black/20 border-b border-gray-100 dark:border-gray-800 text-[13px] font-black uppercase tracking-wider">
                                <th class="px-6 py-4 text-center" style="width: 80px;">#</th>
                                <th class="px-6 py-4 text-center">Nombre</th>
                                <th class="px-6 py-4 text-center">Descripción</th>
                                <th class="px-6 py-4 text-center" style="width: 160px;">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs font-medium">
                            @forelse($categorias as $categoria)
                                <tr class="fila-categoria cursor-pointer hover:bg-gray-50/60 dark:hover:bg-white/[0.02] transition-colors border-b border-gray-100 dark:border-gray-800/60"
                                    onclick="toggleAcciones(event, {{ $categoria->id }})"
                                    title="Click para ver acciones">

                                    {{-- Numeración --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-3 py-1 text-[12px] font-black rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                            {{ ($categorias->currentPage() - 1) * $categorias->perPage() + $loop->iteration }}
                                        </span>
                                    </td>

                                    {{-- Nombre --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap font-bold"
                                        style="color: var(--text-main);">
                                        {{ $categoria->nombre }}
                                    </td>

                                    {{-- Descripción --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap font-bold"
                                        style="color: var(--text-main);">
                                        {{ $categoria->descripcion ?? '-' }}
                                    </td>

                                    {{-- Estado --}}
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if ($categoria->activo)
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 text-[10px] font-black rounded-lg bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-900">
                                                <i class="fas fa-check-circle"></i> Activo
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center gap-1 px-3 py-1 text-[10px] font-black rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900">
                                                <i class="fas fa-times-circle"></i> Inactivo
                                            </span>
                                        @endif
                                    </td>
                                </tr>

                                {{-- Franja de acciones: se despliega debajo de la fila al hacer click --}}
                                <tr class="fila-acciones border-b border-gray-100 dark:border-gray-800/60">
                                    <td colspan="4" class="p-0">
                                        <div id="panel-{{ $categoria->id }}" class="acciones-panel">
                                            <div
                                                class="flex items-center justify-center gap-6 py-3 bg-sky-50/50 dark:bg-sky-950/10">

                                                {{-- Editar --}}
                                                <button type="button"
                                                    class="inline-flex items-center gap-2 text-gray-500 hover:text-yellow-500 font-bold text-xs transition-colors"
                                                    onclick="abrirModalEditarCategoria({{ json_encode($categoria) }}, '{{ route('admin.maestros.categorias.update', $categoria->id) }}')">
                                                    <i class="fas fa-edit"></i> Editar
                                                </button>

                                                @if ($categoria->activo == true)
                                                    <form id="form-toggle-{{ $categoria->id }}"
                                                        action="{{ url('admin/maestros/categorias/' . $categoria->id) }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                            onclick="confirmToggleEstado({{ $categoria->id }}, 'inactivar')"
                                                            class="inline-flex items-center gap-2 text-gray-500 hover:text-rose-500 font-bold text-xs transition-colors">
                                                            <i class="fas fa-trash-alt"></i> Inactivar
                                                        </button>
                                                    </form>
                                                @else
                                                    <form id="form-toggle-{{ $categoria->id }}"
                                                        action="{{ url('admin/maestros/categorias/' . $categoria->id . '/activar') }}"
                                                        method="POST" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <button type="button"
                                                            onclick="confirmToggleEstado({{ $categoria->id }}, 'activar')"
                                                            class="inline-flex items-center gap-2 text-gray-500 hover:text-emerald-500 font-bold text-xs transition-colors">
                                                            <i class="fas fa-check"></i> Activar
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-12 text-center text-gray-400 dark:text-gray-500 font-bold text-xs uppercase tracking-wider">
                                        <i class="fas fa-tags text-3xl mb-3 block text-gray-300 dark:text-gray-700"></i>
                                        No hay categorías registradas
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($categorias->hasPages())
                    <div class="p-4 border-t border-gray-100 dark:border-gray-800 flex justify-center">
                        {{ $categorias->onEachSide(1)->appends(request()->query())->links('components.pagination') }}
                    </div>
                @endif
            </div>

        </div>
    </div>

    {{-- Componente de Modal para crear y editar categoría --}}
    <x-categoria-modal :store-route="route('admin.maestros.categorias.store')" 
    :tipo-producto-id="1"/>

    <style>
        /* Franja de acciones colapsable debajo de cada fila */
        .acciones-panel {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height .25s ease, opacity .2s ease;
        }

        .fila-categoria.is-open+.fila-acciones .acciones-panel {
            max-height: 60px;
            opacity: 1;
        }

        .fila-categoria.is-open {
            background-color: rgba(2, 132, 199, 0.05);
        }
    </style>

    <script>
        let filaAccionesAbierta = null;

        function toggleAcciones(event, id) {
            const filaClickeada = event.currentTarget;
            const yaEstabaAbierta = filaClickeada.classList.contains('is-open');

            if (filaAccionesAbierta && filaAccionesAbierta !== filaClickeada) {
                filaAccionesAbierta.classList.remove('is-open');
            }

            if (yaEstabaAbierta) {
                filaClickeada.classList.remove('is-open');
                filaAccionesAbierta = null;
            } else {
                filaClickeada.classList.add('is-open');
                filaAccionesAbierta = filaClickeada;
            }
        }

        // Cierra la franja de acciones abierta si se hace click fuera de la tabla
        document.addEventListener('click', function(event) {
            if (filaAccionesAbierta && !event.target.closest('#printArea')) {
                filaAccionesAbierta.classList.remove('is-open');
                filaAccionesAbierta = null;
            }
        });

        // Toggle de estado (activo/inactivo)
        document.getElementById('estadoToggle').addEventListener('change', function() {
            const params = new URLSearchParams(window.location.search);
            params.set('activo', this.checked ? 1 : 0);
            window.location.href = "{{ route('admin.maestros.categorias.index') }}?" + params.toString();
        });

        // Confirmación unificada para activar/inactivar
        function confirmToggleEstado(id, action) {
            const isActivate = action === 'activar';
            const title = isActivate ? '¿Activar categoría?' : '¿Inactivar categoría?';
            const text = isActivate ?
                'La categoría volverá a estar disponible en el sistema.' :
                'La categoría dejará de estar disponible en el sistema.';

            window.AppModal.show(title, text, {
                type: 'confirm',
                btnText: isActivate ? 'Sí, activar' : 'Sí, inactivar',
                intent: isActivate ? 'success' : 'danger'
            }).then(result => {
                if (result) document.getElementById('form-toggle-' + id).submit();
            });
        }
    </script>
</x-app-layout>
