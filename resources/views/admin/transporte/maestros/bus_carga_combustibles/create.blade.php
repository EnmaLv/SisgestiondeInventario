@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Registrar Carga de Combustible</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div class="flex items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600;font-size:0.95rem;">{{ \Carbon\Carbon::now()->format('d/m/Y') }}</span>
            </div>
            <div style="width:46px;height:46px;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(15,23,42,0.08);">
                <img src="{{ asset('img/usuario-verificado.webp') }}" alt="Usuario" style="width:100%;height:100%;object-fit:cover;">
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="rd-card p-4">
        <div class="rd-card-header mb-3">
            <h3 class="rd-title-sm">Datos de la Recarga</h3>
            <a href="{{ route('admin.transporte.maestros.bus_carga_combustibles.index') }}" class="rd-btn rd-btn-default">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <form action="{{ route('admin.transporte.maestros.bus_carga_combustibles.store') }}" method="POST"
            class="rd-prevent-double-submit">
            @csrf

            {{-- Fila 1: Vehículo, Viaje, Tipo Combustible --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Vehículo</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-bus"></i></span>
                            <select name="bus_vehiculo_id" id="selectVehiculo"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('bus_vehiculo_id') is-invalid @enderror">
                                <option value="">-- Seleccione Vehículo --</option>
                                @foreach($vehiculos as $v)
                                    <option value="{{ $v->id }}" {{ old('bus_vehiculo_id') == $v->id ? 'selected' : '' }}
                                        data-combustible="{{ $v->bus_tipo_combustible_id }}"
                                        data-km="{{ $v->km_actual }}"
                                        data-bocas="{{ $v->cantidad_bocas }}">
                                        {{ $v->placa }} — {{ $v->modelo->nombre ?? '' }} (KM: {{ number_format($v->km_actual, 0) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bus_vehiculo_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>

                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Viaje Asociado</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-route"></i></span>
                            <select name="bus_viaje_id"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('bus_viaje_id') is-invalid @enderror">
                                <option value="">-- Seleccione Viaje --</option>
                                @foreach($viajes as $viaje)
                                    <option value="{{ $viaje->id }}" {{ old('bus_viaje_id') == $viaje->id ? 'selected' : '' }}>
                                        #{{ $viaje->id }} | {{ $viaje->vehiculo->placa ?? '' }} - {{ $viaje->ruta->nombre ?? '' }} ({{ $viaje->fecha_inicio ? $viaje->fecha_inicio->format('d/m/Y') : 'Prog.' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bus_viaje_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>

                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo de Combustible</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-gas-pump"></i></span>
                            <select name="bus_tipo_combustible_id" id="selectTipoCombustible"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('bus_tipo_combustible_id') is-invalid @enderror">
                                <option value="">-- Seleccione Tipo --</option>
                                @foreach($tipos as $tipo)
                                    <option value="{{ $tipo->id }}" {{ old('bus_tipo_combustible_id') == $tipo->id ? 'selected' : '' }}>
                                        {{ $tipo->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bus_tipo_combustible_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 2: Fecha, Boca, KM al Cargar --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha de Carga</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="fecha"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('fecha') is-invalid @enderror"
                                value="{{ old('fecha', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}">
                        </div>
                        @error('fecha') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>

                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Boca / Tanque #</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-plug"></i></span>
                            <input type="number" name="boca_numero" id="inputBocaNumero"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('boca_numero') is-invalid @enderror"
                                placeholder="Ej: 1" value="{{ old('boca_numero', 1) }}" min="1" max="10">
                        </div>
                        @error('boca_numero') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>

                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">KM al Cargar (Odómetro)</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-tachometer-alt"></i></span>
                            <input type="number" name="km_al_cargar" id="inputKmCargar" step="0.01"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('km_al_cargar') is-invalid @enderror"
                                placeholder="Ej: 52400.00" value="{{ old('km_al_cargar') }}" min="0" max="9999999"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,10)">
                        </div>
                        @error('km_al_cargar') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 3: Litros, Precio/L, Total Calculado --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Litros Cargados</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-fill-drip"></i></span>
                            <input type="number" name="litros" id="inputLitros" step="0.01"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('litros') is-invalid @enderror"
                                placeholder="Ej: 80.50" value="{{ old('litros') }}" min="0.1" max="1000"
                                oninput="calcularTotalCombustible()">
                        </div>
                        @error('litros') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>

                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Precio por Litro ($)</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" name="precio_litros" id="inputPrecio" step="0.01"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('precio_litros') is-invalid @enderror"
                                placeholder="Ej: 0.50" value="{{ old('precio_litros', 0.50) }}" min="0.01" max="999999"
                                oninput="calcularTotalCombustible()">
                        </div>
                        @error('precio_litros') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>

                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Total a Pagar ($) <span class="text-muted font-weight-normal">(auto)</span></label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-receipt"></i></span>
                            <input type="number" id="inputTotal" step="0.01"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                value="{{ old('total', 0) }}" readonly
                                style="background:#f8fafc;cursor:not-allowed;font-weight:700;color:var(--color-primary);">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fila 4: Observaciones --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full">
                    <div class="form-group">
                        <label class="font-weight-bold">Observaciones <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <textarea name="observaciones" rows="3"
                            class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('observaciones') is-invalid @enderror"
                            placeholder="Estación de servicio, número de factura o ticket..."
                            maxlength="2000"
                            oninput="this.value=this.value.slice(0,2000); document.getElementById('contadorObs').textContent=this.value.length"
                            style="resize:none;">{{ old('observaciones') }}</textarea>
                        <small class="text-muted"><span id="contadorObs">0</span>/2000 caracteres</small>
                        @error('observaciones') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            <hr>
            <div class="flex justify-end" style="gap:12px;">
                <a href="{{ route('admin.transporte.maestros.bus_carga_combustibles.index') }}" class="rd-btn rd-btn-default">
                    Cancelar
                </a>
                <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn" style="color:white;">
                    <i class="fas fa-check"></i> Guardar Carga
                </button>
            </div>
        </form>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@push('js')
<script>
function calcularTotalCombustible() {
    const litros = parseFloat(document.getElementById('inputLitros').value) || 0;
    const precio = parseFloat(document.getElementById('inputPrecio').value) || 0;
    const total  = (litros * precio).toFixed(2);
    document.getElementById('inputTotal').value = total;
}

// Autocompletar datos al seleccionar vehículo
document.getElementById('selectVehiculo').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (opt && opt.value) {
        const combId = opt.dataset.combustible;
        const km     = opt.dataset.km;
        if (combId) {
            document.getElementById('selectTipoCombustible').value = combId;
        }
        if (km && !document.getElementById('inputKmCargar').value) {
            document.getElementById('inputKmCargar').value = km;
        }
    }
});

calcularTotalCombustible();
</script>
@endpush
