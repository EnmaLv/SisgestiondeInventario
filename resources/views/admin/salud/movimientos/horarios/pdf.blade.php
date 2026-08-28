<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario de Consultorio - {{ $consultorio->nombre }}</title>
    <style>
        body {
            font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #334155;
            margin: 0;
            padding: 0;
        }
        
        @page { margin: 40px 25px 40px 25px; }
        
        .header-title { text-align: center; margin-bottom: 10px; margin-top: 5px; }
        .header-title h1 { margin: 0; font-size: 18px; font-weight: 800; color: #03133d; letter-spacing: 0.5px; text-transform: uppercase; }
        .header-title p { margin: 4px 0 0; font-size: 12px; font-weight: 700; color: #0284c7; text-transform: uppercase; }
        
        .jornada-header {
            background-color: #f1f5f9;
            color: #000000;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            padding: 4px 10px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            margin-top: 5px; 
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            overflow: hidden;
        }
        th, td { 
            padding: 5px 4px; 
            text-align: center; 
            vertical-align: middle; 
            border-bottom: 1px solid #e2e8f0;
            border-right: 1px solid #e2e8f0;
        }
        th:last-child, td:last-child { border-right: none; }
        tr:last-child td { border-bottom: none; }

        th { 
            font-weight: 800; 
            font-size: 10px;
            color: #1e293b;
            text-transform: uppercase; 
            background-color: #f1f5f9; 
        }
        .time-col { 
            width: 15%; 
            font-weight: 700; 
            font-size: 8px;
            color: #334155;
            background-color: #f8fafc;
            white-space: nowrap;
        }
        
        .bloque-asignado { 
            background-color: #e0f2fe; 
            color: #0369a1; 
            padding: 4px 2px;
            border-radius: 5px; 
            border: 1px solid #bae6fd;
            font-weight: 800;
            font-size: 8px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .empty-dash {
            color: #cbd5e1;
            font-weight: bold;
            font-size: 10px;
        }

        .watermark {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: -10;
            width: 55%;
        }
    </style>
</head>
<body>

    @if(file_exists(public_path('img/logo-universidad-watermark.png')))
        <img src="{{ public_path('img/logo-universidad-watermark.png') }}" class="watermark" alt="Logo de fondo">
    @endif

    <main>
        <div class="header-title">
            <h1>PROGRAMACIÓN DE HORARIOS DE CONSULTORIO</h1>
            <p>{{ mb_strtoupper($consultorio->nombre) }}</p>
        </div>

        <table>
            <thead>
                <tr>
                    <th class="time-col">HORA</th>
                    @foreach($dias as $diaKey => $diaLabel)
                        <th>{{ mb_strtoupper($diaLabel) }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($bloquesJornadas as $jornada => $bloques)
                    <tr>
                        <td colspan="{{ count($dias) + 1 }}" class="jornada-header">
                            JORNADA {{ mb_strtoupper($jornada) }}
                        </td>
                    </tr>
                    @foreach($bloques as $bloque)
                        @php
                            $inicioFormatted = \Carbon\Carbon::parse($bloque['inicio'])->format('g:i A');
                            $finFormatted    = \Carbon\Carbon::parse($bloque['fin'])->format('g:i A');
                            $inicioKey       = \Carbon\Carbon::parse($bloque['inicio'])->format('H:i');
                            $finKey          = \Carbon\Carbon::parse($bloque['fin'])->format('H:i');
                        @endphp
                        <tr>
                            <td class="time-col">{{ $inicioFormatted }} - {{ $finFormatted }}</td>
                            @foreach($dias as $diaKey => $diaLabel)
                                @php
                                    $key = "{$diaKey}|{$inicioKey}|{$finKey}";
                                    $isAsignado = !empty($activosMap[$key]);
                                @endphp
                                <td>
                                    @if($isAsignado)
                                        <div class="bloque-asignado">
                                            ASIGNADO
                                        </div>
                                    @else
                                        <span class="empty-dash">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </main>

</body>
</html>