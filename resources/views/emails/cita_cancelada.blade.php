<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aviso de Cita Cancelada</title>
</head>
<body>
    @if($cancelledBy === 'paciente')
        <p>Hola <strong>{{ $psicologo }}</strong>,</p>
        <p>El paciente <strong>{{ $paciente }}</strong> canceló la cita pautada para el <strong>{{ $fecha }}</strong> a las <strong>{{ $hora }}</strong>.</p>
        <p><a href="{{ route('admin.psicologia.maestros.agenda.index') }}" style="padding: 10px 15px; background-color: #0284c7; color: white; text-decoration: none; border-radius: 5px;">Ir a mi Agenda</a></p>
    @else
        <p>Hola <strong>{{ $paciente }}</strong>,</p>
        <p>El psicólogo <strong>{{ $psicologo }}</strong> canceló la cita pautada para el <strong>{{ $fecha }}</strong> a las <strong>{{ $hora }}</strong>.</p>
        <p>Lo siento, no podré asistir al encuentro. Solicita nuevamente una cita, espero atentamente.</p>
        <p><a href="{{ route('admin.psicologia.maestros.citas.index') }}" style="padding: 10px 15px; background-color: #0284c7; color: white; text-decoration: none; border-radius: 5px;">Ir a mis Citas</a></p>
    @endif
    
    <p>Gracias,</p>
    <p>Equipo de Psicoguía</p>
</body>
</html>
