@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Registrar Viaje</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
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
            <h3 class="rd-title-sm">Datos del Viaje</h3>
            <a href="{{ route('admin.transporte.maestros.bus_viajes.index') }}" class="rd-btn rd-btn-default">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
        
        <form action="{{ route('admin.transporte.maestros.bus_viajes.store') }}" method="POST"
            class="rd-prevent-double-submit">
            @csrf

            {{-- Fila 1: Vehículo, Ruta, Conductor --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Vehículo</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-bus"></i></span>
                            <select name="bus_vehiculo_id"
                                class="form-control rd-filter-input @error('bus_vehiculo_id') is-invalid @enderror">
                                <option value="">-- Seleccione --</option>
                                @foreach($vehiculos as $v)
                                    <option value="{{ $v->id }}" {{ old('bus_vehiculo_id') == $v->id ? 'selected' : '' }}>
                                        {{ $v->placa }} — {{ $v->modelo->nombre ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bus_vehiculo_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Ruta</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-route"></i></span>
                            <select name="bus_ruta_id"
                                class="form-control rd-filter-input @error('bus_ruta_id') is-invalid @enderror">
                                <option value="">-- Seleccione --</option>
                                @foreach($rutas as $r)
                                    <option value="{{ $r->id }}" {{ old('bus_ruta_id') == $r->id ? 'selected' : '' }}>
                                        {{ $r->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('bus_ruta_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Conductor <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <select name="conductor_id"
                                class="form-control rd-filter-input @error('conductor_id') is-invalid @enderror">
                                <option value="">-- Seleccione --</option>
                                @foreach($conductores as $c)
                                    <option value="{{ $c->id_usuario }}" {{ old('conductor_id') == $c->id_usuario ? 'selected' : '' }}>
                                        {{ $c->persona->nombre_persona ?? $c->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('conductor_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 2: Turno, Estado, Firebase ID --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Turno <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <select name="turno"
                                class="form-control rd-filter-input @error('turno') is-invalid @enderror">
                                <option value="">-- Seleccione --</option>
                                <option value="mañana" {{ old('turno') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                                <option value="tarde"  {{ old('turno') == 'tarde'  ? 'selected' : '' }}>Tarde</option>
                                <option value="noche"  {{ old('turno') == 'noche'  ? 'selected' : '' }}>Noche</option>
                            </select>
                        </div>
                        @error('turno') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Estado</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                            <select name="estado"
                                class="form-control rd-filter-input @error('estado') is-invalid @enderror">
                                <option value="programado" {{ old('estado', 'programado') == 'programado' ? 'selected' : '' }}>Programado</option>
                                <option value="en_curso"   {{ old('estado') == 'en_curso'   ? 'selected' : '' }}>En Curso</option>
                                <option value="finalizado" {{ old('estado') == 'finalizado' ? 'selected' : '' }}>Finalizado</option>
                                <option value="cancelado"  {{ old('estado') == 'cancelado'  ? 'selected' : '' }}>Cancelado</option>
                            </select>
                        </div>
                        @error('estado') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Firebase ID <span class="text-muted font-weight-normal">(gestionado por la app)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-fire"></i></span>
                            <input type="text" name="firebase_id"
                                class="form-control rd-filter-input @error('firebase_id') is-invalid @enderror"
                                placeholder="Se asigna automáticamente" value="{{ old('firebase_id') }}"
                                maxlength="100" readonly
                                style="background:#f8fafc;cursor:not-allowed;">
                        </div>
                        @error('firebase_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 3: Fechas --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha y Hora de Inicio <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="datetime-local" name="fecha_inicio"
                                class="form-control rd-filter-input @error('fecha_inicio') is-invalid @enderror"
                                value="{{ old('fecha_inicio') }}">
                        </div>
                        @error('fecha_inicio') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Fecha y Hora de Fin <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                            <input type="datetime-local" name="fecha_fin"
                                class="form-control rd-filter-input @error('fecha_fin') is-invalid @enderror"
                                value="{{ old('fecha_fin') }}">
                        </div>
                        @error('fecha_fin') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 4: KMs --}}
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">KM Inicio</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-road"></i></span>
                            <input type="number" name="km_inicio" step="0.01"
                                class="form-control rd-filter-input @error('km_inicio') is-invalid @enderror"
                                placeholder="Ej: 50000.00" value="{{ old('km_inicio', 0) }}"
                                min="0" max="9999999"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,10); calcularDistancia()">
                        </div>
                        @error('km_inicio') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">KM Fin <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-road"></i></span>
                            <input type="number" name="km_fin" step="0.01"
                                class="form-control rd-filter-input @error('km_fin') is-invalid @enderror"
                                placeholder="Ej: 50120.00" value="{{ old('km_fin') }}"
                                min="0" max="9999999"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,10); calcularDistancia()">
                        </div>
                        @error('km_fin') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Distancia Recorrida (km) <span class="text-muted font-weight-normal">(auto)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-ruler-horizontal"></i></span>
                            <input type="number" name="distancia_km" id="distancia_km" step="0.01"
                                class="form-control rd-filter-input"
                                value="{{ old('distancia_km', 0) }}" min="0" max="9999"
                                readonly style="background:#f8fafc;cursor:not-allowed;">
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Pasajeros <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                            <input type="number" name="pasajeros"
                                class="form-control rd-filter-input @error('pasajeros') is-invalid @enderror"
                                placeholder="Ej: 35" value="{{ old('pasajeros', 0) }}"
                                min="0" max="300"
                                oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,3)">
                        </div>
                        @error('pasajeros') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 5: Litros Gastados --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Litros Gastados <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-gas-pump"></i></span>
                            <input type="number" name="litros_gastados" step="0.01"
                                class="form-control rd-filter-input @error('litros_gastados') is-invalid @enderror"
                                placeholder="Ej: 42.50" value="{{ old('litros_gastados', 0) }}"
                                min="0" max="9999"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,7)">
                        </div>
                        @error('litros_gastados') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 6: Observaciones --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-bold">Observaciones <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <textarea name="observaciones" rows="3"
                            class="form-control rd-filter-input @error('observaciones') is-invalid @enderror"
                            placeholder="Notas del viaje..."
                            maxlength="2000"
                            oninput="this.value=this.value.slice(0,2000); document.getElementById('contadorObs').textContent=this.value.length"
                            style="resize:none;">{{ old('observaciones') }}</textarea>
                        <small class="text-muted"><span id="contadorObs">0</span>/2000 caracteres</small>
                        @error('observaciones') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end" style="gap:12px;">
                <a href="{{ route('admin.transporte.maestros.bus_viajes.index') }}" class="rd-btn rd-btn-default">Cancelar</a>
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
function calcularDistancia() {
    const kmInicio = parseFloat(document.querySelector('[name="km_inicio"]').value) || 0;
    const kmFin    = parseFloat(document.querySelector('[name="km_fin"]').value) || 0;
    const dist     = kmFin > kmInicio ? (kmFin - kmInicio).toFixed(2) : 0;
    document.getElementById('distancia_km').value = dist;
}

// Validación: fecha fin debe ser después de fecha inicio
document.querySelector('[name="fecha_fin"]').addEventListener('change', function() {
    const inicio = document.querySelector('[name="fecha_inicio"]').value;
    const fin    = this.value;
    if (inicio && fin && fin < inicio) {
        this.classList.add('is-invalid');
        let err = this.closest('.form-group').querySelector('.error-inline');
        if (!err) {
            err = document.createElement('div');
            err.className = 'text-danger mt-1 error-inline';
            this.closest('.form-group').appendChild(err);
        }
        err.innerHTML = '<b>La fecha de fin debe ser posterior a la de inicio.</b>';
    } else {
        this.classList.remove('is-invalid');
        const err = this.closest('.form-group').querySelector('.error-inline');
        if (err) err.remove();
    }
});
</script>
@endpush