@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                Crear Sede
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
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
    <div class="rd-card rd-card-form">
        <div class="rd-card-body">
            <div class="rd-card-header mb-3">
                <div>
                    <h3 class="rd-title-sm">Crear Sede</h3>
                    <small class="text-muted">Complete la información requerida</small>
                </div>
                <a href="{{ url('admin/maestros/sucursales') }}" class="rd-btn rd-btn-default">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
            <form action="{{ route('admin.maestros.sucursales.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="rd-label">Nombre de la Sede</label>
                        <div class="rd-input-group">
                            <span class="rd-input-icon"><i class="fas fa-tag"></i></span>
                            <input type="text" name="nombre" class="form-control rd-filter-input" placeholder="Ingrese el nombre"
                                value="{{ old('nombre') }}">
                        </div>
                        @error('nombre')
                            <span class="rd-error">Este campo es obligatorio.</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="rd-label">Dirección</label>
                        <div class="rd-input-group">
                            <span class="rd-input-icon"><i class="fas fa-map-marker-alt"></i></span>
                            <input type="text" name="direccion" class="form-control rd-filter-input" placeholder="Ingrese la dirección"
                                value="{{ old('direccion') }}">
                        </div>
                        @error('direccion')
                            <span class="rd-error">Este campo es obligatorio.</span>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label class="rd-label">Teléfono</label>
                        <div class="rd-input-group">
                            <span class="rd-input-icon"><i class="fas fa-phone"></i></span>
                            <input type="text" name="telefono" id="telefono" class="form-control rd-filter-input"
                                placeholder="(123) 456-7890" value="{{ old('telefono') }}"
                                data-inputmask="'mask': '(999) 999-9999'" data-mask>
                        </div>
                        @error('telefono')
                            <span class="rd-error">Este campo es obligatorio.</span>
                        @enderror
                    </div>
                </div>
                <div class="mt-4 d-flex gap-2 justify-content-end">
                    <a href="{{ url('admin/maestros/sucursales') }}" class="rd-btn rd-btn-default">
                        Cancelar
                    </a>
                    <button type="submit" class="rd-btn rd-btn-primary">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $("[data-mask]").inputmask();
        });
    </script>
@endsection
