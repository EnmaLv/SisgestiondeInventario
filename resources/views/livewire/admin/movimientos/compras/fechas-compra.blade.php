<div>
    <div class="form-group" style="text-align: right;">
        <button wire:click="guardar" class="rd-btn btn-success">
            <i class="fas fa-save"></i> Guardar Fechas de Vencimiento
        </button>
    </div>
    <table class="table table-bordered mt-3">
        
        <thead>
            <tr>
                <th>Producto</th>
                <th>Fecha de Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($compra->detalleCompras as $detalle)
                <tr>
                    <td>{{ $detalle->producto->nombre }}</td>
                    <td>
                        <input type="date" class="form-control"
                            wire:model="fechas.{{ $detalle->id }}.fecha_vencimiento"
                            min="{{ now()->addDay()->format('Y-m-d') }}">
                        @error("fechas.$detalle->id.fecha_vencimiento")
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
