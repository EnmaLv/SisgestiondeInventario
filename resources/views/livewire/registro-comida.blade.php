 <div class="rd-wrapper">
    @include('components.alert')
    <!-- Formulario único para desayuno + cantidad -->
    <div class="rd-card rd-card-desayuno mb-4 col-md-12 text-center mx-auto">
        <div class="rd-card-headerr">
            <h2 class="rd-title">Desayuno del día</h2>
            <p class="rd-sub">Selecciona el desayuno de hoy y registra la cantidad servida</p>
        </div>
        <div class="rd-card-body">
            <form wire:submit.prevent="saveDesayuno" class="rd-search-form" autocomplete="off">
                @csrf
            {{-- Contenedor principal para todas las entradas --}}
                <div class="row g-3">

                    {{-- ITERACIÓN SOBRE LOS DESAYUNOS AGREGADOS --}}
                    @foreach ($desayunos_agregados as $index => $desayuno)
                        <div class="col-12 fade-in">
                            <div class="row g-3 align-items-center">
                                
                                {{-- SELECT: Desayuno (Columna 1: 50% del ancho) --}}
                                <div class="col-md-5">
                                    <div class="rd-input-group">
                                        <select
                                            wire:model.live="desayunos_agregados.{{ $index }}.receta_id"
                                            class="rd-input @error('desayunos_agregados.' . $index . '.receta_id') rd-input-error @enderror">
                                            <option value="">Seleccione una opción</option>
                                            @foreach ($comidas as $comida)
                                                <option value="{{ $comida->id }}">{{ $comida->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>

                                {{-- CONTENEDOR AGRUPADO: Cantidad + Eliminar (Columna 2: 50% del ancho) --}}
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center">
                                        
                                        <div class="rd-input-group mr-2">
                                            <input type="number"
                                                wire:model.live="desayunos_agregados.{{ $index }}.cantidad"
                                                class="rd-input @error('desayunos_agregados.' . $index . '.cantidad') rd-input-error @enderror"
                                                placeholder="Cant." min="1" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                        {{-- Separador entre filas --}}
                        @if(!$loop->last)
                            <div class="col-12"><hr class="my-3"></div> 
                        @endif
                    @endforeach


                    {{-- BOTONES DE ACCIÓN --}}
                    <div class="col-md-12 d-flex mt-4 justify-content-end align-items-center">
                        
                        {{-- Botón para agregar más --}}
                        <button type="button" wire:click="addDesayuno" style="@if ($desayuno_registrado) opacity: 0.5; cursor: not-allowed; @endif" class="rd-btn rd-btn-default mr-4" @disabled($desayuno_registrado)>
                            Agregar Desayuno
                        </button>

                        {{-- Botón principal de Guardar --}}
                        <button class="rd-btn rd-btn-primary w-90" type="submit" aria-label="Guardar desayunos"
                            @disabled($desayuno_registrado)
                            style="@if ($desayuno_registrado) opacity: 0.5; cursor: not-allowed; @endif">
                            Guardar Desayunos
                        </button>
                    </div>

                    <div class="rd-error mt-2" style="font-size: 1rem; text-align: left;">
                        {{-- Literalmente las seccionde los errores --}}
                        @error('desayunos_agregados.' . $index . '.receta_id') 
                            <span class="text-danger">{{ $message }}</span> 
                        @enderror
                        @error('desayunos_agregados.' . $index . '.cantidad') 
                            <span class="text-danger">{{ $message }}</span> 
                        @enderror
                        @error('cantidad_servido')
                            <p>Ingrese una cantidad valida</p>
                        @enderror
                        @error('general')
                            <p>{{ $message }}</p>
                        @enderror
                        @error('duplicado')
                            <p>{{ $message }}</p>
                        @enderror
                        @if ($showNotification && $notification['type'] == 'danger')
                            <div class="rd-error mt-3 text-center" style="font-size: 1rem;">
                                {{ 'No hay suficiente inventario para esta receta' }}
                            </div>
                        @endif
                    </div>

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

    <!-- Formulario de registro diario -->
    <div>

        <!-- Right: Buscador, filtros y tabla -->
        <div class="rd-card rd-card-list">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Registros De Comidas</h3>
                    <p class="rd-sub-sm">Últimos movimientos del día</p>
                </div>

                <div class="rd-actions">
                    <form action="" method="GET"
                        class="rd-search-inline" role="search">
                        <input name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Nombre de comida" id="search" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i
                                class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>

                </div>
            </div>

            <div class="collapse" id="filters">
                <div class="rd-filters">
                    <form action="" method="GET"
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
                                <th>Nombre Comida</th>
                                <th>Cantidad</th>
                                <th>Registrado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $registro)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td>{{ $registro->receta->nombre }}</td>
                                    <td>{{ $registro->cantidad_servido }}</td>
                                    <td>{{ \Carbon\Carbon::parse($registro->created_at)->format('d/m/Y') }}
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


        .rd-card-headerr {
            padding: 18px 20px;
            border-bottom: 1px solid #f2f6f9;
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
            width: 100%;
        }

        .rd-input {
            flex: 1;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 16px;
            outline: none;
            transition: box-shadow .12s;
        }


        .rd-input-error {
            border-color: #f43f5e;
        }


        .rd-btn-eliminar{
            border: 1px solid #d33a3a !important;
            background: transparent;
            &:hover{
                background: #d33a3a;
                color: white;
                transition: background .25s ease;

            }
        }



        .rd-error {
            color: #b91c1c;
            font-size: 13px;
            text-align: center;
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
            Livewire.on('swal', payload => {
                const data = Array.isArray(payload) ? payload[0] : payload;
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
