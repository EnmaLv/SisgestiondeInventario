@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">
        <!-- Texto principal -->
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                Lotes
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>

            <div
                style="
                width:46px;
                height:46px;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 4px 12px rgba(15,23,42,0.08);
            ">
                <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>

    </div>
@stop

@section('content')
    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Lotes Registrados</h3>
                </div>

                <div class="rd-actions">
                    <form action="{{ route('admin.movimientos.lotes.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Escriba el lote" />
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
                    <form action="{{ route('admin.movimientos.lotes.index') }}" method="GET" class="rd-filters-form">
                        <div class="rd-filter-row" style="display: inline-block;">
                            <label>Desde</label>
                            <input type="date" name="fecha_desde" id="fecha_desde" class="rd-filter-input" />
                        </div>
                        <div class="rd-filter-row" style="display: inline-block;">
                            <label>Hasta</label>
                            <input type="date" name="fecha_hasta" id="fecha_hasta" class="rd-filter-input" />
                        </div>
                        <div class="rd-filter-row rd-filter-actions" style="display: inline-block; vertical-align: bottom;">
                            <button class="rd-btn rd-btn-primary" type="submit">Aplicar</button>
                            <button type="button" class="rd-btn rd-btn-default"
                                onclick="document.getElementById('fecha_desde').value=''; document.getElementById('fecha_hasta').value='';">Limpiar</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Tabla --}}
            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Codigo Lote</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Fecha Entrada</th>
                            <th>Fecha Vencimiento</th>
                            <th>Dias Restantes</th>
                            <th>Cantidad Actual</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($lotes as $lote)
                            <tr class="{{ $lote->is_expired ? 'table-danger' : '' }}">
                                <td class="text-center">
                                    {{ ($lotes->currentPage() - 1) * $lotes->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $lote->codigo_lote }}</td>
                                <td>{{ $lote->producto->nombre }}</td>
                                <td>{{ $lote->proveedor->nombre }}</td>
                                <td>{{ $lote->fecha_entrada }}</td>
                                <td>{{ $lote->fecha_vencimiento }}</td>
                                <td>
                                    {{ round($lote->days_to_expire) }} días
                                </td>
                                <td>{{ $lote->cantidad_actual }}</td>
                                <td>
                                    @if ($lote->is_expired)
                                        <span class="rd-badge rd-badge-danger">Vencido</span>
                                    @elseif ($lote->days_to_expire <= 10)
                                        <span class="rd-badge rd-badge-warning">Cerca de Vencer</span>
                                    @else
                                        <span class="rd-badge rd-badge-success">Vigente</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">No hay sucursales</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación del servidor --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $lotes->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop


@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
    <style>
        /* ===========================
                                                           RD FILTER INPUT
                                                           =========================== */
        .rd-filter-input {
            width: 100%;
            background: #ffffff;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 10px 12px;
            font-size: 0.95rem;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease-in-out;
            height: 42px;
        }

        /* Hover */
        .rd-filter-input:hover {
            border-color: #9ca3af;
        }

        /* Foco */
        .rd-filter-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* Input inválido o error */
        .rd-filter-input.is-invalid {
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.15);
        }

        /* Disabled */
        .rd-filter-input:disabled {
            background: #f1f5f9;
            cursor: not-allowed;
            color: #94a3b8;
        }
    </style>
@stop
