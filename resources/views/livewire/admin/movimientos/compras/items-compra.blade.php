<div>
    <div class="row">
        <div class="col-md-3">
            <label for="nombre">Producto</label>
            <div class="rd-input-group">
                <span><i class="fas fa-box"></i></span>
                <select @disabled($compra->estado == 'Enviado al proveedor') name="nombre" wire:model.live="productoId" id="nombre"
                    class="form-control select2">
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

        <div class="col-md-2">
            <label for="nombre">Lote</label>
            <div class="rd-input-group">
                <span><i class="fas fa-box"></i></span>
                <input type="text" wire:model="codigoLote" class="form-control" id="lote" name="lote" placeholder="Código de lote" readonly>
            </div>
            @error('codigoLote')
                <div class="alert text-danger p-0 m-0">
                    <b>{{ 'Este campo es obligatorio.' }}</b>
                </div>
            @enderror
        </div>


        <div class="col-md-2">
            <label for="cantidad">Cantidad (U)</label>
            <div class="rd-input-group">
                    <span><i class="fas fa-plus"></i></span>
                <input @disabled($compra->estado == 'Enviado al proveedor') type="number" class="form-control" id="cantidad"
                    wire:model="cantidad" name="cantidad" placeholder="Ingrese cantidad"
                    value="{{ old('cantidad', $compra->cantidad) }}" min="0">
            </div>
            @error('cantidad')
                <div class="alert text-danger p-0 m-0">
                    <b>{{ 'Este campo es obligatorio.' }}</b>
                </div>
            @enderror
        </div>

        <div class="col-md-2">
            <label for="precioCompra">Precio Compra(.BS)</label>
            <div class="rd-input-group">
                    <span><i class="fas fa-dollar-sign"></i></span>
                <input @disabled($compra->estado == 'Enviado al proveedor') type="number" wire:model="precioCompra" class="form-control"
                    id="precioCompra" name="precio_compra" placeholder="Ingrese precio de compra"
                    value="{{ old('precio_compra', $compra->precio_compra) }}" min="0">
            </div>
            @error('precioCompra')
                <div class="alert text-danger p-0 m-0">
                    <b>{{ 'Este campo es obligatorio.' }}</b>
                </div>
            @enderror
        </div>

        <div class="col-md-1">
            <div style="height: 31px;"></div>
            <div class="form-group">
                @if ($compra->estado == 'Enviado al proveedor')
                @else
                    <button class="rd-btn rd-btn-alter disabled:opacity-25" type="submit" wire:click="agregarItems"
                        wire:loading.attr="disabled">Agregar</button>
                @endif
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
                <h2 class="my-4">Detalles de la Requisicion</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Código de Lote</th>
                            <th>Cantidad (U)</th>
                            <th>Cantidad (g)</th>
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
                                <td>{{ round($detalle->cantidad) }}</td>
                                <td>{{ $detalle->cantidad_gramos }}</td>
                                <td>{{ number_format($detalle->precio_unitario, 2, ',', '.') }} .BS</td>
                                <td>{{ number_format($detalle->subtotal, 2, ',', '.') }} .BS</td>
                                <td>
                                    <button @disabled($compra->estado == 'Enviado al proveedor') class="btn btn-danger disabled:opacity-25"
                                        wire:click="eliminarItem({{ $detalle->id }})" wire:loading.attr="disabled"><i
                                            class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <hr>
            @else
                <h4>No hay productos agregados a la Requsicion.</h4>

            @endif
            <h3 style="display: inline-block"><b>Total de la Requisicion:
                </b>{{ number_format($compra->total, 2, ',', '.') }} .BS</h3>
            @if ($compra->detalleCompras->count() == 0)
                <span class="text-danger" style="display: inline-block; margin-left: 20px; float: right">*Agregue
                    productos para
                    enviar a compras.</span>
            @elseif ($compra->estado == 'Enviado al proveedor')
                <span class="text-danger" style="display: inline-block; margin-left: 20px; float: right">Ya fue hecho el
                    pedido</span>
            @else
                <button class="rd-btn rd-btn-primary" wire:click="confirmarEnvio" style="float:right;">
                    <i class="fas fa-paper-plane"></i> Enviar Correo a Compras
                </button>
                <script>
                    function confirmarEnvio(id) {
                        Swal.fire({
                            title: "¿Quieres enviar el correo a compras?",
                            text: "Luego de enviarlo no podrás agregar más productos, ni enviar otro correo.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Sí, enviar",
                            cancelButtonText: "Cancelar"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = `/admin/movimientos/compras/${id}/enviar-correo`;
                            }
                        });
                    }
                </script>
            @endif
        </div>
    </div>
</div>

@push('js')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        window.addEventListener('confirmar-envio', event => {
            Swal.fire({
                title: "¿Quieres enviar el correo a compras?",
                text: "Luego de enviarlo no podrás agregar más productos.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sí, enviar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href =
                        `/admin/movimientos/compras/${event.detail.compraId}/enviar-correo`;
                }
            });
        });
    });
    </script>
@endpush