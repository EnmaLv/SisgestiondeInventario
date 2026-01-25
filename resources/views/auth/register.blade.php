<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Registro - UPTP</title>
	<style>
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}

		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
			position: relative;
			min-height: 100vh;
			background: #f5f5f5;
		}

		.wrap {
			display: flex;
			min-height: 100vh;
		}

		.logo {
			position: fixed;
			top: 24px;
			left: 24px;
			z-index: 1000;
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(10px);
			border-radius: 16px;
			padding: 12px;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
			transition: transform 0.3s ease;
		}

		.logo:hover {
			transform: translateY(-2px);
		}

		.logo img {
			width: 48px;
			height: 48px;
			display: block;
		}

		.left {
			flex: 1;
			min-width: 0;
			padding: 40px 24px;
			background: #fff;
			display: flex;
			flex-direction: column;
			justify-content: center;
			align-items: center;
			overflow-y: auto;
		}

		.right {
			flex: 1;
			min-width: 0;
			background: linear-gradient(135deg, #000000 0%, #000000 100%);
			color: #fff;
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 48px;
			position: relative;
			overflow: hidden;
		}

		.right::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background-image: url("img/unnamed.jpg");
			background-repeat: no-repeat;
			background-size: cover;
			background-position: center;
			opacity: 0.5;
		}

		.card {
			width: 100%;
			max-width: 560px;
			animation: fadeInUp 0.6s ease-out;
		}

		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(20px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}

		.header-icon {
			width: 80px;
			height: 80px;
			margin: 0 auto 20px;
			background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 100%);
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 0 8px 24px rgba(183, 28, 28, 0.25);
		}

		.header-icon svg {
			width: 44px;
			height: 44px;
			fill: #fff;
		}

		.title {
			font-size: 32px;
			font-weight: 700;
			margin-bottom: 8px;
			color: #1a1a1a;
			text-align: center;
		}

		.subtitle {
			font-size: 15px;
			color: #666;
			margin-bottom: 28px;
			text-align: center;
		}

		.form-group {
			margin-bottom: 16px;
			position: relative;
		}

		.form-row {
			display: flex;
			gap: 12px;
			margin-bottom: 16px;
		}

		.form-row .form-group {
			flex: 1;
			min-width: 0;
			margin-bottom: 0;
		}

		.input, .select {
			width: 100%;
			padding: 14px 20px;
			border: 2px solid #e0e0e0;
			border-radius: 12px;
			font-size: 15px;
			transition: all 0.3s ease;
			background: #fafafa;
			font-family: inherit;
		}

		.input:focus, .select:focus {
			outline: none;
			border-color: #b71c1c;
			background: #fff;
			box-shadow: 0 0 0 4px rgba(183, 28, 28, 0.1);
		}

		.input::placeholder {
			color: #999;
		}

		.select {
			cursor: pointer;
			appearance: none;
			background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath fill='%23666' d='M6 8L0 0h12z'/%3E%3C/svg%3E");
			background-repeat: no-repeat;
			background-position: right 16px center;
			padding-right: 40px;
		}

		.select option[disabled] {
			color: #999;
		}

		.error-message {
			background: #ffebee;
			color: #c62828;
			padding: 12px 16px;
			border-radius: 10px;
			margin-bottom: 20px;
			font-size: 14px;
			border-left: 4px solid #c62828;
			animation: shake 0.4s ease;
		}

		@keyframes shake {
			0%, 100% { transform: translateX(0); }
			25% { transform: translateX(-8px); }
			75% { transform: translateX(8px); }
		}

		.section-title {
			font-size: 18px;
			font-weight: 600;
			color: #1a1a1a;
			margin: 24px 0 16px;
			text-align: center;
			position: relative;
		}

		.section-title::before,
		.section-title::after {
			content: '';
			position: absolute;
			top: 50%;
			width: 60px;
			height: 2px;
			background: linear-gradient(to right, transparent, #b71c1c);
		}

		.section-title::before {
			right: calc(100% + 12px);
		}

		.section-title::after {
			left: calc(100% + 12px);
			background: linear-gradient(to left, transparent, #b71c1c);
		}

		.btn {
			width: 100%;
			background: linear-gradient(135deg, #b71c1c 0%, #d32f2f 100%);
			color: #fff;
			padding: 14px 24px;
			border: none;
			border-radius: 12px;
			font-size: 16px;
			font-weight: 700;
			cursor: pointer;
			transition: all 0.3s ease;
			box-shadow: 0 4px 14px rgba(183, 28, 28, 0.3);
			letter-spacing: 0.5px;
			margin-top: 24px;
		}

		.btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 6px 20px rgba(183, 28, 28, 0.4);
		}

		.btn:active {
			transform: translateY(0);
		}

		.corner-text {
			position: relative;
			z-index: 1;
			max-width: 500px;
			animation: fadeIn 1s ease-out 0.3s both;
		}

		@keyframes fadeIn {
			from { opacity: 0; }
			to { opacity: 1; }
		}

		.corner-text h1 {
			font-size: 42px;
			font-weight: 800;
			margin-bottom: 16px;
			line-height: 1.2;
			text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
		}

		.corner-text p {
			font-size: 18px;
			line-height: 1.6;
			opacity: 0.95;
			text-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
		}

		/* Responsive Design */
		@media (max-width: 1024px) {
			.corner-text h1 {
				font-size: 32px;
			}

			.corner-text p {
				font-size: 16px;
			}
		}

		@media (max-width: 768px) {
			.wrap {
				flex-direction: column;
			}

			.logo {
				top: 16px;
				left: 16px;
				padding: 10px;
			}

			.logo img {
				width: 40px;
				height: 40px;
			}

			.left {
				order: 2;
				min-height: auto;
				padding: 32px 20px 40px;
			}

			.right {
				order: 1;
				min-height: 240px;
				padding: 80px 24px 32px;
				align-items: center;
				justify-content: center;
			}

			.corner-text {
				text-align: center;
				max-width: 100%;
			}

			.corner-text h1 {
				font-size: 28px;
			}

			.corner-text p {
				font-size: 15px;
			}

			.card {
				max-width: 100%;
			}

			.title {
				font-size: 28px;
			}

			.header-icon {
				width: 70px;
				height: 70px;
				margin-bottom: 16px;
			}

			.header-icon svg {
				width: 38px;
				height: 38px;
			}

			.section-title::before,
			.section-title::after {
				width: 40px;
			}
		}

		@media (max-width: 480px) {
			.left {
				padding: 24px 16px 32px;
			}

			.right {
				min-height: 200px;
				padding: 70px 20px 24px;
			}

			.corner-text h1 {
				font-size: 24px;
			}

			.corner-text p {
				font-size: 14px;
			}

			.title {
				font-size: 24px;
			}

			.form-row {
				flex-direction: column;
				gap: 16px;
			}

			.input, .select {
				padding: 12px 16px;
				font-size: 14px;
			}

			.btn {
				padding: 12px 20px;
				font-size: 15px;
			}

			.section-title {
				font-size: 16px;
				margin: 20px 0 12px;
			}

			.section-title::before,
			.section-title::after {
				display: none;
			}
		}
	</style>
</head>
<body>
	<div class="logo">
		<img src="img/Logo.png" alt="Logo UPTP">
	</div>

	<div class="wrap">
		<div class="left">
			<div class="card">
				<div class="header-icon">
					<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
						<path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2V7H4v3H1v2h3v3h2v-3h3v-2H6zm9 4c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
					</svg>
				</div>

				<h2 class="title">Registro de Empleado</h2>
				<p class="subtitle">Completa el formulario para registrar al nuevo empleado</p>

				<!-- Simulación de error para el demo -->
				<div class="error-message" style="display:none;">
					Por favor, completa todos los campos requeridos
				</div>

				<form method="POST" action="#" onsubmit="return validateForm(event);">
					<div class="form-row">
						<div class="form-group">
							<input 
								class="input" 
								name="first_name" 
								placeholder="Primer nombre" 
								required
							>
						</div>
						<div class="form-group">
							<input 
								class="input" 
								name="first_lastname" 
								placeholder="Primer apellido" 
								required
							>
						</div>
					</div>

					<div class="form-group">
						<input 
							class="input" 
							name="cedula" 
							placeholder="Cédula de identidad" 
							required 
							maxlength="8" 
							inputmode="numeric" 
							pattern="\d{1,8}" 
							oninput="this.value=this.value.replace(/\D/g,'').slice(0,8)"
						>
					</div>

					<div class="form-group">
						<input 
							id="telefono"
							class="input" 
							name="telefono" 
							placeholder="Número telefónico (0000-0000000)" 
							maxlength="12" 
							inputmode="numeric" 
							pattern="\d{4}-\d{7}" 
							oninput="formatTelefono(this)"
						>
					</div>

					<div class="form-group">
						<input 
							class="input" 
							type="email"
							name="email" 
							placeholder="Correo electrónico" 
							required
						>
					</div>

					<div class="form-group">
						<input 
							class="input" 
							type="password"
							name="password" 
							placeholder="Contraseña" 
							required
						>
					</div>

					<div class="form-group">
						<input 
							class="input" 
							type="password"
							name="password_confirmation" 
							placeholder="Confirmar contraseña" 
							required
						>
					</div>

					<h3 class="section-title">Preguntas de seguridad</h3>

					<div class="form-row">
						<div class="form-group">
							<select name="security_questions[0][question]" id="q0_type" class="select" required>
								<option value="" disabled selected>Selecciona pregunta 1</option>
								<option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
								<option value="¿Cuál es el nombre de tu madre?">¿Cuál es el nombre de tu madre?</option>
								<option value="¿En qué ciudad naciste?">¿En qué ciudad naciste?</option>
								<option value="¿Cuál es tu comida favorita?">¿Cuál es tu comida favorita?</option>
								<option value="¿Cuál fue tu primer colegio?">¿Cuál fue tu primer colegio?</option>
								<option value="¿Cuál es el segundo nombre de tu padre?">¿Cuál es el segundo nombre de tu padre?</option>
							</select>
						</div>
						<div class="form-group">
							<input 
								class="input" 
								name="security_questions[0][answer]" 
								placeholder="Respuesta 1" 
								required
							>
						</div>
					</div>

					<div class="form-row">
						<div class="form-group">
							<select name="security_questions[1][question]" id="q1_type" class="select" required>
								<option value="" disabled selected>Selecciona pregunta 2</option>
								<option value="¿Cuál es el nombre de tu primera mascota?">¿Cuál es el nombre de tu primera mascota?</option>
								<option value="¿Cuál es el nombre de tu madre?">¿Cuál es el nombre de tu madre?</option>
								<option value="¿En qué ciudad naciste?">¿En qué ciudad naciste?</option>
								<option value="¿Cuál es tu comida favorita?">¿Cuál es tu comida favorita?</option>
								<option value="¿Cuál fue tu primer colegio?">¿Cuál fue tu primer colegio?</option>
								<option value="¿Cuál es el segundo nombre de tu padre?">¿Cuál es el segundo nombre de tu padre?</option>
							</select>
						</div>
						<div class="form-group">
							<input 
								class="input" 
								name="security_questions[1][answer]" 
								placeholder="Respuesta 2" 
								required
							>
						</div>
					</div>

					<!-- Campo de llave maestra (mostrar solo si no hay admin) -->
					<div class="form-group" style="display:none;" id="master-key-group">
						<input 
							class="input" 
							name="master_key" 
							placeholder="Llave Maestra (requerida para administrador)"
						>
					</div>

					<button class="btn" type="submit">REGISTRARSE</button>
				</form>
			</div>
		</div>

		<div class="right">
			<div class="corner-text">
				<h1 style="text-align: center">Únete al Departamento</h1>
				<p style="text-align: center">Crea tu cuenta y accede a todos los servicios y recursos de la universidad</p>
			</div>
		</div>
	</div>

	<script>
		function formatTelefono(el) {
			var v = el.value.replace(/\D/g,'').slice(0,11);
			if(v.length > 4) {
				el.value = v.slice(0,4) + '-' + v.slice(4);
			} else {
				el.value = v;
			}
		}

		function validateForm(e) {
			e.preventDefault();
			
			var q0 = document.getElementById('q0_type');
			var q1 = document.getElementById('q1_type');
			
			if(!q0.value || !q1.value) {
				alert('Complete ambas preguntas de seguridad.');
				return false;
			}
			
			if(q0.value === q1.value) {
				alert('Seleccione dos preguntas diferentes.');
				return false;
			}
			
			alert('Formulario validado correctamente');
			// Aquí iría el envío real del formulario
			return false;
		}

		// Initialize on load
		document.addEventListener('DOMContentLoaded', function() {
			var t = document.getElementById('telefono');
			if(t && t.value) { 
				formatTelefono(t); 
			}
		});
	</script>
</body>
</html>