<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrapropuesta Aceptada</title>
</head>
<body>
    <p>Hola,</p>
    
    <p>El paciente <strong>{{ $paciente }}</strong> aceptó tu contrapropuesta de horario a las <strong>{{ $hora }}</strong> el día <strong>{{ $fecha }}</strong>.</p>
    
    <p><a href="{{ route('admin.psicologia.maestros.agenda.index') }}" style="padding: 10px 15px; background-color: #0284c7; color: white; text-decoration: none; border-radius: 5px;">Ir a mi Agenda</a></p>
    
    <p>Gracias,</p>
    <p>Equipo de Psicoguía</p>
</body>
</html>
