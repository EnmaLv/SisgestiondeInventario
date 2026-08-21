<x-app-layout>
    <div class="pt-8 pb-12 min-h-[calc(100vh-4rem)]">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <a href="{{ route('admin.psicologia.maestros.prioridades.index') }}"
                class="inline-flex items-center gap-2 text-xs font-bold text-gray-500 hover:text-{{ $themeColor }}-600 mb-6 transition-colors group">
                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                <span>Volver a Prioridades</span>
            </a>

            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight" style="color: var(--text-main);">
                    Nueva Prioridad
                </h1>
                <p class="mt-1 text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">
                    Crea niveles personalizados para clasificar a tus pacientes del módulo de <strong
                        class="text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400">Salud Mental</strong>.
                </p>
            </div>

            @include('components.alert')

            @if (session('error'))
                <div
                    class="p-4 mb-6 text-sm text-rose-800 rounded-2xl bg-rose-50 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800 flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-rose-600 dark:text-rose-400 text-lg flex-shrink-0"></i>
                    <span><strong
                            class="font-black uppercase tracking-wider text-[10px] block mb-0.5">Error</strong>{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="p-4 mb-6 text-sm text-rose-800 rounded-2xl bg-rose-50 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                    <strong class="font-black uppercase tracking-wider text-[10px] block mb-2">Por favor corrige los
                        siguientes errores:</strong>
                    <ul class="list-disc list-inside space-y-1 text-xs font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div style="background-color: var(--bg-card); border-color: var(--border-color); color: var(--text-main);"
                class="rounded-2xl border shadow-sm overflow-hidden p-6 sm:p-8">
                <form action="{{ route('admin.psicologia.maestros.prioridades.store') }}" method="POST"
                    class="space-y-6">
                    @csrf
                    <div>
                        <label for="nombre"
                            class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                            Nombre de Prioridad <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nombre" id="nombre" value="{{ old('nombre') }}"
                            style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                            class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all"
                            placeholder="Ej: Especial, Seguimiento Continuo..." required>
                        @error('nombre')
                            <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                <i class="fas fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="nivel_gravedad"
                            class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                            Nivel de Gravedad <span class="text-red-500">*</span>
                        </label>
                        <select name="nivel_gravedad" id="nivel_gravedad" required
                            style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                            class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all">
                            <option value="" class="dark:bg-gray-800">Selecciona un nivel libre...</option>
                            <option value="2" {{ old('nivel_gravedad') == 2 ? 'selected' : '' }}
                                class="dark:bg-gray-800">Nivel 2</option>
                            <option value="3" {{ old('nivel_gravedad') == 3 ? 'selected' : '' }}
                                class="dark:bg-gray-800">Nivel 3</option>
                            <option value="4" {{ old('nivel_gravedad') == 4 ? 'selected' : '' }}
                                class="dark:bg-gray-800">Nivel 4</option>
                            <option value="6" {{ old('nivel_gravedad') == 6 ? 'selected' : '' }}
                                class="dark:bg-gray-800">Nivel 6</option>
                            <option value="8" {{ old('nivel_gravedad') == 8 ? 'selected' : '' }}
                                class="dark:bg-gray-800">Nivel 8</option>
                            <option value="9" {{ old('nivel_gravedad') == 9 ? 'selected' : '' }}
                                class="dark:bg-gray-800">Nivel 9</option>
                        </select>
                        @error('nivel_gravedad')
                            <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                                <i class="fas fa-circle-exclamation"></i> {{ $message }}
                            </p>
                        @enderror
                        <p
                            class="mt-2.5 text-xs text-gray-500 dark:text-gray-400 font-medium flex items-center gap-1.5">
                            <i
                                class="fas fa-info-circle text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400"></i>
                            <span>El sistema reserva los niveles <strong>1 (Baja)</strong>, <strong>5 (Media)</strong>,
                                <strong>7 (Alta)</strong> y <strong>10 (Crítica)</strong>. Solo se muestran niveles
                                permitidos.</span>
                        </p>
                    </div>

                    <div
                        class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800/80 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.psicologia.maestros.prioridades.index') }}"
                            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 transition-all">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl {{ $btnClass }} text-white text-xs font-bold shadow-md active:scale-95 transition-all">
                            <i class="fas fa-floppy-disk text-xs"></i>
                            <span>Guardar</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>