<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Horario Semanal</title>
    <style>
        
        body {
            font-family: 'Nunito', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #374151;
        }
        
        @page { margin: 40px 25px 80px 25px; }
        header { position: fixed; top: -60px; left: 0px; right: 0px; height: 50px; text-align: center; }
        footer { position: fixed; bottom: -60px; left: 0px; right: 0px; height: 50px; text-align: center; }
        
        .header-title { text-align: center; margin-bottom: 25px; margin-top: 10px; }
        .header-title h1 { margin: 0; font-size: 18px; font-weight: 800; color: #111827; letter-spacing: 1px; }
        .header-title p { margin: 5px 0 0; font-size: 12px; font-weight: 600; color: #6b7280; }
        
        table { 
            width: 100%; 
            border-collapse: separate; 
            border-spacing: 0;
            margin-top: 10px; 
            border: 1.5px solid #d1d5db;
            border-radius: 12px;
            overflow: hidden;
        }
        th, td { 
            padding: 10px 8px; 
            text-align: center; 
            vertical-align: middle; 
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }
        th:last-child, td:last-child { border-right: none; }
        tr:last-child td { border-bottom: none; }

        th { 
            font-weight: 700; 
            font-size: 10px;
            color: #383636ff; /*Color de la letra de los días  */
            text-transform: uppercase; 
            background-color: #f9fafb;
            border-bottom: 2px solid #d1d5db; /*Color de la raya debajo letra de los días  */
            letter-spacing: 0.5px;
        }
        .time-col { 
            width: 14%; 
            font-weight: 700; 
            font-size: 8.5px; 
            color: #383636ff; /*Color de letras de la columna de las horas */
            background-color: #f9fafb;
        }
        .day-col { width: 17.2%; }
        
        /* Celda que representa el rango laboral completo — SIN div interno */
        .celda-rango {
            background-color: rgba(249, 250, 251, 0.5);
            border: 0.5px solid #d1d5db;
            padding: 10;
            vertical-align: middle;
        }

        .celda-rango .rango-horas {
            font-size: 13px;
            font-weight: 800;
            color: #383636ff;
            display: block;
            margin-bottom: 4px;
        }

        .celda-rango .rango-nombre {
            font-size: 8.5px;
            font-weight: 700;
            color: #4b5563;
            display: block;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .empty-dash {
            color: #d1d5db;
            font-weight: 800;
            font-size: 14px;
        }

        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            opacity: 0.08;
            z-index: -10;
            width: 55%;
        }
    </style>
</head>
<body>

    <img src="{{ public_path('img/logo-universidad-watermark.png') }}" class="watermark" alt="Logo de fondo">

    <main>
        <div class="header-title">
            <h1>HORARIO LABORAL</h1>
        </div>

    @php
        // Calcular el rango laboral por día (hora_inicio más temprana y hora_fin más tardía)
        $rangoPorDia = [];
        foreach ($dias as $dia) {
            $bloquesDia = $horariosPorDia[$dia] ?? collect();
            if ($bloquesDia->isEmpty()) {
                $rangoPorDia[$dia] = null;
            } else {
                $minInicio = $bloquesDia->min('hora_inicio');
                $maxFin = $bloquesDia->max('hora_fin');
                $rangoPorDia[$dia] = [
                    'inicio' => $minInicio,
                    'fin' => $maxFin,
                ];
            }
        }

        // Determinar el rango global de horas para la tabla
        $globalInicio = null;
        $globalFin = null;
        foreach ($rangoPorDia as $rango) {
            if ($rango) {
                if ($globalInicio === null || $rango['inicio'] < $globalInicio) {
                    $globalInicio = $rango['inicio'];
                }
                if ($globalFin === null || $rango['fin'] > $globalFin) {
                    $globalFin = $rango['fin'];
                }
            }
        }

        if (!$globalInicio) $globalInicio = '07:00';
        if (!$globalFin) $globalFin = '19:00';

        // Generar los intervalos fijos de hora en hora basados en el rango global
        $fixedHours = collect();
        $startOfDay = \Carbon\Carbon::parse($globalInicio)->startOfHour();
        $endOfDay = \Carbon\Carbon::parse($globalFin);
        if ($endOfDay->minute > 0) {
            $endOfDay = $endOfDay->copy()->addHour()->startOfHour();
        }

        while ($startOfDay < $endOfDay) {
            $nextHour = $startOfDay->copy()->addHour();
            $fixedHours->push($startOfDay->format('h:i A') . ' - ' . $nextHour->format('h:i A'));
            $startOfDay = $nextHour;
        }

        // Arreglo para controlar las celdas que debemos omitir (por rowspan)
        $skipCells = [];
        // Flag para saber si ya imprimimos el rango de este día
        $rangoImpreso = [];
        foreach($dias as $dia) {
            $skipCells[$dia] = 0;
            $rangoImpreso[$dia] = false;
        }
    @endphp

    <table>
        <thead>
            <tr>
                <th class="time-col">HORA</th>
                @foreach($dias as $dia)
                    <th class="day-col">{{ mb_strtoupper($dia) }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($fixedHours as $horaFija)
                <tr>
                    <td class="time-col" style="font-size: 8.5px;">{{ $horaFija }}</td>
                    
                    @foreach($dias as $dia)
                        @if($skipCells[$dia] > 0)
                            @php $skipCells[$dia]--; @endphp
                        @else
                            @php
                                $matched = false;
                                $rowStartTime = \Carbon\Carbon::parse(explode(' - ', $horaFija)[0]);
                                $rango = $rangoPorDia[$dia] ?? null;
                                
                                if ($rango && !$rangoImpreso[$dia]) {
                                    $rangoInicio = \Carbon\Carbon::parse($rango['inicio']);
                                    $rangoFin = \Carbon\Carbon::parse($rango['fin']);
                                    
                                    // Redondear inicio al piso de la hora para alinear con la grilla
                                    $rangoInicioHora = $rangoInicio->copy()->startOfHour();
                                    
                                    if ($rowStartTime->equalTo($rangoInicioHora)) {
                                        $matched = true;
                                        $rangoImpreso[$dia] = true;
                                        
                                        // Redondear fin al techo de la hora
                                        $rangoFinHora = $rangoFin->copy();
                                        if ($rangoFinHora->minute > 0) {
                                            $rangoFinHora = $rangoFinHora->addHour()->startOfHour();
                                        }
                                        
                                        $horasDeDuracion = (int) ceil($rangoInicioHora->diffInMinutes($rangoFinHora) / 60);
                                        if ($horasDeDuracion < 1) $horasDeDuracion = 1;
                                        
                                        if ($horasDeDuracion > 1) {
                                            $skipCells[$dia] = $horasDeDuracion - 1;
                                        }
                                        
                                        $textoInicio = $rangoInicio->format('g:i a');
                                        $textoFin = $rangoFin->format('g:i a');
                                        $nombrePsicologo = mb_strtoupper($psicologo->name ?? 'PSICÓLOGO');
                                        
                                        // Aplicar estilo directamente al TD, sin div interno
                                        echo '<td rowspan="' . $horasDeDuracion . '" class="celda-rango">';
                                        echo '<span class="rango-horas">' . $textoInicio . ' - ' . $textoFin . '</span>';
                                        echo '<span class="rango-nombre">' . $nombrePsicologo . '</span>';
                                        echo '</td>';
                                    }
                                }
                                
                                if (!$matched) {
                                    echo '<td><span class="empty-dash">-</span></td>';
                                }
                            @endphp
                        @endif
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
    </main>
</body>
</html>