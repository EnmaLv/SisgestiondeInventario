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
                Compra nro {{ $compra->id }}
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
        <div class="col-md-12 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>Compra creada</b></h3>

                    <div class="card-tools">
                        <a href="{{ url('admin/movimientos/compras') }}" class="btn btn-tool">
                            <i class="fas fa-arrow-left"></i>
                            <b>Volver</b>
                        </a>
                    </div>
                </div>
                <div class="card-body" style="display: block;">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-3" style="display: inline-block;">
                                    <div class="form-group">
                                        <label for="nombre">Proveedor</label>
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text inline-block"><i
                                                        class="fas fa-tags"></i></span>
                                            </div>
                                            <input type="text" class="form-control" id="proveedor_id" name="proveedor_id"
                                                placeholder="Seleccione proveedor"
                                                value="{{ old('proveedor_id', $compra->proveedor->nombre) }}" readonly>


                                        </div>
                                        @error('proveedor_id')
                                            <div class="alert text-danger p-0 m-0">
                                                <b>{{ 'Este campo es obligatorio.' }}</b>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="codigo">Fecha de Compra</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="datetime-local"
                                            value="{{ \Carbon\Carbon::now('America/Caracas')->format('Y-m-d\TH:i') }}"
                                            class="form-control" id="fecha" name="fecha"
                                            value="{{ old('fecha', $compra->fecha) }}" disabled>
                                    </div>
                                    @error('fecha')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-3" style="display: inline-block;">
                                    <label for="codigo">Observaciones</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-sticky-note"></i></span>
                                        </div>
                                        @if ($compra->observaciones == !null)
                                            <input type="text" class="form-control" id="observaciones"
                                                name="observaciones" placeholder="Ingrese observaciones"
                                                value="{{ old('observaciones', $compra->observaciones) }}" readonly>
                                        @else
                                            <input type="text" class="form-control" id="observaciones"
                                                name="observaciones" placeholder="Ingrese observaciones"
                                                value="Sin observaciones" readonly>
                                        @endif
                                    </div>
                                    @error('observaciones')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="codigo">Compra</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-sticky-note"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="estado" name="estado"
                                            placeholder="Ingrese estado" value="{{ old('estado', $compra->estado) }}"
                                            readonly>
                                    </div>
                                    @error('estado')
                                        <div class="alert text-danger p-0 m-0">
                                            <b>{{ 'Este campo es obligatorio.' }}</b>
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-2" style="display: inline-block;">
                                    <label for="codigo">Sucursal de Destino</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text inline-block"><i
                                                    class="fas fa-sticky-note"></i></span>
                                        </div>
                                        @if ($sucursal_destino != null)
                                            <input type="text" class="form-control" id="estado" name="estado"
                                                placeholder="Ingrese estado"
                                                value="{{ old('estado', $sucursal_destino ? $sucursal_destino->nombre : '') }}"
                                                readonly>
                                        @else
                                            <input type="text" class="form-control" id="sucursal_destino"
                                                name="sucursal_destino" placeholder="Ingrese sucursal de destino"
                                                value="Sin concluir" readonly>
                                        @endif
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


                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 m-auto">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><b>Productos Agregados</b></h3>
                </div>
                <div class="card-body" style="display: block;">

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
            </div>
        </div>
    </div>


@stop
