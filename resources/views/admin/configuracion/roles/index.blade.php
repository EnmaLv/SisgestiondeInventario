@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center"
        style="background: #ffffff; border-radius: 14px; box-shadow: 0 4px 14px rgba(0,0,0,0.06); border: 1px solid #e5e7eb;">

        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">Configuración de Roles</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                <i class="fas fa-user-tag mr-1" style="color: var(--color-secondary)"></i> Definición de permisos y accesos
            </p>
        </div>

        <a href="{{ route('admin.configuracion.roles.create') }}" class="rd-btn rd-btn-primary px-4">
            <i class="fas fa-plus-circle"></i> Crear Nuevo Rol
        </a>
    </div>
@stop

@section('content')
    @include('components.alert')

    <div class="rd-card rd-card-full shadow-sm border-0 overflow-hidden">
        <div class="rd-card-body border-bottom bg-white">
            <form action="{{ route('admin.configuracion.roles.index') }}" method="GET">
                <div class="flex flex-wrap -mx-2 items-center">
                    <div class="w-full md:w-1/2">
                        <h3 class="rd-title-sm">Listado de Roles</h3>
                    </div>
                    <div class="w-full md:w-1/2 flex justify-end">
                        <div class="flex items-center gap-2"> 
                            <div class="flex items-stretch w-full">
                                <span><i class="fas fa-search"></i></span>
                                <input type="text" name="q" value="{{ request('q') }}" class="rd-input w-100" placeholder="Buscar por nombre o descripción...">
                            </div>
                            <button type="submit" class="rd-btn rd-btn-primary">
                                Buscar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="rd-table-container">
            <table class="rd-table">
                <thead>
                    <tr>
                        <th class="text-center" style="width:60px">#</th>
                        <th>Nombre del Rol</th>
                        <th>Descripción</th>
                        <th class="text-center" style="width:160px">Acciones</th>
                    </tr>
                </thead>
                <tbody class="fade-in">
                    @forelse($roles as $rol)
                        <tr>
                            <td class="text-center text-muted font-weight-bold">
                                {{ ($roles->currentPage()-1)*$roles->perPage()+$loop->iteration }}
                            </td>
                            <td>
                                <span class="rd-badge {{ in_array(strtolower($rol->nombre), ['administrador', 'empleado', 'obrero']) ? 'rd-badge-success' : 'rd-badge-warning' }}" 
                                      style="font-size: 0.85rem; letter-spacing: 0.3px;">
                                    {{ strtoupper($rol->nombre) }}
                                </span>
                            </td>
                            <td>
                                <span class="text-muted" title="{{ $rol->descripcion }}">
                                    {{ \Illuminate\Support\Str::limit($rol->descripcion, 80) ?? 'Sin descripción' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @php
                                    $protected = ['Empleado','Obrero','Administrador'];
                                    $isProtected = in_array(strtolower($rol->nombre ?? ''), array_map('strtolower', $protected));
                                @endphp
                                
                                <div class="flex justify-center gap-2">
                                    @if(!$isProtected)
                                        <a href="{{ route('admin.configuracion.roles.edit', $rol->id_rol) }}" 
                                           class="rd-action" title="Editar permisos">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <form action="{{ route('admin.configuracion.roles.destroy', $rol->id_rol) }}" 
                                              method="POST" class="inline delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="rd-action rd-btn-danger" title="Eliminar Rol">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>

                                        <script>
                                            document.querySelectorAll('.delete-form button').forEach(button => {
                                                button.addEventListener('click', function(e) {
                                                    e.preventDefault();
                                                    const form = this.closest('.delete-form');
                                                    
                                                    // Si usas SweetAlert2:
                                                    Swal.fire({
                                                        title: '¿Estás seguro?',
                                                        text: "Se eliminarán los accesos y módulos asociados a este rol.",
                                                        icon: 'warning',
                                                        showCancelButton: true,
                                                        confirmButtonColor: '#3085d6',
                                                        cancelButtonColor: '#d33',
                                                        confirmButtonText: 'Sí, eliminar',
                                                        cancelButtonText: 'Cancelar'
                                                    }).then((result) => {
                                                        if (result.isConfirmed) {
                                                            form.submit(); // Aquí es donde se envía realmente
                                                        }
                                                    });
                                                });
                                            });
                                        </script>
                                    @else
                                        <span class="badge badge-light border text-muted px-3 py-2 text-center" style="border-radius: 8px;">
                                            <i class="fas fa-lock "></i> Protegido
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-shield-alt fa-3x mb-3" style="opacity: 0.1"></i>
                                    <p>No se encontraron roles registrados.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="rd-card-body border-top bg-light flex justify-center">
            {{ $roles->appends(request()->query())->links('components.pagination') }}
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function(){
        // Integración de SweetAlert2 para la eliminación
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(){
                Swal.fire({
                    title: '¿Eliminar Rol?',
                    text: "Esto podría afectar el acceso de los usuarios vinculados.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: 'var(--color-primary)',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit();
                    }
                })
            });
        });
    });
</script>
@stop