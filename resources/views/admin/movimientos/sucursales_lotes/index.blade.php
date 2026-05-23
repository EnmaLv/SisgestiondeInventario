@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background:#ffffff;
            border-radius:16px;
            border:1px solid #e5e7eb;
            box-shadow:0 4px 14px rgba(0,0,0,0.06);
        ">

        {{-- Título --}}
        <div>
            <h1 class="m-0" style="font-size:1.5rem; color:#0f172a; font-weight:700;">
                Lotes por Sedes
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>

        {{-- Fecha + Imagen --}}
        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small style="font-size:0.75rem; color:#94a3b8;">Hoy</small>
                <div style="font-weight:600; font-size:0.95rem; color:#0f172a;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </div>
            </div>

            <div
                style="
                width:46px;
                height:46px;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 4px 12px rgba(15,23,42,0.08);
            ">
                <img src="{{ asset('img/usuario-verificado.webp') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>

    </div>
@stop

@section('content')
    <div class="row">
        @foreach ($sucursales as $sucursalLote)
            <div class="col-md-3 col-sm-6 col-12">
                <div class="info-box">
                    <a href="{{ url('admin/movimientos/sucursales_lotes/show/' . $sucursalLote->id) }}">
                        <span class="info-box-icon bg-info">
                            <img src="{{ url('/img/restaurante.webp') }}" alt="xd">
                        </span>
                    </a>
                    <div class="info-box-content">
                        <a href="{{ url('admin/movimientos/sucursales_lotes/show/' . $sucursalLote->id) }}">
                            <span class="info-box-text"
                                style="text-decoration: none; color: #000;"><b>{{ $sucursalLote->nombre }}</b></span></a>
                        <span class="info-box-number">
                            {{ $sucursalLote->totalInventarioSucursalLotes }} Unidades
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@stop
