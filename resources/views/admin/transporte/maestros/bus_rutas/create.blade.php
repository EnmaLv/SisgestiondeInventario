@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Registrar Nueva Ruta</h1>
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
            <h3 class="rd-title-sm">Datos de la Ruta</h3>
            <a href="{{ route('admin.transporte.maestros.bus_rutas.index') }}" class="rd-btn rd-btn-default">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>

        <form action="{{ route('admin.transporte.maestros.bus_rutas.store') }}" method="POST"
            class="rd-prevent-double-submit">
            @csrf

            {{-- Fila 1: Nombre, Distancia --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Nombre de la Ruta</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-route"></i></span>
                            <input type="text" name="nombre"
                                class="form-control rd-filter-input @error('nombre') is-invalid @enderror"
                                placeholder="Ej: Ruta Acarigua - Araure"
                                value="{{ old('nombre') }}" maxlength="100"
                                oninput="this.value=this.value.slice(0,100)">
                        </div>
                        @error('nombre') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Distancia (km)</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-road"></i></span>
                            <input type="number" name="distancia_km" step="0.01"
                                class="form-control rd-filter-input @error('distancia_km') is-invalid @enderror"
                                placeholder="Ej: 12.50" value="{{ old('distancia_km') }}"
                                min="0.1" max="9999"
                                oninput="this.value=this.value.replace(/[^0-9.]/g,'').slice(0,7)">
                        </div>
                        @error('distancia_km') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 2: Origen, Destino --}}
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Sede Origen</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                            <select name="sucursal_origen_id"
                                class="form-control rd-filter-input @error('sucursal_origen_id') is-invalid @enderror">
                                <option value="">-- Seleccione --</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" {{ old('sucursal_origen_id') == $sucursal->id ? 'selected' : '' }}>
                                        {{ $sucursal->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('sucursal_origen_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="font-weight-bold">Sede Destino</label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-map-marker-alt" style="color:var(--color-primary)"></i></span>
                            <select name="sucursal_destino_id"
                                class="form-control rd-filter-input @error('sucursal_destino_id') is-invalid @enderror">
                                <option value="">-- Seleccione --</option>
                                @foreach($sucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}" {{ old('sucursal_destino_id') == $sucursal->id ? 'selected' : '' }}>
                                        {{ $sucursal->nombre }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('sucursal_destino_id') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 3: Horarios --}}
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Hora Salida Mañana <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <input type="time" name="hora_salida_manana"
                                class="form-control rd-filter-input @error('hora_salida_manana') is-invalid @enderror"
                                value="{{ old('hora_salida_manana') }}">
                        </div>
                        @error('hora_salida_manana') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Hora Salida Tarde <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <input type="time" name="hora_salida_tarde"
                                class="form-control rd-filter-input @error('hora_salida_tarde') is-invalid @enderror"
                                value="{{ old('hora_salida_tarde') }}">
                        </div>
                        @error('hora_salida_tarde') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="font-weight-bold">Hora Salida Noche <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <div class="input-group mt-1">
                            <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            <input type="time" name="hora_salida_noche"
                                class="form-control rd-filter-input @error('hora_salida_noche') is-invalid @enderror"
                                value="{{ old('hora_salida_noche') }}">
                        </div>
                        @error('hora_salida_noche') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            {{-- Fila 4: Paradas Intermedias --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="font-weight-bold mb-0">
                                <i class="fas fa-map-pin" style="color:var(--color-primary);"></i> Paradas Intermedias del Recorrido <span class="text-muted font-weight-normal">(opcional)</span>
                            </label>
                            <button type="button" class="rd-btn rd-btn-default btn-sm" id="btnAgregarParada">
                                <i class="fas fa-plus"></i> Añadir Parada
                            </button>
                        </div>
                        <div class="p-3" style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;">
                            <div id="contenedorParadas">
                                <p id="sinParadasMsg" class="text-muted mb-0 text-center py-2" style="font-size:0.9rem;">
                                    No se han añadido paradas intermedias aún. Haz clic en <strong>Añadir Parada</strong> para agregarlas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Fila 5: Descripción --}}
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="font-weight-bold">Descripción <span class="text-muted font-weight-normal">(opcional)</span></label>
                        <textarea name="descripcion" rows="3"
                            class="form-control rd-filter-input @error('descripcion') is-invalid @enderror"
                            placeholder="Describe el recorrido, paradas principales, observaciones..."
                            maxlength="1000"
                            oninput="this.value=this.value.slice(0,1000); document.getElementById('contadorDesc').textContent=this.value.length"
                            style="resize:none;">{{ old('descripcion') }}</textarea>
                        <small class="text-muted"><span id="contadorDesc">0</span>/1000 caracteres</small>
                        @error('descripcion') <div class="text-danger mt-1"><b>{{ $message }}</b></div> @enderror
                    </div>
                </div>
            </div>

            <hr>
            <div class="d-flex justify-content-end" style="gap:12px;">
                <a href="{{ route('admin.transporte.maestros.bus_rutas.index') }}" class="rd-btn rd-btn-default">
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
const CSRF = '{{ csrf_token() }}';
const paradasDisponibles = @json($paradas);

// ── Validación en tiempo real ─────────────────────────────────────
function mostrarErrorInline(input, msg) {
    limpiarErrorInline(input);
    input.classList.add('is-invalid');
    const div = document.createElement('div');
    div.className = 'text-danger mt-1 error-inline';
    div.innerHTML = `<b>${msg}</b>`;
    input.closest('.form-group').appendChild(div);
}

function limpiarErrorInline(input) {
    input.classList.remove('is-invalid');
    const prev = input.closest('.form-group').querySelector('.error-inline');
    if (prev) prev.remove();
}

// Distancia
document.querySelector('[name="distancia_km"]').addEventListener('input', function() {
    const val = parseFloat(this.value);
    if (this.value && (val < 0.1 || val > 9999)) {
        mostrarErrorInline(this, 'Entre 0.1 y 9,999 km.');
    } else {
        limpiarErrorInline(this);
    }
});

// Nombre único en tiempo real
let nombreTimer = null;
document.querySelector('[name="nombre"]').addEventListener('input', function() {
    const val = this.value.trim();
    limpiarErrorInline(this);
    if (!val) return;
    clearTimeout(nombreTimer);
    nombreTimer = setTimeout(() => {
        fetch(`/admin/transporte/maestros/bus_rutas/verificar-nombre?nombre=${encodeURIComponent(val)}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(r => r.json())
        .then(res => {
            if (res.existe) mostrarErrorInline(document.querySelector('[name="nombre"]'), 'Ya existe una ruta con este nombre.');
        });
    }, 500);
});

// ── Manejo de Paradas Intermedias Dinámicas ──────────────────────
const contenedorParadas = document.getElementById('contenedorParadas');
const btnAgregarParada  = document.getElementById('btnAgregarParada');
let contadorParadas     = 0;

function actualizarNumeracionParadas() {
    const items = contenedorParadas.querySelectorAll('.parada-item');
    const sinMsg = document.getElementById('sinParadasMsg');
    if (items.length === 0) {
        if (!sinMsg) {
            contenedorParadas.innerHTML = `
                <p id="sinParadasMsg" class="text-muted mb-0 text-center py-2" style="font-size:0.9rem;">
                    No se han añadido paradas intermedias aún. Haz clic en <strong>Añadir Parada</strong> para agregarlas.
                </p>`;
        }
    } else {
        if (sinMsg) sinMsg.remove();
        items.forEach((item, index) => {
            const badge = item.querySelector('.parada-orden-badge');
            if (badge) badge.textContent = `Parada #${index + 1}`;
        });
    }
}

function crearFilaParada(seleccionadaId = '') {
    const sinMsg = document.getElementById('sinParadasMsg');
    if (sinMsg) sinMsg.remove();

    contadorParadas++;
    let optionsHtml = '<option value="">-- Seleccionar Parada --</option>';
    paradasDisponibles.forEach(p => {
        const selected = (p.id == seleccionadaId) ? 'selected' : '';
        const direccion = p.direccion ? ` (${p.direccion})` : '';
        optionsHtml += `<option value="${p.id}" ${selected}>${p.nombre}${direccion}</option>`;
    });

    const div = document.createElement('div');
    div.className = 'parada-item d-flex align-items-center mb-2 p-2';
    div.style.cssText = 'background:#ffffff;border:1px solid #e2e8f0;border-radius:8px;gap:10px;';
    div.innerHTML = `
        <span class="rd-badge rd-badge-info parada-orden-badge" style="min-width:85px;text-align:center;">Parada #1</span>
        <div class="flex-grow-1">
            <select name="paradas[]" class="form-control rd-filter-input" required>
                ${optionsHtml}
            </select>
        </div>
        <button type="button" class="btn btn-sm btn-outline-secondary btn-subir-parada" title="Subir">
            <i class="fas fa-arrow-up"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-secondary btn-bajar-parada" title="Bajar">
            <i class="fas fa-arrow-down"></i>
        </button>
        <button type="button" class="btn btn-sm btn-outline-danger btn-eliminar-parada" title="Eliminar">
            <i class="fas fa-trash"></i>
        </button>
    `;

    // Eventos
    div.querySelector('.btn-eliminar-parada').addEventListener('click', () => {
        div.remove();
        actualizarNumeracionParadas();
    });

    div.querySelector('.btn-subir-parada').addEventListener('click', () => {
        const prev = div.previousElementSibling;
        if (prev && prev.classList.contains('parada-item')) {
            contenedorParadas.insertBefore(div, prev);
            actualizarNumeracionParadas();
        }
    });

    div.querySelector('.btn-bajar-parada').addEventListener('click', () => {
        const next = div.nextElementSibling;
        if (next && next.classList.contains('parada-item')) {
            contenedorParadas.insertBefore(next, div);
            actualizarNumeracionParadas();
        }
    });

    contenedorParadas.appendChild(div);
    actualizarNumeracionParadas();
}

if (btnAgregarParada) {
    btnAgregarParada.addEventListener('click', () => crearFilaParada());
}
</script>
@endpush