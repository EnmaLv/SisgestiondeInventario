<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 30px 35px; }
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #111; margin: 0; }

        /* Maquetado de Cabecera con Tabla para evitar errores de float */
        .table-header { width: 100%; border-collapse: collapse; border-bottom: 3px solid #c0392b; margin-bottom: 10px; }
        .logo-col { width: 15%; vertical-align: middle; padding-bottom: 10px; }
        .title-col { width: 85%; text-align: center; vertical-align: middle; padding-bottom: 10px; }
        .header-logo { width: 80px; height: auto; }
        .header-title { margin: 0; font-size: 20px; color: #c0392b; text-transform: uppercase; }
        .header-subtitle { margin: 4px 0 0 0; color: #444; font-size: 11px; }

        /* Sección de Filtros */
        .table-meta { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 15px; }
        .meta-col { width: 50%; font-size: 10px; vertical-align: top; line-height: 1.4; }

        /* Tabla de Datos */
        table.data-table { width: 100%; border-collapse: collapse; }
        th { background: #c0392b; color: #fff; padding: 8px; font-size: 10px; text-transform: uppercase; text-align: left; }
        td { border-bottom: 1px solid #e5e5e5; padding: 7px 8px; }
        tr:nth-child(even) td { background: #fdf2f2; }

        .badge { padding: 2px 5px; border-radius: 3px; color: #fff; font-size: 9px; text-transform: uppercase; font-weight: bold; }
        .badge-egreso { background-color: #e67e22; }
        .badge-ingreso { background-color: #27ae60; }

        .footer { position: fixed; bottom: -10px; left: 0px; right: 0px; height: 30px; text-align: center; font-size: 9px; color: #777; border-top: 1px solid #eee; }
    </style>
</head>
<body>

    <table class="table-header">
        <tr>
            <td class="logo-col">
                <img src="{{ public_path('img/Logo.png') }}" class="header-logo">
            </td>
            <td class="title-col">
                <h1 class="header-title">Movimiento de Inventario</h1>
                <p class="header-subtitle">Reporte detallado de entradas y salidas de almacén</p>
            </td>
        </tr>
    </table>

    <table class="table-meta">
        <tr>
            <td class="meta-col">
                <strong>RANGO DE BÚSQUEDA:</strong><br>
                Desde: {{ $filtro['fecha_desde'] ?? 'Hoy' }}<br>
                Hasta: {{ $filtro['fecha_hasta'] ?? 'N/A' }}
            </td>
            <td class="meta-col" style="text-align: right;">
                <strong>GENERADO EL:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}<br>
                <strong>TOTAL MOVIMIENTOS:</strong> {{ $movimiento->count() }}
            </td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 12%;">FECHA</th>
                <th style="width: 20%;">LOTE</th>
                <th style="width: 23%;">PRODUCTO / ÍTEM</th>
                <th style="width: 15%;">TIPO</th>
                <th style="width: 10%;">CANT.</th>
                <th style="width: 15%;">SEDE</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($movimiento as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($item->fecha_movimiento)->format('d/m/Y') }}</td>
                    <td><strong>{{ $item->lote->codigo_lote ?? 'N/A' }}</strong></td>
                    <td><strong>{{ $item->producto->nombre ?? 'N/A' }}</strong></td>
                    <td>
                        <span class="badge {{ strtoupper($item->tipo_movimiento) == 'ENTRADA' ? 'badge-ingreso' : 'badge-egreso' }}">
                            {{ $item->tipo_movimiento }}
                        </span>
                    </td>
                    <td>{{ number_format($item->cantidad, 2) }}.g</td>
                    <td>{{ $item->sucursal->nombre ?? 'N/A' }}</td>
                </tr>
            @endforeach
            <script type="text/php">
                if (isset($pdf)) {
                    $text = "Página {PAGE_NUM} de {PAGE_COUNT}";
                    $font = $fontMetrics->get_font("helvetica", "normal");
                    $size = 9;
                    $width = $fontMetrics->get_text_width($text, $font, $size);
                    // Calculamos X para que quede perfectamente centrado
                    $x = ($pdf->get_width() - $width) / 2;
                    $y = $pdf->get_height() - 35;
                    $pdf->page_text($x, $y, $text, $font, $size, array(0.4, 0.4, 0.4));
                }
            </script>
        </tbody>
    </table>


</body>
</html>