<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restablecer Contraseña</title>
    <style>body{font-family:Arial,Helvetica,sans-serif}.box{width:100%;max-width:500px;margin:40px auto;padding:20px;background:#fff;border-radius:8px;border:1px solid #eee}.input{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px}.btn{background:#b71c1c;color:#fff;padding:10px;border:none;border-radius:6px;width:100%;cursor:pointer}</style>
</head>
<body>
<div class="box">
    <h3>Restablecer Contraseña</h3>
    <form method="POST" action="{{ route('password.recover.reset_password') }}">
        @csrf
        <input class="input" name="password" type="password" placeholder="Nueva contraseña" required>
        <input class="input" name="password_confirmation" type="password" placeholder="Confirmar contraseña" required>
        <button class="btn" type="submit">Restablecer</button>
    </form>
</div>
</body>
</html>
