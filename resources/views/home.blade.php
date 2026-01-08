@extends('adminlte::page')

@section('content_header')
    @include('components.alert')
    <div class="dashboard-header"
        style="
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-tertiary) 100%);
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(197, 34, 34, 0.3);
            padding: 2rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
         ">

        <!-- Patrón de fondo decorativo -->
        <div
            style="
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            filter: blur(40px);
        ">
        </div>

        <div class="d-flex justify-content-between align-items-center" style="position: relative; z-index: 1;">
            <!-- Texto principal -->
            <div>
                <h1 class="m-0 text-white" style="font-size: 2rem; font-weight: 800; letter-spacing: -0.5px;">
                    🏠 Panel de Control
                </h1>
                <p class="mt-2 mb-0 text-white" style="font-size: 1.1rem; opacity: 0.95;">
                    Bienvenido de nuevo, <strong>{{ auth()->user()->persona->nombre_persona }}</strong>
                </p>
                <p class="mb-0 text-white" style="font-size: 0.9rem; opacity: 0.8;">
                    Gestiona tu inventario de manera eficiente
                </p>
            </div>

            <!-- Tarjeta de fecha con avatar -->
            <div class="d-none d-md-flex align-items-center" style="gap: 1.5rem;">
                <div class="text-right text-white">
                    <div style="font-size: 0.85rem; opacity: 0.9; margin-bottom: 0.25rem;">
                        📅 Hoy es
                    </div>
                    <div style="font-weight: 700; font-size: 1.3rem;">
                        {{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}
                    </div>
                    <div style="font-size: 0.85rem; opacity: 0.8;">
                        {{ \Carbon\Carbon::now()->translatedFormat('l') }}
                    </div>
                </div>

                <div
                    style="
                    width: 70px;
                    height: 70px;
                    border-radius: 50%;
                    overflow: hidden;
                    border: 4px solid rgba(255,255,255,0.3);
                    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
                    background: white;
                ">
                    <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                        style="width: 100%; height: 100%; object-fit: cover;">
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">

        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/sucursales') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/edificio.gif') }}" alt="Sucursales">
                    </div>
                    <h5>Sedes</h5>
                    <p>{{ $total_sucursales }} registradas</p>
                </div>
            </a>
        </div>

        <!-- Categorías -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/categorias') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/carpetas.gif') }}" alt="Categorías">
                    </div>
                    <h5>Categorías</h5>
                    <p>{{ $total_categorias }} activas</p>
                </div>
            </a>
        </div>

        <!-- Productos -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/productos') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/paquete.gif') }}" alt="Productos">
                    </div>
                    <h5>Productos</h5>
                    <p>{{ $total_productos }} en inventario</p>
                </div>
            </a>
        </div>

        <!-- Proveedores -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/proveedores') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/camion.gif') }}" alt="Proveedores">
                    </div>
                    <h5>Proveedores</h5>
                    <p>{{ $total_proveedores }} disponibles</p>
                </div>
            </a>
        </div>

        <!-- Compras -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/movimientos/compras') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/lista-de-verificacion.gif') }}" alt="Compras">
                    </div>
                    <h5>Compras</h5>
                    <p>{{ $total_compras }} realizadas</p>
                </div>
            </a>
        </div>

        <!-- Compras -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/maestros/recetas') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/bandeja-de-comida.gif') }}" alt="Compras">
                    </div>
                    <h5>Comidas</h5>
                    <p>{{ $total_compras }} registradas</p>
                </div>
            </a>
        </div>

        <!-- Productos por Vencer -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/movimientos/lotes?filtro=por_vencer') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/notificaciones.gif') }}" alt="Por vencer">
                    </div>
                    <h5>Por Vencer</h5>
                    <p>{{ $total_lotes_por_vencer }} próximos a vencer</p>
                </div>
            </a>
        </div>



        <script>
            document.addEventListener('DOMContentLoaded', async function () {

                const hoy = new Date().toISOString().slice(0, 10);
                const alertas = [];

                /* ================= PRIORIDAD 1: LOTES VENCIDOS ================= */
                @if ($total_lotes_vencidos > 0)
                    if (localStorage.getItem('alerta_lotes_vencidos') === hoy) {
                        alertas.push(async () => {
                            const result = await Swal.fire({
                                title: '⚠️ Lotes vencidos',
                                html: `
                                    <p style="font-size:15px">
                                        Existen <b>{{ $total_lotes_vencidos }}</b> lote(s) vencido(s).<br>
                                        Requieren atención inmediata.
                                    </p>
                                `,
                                icon: 'error',
                                confirmButtonText: 'Ver lotes',
                                confirmButtonColor: '#dc2626'
                            });

                            localStorage.setItem('alerta_lotes_vencidos', hoy);

                            if (result.isConfirmed) {
                                window.location.href = "{{ url('/admin/movimientos/lotes?filtro=vencido') }}";
                            }
                        });
                    }
                @endif

                /* ================= PRIORIDAD 2: POR VENCER ================= */
                @if ($total_lotes_por_vencer > 0)
                    if (localStorage.getItem('alerta_por_vencer') !== hoy) {
                        alertas.push(async () => {
                            const result = await Swal.fire({
                                title: 'Productos por vencer',
                                html: `
                                    <p style="font-size:15px">
                                        Hay <b>{{ $total_lotes_por_vencer }}</b> producto(s)
                                        que vencerán en los próximos <b>7 días</b>.
                                    </p>
                                `,
                                icon: 'warning',
                                confirmButtonText: 'Revisar',
                                confirmButtonColor: '#f59e0b'
                            });

                            localStorage.setItem('alerta_por_vencer', hoy);

                            if (result.isConfirmed) {
                                window.location.href = "{{ url('/admin/movimientos/lotes?filtro=por_vencer') }}";
                            }
                        });
                    }
                @endif

                /* ================= PRIORIDAD 3: STOCK MÍNIMO ================= */
                @if ($total_productos_stock_minimo > 0)
                    if (localStorage.getItem('alerta_stock_minimo') !== hoy) {
                        alertas.push(async () => {
                            const result = await Swal.fire({
                                title: '📉 Stock mínimo alcanzado',
                                html: `
                                    <p style="font-size:15px">
                                        Existen <b>{{ $total_productos_stock_minimo }}</b> producto(s)
                                        por debajo del stock mínimo.
                                    </p>
                                    <ul style="text-align:left; font-size:14px; max-height:200px; overflow-y:auto;">
                                        @foreach ($productos_stock_minimo as $producto)
                                            <li>
                                                <strong>{{ $producto->nombre }}</strong>
                                                <small class="text-danger">
                                                    ({{ number_format($producto->stock_actual, 2) }} /
                                                    {{ number_format($producto->stock_minimo, 2) }})
                                                </small>
                                            </li>
                                        @endforeach
                                    </ul>
                                `,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Revisar inventario',
                                cancelButtonText: 'Cerrar',
                                confirmButtonColor: '#f59e0b'
                            });

                            localStorage.setItem('alerta_stock_minimo', hoy);

                            if (result.isConfirmed) {
                                window.location.href = "{{ url('/admin/maestros/productos?filtro=stock_minimo') }}";
                            }
                        });
                    }
                @endif

                /* ================= EJECUCIÓN SECUENCIAL ================= */
                for (const alerta of alertas) {
                    await alerta();
                }

            });
            </script>


        <!-- Lotes Vencidos -->
        <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
            <a href="{{ url('/admin/movimientos/lotes?filtro=vencido') }}" class="module-link">
                <div class="module-card-light">
                    <div class="module-icon">
                        <img src="{{ url('/img/alarma.gif') }}" alt="Lotes vencidos">
                    </div>
                    <h5>Lotes Vencidos</h5>
                    <p>{{ $total_lotes_vencidos }} requieren atención</p>
                </div>
            </a>
        </div>

    </div>

    <!-- Resumen rápido con estadísticas destacadas -->
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


    <!-- Sección de Gráficas -->
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
    <div>

    </div>
@stop

@push('css')
    @section('css')
        <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    @stop
@endpush

@section('js')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const ctx = document.getElementById('doughnutChartID');
            if (!ctx) return;

            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Estado'],
                    datasets: [{
                        data: [100],
                        backgroundColor: [
                            @if($variacion_dolar === 'subio')
                                '#dc2626'
                            @elseif($variacion_dolar === 'bajo')
                                '#16a34a'
                            @else
                                '#9ca3af'
                            @endif
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { enabled: false }
                    }
                }
            });

        });
        </script>


    <script>
        console.log("Dashboard mejorado cargado exitosamente 🚀");
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
