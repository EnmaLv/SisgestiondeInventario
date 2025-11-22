<div class="rd-wrapper">
    <div class="rd-grid">
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
                                <strong>{{ ucfirst($notification['type'] ?? 'Info') }}:</strong>
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
                            placeholder="Nombre, apellido o PNF" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>

                    <div class="rd-export-group">
                        <button class="rd-btn rd-btn-success" title="Exportar Excel"><i class="fas fa-file-excel"></i>
                            Excel</button>
                        <button class="rd-btn rd-btn-danger" title="Exportar PDF"><i class="fas fa-file-pdf"></i>
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
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="rd-filter-input" />
                        </div>
                        <div class="rd-filter-row rd-filter-actions">
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
                                            <a class="rd-action" href="#" title="Ver"><i
                                                    class="fas fa-eye"></i></a>
                                            <a class="rd-action" href="#" title="Editar"><i
                                                    class="fas fa-edit"></i></a>
                                            <form action="#" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="rd-action rd-action-danger" title="Eliminar"
                                                    type="submit"><i class="fas fa-trash"></i></button>
                                            </form>
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

        .rd-toast-error {
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
        }

        .rd-filters-form {
            display: flex;
            gap: 12px;
            align-items: flex-end;
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
    </style>
@endpush

@push('js')
    <script>
        // ==== Livewire notifications (safe + debounce) ====
        document.addEventListener('livewire:load', function() {
            let notificationTimer = null;

            Livewire.on('notify-saved', () => {
                // showNotification control es manejado por el componente
                // pero nos aseguramos de ocultarlo si no se hace desde servidor
                if (notificationTimer) clearTimeout(notificationTimer);
                notificationTimer = setTimeout(() => {
                    Livewire.emit('hide-notification'); // si quieres manejarlo desde componente
                }, 3000);
            });
        });

        // ===== Mantener foco en cédula con seguridad (solo si existe) =====
        (function focusCedula() {
            const ced = document.getElementById('cedula');
            if (!ced) return;
            ced.focus({
                preventScroll: true
            });
            // volver a enfocar cuando el usuario clickee por fuera del contenido principal
            const root = document.querySelector('.content-wrapper') || document.body;
            root.addEventListener('click', () => ced.focus({
                preventScroll: true
            }));
            window.addEventListener('focus', () => ced.focus({
                preventScroll: true
            }));
        })();

        // ===== Fecha max/logic =====
        (function dateLimits() {
            const desde = document.getElementById('fecha_desde');
            const hasta = document.getElementById('fecha_hasta');
            if (!desde || !hasta) return;
            const hoy = new Date().toISOString().split('T')[0];
            desde.max = hoy;
            hasta.max = hoy;

            desde.addEventListener && desde.addEventListener('change', function() {
                hasta.min = this.value || '';
                if (hasta.value && hasta.value < this.value) hasta.value = this.value;
            });
            hasta.addEventListener && hasta.addEventListener('change', function() {
                if (desde.value && this.value < desde.value) desde.value = this.value;
            });
        })();
    </script>
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

        //Focus al input
        inputCedula.focus();

        //escuchar el click en cualquier parte del documento para no perder el focus
        document.querySelector('.content-wrapper').addEventListener('click', function() {
            inputCedula.focus();
        });

        //Re-enfocar si la ventana vuelve a estar activa (ej. alt-tab y volver)
        window.addEventListener('focus', function() {
            inputCedula.focus();
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
    </script>
@endpush

@push('css')
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .fade-in {
            animation: fade-in 0.5s ease-in-out;
        }

        /* Fondo transparente y sin borde en el contenedor */
        #example1_wrapper .dt-buttons {
            background-color: transparent;
            box-shadow: none;
            border: none;
            display: flex;
            justify-content: center;
            /* Centrar los botones */
            gap: 10px;
            /* Espaciado entre botones */
            margin-bottom: 15px;
            /* Separar botones de la tabla */
        }

        /* Estilo personalizado para los botones */
        #example1_wrapper .btn {
            color: #fff;
            /* Color del texto en blanco */
            border-radius: 4px;
            /* Bordes redondeados */
            padding: 5px 15px;
            /* Espaciado interno */
            font-size: 14px;
            /* TamaÃ±o de fuente */
        }

        /* Colores por tipo de botÃ³n */
        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-info {
            background-color: #17a2b8;
            border: none;
        }

        .btn-warning {
            background-color: #ffc107;
            color: #212529;
            border: none;
        }

        .btn-default {
            background-color: #6e7176;
            color: #212529;
            border: none;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
        }

        .minimalist-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .minimalist-item:last-child {
            border-bottom: none;
        }

        .minimalist-info {
            flex: 1;
        }

        .minimalist-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .minimalist-details {
            display: flex;
            font-size: 0.9rem;
            color: #7f8c8d;
        }

        .minimalist-details span {
            margin-right: 15px;
        }

        .minimalist-status {
            font-weight: 600;
        }

        .minimalist-status.success {
            color: #27ae60;
        }

        .minimalist-status.error {
            color: #e74c3c;
        }

        .minimalist-status.warning {
            color: #f39c12;
        }
    </style>
@endpush
