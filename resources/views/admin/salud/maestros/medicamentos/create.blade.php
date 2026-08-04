@extends('adminlte::page')

@section('content_header')
    <div class="rd-card p-4 mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0 rd-title-sm" style="font-size:1.4rem;">Crear Nuevo Medicamento</h1>
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
            <div style="width:46px;height:46px;border-radius:12px;overflow:hidden;box-shadow:0 4px 12px rgba(15,23,42,0.08);">
                <img src="{{ asset('img/usuario-verificado.webp') }}" alt="Usuario" style="width:100%; height:100%; object-fit:cover;">
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="rd-card p-4">
                <div class="rd-card-header mb-3">
                    <h3 class="rd-title-sm">Crear medicamento</h3>
                    <div>
                        <a href="{{ url('admin/salud/maestros/medicamentos') }}" class="rd-btn rd-btn-default">
                            <i class="fas fa-arrow-left"></i> Volver
                        </a>
                    </div>
                </div>
                <form action="{{ route('admin.salud.maestros.medicamentos.store') }}" method="POST" enctype="multipart/form-data" class="rd-prevent-double-submit">
                    @csrf
                    <input type="hidden" name="from" value="{{ request('from') }}">
                    <div class="row">
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Categoría</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-tags"></i></span>
                                            <select class="form-control rd-filter-input" id="categoria_id" name="categoria_id">
                                                <option value="" selected disabled>Seleccione una categoría</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}" {{ old('categoria_id', request('categoria_id')) == $categoria->id ? 'selected' : '' }}>
                                                        {{ $categoria->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('categoria_id')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                        <div class="mt-2 pt-2" style="border-top: 1px solid #e5e7eb; padding-top: 12px;">
                                            <small style="color: #64748b; font-size: 0.85rem;">
                                                ¿No encuentras lo que buscas?
                                                <a style="color: #a84348; text-decoration: none; font-weight: 600; transition: color 0.2s;" href="{{ route('admin.salud.maestros.categorias.create', ['from' => url()->current()]) }}">
                                                    Créala aquí
                                                </a>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Código</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                            <input type="text" class="form-control" value="Se generará automáticamente" disabled>
                                        </div>
                                        @error('codigo')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Nombre</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            <input type="text" value="{{ old('nombre') }}" class="form-control rd-filter-input" name="nombre" placeholder="Nombre del producto">
                                        </div>
                                        @error('nombre')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label class="font-weight-bold">Descripción</label>
                                <textarea name="descripcion" id="descripcion">{{ old('descripcion') }}</textarea>
                                @error('descripcion')
                                    <div class="text-danger"><b>{{ $message }}</b></div>
                                @enderror
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Precio base (USD)</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text">$</span>
                                            <input type="number" name="costo_usd" class="form-control rd-filter-input" value="{{ old('costo_usd') }}" placeholder="0.00" min="0" step="0.01">
                                        </div>
                                        @error('costo_usd')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Stock Mínimo</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-arrow-down"></i></span>
                                            <input type="number" class="form-control rd-filter-input" name="stock_minimo" value="{{ old('stock_minimo') }}" placeholder="Mínimo" min="0">
                                        </div>
                                        @error('stock_minimo')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Stock Máximo</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-arrow-up"></i></span>
                                            <input type="number" class="form-control rd-filter-input" name="stock_maximo" value="{{ old('stock_maximo') }}" placeholder="Máximo" min="0">
                                        </div>
                                        @error('stock_maximo')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Envase Primario</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                            <select class="form-control rd-filter-input" name="envase_primario_id" id="envase_primario_id">
                                                <option value="" selected disabled>Seleccione un envase</option>
                                                @foreach ($envases as $envase)
                                                    <option value="{{ $envase->id }}" {{ old('envase_primario_id') == $envase->id ? 'selected' : '' }}>
                                                        {{ $envase->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('envase_primario_id')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold">Unidad de Medida</label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                            <select class="form-control rd-filter-input" name="unidad_id" id="unidad_id">
                                                <option value="" selected disabled>Seleccione una unidad</option>
                                                @foreach ($unidades as $unidad)
                                                    <option value="{{ $unidad->id }}" data-abreviatura="{{ $unidad->abreviatura }}" {{ old('unidad_id') == $unidad->id ? 'selected' : '' }}>
                                                        {{ $unidad->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('unidad_id')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="font-weight-bold" id="label-peso">
                                            Peso del contenido
                                        </label>
                                        <div class="input-group mb-2">
                                            <span class="input-group-text"><i class="fas fa-weight"></i></span>
                                            <input type="number" class="form-control rd-filter-input" name="peso_contenido" value="{{ old('peso_contenido') }}" placeholder="Peso contenido" min="0">
                                        </div>
                                        @error('peso_contenido')
                                            <div class="text-danger"><b>{{ $message }}</b></div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="font-weight-bold">Imagen del producto</label>
                                <div class="input-group mb-2">
                                    <span class="input-group-text"><i class="fas fa-image"></i></span>
                                    <label for="imagen" class="p-2" style="margin: 0; cursor: pointer; width: 90%;">Seleccione una foto</label>
                                    <input type="file" name="imagen" id="imagen" class="form-control rd-filter-input" accept="image/*" onchange="previewImage(event)" style="display: none">
                                </div>
                                <img id="imgPreview" style="width: 100%; display:none; border-radius:10px; box-shadow:0 4px 14px rgba(0,0,0,0.08);" />
                                <em id="fileName" style="margin: 10px"></em>

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
                                        const fileName = document.getElementById('fileName');
                                        fileName.textContent = file.name;
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
                        <a href="{{ url('admin/salud/maestros/medicamentos') }}" class="rd-btn rd-btn-default">Cancelar</a>
                        <button type="submit" class="rd-btn rd-btn-primary rd-submit-btn">
                            <i class="fas fa-save"></i>Guardar
                        </button>
                    </div>

                </form>
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
        document.getElementById('unidad_id').addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            const abrev = selected.getAttribute('data-abreviatura');

            const label = document.getElementById('label-peso');

            if (abrev) {
                label.textContent = `Peso contenido (en ${abrev})`;
            } else {
                label.textContent = 'Peso contenido';
            }
        });
    </script>

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