@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">

        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Editar Perfil de Empleado</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                <i class="fas fa-user-edit mr-1" style="color: var(--color-secondary)"></i> 
                ID de Usuario: <strong>{{ $usuario->id_usuario }}</strong>
            </p>
        </div>

        <a href="{{ route('admin.configuracion.empleados.index') }}" class="rd-btn rd-btn-default">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="rd-card shadow-sm border-0 overflow-hidden fade-in">
                <div class="rd-card-body border-bottom bg-light">
                    <h3 class="rd-title-sm">
                        <i class="fas fa-id-badge mr-2" style="color: var(--color-secondary)"></i> Información de Cuenta
                    </h3>
                </div>

                <div class="rd-card-body p-4">
                    <form action="{{ route('admin.configuracion.empleados.update', $usuario->id_usuario) }}" method="POST" class="rd-prevent-double-submit">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-4">
                            <label class="rd-label mb-2">Nombre de Usuario</label>
                            <div class="rd-input-group" style="background: #f1f5f9; cursor: not-allowed;">
                                <span><i class="fas fa-user-lock text-muted"></i></span>
                                <input type="text" class="rd-input w-100" value="{{ $usuario->username }}" disabled>
                            </div>
                            <small class="text-muted mt-1 d-block">El nombre de usuario no puede ser modificado por seguridad.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label class="rd-label mb-2">Asignación de Rol</label>
                            @php
                                $isAdminUsuario = $usuario->roles->contains('nombre', 'Administrador');
                                $authUser = auth()->user();
                                $isSelfAdmin = $authUser && $authUser->id_usuario == $usuario->id_usuario && $authUser->roles->contains('nombre', 'Administrador');
                                $hideRoleSelectForSelfAdmin = $isSelfAdmin;
                                $currentRole = $usuario->roles->first();
                            @endphp

                            @if(!$hideRoleSelectForSelfAdmin)
                                <div class="rd-input-group @error('role') border-danger @enderror">
                                    <span><i class="fas fa-shield-alt"></i></span>
                                    <select name="role" id="roleSelect" class="rd-input w-100">
                                        @foreach($roles as $r)
                                            <option value="{{ $r->id_rol }}" {{ $usuario->roles->contains('id_rol', $r->id_rol) ? 'selected' : '' }}>
                                                {{ $r->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div id="newAdminKeyWrap" class="mt-3 p-3" style="display:none; background: #fffcf0; border: 1px dashed #fcd34d; border-radius: 10px;">
                                    <label class="rd-label text-warning mb-2" style="font-size: 0.9rem;">
                                        <i class="fas fa-key mr-1"></i> Llave Maestra de Autorización
                                    </label>
                                    <input type="password" name="new_admin_master_key" id="newAdminKey" class="form-control" 
                                           placeholder="Escriba la llave para confirmar el nuevo Admin" 
                                           style="border-radius: 8px; border: 1px solid #fbbf24;">
                                    <small class="text-muted mt-2 d-block">Se requiere validación de seguridad para otorgar permisos de administrador.</small>
                                </div>
                            @else
                                <div class="rd-input-group" style="background: #f8fafc; border-style: dashed;">
                                    <span><i class="fas fa-user-check text-success"></i></span>
                                    <div class="rd-input w-100 py-2">{{ $currentRole ? $currentRole->nombre : '—' }}</div>
                                </div>
                                <input type="hidden" name="role" value="{{ $currentRole ? $currentRole->id_rol : '' }}">
                                <small class="text-danger mt-1 d-block">No puedes cambiar tu propio rol de Administrador.</small>
                            @endif

                            @error('role') <div class="rd-error">{{ $message }}</div> @enderror

                            @if(!($otherAdminExists ?? true) && !$hideRoleSelectForSelfAdmin && $isAdminUsuario)
                                <input type="hidden" name="role" value="{{ $currentRole ? $currentRole->id_rol : '' }}">
                                <div class="alert alert-warning mt-3 border-0 shadow-sm" style="border-radius: 10px; font-size: 0.9rem;">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> No puedes cambiar el rol: Este es el único <strong>Administrador</strong> del sistema.
                                </div>
                            @endif
                        </div>

                        <div class="mt-5 d-flex gap-2 justify-content-end">
                            <a href="{{ route('admin.configuracion.empleados.index') }}" class="rd-btn rd-btn-alter px-4" style="height: 45px; justify-content: center;">
                                Cancelar
                            </a>
                            <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn px-4" style="height: 45px; justify-content: center;">
                                <i class="fas fa-save"></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function(){
        var select = document.getElementById('roleSelect');
        var wrap = document.getElementById('newAdminKeyWrap');
        if(!select || !wrap) return;

        function check(){
            var selText = select.options[select.selectedIndex].text || '';
            if(selText.trim().toLowerCase() === 'administrador'){
                wrap.style.display = 'block';
                wrap.classList.add('fade-in');
            } else { 
                wrap.style.display = 'none'; 
            }
        }
        select.addEventListener('change', check);
        check(); // Ejecución inicial
    });
</script>
@stop