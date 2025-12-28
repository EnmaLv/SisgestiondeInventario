<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro</title>
	<style>
		body{margin:0;font-family:Arial,Helvetica,sans-serif;position:relative}
		.wrap{display:flex;height:100vh}
		.left{width:40%;padding:24px;background:#fff;display:flex;flex-direction:column;justify-content:center;align-items:center}
		.right{width:60%;background-color:#b71c1c;color:#fff;display:flex;align-items:flex-end;justify-content:flex-end;padding:28px;background-image: url("{{ asset('img/fondo.png') }}");background-repeat:no-repeat;background-size:cover;background-position:center}
		.card{width:100%;max-width:520px;margin:0 auto;text-align:center}
		.input{width:100%;padding:12px;margin:10px 0;border:1px solid #ccc;border-radius:18px}
		/* botones pequeños y centrados (coherentes con login) */
		.btn{background:#b71c1c;color:#fff;padding:10px 16px;border:none;border-radius:24px;width:200px;cursor:pointer;margin:12px auto 0;display:block;font-size:14px}
		.btn-outline{background:#fff;color:#b71c1c;border:2px solid #b71c1c;padding:10px 16px;border-radius:24px;width:200px;cursor:pointer;margin:8px auto 0;display:block;text-decoration:none;text-align:center;font-size:14px}
		.title{font-size:22px;margin-bottom:8px}
		.sec-q{display:flex;gap:8px}
		.logo{position:absolute;top:18px;left:18px;width:72px;height:72px;display:flex;align-items:center;justify-content:center}
		.logo img{width:56px;height:56px}
		.right .corner-text{font-size:28px;margin:0;text-align:right;max-width:60%}
		@media (max-width:900px){
			.right .corner-text{font-size:14px}
		}
	</style>
</head>
<body>
<div class="logo">
	<img src="{{ asset('img/logo.png') }}" alt="Logo">
</div>
<div class="wrap">
	<div class="left">
		<div class="card">
			<h2 class="title">Crear cuenta</h2>

			@if($errors->any())
				<div style="color:#b71c1c;margin-bottom:12px">{{ implode(', ', $errors->all()) }}</div>
			@endif

			<form method="POST" action="{{ route('register') }}">
				@csrf
				<input class="input" name="name" placeholder="Nombre completo" value="{{ old('name') }}" required>
				<input class="input" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required>
				<input class="input" name="password" type="password" placeholder="Contraseña" required>
				<input class="input" name="password_confirmation" type="password" placeholder="Confirmar contraseña" required>

				<h4 style="margin-top:12px">Preguntas de seguridad</h4>
				<div id="questions">
					<div class="sec-q">
						<input class="input" name="security_questions[0][question]" placeholder="Pregunta 1" required>
						<input class="input" name="security_questions[0][answer]" placeholder="Respuesta 1" required>
					</div>
					<div class="sec-q">
						<input class="input" name="security_questions[1][question]" placeholder="Pregunta 2" required>
						<input class="input" name="security_questions[1][answer]" placeholder="Respuesta 2" required>
					</div>
				</div>

				@php $hasAdmin = \App\Models\User::where('role','Administrador')->count() > 0; @endphp
				@if(!$hasAdmin)
					<input class="input" name="master_key" placeholder="Llave Maestra (requerida para administrador)" required>
				@else
					<!-- Si ya existe un Administrador, no se muestra el campo de Llave Maestra -->
				@endif

				<button class="btn" type="submit">REGISTRARSE</button>
				<a href="{{ route('login') }}" class="btn-outline" style="margin-top:8px;">VOLVER AL LOGIN</a>
			</form>
		</div>
	</div>
	<div class="right">
		<div style="max-width:600px;" class="corner-text">
			<h1 class="corner-text">Bienvenido</h1>
			<p style="opacity:0.9;text-align:right">Crea tu cuenta para continuar</p>
		</div>
	</div>
</div>
</body>
</html>