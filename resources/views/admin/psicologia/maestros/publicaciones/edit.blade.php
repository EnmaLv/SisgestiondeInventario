<x-app-layout>
    @php
        $moduloActivo = strtolower(session('modulo_activo', 'general'));
        $esPsicologia = in_array($moduloActivo, ['psicologia', 'psicología', 'mental']);
        $themeColor = $esPsicologia ? 'indigo' : 'blue';
        $btnClass = $esPsicologia ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-blue-600 hover:bg-blue-700';
        $focusRingClass = $esPsicologia
            ? 'focus:ring-indigo-500/20 focus:border-indigo-500'
            : 'focus:ring-blue-500/20 focus:border-blue-500';
    @endphp

    <div class="pt-8 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Botón Volver -->
            <a href="{{ route('admin.psicologia.maestros.publicaciones.index') }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-{{ $themeColor }}-600 mb-6 transition-colors group">
                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                <span>Volver a avisos y comunicados</span>
            </a>

            <!-- Cabecera -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                    Editar Aviso
                </h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                    Modificación de la información del aviso publicado.
                </p>
            </div>

            <!-- Card Formulario -->
            <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                class="rounded-2xl border shadow-sm overflow-hidden p-6 sm:p-8">

                <form action="{{ route('admin.psicologia.maestros.publicaciones.update', $publicacion->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="space-y-6">

                        <!-- Título / Encabezado -->
                        <div>
                            <label for="titulo" class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                Título del Anuncio / Encabezado <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $publicacion->titulo) }}"
                                style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-bold transition-all"
                                required>
                            @error('titulo')
                                <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Contenido Extendido -->
                        <div>
                            <label for="contenido" class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                Contenido Detallado (Opcional)
                            </label>
                            <textarea name="contenido" id="contenido" rows="5"
                                style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                class="w-full p-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all"
                                placeholder="Escribe el texto detallado del comunicado...">{{ old('contenido', $publicacion->contenido) }}</textarea>
                            @error('contenido')
                                <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Alcance / Visibilidad -->
                        <div>
                            <label for="alcance" class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                Alcance y Visibilidad <span class="text-red-500">*</span>
                            </label>
                            <select name="alcance" id="alcance"
                                style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all">
                                <option value="todos" {{ old('alcance', $publicacion->alcance) === 'todos' ? 'selected' : '' }}>
                                    Público (Todos los pacientes en el sistema)
                                </option>
                                <option value="mis_pacientes" {{ old('alcance', $publicacion->alcance) === 'mis_pacientes' ? 'selected' : '' }}>
                                    Segmentado (Solo mis pacientes asignados)
                                </option>
                            </select>
                            @error('alcance')
                                <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <!-- Pie de Formulario -->
                    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.psicologia.maestros.publicaciones.index') }}"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl {{ $btnClass }} text-white text-xs font-bold shadow-md active:scale-95 transition-all">
                            <i class="fas fa-floppy-disk text-xs"></i>
                            <span>Guardar Cambios</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>