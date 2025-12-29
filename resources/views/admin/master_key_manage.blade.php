<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Actualizar Llave Maestra</title>
    <style>body{font-family:Arial,Helvetica,sans-serif;padding:24px} .box{max-width:520px;margin:24px auto;padding:20px;border:1px solid #eee;border-radius:8px;background:#fff} .input{width:100%;padding:10px;margin:8px 0;border-radius:6px;border:1px solid #ccc} .btn{background:#b71c1c;color:#fff;padding:10px 14px;border-radius:6px;border:none;cursor:pointer}</style>
</head>
<body>
<div class="box">
    <h3>Actualizar Llave Maestra</h3>
    @if(session('status'))<div style="color:green">{{ session('status') }}</div>@endif
    @if($errors->any())<div style="color:#b71c1c">{{ implode(', ', $errors->all()) }}</div>@endif
    <form method="POST" action="{{ route('admin.master_key.update') }}">
        @csrf
        <label>Llave maestra actual</label>
        <input class="input" name="current_master_key" type="password" required>
        <label>Nueva llave maestra</label>
        <input class="input" name="new_master_key" type="password" required>
        <input class="input" name="new_master_key_confirmation" type="password" required placeholder="Confirmar nueva llave">
        <button class="btn" type="submit">Actualizar Llave Maestra</button>
    </form>
</div>
</body>
</html>
