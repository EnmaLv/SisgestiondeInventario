<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preguntas de seguridad</title>
    <style>body{font-family:Arial,Helvetica,sans-serif}.box{width:100%;max-width:720px;margin:40px auto;padding:20px;background:#fff;border-radius:8px;border:1px solid #eee}.input{width:100%;padding:10px;margin:8px 0;border:1px solid #ccc;border-radius:6px}.btn{background:#b71c1c;color:#fff;padding:10px;border:none;border-radius:6px;width:100%;cursor:pointer}.q{margin-bottom:12px}</style>
</head>
<body>
<div class="box">
    <h3>Responde las preguntas de seguridad</h3>
    @if($errors->any())<div style="color:#b71c1c">{{ $errors->first() }}</div>@endif
    <form method="POST" action="{{ route('password.recover.verify') }}">
        @csrf
        @foreach($questions as $i => $q)
            <div class="q">
                <label>{{ $q['question'] }}</label>
                <input class="input" name="answers[{{ $i }}]" placeholder="Respuesta" required>
            </div>
        @endforeach
        <button class="btn" type="submit">Verificar</button>
    </form>
    <p style="margin-top:12px;color:#666">Role: {{ $role }}</p>
</div>
</body>
</html>
