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

                <form action="{{ route('admin.maestros.receta_ingredientes.update', $recetaIngrediente->id) }}"
                    method="POST">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        {{-- Receta --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold">Recetas</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                    <select class="form-control rd-filter-input" name="recetas_id" id="recetas_id">
                                        <option value="">Seleccione una receta</option>

                                        @foreach ($recetas as $receta)
                                            <option value="{{ $receta->id }}"
                                                {{ old('recetas_id', $recetaIngrediente->recetas_id) == $receta->id ? 'selected' : '' }}>
                                                {{ $receta->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('recetas_id')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>
                        </div>

                        {{-- Selector producto + cantidad + unidad --}}
                        <div class="col-md-6">
                            <label class="font-weight-bold">Editar ingredientes</label>
                            <div class="d-flex gap-2 mb-2">
                                <div style="flex:1">
                                    <select class="form-control rd-filter-input" id="producto_select">
                                        <option value="">Seleccione un producto</option>
                                        @foreach ($productos as $producto)
                                            <option value="{{ $producto->id }}" data-nombre="{{ $producto->nombre }}">
                                                {{ $producto->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div style="width:140px">
                                    <input type="number" step="any" min="0" id="cantidad_input"
                                        class="form-control" placeholder="Cantidad">
                                </div>

                                <div style="width:180px">
                                    <select class="form-control" id="unidad_select">
                                        <option value="">Unidad</option>
                                        @foreach ($unidades as $unidad)
                                            <option value="{{ $unidad->id }}" data-nombre="{{ $unidad->nombre }}">
                                                {{ $unidad->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div style="width:120px">
                                    <button type="button" class="btn rd-btn-primary" id="agregarProducto">Agregar</button>
                                </div>
                            </div>

                            {{-- Lista visual (precargada) --}}
                            <ul id="listaProductos" class="list-group">

                                {{-- Ingrediente actual --}}
                                <li class="list-group-item d-flex justify-content-between align-items-center"
                                    id="prod_{{ $recetaIngrediente->producto->id }}">
                                    <div>
                                        <strong>{{ $recetaIngrediente->producto->nombre }}</strong> —
                                        {{ $recetaIngrediente->cantidad_porcion }}
                                        {{ $recetaIngrediente->unidad->nombre }}
                                    </div>
                                    <div>
                                        <input type="hidden" name="producto_id[]"
                                            value="{{ $recetaIngrediente->producto->id }}">
                                        <input type="hidden" name="cantidad_porcion[]"
                                            value="{{ $recetaIngrediente->cantidad_porcion }}">
                                        <input type="hidden" name="unidad_id[]"
                                            value="{{ $recetaIngrediente->unidad_id }}">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="this.closest('li').remove();">X</button>
                                    </div>
                                </li>

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
