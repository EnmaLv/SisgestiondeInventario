<div>
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <label for="nombre">Producto</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text inline-block"><i class="fas fa-box"></i></span>
                    </div>
                    <select name="nombre" wire:model.live="productoId" id="nombre" class="form-control select2">
                        <option value="">Seleccione un producto</option>
                        @foreach ($productos as $producto)
                            <option value="{{ $producto->id }}">{{ $producto->codigo }} -
                                {{ $producto->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                @error('productoId')
                    <div class="alert text-danger p-0 m-0">
                        <b>{{ 'Este campo es obligatorio.' }}</b>
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="nombre">Lote</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text inline-block"><i class="fas fa-box"></i></span>
                    </div>
                    <input type="text" wire:model="codigoLote" class="form-control" id="lote" name="lote"
                        placeholder="Ingrese el lote" value="{{ old('lote', $compra->lote) }}">
                </div>
                @error('codigoLote')
                    <div class="alert text-danger p-0 m-0">
                        <b>{{ 'Este campo es obligatorio.' }}</b>
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="cantidad">Cantidad</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text inline-block"><i class="fas fa-plus"></i></span>
                    </div>
                    <input type="number" class="form-control" id="cantidad" wire:model="cantidad" name="cantidad"
                        placeholder="Ingrese cantidad" value="{{ old('cantidad', $compra->cantidad) }}">
                </div>
                @error('cantidad')
                    <div class="alert text-danger p-0 m-0">
                        <b>{{ 'Este campo es obligatorio.' }}</b>
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="precioCompra">Precio Compra</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text inline-block"><i class="fas fa-dollar-sign"></i></span>
                    </div>
                    <input type="number" wire:model="precioCompra" class="form-control" id="precioCompra"
                        name="precio_compra" placeholder="Ingrese precio de compra"
                        value="{{ old('precio_compra', $compra->precio_compra) }}">
                </div>
                @error('precioCompra')
                    <div class="alert text-danger p-0 m-0">
                        <b>{{ 'Este campo es obligatorio.' }}</b>
                    </div>
                @enderror
            </div>
        </div>

        <div class="col-md-2">
            <div class="form-group">
                <label for="fecha">Fecha de Vencimiento</label>
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text inline-block"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                    <input type="date" wire:model="fechaVencimiento" class="form-control" id="fecha"
                        name="fecha" placeholder="Ingrese fecha de vencimiento"
                        value="{{ old('fecha', $compra->fecha) }}">
                </div>
                @error('fechaVencimiento')
                    <div class="alert text-danger p-0 m-0">
                        <b>{{ 'Este campo es obligatorio.' }}</b>
                    </div>
                @enderror
            </div>
        </div>
        <div class="col-md-1">
            <div style="height: 31px;"></div>
            <div class="form-group">
                <button class="btn btn-primary" type="submit" wire:click="agregarItems">Agregar</button>
            </div>
        </div>

        <hr>

        <div x-data
            x-on:mostrar-alerta.window="
                Swal.fire({
                    position: 'center',
                    icon: $event.detail.icono,
                    title: $event.detail.mensaje,
                    showConfirmButton: false,
                    timer: 3000
                });">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">

            @if ($compra->detalleCompras->count() > 0)
                <h2>Items de Compra</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código de Lote</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compra->detalleCompras as $detalle)
                            <tr>
                                <td>{{ $detalle->producto->nombre }}</td>
                                <td>{{ $detalle->lote->codigo_lote }}</td>
                                <td>{{ $detalle->cantidad }}</td>
                                <td>{{ $detalle->precio_unitario }}</td>
                                <td>{{ $detalle->subtotal }}</td>
                                <td>
                                    <button class="btn btn-danger" wire:click="eliminarItem({{ $detalle->id }})"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>
            @else
                <h4>No hay productos agregados a la compra.</h4>

            @endif
            <h3><b>Total de la Compra: </b>{{ $compra->total }}</h3>

        </div>
    </div>
</div>
