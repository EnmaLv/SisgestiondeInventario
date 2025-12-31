@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Permisos de Usuario</h1>
            <p class="mb-0">Usuario: <strong>{{ $usuario->username }}</strong></p>
        </div>
        <div>
            <a href="{{ route('admin.configuracion.permisos.index') }}" class="rd-btn rd-btn-outline">Volver</a>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <form action="{{ route('admin.configuracion.permisos.update', $usuario->id_usuario) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Permisos Permitidos (adicionales)</label>
                    <div id="permissions-box" style="max-height:400px; overflow:auto; border:1px solid #e5e7eb; padding:8px; border-radius:6px;">
                        @php
                            function renderMenuCheckboxes($items, $namePrefix, $selected, $effective) {
                                foreach ($items as $it) {
                                    if (isset($it['submenu'])) {
                                        echo '<div class="mb-1"><strong>'.e($it['text']).'</strong></div>';
                                        renderMenuCheckboxes($it['submenu'], $namePrefix, $selected, $effective);
                                    } else {
                                        $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
                                        if (!$val) continue;
                                        $rolePerms = $GLOBALS['rolePerms'] ?? [];
                                        $isRoleProvided = in_array($val, (array)$rolePerms);
                                        $checked = in_array($val, $effective) ? 'checked' : '';
                                        if ($isRoleProvided) {
                                            echo '<div class="form-check d-flex align-items-center"><label class="form-check-label"><input class="perm-chk" data-role="1" type="checkbox" value="'.e($val).'" '.$checked.'> '.e($it['text']).'</label><span class="badge badge-secondary ml-2" style="margin-left:8px;">Provisto por rol</span></div>';
                                        } else {
                                            echo '<div class="form-check"><label class="form-check-label"><input class="perm-chk" type="checkbox" name="'.$namePrefix.'[]" value="'.e($val).'" '.$checked.'> '.e($it['text']).'</label></div>';
                                        }
                                    }
                                }
                            }
                            $GLOBALS['rolePerms'] = $rolePerms ?? [];
                            renderMenuCheckboxes($menu, 'allow', $allow, $effective ?? $allow);
                        @endphp
                    </div>
                </div>

                <div class="mt-3">
                    <button id="save-perms" class="rd-btn rd-btn-primary">Guardar</button>
                    <a href="{{ route('admin.configuracion.permisos.index') }}" class="rd-btn rd-btn-outline">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
<script>
    (function(){
        const form = document.querySelector('form');

        // Confirm when unchecking a role-provided permission
        document.querySelectorAll('.perm-chk[data-role="1"]').forEach(chk=>{
            chk.addEventListener('change', function(e){
                if (!chk.checked) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Permiso provisto por rol',
                        text: 'Este permiso es provisto por el rol del usuario. ¿Seguro que desea deshabilitarlo para este usuario?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, deshabilitar',
                        cancelButtonText: 'Cancelar'
                    }).then((result)=>{
                        if (result.isConfirmed) {
                            chk.checked = false;
                        } else {
                            chk.checked = true;
                        }
                    });
                }
            });
        });

        form.addEventListener('submit', function(e){
            e.preventDefault();
            const allow = [];
            const deny = [];
            document.querySelectorAll('.perm-chk').forEach(i=>{
                const val = i.value;
                const isRole = i.dataset.role === '1';
                if (isRole) {
                    if (!i.checked) deny.push(val);
                } else {
                    if (i.checked) allow.push(val);
                }
            });

            // remove old hidden inputs
            document.querySelectorAll('input[name="allow[]"], input[name="deny[]"]').forEach(n=>n.remove());
            allow.forEach(v=>{ const ip = document.createElement('input'); ip.type='hidden'; ip.name='allow[]'; ip.value=v; form.appendChild(ip); });
            deny.forEach(v=>{ const ip = document.createElement('input'); ip.type='hidden'; ip.name='deny[]'; ip.value=v; form.appendChild(ip); });

            let message = '';
            if (allow.length) message += '<strong>Permisos a agregar:</strong><br><ul>' + allow.map(a=>'<li>'+a+'</li>').join('') + '</ul>';
            if (deny.length) message += '<strong>Permisos a deshabilitar (provistos por rol):</strong><br><ul>' + deny.map(a=>'<li>'+a+'</li>').join('') + '</ul>';

            if (!message) { form.submit(); return; }

            Swal.fire({
                title: 'Confirmar cambios de permisos',
                html: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sí, aplicar',
                cancelButtonText: 'Cancelar'
            }).then((result)=>{
                if (result.isConfirmed) form.submit();
            });
        });
    })();
</script>
@stop
