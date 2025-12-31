<!-- Empleados index (migrated from empleos) -->
@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">

        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Gestión de Empleados</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">Usuario: <strong>{{ auth()->user()->username }}</strong></p>
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
                    <h3 class="rd-title-sm">Empleados</h3>
                </div>

                <div class="rd-actions d-flex align-items-center">
                    <div id="filtersPanel" class="rd-filters-panel" style="min-width:320px;background:#ffffff;border:1px solid #e6e9ef;padding:12px;border-radius:10px;box-shadow:0 6px 18px rgba(15,23,42,0.06);height:0;opacity:0;overflow:hidden;transition:height .28s ease, opacity .18s ease;">
                        <form action="{{ route('admin.configuracion.empleados.index') }}" method="GET" class="form-inline" role="search">
                            <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center">
                                <select name="rol" class="form-control" style="border:1px solid #cbd5e1;min-width:200px">
                                    <option value="">Filtrar por Rol</option>
                                    @foreach($roles as $r)
                                        <option value="{{ $r->id_rol }}" {{ request('rol') == $r->id_rol ? 'selected' : '' }}>{{ $r->nombre }}</option>
                                    @endforeach
                                </select>

                                <div style="display:flex;gap:6px;align-items:center">
                                    <button id="btnBuscar" class="rd-btn rd-btn-primary" type="submit">Buscar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <button id="toggleFilters" type="button" class="rd-btn rd-btn-ghost" title="Filtros" style="border-radius:8px;padding:8px 10px;margin-left:auto;">
                        <i class="fas fa-filter" style="font-size:1rem;color:#0f172a"></i>
                    </button>
                </div>
            </div>

            <div id="printArea">
                <table class="rd-table">
                    <thead>
                        <tr>
                            <th style="width:60px">#</th>
                            <th>Usuario</th>
                            <th>Nombre y Apellido</th>
                            <th>Rol</th>
                            <th style="width:150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($usuarios as $usuario)
                            <tr>
                                <td class="text-center">{{ ($usuarios->currentPage() - 1) * $usuarios->perPage() + $loop->iteration }}</td>
                                <td>{{ $usuario->username }}</td>
                                <td>{{ optional($usuario->persona)->nombre ?? '—' }}</td>
                                <td>{{ $usuario->roles->pluck('nombre')->join(', ') ?: ($usuario->perfil->nombre_perfil ?? '—') }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.configuracion.empleados.show', $usuario->id_usuario) }}" class="rd-action" title="Ver"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.configuracion.empleados.edit', $usuario->id_usuario) }}" class="rd-action mx-2" title="Editar"><i class="fas fa-edit"></i></a>
                                        @php
                                            $auth = auth()->user();
                                            $isSelfAdmin = $auth && $auth->id_usuario == $usuario->id_usuario && $auth->roles->contains('nombre', 'Administrador');
                                        @endphp
                                        @if(!$isSelfAdmin)
                                            <form action="{{ route('admin.configuracion.empleados.destroy', $usuario->id_usuario) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('¿Seguro que desea eliminar este empleado?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rd-action" title="Eliminar" style="background:none;border:none;padding:0;color:#ef4444"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @else
                                            <span class="text-muted" title="No puedes eliminarte a ti mismo">—</span>
                                        @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">No hay usuarios</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3 d-flex justify-content-center">
                {{ $usuarios->onEachSide(1)->links('components.pagination') }}
            </div>
        </div>
    </div>
        @stop

        @section('js')
        <script>
        document.addEventListener('DOMContentLoaded', function(){
            var btn = document.getElementById('toggleFilters');
            var panel = document.getElementById('filtersPanel');
            if(!btn || !panel) return;

            function openPanel(){
                // Set explicit height to enable transition
                var full = panel.scrollHeight;
                panel.style.height = full + 'px';
                panel.style.opacity = '1';
                panel.classList.add('open');
                btn.setAttribute('aria-expanded','true');
            }

            function closePanel(){
                // From auto-height: set height to current scrollHeight then to 0
                var full = panel.scrollHeight;
                panel.style.height = full + 'px';
                // force reflow
                panel.offsetHeight;
                panel.style.height = '0';
                panel.style.opacity = '0';
                panel.classList.remove('open');
                btn.setAttribute('aria-expanded','false');
            }

            btn.addEventListener('click', function(){
                var isOpen = panel.classList.contains('open');
                if(!isOpen){
                    openPanel();
                } else {
                    closePanel();
                }
            });

            // When transition ends and panel is open, set height to auto to allow internal changes
            panel.addEventListener('transitionend', function(e){
                if(e.propertyName === 'height' && panel.classList.contains('open')){
                    panel.style.height = 'auto';
                }
            });

            // Ensure filter form submits properly by constructing query and redirecting
            var filtrosForm = document.querySelector('#filtersPanel form');
            if(filtrosForm){
                filtrosForm.addEventListener('submit', function(evt){
                    evt.preventDefault();
                    var params = new URLSearchParams(window.location.search);
                    var rol = filtrosForm.querySelector('select[name="rol"]').value;
                    if(rol){ params.set('rol', rol); } else { params.delete('rol'); }
                    // Preserve other existing params except page
                    params.delete('page');
                    var url = window.location.pathname + '?' + params.toString();
                    window.location.href = url;
                });
            }
        });
        </script>
        @stop
