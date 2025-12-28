<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Iniciar sesión</title>
	<style>
		body{margin:0;font-family:Arial,Helvetica,sans-serif;position:relative}
		.wrap{display:flex;height:100vh}
		.left{width:40%;padding:40px;background:#fff;display:flex;flex-direction:column;justify-content:center;align-items:center}
		.right{width:60%;background-color:#b71c1c;color:#fff;display:flex;align-items:flex-end;justify-content:flex-end;padding:28px;background-image: url("{{ asset('img/fondo.png') }}");background-repeat:no-repeat;background-size:cover;background-position:center}
		.card{width:100%;max-width:360px;margin:0 auto;text-align:center}
		.input{width:100%;padding:12px;margin:10px 0;border:1px solid #ccc;border-radius:18px}
		/* botones pequeños y centrados */
		.btn{background:#b71c1c;color:#fff;padding:10px 16px;border:none;border-radius:24px;width:200px;cursor:pointer;margin:12px auto 0;display:block;font-size:14px}
		.btn-outline{background:#fff;color:#b71c1c;border:2px solid #b71c1c;padding:10px 16px;border-radius:24px;width:200px;cursor:pointer;margin:8px auto 0;display:block;text-decoration:none;text-align:center;font-size:14px}
		.title{font-size:28px;margin-bottom:8px}
		.small{font-size:13px;color:#666}
		.user-icon{width:84px;height:84px;margin:0 auto 12px;display:block}
		.user-icon svg{width:84px;height:84px;fill:#b71c1c}
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
			<div class="user-icon" aria-hidden="true">
				<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
					<path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8V22h19.2v-2.8c0-3.2-6.4-4.8-9.6-4.8z"/>
				</svg>
			</div>
			<h2 class="title">Iniciar sesión</h2>

			@if($errors->any())
				<div style="color:#b71c1c;margin-bottom:12px">{{ $errors->first() }}</div>
			@endif

				<form method="POST" action="{{ route('login') }}">
					@csrf
					<input class="input" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
					<input class="input" name="password" type="password" placeholder="Contraseña" required>
	
					<a class="forgot" href="{{ route('password.recover.email') }}">¿Olvidaste tu contraseña?</a>
					<button class="btn" type="submit">INICIAR SESIÓN</button>
				</form>

				<a class="btn-outline" href="{{ url('/register') }}">REGISTRARSE</a>

			<style>
				.forgot{display:block;color:#b71c1c;margin:6px 0 8px;text-decoration:none;font-weight:600}
			</style>
		</div>
	</div>
	<div class="right">
			<div style="max-width:600px;" class="corner-text">
				<h1 class="corner-text">Bienvenido.</h1>
				<p style="opacity:0.9;text-align:right">Accede a tu panel</p>
			</div>
	</div>
</div>
</body>
</html>