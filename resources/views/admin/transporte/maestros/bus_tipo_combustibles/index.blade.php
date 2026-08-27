@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Tipos de Combustible</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <button type="button" class="rd-btn rd-btn-primary" data-modal-toggle="modal" data-target="#modalCrear">
                <i class="fas fa-plus"></i> Nuevo Tipo
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
                    <h3 class="rd-title-sm">Tipos de Combustible Registrados</h3>
                </div>
                <div class="rd-actions">
                    <div class="flex gap-3 items-center">
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
                    <form action="{{ route('admin.transporte.maestros.bus_tipo_combustibles.index') }}" method="GET"
                        class="rd-search-inline" role="search">
                        <input type="hidden" name="estado" value="{{ request('estado', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                            class="rd-search-input" placeholder="Buscar tipo..." />
                        <button class="rd-icon-btn" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th style="width:60px">#</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Descripción</th>
                        <th style="width:120px" class="text-center">Estado</th>
                        <th style="width:150px" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tipos as $tipo)
                        <tr data-id="{{ $tipo->id }}">
                            <td class="text-center">
                                {{ ($tipos->currentPage() - 1) * $tipos->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-center">{{ $tipo->nombre }}</td>
                            <td class="text-center">{{ $tipo->descripcion }}</td>
                            <td class="text-center">
                                @if ($tipo->estado)
                                    <span class="rd-badge rd-badge-success">Activo</span>
                                @else
                                    <span class="rd-badge rd-badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="rd-action-group">
                                    <button type="button" class="rd-action btn-abrir-editar"
                                        data-id="{{ $tipo->id }}"
                                        data-nombre="{{ $tipo->nombre }}"
                                        data-descripcion="{{ $tipo->descripcion }}"
                                        data-modal-toggle="modal" data-target="#modalEditar"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if ($tipo->estado)
                                        <form action="{{ route('admin.transporte.maestros.bus_tipo_combustibles.destroy', $tipo) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rd-action rd-btn-danger"
                                                onclick="confirmAccion(event, this, 'inactivar', 'tipo')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.transporte.maestros.bus_tipo_combustibles.activar', $tipo) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="rd-action rd-btn-success"
                                                onclick="confirmAccion(event, this, 'activar', 'tipo')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No hay tipos de combustible registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 flex justify-center">
                {{ $tipos->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>

    {{-- ==================== MODAL CREAR ==================== --}}
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" id="modalCrear" tabindex="-1" aria-hidden="true">
        <div class="relative w-full w-full max-w-2xl flex items-center justify-center min-h-full">
            <div class="relative w-full rd-card" style="border-radius:12px;border:1px solid #e5e7eb;">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title rd-title-sm">
                        <i class="fas fa-plus-circle mr-2" style="color:var(--color-primary)"></i>Nuevo Tipo de Combustible
                    </h5>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" data-modal-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formCrear" action="{{ route('admin.transporte.maestros.bus_tipo_combustibles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre</label>
                            <div class="flex items-stretch w-full mt-1">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-gas-pump"></i></span>
                                <input type="text" name="nombre" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                    placeholder="Ej: Gasolina" maxlength="100" autofocus>
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Descripción <span class="text-muted font-weight-normal">(opcional)</span></label>
                            <div class="flex items-stretch w-full mt-1">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-align-left"></i></span>
                                <input type="text" name="descripcion" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                    placeholder="Ej: Combustible de 95 octanos" maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="rd-btn rd-btn-default" data-modal-dismiss="modal">Cancelar</button>
                        <button type="submit" class="rd-btn rd-btn-primary">
                            <i class="fas fa-check"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL EDITAR ==================== --}}
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" id="modalEditar" tabindex="-1" aria-hidden="true">
        <div class="relative w-full w-full max-w-2xl flex items-center justify-center min-h-full">
            <div class="relative w-full rd-card" style="border-radius:12px;border:1px solid #e5e7eb;">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title rd-title-sm">
                        <i class="fas fa-edit mr-2" style="color:var(--color-primary)"></i>Editar Tipo de Combustible
                    </h5>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" data-modal-dismiss="modal"><span>&times;</span></button>
                </div>
                <form id="formEditar" action="" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre</label>
                            <div class="flex items-stretch w-full mt-1">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-gas-pump"></i></span>
                                <input type="text" id="editNombre" name="nombre"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                    placeholder="Nombre del tipo" maxlength="100">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Descripción <span class="text-muted font-weight-normal">(opcional)</span></label>
                            <div class="flex items-stretch w-full mt-1">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-align-left"></i></span>
                                <input type="text" id="editDescripcion" name="descripcion"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                    placeholder="Descripción del tipo" maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="rd-btn rd-btn-default" data-modal-dismiss="modal">Cancelar</button>
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
const BASE_URL = '/admin/transporte/maestros/bus_tipo_combustibles';

function toastExito(mensaje) {
    Swal.fire({
        toast: true, position: 'top-end', icon: 'success',
        title: mensaje, showConfirmButton: false,
        timer: 3000, timerProgressBar: true,
    });
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
            input.closest('.flex items-stretch w-full').after(div);
        }
    });
}

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
            agregarFilaTabla(res.tipo);
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
        document.getElementById('editNombre').value      = this.dataset.nombre;
        document.getElementById('editDescripcion').value = this.dataset.descripcion === 'Ninguna' ? '' : this.dataset.descripcion;
        document.getElementById('formEditar').action     = `${BASE_URL}/${this.dataset.id}`;
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
            actualizarFilaTabla(res.tipo);
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
        text: `¿Desea ${accion} el ${entidad}?`,
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
            if (res.success) {
                button.closest('tr').remove();
                toastExito(res.message);
            }
        });
    });
}

// ── Helpers DOM ───────────────────────────────────────────────────
let contadorFilas = {{ $tipos->total() }};

function agregarFilaTabla(tipo) {
    contadorFilas++;
    const tbody = document.querySelector('.rd-table tbody');
    const vacio = tbody.querySelector('td[colspan]');
    if (vacio) vacio.closest('tr').remove();

    const descripcion = tipo.descripcion || 'Ninguna';
    const fila = `
        <tr data-id="${tipo.id}">
            <td class="text-center">${contadorFilas}</td>
            <td class="text-center">${tipo.nombre}</td>
            <td class="text-center">${descripcion}</td>
            <td class="text-center"><span class="rd-badge rd-badge-success">Activo</span></td>
            <td class="text-center">
                <div class="rd-action-group">
                    <button type="button" class="rd-action btn-abrir-editar"
                        data-id="${tipo.id}"
                        data-nombre="${tipo.nombre}"
                        data-descripcion="${descripcion}"
                        data-modal-toggle="modal" data-target="#modalEditar"
                        title="Editar">
                        <i class="fas fa-edit"></i>
                    </button>
                    <form action="${BASE_URL}/${tipo.id}" method="POST" style="display:inline;">
                        <input type="hidden" name="_token" value="${CSRF}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="rd-action rd-btn-danger"
                            onclick="confirmAccion(event, this, 'inactivar', 'tipo')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </div>
            </td>
        </tr>`;
    tbody.insertAdjacentHTML('beforeend', fila);
    bindEditar(tbody.querySelector(`tr[data-id="${tipo.id}"] .btn-abrir-editar`));
}

function actualizarFilaTabla(tipo) {
    const fila = document.querySelector(`tr[data-id="${tipo.id}"]`);
    if (!fila) return;
    const descripcion = tipo.descripcion || 'Ninguna';
    fila.cells[1].textContent = tipo.nombre;
    fila.cells[2].textContent = descripcion;
    const btn = fila.querySelector('.btn-abrir-editar');
    if (btn) {
        btn.dataset.nombre      = tipo.nombre;
        btn.dataset.descripcion = descripcion;
    }
}

// ── Toggle estado ─────────────────────────────────────────────────
document.getElementById('estadoToggle').addEventListener('change', function() {
    const params = new URLSearchParams(window.location.search);
    params.set('estado', this.checked ? 1 : 0);
    window.location.href = "{{ route('admin.transporte.maestros.bus_tipo_combustibles.index') }}?" + params.toString();
});
</script>
@endpush