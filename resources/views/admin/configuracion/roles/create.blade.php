@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">Crear Rol</h1>
        </div>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full">
        <div class="rd-card-body">
            <form action="{{ route('admin.configuracion.roles.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" class="form-control" required value="{{ old('nombre') }}" style="border:1px solid #cbd5e1;">
                </div>
                <div class="form-group">
                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" style="border:1px solid #cbd5e1;">{{ old('descripcion') }}</textarea>
                </div>
                <div class="form-group">
                    <label>Permisos de Menú</label>
                    <div class="pl-2" style="max-height:220px; overflow:auto; border:1px solid #e5e7eb; padding:8px; border-radius:6px; background:#fff;">
                        @php
                            function renderMenuItems($items, $prefix = '') {
                                foreach ($items as $it) {
                                    if (isset($it['submenu'])) {
                                        echo '<div class="mb-1"><strong>'.$it['text'].'</strong></div>';
                                        renderMenuItems($it['submenu'], $prefix);
                                    } else {
                                        $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
                                        if (!$val) continue;
                                        $key = $val;
                                        echo '<div class="form-check"><label class="form-check-label"><input type="checkbox" name="menu_permissions[]" value="'.e($key).'"> '.e($it['text']).'</label></div>';
                                    }
                                }
                            }
                            renderMenuItems($menu);
                        @endphp
                    </div>
                </div>
                <button class="rd-btn rd-btn-primary">Guardar</button>
            </form>
        </div>
    </div>
@stop
