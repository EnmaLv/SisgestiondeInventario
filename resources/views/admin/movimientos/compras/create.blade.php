@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center"
        style="
            background: #ffffff;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.06);
            border: 1px solid #e5e7eb;
         ">

        <!-- Texto principal -->
        <div>
            <h1 class="m-0" style="font-size:1.45rem; color:#0f172a; font-weight:700;">
                Crear Nueva Compra
            </h1>

            <p class="mt-1 mb-0" style="font-size:0.95rem; color:#475569;">
                Bienvenido <strong>{{ auth()->user()->name }}</strong>.
            </p>
        </div>

        <!-- Imagen + Fecha -->
        <div class="d-flex align-items-center" style="gap:14px;">
            <div class="text-right d-none d-sm-block">
                <small class="text-muted d-block" style="font-size:0.75rem;">Hoy</small>
                <span style="font-weight:600; font-size:0.95rem;">
                    {{ \Carbon\Carbon::now()->format('d/m/Y') }}
                </span>
            </div>

            <div
                style="
                width:46px;
                height:46px;
                border-radius:12px;
                overflow:hidden;
                box-shadow:0 4px 12px rgba(15,23,42,0.08);
            ">
                <img src="{{ asset('img/usuario-verificado.png') }}" alt="Usuario"
                    style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>

    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-9 m-auto">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Crear Compra</b></h3>

                    <div class="card-tools">
                        <a href="{{ url('admin/movimientos/compras') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-left"></i>
                            <b>Volver</b>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">
                    <form action="{{ route('admin.movimientos.compras.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4 display: inline-block;">
                                        <div class="form-group">
                                            <label for="nombre">Proveedor</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text inline-block"><i
                                                            class="fas fa-tags"></i></span>
                                                </div>
                                                <select class="form-control" id="proveedor_id" name="proveedor_id">
                                                    <option value="">Seleccione un proveedor</option>
                                                    @foreach ($proveedores as $proveedor)
                                                        <option value="{{ $proveedor->id }}"
                                                            {{ old('proveedor_id') == $proveedor->id ? 'selected' : '' }}>
                                                            {{ $proveedor->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @error('proveedor_id')
                                                <div class="alert text-danger p-0 m-0">
                                                    <b>{{ 'Este campo es obligatorio.' }}</b>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4" style="display: inline-block;">
                                        <label for="codigo">Fecha de Compra</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="datetime-local"
                                                value="{{ \Carbon\Carbon::now('America/Caracas')->format('Y-m-d\TH:i') }}"
                                                class="form-control" id="fecha" name="fecha">
                                        </div>
                                        @error('fecha')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-4" style="display: inline-block;">
                                        <label for="codigo">Observaciones</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-sticky-note"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="observaciones"
                                                name="observaciones" placeholder="Ingrese observaciones">
                                        </div>
                                        @error('observaciones')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <a href="{{ url('admin/movimientos/compras') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Crear compra y añadir productos</button>
                        </div>
                    </form>
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
