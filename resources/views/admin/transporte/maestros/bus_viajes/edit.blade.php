@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Editar Asignación de Viaje</h1>
        <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
            Modifique la unidad, la ruta o el chofer asignado para este viaje.
        </p>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="flex flex-wrap -mx-2">
        <div class="w-full md:w-1/2">
            <div class="rd-card p-4"
                style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                <form id="formEditar" action="{{ route('admin.transporte.maestros.bus_viajes.update', $busViaje) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-3">
                        <label class="rd-label">Autobús / Unidad Asignada</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-bus"></i></span>
                            <select name="vehiculo_id" id="vehiculoSelect"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('vehiculo_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar Vehículo --</option>
                                @foreach ($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id }}"
                                        {{ old('vehiculo_id', $busViaje->vehiculo_id) == $vehiculo->id ? 'selected' : '' }}>
                                        {{ $vehiculo->unidad ?? 'Unidad sin nombre' }} (Placa: {{ $vehiculo->placa }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('vehiculo_id')
                            <div class="text-danger font-weight-bold mt-1" style="font-size:0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="rd-label">Ruta de Transporte</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-route"></i></span>
                            <select name="bus_ruta_id" id="rutaSelect"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('bus_ruta_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar Ruta --</option>
                                @foreach ($rutas as $ruta)
                                    <option value="{{ $ruta->id }}" data-distancia="{{ $ruta->distancia_km }}"
                                        data-paradas="{{ $ruta->paradas_count ?? $ruta->paradas->count() }}"
                                        {{ old('bus_ruta_id', $busViaje->bus_ruta_id) == $ruta->id ? 'selected' : '' }}>
                                        {{ $ruta->nombre }} ({{ $ruta->distancia_km }} km)
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bus_ruta_id')
                            <div class="text-danger font-weight-bold mt-1" style="font-size:0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label class="rd-label">Conductor Asignado</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-id-card"></i></span>
                            <select name="conductor_id" id="conductorSelect"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('conductor_id') is-invalid @enderror" required>
                                <option value="">-- Seleccionar Chofer --</option>
                                @foreach ($conductores as $conductor)
                                    <option value="{{ $conductor->id_usuario }}"
                                        {{ old('conductor_id', $busViaje->conductor_id) == $conductor->id_usuario ? 'selected' : '' }}>
                                        {{ $conductor->persona->nombre_persona ?? '' }}
                                        {{ $conductor->persona->apellido_persona ?? '' }} (C.I:
                                        {{ $conductor->persona->cedula ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('conductor_id')
                            <div class="text-danger font-weight-bold mt-1" style="font-size:0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <label class="rd-label">Turno Correspondiente</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-clock"></i></span>
                            <select name="turno" id="turnoSelect"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('turno') is-invalid @enderror" required>
                                <option value="mañana" {{ old('turno', $busViaje->turno) === 'mañana' ? 'selected' : '' }}>
                                    Mañana (06:00 AM - 12:59 AM)</option>
                                <option value="tarde" {{ old('turno', $busViaje->turno) === 'tarde' ? 'selected' : '' }}>
                                    Tarde (01:00 PM - 05:59 PM)</option>
                                <option value="noche" {{ old('turno', $busViaje->turno) === 'noche' ? 'selected' : '' }}>
                                    Noche (06:00 PM - 11:59 PM)</option>
                            </select>
                        </div>
                        @error('turno')
                            <div class="text-danger font-weight-bold mt-1" style="font-size:0.85rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-between mt-4"
                        style="border-top:1px solid #e5e7eb; padding-top:20px;">
                        <a href="{{ route('admin.transporte.maestros.bus_viajes.index') }}"
                            class="rd-btn rd-btn-default">Cancelar</a>
                        <button type="submit" class="rd-btn rd-btn-primary">
                            <i class="fas fa-save mr-1"></i> Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="w-full md:w-1/2 mt-3 mt-md-0">
            <div class="rd-card p-4"
                style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
                <h3 style="font-size:1.1rem;color:#0f172a;font-weight:700;" class="mb-3">
                    <i class="fas fa-info-circle text-primary mr-1"></i> Resumen del Viaje #{{ $busViaje->id }}
                </h3>

                <div class="p-3 mb-3" style="background:#f8fafc; border-radius:10px; border:1px solid #e2e8f0;">
                    <div class="flex justify-between mb-2">
                        <span class="text-muted">Estado Actual:</span>
                        @if($busViaje->estado === 'programado')
                            <span class="rd-badge rd-badge-warning">Programado</span>
                        @elseif($busViaje->estado === 'en_curso')
                            <span class="rd-badge rd-badge-info">En Curso</span>
                        @elseif($busViaje->estado === 'finalizado')
                            <span class="rd-badge rd-badge-success">Finalizado</span>
                        @else
                            <span class="rd-badge rd-badge-secondary">{{ ucfirst($busViaje->estado) }}</span>
                        @endif
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-muted">Identificador Firebase:</span>
                        <code style="color:#0f172a; font-weight:600;">{{ $busViaje->firebase_id ?? 'N/A' }}</code>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-muted">Distancia de la Ruta:</span>
                        <strong id="infoDistancia" style="color:#0f172a;">-- km</strong>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-muted">Cantidad de Paradas:</span>
                        <strong id="infoParadas" style="color:#0f172a;">--</strong>
                    </div>
                </div>

                <div class="alert alert-info border-0 mb-0"
                    style="border-radius:10px; background-color:#eff6ff; color:#1e40af; font-size:0.88rem;">
                    <i class="fas fa-lightbulb mr-1"></i>
                    <strong>Nota:</strong> Los cambios realizados se reflejarán de inmediato en la aplicación del conductor asignado.
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const rutaSelect = document.getElementById('rutaSelect');
            const infoDistancia = document.getElementById('infoDistancia');
            const infoParadas = document.getElementById('infoParadas');

            function actualizarInfoRuta() {
                const selectedOption = rutaSelect.options[rutaSelect.selectedIndex];
                if (selectedOption && selectedOption.value) {
                    const distancia = selectedOption.getAttribute('data-distancia') || '0';
                    const paradas = selectedOption.getAttribute('data-paradas') || '0';
                    infoDistancia.textContent = `${distancia} km`;
                    infoParadas.textContent = `${paradas} paradas`;
                } else {
                    infoDistancia.textContent = '-- km';
                    infoParadas.textContent = '--';
                }
            }

            rutaSelect.addEventListener('change', actualizarInfoRuta);
            actualizarInfoRuta();
        });
    </script>
@endpush