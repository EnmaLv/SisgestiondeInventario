<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Llave Maestra</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;background:#f7f7f7}.box{width:100%;max-width:420px;margin:80px auto;padding:24px;background:#fff;border-radius:8px;box-shadow:0 6px 18px rgba(0,0,0,0.06)}.btn{background:#b71c1c;color:#fff;padding:10px;border:none;border-radius:6px;width:100%;cursor:pointer}.input{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px}</style>
</head>
<body>
<div class="box">
    <h3>Llave Maestra</h3>
    <p>Introduce la llave maestra para completar el acceso de Administrador.</p>

    @if($errors->any())
        <div style="color:#b71c1c">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.master_key.verify') }}">
        @csrf
        <input class="input" name="master_key" placeholder="Llave Maestra" required>
        <button class="btn" type="submit">Verificar</button>
    </form>
</div>
</body>
</html>
