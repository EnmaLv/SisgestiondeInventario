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
                Personas
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div>
            <a href="{{ url('admin/persona/create') }}" class="rd-btn rd-btn-primary">
                <i class="fas fa-plus"></i>Registra una nueva persona
            </a>
        </div>

    </div>
@stop

@section('content')
    @include('components.alert')
    <div class="rd-card rd-card-full">

        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Personas Registradas</h3>
                </div>

                <div class="rd-actions">
                    <form action="{{ route('admin.configuracion.persona.index') }}" method="GET" class="rd-search-inline"
                        role="search">
                        <input type="text" name="buscar" value="{{ $buscar ?? '' }}" class="rd-search-input"
                            placeholder="Buscar nombre o apellido" />
                        <button class="rd-icon-btn" type="submit" title="Buscar"><i class="fas fa-search"></i></button>
                    </form>

                    <button class="rd-icon-btn" data-toggle="collapse" data-target="#filters" aria-expanded="false"
                        aria-controls="filters" title="Filtros">
                        <i class="fas fa-filter"></i>
                    </button>

                </div>
            </div>

            <div class="collapse" id="filters">
                <div class="rd-filters">
                    <div class="d-flex gap-3 align-items-center mb-3">
                        <span class="font-weight-bold" style="margin-right:5px; ">Filtrar por estado:</span>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.maestros.categorias.index', array_merge(request()->query(), ['estado' => 1])) }}"
                                class="btn {{ request('estado', 1) == 1 ? 'btn-primary' : 'btn-outline-primary' }}">
                                Activos
                            </a>
                            <a href="{{ route('admin.maestros.categorias.index', array_merge(request()->query(), ['estado' => 0])) }}"
                                class="btn {{ request('estado', 1) == 0 ? 'btn-danger' : 'btn-outline-danger' }}">
                                Inactivos
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabla --}}
            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Apellido</th>
                            <th class="text-center">Email</th>
                            <th style="width:120px" class="text-center">Perfil</th>
                            <th style="width:150px" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personas as $persona)
                            <tr>
                                <td class="text-center">
                                    {{ ($personas->currentPage() - 1) * $personas->perPage() + $loop->iteration }}</td>
                                <td class="text-center">{{ $persona->nombre_persona }}</td>
                                <td class="text-center">{{ $persona->apellido_persona }}</td>
                                <td class="text-center">{{ $persona->email_persona }}</td>
                                @php
                                    $perfil = [1=>'Estudiante', 2=>'Usuario'];
                                @endphp
                                <td class="text-center ">
                                    <span class="rd-badge rd-badge-success">
                                        {{ $perfil[$persona->id_perfil] ?? $persona->id_perfil }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="rd-action-group">
                                        <a href="{{ route('admin.configuracion.persona.edit', ['id' => $persona->id_persona]) }}"
                                            class="rd-action" title="Editar"><i class="fas fa-edit"></i></a>
                                        <a href="{{ route('admin.configuracion.persona.show', ['id' => $persona->id_persona]) }}" class="rd-action" title="Ver"><i class="fas fa-eye"></i></a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No hay Usuarios registrados</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación del servidor --}}
            <div class="mt-3 d-flex justify-content-center">
                {{ $personas->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop


