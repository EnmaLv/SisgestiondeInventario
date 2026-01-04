<div class="rd-wrapper">
    <!-- Formulario de registro diario -->
    <div class="rd-grid">
        <!-- Left: Cedula buscador -->
        <div class="rd-card rd-card-search">
            <div class="rd-card-headerr">
                <h2 class="rd-title">Registro Diario</h2>
                <p class="rd-sub">Escanea el código de barras del carnet para registrar la entrada</p>
            </div>

            <div class="rd-card-body">
                <form wire:submit.prevent="save" class="rd-search-form" autocomplete="off">
                    @csrf
                    <div style="display: flex;gap: 10px;align-items: center; justify-content: space-between;">
                        <label for="cedula" class="sr-only">Cédula</label>
                        <input type="tel" id="cedula" wire:model.defer="cedula" @disabled(!$receta_diario || !$enableInput)
                            class="rd-input @error('cedula') rd-input-error @enderror" placeholder="Ej: 12345678"
                            maxlength="8" inputmode="numeric" autofocus />

                        <button class="rd-btn rd-btn-primary" type="submit" @disabled(!$enableInput)
                         aria-label="Buscar"  @if(!$enableInput) style="opacity: .8; cursor: not-allowed;" @endif>Buscar</button>
                    </div>
                    <small class="text-muted d-block mt-1">
                        Solo números, máximo 8 dígitos. <br />
                        También puedes escribir el número manualmente si es necesario.
                    </small>

                    @error('cedula')
                        <div class="rd-error mt-2">{{ $message }}</div>
                    @enderror
                </form>

                <!-- Notificación como toast (Livewire controla showNotification) -->
                <div class="rd-toast-holder">
                    @if ($showNotification && isset($notification['message']))
                        <div class="rd-toast rd-toast-{{ $notification['type'] ?? 'info' }}" role="status"
                            aria-live="polite">
                            <div class="rd-toast-body">
                                @php
                                    if ($notification['type'] == 'success') {
                                        $type = 'exito';
                                    } else {
                                        $type = 'error';
                                    }
                                @endphp
                                <strong>{{ ucfirst($type) }}</strong>
                                <span>{{ $notification['message'] }}</span>
                            </div>
                            <button class="rd-toast-close" aria-label="Cerrar"
                                wire:click="$set('showNotification', false)">×</button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rd-card-footer text-center">
                <small class="text-muted">Mantén tu carnet a mano</small>
            </div>
        </div>

        <!-- Right: Buscador, filtros y tabla -->
        <div class="rd-card rd-card-list" style="height: 100%">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Registros</h3>
                    <p class="rd-sub-sm">Últimos movimientos del día</p>
                </div>

                <div class="rd-actions">
                    <form action="{{ route('admin.movimientos.registro_diario.index') }}" method="GET"
                        class="rd-search-inline" role="search">
                        <input name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Nombre, apellido o PNF" id="search" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if ($showBtnFinalizar)
                        <button class="rd-btn rd-btn-alter" title="Finalizar Dia" id="finalizarDia">
                            <i class="fas fa-sun"></i>
                            Finalizar Dia
                        </button>  
                    @endif
                    <!-- Modal Finalizar Dia -->
                    <div wire:ignore.self class="modal fade" id="modalFinalizarDia" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md"> <div class="modal-content rd-card border-0">
                                <div class="modal-header border-bottom-0 pt-4 px-4">
                                    <h5 class="rd-title-sm" style="font-size: 1.25rem;">
                                        <i class="fas fa-file-signature me-2" style="color: var(--color-tertiary);"></i>
                                        Reporte de Cierre de Jornada
                                    </h5>
                                </div>
                                
                                <form wire:submit.prevent="finalizarDia" id="formFinalizarDia">
                                    <div class="modal-body px-4">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="rd-label mb-2">Fecha de Cierre</label>
                                                <div class="rd-input-group bg-light">
                                                    <span><i class="fas fa-calendar-day"></i></span>
                                                    <input wire:model="fecha" type="date" class="form-control rd-input" id="fechaCierre" readonly >
                                                </div>
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="rd-label mb-2">Cantidad Sobrante</label>
                                                <div class="rd-input-group">
                                                    <span><i class="fas fa-utensils"></i></span>
                                                    <input wire:model="sobrante" type="number" class="form-control rd-input" id="cantidadSobrante" placeholder="0" min="0" required readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="rd-label mb-2">Motivo del Cierre</label>
                                            <select wire:model="motivo" class="form-select rd-filter-input w-100" id="motivoCierre" >
                                                <option value="" selected >Seleccione el motivo...</option>
                                                <option value="Falta de insumos">Falta de insumos</option>
                                                <option value="Baja asistencia de Personal">Baja asistencia de personal</option>
                                                <option value="Emergencia / Contingencia">Emergencia / Contingencia</option>
                                                <option value="Suspensión de Actividades">Suspensión de Actividades (Paros/Asambleas)</option>
                                            </select>
                                            @error('motivo')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="rd-label mb-2">Acción Tomada con el Sobrante</label>
                                            <div class="rd-input-group">
                                                <span><i class="fas fa-hand-holding-heart"></i></span>
                                                <input wire:model="accion" type="text" class="form-control rd-input" id="accionTomada" placeholder="Ej: Donación, refrigeración, descarte..." >
                                            </div>
                                            @error('accion')
                                                <div class="text-danger small">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="modal-footer border-top-0 pb-4 px-4 gap-2">
                                        <button type="button" class="rd-btn rd-btn-default" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="rd-btn rd-btn-primary" id="btnConfirmarCierre">
                                            <i class="fas fa-save me-1"></i> Guardar y Finalizar
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    

                    <div class="rd-export-group">
                        <a href="{{ route('admin.movimientos.registro_diario.export_excel', request()->only(['buscar', 'fecha_desde', 'fecha_hasta'])) }}"
                            class="rd-btn rd-btn-success" title="Exportar Excel"><i class="fas fa-file-excel"></i>
                            Excel</a>

                        <button class="rd-btn rd-btn-danger" title="Exportar PDF" id="pdfBtn"><i
                                class="fas fa-file-pdf"></i>
                            PDF</button>
                    </div>
                </div>
            </div>

            <div class="collapse" id="filters">
                <div class="rd-filters">
                    <form action="{{ route('admin.movimientos.registro_diario.index') }}" method="GET"
                        class="rd-filters-form">
                        <div class="rd-filter-row">
                            <label>Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="rd-filter-input" max="{{ date('Y-m-d') }}"value="{{ request("fecha_desde") }}"/>
                        </div>
                        <div class="rd-filter-row">
                            <label>Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="rd-filter-input"
                                max="{{ date('Y-m-d') }}" value="{{ request("fecha_hasta") }}"/>
                        </div>
                        <div class="rd-filter-actions">
                            <button class="rd-btn rd-btn-primary" type="submit">Aplicar</button>
                            <button type="button" class="rd-btn rd-btn-default"
                                onclick="document.getElementById('fecha_desde').value=''; document.getElementById('fecha_hasta').value='';">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="rd-card-body rd-list-body">
                <div class="rd-list">
                    <table class="rd-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nombre</th>
                                <th>Apellido</th>
                                <th>PNF</th>
                                <th>Registrado</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $registro)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $registro->nombre_persona }}</td>
                                    <td>{{ $registro->apellido_persona }}</td>
                                    <td>{{ $registro->nombre_pnf }}</td>
                                    <td>{{ \Carbon\Carbon::parse($registro->fecha_regis_diario_c)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="rd-badge rd-badge-success">Aprobado</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="rd-action-group">
                                            <a class="rd-action"
                                                href="{{ route('admin.movimientos.registro_diario.show', $registro->id) }}"
                                                title="Ver"><i class="fas fa-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">No hay registros</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Paginación (si aplica) -->
                <div class="mt-3 d-flex justify-content-center">
                    {{ $data->onEachSide(1)->links('components.pagination-livewire') }}
                </div>
                {{-- <livewire:paginacion-reusable /> --}}
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        /* Reset pequeño para este componente */
        .rd-wrapper {
            padding: 18px 12px;
            min-height: calc(100vh - 240px);
        }

        .rd-grid {
            display: grid;
            grid-template-columns: 360px 1fr;
            gap: 18px;
            align-items: start;
        }

        /* Card base */
        .rd-card {
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 6px 18px rgba(31, 41, 55, 0.06);
            overflow: hidden;
            border: 1px solid #eef2f6;
        }

        .rd-card-headerr {
            padding: 18px 20px;
            border-bottom: 1px solid #f2f6f9;
        }

        .rd-card-body {
            padding: 18px 20px;
        }

        .rd-card-footer {
            padding: 12px 20px;
            border-top: 1px solid #f2f6f9;
            background: #fafbfd;
        }

        /* Search card */
        .rd-card-search .rd-title {
            margin: 0;
            font-size: 20px;
            color: #0f172a;
        }

        .rd-card-search .rd-sub {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        .rd-search-form {
            margin-top: 12px;
            width: 100% !important;
        }

        .rd-input-group {
            display: flex;
            gap: 10px;
            align-items: center;
            width: 100%;
        }

        .rd-input {
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 16px;
            outline: none;
            transition: border .12s;

            &:focus-within{
                border: 1px solid var(--color-primary);
            }
        }



        /* Toast/Aviso del input de carnet */
        .rd-toast-holder {
            position: relative;
            margin-top: 14px;
        }

        .rd-toast {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 14px;
            border-radius: 10px;
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
        }

        .rd-toast-body {
            display: flex;
            gap: 10px;
            align-items: center;
            color: #0f172a;
        }

        .rd-toast-close {
            background: transparent;
            border: none;
            font-size: 20px;
            line-height: 1;
            cursor: pointer;
            color: #374151;
        }

        .rd-toast-success {
            background: linear-gradient(90deg, #ecfeff, #f0fdf4);
            border: 1px solid #d1fae5;
        }

        .rd-toast-info {
            background: linear-gradient(90deg, #eef2ff, #f8fafc);
            border: 1px solid #bfdbfe;
        }

        .rd-toast-danger {
            background: linear-gradient(90deg, #fff1f2, #fff7f7);
            border: 1px solid #fecaca;
        }

        /* List card */
        .rd-card-list .rd-header-space {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 20px;
            gap: 12px;
        }

        .rd-title-sm {
            margin: 0;
            font-size: 16px;
            font-weight: 700;
        }

        .rd-sub-sm {
            margin: 0;
            color: #6b7280;
            font-size: 13px;
        }


        .rd-action-group {
            display: flex;
            gap: 8px;
            justify-content: center;
            align-items: center;
        }

        .rd-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            border: 1px solid transparent;
            padding: 7px 9px;
            border-radius: 8px;
            color: #374151;
        }

        /* Responsive */
        @media (max-width: 980px) {
            .rd-grid {
                grid-template-columns: 1fr;
            }

            .rd-card-search {
                order: 2;
            }

            .rd-card-list {
                order: 1;
            }

            .rd-search-input {
                min-width: 120px;
            }
        }


        .rd-alert {
            display: flex;
            align-items: flex-start;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1.5rem;
            border: 1px solid;
            transition: all 0.3s ease;
        }

        .rd-alert-error {
            background-color: #fef2f2;
            border-color: #fecaca;
            border-left: 4px solid #ef4444;
        }

        .rd-alert-icon {
            flex-shrink: 0;
            margin-right: 0.75rem;
            display: flex;
            align-items: flex-start;
        }

        .rd-alert-icon svg {
            width: 1.5rem;
            height: 1.5rem;
        }

        .rd-alert-error .rd-alert-icon svg {
            color: #ef4444;
        }

        .rd-alert-content {
            flex: 1 1 0%;
        }

        .rd-alert-title {
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .rd-alert-list {
            list-style-type: disc;
            padding-left: 1.25rem;
            margin-top: 0.5rem;
        }

        .rd-alert-error .rd-alert-title {
            color: #991b1b;
        }

        .rd-alert-error .rd-alert-list {
            color: #b91c1c;
        }
    </style>
@endpush



@push('js')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal', data => {
                Swal.fire({
                    title: data.title,
                    text: data.text,
                    icon: data.icon,
                    confirmButtonText: 'Aceptar'
                });
            });
        });
        document.addEventListener('livewire:initialized', () => {
            let isNotificationVisible = false;
            let hideTimeout = null;

            @this.on('notify-saved', () => {
                // Si ya hay una notificación visible, no hacer nada
                if (isNotificationVisible) {
                    return;
                }

                isNotificationVisible = true;

                // Ocultar después de 3 segundos
                hideTimeout = setTimeout(() => {
                    @this.set('showNotification', false);
                    isNotificationVisible = false;
                }, 3000);
            });

            // Limpiar el estado cuando la notificación se oculta
            @this.on('notify-hidden', () => {
                isNotificationVisible = false;
                if (hideTimeout) {
                    clearTimeout(hideTimeout);
                }
            });

            Livewire.on('notify-inventario', () => {
                Swal.fire({
                    icon: 'error',
                    title: 'Inventario insuficiente',
                    text: @this.get('alertInventario'),
                });
            });

            Livewire.on('notify-limite', () => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Límite alcanzado',
                    text: @this.get('alertLimite'),
                });
            });
        });

        // Evento para el input de cédula
        const inputCedula = document.getElementById('cedula');
        const inputSearch = document.getElementById('search');

        //No cargar el script si esta en blur
        const bgBlur = document.querySelector(".rd-blur");
        if (!bgBlur) {
            if (inputCedula) {
                let blockCedulaFocus = false;
    
                const focusCedulaSafely = () => {
                    const isModalOpen = document.querySelector('#modalFinalizarDia.show');
                    if (blockCedulaFocus || isModalOpen) return;
                    inputCedula.focus({
                        preventScroll: true
                    });
                };
    
                // Focus inicial
                focusCedulaSafely();
    
                if (inputSearch) {
                    inputSearch.addEventListener('focus', () => {
                        blockCedulaFocus = true;
                    });
                    inputSearch.addEventListener('blur', () => {
                        blockCedulaFocus = false;
                        focusCedulaSafely();
                    });
                }
    
                // Escuchar click en el contenedor principal
                const root = document.querySelector('.content-wrapper') || document.body;
                root.addEventListener('click', (event) => {
                    if (inputSearch && (event.target === inputSearch || inputSearch.contains(event.target))) {
                        return;
                    }
                    focusCedulaSafely();
                });
    
                // Re-enfocar cuando la ventana retorne, respetando el focus del search
                window.addEventListener('focus', () => {
                    focusCedulaSafely();
                });
                //Limites del input
                inputCedula.addEventListener('input', function(e) {
                    // Remover caracteres no numéricos
                    this.value = this.value.replace(/[^0-9]/g, '');
        
                    // Limitar a 8 dígitos máximo
                    if (this.value.length > 8) {
                        this.value = this.value.slice(0, 8);
                    }
                });
            }
        }


        const finalizarModal = new bootstrap.Modal(document.getElementById('modalFinalizarDia'));
        document.addEventListener('DOMContentLoaded', ()=>{
            //Script para el boton de finalizarDia
            const finalizarBtn = document.querySelector('#finalizarDia')
            finalizarBtn.addEventListener('click', function() {
                //Mostramos una alerta de confirmacion
                Swal.fire({
                    title: '¿Estas seguro de finalizar el dia?',
                    icon: 'warning',
                    text: 'Al finalizar el dia, no podras registrar mas estudiantes',
                    showCancelButton: true,
                    confirmButtonText: 'Si, finalizar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        //emitimos el evento
                        @this.openModal();  
                    }
                });
            })
        })


        document.querySelector('button[data-bs-dismiss="modal"]').addEventListener('click', function() {
            finalizarModal.hide();
        }); 

        // Escuchamos el evento que viene del servidor (PHP)
        document.addEventListener('livewire:initialized', () => {
            @this.on('openModal', () => {
                // Mostramos la modal de forma segura una vez el DOM está listo
                finalizarModal.show();
            });
            
            @this.on('finalizar-dia-guardado', (event) => {
                let message = event[0]
                finalizarModal.hide();
                Swal.fire({
                    icon: message.icon,
                    title: message.title,
                    text: message.text,
                    timer: 3000,
                    showConfirmButton: false
                });
            });
        });



        //Script para mostrar el PdfGeneratorUtil
        const pdfBtn = document.querySelector('#pdfBtn');
        const pdfRoute = `{{ route('admin.movimientos.registro_diario.export_pdf') }}`;
        if (pdfBtn) {
            pdfBtn.addEventListener('click', function() {

                const params = new URLSearchParams(window.location.search);
                const fechaDesde = params.get('fecha_desde') ?? "";
                const fechaHasta = params.get('fecha_hasta') ?? "";

                const url = `${pdfRoute}?fecha_desde=${fechaDesde}&fecha_hasta=${fechaHasta}`;
                window.open(url, '_blank');
            });
        }
    </script>
@endpush
