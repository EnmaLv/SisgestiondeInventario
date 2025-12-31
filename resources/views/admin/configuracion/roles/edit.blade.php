@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Editar Rol</h1>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <form action="{{ route('admin.configuracion.roles.update', $rol->id_rol) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required value="{{ old('nombre', $rol->nombre) }}" style="border:1px solid #cbd5e1;" {{ ($isProtected ?? false) ? 'disabled' : '' }}>
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" style="border:1px solid #cbd5e1;" {{ ($isProtected ?? false) ? 'disabled' : '' }}>{{ old('descripcion', $rol->descripcion) }}</textarea>
                </div>
                <div class="form-group">
                    <label>Permisos de Menú</label>
                    <div class="pl-2" style="max-height:220px; overflow:auto; border:1px solid #e5e7eb; padding:8px; border-radius:6px; background:#fff;">
                        @php
                            function collectMenuKeys($items, &$out) {
                                foreach ($items as $it) {
                                    if (isset($it['submenu'])) {
                                        collectMenuKeys($it['submenu'], $out);
                                    } else {
                                        $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
                                        if ($val) $out[] = $val;
                                    }
                                }
                            }

                            $isAdminRole = ($rol->nombre ?? '') === 'Administrador';
                            $allKeys = [];
                            collectMenuKeys($menu, $allKeys);
                            $allKeys = array_values(array_unique($allKeys));

                            function renderMenuItemsEdit($items, $rol, $isAdminRole, $allKeys) {
                                foreach ($items as $it) {
                                    if (isset($it['submenu'])) {
                                        echo '<div class="mb-1"><strong>'.e($it['text']).'</strong></div>';
                                        renderMenuItemsEdit($it['submenu'], $rol, $isAdminRole, $allKeys);
                                    } else {
                                        $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
                                        if (!$val) continue;
                                        $key = $val;
                                        if ($isAdminRole) {
                                            $checked = 'checked';
                                            $disabled = 'disabled';
                                        } else {
                                            $checked = in_array($key, (array)($rol->menu_permissions ?? [])) ? 'checked' : '';
                                            $disabled = '';
                                        }
                                        echo '<div class="form-check"><label class="form-check-label"><input type="checkbox" name="menu_permissions[]" value="'.e($key).'" '.$checked.' '.$disabled.'> '.e($it['text']).'</label></div>';
                                    }
                                }
                            }
                            renderMenuItemsEdit($menu, $rol, $isAdminRole, $allKeys);
                        @endphp
                        @if($isAdminRole)
                            <div class="mt-2 text-muted">Los permisos del rol <strong>Administrador</strong> están fijados a todas las opciones y no pueden modificarse aquí.</div>
                        @endif
                    </div>
                </div>
                @if(!($isProtected ?? false))
                    <button class="rd-btn rd-btn-primary">Actualizar</button>
                @else
                    <div class="alert alert-info">El rol <strong>{{ $rol->nombre }}</strong> está protegido y no puede ser editado desde aquí.</div>
                @endif
            </form>
        </div>
    </div>
@stop
