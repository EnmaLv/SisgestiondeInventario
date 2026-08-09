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
                    Crear Nuevo Aviso
                </h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                    Publica comunicados e información relevante para los pacientes de <strong
                        class="text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400">Salud Mental</strong>.
                </p>
            </div>

            <!-- Card Formulario -->
            <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                class="rounded-2xl border shadow-sm overflow-hidden p-6 sm:p-8">

                <form action="{{ route('admin.psicologia.maestros.publicaciones.store') }}" method="POST" enctype="multipart/form-data" 
                      x-data="{ tipo: '{{ old('tipo', 'texto') }}', colorFondo: '{{ old('color_fondo', 'bg-indigo-600') }}' }">
                    @csrf

                    <div class="space-y-6">

                        <!-- Selección de Tipo de Publicación -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                Formato del Aviso <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- Texto Normal -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipo" value="texto" x-model="tipo" class="peer sr-only">
                                    <div class="p-3.5 text-center rounded-xl border-2 transition-all flex flex-col items-center justify-center gap-1.5"
                                        :class="tipo === 'texto' 
                                            ? 'border-{{ $themeColor }}-500 bg-{{ $themeColor }}-50/50 text-{{ $themeColor }}-600 dark:bg-{{ $themeColor }}-950/40 dark:text-{{ $themeColor }}-400 font-bold' 
                                            : 'border-gray-100 dark:border-gray-800 text-gray-500 hover:border-gray-200 dark:hover:border-gray-700'">
                                        <i class="fas fa-align-left text-lg mb-0.5"></i>
                                        <span class="text-xs">Texto Normal</span>
                                    </div>
                                </label>

                                <!-- Color de Fondo -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipo" value="color" x-model="tipo" class="peer sr-only">
                                    <div class="p-3.5 text-center rounded-xl border-2 transition-all flex flex-col items-center justify-center gap-1.5"
                                        :class="tipo === 'color' 
                                            ? 'border-{{ $themeColor }}-500 bg-{{ $themeColor }}-50/50 text-{{ $themeColor }}-600 dark:bg-{{ $themeColor }}-950/40 dark:text-{{ $themeColor }}-400 font-bold' 
                                            : 'border-gray-100 dark:border-gray-800 text-gray-500 hover:border-gray-200 dark:hover:border-gray-700'">
                                        <i class="fas fa-palette text-lg mb-0.5"></i>
                                        <span class="text-xs">Fondo de Color</span>
                                    </div>
                                </label>

                                <!-- Imagen -->
                                <label class="cursor-pointer">
                                    <input type="radio" name="tipo" value="imagen" x-model="tipo" class="peer sr-only">
                                    <div class="p-3.5 text-center rounded-xl border-2 transition-all flex flex-col items-center justify-center gap-1.5"
                                        :class="tipo === 'imagen' 
                                            ? 'border-{{ $themeColor }}-500 bg-{{ $themeColor }}-50/50 text-{{ $themeColor }}-600 dark:bg-{{ $themeColor }}-950/40 dark:text-{{ $themeColor }}-400 font-bold' 
                                            : 'border-gray-100 dark:border-gray-800 text-gray-500 hover:border-gray-200 dark:hover:border-gray-700'">
                                        <i class="fas fa-image text-lg mb-0.5"></i>
                                        <span class="text-xs">Subir Imagen</span>
                                    </div>
                                </label>
                            </div>
                            @error('tipo')
                                <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Selector de Paleta de Colores (Condicional) -->
                        <div x-show="tipo === 'color'" x-transition class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 space-y-3">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Selecciona un Color de Fondo
                            </label>
                            <div class="flex flex-wrap gap-3">
                                <template x-for="color in ['bg-indigo-600', 'bg-blue-600', 'bg-teal-600', 'bg-emerald-600', 'bg-rose-600', 'bg-amber-500', 'bg-purple-600', 'bg-slate-800']">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="color_fondo" :value="color" x-model="colorFondo" class="sr-only">
                                        <div :class="[color, colorFondo === color ? 'ring-2 ring-offset-2 ring-indigo-500 dark:ring-offset-gray-900 scale-110' : 'opacity-80 hover:opacity-100']" 
                                             class="w-9 h-9 rounded-xl shadow-sm transition-all transform"></div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Carga de Imagen (Condicional) -->
                        <div x-show="tipo === 'imagen'" x-transition class="p-4 rounded-xl bg-gray-50 dark:bg-black/20 border border-gray-100 dark:border-gray-800 space-y-2">
                            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400">
                                Seleccionar Archivo de Imagen
                            </label>
                            <p class="text-[11px] text-gray-400 font-medium mb-2">Formatos permitidos: JPG, PNG, WEBP. Tamaño máx: 2MB.</p>
                            <input type="file" name="imagen" accept="image/*" 
                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-{{ $themeColor }}-50 file:text-{{ $themeColor }}-600 hover:file:bg-{{ $themeColor }}-100 dark:file:bg-{{ $themeColor }}-950/50 dark:file:text-{{ $themeColor }}-400 transition-all cursor-pointer">
                            @error('imagen')
                                <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Título / Encabezado -->
                        <div>
                            <label for="titulo" class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                Título del Anuncio / Encabezado <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}"
                                style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-bold transition-all"
                                placeholder="Ej: Aviso importante sobre la jornada de consulta..." required>
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
                            <textarea name="contenido" id="contenido" rows="4"
                                style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                class="w-full p-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all"
                                placeholder="Describe el detalle o cuerpo del comunicado aquí...">{{ old('contenido') }}</textarea>
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
                                <option value="todos" {{ old('alcance') === 'todos' ? 'selected' : '' }}>Público (Todos los pacientes en el sistema)</option>
                                <option value="mis_pacientes" {{ old('alcance') === 'mis_pacientes' ? 'selected' : '' }}>Segmentado (Solo mis pacientes asignados)</option>
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
                            <i class="fas fa-paper-plane text-xs"></i>
                            <span>Publicar Aviso</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>