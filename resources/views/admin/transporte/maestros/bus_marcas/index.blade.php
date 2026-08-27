@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center"
        style="background:#ffffff;border-radius:14px;box-shadow:0 4px 14px rgba(0,0,0,0.06);border:1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem;color:#0f172a;font-weight:700;">Marcas de Buses</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem;color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div>
            <button type="button" class="rd-btn rd-btn-primary" data-modal-toggle="modal" data-target="#modalCrear">
                <i class="fas fa-plus"></i> Nueva Marca
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
                    <h3 class="rd-title-sm">Marcas Registradas</h3>
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
                    <form action="{{ route('admin.transporte.maestros.bus_marcas.index') }}" method="GET"
                        class="rd-search-inline" role="search">
                        <input type="hidden" name="estado" value="{{ request('estado', 1) }}">
                        <input type="text" name="buscar" value="{{ request('buscar') }}"
                            class="rd-search-input" placeholder="Buscar marca..." />
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
                    @forelse($marcas as $marca)
                        <tr data-id="{{ $marca->id }}">
                            <td class="text-center">
                                {{ ($marcas->currentPage() - 1) * $marcas->perPage() + $loop->iteration }}
                            </td>
                            <td class="text-center">{{ $marca->nombre }}</td>
                            <td class="text-center">{{ $marca->descripcion }}</td>
                            <td class="text-center">
                                @if ($marca->estado)
                                    <span class="rd-badge rd-badge-success">Activo</span>
                                @else
                                    <span class="rd-badge rd-badge-danger">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="rd-action-group">
                                    {{-- Botón editar: abre modal y carga datos --}}
                                    <button type="button" class="rd-action btn-abrir-editar"
                                        data-id="{{ $marca->id }}"
                                        data-nombre="{{ $marca->nombre }}"
                                        data-descripcion="{{ $marca->descripcion }}"
                                        data-modal-toggle="modal" data-target="#modalEditar"
                                        title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    @if ($marca->estado)
                                        <form action="{{ route('admin.transporte.maestros.bus_marcas.destroy', $marca) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="rd-action rd-btn-danger"
                                                onclick="confirmAccion(event, this, 'inactivar', 'marca')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.transporte.maestros.bus_marcas.activar', $marca) }}"
                                            method="POST" style="display:inline;">
                                            @csrf @method('PUT')
                                            <button type="submit" class="rd-action rd-btn-success"
                                                onclick="confirmAccion(event, this, 'activar', 'marca')">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">No hay marcas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 flex justify-center">
                {{ $marcas->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>

    {{-- ==================== MODAL CREAR ==================== --}} 
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" id="modalCrear" tabindex="-1" aria-labelledby="modalCrearLabel" aria-hidden="true">
        <div class="relative w-full w-full max-w-2xl flex items-center justify-center min-h-full">
            <div class="relative w-full rd-card" style="border-radius:12px;border:1px solid #e5e7eb;">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title rd-title-sm" id="modalCrearLabel">
                        <i class="fas fa-plus-circle mr-2" style="color:var(--color-primary)"></i>Nueva Marca
                    </h5>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" data-modal-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formCrear" action="{{ route('admin.transporte.maestros.bus_marcas.store') }}" method="POST" class="rd-prevent-double-submit">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre</label>
                            <div class="flex items-stretch w-full mt-1">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-tag"></i></span>
                                <input type="text" name="nombre"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input @error('nombre') is-invalid @enderror"
                                    placeholder="Ej: Toyota" value="{{ old('nombre') }}" maxlength="100" autofocus>
                            </div>
                            @error('nombre')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Descripción <span class="text-muted font-weight-normal">(opcional)</span></label>
                            <div class="flex items-stretch w-full mt-1">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-align-left"></i></span>
                                <input type="text" name="descripcion"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                    placeholder="Ej: Marca japonesa de vehículos"
                                    value="{{ old('descripcion') }}" maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="rd-btn rd-btn-default" data-modal-dismiss="modal">Cancelar</button>
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn">
                            <i class="fas fa-check"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ==================== MODAL EDITAR ==================== --}}
    <div class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/60 p-4" id="modalEditar" tabindex="-1" aria-labelledby="modalEditarLabel" aria-hidden="true">
        <div class="relative w-full w-full max-w-2xl flex items-center justify-center min-h-full">
            <div class="relative w-full rd-card" style="border-radius:12px;border:1px solid #e5e7eb;">
                <div class="modal-header" style="border-bottom:1px solid #e5e7eb;">
                    <h5 class="modal-title rd-title-sm" id="modalEditarLabel">
                        <i class="fas fa-edit mr-2" style="color:var(--color-primary)"></i>Editar Marca
                    </h5>
                    <button type="button" class="rounded-lg p-2 text-slate-500 hover:bg-slate-100" data-modal-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form id="formEditar" action="" method="POST" class="rd-prevent-double-submit">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="font-weight-bold">Nombre</label>
                            <div class="flex items-stretch w-full mt-1">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-tag"></i></span>
                                <input type="text" id="editNombre" name="nombre"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                    placeholder="Nombre de la marca" maxlength="100">
                            </div>
                        </div>
                        <div class="form-group mb-0">
                            <label class="font-weight-bold">Descripción <span class="text-muted font-weight-normal">(opcional)</span></label>
                            <div class="flex items-stretch w-full mt-1">
                                <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-align-left"></i></span>
                                <input type="text" id="editDescripcion" name="descripcion"
                                    class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                    placeholder="Descripción de la marca" maxlength="255">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:1px solid #e5e7eb;">
                        <button type="button" class="rd-btn rd-btn-default" data-modal-dismiss="modal">Cancelar</button>
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn" style="color:white;">
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
    // ── Utilidad: mostrar toast de éxito ──────────────────────────────
    function toastExito(mensaje) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: mensaje,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    // ── Utilidad: mostrar errores de validación dentro del modal ──────
    function mostrarErrores(errors, formId) {
        // Limpia errores anteriores
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
        const form = this;
        const data = new FormData(form);
        const btnSubmit = form.querySelector('.rd-submit-btn');
        const originalHtml = btnSubmit.innerHTML;

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: data,
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                $('#modalCrear').modal('hide');
                form.reset();
                // Agregar fila a la tabla
                agregarFilaTabla(res.marca);
                toastExito(res.message);
            } else {
                mostrarErrores(res.errors ?? {}, '#formCrear');
            }
        })
        .catch(() => mostrarErrores({ nombre: ['Error inesperado, intente de nuevo.'] }, '#formCrear'))
        .finally(() => {
            // Restaurar estado del botón
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalHtml;
        });
    });

    // ── EDITAR ────────────────────────────────────────────────────────
    document.querySelectorAll('.btn-abrir-editar').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('editNombre').value       = this.dataset.nombre;
            document.getElementById('editDescripcion').value  = this.dataset.descripcion === 'Ninguna' ? '' : this.dataset.descripcion;
            document.getElementById('formEditar').action      = `/admin/transporte/maestros/bus_marcas/${this.dataset.id}`;
            document.getElementById('formEditar').dataset.id  = this.dataset.id;
        });
    });

    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const data = new FormData(form);
        data.append('_method', 'PUT');
        const btnSubmit = form.querySelector('.rd-submit-btn');
        const originalHtml = btnSubmit.innerHTML;

        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: data,
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                $('#modalEditar').modal('hide');
                // Actualizar fila existente
                actualizarFilaTabla(res.marca);
                toastExito(res.message);
            } else {
                mostrarErrores(res.errors ?? {}, '#formEditar');
            }
        })
        .catch(() => mostrarErrores({ nombre: ['Error inesperado, intente de nuevo.'] }, '#formEditar'))
        .finally(() => {
            // Restaurar estado del botón
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = originalHtml;
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

            const form   = button.closest('form');
            const url    = form.action;
            const method = form.querySelector('[name="_method"]')?.value ?? 'POST';

            fetch(url, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                body: new FormData(form),
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    // Recargar solo la fila cambiando el badge y los botones
                    const fila = button.closest('tr');
                    fila.remove(); // más simple: si filtra por estado activo/inactivo, la fila desaparece igual
                    toastExito(res.message);
                }
            });
        });
    }

    // ── Helpers DOM ───────────────────────────────────────────────────
    let contadorFilas = {{ $marcas->total() }};

    function agregarFilaTabla(marca) {
        contadorFilas++;
        const tbody = document.querySelector('.rd-table tbody');
        // Quita el "No hay marcas" si existe
        const vacio = tbody.querySelector('td[colspan]');
        if (vacio) vacio.closest('tr').remove();

        const descripcion = marca.descripcion || 'Ninguna';
        const fila = `
            <tr data-id="${marca.id}">
                <td class="text-center">${contadorFilas}</td>
                <td class="text-center">${marca.nombre}</td>
                <td class="text-center">${descripcion}</td>
                <td class="text-center"><span class="rd-badge rd-badge-success">Activo</span></td>
                <td class="text-center">
                    <div class="rd-action-group">
                        <button type="button" class="rd-action btn-abrir-editar"
                            data-id="${marca.id}"
                            data-nombre="${marca.nombre}"
                            data-descripcion="${descripcion}"
                            data-modal-toggle="modal" data-target="#modalEditar"
                            title="Editar">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="/admin/transporte/maestros/bus_marcas/${marca.id}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rd-action rd-btn-danger"
                                onclick="confirmAccion(event, this, 'inactivar', 'marca')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>`;
        tbody.insertAdjacentHTML('beforeend', fila);
        // Re-bindear el nuevo botón de editar
        tbody.querySelector(`tr[data-id="${marca.id}"] .btn-abrir-editar`)
            .addEventListener('click', function() {
                document.getElementById('editNombre').value      = this.dataset.nombre;
                document.getElementById('editDescripcion').value = this.dataset.descripcion === 'Ninguna' ? '' : this.dataset.descripcion;
                document.getElementById('formEditar').action     = `/admin/transporte/maestros/bus_marcas/${this.dataset.id}`;
            });
    }

    function actualizarFilaTabla(marca) {
        const fila = document.querySelector(`tr[data-id="${marca.id}"]`);
        if (!fila) return;
        const descripcion = marca.descripcion || 'Ninguna';
        fila.cells[1].textContent = marca.nombre;
        fila.cells[2].textContent = descripcion;
        // Actualizar data-* del botón editar para la próxima vez
        const btnEditar = fila.querySelector('.btn-abrir-editar');
        if (btnEditar) {
            btnEditar.dataset.nombre      = marca.nombre;
            btnEditar.dataset.descripcion = descripcion;
        }
    }

    // ── Toggle estado ─────────────────────────────────────────────────
    document.getElementById('estadoToggle').addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        params.set('estado', this.checked ? 1 : 0);
        window.location.href = "{{ route('admin.transporte.maestros.bus_marcas.index') }}?" + params.toString();
    });
    </script>
@endpush