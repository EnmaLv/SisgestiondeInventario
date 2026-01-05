@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Crear Nuevo Rol</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                <i class="fas fa-shield-alt mr-1" style="color: var(--color-secondary)"></i> 
                Define un nuevo perfil de acceso al sistema.
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
        <div class="col-md-11"> <div class="rd-card shadow-sm border-0">
                <div class="rd-card-body p-4">
                    <form action="{{ route('admin.configuracion.roles.store') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="rd-label mb-2">Nombre del Rol</label>
                                <div class="rd-input-group">
                                    <span><i class="fas fa-tag"></i></span>
                                    <input type="text" name="nombre" class="rd-input w-100" 
                                           placeholder="Ej: Supervisor" required value="{{ old('nombre') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="rd-label mb-2">Descripción Corta</label>
                                <input type="text" name="descripcion" class="form-control" 
                                       placeholder="Propósito del rol"
                                       style="border: 1px solid #d8dee9; border-radius: 10px; padding: 8px 12px; height: 45px;">
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="rd-label m-0">
                                    <i class="fas fa-list-check mr-2 text-primary"></i> Asignación de Permisos de Menú
                                </label>
                                <button type="button" id="selectAll" class="btn btn-xs btn-outline-secondary" style="border-radius: 6px;">
                                    Seleccionar Todos
                                </button>
                            </div>
                            
                            <div class="permissions-grid p-4" 
                                 style="border: 1px solid #eef2f6; border-radius: 12px; background: #fbfdff;">
                                
                                @php
                                    function renderMenuItems($items, $parentText = '') {
                                        foreach ($items as $it) {
                                            if (isset($it['submenu'])) {
                                                // Título de grupo para agrupar visualmente
                                                echo '<div class="permission-group-title mt-2 mb-2">
                                                        <strong class="text-uppercase small text-muted" style="letter-spacing:0.5px; color:var(--color-secondary) !important;">
                                                            <i class="fas fa-folder mr-1"></i> '.$it['text'].'
                                                        </strong>
                                                      </div>';
                                                renderMenuItems($it['submenu'], $it['text']);
                                            } else {
                                                $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
                                                if (!$val) continue;
                                                echo '<div class="custom-control custom-checkbox mb-2 permission-item">
                                                        <input type="checkbox" name="menu_permissions[]" value="'.e($val).'" class="custom-control-input perm-check" id="check_'.e($val).'">
                                                        <label class="custom-control-label font-weight-normal" style="cursor:pointer; font-size:0.9rem;" for="check_'.e($val).'">
                                                            '.e($it['text']).'
                                                        </label>
                                                      </div>';
                                            }
                                        }
                                    }
                                    renderMenuItems($menu);
                                @endphp
                            </div>
                        </div>

                        <div class="d-flex mt-5 justify-content-end" style="gap: 10px">
                            <a href="{{ route('admin.configuracion.roles.index') }}" class="rd-btn rd-btn-default px-4" style="height: 48px; justify-content: center;">
                                Cancelar
                            </a>
                            <button type="submit" class="rd-btn rd-btn-primary px-5" style="height: 48px;justify-content: center;">
                                <i class="fas fa-save"></i> Guardar Nuevo Rol
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
<style>
    /* Configuración de Columnas para evitar el scroll */
    .permissions-grid {
        column-count: 3; /* Divide en 3 columnas */
        column-gap: 30px;
        column-rule: 1px solid #f1f5f9; /* Línea divisoria suave */
    }

    /* Evita que un grupo se rompa entre dos columnas */
    .permission-group-title, .permission-item {
        break-inside: avoid;
        display: block;
    }

    .permission-group-title {
        border-bottom: 1px solid #f1f5f9;
        padding-bottom: 4px;
        margin-top: 15px !important;
    }

</style>
@stop

@section('js')
<script>
    document.getElementById('selectAll').addEventListener('click', function() {
        const checks = document.querySelectorAll('.perm-check');
        const allChecked = Array.from(checks).every(c => c.checked);
        checks.forEach(c => c.checked = !allChecked);
        this.textContent = allChecked ? 'Seleccionar Todos' : 'Desmarcar Todos';
    });
</script>
@stop