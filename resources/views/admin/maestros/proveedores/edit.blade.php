@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 flex justify-between items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Editar Proveedor</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>
        <div class="flex items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>
            <div
                style="width:46px;height:46px;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(15,23,42,0.08);">
                <img src="{{ asset('img/usuario-verificado.webp') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="flex flex-wrap -mx-2">
        <div class="w-full mx-auto">
            <div class="rd-card p-4">
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Editar Proveedor</h3>

                    <a href="{{ url('admin/maestros/proveedores') }}" class="rd-btn rd-btn-default">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
                <form action="{{ route('admin.maestros.proveedores.update', $proveedor->id) }}" method="POST"
                    class="rd-prevent-double-submit">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-wrap -mx-2">
                        <div class="w-full md:w-1/3">
                            <div class="form-group">
                                <label class="font-weight-bold">Empresa</label>
                                <div class="flex items-stretch w-full mb-2">
                                    <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-building"></i></span>
                                    <input type="text" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input" name="empresa"
                                        value="{{ old('empresa', $proveedor->empresa) }}"
                                        placeholder="Nombre de la empresa">
                                </div>
                                @error('empresa')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>
                        </div>
                        <div class="w-full md:w-1/3">
                            <div class="form-group">
                                <label class="font-weight-bold">Dirección</label>
                                <div class="flex items-stretch w-full mb-2">
                                    <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                        value="{{ old('direccion', $proveedor->direccion) }}" name="direccion"
                                        placeholder="Dirección completa">
                                </div>
                                @error('direccion')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>
                        </div>
                        <div class="w-full md:w-1/3">
                            <div class="form-group">
                                <label class="font-weight-bold">Nombre del Proveedor</label>
                                <div class="flex items-stretch w-full mb-2">
                                    <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-user"></i></span>
                                    <input type="text" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input"
                                        value="{{ old('nombre', $proveedor->nombre) }}" name="nombre"
                                        placeholder="Nombre del proveedor">
                                </div>
                                @error('nombre')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-2 mt-3">
                        <div class="w-full md:w-1/3">
                            <div class="form-group">
                                <label class="font-weight-bold">Teléfono</label>
                                <div class="flex items-stretch w-full mb-2">
                                    <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-phone"></i></span>
                                    <input type="text" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input" name="telefono"
                                        value="{{ old('telefono', $proveedor->telefono) }}" id="telefono"
                                        data-inputmask="'mask': '(999) 999-9999'" data-mask placeholder="(123) 456-7890">
                                </div>
                                @error('telefono')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>
                        </div>
                        <div class="w-full md:w-1/3">
                            <div class="form-group">
                                <label class="font-weight-bold">Email</label>
                                <div class="flex items-stretch w-full mb-2">
                                    <span class="inline-flex items-center px-3 border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="block w-full rounded-lg border px-3 py-2 text-sm rd-filter-input" name="email"
                                        value="{{ old('email', $proveedor->email) }}" placeholder="correo@empresa.com">
                                </div>
                                @error('email')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="flex justify-end gap-2">
                        <a href="{{ url('admin/maestros/proveedores') }}" class="rd-btn rd-btn-default">
                            Cancelar
                        </a>
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $("[data-mask]").inputmask();
        });
    </script>
@stop
