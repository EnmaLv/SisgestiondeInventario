@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Editar Rol: {{ $rol->nombre }}</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                <i class="fas fa-user-shield mr-1" style="color: var(--color-secondary)"></i> 
                Modifica los accesos y descripción del perfil.
            </p>
        </div>
        <a href="{{ route('admin.configuracion.roles.index') }}" class="rd-btn rd-btn-default">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="row justify-content-center fade-in">
        <div class="col-md-11">
            <div class="rd-card shadow-sm border-0">
                <div class="rd-card-body p-4">
                    <form action="{{ route('admin.configuracion.roles.update', $rol->id_rol) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="rd-label mb-2">Nombre del Rol</label>
                                <div class="rd-input-group {{ ($isProtected ?? false) ? 'bg-light' : '' }}">
                                    <span><i class="fas fa-tag"></i></span>
                                    <input type="text" name="nombre" class="rd-input w-100" 
                                           value="{{ old('nombre', $rol->nombre) }}" 
                                           {{ ($isProtected ?? false) ? 'readonly' : 'required' }} placeholder="Nombre del rol">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="rd-label mb-2">Descripción</label>
                                <input type="text" name="descripcion" class="form-control" 
                                       style="border: 1px solid #d8dee9; border-radius: 10px; height: 45px; padding: 0 12px;"
                                       value="{{ old('descripcion', $rol->descripcion) }}" placeholder="Descripcion del rol"
                                       {{ ($isProtected ?? false) ? 'readonly' : '' }}>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            @php $isAdminRole = (strtolower($rol->nombre ?? '') === 'administrador'); @endphp
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="rd-label m-0">
                                    <i class="fas fa-list-check mr-2 text-primary"></i> Permisos de Menú y Navegación
                                </label>
                                @if(!$isAdminRole && !($isProtected ?? false))
                                    <button type="button" id="selectAll" class="btn btn-xs btn-outline-secondary" style="border-radius:6px;">
                                        Alternar Todos
                                    </button>
                                @endif
                            </div>
                            
                            <div class="permissions-grid p-4 {{ $isAdminRole ? 'bg-light opacity-75' : '' }}" 
                                 style="border: 1px solid #eef2f6; border-radius: 12px; background: #fbfdff;">
                                
                                @php
                                    if (!function_exists('renderPermissions')) {
                                        function renderPermissions($items, $rol, $isAdminRole, $depth = 0) {
                                            foreach ($items as $it) {
                                                $margin = $depth * 20;
                                                if (isset($it['submenu'])) {
                                                    echo '<div class="permission-group-title mt-2 mb-2" style="margin-left:'.$margin.'px;">
                                                            <strong class="text-uppercase small text-muted" style="letter-spacing:0.5px; color:var(--color-secondary) !important;">
                                                                <i class="fas fa-folder-open mr-1"></i> '.$it['text'].'
                                                            </strong>
                                                          </div>';
                                                    renderPermissions($it['submenu'], $rol, $isAdminRole, $depth + 1);
                                                } else {
                                                    $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
                                                    if (!$val) continue;
                                                    
                                                    $checked = ($isAdminRole || in_array($val, (array)($rol->menu_permissions ?? []))) ? 'checked' : '';
                                                    $disabled = $isAdminRole ? 'disabled' : '';
                                                    $id = 'check_' . Str::slug($val);

                                                    echo '<div class="custom-control custom-checkbox mb-2 permission-item" style="margin-left:'.$margin.'px;">
                                                            <input type="checkbox" name="menu_permissions[]" value="'.e($val).'" 
                                                                   class="custom-control-input perm-check" id="'.$id.'" '.$checked.' '.$disabled.'>
                                                            <label class="custom-control-label font-weight-normal" style="cursor:pointer; font-size:0.9rem;" for="'.$id.'">
                                                                '.e($it['text']).'
                                                            </label>
                                                          </div>';
                                                }
                                            }
                                        }
                                    }
                                    renderPermissions($menu, $rol, $isAdminRole);
                                @endphp
                            </div>

                            @if($isAdminRole)
                                <div class="alert alert-info mt-3 border-0 shadow-sm" style="border-radius: 10px;">
                                    <i class="fas fa-info-circle mr-2"></i> Los permisos del rol <strong>Administrador</strong> son totales y no pueden editarse.
                                </div>
                            @endif
                        </div>

                        <hr class="my-4" style="opacity:0.5;">

                        <div class="d-flex gap-3 justify-content-end" style="gap: 10px">
                            <a href="{{ route('admin.configuracion.roles.index') }}" class="rd-btn rd-btn-default px-4 d-flex align-items-center">
                                Cancelar
                            </a>
                            @if(!($isProtected ?? false))
                                <button type="submit" class="rd-btn rd-btn-primary px-5" style="height: 48px; justify-content: center;">
                                    <i class="fas fa-sync-alt"></i> Actualizar Rol
                                </button>
                            @else
                                <div class="alert alert-warning w-100 mb-0 shadow-sm border-0 d-flex align-items-center" style="border-radius: 10px;">
                                    <i class="fas fa-lock mr-3 fa-lg"></i> 
                                    <div>El rol <strong>{{ $rol->nombre }}</strong> es un rol de sistema protegido.</div>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    .permissions-grid {
        column-count: 3;
        column-gap: 30px;
        column-rule: 1px solid #f1f5f9;
    }

    .permission-group-title, .permission-item {
        break-inside: avoid;
        display: block;
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--color-secondary) !important;
        border-color: var(--color-secondary) !important;
    }

    /* Quitar bordes de foco feos según lo solicitado */
    .rd-input:focus, .perm-check:focus, .form-control:focus {
        outline: none !important;
        box-shadow: none !important;
    }

</style>
@stop

@section('js')
<script>
    const selectBtn = document.getElementById('selectAll');
    if(selectBtn) {
        selectBtn.addEventListener('click', function() {
            const checks = document.querySelectorAll('.perm-check:not(:disabled)');
            const allChecked = Array.from(checks).every(c => c.checked);
            checks.forEach(c => c.checked = !allChecked);
            this.textContent = allChecked ? 'Seleccionar Todos' : 'Desmarcar Todos';
        });
    }
</script>
@stop