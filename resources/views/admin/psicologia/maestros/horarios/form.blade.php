@props(['horario' => null, 'dias' => [], 'grupoRetorno' => null])

@php
    $moduloActivo = strtolower(session('modulo_activo', 'general'));
    $esPsicologia = in_array($moduloActivo, ['psicologia', 'psicología', 'mental']);

    $themeColor = $esPsicologia ? 'indigo' : 'red';
    $btnClass = $esPsicologia ? 'bg-indigo-600 hover:bg-indigo-700' : 'bg-red-600 hover:bg-red-700';
    $focusRingClass = $esPsicologia
        ? 'focus:ring-indigo-500/20 focus:border-indigo-500'
        : 'focus:ring-red-500/20 focus:border-red-500';

    $horaInicioHora = old(
        'hora_inicio_hora',
        isset($horario->hora_inicio) ? \Carbon\Carbon::parse($horario->hora_inicio)->format('g') : '',
    );
    $horaInicioMinuto = old(
        'hora_inicio_minuto',
        isset($horario->hora_inicio) ? \Carbon\Carbon::parse($horario->hora_inicio)->format('i') : '00',
    );
    $horaInicioPeriodo = old(
        'hora_inicio_periodo',
        isset($horario->hora_inicio) ? \Carbon\Carbon::parse($horario->hora_inicio)->format('A') : 'AM',
    );

    $horaFinHora = old(
        'hora_fin_hora',
        isset($horario->hora_fin) ? \Carbon\Carbon::parse($horario->hora_fin)->format('g') : '',
    );
    $horaFinMinuto = old(
        'hora_fin_minuto',
        isset($horario->hora_fin) ? \Carbon\Carbon::parse($horario->hora_fin)->format('i') : '00',
    );
    $horaFinPeriodo = old(
        'hora_fin_periodo',
        isset($horario->hora_fin) ? \Carbon\Carbon::parse($horario->hora_fin)->format('A') : 'AM',
    );
@endphp

<form
    action="{{ $horario ? route('admin.psicologia.maestros.horarios.update', $horario->id) : route('admin.psicologia.maestros.horarios.store') }}"
    method="POST">
    @csrf

    @if ($horario)
        @method('PUT')
    @endif

    @if (isset($grupoRetorno) && $grupoRetorno)
        <input type="hidden" name="grupo_id" value="{{ $grupoRetorno }}">
    @endif

    @if ($errors->any())
        <div
            class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800/60 text-xs text-rose-700 dark:text-rose-300">
            <div
                class="flex items-center gap-2 mb-2 font-black uppercase tracking-wider text-[10px] text-rose-800 dark:text-rose-400">
                <i class="fas fa-triangle-exclamation text-sm"></i>
                <span>Por favor corrige los siguientes errores:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 font-medium pl-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="space-y-6">

        @if (isset($horario))
            <input type="hidden" name="dia" value="{{ old('dia', $horario->dia) }}">
            <div
                class="p-3.5 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200/60 dark:border-gray-700/60 flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-gray-500">Día de la semana:</span>
                <span class="text-sm font-extrabold text-gray-800 dark:text-gray-200 uppercase flex items-center gap-2">
                    <i class="fas fa-calendar-day text-{{ $themeColor }}-600 dark:text-{{ $themeColor }}-400"></i>
                    {{ old('dia', $horario->dia) }}
                </span>
            </div>
        @else
            <div>
                <label for="dia" class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                    Día de la Semana <span class="text-red-500">*</span>
                </label>
                <select name="dia" id="dia" required
                    style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                    class="w-full h-11 px-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all">
                    <option value="" disabled {{ old('dia', $horario->dia ?? '') === '' ? 'selected' : '' }}>
                        Selecciona un día</option>
                    @foreach ($dias as $dia)
                        <option value="{{ $dia }}"
                            {{ old('dia', $horario->dia ?? '') === $dia ? 'selected' : '' }}>{{ $dia }}
                        </option>
                    @endforeach
                </select>
                @error('dia')
                    <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                        <i class="fas fa-circle-exclamation"></i> {{ $message }}
                    </p>
                @enderror
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                    Hora Inicio <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-2">
                    <select name="hora_inicio_hora" required
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full h-11 px-3 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all">
                        <option value="" disabled {{ $horaInicioHora === '' ? 'selected' : '' }}>Hora</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"
                                {{ (string) $i === (string) $horaInicioHora ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>

                    <span class="text-gray-400 font-bold">:</span>

                    <select name="hora_inicio_minuto" required
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full h-11 px-3 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all">
                        @for ($i = 0; $i < 60; $i++)
                            @php $minute = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $minute }}" {{ $minute === $horaInicioMinuto ? 'selected' : '' }}>
                                {{ $minute }}</option>
                        @endfor
                    </select>

                    <select name="hora_inicio_periodo" required
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full h-11 px-3 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-bold transition-all">
                        <option value="AM" {{ $horaInicioPeriodo === 'AM' ? 'selected' : '' }}>AM</option>
                        <option value="PM" {{ $horaInicioPeriodo === 'PM' ? 'selected' : '' }}>PM</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                    Hora Fin <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-2">
                    <select name="hora_fin_hora" required
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full h-11 px-3 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all">
                        <option value="" disabled {{ $horaFinHora === '' ? 'selected' : '' }}>Hora</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}"
                                {{ (string) $i === (string) $horaFinHora ? 'selected' : '' }}>{{ $i }}
                            </option>
                        @endfor
                    </select>

                    <span class="text-gray-400 font-bold">:</span>

                    <select name="hora_fin_minuto" required
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full h-11 px-3 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all">
                        @for ($i = 0; $i < 60; $i++)
                            @php $minute = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                            <option value="{{ $minute }}" {{ $minute === $horaFinMinuto ? 'selected' : '' }}>
                                {{ $minute }}</option>
                        @endfor
                    </select>

                    <select name="hora_fin_periodo" required
                        style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                        class="w-full h-11 px-3 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-bold transition-all">
                        <option value="AM" {{ $horaFinPeriodo === 'AM' ? 'selected' : '' }}>AM</option>
                        <option value="PM" {{ $horaFinPeriodo === 'PM' ? 'selected' : '' }}>PM</option>
                    </select>
                </div>
            </div>

        </div>

        <div>
            <label for="descripcion" class="block text-[10px] font-black uppercase tracking-wider text-gray-400 mb-2">
                Descripción (Opcional)
            </label>
            <textarea name="descripcion" id="descripcion" rows="3"
                style="background-color: rgba(0,0,0,0.02); border-color: var(--border-color); color: var(--text-main);"
                class="w-full p-4 border rounded-xl focus:outline-none focus:ring-2 {{ $focusRingClass }} text-sm font-medium transition-all resize-none"
                placeholder="Notas adicionales sobre este bloque de horario...">{{ old('descripcion', $horario->descripcion ?? '') }}</textarea>
            @error('descripcion')
                <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                    <i class="fas fa-circle-exclamation"></i> {{ $message }}
                </p>
            @enderror
        </div>

    </div>

    <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-800/80 flex items-center justify-end gap-3">
        <a href="{{ route('admin.psicologia.maestros.horarios.index', isset($grupoRetorno) && $grupoRetorno ? ['grupo' => $grupoRetorno] : []) }}"
            class="px-5 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-800 text-xs font-bold text-gray-600 dark:text-gray-300 transition-all">
            Cancelar
        </a>
        <button type="submit"
            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl {{ $btnClass }} text-white text-xs font-bold shadow-md active:scale-95 transition-all">
            <i class="fas {{ $horario ? 'fa-floppy-disk' : 'fa-plus' }} text-xs"></i>
            <span>{{ $horario ? 'Actualizar Bloque' : 'Agregar Bloque' }}</span>
        </button>
    </div>
</form>
