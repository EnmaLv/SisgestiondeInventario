
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="{{ asset('img/uptp-logo.png') }}" type="image/png">
	<title>Registro</title>
	<style>
		body{margin:0;font-family:Arial,Helvetica,sans-serif;position:relative}
		.wrap{display:flex;height:100vh}
		.left{width:40%;padding:24px;background:#fff;display:flex;flex-direction:column;justify-content:center;align-items:center}
		.right{width:60%;background-color:#b71c1c;color:#fff;display:flex;align-items:flex-end;justify-content:flex-end;padding:28px;background-image: url("{{ asset('img/fondo.png') }}");background-repeat:no-repeat;background-size:cover;background-position:center}
		.card{width:100%;max-width:520px;margin:0 auto;text-align:center}
		.input{width:100%;padding:12px;margin:10px 0;border:1px solid #ccc;border-radius:18px;box-sizing:border-box}
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
	<img src="{{ asset('img/Logo.png') }}" alt="Logo">
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
				<div style="display:flex;gap:8px;width:100%;">
					<input class="input" name="first_name" placeholder="Primer nombre" value="{{ old('first_name') }}" required style="flex:1;min-width:0">
					<input class="input" name="first_lastname" placeholder="Primer apellido" value="{{ old('first_lastname') }}" required style="flex:1;min-width:0">
				</div>
				<input class="input" name="cedula" placeholder="Cédula de identidad" value="{{ old('cedula') }}" required maxlength="8" inputmode="numeric" pattern="\d{1,8}" oninput="this.value=this.value.replace(/\D/g,'').slice(0,8)">
				<input id="telefono" class="input" name="telefono" placeholder="Número telefónico" value="{{ old('telefono') }}" maxlength="12" inputmode="numeric" pattern="\d{4}-\d{7}" oninput="formatTelefono(this)">
				<input class="input" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required>
				<input class="input" name="password" type="password" placeholder="Contraseña" required>
				<input class="input" name="password_confirmation" type="password" placeholder="Confirmar contraseña" required>

					<h4 style="margin-top:12px;margin-bottom:6px">Preguntas de seguridad</h4>
					@php
						$questions = [
							'¿Cuál es el nombre de tu primera mascota?',
							'¿Cuál es el nombre de tu madre?',
							'¿En qué ciudad naciste?',
							'¿Cuál es tu comida favorita?',
							'¿Cuál fue tu primer colegio?',
							'¿Cuál es el segundo nombre de tu padre?',
						];
					@endphp

					<div style="display:flex;gap:8px;width:100%;margin-top:12px">
						<select name="security_questions[0][question]" id="q0_type" class="input" required style="flex:1;min-width:0">
							<option value="" disabled selected>Pregunta 1</option>
							@foreach($questions as $q)
								<option value="{{ $q }}">{{ $q }}</option>
							@endforeach
						</select>
						<input class="input" name="security_questions[0][answer]" placeholder="Respuesta 1" required style="flex:1;min-width:0">
					</div>

					<div style="display:flex;gap:8px;width:100%;margin-top:8px">
						<select name="security_questions[1][question]" id="q1_type" class="input" required style="flex:1;min-width:0">
							<option value="" disabled selected>Pregunta 2</option>
							@foreach($questions as $q)
								<option value="{{ $q }}">{{ $q }}</option>
							@endforeach
						</select>
						<input class="input" name="security_questions[1][answer]" placeholder="Respuesta 2" required style="flex:1;min-width:0">
					</div>

				@php
					try {
						$adminRol = \App\Models\Rol::where('nombre','Administrador')->first();
						$hasAdmin = $adminRol ? $adminRol->usuarios()->count() > 0 : false;
					} catch (\Throwable $e) {
						$hasAdmin = \App\Models\Usuario::join('perfil', 'usuario.id_perfil', '=', 'perfil.id_perfil')->where('perfil.nombre_perfil','Administrador')->count() > 0;
					}
				@endphp
				@if(!$hasAdmin)
					<input class="input" name="master_key" placeholder="Llave Maestra (requerida para administrador)" required>
				@endif

				<button class="btn" type="submit">REGISTRARSE</button>
			</form>

				<!-- inline security questions added above inside form -->
		</div>
	</div>
	<div class="right">
		<div style="max-width:600px;" class="corner-text">
			<h1 class="corner-text"></h1>
			<p style="opacity:0.9;text-align:right"></p>
		</div>
	</div>
		<script>
		function formatTelefono(el){
			var v = el.value.replace(/\D/g,'').slice(0,11);
			if(v.length>4){
				el.value = v.slice(0,4)+'-'+v.slice(4);
			} else {
				el.value = v;
			}
		}
		// initialize on load
		document.addEventListener('DOMContentLoaded', function(){
			var t = document.getElementById('telefono');
			if(t && t.value){ formatTelefono(t); }

			var q0_type = document.getElementById('q0_type');
			var q1_type = document.getElementById('q1_type');

			// Validate questions on submit: ensure not equal and that both selected
			var regForm = document.querySelector('form[action="{{ route('register') }}"]');
			if(regForm){
				regForm.addEventListener('submit', function(e){
					var q0val = q0_type ? q0_type.value : '';
					var q1val = q1_type ? q1_type.value : '';
					if(!q0val || !q1val){
						alert('Complete ambas preguntas de seguridad.');
						e.preventDefault();
						return;
					}
					if(q0val === q1val){
						alert('Seleccione dos preguntas diferentes.');
						e.preventDefault();
						return;
					}
				});
			}
		});
		</script>
</div>
</body>
</html>
