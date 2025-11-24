@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Editar Producto #{{ $producto->id }}</h1>
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
                    <h3 class="rd-title-sm">Editar producto</h3>

                    <div>
                        <a href="{{ url('admin/maestros/productos') }}" class="rd-btn rd-btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>

                <form action="{{ route('admin.maestros.productos.update', $producto->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                                <option value="">Seleccione una categoría</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}"
                                                        {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
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
                                            <input type="text" class="form-control rd-filter-input" name="codigo"
                                                value="{{ old('codigo', $producto->codigo) }}"
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
                                            <input type="text" class="form-control rd-filter-input" name="nombre"
                                                value="{{ old('nombre', $producto->nombre) }}"
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
                                <textarea name="descripcion" id="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                @error('descripcion')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>

                            <div class="row mt-3">

                                {{-- Precio Compra --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Precio Compra</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                            <input type="number" class="form-control rd-filter-input" name="precio_compra"
                                                value="{{ old('precio_compra', $producto->precio_compra) }}"
                                                placeholder="$$">
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
                                                value="{{ old('precio_venta', $producto->precio_venta) }}"
                                                placeholder="$$">
                                        </div>
                                        @error('precio_venta')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Stock Mínimo --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Stock Mínimo</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-arrow-down"></i></span>
                                            <input type="number" class="form-control rd-filter-input"
                                                name="stock_minimo"
                                                value="{{ old('stock_minimo', $producto->stock_minimo) }}"
                                                placeholder="Mínimo">
                                        </div>
                                        @error('stock_minimo')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Stock Máximo --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Stock Máximo</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-arrow-up"></i></span>
                                            <input type="number" class="form-control rd-filter-input"
                                                name="stock_maximo"
                                                value="{{ old('stock_maximo', $producto->stock_maximo) }}"
                                                placeholder="Máximo">
                                        </div>
                                        @error('stock_maximo')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Unidad --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Unidad Medida</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                            <select class="form-control rd-filter-input" name="unidad_medida">
                                                <option value="">Seleccione</option>
                                                <option value="U"
                                                    {{ old('unidad_medida', $producto->unidad_medida) == 'U' ? 'selected' : '' }}>
                                                    Unidad</option>
                                                <option value="KG"
                                                    {{ old('unidad_medida', $producto->unidad_medida) == 'KG' ? 'selected' : '' }}>
                                                    Kilogramo</option>
                                                <option value="G"
                                                    {{ old('unidad_medida', $producto->unidad_medida) == 'G' ? 'selected' : '' }}>
                                                    Gramo</option>
                                                <option value="L"
                                                    {{ old('unidad_medida', $producto->unidad_medida) == 'L' ? 'selected' : '' }}>
                                                    Litro</option>
                                                <option value="ML"
                                                    {{ old('unidad_medida', $producto->unidad_medida) == 'ML' ? 'selected' : '' }}>
                                                    Mililitro</option>
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
                                                <option value="">Seleccione</option>
                                                <option value="1"
                                                    {{ old('estado', $producto->estado) == '1' ? 'selected' : '' }}>Activo
                                                </option>
                                                <option value="0"
                                                    {{ old('estado', $producto->estado) == '0' ? 'selected' : '' }}>
                                                    Inactivo</option>
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

                                <img id="imgPreview" class="mt-2" src="{{ asset('storage/' . $producto->imagen) }}"
                                    style="width:100%; border-radius:10px; box-shadow:0 4px 14px rgba(0,0,0,0.08);" />

                                <script>
                                    function previewImage(event) {
                                        const file = event.target.files[0];
                                        if (file) {
                                            const reader = new FileReader();
                                            reader.onload = function(e) {
                                                document.querySelector('#imgPreview').src = e.target.result;
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

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ url('admin/maestros/productos') }}" class="rd-btn rd-btn-default">Cancelar</a>
                        <button type="submit" class="rd-btn rd-btn-warning">
                            <i class="fas fa-save"></i> Guardar Cambios
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

@section('css')
    <style>
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
@stop
@section('js')
    <script>
        ClassicEditor
            .create(document.querySelector('#descripcion'), {
                toolbar: {
                    items: [
                        'heading', '|',
                        'bold', 'italic', 'underline', 'strikethrough', 'subscript', '|',
                        'link', 'bulletedList', 'numberedList', '|',
                        'outdent', 'indent', '|',
                        'blockQuote', 'insertTable', 'mediaEmbed', '|',
                        'undo', 'redo', '|',
                        'footBackgroundColor', 'fontColor', 'fontSize', 'fontFamily', '|',
                        'code', 'codeBlock', 'htmlEmbed', '|',
                        'sourceEditing'
                    ],
                    shouldNotGroupWhenFull: true
                },
                language: 'es'
            })
            .then(editor => {
                const editorEl = editor.ui.view.element;
                editorEl.style.width = '100%';
                editorEl.querySelector('.ck-editor__editable').style.width = '100%';
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@stop
