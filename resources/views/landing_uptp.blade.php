<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienestar Estudiantil - UPTP J.J. Montilla</title>
    <meta name="description" content="Bienestar Estudiantil UPTP Juan de Jesús Montilla - Acarigua, Portuguesa. Salud, deportes, apoyo académico y programas sociales para estudiantes.">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css">
    <!-- AOS CSS -->
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css">
    
    <style>
        :root {
            --color-primary: #b71c1c;
            --color-primary-dark: #8b0000;
            --color-secondary: #d32f2f;
            --color-text: #1a1a1a;
            --color-text-light: #64748b;
            --color-bg: #ffffff;
            --color-bg-light: #f8fafc;
            --spacing: 80px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: var(--color-text);
            overflow-x: hidden;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* HEADER */
        .site-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.08);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 24px;
            gap: 32px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo {
            width: 56px;
            height: 56px;
            object-fit: contain;
        }

        .brand-text h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--color-primary);
            margin: 0;
            line-height: 1.2;
        }

        .brand-text p {
            margin: 0;
            font-size: 12px;
            opacity: 0.9;
            color: var(--color-text-light);
        }

        .main-nav ul {
            display: flex;
            list-style: none;
            gap: 32px;
        }

        .main-nav a {
            text-decoration: none;
            color: var(--color-text);
            font-weight: 600;
            font-size: 15px;
            transition: color 0.2s ease;
            position: relative;
        }

        .main-nav a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--color-primary);
            transition: width 0.3s ease;
        }

        .main-nav a:hover {
            color: var(--color-primary);
        }

        .main-nav a:hover::after {
            width: 100%;
        }

        .header-ctas {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn {
            padding: 10px 24px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: 2px solid transparent;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            box-shadow: 0 4px 14px rgba(183, 28, 28, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(183, 28, 28, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--color-primary);
            border-color: var(--color-primary);
        }

        .btn-outline:hover {
            background: var(--color-primary);
            color: white;
        }

        .mobile-menu {
            display: none;
            background: none;
            border: none;
            font-size: 24px;
            color: var(--color-primary);
            cursor: pointer;
        }

        /* HERO */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover !important;
            background-position: center !important;
            margin-top: 88px;
            overflow: hidden;
        }

        .hero-overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
        }

        .hero-copy-edge {
            position: relative;
            z-index: 2;
            max-width: 700px;
            padding: 40px;
            text-align: center;
        }

        .eyebrow {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 999px;
            color: white;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
        }

        .hero-copy-edge h2 {
            font-size: 56px;
            font-weight: 900;
            color: white;
            margin-bottom: 24px;
            line-height: 1.1;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-copy-edge p {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.95);
            margin-bottom: 40px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* SECTIONS */
        section {
            padding: var(--spacing) 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-header h3 {
            color: var(--color-primary);
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 12px;
        }

        .section-header h2 {
            font-size: 42px;
            font-weight: 800;
            color: var(--color-text);
            line-height: 1.2;
        }

        /* FEATURES GRID */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 32px;
        }

        .feature {
            background: white;
            padding: 40px 32px;
            border-radius: 20px;
            border: 2px solid #f1f5f9;
            transition: all 0.3s ease;
            text-align: center;
        }

        .feature:hover {
            border-color: var(--color-primary);
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(183, 28, 28, 0.15);
        }

        .feature .icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            box-shadow: 0 8px 24px rgba(183, 28, 28, 0.25);
        }

        .feature h4 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 16px;
            color: var(--color-text);
        }

        .feature p {
            color: var(--color-text-light);
            line-height: 1.7;
        }

        /* VISUAL REFERENCE */
        .visual-reference {
            background: var(--color-bg-light);
        }

        .visual-inner {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .visual-graphic {
            position: relative;
            height: 500px;
        }

        .rect,
        .circle {
            position: absolute;
            background-size: cover;
            background-position: center;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }

        .rect {
            width: 280px;
            height: 320px;
        }

        .rect.tall {
            width: 240px;
            height: 420px;
            top: 0;
            left: 0;
        }

        .rect:not(.tall) {
            width: 280px;
            height: 320px;
            bottom: 40px;
            right: 0;
        }

        .circle {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            border: 8px solid white;
        }

        .visual-text .section-header {
            text-align: left;
            margin-bottom: 32px;
        }

        .visual-text p {
            font-size: 17px;
            color: var(--color-text-light);
            line-height: 1.8;
            margin-bottom: 32px;
        }

        .text-circles {
            display: flex;
            gap: -20px;
        }

        .text-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 4px solid white;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .text-circle.c2 {
            margin-left: -20px;
        }

        /* CTA GRID */
        .cta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 32px;
        }

        .cta-card {
            background: white;
            padding: 40px 32px;
            border-radius: 20px;
            border: 2px solid #f1f5f9;
            text-decoration: none;
            color: inherit;
            transition: all 0.3s ease;
            display: block;
        }

        .cta-card:hover {
            border-color: var(--color-primary);
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(183, 28, 28, 0.15);
        }

        .cta-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            color: white;
            margin-bottom: 24px;
        }

        .cta-card h4 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 12px;
            color: var(--color-text);
        }

        .cta-card p {
            color: var(--color-text-light);
            line-height: 1.7;
        }

        /* TESTIMONIALS */
        .testimonials {
            background: var(--color-bg-light);
        }

        .testimonial-card {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .thumb {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            margin: 0 auto 24px;
            background-size: cover;
            background-position: center;
            border: 4px solid var(--color-primary);
            box-shadow: 0 8px 24px rgba(183, 28, 28, 0.2);
        }

        blockquote {
            font-size: 18px;
            line-height: 1.8;
            color: var(--color-text);
            font-style: italic;
            margin: 0;
        }

        cite {
            display: block;
            margin-top: 16px;
            font-size: 16px;
            font-weight: 700;
            color: var(--color-primary);
            font-style: normal;
        }

        .swiper-button-next,
        .swiper-button-prev {
            color: var(--color-primary);
        }

        .swiper-pagination-bullet-active {
            background: var(--color-primary);
        }

        /* FOOTER */
        .site-footer {
            background: #1a1a1a;
            color: white;
        }

        .footer-top {
            padding: 60px 0 40px;
        }

        .footer-top-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .logo-foot {
            width: 64px;
            height: 64px;
        }

        .footer-brand-text h4 {
            font-size: 20px;
            margin-bottom: 4px;
        }

        .footer-right {
            text-align: right;
        }

        .follow {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 16px;
            letter-spacing: 2px;
        }

        .social-icons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .social {
            width: 48px;
            height: 48px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-decoration: none;
            font-size: 20px;
            transition: all 0.3s ease;
        }

        .social:hover {
            background: var(--color-primary);
            transform: translateY(-4px);
        }

        .footer-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.1);
            margin: 0 24px;
        }

        .footer-mid {
            padding: 40px 0;
        }

        .footer-mid-inner {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-mid h5 {
            font-size: 14px;
            letter-spacing: 2px;
            margin-bottom: 20px;
            color: var(--color-primary);
        }

        .footer-mid p {
            color: rgba(255, 255, 255, 0.8);
            margin-bottom: 12px;
            line-height: 1.7;
        }

        .footer-mid .muted {
            color: rgba(255, 255, 255, 0.5);
        }

        .footer-mid ul {
            list-style: none;
        }

        .footer-mid ul li {
            margin-bottom: 12px;
        }

        .footer-mid ul a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .footer-mid ul a:hover {
            color: var(--color-primary);
        }

        .footer-bottom {
            padding: 24px 0;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom-inner {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 14px;
            color: rgba(255, 255, 255, 0.6);
        }

        /* RESPONSIVE */
        @media (max-width: 1024px) {
            .main-nav {
                display: none;
            }

            .mobile-menu {
                display: block;
            }

            .visual-inner {
                grid-template-columns: 1fr;
                gap: 60px;
            }

            .visual-graphic {
                height: 400px;
            }
        }

        @media (max-width: 768px) {
            :root {
                --spacing: 60px;
            }

            .hero-copy-edge h2 {
                font-size: 36px;
            }

            .hero-copy-edge p {
                font-size: 16px;
            }

            .section-header h2 {
                font-size: 32px;
            }

            .features-grid,
            .cta-grid {
                grid-template-columns: 1fr;
            }

            .footer-top-inner {
                flex-direction: column;
                gap: 32px;
                text-align: center;
            }

            .footer-right {
                text-align: center;
            }

            .social-icons {
                justify-content: center;
            }

            .footer-bottom-inner {
                flex-direction: column;
                gap: 16px;
                text-align: center;
            }

            .header-ctas .btn-outline {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .hero-copy-edge {
                padding: 24px;
            }

            .hero-copy-edge h2 {
                font-size: 28px;
            }

            .logo {
                width: 48px;
                height: 48px;
            }

            .brand-text h1 {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>

<header class="site-header">
    <div class="container header-inner">
        <div class="brand">
            <img src="{{ asset('img/uptp-logo.png') }}" alt="UPTP" class="logo">
            <div class="brand-text">
                <h1>Bienestar Estudiantil</h1>
                <p>UPTP J.J. Montilla</p>
            </div>
        </div>

        <nav class="main-nav" aria-label="Main navigation">
            <ul>
                <li><a href="#inicio">Inicio</a></li>
                <li><a href="#programas">Programas</a></li>
                <li><a href="#sobre-nosotros">Sobre nosotros</a></li>
                <li><a href="#actividades">Actividades</a></li>
                <li><a href="#contactos">Contactos</a></li>
            </ul>
        </nav>

        <div class="header-ctas">
            <a href="/login" class="btn btn-primary">Iniciar Sesión</a>
            @if(! (isset($hasEmployees) && $hasEmployees))
            <a href="/register" class="btn btn-outline">Registrarse</a>
            @endif
            <button class="mobile-menu" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
        </div>
    </div>
</header>

<!-- HERO -->
<section id="inicio" class="hero" aria-label="Hero" style="background-image: url('{{ asset('img/unnamed.jpg') }}');">
    <div class="hero-overlay"></div>
    <div class="hero-copy-edge" data-aos="fade-up">
        <small class="eyebrow">Programa de Bienestar Estudiantil</small>
        <h2>Tu bienestar es nuestra prioridad</h2>
        <p>Servicios integrales: apoyo psicológico, salud, deporte, becas y orientación para el éxito académico y personal de la comunidad estudiantil UPTP.</p>
        <div class="hero-actions">
            <a href="#sobre-nosotros" class="btn btn-primary">Sobre nosotros</a>
            <a href="#programas" class="btn btn-outline">Programas Académicos</a>
        </div>
    </div>
</section>

<!-- PROGRAMAS -->
<section id="programas" class="how-it-works">
    <div class="container">
        <div class="section-header" data-aos="fade-up">
            <h3>Cómo funciona</h3>
            <h2>Programas diseñados para cuidar al estudiante</h2>
        </div>

        <div class="features-grid">
            <div class="feature" data-aos="fade-up" data-aos-delay="50">
                <div class="icon"><i class="fas fa-user-md"></i></div>
                <h4>Apoyo Psicológico</h4>
                <p>Atención profesional, confidencial y accesible para manejar estrés y mejorar tu bienestar mental.</p>
            </div>
            <div class="feature" data-aos="fade-up" data-aos-delay="100">
                <div class="icon"><i class="fas fa-heartbeat"></i></div>
                <h4>Salud y Nutrición</h4>
                <p>Programas de prevención, charlas y seguimiento nutricional para una vida estudiantil saludable.</p>
            </div>
            <div class="feature" data-aos="fade-up" data-aos-delay="150">
                <div class="icon"><i class="fas fa-futbol"></i></div>
                <h4>Deporte y Recreación</h4>
                <p>Actividades deportivas, ligas internas y talleres para integrar salud física y socialización.</p>
            </div>
            <div class="feature" data-aos="fade-up" data-aos-delay="200">
                <div class="icon"><i class="fas fa-hands-helping"></i></div>
                <h4>Becas y Apoyo Social</h4>
                <p>Orientación y postulación a becas, ayudas económicas y servicios de apoyo social.</p>
            </div>
        </div>
    </div>
</section>

<!-- SOBRE NOSOTROS -->
<section id="sobre-nosotros" class="visual-reference" data-aos="fade-up">
    <div class="container visual-inner">
        <div class="visual-graphic">
            <div class="rect tall" style="background-image:url('{{ asset('img/foto4b.jpg') }}')"></div>
            <div class="rect" style="background-image:url('{{ asset('img/foto5b.jpg') }}')"></div>
            <div class="circle overlap" style="background-image:url('{{ asset('img/foto1b.png') }}')"></div>
        </div>

        <div class="visual-text">
            <div class="section-header small">
                <h3>Sobre nosotros</h3>
                <h2>Unidad de Bienestar Estudiantil — UPTP J.J. Montilla</h2>
            </div>
            <p>La Unidad de Bienestar Estudiantil acompaña a la comunidad de la UPTP Juan de Jesús Montilla con servicios de apoyo psicológico, salud, actividades deportivas, programas de becas y asesoría socioacadémica. Nuestro objetivo es promover el desarrollo integral y la calidad de vida de los estudiantes.</p>
            <div class="text-circles">
                <img src="{{ asset('img/foto2b.png') }}" alt="Equipo UPTP" class="text-circle c1">
                <img src="{{ asset('img/foto3b.png') }}" alt="Actividades" class="text-circle c2">
            </div>
        </div>
    </div>
</section>

<!-- ACTIVIDADES -->
<section id="actividades" class="ctas">
    <div class="container" data-aos="fade-up">
        <div class="section-header">
            <h3>Actividades</h3>
            <h2>Lo que hacemos por la comunidad estudiantil</h2>
        </div>

        <div class="cta-grid">
            <a class="cta-card" href="#sobre-nosotros">
                <div class="cta-icon"><i class="fas fa-brain"></i></div>
                <h4>Talleres de Salud Mental</h4>
                <p>Sesiones grupales e individuales, charlas y talleres prácticos para promover el bienestar emocional y la resiliencia estudiantil.</p>
            </a>

            <a class="cta-card" href="#actividades">
                <div class="cta-icon"><i class="fas fa-basketball-ball"></i></div>
                <h4>Deporte y Recreación</h4>
                <p>Ligas internas, entrenamientos y actividades recreativas para fomentar la salud física, trabajo en equipo y convivencia.</p>
            </a>

            <a class="cta-card" href="#programas">
                <div class="cta-icon"><i class="fas fa-hand-holding-usd"></i></div>
                <h4>Programas de Apoyo y Becas</h4>
                <p>Orientación para postular a becas, ayudas económicas y programas sociales que facilitan la permanencia y el éxito académico.</p>
            </a>
        </div>
    </div>
</section>

<!-- TESTIMONIOS -->
<section class="testimonials" data-aos="fade-up">
    <div class="container">
        <div class="section-header">
            <h3>Testimonios</h3>
            <h2>Historias reales de estudiantes</h2>
        </div>

        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="thumb" style="background-image:url('{{ asset('img/estudiante.png') }}')"></div>
                        <blockquote>
                            "El apoyo psicológico me ayudó a manejar la ansiedad y mejorar mi rendimiento académico."
                            <cite>- José R.</cite>
                        </blockquote>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="thumb" style="background-image:url('{{ asset('img/estudiante2.png') }}')"></div>
                        <blockquote>
                            "Las actividades deportivas me conectaron con compañeros y mejoraron mi salud." 
                            <cite>- María P.</cite>
                        </blockquote>
                    </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="thumb" style="background-image:url('{{ asset('img/estudiante3.png') }}')"></div>
                        <blockquote>
                            "Gracias a la orientación pude postular a una beca que cambió mi situación." 
                            <cite>- Carmen L.</cite>
                        </blockquote>
                    </div>
                </div>
            </div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer id="contactos" class="site-footer">
    <div class="footer-top">
        <div class="container footer-top-inner">
            <div class="footer-left">
                <img src="{{ asset('img/uptp-logo.png') }}" alt="UPTP" class="logo-foot">
                <div class="footer-brand-text">
                    <h4>Bienestar Estudiantil</h4>
                    <p class="muted">UPTP J.J. Montilla</p>
                </div>
            </div>
            <div class="footer-right">
                <div class="follow">SÍGUENOS</div>
                <div class="social-icons">
                    <a href="https://www.instagram.com/bienestaruptp/" target="_blank" rel="noopener" aria-label="Instagram" class="social">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-divider" role="presentation"></div>

    <div class="footer-mid">
        <div class="container footer-mid-inner">
            <div class="col locations">
                <h5>SEDE PRINCIPAL</h5>
                <p>Diagonal a la Cruz Roja<br>Sector Bellas Artes<br>Acarigua, Portuguesa</p>
                <p class="muted">Tel: 0414-9548887</p>
            </div>

            <div class="col links">
                <h5>ENLACES RÁPIDOS</h5>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#programas">Programas</a></li>
                    <li><a href="#sobre-nosotros">Sobre nosotros</a></li>
                    <li><a href="#actividades">Actividades</a></li>
                </ul>
            </div>

            <div class="col contact">
                <h5>CONTACTO</h5>
                <p><i class="fas fa-envelope" style="margin-right: 8px; color: var(--color-primary);"></i>info@uptp.edu.ve</p>
                <p><i class="fas fa-phone" style="margin-right: 8px; color: var(--color-primary);"></i>0414-9548887</p>
                <p class="muted">Horario de atención:<br>Lunes a Viernes<br>8:00 AM - 4:00 PM</p>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <div class="left">
                © <span id="currentYear"></span> Bienestar Estudiantil UPTP — Unidad de Sistemas
            </div>
            <div class="right">
                <a href="#" style="color: inherit; text-decoration: none;">Privacidad y Seguridad</a>
                &nbsp;|&nbsp;
                <a href="#" style="color: inherit; text-decoration: none;">Términos y Condiciones</a>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Año dinámico
    document.getElementById('currentYear').textContent = new Date().getFullYear();

    // Inicializar AOS
    AOS.init({
        duration: 800,
        once: true,
        offset: 100
    });

    // Inicializar Swiper
    const swiper = new Swiper('.mySwiper', {
        slidesPerView: 1,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 1,
            },
            768: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 3,
            }
        }
    });

    // Header scroll effect
    let lastScroll = 0;
    const header = document.querySelector('.site-header');

    window.addEventListener('scroll', () => {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            header.style.boxShadow = '0 4px 30px rgba(0, 0, 0, 0.12)';
        } else {
            header.style.boxShadow = '0 2px 20px rgba(0, 0, 0, 0.08)';
        }
        
        lastScroll = currentScroll;
    });

    // Mobile menu (básico)
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mainNav = document.querySelector('.main-nav');
    
    mobileMenuBtn.addEventListener('click', () => {
        alert('Menú móvil - Implementar según necesidades');
    });

    // Smooth scroll para los enlaces internos
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const headerOffset = 88;
                const elementPosition = target.getBoundingClientRect().top;
                const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                window.scrollTo({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
</script>
</body>
</html>