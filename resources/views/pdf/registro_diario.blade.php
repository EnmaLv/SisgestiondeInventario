<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte Registro Diario</title>
    <style>
        @page {
            margin: 30px 35px;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #111;
            margin: 0;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #c0392b;
            padding-bottom: 12px;
            margin-bottom: 18px;
        }

        .header h1 {
            margin: 0;
            font-size: 22px;
            letter-spacing: 0.5px;
            color: #c0392b;
            text-transform: uppercase;
        }

        .header p {
            margin: 4px 0 0 0;
            color: #444;
            font-size: 11px;
        }

        .meta {
            margin-bottom: 14px;
            color: #333;
        }

        .meta div {
            width: 48%;
        }

        .meta strong {
            color: #000;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        th {
            background: #c0392b;
            color: #fff;
            padding: 8px;
            font-size: 11px;
            text-transform: uppercase;
        }

        td {
            border-bottom: 1px solid #e5e5e5;
            padding: 7px 8px;
            font-size: 11px;
        }

        tr:nth-child(even) td {
            background: #fdf2f2;
        }

        .empty-state {
            border: 1px dashed #c0392b;
            padding: 20px;
            text-align: center;
            color: #c0392b;
            font-weight: 600;
            margin-top: 15px;
        }

        .footer {
            margin-top: 18px;
            font-size: 10px;
            text-align: center;
            color: #777;
        }
    </style>
</head>

<body>
    @php
        $fechaGeneracion = \Carbon\Carbon::now()->format('d/m/Y H:i');
    @endphp

    <div class="header">
        <h1>Registro Diario del Comedor</h1>
        <p>Reporte de estudiantes registrados</p>
    </div>

    <div class="meta">
        <div style="float: right">
            <strong>Generado:</strong>
            {{ $fechaGeneracion }}<br>
            Total de registros: {{ $registros->count() }}
        </div>
        <div>
            <strong>Rango solicitado:</strong><br>
            Desde: {{ $fecha_desde ?? '—' }}<br>
            Hasta: {{ $fecha_hasta ?? '—' }}
        </div>
    </div>

    @if ($registros->isEmpty())
        <div class="empty-state">
            No se encontraron registros para los filtros seleccionados.
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>PNF</th>
                    <th>Fecha Registro</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($registros as $index => $registro)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $registro->nombre_persona ?? '—' }}</td>
                        <td>{{ $registro->apellido_persona ?? '—' }}</td>
                        <td>{{ $registro->nombre_pnf ?? '—' }}</td>
                        <td>
                            @if (!empty($registro->fecha_regis_diario_c))
                                {{ \Carbon\Carbon::parse($registro->fecha_regis_diario_c)->format('d/m/Y') }}
                            @else
                                —
                            @endif
                        </td>
                        <td>Aprobado</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        Reporte emitido por el sistema de {{ config("adminlte.title") }} — {{ $fechaGeneracion }}
    </div>

</body>

</html>
