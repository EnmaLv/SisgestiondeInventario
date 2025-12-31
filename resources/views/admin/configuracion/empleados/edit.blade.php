<!-- Empleados edit (migrated from empleos) -->
@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">

        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Editar Empleado</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">Usuario: <strong>{{ $usuario->username }}</strong></p>
        </div>

        <div></div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <form action="{{ route('admin.configuracion.empleados.update', $usuario->id_usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Username</label>
                    <input type="text" class="form-control" value="{{ $usuario->username }}" disabled style="border:1px solid #cbd5e1;" />
                </div>

                <div class="form-group">
                    <label>Rol</label>
                    @php
                        $isAdminUsuario = $usuario->roles->contains('nombre', 'Administrador');
                        $authUser = auth()->user();
                        $isSelfAdmin = $authUser && $authUser->id_usuario == $usuario->id_usuario && $authUser->roles->contains('nombre', 'Administrador');
                        // hide role selector if the user is editing themselves and is an Administrator
                        $hideRoleSelectForSelfAdmin = $isSelfAdmin;
                        $currentRole = $usuario->roles->first();
                    @endphp
                    @if(!$hideRoleSelectForSelfAdmin)
                        <select name="role" id="roleSelect" class="form-control" style="border:1px solid #cbd5e1;">
                        @foreach($roles as $r)
                            <option value="{{ $r->id_rol }}" {{ $usuario->roles->contains('id_rol', $r->id_rol) ? 'selected' : '' }}>{{ $r->nombre }}</option>
                        @endforeach
                        </select>
                        <div id="newAdminKeyWrap" style="display:none;margin-top:10px;">
                            <label>Llave Maestra para nuevo Administrador</label>
                            <input type="password" name="new_admin_master_key" id="newAdminKey" class="form-control" placeholder="Introduzca Llave Maestra para el nuevo administrador (mín 6 caracteres)">
                        </div>
                    @else
                        <div class="form-control" style="border:1px solid #cbd5e1;background:#f8fafc">{{ $currentRole ? $currentRole->nombre : '—' }}</div>
                        <input type="hidden" name="role" value="{{ $currentRole ? $currentRole->id_rol : '' }}">
                    @endif

                    <script>
                        (function(){
                            var select = document.getElementById('roleSelect');
                            if(!select) return;
                            var wrap = document.getElementById('newAdminKeyWrap');
                            function check(){
                                var sel = select.options[select.selectedIndex].text || '';
                                if(sel.trim().toLowerCase() === 'administrador'){
                                    wrap.style.display = 'block';
                                } else { wrap.style.display = 'none'; }
                            }
                            select.addEventListener('change', check);
                            document.addEventListener('DOMContentLoaded', check);
                        })();
                    </script>
                    @error('role') <div class="text-danger">{{ $message }}</div> @enderror

                    @if(!empty($otherAdminExists) === false && !$hideRoleSelectForSelfAdmin && $isAdminUsuario)
                        <input type="hidden" name="role" value="{{ $currentRole ? $currentRole->id_rol : '' }}">
                        <div class="alert alert-warning mt-2">No se puede cambiar el rol porque este usuario es el único Administrador activo.</div>
                    @endif
                </div>

                <div class="mt-3">
                    <button class="rd-btn rd-btn-primary">Guardar</button>
                    <a href="{{ route('admin.configuracion.empleados.index') }}" class="rd-btn rd-btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@stop
