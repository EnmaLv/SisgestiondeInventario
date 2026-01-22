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
                Crear Nueva Requisicion
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
                <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>

    </div>
@stop


@section('content')
    <div class="row">
        <div class="col-md-12 m-auto">

            <div class="rd-card p-4">

                {{-- Header interno --}}
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Datos de la Requisicion</h3>

                    <a href="{{ url('admin/movimientos/compras') }}" class="rd-btn rd-btn-default">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <form action="{{ route('admin.movimientos.compras.store') }}" method="POST" class="rd-prevent-double-submit">
                    @csrf

                    <div class="row">

                        {{-- Proveedor --}}
                        <div class="col-md-4 mb-3">
                            <label class="rd-label">Proveedor</label>
                            <div class="rd-input-group">
                                <span><i class="fas fa-user-tie"></i></span>
                                <select name="proveedor_id" id="proveedor_id" class="form-control rd-input">
                                    <option value="">Seleccione un proveedor</option>
                                    @foreach ($proveedores as $proveedor)
                                        <option value="{{ $proveedor->id }}"
                                            {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                            {{ $proveedor->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($proveedores->isEmpty())
                                <div class="pt-2">No tienes proveedores registrados, <a
                                        href="{{ route('admin.maestros.proveedores.create') }}">agrega uno</a></div>
                            @endif
                            @error('proveedor_id')
                                <div class="rd-error">Este campo es obligatorio.</div>
                            @enderror
                        </div>

                        {{-- Fecha --}}
                        <div class="col-md-4 mb-3">
                            <label class="rd-label">Fecha de la Requisicion</label>
                            <div class="rd-input-group">
                                <span><i class="fas fa-calendar-alt"></i></span>
                                <input type="datetime-local" id="fecha" name="fecha" class="form-control rd-input"
                                    value="{{ \Carbon\Carbon::now('America/Caracas')->format('Y-m-d\TH:i') }}" readonly>
                            </div>
                            @error('fecha')
                                <div class="rd-error">Este campo es obligatorio.</div>
                            @enderror
                        </div>

                        {{-- Observaciones --}}
                        <div class="col-md-4 mb-3">
                            <label class="rd-label">Observaciones</label>
                            <div class="rd-input-group">
                                <span><i class="fas fa-sticky-note"></i></span>
                                <input type="text" id="observaciones" name="observaciones"
                                    placeholder="Ingrese observaciones" class="form-control rd-input"
                                    value="{{ old('observaciones') }}">
                            </div>
                            @error('observaciones')
                                <div class="rd-error">Este campo es obligatorio.</div>
                            @enderror
                        </div>

                    </div>

                    <hr>

                    <div class="d-flex justify-content-end" style="gap:10px;">
                        <a href="{{ url('admin/movimientos/compras') }}" class="rd-btn rd-btn-default">
                            Cancelar
                        </a>

                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn" @disabled($proveedores->isEmpty())
                            style="@if ($proveedores->isEmpty()) opacity: 0.5!important; cursor: not-allowed; @endif">
                            Crear Requisicion
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
