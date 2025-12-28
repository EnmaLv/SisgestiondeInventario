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

            <div class="rd-card p-4 card-body">

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
                                    {!! $producto->descripcion ?? 'Sin Descripción' !!}
                                </div>
                            </div>
                        </div>

                        {{-- Precios / Stock --}}
                        <div class="row">

                            <div class="col-md-3 mb-3">
                                <label class="font-weight-bold">Precio Compra</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->precio_compra }}.BS"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="font-weight-bold">Stock Mínimo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-arrow-down"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->stock_minimo }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="font-weight-bold">Stock Máximo</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-arrow-up"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->stock_maximo }}"
                                        readonly>
                                </div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label class="font-weight-bold">Unidad Medida</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                    <input type="text" class="form-control" value="{{ $producto->unidad->nombre }}"
                                        readonly>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- Imagen --}}
                    <div class="col-md-3">

                        <label class="font-weight-bold" >Imagen del Producto</label>


                        @if ($producto->imagen)
                            <div class="rd-card p-2" style="border-radius:12px; overflow:hidden; border:1px solid #e2e8f0;">
                                <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen del producto"
                                    style="width:100%; height:auto; border-radius:10px; object-fit:cover;">
                            </div>
                        @else
                        <h4 style="text-align: center; margin-top: 3rem">Imagen no disponible</h4>
                        @endif


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
    .rd-input-group {
        margin-bottom: 1.25rem;
    }

    .card-body .input-group {
        border: 1px solid #d8dee9;
        background-color: #ebebeb;
        border-radius: 12px;
        padding-inline: 8px;
        transition: border-color .2s ease, box-shadow .2s ease;
        overflow: hidden;
    }

    .input-group:focus-within {
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1);
    }

    .card-body .input-group-text {
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
    .card-body .form-control,
    .card-body .form-select {
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
