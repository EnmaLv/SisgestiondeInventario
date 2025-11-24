@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Ver Producto</h1>
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
        <div class="col-md-12">

            <div class="rd-card p-4">

                {{-- Header --}}
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Información del Producto</h3>

                    <a href="{{ url('admin/maestros/productos') }}" class="rd-btn rd-btn-default">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>

                <div class="row">

                    {{-- Lado izquierdo --}}
                    <div class="col-md-9">

                        <div class="row">

                            {{-- Categoría --}}
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Categoría</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->categoria->nombre }}"
                                        readonly>
                                </div>
                            </div>

                            {{-- Código --}}
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Código</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->codigo }}" readonly>
                                </div>
                            </div>

                            {{-- Nombre --}}
                            <div class="col-md-4 mb-3">
                                <label class="font-weight-bold">Nombre</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->nombre }}" readonly>
                                </div>
                            </div>

                        </div>


                        {{-- Descripción --}}
                        <div class="row mt-2 mb-3">
                            <div class="col-md-12">
                                <label class="font-weight-bold">Descripción</label>
                                <div class="p-3"
                                    style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; min-height:140px;">
                                    {!! $producto->descripcion !!}
                                </div>
                            </div>
                        </div>

                        {{-- Precios / Stock --}}
                        <div class="row">

                            <div class="col-md-2 mb-3">
                                <label class="font-weight-bold">Precio Compra</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->precio_compra }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="font-weight-bold">Precio Venta</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->precio_venta }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="font-weight-bold">Stock Mínimo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-arrow-down"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->stock_minimo }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="font-weight-bold">Stock Máximo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-arrow-up"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->stock_maximo }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="font-weight-bold">Unidad Medida</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->unidad_medida }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="font-weight-bold">Estado</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                                    <input type="text" class="form-control"
                                        value="{{ $producto->estado == 1 ? 'Activo' : 'Inactivo' }}" readonly>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- Imagen --}}
                    <div class="col-md-3">

                        <label class="font-weight-bold">Imagen del Producto</label>

                        <div class="rd-card p-2" style="border-radius:12px; overflow:hidden; border:1px solid #e2e8f0;">

                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen del producto"
                                style="width:100%; height:auto; border-radius:10px; object-fit:cover;">

                        </div>

                    </div>

                </div>

                <hr>

                <div class="d-flex justify-content-end">
                    <a href="{{ url('admin/maestros/productos') }}" class="rd-btn rd-btn-default">
                        Volver
                    </a>
                </div>

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
