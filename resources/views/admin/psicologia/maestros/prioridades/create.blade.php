<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('error'))
                <div class="p-4 mb-6 text-sm text-rose-800 rounded-2xl bg-rose-50 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800 flex items-center gap-3">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span><strong class="font-black uppercase tracking-wider text-[10px] block mb-0.5">Error</strong>{{ session('error') }}</span>
                </div>
            @endif
            @if ($errors->any())
                <div class="p-4 mb-6 text-sm text-rose-800 rounded-2xl bg-rose-50 dark:bg-rose-900/30 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                    <strong class="font-black uppercase tracking-wider text-[10px] block mb-2">Por favor corrige los siguientes errores:</strong>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-[32px] border border-slate-100 dark:border-gray-700 p-8">
                <div class="mb-8">
                    <!--
                    <div class="w-12 h-12 bg-sky-50 dark:bg-sky-900/30 rounded-2xl flex items-center justify-center mb-4 text-sky-600 dark:text-sky-400">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    </div>
                    -->
                    <h3 class="text-2xl font-black tracking-tight text-slate-800 dark:text-white">Nueva Prioridad</h3>
                    <p class="text-sm text-slate-500 dark:text-gray-400 mt-2">
                        Crea niveles personalizados para clasificar a tus pacientes. 
                        El sistema ya ocupa los niveles <strong>1 (Baja)</strong>, <strong>5 (Media)</strong>, <strong>7 (Alta)</strong> y <strong>10 (Crítica)</strong>.
                    </p>
                    <hr class="text-muted my-4 ">
                </div>

                <form action="{{ route('admin.psicologia.maestros.prioridades.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label for="nombre" class="block text-xs font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nombre de Prioridad</label>
                        <input type="text" name="nombre" id="nombre" class="w-full rounded-2xl border-slate-200 dark:border-gray-600 bg-slate-50 dark:bg-gray-700 px-4 py-3 text-sm focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all text-gray-900 dark:text-white" required placeholder="Ej: Especial, Seguimiento Continuo..." value="{{ old('nombre') }}">
                    </div>
                    <div>
                        <label for="nivel_gravedad" class="block text-xs font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-2">Nivel de Gravedad</label>
                        <select name="nivel_gravedad" id="nivel_gravedad" class="w-full rounded-2xl border-slate-200 dark:border-gray-600 bg-slate-50 dark:bg-gray-700 px-4 py-3 text-sm focus:border-sky-500 focus:ring-4 focus:ring-sky-500/10 transition-all text-gray-900 dark:text-white" required>
                            <option value="">Selecciona un nivel libre...</option>
                            <option value="2" {{ old('nivel_gravedad') == 2 ? 'selected' : '' }}>Nivel 2</option>
                            <option value="3" {{ old('nivel_gravedad') == 3 ? 'selected' : '' }}>Nivel 3</option>
                            <option value="4" {{ old('nivel_gravedad') == 4 ? 'selected' : '' }}>Nivel 4</option>
                            <option value="6" {{ old('nivel_gravedad') == 6 ? 'selected' : '' }}>Nivel 6</option>
                            <option value="8" {{ old('nivel_gravedad') == 8 ? 'selected' : '' }}>Nivel 8</option>
                            <option value="9" {{ old('nivel_gravedad') == 9 ? 'selected' : '' }}>Nivel 9</option>
                        </select>
                        <p class="text-[11px] text-slate-400 mt-2 font-medium flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Solo se muestran los niveles permitidos del 1 al 10 (2, 3, 4, 6, 8, 9).
                        </p>
                    </div>
                    <div class="pt-4 flex justify-end gap-4">
                        <a href="{{ route('admin.psicologia.maestros.prioridades.index') }}" class="px-8 h-14 flex items-center justify-center font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 rounded-2xl transition-colors">
                            Cancelar
                        </a>
                        <button type="submit" class="px-6 h-14 flex items-center justify-center gap-2 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-2xl transition-all shadow-md shadow-sky-200 dark:shadow-none text-base tracking-wide">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            Guardar
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
