@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Empleado</h1>
            <p class="mb-0">Usuario: <strong>{{ $usuario->username }}</strong></p>
        </div>
        <div>
            <a href="{{ route('admin.configuracion.empleados.index') }}" class="rd-btn rd-btn-primary">Volver</a>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="mb-3">
                <h4>Detalles</h4>
                <table class="table table-striped">
                    <tr><th>Usuario</th><td>{{ $usuario->username }}</td></tr>
                    <tr><th>Nombre</th><td>{{ optional($usuario->persona)->nombre_persona ?? '—' }}</td></tr>
                    <tr><th>Apellido</th><td>{{ optional($usuario->persona)->apellido_persona ?? '—' }}</td></tr>
                    <tr><th>Cédula</th><td>{{ optional($usuario->persona)->cedula_persona ?? '—' }}</td></tr>
                    <tr><th>Teléfono</th><td>{{ optional($usuario->persona)->telefono_persona ?? '—' }}</td></tr>
                    <tr><th>Roles</th><td>{{ $usuario->roles->pluck('nombre')->join(', ') }}</td></tr>
                </table>
            </div>

            <div class="mt-3">
                <a href="{{ route('admin.configuracion.empleados.index') }}" class="rd-btn rd-btn-primary">Volver</a>
            </div>
        </div>
    </div>
@stop
