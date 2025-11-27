@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Crear Nuevo Producto</h1>
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
                    <h3 class="rd-title-sm">Crear producto</h3>

                    <div>
                        <a href="{{ url('admin/maestros/productos') }}" class="rd-btn rd-btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.maestros.productos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-9">

                            <div class="row">

                                {{-- Categoría --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Categoría</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                            <select class="form-control rd-filter-input" id="categoria_id"
                                                name="categoria_id">
                                                <option selected disabled>Seleccione una categoría</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}"
                                                        {{ old('categoria_id') == $categoria->id ? 'selected' : '' }}>
                                                        {{ $categoria->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('categoria_id')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Código --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Código</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            <input type="text" value="{{ old('codigo') }}"
                                                class="form-control rd-filter-input" name="codigo"
                                                placeholder="Código del producto">
                                        </div>
                                        @error('codigo')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Nombre --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Nombre</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            <input type="text" value="{{ old('nombre') }}"
                                                class="form-control rd-filter-input" name="nombre"
                                                placeholder="Nombre del producto">
                                        </div>
                                        @error('nombre')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Descripción (CKEditor) --}}
                            <div class="form-group mt-3">
                                <label class="font-weight-bold">Descripción</label>
                                <textarea name="descripcion" id="descripcion">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>

                            <div class="row mt-3">

                                {{-- Precio Compra --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Precio Compra</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                            <input type="number" class="form-control rd-filter-input" name="precio_compra"
                                                value="{{ old('precio_compra') }}" placeholder="$$" min="0">
                                        </div>
                                        @error('precio_compra')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Precio Venta --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Precio Venta</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                            <input type="number" class="form-control rd-filter-input" name="precio_venta"
                                                value="{{ old('precio_venta') }}" placeholder="$$" min="0">
                                        </div>
                                        @error('precio_venta')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Stock Mínimo --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Stock Mínimo</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-arrow-down"></i></span>
                                            <input type="number" class="form-control rd-filter-input"
                                                name="stock_minimo" value="{{ old('stock_minimo') }}" min="1"
                                                placeholder="Mínimo">
                                        </div>
                                        @error('stock_minimo')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Stock Máximo --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Stock Máximo</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-arrow-up"></i></span>
                                            <input type="number" class="form-control rd-filter-input"
                                                name="stock_maximo" value="{{ old('stock_maximo') }}" min="1"
                                                placeholder="Máximo">
                                        </div>
                                        @error('stock_maximo')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Unidad de Medida --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Unidad de Medida</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                            <select class="form-control rd-filter-input" name="unidad_medida">
                                                <option selected disabled>Seleccione</option>
                                                <option value="U">Unidad</option>
                                                <option value="KG">Kilogramo</option>
                                                <option value="G">Gramo</option>
                                                <option value="L">Litro</option>
                                                <option value="ML">Mililitro</option>
                                            </select>
                                        </div>
                                        @error('unidad_medida')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Estado --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Estado</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                            <select class="form-control rd-filter-input" name="estado">
                                                <option selected disabled>Seleccione</option>
                                                <option value="1">Activo</option>
                                                <option value="0">Inactivo</option>
                                            </select>
                                        </div>
                                        @error('estado')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Right Column — Imagen --}}
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Imagen del producto</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                    <input type="file" name="imagen" id="imagen"
                                        class="form-control rd-filter-input" accept="image/*"
                                        onchange="previewImage(event)">
                                </div>

                                <img id="imgPreview"
                                    style="width: 100%; display:none; border-radius:10px; box-shadow:0 4px 14px rgba(0,0,0,0.08);" />

                                <script>
                                    function previewImage(event) {
                                        const file = event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                const img = document.getElementById('imgPreview');
                                                img.style.display = 'block';
                                                img.src = e.target.result;
                                            };
                                            reader.readAsDataURL(file);
                                        }
                                    }
                                </script>

                                @error('imagen')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-end gap-2" style="gap: 15px">
                        <a href="{{ url('admin/maestros/productos') }}" class="rd-btn rd-btn-default">Cancelar</a>
                        <button type="submit" class="rd-btn rd-btn-primary">
                            <i class="fas fa-check"></i> Crear
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@stop
@push('css')
    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
@endpush


@push('css')
    <style>
        .rd-card .input-group {
            border: 1px solid #d8dee9;
            border-radius: 12px;
            background: #f8fafc;
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

        .rd-card .rd-filter-input:focus,
        .rd-card .form-control:focus {
            border: none;
            box-shadow: none;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
        }

        .ck.ck-editor {
            width: 100% !important;
        }

        .ck.ck-editor__editable {
            width: 100% !important;
            min-height: 300px;
            box-sizing: border-box;
        }

        @media (max-width: 768px) {
            .ck.ck-editor__editable {
                min-height: 250px;
                padding: 10px;
            }
        }
    </style>
@endpush
@section('js')
    <script>
        let descripcionEditor;
        ClassicEditor
            .create(document.querySelector('#descripcion'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', 'subscript', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', 'mediaEmbed', '|',
                        'undo', 'redo'
                    ],
                    shouldNotGroupWhenFull: true
                },
                language: 'es'
            })
            .then(editor => {
                descripcionEditor = editor;
                const editorEl = editor.ui.view.element;
                editorEl.style.width = '100%';
                editorEl.querySelector('.ck-editor__editable').style.width = '100%';
            })
            .catch(error => {
                console.error(error);
            });

        // Antes de enviar el formulario, aseguramos que el textarea tenga el contenido
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (descripcionEditor) {
                        document.querySelector('#descripcion').value = descripcionEditor.getData();
                    }
                });
            }
        });
    </script>
@stop
