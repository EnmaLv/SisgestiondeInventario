<!DOCTYPE html>
<html>

<head>
    <title>Reporte de Compras Detallado</title>
    <style>
        /* CSS 2.1 Básico - Minimalista Azul/Gris */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            margin: 20px;
            padding: 0;
            color: #333;
        }

        .title {
            text-align: left;
            margin-bottom: 30px;
            padding-bottom: 5px;
            border-bottom: 3px solid #d41002;
        }

        .title h1 {
            font-size: 18pt;
            color: #d41002;
            margin: 0;
        }

        .title p {
            font-size: 8pt;
            color: #666;
            margin-top: 5px;
        }

        .compra-item {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #ddd;
            page-break-inside: avoid;
        }

        .compra-info table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 15px;
        }

        .compra-info td {
            padding: 4px 0;
        }

        .label {
            font-weight: bold;
            width: 160px;
            color: #444;
        }

        /* TABLA DE DETALLES DE PRODUCTOS */
        .detalle-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .detalle-table th,
        .detalle-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 9pt;
        }

        .detalle-table th {
            background-color: #f0f0f0;
            color: #000;
            font-weight: bold;
            text-align: center;
        }

        .detalle-table .right {
            text-align: right;
        }

        /* TOTAL INDIVIDUAL DE LA COMPRA */
        .total-final {
            margin-top: 15px;
            text-align: right;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
        }

        .total-final .total-label {
            font-size: 11pt;
            font-weight: bold;
            color: #333;
            margin-right: 10px;
        }

        .total-final .total-valor {
            font-size: 12pt;
            font-weight: bold;
            color: #d41002;
        }

        .observaciones {
            font-style: italic;
            font-size: 9pt;
            margin-top: 10px;
            padding-top: 5px;
            color: #666;
        }
    </style>
</head>

<body>

    <div class="title">
        <h1>REPORTE DE COMPRAS DETALLADO Y ACUMULADO</h1>
        <p>Documento generado el: **{{ now()->format('d-m-Y H:i:s') }}**</p>
    </div>

    @forelse ($compras as $compra)

        <div class="compra-item">
            {{-- SECCIÓN DE INFORMACIÓN GENERAL DE LA COMPRA --}}
            <h2
                style="font-size: 13pt; color: #d41002; border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-bottom: 10px;">
                Compra #{{ $compra->id ?? 'N/A' }} al Proveedor: {{ $compra->proveedor_empresa }}
            </h2>

            <div class="compra-info">
                <table>
                    <tr>
                        <td class="label">Fecha de Compra:</td>
                        <td>{{ \Carbon\Carbon::parse($compra->fecha)->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Registro en Sistema:</td>
                        <td>{{ \Carbon\Carbon::parse($compra->created_at)->format('d/m/Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>

            {{-- TABLA DE DETALLES DE PRODUCTOS (IDs OMITIDOS) --}}
            <h3 style="font-size: 11pt; margin-top: 20px; color: #000;">Productos Adquiridos</h3>

            <table class="detalle-table">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th class="right">Cantidad</th>
                        <th style="text-align: center;">Unidad</th>
                        <th class="right">Precio Unitario</th>
                        <th class="right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($compra->detalles as $detalle)
                        <tr>
                            <td>{{ $detalle->producto_nombre }}</td>
                            <td class="right">{{ number_format($detalle->cantidad, 2, ',', '.') }}</td>
                            <td style="text-align: center;">{{ $detalle->unidad_abreviatura }}</td>
                            <td class="right">{{ number_format($detalle->precio_unitario, 2, ',', '.') }}BS</td>
                            <td class="right">{{ number_format($detalle->subtotal, 2, ',', '.') }}BS</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="observaciones">
                **Observaciones de Compra:** {{ $compra->observaciones ?? 'N/A' }}
            </div>

            <div class="total-final">
                <span class="total-label">Total de esta Compra:</span>
                <span class="total-valor">{{ number_format($compra->total, 2, ',', '.') }}BS </span>
            </div>
        </div>
    @empty
        <div style="text-align: center; padding: 50px;">
            <p style="font-size: 14pt; color: #005691;">No se encontraron registros de compras con los filtros
                especificados.</p>
        </div>
    @endforelse

</body>

</html>
