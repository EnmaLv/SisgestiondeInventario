@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Registrar Mantenimiento</h1>
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
            <h3 class="rd-title-sm">Datos del Mantenimiento</h3>
            <a href="{{ route('admin.transporte.maestros.bus_mantenimientos.index') }}" class="rd-btn rd-btn-default">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <form action="{{ route('admin.transporte.maestros.bus_mantenimientos.store') }}" method="POST"
            class="rd-prevent-double-submit">
            @csrf

            {{-- Fila 1: Vehículo, Tipo, Estado --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Vehículo</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-bus"></i></span>
                            <select name="bus_vehiculo_id"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('bus_vehiculo_id') is-invalid @enderror">
                                <option value="">-- Seleccione --</option>
                                @foreach($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id }}" {{ old('bus_vehiculo_id') == $vehiculo->id ? 'selected' : '' }}>
                                        {{ $vehiculo->placa }} — {{ $vehiculo->modelo->nombre ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bus_vehiculo_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Tipo</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-wrench"></i></span>
                            <select name="tipo"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('tipo') is-invalid @enderror">
                                <option value="">-- Seleccione --</option>
                                <option value="preventivo" {{ old('tipo') == 'preventivo' ? 'selected' : '' }}>Preventivo</option>
                                <option value="correctivo" {{ old('tipo') == 'correctivo' ? 'selected' : '' }}>Correctivo</option>
                            </select>
                        </div>
                        @error('tipo') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Estado</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-info-circle"></i></span>
                            <select name="estado"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('estado') is-invalid @enderror">
                                <option value="pendiente"  {{ old('estado', 'pendiente') == 'pendiente'  ? 'selected' : '' }}>Pendiente</option>
                                <option value="en_proceso" {{ old('estado') == 'en_proceso' ? 'selected' : '' }}>En Proceso</option>
                                <option value="completado" {{ old('estado') == 'completado' ? 'selected' : '' }}>Completado</option>
                            </select>
                        </div>
                        @error('estado') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 2: Título --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full">
                    <div class="form-group">
                        <label class="font-weight-bold">Título</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-heading"></i></span>
                            <input type="text" name="titulo"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('titulo') is-invalid @enderror"
                                placeholder="Ej: Cambio de aceite y filtros" value="{{ old('titulo') }}"
                                maxlength="150" oninput="this.value=this.value.slice(0,150)">
                        </div>
                        @error('titulo') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 3: Fecha, Costo, KM al servicio --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha del Servicio</label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="fecha"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('fecha') is-invalid @enderror"
                                value="{{ old('fecha', date('Y-m-d')) }}"
                                max="{{ date('Y-m-d') }}">
                        </div>
                        @error('fecha') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">Costo <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-dollar-sign"></i></span>
                            <input type="number" name="costo" step="0.01"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('costo') is-invalid @enderror"
                                placeholder="Ej: 150.00" value="{{ old('costo') }}"
                                min="0" max="9999999"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,10)">
                        </div>
                        @error('costo') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="w-full md:w-1/3">
                    <div class="form-group">
                        <label class="font-weight-bold">KM al momento del servicio <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-road"></i></span>
                            <input type="number" name="km_al_servicio" step="0.01"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('km_al_servicio') is-invalid @enderror"
                                placeholder="Ej: 50000.00" value="{{ old('km_al_servicio') }}"
                                min="0" max="9999999"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,10)">
                        </div>
                        @error('km_al_servicio') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 4: Próximo KM, Próxima Fecha --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full md:w-1/2">
                    <div class="form-group">
                        <label class="font-weight-bold">Próximo KM de mantenimiento <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-tachometer-alt"></i></span>
                            <input type="number" name="proximo_km" step="0.01"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('proximo_km') is-invalid @enderror"
                                placeholder="Ej: 55000.00" value="{{ old('proximo_km') }}"
                                min="0" max="9999999"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,10)">
                        </div>
                        @error('proximo_km') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="w-full md:w-1/2">
                    <div class="form-group">
                        <label class="font-weight-bold">Próxima Fecha de mantenimiento <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="flex items-stretch w-full mt-1">
                            <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-calendar-check"></i></span>
                            <input type="date" name="proxima_fecha"
                                class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('proxima_fecha') is-invalid @enderror"
                                value="{{ old('proxima_fecha') }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        @error('proxima_fecha') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 5: Descripción --}}
            <div class="flex flex-wrap -mx-2">
                <div class="w-full">
                    <div class="form-group">
                        <label class="font-weight-bold">Descripción <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <textarea name="descripcion" rows="4"
                            class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('descripcion') is-invalid @enderror"
                            placeholder="Detalla el trabajo realizado, piezas cambiadas, observaciones..."
                            maxlength="2000"
                            oninput="this.value=this.value.slice(0,2000); document.getElementById('contadorDesc').textContent=this.value.length"
                            style="resize:none;">{{ old('descripcion') }}</textarea>
                        <small class="text-muted"><span id="contadorDesc">0</span>/2000 caracteres</small>
                        @error('descripcion') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            <hr>
            <div class="flex justify-end" style="gap:12px;">
                <a href="{{ route('admin.transporte.maestros.bus_mantenimientos.index') }}" class="rd-btn rd-btn-default">
                    Cancelar
                </a>
                <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn" style="color:white;">
                    <i class="fas fa-check"></i> Guardar
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
// Validación: próxima fecha debe ser después de la fecha del servicio
document.querySelector('[name="proxima_fecha"]').addEventListener('change', function() {
    const fecha        = document.querySelector('[name="fecha"]').value;
    const proximaFecha = this.value;
    if (fecha && proximaFecha && proximaFecha <= fecha) {
        this.classList.add('is-invalid');
        let err = this.closest('.form-group').querySelector('.error-inline');
        if (!err) {
            err = document.createElement('div');
            err.className = 'text-danger mt-1 error-inline';
            this.closest('.form-group').appendChild(err);
        }
        err.innerHTML = '<b>La próxima fecha debe ser posterior a la fecha del servicio.</b>';
    } else {
        this.classList.remove('is-invalid');
        const err = this.closest('.form-group').querySelector('.error-inline');
        if (err) err.remove();
    }
});
</script>
@endpush