<div class="row">
    @if ($visibleModules['sucursales'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/sucursales') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/edificio.webp') }}" alt="Sucursales">
                    </div>
                    <h5>Sedes</h5>
                    <p>{{ $total_sucursales }} registradas</p>
                </div>
            </a>
        </div>
    @endif

    <!-- Categorías -->
    @if ($visibleModules['categorias'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/categorias') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/carpetas.webp') }}" alt="Categorías">
                    </div>
                    <h5>Categorías</h5>
                    <p>{{ $total_categorias }} activas</p>
                </div>
            </a>
        </div>
    @endif

    <!-- Productos -->
    @if ($visibleModules['productos'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/productos') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/paquete.webp') }}" alt="Productos">
                    </div>
                    <h5>Productos</h5>
                    <p>{{ $total_productos }} en inventario</p>
                </div>
            </a>
        </div>
    @endif

    <!-- Proveedores -->
    @if ($visibleModules['proveedores'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/proveedores') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/camion.webp') }}" alt="Proveedores">
                    </div>
                    <h5>Proveedores</h5>
                    <p>{{ $total_proveedores }} disponibles</p>
                </div>
            </a>
        </div>
    @endif

    <!-- Compras -->
    @if ($visibleModules['compras'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/movimientos/compras') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/lista-de-verificacion.webp') }}" alt="Compras">
                    </div>
                    <h5>Compras</h5>
                    <p>{{ $total_compras }} realizadas</p>
                </div>
            </a>
        </div>
    @endif

    <!-- Compras -->
    @if ($visibleModules['comidas'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/recetas') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/bandeja-de-comida.webp') }}" alt="Compras">
                    </div>
                    <h5>Comidas</h5>
                    <p>{{ $total_compras }} registradas</p>
                </div>
            </a>
        </div>
    @endif

    <!-- Productos por Vencer -->
    @if ($visibleModules['por_vencer'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/movimientos/lotes?filtro=por_vencer') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/notificaciones.webp') }}" alt="Por vencer">
                    </div>
                    <h5>Por Vencer</h5>
                    <p>{{ $total_lotes_por_vencer }} próximos a vencer</p>
                </div>
            </a>
        </div>
    @endif

    <!-- Lotes Vencidos -->
    @if ($visibleModules['por_vencer'] ?? false)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/movimientos/lotes?filtro=vencido') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/alarma.webp') }}" alt="Lotes vencidos">
                    </div>
                    <h5>Lotes Vencidos</h5>
                    <p>{{ $total_lotes_vencidos }} requieren atención</p>
                </div>
            </a>
        </div>
    @endif
</div>
<div class="row mb-4">
    <div class="col-12">
        <div class="stats-summary"
            style="
            background: var(--color-bg-card);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid var(--color-border-soft);
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        ">
            <h5 style="color: var(--color-text-main); font-weight: 700;">
                📊 Resumen General
            </h5>
            <div class="row">
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <div class="text-center">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--color-primary);">
                            {{ $total_productos }}
                        </div>
                        <div style="color: var(--color-text-main); font-size: 0.9rem; opacity: 0.8;">Total Productos
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-3 mb-md-0">
                    <div class="text-center">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--color-tertiary);">
                            {{ $total_compras }}
                        </div>
                        <div style="color: var(--color-text-main); font-size: 0.9rem; opacity: 0.8;">Compras Realizadas
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div style="font-size: 2rem; font-weight: 800; color: var(--color-secondary);">
                            {{ $total_proveedores }}
                        </div>
                        <div style="color: var(--color-text-main); font-size: 0.9rem; opacity: 0.8;">Proveedores
                            Activos</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="text-center">
                        <div
                            style="font-size: 2rem; font-weight: 800; color: {{ $total_lotes_vencidos > 0 ? '#ff6b6b' : 'var(--color-tertiary)' }};">
                            {{ $total_lotes_vencidos }}
                        </div>
                        <div style="color: var(--color-text-main); font-size: 0.9rem; opacity: 0.8;">Lotes Vencidos
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row ">
    <!-- Gráfica de Barras -->
    <div class="col-lg-12 mb-4">
        <div
            style="
            background: var(--color-bg-card);
    border-radius: 16px;
    padding: 1.5rem;
    border: 1px solid var(--color-border-soft);
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
        ">
            <h5 style="color: var(--color-text-main); font-weight: 700;">
                📈 Estadísticas del Sistema
            </h5>
            <canvas id="mainChart" height="100"></canvas>
        </div>
    </div>
</div>

@section('grafica')
    <script>
        const chartPalette = [
            '#dc2626',
            '#ef4444',
            '#f87171',
            '#b91c1c',
            '#991b1b',
            '#7f1d1d'
        ];


        // Configuración de colores del tema
        const themeColors = {
            primary: 'hsl(358, 80%, 45%)',
            secondary: 'hsl(357, 43%, 46%)',
            tertiary: 'hsl(357, 87%, 47%)',
            bgLightDarkRed: 'hsl(357, 28%, 30%)',
            bgDark: 'hsl(356, 15%, 18%)',
            textWhite: 'hsl(0, 0%, 85%)',
            btnHover: 'hsl(358, 75%, 30%)'
        };


        // Gráfica de Barras Principal
        const ctxMain = document.getElementById('mainChart');

        if (ctxMain) {
            new Chart(ctxMain, {
                type: 'bar',
                data: {
                    labels: [
                        'Compras',
                        'Lotes Vencidos'
                    ],
                    datasets: [{
                        label: 'Cantidad',
                        data: [
                            {{ $total_compras }},
                            {{ $total_lotes_vencidos }}
                        ],
                        backgroundColor: chartPalette.map(c => c + 'cc'),
                        borderColor: chartPalette,
                        borderWidth: 1,
                        borderRadius: 8,
                        borderSkipped: false
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: '#ffffff',
                            titleColor: '#111827',
                            bodyColor: '#374151',
                            borderColor: '#e5e7eb',
                            borderWidth: 1,
                            padding: 12,
                            callbacks: {
                                label: ctx => `${ctx.parsed.y} registros`
                            }
                        }

                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: '#374151',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: '#e5e7eb',
                                drawBorder: false
                            }
                        },
                        x: {
                            ticks: {
                                color: '#374151',
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }

                }
            });
        }
    </script>
@endsection