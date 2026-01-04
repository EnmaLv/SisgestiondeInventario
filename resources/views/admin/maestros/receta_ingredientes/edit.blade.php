@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Editar Ingredientes de Receta</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->persona->nombre_persona }}</strong>.
            </p>
        </div>

        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>

            <div
                style="width:46px;height:46px;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(15,23,42,0.08);">
                <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
    </div>
@stop


@section('content')
    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="rd-card p-4">

                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Modificar los campos del formulario</h3>

                    <div>
                        <a href="{{ url('admin/maestros/receta_ingredientes') }}" class="rd-btn rd-btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.maestros.receta_ingredientes.update', $receta->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Receta --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Receta</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                    <input type="text"
                                        class="form-control rd-filter-input"
                                        value="{{ $receta->nombre }}"
                                        disabled>

                                    <input type="hidden" name="recetas_id" value="{{ $receta->id }}">
                                </div>
                            </div>
                        </div>


                        {{-- Selector producto + cantidad + unidad --}}
                        <div class="col-md-8">
                            <label class="font-weight-bold">Editar ingredientes</label>
                            <div class="d-flex gap-2 mb-2">
                                <div style="flex:1">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                        <select class="form-control rd-filter-input" id="producto_select">
                                            <option value="">Seleccione un producto</option>
                                            @foreach ($productos as $producto)
                                                <option value="{{ $producto->id }}" data-nombre="{{ $producto->nombre }}">
                                                    {{ $producto->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div style="width:140px">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        <input type="number" step="any" min="0" id="cantidad_input"
                                            class="form-control rd-filter-input" placeholder="Cantidad">
                                    </div>
                                </div>

                                <div style="width:180px">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                        <select class="form-control" id="unidad_select">
                                            <option value="">Unidad</option>
                                            @foreach ($unidades as $unidad)
                                                <option value="{{ $unidad->id }}" data-nombre="{{ $unidad->nombre }}">
                                                    {{ $unidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div style="width:120px">
                                    <button type="button" class="btn rd-btn-primary" id="agregarProducto"
                                        style="height: 38px;"><i class="fas fa-plus"></i> Agregar</button>
                                </div>
                            </div>

                            <ul id="listaProductos" class="list-group">

                                @foreach ($receta->recetaIngredientes as $ing)
                                    <li class="list-group-item d-flex justify-content-between align-items-center"
                                        id="prod_{{ $ing->producto_id }}">
                                        <div>
                                            <strong>{{ $ing->producto->nombre }}</strong> —
                                            {{ $ing->cantidad_porcion }}
                                            {{ $ing->unidad->nombre }}
                                        </div>

                                        <div>
                                            <input type="hidden" name="producto_id[]" value="{{ $ing->producto_id }}">
                                            <input type="hidden" name="cantidad_porcion[]" value="{{ $ing->cantidad_porcion }}">
                                            <input type="hidden" name="unidad_id[]" value="{{ $ing->unidad_id }}">

                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="this.closest('li').remove();">
                                                X
                                            </button>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>

                            {{-- Errores --}}
                            @error('producto_id.*')
                                <div class="text-danger"><b>{{ $message }}</b></div>
                            @enderror

                            @error('cantidad_porcion.*')
                                <div class="text-danger"><b>{{ $message }}</b></div>
                            @enderror

                            @error('unidad_id.*')
                                <div class="text-danger"><b>{{ $message }}</b></div>
                            @enderror
                        </div>

                    </div> {{-- row --}}

                    <hr class="mt-4">

                    <div class="mt-4 d-flex justify-content-end">
                        <a href="{{ url('admin/maestros/receta_ingredientes') }}"
                            class="rd-btn rd-btn-default">Cancelar</a>
                        <button type="submit" class="rd-btn rd-btn-primary ml-2">
                            <i class="fas fa-check"></i> Actualizar
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

@push('css')
    <style>
        .rd-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .rd-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .rd-title-sm {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1a202c;
            margin: 0;
        }

        .rd-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #4a5568;
            font-size: 0.9375rem;
        }

        /* Estilos para grupos de entrada */
        .rd-card .rd-input-group {
            margin-bottom: 1.25rem;
        }

        .rd-card .input-group {
            border: 1px solid #d8dee9;
            border-radius: 12px;
            padding-inline: 8px;
            transition: border-color .2s ease, box-shadow .2s ease;
            overflow: hidden;
        }

        .input-group:focus-within {
            border-color: #7c3aed;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 1.05rem;
            padding: 0 0.5rem;
        }

        .input-group-text i {
            width: 22px;
            text-align: center;
        }

        /* Estilos para inputs */
        .rd-card .form-control,
        .rd-card .form-select {
            border: none;
            background: transparent;
            box-shadow: none;
            padding: 0.75rem 0.5rem;
            height: auto;
            font-size: 0.9375rem;
            color: #2d3748;
        }

        .form-control:focus,
        .form-select:focus {
            outline: none;
            box-shadow: none;
        }

        /* Estilos para el editor CKEditor */
        .ck.ck-editor {
            width: 100% !important;
            margin-top: 0.5rem;
        }

        .ck.ck-editor__editable {
            width: 100% !important;
            min-height: 200px;
            border: 1px solid #d8dee9 !important;
            border-radius: 0 0 12px 12px !important;
            padding: 1rem !important;
            color: #2d3748;
        }

        .ck.ck-toolbar {
            border: 1px solid #d8dee9 !important;
            border-bottom: none !important;
            border-radius: 12px 12px 0 0 !important;
            background-color: #f8fafc !important;
        }

        /* Estilos para la vista previa de imágenes */
        .image-preview {
            width: 100%;
            max-width: 200px;
            height: auto;
            border-radius: 8px;
            border: 2px dashed #d8dee9;
            padding: 0.5rem;
            margin-top: 0.5rem;
        }

        /* Botones */
        .rd-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.6rem 1.25rem;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.9375rem;
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid transparent;
        }

        .rd-btn i {
            margin-right: 0.5rem;
        }

        .rd-btn-primary {
            background-color: #7c3aed;
            color: white;
        }

        .rd-btn-primary:hover {
            background-color: #6d28d9;
        }

        .rd-btn-default {
            background-color: #f1f5f9;
            color: #475569;
            border-color: #e2e8f0;
        }


        /* Mensajes de error */
        .text-danger {
            color: #dc2626;
            font-size: 0.8125rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Ajustes responsivos */
        @media (max-width: 768px) {
            .rd-card {
                padding: 1rem;
            }

            .ck.ck-editor__editable {
                min-height: 250px;
            }
        }
    </style>
@endpush

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectProd = document.getElementById('producto_select');
            const cantidadInput = document.getElementById('cantidad_input');
            const unidadSelect = document.getElementById('unidad_select');
            const btnAgregar = document.getElementById('agregarProducto');
            const lista = document.getElementById('listaProductos');

            function crearItem(prodId, prodNombre, cantidad, unidadId, unidadNombre) {

                const li = document.createElement('li');
                li.className = 'list-group-item d-flex justify-content-between align-items-center';
                li.id = 'prod_' + prodId;

                li.innerHTML = `
            <div>
                <strong>${prodNombre}</strong> — ${cantidad} ${unidadNombre}
            </div>
            <div>
                <input type="hidden" name="producto_id[]" value="${prodId}">
                <input type="hidden" name="cantidad_porcion[]" value="${cantidad}">
                <input type="hidden" name="unidad_id[]" value="${unidadId}">
                <button type="button" class="btn btn-danger btn-sm">X</button>
            </div>
        `;

                li.querySelector('button').addEventListener('click', () => li.remove());

                return li;
            }

            btnAgregar.addEventListener('click', function() {

                const prodId = selectProd.value;
                const prodNombre = selectProd.options[selectProd.selectedIndex]?.text || '';
                const cantidad = cantidadInput.value;
                const unidadId = unidadSelect.value;
                const unidadNombre = unidadSelect.options[unidadSelect.selectedIndex]?.text || '';

                if (!prodId) return alert('Seleccione un producto.');
                if (!cantidad || Number(cantidad) <= 0) return alert('Ingrese una cantidad válida.');
                if (!unidadId) return alert('Seleccione una unidad.');

                const item = crearItem(prodId, prodNombre, cantidad, unidadId, unidadNombre);
                lista.appendChild(item);

                selectProd.selectedIndex = 0;
                cantidadInput.value = '';
                unidadSelect.selectedIndex = 0;
            });
        });
    </script>
@endsection
