@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">

        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Roles</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">Gestiona roles del sistema</p>
        </div>

        <div>
            <a href="{{ route('admin.configuracion.roles.create') }}" class="rd-btn rd-btn-primary">Crear Rol</a>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Listado de Roles</h3>
                </div>

                <div class="rd-actions">
                    <form class="form-inline" method="GET" action="{{ route('admin.configuracion.roles.index') }}">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar..." class="form-control mr-2" style="border:1px solid #cbd5e1;">
                        <button class="rd-btn rd-btn-ghost" type="submit" title="Buscar" style="padding:7px 9px;border-radius:8px">
                            <i class="fas fa-search" style="color:#0f172a"></i>
                        </button>
                    </form>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th style="width:140px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($roles as $rol)
                        <tr>
                            <td>{{ ($roles->currentPage()-1)*$roles->perPage()+$loop->iteration }}</td>
                            <td>{{ $rol->nombre }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($rol->descripcion, 80) }}</td>
                            <td class="text-center">
                                @php
                                    $protected = ['Empleado','Obrero','Administrador'];
                                    $isProtected = in_array(strtolower($rol->nombre ?? ''), array_map('strtolower', $protected));
                                @endphp
                                @if(!$isProtected)
                                    <a href="{{ route('admin.configuracion.roles.edit', $rol->id_rol) }}" class="rd-action"><i class="fas fa-edit"></i></a>
                                    <form action="{{ route('admin.configuracion.roles.destroy', $rol->id_rol) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Eliminar rol?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rd-action btn-link"><i class="fas fa-trash text-danger"></i></button>
                                    </form>
                                @else
                                    <span class="text-muted">Protegido</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4">No hay roles</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">{{ $roles->links('components.pagination') }}</div>
        </div>
    </div>
@stop
