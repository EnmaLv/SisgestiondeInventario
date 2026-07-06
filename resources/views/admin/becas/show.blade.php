@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">{{ $beca->nombre }}</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Codigo <strong>{{ $beca->codigo }}</strong>
            </p>
        </div>
        <div class="d-flex" style="gap:12px;">
            <a href="{{ route('admin.becas.edit', $beca) }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('admin.becas.index') }}" class="rd-btn rd-btn-default">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card p-4 mb-4">
        <div class="row">
            <div class="col-md-4">
                <strong>Estado</strong>
                <p>
                    <span class="rd-badge {{ $beca->activo ? 'rd-badge-success' : 'rd-badge-danger' }}">
                        {{ $beca->activo ? 'Activa' : 'Inactiva' }}
                    </span>
                </p>
            </div>
        </div>
        <strong>Descripcion</strong>
        <p class="mb-0">{{ $beca->descripcion ?: 'Sin descripcion.' }}</p>
    </div>

    <div class="rd-card p-4 mb-4">
        <div class="rd-card-header mb-3">
            <h3 class="rd-title-sm">Beneficios</h3>
        </div>
        <table class="rd-table">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Descripcion</th>
                    <th>Observacion</th>
                </tr>
            </thead>
            <tbody>
                @forelse($beca->beneficios as $beneficio)
                    <tr>
                        <td>{{ $beneficio->nombre_beneficio }}</td>
                        <td>{{ $beneficio->descripcion ?: 'Sin descripcion' }}</td>
                        <td>{{ $beneficio->pivot->observacion ?: 'Sin observacion' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4">Esta beca no tiene beneficios asignados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@stop
