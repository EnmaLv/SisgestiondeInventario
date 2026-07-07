@extends('adminlte::page')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Crear Nueva Jornada de Beca</h1>
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
                style="width:46px;height:46px;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(15,23,42,0.08);">
                <img src="{{ asset('img/usuario-verificado.webp') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10 m-auto">
            <div class="rd-card p-4">
                <div class="rd-card-header mb-4">
                    <h3 class="rd-title-sm">Registrar Nueva Jornada</h3>
                    <div>
                        <a href="{{ url('admin/becas/jornada') }}" class="rd-btn rd-btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                @if (session('error'))
                    <div class="rd-alert rd-alert-danger mb-3">
                        <div class="rd-alert-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="rd-alert-content">
                            <h6 class="rd-alert-title">Ocurrió un error</h6>
                            <p class="rd-alert-text">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="rd-alert rd-alert-danger mb-3">
                        <div class="rd-alert-icon">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="rd-alert-content">
                            <h6 class="rd-alert-title">Por favor corrige los siguientes errores:</h6>
                            <ul class="rd-alert-list">
                                @foreach ($errors->all() as $error)
                                    <li><i class="fas fa-dot-circle"></i> {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.becas.jornada.store') }}" method="POST" class="rd-prevent-double-submit">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label class="font-weight-bold">Nombre de la Jornada <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-signature"></i></span>
                                <input type="text" class="form-control rd-filter-input @error('nombre_jornada') is-invalid @enderror"
                                    name="nombre_jornada" value="{{ old('nombre_jornada') }}"
                                    placeholder="Ej. Convocatoria Comedor Universitario 2026-1" required>
                            </div>
                            @error('nombre_jornada')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Beneficio <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-award"></i></span>
                                <select class="form-control rd-filter-input @error('beneficio_id') is-invalid @enderror"
                                    name="beneficio_id" required>
                                    <option value="" disabled selected>Seleccione el beneficio a ofrecer</option>
                                    @foreach ($beneficios as $beneficio)
                                        <option value="{{ $beneficio->id }}" {{ old('beneficio_id') == $beneficio->id ? 'selected' : '' }}>
                                            {{ $beneficio->nombre_beneficio }} (Disponibles: {{ $beneficio->cupones_disponibles }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('beneficio_id')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Lapso Académico <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                <select class="form-control rd-filter-input @error('lapsos_id') is-invalid @enderror"
                                    name="lapsos_id" required>
                                    <option value="" disabled selected>Seleccione el lapso académico</option>
                                    @foreach ($lapsos as $lapso)
                                        <option value="{{ $lapso->id }}" {{ old('lapsos_id') == $lapso->id ? 'selected' : '' }}>
                                            {{ $lapso->codigo }} {{ $lapso->es_actual ? '(Actual)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('lapsos_id')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Fecha de Inicio de Solicitudes <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-day"></i></span>
                                <input type="date" class="form-control rd-filter-input @error('fecha_inicio_solicitud') is-invalid @enderror"
                                    name="fecha_inicio_solicitud" value="{{ old('fecha_inicio_solicitud') ?: date('Y-m-d') }}" required min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                            @error('fecha_inicio_solicitud')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">Fecha de Fin de Solicitudes <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-calendar-check"></i></span>
                                <input type="date" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="form-control rd-filter-input @error('fecha_fin_solicitud') is-invalid @enderror"
                                    name="fecha_fin_solicitud" value="{{ old('fecha_fin_solicitud') }}" required >
                            </div>
                            @error('fecha_fin_solicitud')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 form-group mb-3">
                            <label class="font-weight-bold">
                                Cupos Máximos <span class="text-danger">*</span>
                                <span class="ml-1" data-toggle="tooltip" data-placement="top" 
                                    title="El número de cupos máximos de la jornada no puede superar los cupos disponibles del beneficio seleccionado.">
                                    <i class="fas fa-info-circle" style="cursor: help;"></i>
                                </span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-users"></i></span>
                                <input type="number" min="1" class="form-control rd-filter-input @error('cupos_maximos') is-invalid @enderror"
                                    name="cupos_maximos" value="{{ old('cupos_maximos') }}" placeholder="Ej. 100" required>
                            </div>
                            @error('cupos_maximos')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>

                        <div class="col-md-6 form-group mb-3 d-flex align-items-center">
                            <div class="custom-control custom-switch mt-4">
                                <input type="checkbox" class="custom-control-input" id="activa" name="activa" value="1" {{ old('activa', 1) ? 'checked' : '' }}>
                                <label class="custom-control-label font-weight-bold" for="activa">Jornada Activa</label>
                                <small class="d-block text-muted">Las jornadas inactivas no permiten a los estudiantes postularse.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12 form-group mb-3">
                            <label class="font-weight-bold">Descripción de la Jornada</label>
                            <textarea class="form-control rd-filter-input @error('descripcion_jornada') is-invalid @enderror"
                                name="descripcion_jornada" rows="4" placeholder="Ingrese detalles o requisitos de esta jornada..."
                                style="resize: none;">{{ old('descripcion_jornada') }}</textarea>
                            @error('descripcion_jornada')
                                <div class="text-danger mt-1"><b>{{ $message }}</b></div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4" style="border-top: 1px solid #e5e7eb;">

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url('admin/becas/jornada') }}" class="rd-btn rd-btn-default mr-2">
                            Cancelar
                        </a>
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn">
                            <i class="fas fa-save"></i> Guardar Jornada
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@stop
