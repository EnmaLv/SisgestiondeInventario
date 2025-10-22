<div class="row">
    <div class="col-md-12 m-auto">
        <div class="card card-warning">
            <div class="card-header">
                <h3 class="card-title" style="color: #fff"><b>Editar el producto</b></h3>

                <div class="card-tools">
                    <a href="javascript:void(0)" class="btn btn-tool" onclick="GoTo('{{ url('admin/maestros/productos') }}')">
                        <i class="fas fa-arrow-left"></i>
                        <b>Volver</b>
                    </a>
                </div>
            </div>
            <div class="card-body" style="display: block;">
                <form id="editForm" action="{{ route('admin.maestros.productos.update', $producto->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
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
                                            <select class="form-control" id="categoria_id" name="categoria_id">
                                                <option value="">Seleccione una categoría</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->id }}"
                                                        {{ old('categoria_id', $producto->categoria_id) == $categoria->id ? 'selected' : '' }}>
                                                        {{ $categoria->nombre }}</option>
                                                @endforeach
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
                                        <input type="text" value="{{ old('codigo', $producto->codigo) }}"
                                            class="form-control" id="codigo" name="codigo"
                                            placeholder="Ingrese el código del producto">
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
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-tag"></i></span>
                                        </div>
                                        <input type="text" value="{{ old('nombre', $producto->nombre) }}"
                                            class="form-control" id="nombre" name="nombre"
                                            placeholder="Ingrese el nombre del producto">
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
                                    <div class="editor-wrapper">
                                        <textarea name="descripcion" id="descripcion">{{ old('descripcion', $producto->descripcion) }}</textarea>
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
                                        <input type="number"
                                            value="{{ old('precio_compra', $producto->precio_compra) }}"
                                            class="form-control" id="precio_compra" name="precio_compra"
                                            placeholder="$$">
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
                                        <input type="number"
                                            value="{{ old('precio_venta', $producto->precio_venta) }}"
                                            class="form-control" id="precio_venta" name="precio_venta" placeholder="$$">
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
                                        <input type="number"
                                            value="{{ old('stock_minimo', $producto->stock_minimo) }}"
                                            class="form-control" id="stock_minimo" name="stock_minimo"
                                            placeholder="Minimo">
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
                                        <input type="number"
                                            value="{{ old('stock_maximo', $producto->stock_maximo) }}"
                                            class="form-control" id="stock_maximo" name="stock_maximo"
                                            placeholder="Maximo">
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
                                            <select class="form-control" id="unidad_medida" name="unidad_medida">
                                                <option value="">Seleccione una medida</option>
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
                                            <select name="estado" id="estado" class="form-control">
                                                <option value="">Seleccione un estado</option>
                                                <option value="1"
                                                    {{ old('estado', $producto->estado) == '1' ? 'selected' : '' }}>
                                                    Activo
                                                </option>
                                                <option value="0"
                                                    {{ old('estado', $producto->estado) == '0' ? 'selected' : '' }}>
                                                    Inactivo
                                                </option>
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
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                            </div>
                                            <input type="file" name="imagen" id="imagen" class="form-control"
                                                accept="image/*" onchange="previewImage(event)">

                                        </div>
                                        <img id="imgPreview" style="width: 100%; height: auto;"
                                            src="{{ asset('storage/' . $producto->imagen) }}" alt=""
                                            class="img-thumbnail mb-2">
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
                        <a href="javascript:void(0)" onclick="GoTo('{{ url('admin/maestros/productos') }}')" class="btn btn-danger">Cancelar</a>    
                        <button type="submit" class="btn btn-warning" style="color: #fff"><b>Guardar</b></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
