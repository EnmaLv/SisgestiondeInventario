<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cita Rechazada</title>
</head>
<body>
    <p>Hola,</p>
    
    <p>El psicólogo <strong>{{ $psicologo }}</strong> ha rechazado tu cita.</p>
    
    <p><a href="{{ route('admin.psicologia.maestros.citas.index') }}" style="padding: 10px 15px; background-color: #0284c7; color: white; text-decoration: none; border-radius: 5px;">Ir a mis Citas</a></p>
    
    <p>Gracias,</p>
    <p>Equipo de Psicoguía</p>
</body>
</html>
