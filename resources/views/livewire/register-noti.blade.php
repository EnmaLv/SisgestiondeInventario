<div class="rd-wrapper">
    <!-- Formulario único para desayuno + cantidad -->
    <div class="rd-card rd-card-desayuno mb-4 col-md-12 text-center mx-auto">
        <div class="rd-card-header">
            <h2 class="rd-title">Desayuno del día</h2>
            <p class="rd-sub">Selecciona el desayuno de hoy y registra la cantidad servida</p>
        </div>

        <div class="rd-card-body">
            <form wire:submit.prevent="saveDesayuno" class="rd-search-form" autocomplete="off">
                @csrf

                <div class="row g-3">

                    <!-- SELECT: Desayuno -->
                    <div class="col-md-6">
                        <div class="rd-input-group">
                            <label for="desayuno" class="sr-only">Desayuno</label>

                            <select id="desayuno" wire:model="desayuno_del_dia"
                                class="rd-input @error('desayuno_del_dia') rd-input-error  @enderror"
                                @disabled($desayuno_del_dia)>
                                <option value="" selected disabled>Seleccione una opción</option>
                                @foreach ($comidas as $comida)
                                    <option value="{{ $comida->id }}">{{ $comida->nombre }}</option>
                                @endforeach
                            </select>


                        </div>
                    </div>

                    <!-- INPUT: Cantidad -->
                    <div class="col-md-6">
                        <div class="rd-input-group">
                            <label for="cantidad_servido" class="sr-only">Cantidad servida</label>

                            <input type="number" name="cantidad_servido" id="cantidad_servido"
                                wire:model="cantidad_servido"
                                class="rd-input @error('cantidad_servido') rd-input-error @enderror" placeholder="Cant."
                                @disabled($desayuno_del_dia) min="0" />


                        </div>
                    </div>


                    <div class="col-md-12 d-flex mt-2 justify-content-end">
                        <button class="rd-btn rd-btn-primary w-90" type="submit" aria-label="Guardar desayuno"
                            @disabled($desayuno_registrado)
                            style="@if ($desayuno_registrado) opacity: 0.5; cursor: not-allowed; @endif">
                            Guardar Desayuno
                        </button>
                    </div>
                    @error('cantidad_servido')
                        <div class="rd-error mt-2 mr-2">No hay suficiente stock</div>
                    @enderror
                    @error('desayuno_del_dia')
                        <div class="rd-error mt-2">Registre los ingredientes para continuar</div>
                    @enderror

                    @if (!$horarioPermitido)
                        <div
                            style="display: flex; align-items: center; padding: 1rem; margin-top: 1rem; background-color: #fef2f2; border-left: 4px solid #ef4444; border-radius: 0.375rem; margin-inline: auto;">
                            <div style="flex-shrink: 0;">
                                <svg style="width: 1.5rem; height: 1.5rem; color: #ef4444;" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div style="margin-left: 0.75rem;">
                                <h3 style="margin: 0; font-size: 0.875rem; font-weight: 500; color: #991b1b;">Horario no
                                    permitido</h3>
                                <div style="margin-top: 0.25rem; font-size: 0.875rem; color: #b91c1c;">
                                    <p style="margin: 0.25rem 0;">El registro de comedor solo está disponible de 12:00
                                        AM a 12:00 PM.</p>
                                    <p style="margin: 0.25rem 0 0;">Por favor, inténtalo de nuevo dentro del horario
                                        establecido.</p>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($errors->has('hora'))
                        <div class="rd-alert rd-alert-error" role="alert">
                            <div class="rd-alert-icon">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="rd-alert-content">
                                <h4 class="rd-alert-title">¡Error!</h3>
                                    <ul class="rd-alert-list">
                                        @foreach ($errors->all() as $error)
                                            <h4>{{ $error }}</h3>
                                        @endforeach
                                    </ul>
                            </div>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <div class="rd-card-footer">
            <small class="text-muted">Una vez guardado, no se podrá modificar</small>
        </div>
    </div>


    @if (!$desayuno_del_dia)
        <p>Registre un desayuno para continuar</p>
    @elseif (!$horarioPermitido)
        <p></p>
    @endif
    <!-- Formulario de registro diario -->
    <div class="rd-grid" @if (!$desayuno_del_dia || !$horarioPermitido) style="display: none;" @endif>

        <!-- Left: Cedula buscador -->
        <div class="rd-card rd-card-search">
            <div class="rd-card-header">
                <h2 class="rd-title">Registro Diario</h2>
                <p class="rd-sub">Busca rápido por cédula y registra la entrada</p>
            </div>

            <div class="rd-card-body">
                <form wire:submit.prevent="save" class="rd-search-form" autocomplete="off">
                    @csrf
                    <div class="rd-input-group">
                        <label for="cedula" class="sr-only">Cédula</label>
                        <input type="tel" id="cedula" wire:model.defer="cedula"
                            class="rd-input @error('cedula') rd-input-error @enderror" placeholder="Ej: 12345678"
                            maxlength="8" inputmode="numeric" autofocus />
                        <button class="rd-btn rd-btn-primary" type="submit" aria-label="Buscar">Buscar</button>
                    </div>

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

            <div class="rd-card-footer">
                <small class="text-muted">Mantén tu cédula a mano</small>
            </div>
        </div>





        <!-- Right: Buscador, filtros y tabla -->
        <div class="rd-card rd-card-list">
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
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i
                                class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>

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
                            <input type="date" name="fecha_desde" id="fecha_desde" class="rd-filter-input" />
                        </div>
                        <div class="rd-filter-row">
                            <label>Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="rd-filter-input"
                                max="{{ date('Y-m-d') }}" />
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
                <div class="rd-pagination">
                    {{ $data->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('css')
    <style>
        /* Card especial para el desayuno */
        .rd-card-desayuno .rd-title {
            margin: 0;
            font-size: 20px;
            color: #0f172a;
        }

        .rd-card-desayuno .rd-sub {
            margin: 4px 0 0;
            color: #6b7280;
            font-size: 13px;
        }

        /* Ajusta el select al estilo general */
        .rd-card-desayuno select.rd-input {
            padding: 12px 14px;
            appearance: none;
            background-position: right 12px center;
            background-repeat: no-repeat;
            background-size: 14px;
        }

        /* Reset pequeño para este componente */
        .rd-wrapper {
            padding: 18px 12px;
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

        .rd-card-header {
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
            width: 100%;
        }

        .rd-input-group {
            display: flex;
            gap: 10px;
            align-items: center;
            width: 100%;
        }

        .rd-input {
            flex: 1;
            padding: 12px 14px;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 16px;
            outline: none;
            transition: box-shadow .12s;
        }

        .rd-input:focus {
            box-shadow: 0 6px 18px rgba(15, 23, 42, 0.06);
            border-color: #7c3aed;
        }

        .rd-input-error {
            border-color: #f43f5e;
        }

        .rd-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .rd-btn-primary {
            background: #4f46e5;
            color: #fff;
        }

        .rd-btn-success {
            background: #10b981;
            color: #fff;
        }

        /* Mantiene el mismo estilo al hacer hover para el botón de exportar Excel */
        .rd-btn-success:hover {
            background: #10b981;
            color: #fff;
        }

        .rd-btn-danger {
            background: #ef4444;
            color: #fff;
        }

        .rd-btn-default {
            background: #6b7280;
            color: #fff;
        }

        .rd-error {
            color: #b91c1c;
            font-size: 13px;
            text-align: center;
        }

        /* Toast */
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

        .rd-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rd-search-inline {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .rd-search-input {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #e6eef6;
            min-width: 200px;
        }

        .rd-icon-btn {
            background: transparent;
            border: 1px solid #e6eef6;
            padding: 8px 10px;
            border-radius: 8px;
            cursor: pointer;
            color: #374151;
        }

        .rd-export-group {
            display: flex;
            gap: 8px;
        }

        .rd-filters {
            padding: 12px;
            background: #fbfdff;
            border-top: 1px solid #f3f6fb;
            box-shadow: inset 0 6px 10px rgba(101, 114, 151, 0.1);
        }

        .rd-filters-form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            justify-content: center;
            flex-wrap: wrap;
        }

        .rd-filter-row {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .rd-filter-input {
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #e6eef6;
        }

        /* Table */
        .rd-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .rd-table thead th {
            background: #f8fafc;
            padding: 12px 10px;
            text-align: left;
            color: #374151;
            font-weight: 700;
            border-bottom: 1px solid #eef2f6;
        }

        .rd-table tbody td {
            padding: 12px 10px;
            border-bottom: 1px solid #f6f8fb;
            vertical-align: middle;
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

        .rd-action:hover {
            background: #f3f4f6;
        }

        .rd-action-danger {
            background: #fff7f7;
            border: 1px solid #fee2e2;
            color: #dc2626;
        }

        .rd-badge {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 13px;
        }

        .rd-badge-success {
            background: linear-gradient(90deg, #ecfdf5, #e6fffa);
            color: #065f46;
            border: 1px solid #bbf7d0;
        }

        .rd-pagination {
            padding: 12px 8px;
            display: flex;
            justify-content: center;
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

        /* small accessibility */
        .sr-only {
            position: absolute !important;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            border: 0;
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
        });

        // Evento para el input de cédula
        const inputCedula = document.getElementById('cedula');
        const inputSearch = document.getElementById('search');

        if (inputCedula) {
            let blockCedulaFocus = false;

            const focusCedulaSafely = () => {
                if (blockCedulaFocus) return;
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
        }

        //Limites del input
        inputCedula.addEventListener('input', function(e) {
            // Remover caracteres no numéricos
            this.value = this.value.replace(/[^0-9]/g, '');

            // Limitar a 8 dígitos máximo
            if (this.value.length > 8) {
                this.value = this.value.slice(0, 8);
            }
        });

        const desdeDate = document.getElementById('fecha_desde');
        const hastaDate = document.getElementById('fecha_hasta');

        // Fecha actual (máximo permitido)
        const fechaActual = new Date().toISOString().split('T')[0];

        // Establecer máximo hoy para ambos campos
        if (desdeDate) desdeDate.max = fechaActual;
        if (hastaDate) hastaDate.max = fechaActual;


        // Cuando cambie "desde", ajustar el mínimo de "hasta"
        if (desdeDate && hastaDate) {
            desdeDate.addEventListener('change', function() {
                if (!desdeDate.value) {
                    // Si se borra la fecha desde, quitamos la restricción mínima en hasta
                    hastaDate.min = '';
                    return;
                }

                // "hasta" no puede ser menor que "desde"
                hastaDate.min = desdeDate.value;

                if (hastaDate.value && hastaDate.value < desdeDate.value) {
                    hastaDate.value = desdeDate.value;
                }
            });

            // Cuando cambie "hasta", validar contra "desde"
            hastaDate.addEventListener('change', function() {
                if (!hastaDate.value || !desdeDate.value) {
                    return;
                }

                if (hastaDate.value < desdeDate.value) {
                    // Si el usuario pone una fecha hasta menor, movemos "desde" a esa fecha
                    desdeDate.value = hastaDate.value;
                }
            });
        }

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
