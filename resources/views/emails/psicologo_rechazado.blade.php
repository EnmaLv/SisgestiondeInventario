<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualización de Solicitud</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 40px 20px; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #ef4444; padding: 30px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .content { padding: 40px 30px; }
        .content h2 { color: #0f172a; font-size: 20px; margin-top: 0; }
        .content p { line-height: 1.6; margin-bottom: 20px; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Aviso Importante - Psico-Guía UPTP</h1>
        </div>
        <div class="content">
            <h2>Hola, {{ $usuario->nombres ?? 'Profesional' }}</h2>
            <p>Le escribimos para notificarle que, tras una revisión por parte de la administración, su solicitud para registrarse como psicólogo en nuestra plataforma no ha podido ser aceptada en este momento.</p>
            <p>Esto puede deberse a que sus credenciales no han podido ser verificadas o no cumple con los requisitos institucionales actuales. Su cuenta ha sido inhabilitada.</p>
            <p>Si considera que se trata de un error o desea mayor información, por favor comuníquese directamente con la administración de la institución.</p>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático generado por Psico-Guía UPTP. Por favor, no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>
