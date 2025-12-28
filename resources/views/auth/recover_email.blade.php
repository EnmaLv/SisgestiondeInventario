<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Recuperar acceso</title>
    <style>body{font-family:Arial,Helvetica,sans-serif}.box{width:100%;max-width:420px;margin:60px auto;padding:20px;background:#fff;border-radius:8px;border:1px solid #eee}.input{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px}.btn{background:#b71c1c;color:#fff;padding:10px;border:none;border-radius:6px;width:100%;cursor:pointer}</style>
</head>
<body>
<div class="box">
    <h3>Recuperar acceso</h3>
    @if($errors->any())<div style="color:#b71c1c">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.recover.post_email') }}">
        @csrf
        <input class="input" name="email" placeholder="Correo registrado" required>
        <button class="btn" type="submit">Continuar</button>
    </form>
</div>
</body>
</html>
