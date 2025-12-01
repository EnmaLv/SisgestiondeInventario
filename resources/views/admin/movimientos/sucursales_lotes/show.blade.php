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
                Inventario de la Sucursal:
                {{ $sucursales->firstWhere('id', request()->segment(count(request()->segments())))?->nombre ?? '' }}
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
                    <h3 class="rd-title-sm">Inventario Registrado</h3>
                </div>

                <div class="rd-actions">
                    <form
                        action="{{ route('admin.movimientos.sucursales_lotes.show', request()->segment(count(request()->segments()))) }}"
                        method="GET" class="rd-search-inline" role="search">
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

            {{-- <div class="collapse" id="filters">
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
            </div> --}}

            {{-- Tabla --}}
            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Codigo de Lote</th>
                            <th>Producto</th>
                            <th>Cantidad Actual</th>
                            <th>Fecha Entrada</th>
                            <th>Fecha Vencimiento</th>
                            <th>Proveedor</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sucursal as $lotesucursal)
                            <tr>
                                <td class="text-center">
                                    {{ ($sucursal->currentPage() - 1) * $sucursal->perPage() + $loop->iteration }}
                                </td>
                                <td>{{ $lotesucursal->lote->codigo_lote }}</td>
                                <td>{{ $lotesucursal->lote->producto->nombre }}</td>
                                <td>{{ $lotesucursal->cantidad }}</td>
                                <td>{{ $lotesucursal->lote->fecha_entrada }}</td>
                                <td>{{ $lotesucursal->lote->fecha_vencimiento }}</td>
                                <td>{{ $lotesucursal->lote->proveedor->nombre }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">No hay registros</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación del servidor --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $sucursal->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop


@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop
