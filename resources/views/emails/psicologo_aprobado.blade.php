<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuenta Aprobada</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f8fafc; margin: 0; padding: 40px 20px; color: #334155; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
        .header { background-color: #0ea5e9; padding: 30px; text-align: center; color: white; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 700; }
        .content { padding: 40px 30px; }
        .content h2 { color: #0f172a; font-size: 20px; margin-top: 0; }
        .content p { line-height: 1.6; margin-bottom: 20px; }
        .btn { display: inline-block; padding: 12px 24px; background-color: #0ea5e9; color: white; text-decoration: none; border-radius: 8px; font-weight: bold; margin-top: 10px; }
        .footer { background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #64748b; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Bienvenido a Psico-Guía UPTP!</h1>
        </div>
        <div class="content">
            <h2>Hola, {{ $usuario->nombres ?? 'Profesional' }}</h2>
            <p>Nos complace informarle que tu solicitud de registro como psicólogo en la plataforma institucional ha sido <strong>aprobada</strong> por la administración.</p>
            <p>A partir de este momento, puedes iniciar sesión en el sistema con tus credenciales y acceder a tu panel de control, gestionar pacientes y empezar a utilizar todas las herramientas profesionales disponibles.</p>
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="btn">Iniciar Sesión Ahora</a>
            </div>
        </div>
        <div class="footer">
            <p>Este es un mensaje automático generado por Psico-Guía UPTP. Por favor, no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>
