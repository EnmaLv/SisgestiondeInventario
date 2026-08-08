@php
    $esPsicologia = $categoria === 'mental' || in_array($tipo, ['mental', 'psicologia']);
    $themeColor = $esPsicologia ? 'indigo' : 'blue';
    $categoriaTexto = match ($categoria) {
        'mental' => 'Psiquiátrica / Salud Mental',
        'biopsicosocial' => 'Biopsicosocial',
        default => 'Médica / Salud General',
    };
    $categoriaBadgeClass = $esPsicologia
        ? 'bg-indigo-50 dark:bg-indigo-950/50 text-indigo-600 dark:text-indigo-400 border-indigo-200 dark:border-indigo-800'
        : 'bg-blue-50 dark:bg-blue-950/50 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-800';
    $btnClass = $esPsicologia
        ? 'bg-indigo-600 hover:bg-indigo-700 shadow-indigo-500/20'
        : 'bg-blue-600 hover:bg-blue-700 shadow-blue-500/20';
    $focusRingClass = $esPsicologia
        ? 'focus:ring-indigo-500/30 focus:border-indigo-500'
        : 'focus:ring-blue-500/30 focus:border-blue-500';
@endphp

<x-app-layout>
    <div class="pt-8 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Botón Volver -->
            <a href="{{ route('admin.enfermedades.index', ['tipo' => $tipo, 'return_to' => $returnTo, 'editing' => $editing]) }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-{{ $themeColor }}-600 mb-6 transition-colors group">
                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                <span>Volver al catálogo</span>
            </a>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                    Editar Enfermedad
                </h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                    Modificación de registro en el módulo de <strong
                        class="text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400">{{ $categoriaTexto }}</strong>.
                </p>
            </div>

            <!-- Card Form -->
            <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                class="rounded-2xl border shadow-sm overflow-hidden p-6 sm:p-8">

                <form action="{{ route('admin.enfermedades.update', $enfermedad->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="tipo_contexto" value="{{ $tipo }}">
                    <input type="hidden" name="return_to" value="{{ $returnTo }}">
                    <input type="hidden" name="editing" value="{{ $editing }}">

                    <div class="space-y-6">

                        <!-- Categoría Asignada (Informativa/Fija) -->
                        <div>
                            <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                Categoría Asignada
                            </label>
                            <div
                                class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl text-xs font-bold border {{ $categoriaBadgeClass }}">
                                <i class="fas {{ $esPsicologia ? 'fa-brain' : 'fa-notes-medical' }}"></i>
                                <span>{{ $categoriaTexto }}</span>
                            </div>
                        </div>

                        <!-- Nombre -->
                        <div>
                            <label for="nombre"
                                class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                Nombre del Diagnóstico / Enfermedad <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre" id="nombre"
                                value="{{ old('nombre', $enfermedad->nombre) }}"
                                style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all"
                                placeholder="Ej: Trastorno de Ansiedad Generalizada..." required>
                            @error('nombre')
                                <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <!-- Código CIE-10 / DSM-5 -->
                            <div>
                                <label for="codigo"
                                    class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                    Código CIE-10 / DSM-5 (Opcional)
                                </label>
                                <input type="text" name="codigo" id="codigo"
                                    value="{{ old('codigo', $enfermedad->codigo) }}"
                                    style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                    class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-mono font-medium transition-all"
                                    placeholder="Ej: F41.1, E11, A00...">
                                @error('codigo')
                                    <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Nivel de Gravedad (0 - 5) -->
                            <div>
                                <label for="nivel"
                                    class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                                    Nivel de Gravedad (0: Leve - 5: Muy Grave)
                                </label>
                                <input type="number" name="nivel" id="nivel" min="0" max="5"
                                    value="{{ old('nivel', $enfermedad->nivel ?? 0) }}"
                                    oninput="if(this.value > 5) this.value = 5; if(this.value < 0) this.value = 0; if(this.value.length > 1) this.value = this.value.slice(0, 1);"
                                    style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                                    class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all"
                                    placeholder="0">
                                @error('nivel')
                                    <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <!-- Botones de Acción -->
                    <div
                        class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.enfermedades.index', ['tipo' => $tipo, 'return_to' => $returnTo, 'editing' => $editing]) }}"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl {{ $btnClass }} text-white text-xs font-bold shadow-md active:scale-95 transition-all">
                            <i class="fas fa-arrows-rotate text-xs"></i>
                            <span>Actualizar Registro</span>
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
