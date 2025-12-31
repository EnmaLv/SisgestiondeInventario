@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Permisos</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">Gestionar permisos adicionales por usuario.</p>
        </div>
        <div></div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <div class="rd-card-header rd-header-space">
                <div>
                    <h3 class="rd-title-sm">Asignar Permisos</h3>
                </div>
            </div>

            <table class="rd-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Nombre y Apellido</th>
                        <th>Rol</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($usuarios as $usuario)
                        <tr>
                            <td>{{ $loop->iteration + ($usuarios->currentPage()-1)* $usuarios->perPage() }}</td>
                            <td>{{ $usuario->username }}</td>
                            <td>{{ optional($usuario->persona)->nombre ?? '—' }}</td>
                            <td>{{ $usuario->roles->pluck('nombre')->join(', ') ?: ($usuario->perfil->nombre_perfil ?? '—') }}</td>
                            @php
                                $auth = auth()->user();
                                $isSelfAdmin = $auth && $auth->id_usuario == $usuario->id_usuario && $auth->roles->contains('nombre', 'Administrador');
                            @endphp
                            <td>
                                @if(!$isSelfAdmin)
                                    <a href="{{ route('admin.configuracion.permisos.edit', $usuario->id_usuario) }}" class="rd-btn rd-btn-primary">Gestionar</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">No hay usuarios</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-3 d-flex justify-content-center">
                {{ $usuarios->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
@stop
