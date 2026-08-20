@if ($pacientes->isEmpty())
    <div class="bg-white dark:bg-gray-800 rounded-[32px] border-2 border-dashed border-slate-200 dark:border-gray-700 p-16 text-center shadow-sm">
        <div class="w-20 h-20 bg-slate-50 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-slate-300 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">Sin pacientes disponibles</h3>
        <p class="text-slate-500 dark:text-gray-400 max-w-sm mx-auto leading-relaxed">
            Aquí aparecerán los pacientes registrados en el sistema una vez que completen su primera cita.
        </p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-1 gap-4 mb-4">
        @foreach ($pacientes as $paciente)
            @php
                $perfil = $paciente;
                $photoPath = $paciente->profile_photo_path ?? null;
                $hasPhoto = !empty($photoPath);
                $nombreCompleto = $paciente->name ?? '';
                $partes = explode(' ', trim($nombreCompleto));
                $primerNombre = $partes[0] ?? '';
                $primerApellido = $partes[1] ?? '';
                $iniciales = strtoupper(substr($primerNombre, 0, 1) . substr($primerApellido, 0, 1));
                $fechaCita = $paciente->primera_cita ? \Carbon\Carbon::parse($paciente->primera_cita)->format('d/m/Y') : 'No disponible';
                $edad = $perfil->fecha_nacimiento ? \Carbon\Carbon::parse($perfil->fecha_nacimiento)->age : 'No disponible';
            @endphp
            <button type="button" class="open-patient-modal w-full p-4 bg-white dark:bg-gray-800 rounded-2xl border border-slate-100 dark:border-gray-700 shadow-sm hover:shadow-md hover:bg-slate-50 dark:hover:bg-gray-700 transition-all cursor-pointer text-left"
                data-patient-type="user"
                data-patient-name="{{ $paciente->name }}"
                data-patient-email="{{ $paciente->email ?? 'No disponible' }}"
                data-patient-phone="{{ $paciente->telefono ?? 'No disponible' }}"
                data-patient-created="{{ $fechaCita }}"
                data-patient-cedula="{{ $perfil->cedula ?? 'No disponible' }}"
                data-patient-genero="{{ $perfil->genero ?? 'No disponible' }}"
                data-patient-nacimiento="{{ $perfil->fecha_nacimiento ? \Carbon\Carbon::parse($perfil->fecha_nacimiento)->format('d/m/Y') : 'No disponible' }}"
                data-patient-ubicacion="{{ $perfil->ubicacion ?? 'No disponible' }}"
                data-patient-discapacidad="{{ ($perfil->discapacidad ?? 'No') == 'Si' ? $perfil->tipo_discapacidad : 'Ninguna' }}"
                data-patient-hijos="{{ ($perfil->tiene_hijos ?? 'No') == 'Si' ? $perfil->numero_hijos : 'Ninguno' }}"
                data-patient-civil="{{ $perfil->estado_civil ?? 'No disponible' }}"
                data-patient-perfil-academico="{{ $perfil->perfil_academico ?? 'Sin definir' }}"
                data-patient-pnf="{{ $perfil->pnf ?? 'No aplica' }}"
                data-patient-semestre="{{ $perfil->semestre ? $perfil->semestre . '° Semestre' : 'No aplica' }}"
                data-patient-edad="{{ $edad }}"
                data-patient-photo="{{ $hasPhoto ? route('media.profile_photos', basename($photoPath)) : '' }}"
            >
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full overflow-hidden bg-gradient-to-br from-sky-600 to-indigo-700 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-sky-200 dark:shadow-sky-900/30">
                        @if($hasPhoto)
                            <img src="{{ route('media.profile_photos', basename($photoPath)) }}" alt="{{ $nombreCompleto }}" class="w-full h-full object-cover">
                        @else
                            {{ $iniciales }}
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-lg font-semibold text-slate-800 dark:text-white">{{ $paciente->name ?? 'Paciente' }}</p>
                    </div>
                    <div class="flex items-center text-slate-400 dark:text-gray-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
            </button>
        @endforeach
    </div>

    <div class="mt-auto flex justify-center pb-2 pt-12">
        {{ $pacientes->appends(['buscar' => $buscar])->links('pacientes.partials.pagination') }}
    </div>
@endif
