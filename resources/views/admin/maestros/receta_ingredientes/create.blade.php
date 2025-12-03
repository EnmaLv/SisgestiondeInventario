@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Crear Ingredientes de Receta</h1>
            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
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
                    <h3 class="rd-title-sm">Agregar ingredientes a la receta</h3>

                    <div>
                        <a href="{{ url('admin/maestros/receta_ingredientes') }}" class="rd-btn rd-btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.maestros.receta_ingredientes.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        {{-- Receta --}}
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="font-weight-bold">Recetas</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                    <select class="form-control rd-filter-input" name="recetas_id" id="recetas_id">
                                        <option value="" selected disabled>Seleccione una receta</option>

                                        @foreach ($recetas as $receta)
                                            <option value="{{ $receta->id }}"
                                                {{ old('recetas_id') == $receta->id ? 'selected' : '' }}>
                                                {{ $receta->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                @error('recetas_id')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                                @if ($recetas->isEmpty())
                                    <div>No tienes recetas registradas, <a
                                            href="{{ route('admin.maestros.recetas.create') }}">preciona aqui</a> para crear
                                        una receta.</div>
                                @endif
                            </div>
                        </div>

                        {{-- Selector producto + cantidad + unidad --}}
                        <div class="col-md-8">
                            <label class="font-weight-bold">Agregar ingredientes</label>
                            <div class="d-flex gap-2 mb-2">
                                <div style="flex:1">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                        <select class="form-control rd-filter-input" id="producto_select">
                                            <option value="" selected disabled>Seleccione un producto</option>
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
                                        <input type="number" step="any" min="0" id="cantidad_porcion"
                                            class="form-control" placeholder="Cantidad">
                                    </div>
                                </div>

                                <div style="width:180px">
                                    <div class="input-group mb-2">
                                        <span class="input-group-text"><i class="fas fa-ruler"></i></span>
                                        <select class="form-control" id="unidad_select">
                                            <option value="" selected disabled>Unidad</option>
                                            @foreach ($unidades as $unidad)
                                                <option value="{{ $unidad->id }}" data-nombre="{{ $unidad->nombre }}">
                                                    {{ $unidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div style="width:120px">
                                    <button type="button" class="btn btn-primary" id="agregarProducto"
                                        style="height: 38px;" @disabled($recetas->isEmpty() || $productos->isEmpty())
                                        style="@if ($recetas->isEmpty() || $productos->isEmpty()) opacity: 0.5!important; cursor: not-allowed!important; @endif">
                                        <i class="fas fa-plus"></i> Agregar
                                    </button>
                                </div>
                            </div>
                            @if ($productos->isEmpty())
                                <div>No tienes productos registrados, <a
                                        href="{{ route('admin.maestros.productos.create') }}">preciona aqui</a> para crear
                                    un producto.</div>
                            @endif

                            {{-- Lista visual --}}
                            <ul id="listaProductos" class="list-group">
                                {{-- Lista vacía al crear --}}
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
                        <button type="submit" class="rd-btn rd-btn-primary ml-2" @disabled($recetas->isEmpty() || $productos->isEmpty())
                            style="@if ($recetas->isEmpty() || $productos->isEmpty()) opacity: 0.5!important; cursor: not-allowed!important; @endif">
                            <i class="fas fa-check"></i> Crear
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
        .rd-card .input-group {
            border: 1px solid #d8dee9;
            border-radius: 12px;
            padding-inline: 8px;
            transition: border-color .2s ease, box-shadow .2s ease;
            overflow: hidden;
        }

        .rd-card .input-group:focus-within {
            border-color: #7c3aed;
            background: #ffffff;
        }

        .rd-card .input-group-text {
            background: transparent;
            border: none;
            color: #64748b;
            font-size: 1.05rem;
            padding-left: 4px;
            padding-right: 4px;
        }

        .rd-card .input-group-text i {
            width: 22px;
            text-align: center;
        }

        .rd-card .rd-filter-input,
        .rd-card .form-control {
            border: none;
            background: transparent;
            box-shadow: none;
            padding-left: 6px;
        }

        .rd-card textarea.form-control {
            border: 1px solid #d8dee9;
            border-radius: 12px;
            padding: 0.5rem 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            width: 100%;
            min-height: 120px;
            resize: vertical;
        }

        .rd-card textarea.form-control:focus {
            border-color: #7c3aed;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
            outline: none;
        }

        /* Para el caso de textarea dentro de un input-group */
        .rd-card .input-group textarea.form-control {
            border: none;
            background: transparent;
            box-shadow: none;
            padding-left: 6px;
            min-height: 38px;
            resize: none;
        }

        .rd-card .input-group:focus-within textarea.form-control {
            background: transparent;
        }
    </style>
@endpush

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectProd = document.getElementById('producto_select');
            const cantidadInput = document.getElementById('cantidad_porcion');
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
