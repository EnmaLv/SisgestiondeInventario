@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Paradas</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <button type="button" class="rd-btn rd-btn-primary" data-toggle="modal" data-target="#modalCrear">
                <i class="fas fa-plus"></i> Nueva Parada
            </button>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Paradas Registradas</h3>
                </div>
                <div class="rd-actions">
                    <div class="d-flex gap-3 align-items-center">
                        <span class="font-weight-bold" style="margin-right:10px;">Filtrar por estado:</span>
                        <div class="toggle-container">
                            <input type="checkbox" id="estadoToggle" class="toggle-checkbox"
                                {{ request('estado', 1) == 1 ? 'checked' : '' }}>
                            <label for="estadoToggle" class="toggle-label">
                                <span class="toggle-inner"></span>
                                <span class="toggle-switch"></span>
                            </label>
                        </div>
                    </div>
                    <form action="{{ route('admin.transporte.maestros.bus_paradas.index') }}" method="GET"
                        class="rd-search-inline" role="search">
                        <input type="hidden" name="estado" value="{{ request('estado', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                            class="rd-search-input" placeholder="Buscar parada..." />
                        <button class="rd-icon-btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Dirección</th>
                        <th class="text-center">Coordenadas</th>
                        <th style="width:120px" class="text-center">Estado</th>
                        <th style="width:150px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paradas as $parada)
                        <tr data-id="{{ $parada->id }}">
                            <td class="text-center">
                                {{ ($paradas->currentPage() - 1) * $paradas->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-center">{{ $parada->nombre }}</td>
                            <td class="text-center">{{ $parada->direccion }}</td>
                            <td class="text-center">
                                @if($parada->lat && $parada->lng)
                                    <small class="text-muted">{{ $parada->lat }}, {{ $parada->lng }}</small>
                                @else
                                    <span class="text-muted">Sin coordenadas</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($parada->estado)
                                    <span class="rd-badge rd-badge-success">Activa</span>
                                @else
                                    <span class="rd-badge rd-badge-danger">Inactiva</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="rd-action-group">
                                    <button type="button" class="rd-action btn-abrir-editar"
                                        data-id="{{ $parada->id }}"
                                        data-nombre="{{ $parada->nombre }}"
                                        data-lat="{{ $parada->lat }}"
                                        data-lng="{{ $parada->lng }}"
                                        data-direccion="{{ $parada->getRawOriginal('direccion') }}"
                                        data-toggle="modal" data-target="#modalEditar"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if ($parada->estado)
                                        <form action="{{ route('admin.transporte.maestros.bus_paradas.destroy', $parada) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rd-action rd-btn-danger"
                                                onclick="confirmAccion(event, this, 'inactivar', 'parada')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.transporte.maestros.bus_paradas.activar', $parada) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="rd-action rd-btn-success"
                                                onclick="confirmAccion(event, this, 'activar', 'parada')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">No hay paradas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $paradas->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>

    {{-- ==================== MODAL CREAR ==================== --}}
    <div class="modal fade" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rd-card" style="border-radius:12px;border:1px solid #e5e7eb;">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title rd-title-sm">
                        <i class="fas fa-plus-circle mr-2" style="color:var(--color-primary)"></i>Nueva Parada
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formCrear" action="{{ route('admin.transporte.maestros.bus_paradas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre</label>
                            <div class="input-group mt-1">
                                <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                <input type="text" name="nombre" id="crearNombre"
                                    class="form-control rd-filter-input"
                                    placeholder="Ej: Terminal Acarigua" maxlength="100"
                                    oninput="this.value=this.value.slice(0,100)" autofocus>
                            </div>
                            <div id="errorCrearNombre" class="text-danger mt-1" style="display:none;"></div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Dirección <span class="text-muted font-weight-normal">(opcional)</span></label>
                            <div class="input-group mt-1">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" name="direccion"
                                    class="form-control rd-filter-input"
                                    placeholder="Ej: Av. Principal, Acarigua" maxlength="255"
                                    oninput="this.value=this.value.slice(0,255)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Latitud <span class="text-muted font-weight-normal">(opcional)</span></label>
                                    <div class="input-group mt-1">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="number" name="lat" step="0.0000001"
                                            class="form-control rd-filter-input"
                                            placeholder="Ej: 9.5597" min="-90" max="900000000000"
                                            oninput="this.value=this.value.replace(/[^0-9.\-]/g,'').slice(0,12)">
                                    </div>
                                    <div id="errorCrearLat" class="text-danger mt-1" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Longitud <span class="text-muted font-weight-normal">(opcional)</span></label>
                                    <div class="input-group mt-1">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="number" name="lng" step="0.0000001"
                                            class="form-control rd-filter-input"
                                            placeholder="Ej: -69.2014" min="-180" max="180"
                                            oninput="this.value=this.value.replace(/[^0-9.\-]/g,'').slice(0,13)">
                                    </div>
                                    <div id="errorCrearLng" class="text-danger mt-1" style="display:none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="rd-btn rd-btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="rd-btn rd-btn-primary">
                            <i class="fas fa-check"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL EDITAR ==================== --}}
    <div class="modal fade" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content rd-card" style="border-radius:12px;border:1px solid #e5e7eb;">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title rd-title-sm">
                        <i class="fas fa-edit mr-2" style="color:var(--color-primary)"></i>Editar Parada
                    </h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formEditar" action="" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre</label>
                            <div class="input-group mt-1">
                                <span class="input-group-text"><i class="fas fa-map-pin"></i></span>
                                <input type="text" id="editNombre" name="nombre"
                                    class="form-control rd-filter-input" maxlength="100"
                                    oninput="this.value=this.value.slice(0,100)">
                            </div>
                            <div id="errorEditNombre" class="text-danger mt-1" style="display:none;"></div>
                        </div>
                        <div class="form-group">
                            <label class="font-weight-bold">Dirección <span class="text-muted font-weight-normal">(opcional)</span></label>
                            <div class="input-group mt-1">
                                <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                <input type="text" id="editDireccion" name="direccion"
                                    class="form-control rd-filter-input" maxlength="255"
                                    oninput="this.value=this.value.slice(0,255)">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Latitud <span class="text-muted font-weight-normal">(opcional)</span></label>
                                    <div class="input-group mt-1">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="number" id="editLat" name="lat" step="0.0000001"
                                            class="form-control rd-filter-input" min="-90" max="900000000000"
                                            oninput="this.value=this.value.replace(/[^0-9.\-]/g,'').slice(0,12)">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-0">
                                    <label class="font-weight-bold">Longitud <span class="text-muted font-weight-normal">(opcional)</span></label>
                                    <div class="input-group mt-1">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="number" id="editLng" name="lng" step="0.0000001"
                                            class="form-control rd-filter-input" min="-180" max="180"
                                            oninput="this.value=this.value.replace(/[^0-9.\-]/g,'').slice(0,13)">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="rd-btn rd-btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="rd-btn rd-btn-primary" style="color:white;">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@push('js')
<script>
const CSRF     = '{{ csrf_token() }}';
const BASE_URL = '/admin/transporte/maestros/bus_paradas';

function toastExito(mensaje) {
    Swal.fire({
        toast: true, position: 'top-end', icon: 'success',
        title: mensaje, showConfirmButton: false,
        timer: 3000, timerProgressBar: true,
    });
}

function mostrarError(id, msg) {
    const el = document.getElementById(id);
    if (el) { el.textContent = msg; el.style.display = 'block'; }
}

function limpiarError(id) {
    const el = document.getElementById(id);
    if (el) { el.textContent = ''; el.style.display = 'none'; }
}

function mostrarErrores(errors, formId) {
    document.querySelectorAll(`${formId} .text-danger`).forEach(e => e.remove());
    document.querySelectorAll(`${formId} .is-invalid`).forEach(e => e.classList.remove('is-invalid'));
    Object.keys(errors).forEach(function(campo) {
        const input = document.querySelector(`${formId} [name="${campo}"]`);
        if (input) {
            input.classList.add('is-invalid');
            const div = document.createElement('div');
            div.className = 'text-danger mt-1';
            div.innerHTML = `<b>${errors[campo][0]}</b>`;
            input.closest('.form-group').appendChild(div);
        }
    });
}

// ── Verificación nombre duplicado en tiempo real ───────────────────
let timerCrear = null, timerEditar = null, paradaEditandoId = null;

document.getElementById('crearNombre').addEventListener('input', function() {
    limpiarError('errorCrearNombre');
    const val = this.value.trim();
    if (!val) return;
    clearTimeout(timerCrear);
    timerCrear = setTimeout(() => {
        fetch(`${BASE_URL}/verificar-nombre?nombre=${encodeURIComponent(val)}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(r => r.json())
        .then(res => { if (res.existe) mostrarError('errorCrearNombre', 'Ya existe una parada con este nombre.'); });
    }, 500);
});

document.getElementById('editNombre').addEventListener('input', function() {
    limpiarError('errorEditNombre');
    const val = this.value.trim();
    if (!val) return;
    clearTimeout(timerEditar);
    timerEditar = setTimeout(() => {
        fetch(`${BASE_URL}/verificar-nombre?nombre=${encodeURIComponent(val)}&exclude=${paradaEditandoId}`, {
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
        })
        .then(r => r.json())
        .then(res => { if (res.existe) mostrarError('errorEditNombre', 'Ya existe una parada con este nombre.'); });
    }, 500);
});

// ── CREAR ─────────────────────────────────────────────────────────
document.getElementById('formCrear').addEventListener('submit', function(e) {
    e.preventDefault();
    fetch(this.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: new FormData(this),
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            $('#modalCrear').modal('hide');
            this.reset();
            agregarFilaTabla(res.parada);
            toastExito(res.message);
        } else {
            mostrarErrores(res.errors ?? {}, '#formCrear');
        }
    });
});

// ── EDITAR ────────────────────────────────────────────────────────
document.querySelectorAll('.btn-abrir-editar').forEach(bindEditar);

function bindEditar(btn) {
    btn.addEventListener('click', function() {
        paradaEditandoId = this.dataset.id;
        document.getElementById('editNombre').value    = this.dataset.nombre;
        document.getElementById('editDireccion').value = this.dataset.direccion ?? '';
        document.getElementById('editLat').value       = this.dataset.lat ?? '';
        document.getElementById('editLng').value       = this.dataset.lng ?? '';
        document.getElementById('formEditar').action   = `${BASE_URL}/${this.dataset.id}`;
        limpiarError('errorEditNombre');
    });
}

document.getElementById('formEditar').addEventListener('submit', function(e) {
    e.preventDefault();
    const data = new FormData(this);
    data.append('_method', 'PUT');
    fetch(this.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: data,
    })
    .then(r => r.json())
    .then(res => {
        if (res.success) {
            $('#modalEditar').modal('hide');
            actualizarFilaTabla(res.parada);
            toastExito(res.message);
        } else {
            mostrarErrores(res.errors ?? {}, '#formEditar');
        }
    });
});

// ── INACTIVAR / ACTIVAR ───────────────────────────────────────────
function confirmAccion(event, button, accion, entidad) {
    event.preventDefault();
    Swal.fire({
        title: '¿Estás seguro?',
        text: `¿Desea ${accion} la ${entidad}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Sí, ${accion}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (!result.isConfirmed) return;
        const form = button.closest('form');
        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
            body: new FormData(form),
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) { button.closest('tr').remove(); toastExito(res.message); }
        });
    });
}

// ── Helpers DOM ───────────────────────────────────────────────────
let contadorFilas = {{ $paradas->total() }};

function agregarFilaTabla(p) {
    contadorFilas++;
    const tbody = document.querySelector('.rd-table tbody');
    const vacio = tbody.querySelector('td[colspan]');
    if (vacio) vacio.closest('tr').remove();

    const direccion = p.direccion || 'Ninguna';
    const coords    = (p.lat && p.lng) ? `<small class="text-muted">${p.lat}, ${p.lng}</small>` : '<span class="text-muted">Sin coordenadas</span>';

    const fila = `
        <tr data-id="${p.id}">
            <td class="text-center">${contadorFilas}</td>
            <td class="text-center">${p.nombre}</td>
            <td class="text-center">${direccion}</td>
            <td class="text-center">${coords}</td>
            <td class="text-center"><span class="rd-badge rd-badge-success">Activa</span></td>
            <td class="text-center">
                <div class="rd-action-group">
                    <button type="button" class="rd-action btn-abrir-editar"
                        data-id="${p.id}" data-nombre="${p.nombre}"
                        data-lat="${p.lat ?? ''}" data-lng="${p.lng ?? ''}"
                        data-direccion="${p.direccion ?? ''}"
                        data-toggle="modal" data-target="#modalEditar" title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="${BASE_URL}/${p.id}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${CSRF}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="rd-action rd-btn-danger"
                            onclick="confirmAccion(event, this, 'inactivar', 'parada')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>`;
    tbody.insertAdjacentHTML('beforeend', fila);
    bindEditar(tbody.querySelector(`tr[data-id="${p.id}"] .btn-abrir-editar`));
}

function actualizarFilaTabla(p) {
    const fila = document.querySelector(`tr[data-id="${p.id}"]`);
    if (!fila) return;
    const direccion = p.direccion || 'Ninguna';
    const coords    = (p.lat && p.lng) ? `<small class="text-muted">${p.lat}, ${p.lng}</small>` : '<span class="text-muted">Sin coordenadas</span>';
    fila.cells[1].textContent = p.nombre;
    fila.cells[2].textContent = direccion;
    fila.cells[3].innerHTML   = coords;
    const btn = fila.querySelector('.btn-abrir-editar');
    if (btn) {
        btn.dataset.nombre    = p.nombre;
        btn.dataset.direccion = p.direccion ?? '';
        btn.dataset.lat       = p.lat ?? '';
        btn.dataset.lng       = p.lng ?? '';
    }
}

document.getElementById('estadoToggle').addEventListener('change', function() {
    const params = new URLSearchParams(window.location.search);
    params.set('estado', this.checked ? 1 : 0);
    window.location.href = "{{ route('admin.transporte.maestros.bus_paradas.index') }}?" + params.toString();
});
</script>
@endpush