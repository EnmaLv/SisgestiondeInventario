@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Gestión de Permisos Especiales</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                <i class="fas fa-user-shield mr-1" style="color: var(--color-secondary)"></i> 
                Usuario: <strong>{{ $usuario->username }}</strong>
            </p>
        </div>
        <a href="{{ route('admin.configuracion.permisos.index') }}" class="rd-btn rd-btn-default">
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
                    <form action="{{ route('admin.configuracion.permisos.update', $usuario->id_usuario) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="rd-label m-0">
                                    <i class="fas fa-list-check mr-2 text-primary"></i> Matriz de Permisos Individuales
                                </label>
                                <span class="badge badge-light border text-muted" style="border-radius: 6px; padding: 5px 10px;">
                                    <i class="fas fa-info-circle mr-1"></i> Los cambios aquí sobrescriben el rol base
                                </span>
                            </div>

                            <div class="permissions-grid p-4" 
                                 style="border: 1px solid #eef2f6; border-radius: 12px; background: #fbfdff;">
                                @include('admin.configuracion.permisos._matrix', [
                                    'items' => $menu ?? [],
                                    'rolePerms' => $rolePerms ?? [],
                                    'rolePatterns' => $rolePatterns ?? [],
                                    'effective' => $effective ?? $allow ?? [],
                                    'keyToPatterns' => $keyToPatterns ?? [],
                                    'depth' => 0,
                                ])
                            </div>
                        </div>

                        <hr class="my-4" style="opacity:0.5;">

                        <div class="d-flex gap-3 justify-content-end" style="gap:10px">
                            <a href="{{ route('admin.configuracion.permisos.index') }}" class="rd-btn rd-btn-default px-4 d-flex align-items-center">
                                Cancelar
                            </a>
                            <button type="submit" id="save-perms" class="rd-btn rd-btn-primary px-5" style="height: 48px; justify-content: center;">
                                <i class="fas fa-save"></i> Aplicar Ajustes
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
    /* Grid de 3 columnas para evitar scroll */
    .permissions-grid {
        column-count: 3;
        column-gap: 30px;
        column-rule: 1px solid #f1f5f9;
    }

    .permission-group-title, .permission-item {
        break-inside: avoid;
        display: block;
    }


</style>
@stop

@section('js')
<script>
    (function(){
        const form = document.querySelector('form');

        // Alerta al desmarcar algo que viene del rol
        document.querySelectorAll('.perm-chk[data-role="1"]').forEach(chk => {
            chk.addEventListener('click', function(e) {
                if (!this.checked) {
                    e.preventDefault();
                    Swal.fire({
                        title: '¿Deshabilitar permiso de rol?',
                        text: 'Este permiso es heredado del rol del usuario. Al desmarcarlo, estarás restringiendo explícitamente esta función.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: 'var(--color-primary)',
                        confirmButtonText: 'Sí, restringir',
                        cancelButtonText: 'Mantener'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            this.checked = false;
                        }
                    });
                }
            });
        });

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset names to avoid stale values
            document.querySelectorAll('.perm-chk').forEach(i => i.removeAttribute('name'));

            const allow = [];
            const deny = [];

            document.querySelectorAll('.perm-chk').forEach(i => {
                const val = i.value;
                const isRole = i.dataset.role === '1';
                if (isRole) {
                    if (!i.checked) {
                        deny.push(val);
                        i.name = 'deny[]';
                    }
                } else {
                    if (i.checked) {
                        allow.push(val);
                        i.name = 'allow[]';
                    }
                }
            });

            // If nothing to change, submit (checkboxes with names will be sent)
            if (allow.length === 0 && deny.length === 0) {
                form.submit();
                return;
            }

            let htmlContent = '<div class="text-left small">';
            if (allow.length) htmlContent += '<strong>Permisos adicionales:</strong><ul class="mb-2">' + allow.map(a => '<li>' + a + '</li>').join('') + '</ul>';
            if (deny.length) htmlContent += '<strong>Restricciones sobre el rol:</strong><ul>' + deny.map(a => '<li>' + a + '</li>').join('') + '</ul>';
            htmlContent += '</div>';

            Swal.fire({
                title: 'Confirmar cambios',
                html: htmlContent,
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: 'var(--color-secondary)',
                confirmButtonText: 'Aplicar',
                cancelButtonText: 'Revisar'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    })();
</script>
@stop