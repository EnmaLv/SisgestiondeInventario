@props([
    'producto',
    'rutaVolver',
    'esMedicamento' => false
])

<div class="rd-card p-4 card-body">
    <div class="rd-card-header mb-3">
        <h3 class="rd-title-sm">Información del Producto</h3>
        <a href="{{ $rutaVolver }}" class="rd-btn rd-btn-default">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
    </div>
    
    <div class="row">
        <div class="col-md-9">
            <div class="row">
                <div class="{{ $esMedicamento ? 'col-md-3' : 'col-md-4' }} mb-3">
                    <label class="font-weight-bold">Categoría</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tags"></i></span>
                        <input type="text" class="form-control" value="{{ optional($producto->categoria)->nombre }}" readonly>
                    </div>
                </div>
                
                <div class="{{ $esMedicamento ? 'col-md-3' : 'col-md-4' }} mb-3">
                    <label class="font-weight-bold">Código</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                        <input type="text" class="form-control" value="{{ $producto->codigo }}" readonly>
                    </div>
                </div>
                
                <div class="{{ $esMedicamento ? 'col-md-3' : 'col-md-4' }} mb-3">
                    <label class="font-weight-bold">Nombre</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                        <input type="text" class="form-control" value="{{ $producto->nombre }}" readonly>
                    </div>
                </div>

                @if($esMedicamento)
                <div class="col-md-3 mb-3">
                    <label class="font-weight-bold">Presentación</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                        <input type="text" class="form-control" value="{{ optional($producto->presentacion)->nombre ?? 'No definida' }}" readonly>
                    </div>
                </div>
                @endif
            </div>

            <div class="row mt-2 mb-3">
                <div class="col-md-12">
                    <label class="font-weight-bold">Descripción</label>
                    <div class="p-3" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:10px; min-height:140px;">
                        {!! $producto->descripcion ?? 'Sin Descripción' !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="font-weight-bold">Precio Compra</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-money-bill-wave"></i></span>
                        <input type="text" class="form-control" value="{{ $producto->precio_compra }} BS" readonly>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="font-weight-bold">Stock Mín.</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-arrow-down"></i></span>
                        <input type="text" class="form-control" value="{{ $producto->stock_minimo }}" readonly>
                    </div>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="font-weight-bold">Stock Máx.</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-arrow-up"></i></span>
                        <input type="text" class="form-control" value="{{ $producto->stock_maximo }}" readonly>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="font-weight-bold">Unidad</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                        <input type="text" class="form-control" value="{{ optional($producto->unidad)->nombre }}" readonly>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="font-weight-bold">Peso del contenido</label>
                        <div class="input-group mb-2">
                            <span class="input-group-text"><i class="fas fa-weight"></i></span>
                            <input type="number" class="form-control" value="{{ round($producto->peso_contenido, 2) }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <label class="font-weight-bold">Imagen del Producto</label>
            @if ($producto->imagen && $producto->imagen !== 'imagenes/productos/product-defect.webp')
                <div class="rd-card p-2" style="border-radius:12px; overflow:hidden; border:1px solid #e2e8f0;">
                    <img src="{{ asset('storage/' . $producto->imagen) }}" alt="Imagen del producto" style="width:100%; height:auto; border-radius:10px; object-fit:cover;">
                </div>
            @else
                <div class="rd-card p-4 text-center mt-2" style="border-radius:12px; border:1px dashed #cbd5e1; background: #f8fafc;">
                    <i class="fas fa-image text-muted mb-2" style="font-size: 2rem;"></i>
                    <h6 class="text-muted">Imagen no disponible</h6>
                </div>
            @endif
        </div>
    </div>
    
    <hr>
    
    <div class="d-flex justify-content-end">
        <a href="{{ $rutaVolver }}" class="rd-btn rd-btn-default">Volver</a>
    </div>
</div>