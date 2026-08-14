<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrapropuesta Rechazada</title>
</head>
<body>
    <p>Hola,</p>
    
    <p>El paciente <strong>{{ $paciente }}</strong> ha rechazado tu contrapropuesta de <strong>{{ $bloques_rechazados }}</strong> y envió una nueva propuesta.</p>
    
    <p><a href="{{ route('admin.psicologia.maestros.agenda.index') }}" style="padding: 10px 15px; background-color: #0284c7; color: white; text-decoration: none; border-radius: 5px;">Revisar Nueva Propuesta</a></p>
    
    <p>Gracias,</p>
    <p>Equipo de Psicoguía</p>
</body>
</html>
