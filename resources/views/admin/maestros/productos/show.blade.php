@extends('adminlte::page')

@section('content_header')
    <h1>Crea un nuevo Producto</h1>
    <p>Bienvenido {{ auth()->user()->name }}.</p>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="card card-info">
                <div class="card-header">
                    <h3 class="card-title"><b>Productos Registrados</b></h3>

                    <div class="card-tools">
                        <a href="{{ url('admin/maestros/productos') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-left"></i>
                            <b>Volver</b>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">

                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4 display: inline-block;">
                                    <div class="form-group">
                                        <label for="nombre">Categorias</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-tags"></i></span>
                                            </div>
                                            <select class="form-control" id="categoria_id" name="categoria_id" disabled>
                                                <option value="">Seleccione una categoría</option>
                                                <option value="{{ $producto->categoria_id }}" selected>
                                                    {{ $producto->categoria->nombre }}</option>
                                            </select>
                                        </div>
                                        @error('categoria_id')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-4" style="display: inline-block;">
                                    <label for="codigo">Codigo del Producto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" value="{{ $producto->codigo }}" class="form-control"
                                            id="codigo" name="codigo" placeholder="Ingrese el código del producto"
                                            readonly>
                                    </div>
                                    @error('codigo')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-4" style="display: inline-block;">
                                    <label for="nombre">Nombre del Producto</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i class="fas fa-tag"></i></span>
                                        </div>
                                        <input type="text" value="{{ $producto->nombre }}" class="form-control"
                                            id="nombre" name="nombre" placeholder="Ingrese el nombre del producto"
                                            readonly>
                                    </div>
                                    @error('nombre')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-12" style="display: inline-block;">
                                    <label for="descripcion">Descripción</label>
                                    <div class="p-2"
                                        style="background: #e9ecef; min-height: 120px; border-radius: 5px; border: 2px solid #ced4da;">
                                        {!! $producto->descripcion !!}
                                    </div>
                                    @error('descripcion')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="precio_compra">Precio Compra</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-money-bill-wave"></i></span>
                                        </div>
                                        <input type="number" value="{{ $producto->precio_compra }}" class="form-control"
                                            id="precio_compra" name="precio_compra" placeholder="$$" readonly>
                                    </div>
                                    @error('precio_compra')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="precio_venta">Precio Venta</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-money-bill-wave"></i></span>
                                        </div>
                                        <input type="number" value="{{ $producto->precio_venta }}" class="form-control"
                                            id="precio_venta" name="precio_venta" placeholder="$$" readonly>
                                    </div>
                                    @error('precio_venta')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="stock_minimo">Stock Minimo</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-arrow-down"></i></span>
                                        </div>
                                        <input type="number" value="{{ $producto->stock_minimo }}" class="form-control"
                                            id="stock_minimo" name="stock_minimo" placeholder="Minimo" readonly>
                                    </div>
                                    @error('stock_minimo')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="stock_maximo">Stock Maximo</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-arrow-up"></i></span>
                                        </div>
                                        <input type="number" value="{{ $producto->stock_maximo }}" class="form-control"
                                            id="stock_maximo" name="stock_maximo" placeholder="Maximo" readonly>
                                    </div>
                                    @error('stock_maximo')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="unidad_medida">Unidad Medida</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-balance-scale"></i></span>
                                            </div>
                                            <select class="form-control" id="unidad_medida" name="unidad_medida"
                                                disabled>
                                                <option value="{{ $producto->unidad_medida }}">
                                                    {{ $producto->unidad_medida }}</option>
                                            </select>
                                        </div>
                                        @error('unidad_medida')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="estado">Estado</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-toggle-on"></i></span>
                                            </div>
                                            <select name="estado" id="estado" class="form-control" disabled>
                                                <option value="{{ $producto->estado }}">{{ $producto->estado }}</option>

                                            </select>
                                        </div>
                                        @error('estado')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group ">
                                        <label for="imagen">Imagen del producto</label>
                                        <div class="input-group mb-3">
                                            <input type="image" src="{{ asset('storage/' . $producto->imagen) }}"
                                                class="form-control" id="imagen" name="imagen"
                                                style="width: 100%; height: auto;" disabled>
                                        </div>

                                        <script>
                                            function previewImage(event) {
                                                const input = event.target;
                                                const file = input.files[0];
                                                if (file) {
                                                    const reader = new FileReader();
                                                    reader.onload = function(e) {
                                                        const imgPreview = document.getElementById('imgPreview');
                                                        imgPreview.src = e.target.result;
                                                        imgPreview.style.display = 'block';
                                                    };
                                                    reader.readAsDataURL(file);
                                                }
                                            }
                                        </script>
                                        @error('imagen')
                                            <div
                                                class="alert
                                                    text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <a href="{{ url('admin/maestros/productos') }}" class="btn btn-secondary">Cancelar</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
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
