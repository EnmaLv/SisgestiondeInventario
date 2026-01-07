<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bienestar Estudiantil - UPTP J.J. Montilla</title>
    <meta name="description" content="Bienestar Estudiantil UPTP Juan de Jesús Montilla - Acarigua, Portuguesa. Salud, deportes, apoyo académico y programas sociales para estudiantes.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;600;800&family=Inter:wght@300;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />

    <link rel="stylesheet" href="{{ asset('css/diseño.css') }}">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <link rel="icon" href="{{ asset('img/uptp-logo.png') }}" type="image/png">
    <link rel="apple-touch-icon" href="{{ asset('img/uptp-logo.png') }}">
</head>
<body>

<header class="site-header">
    <div class="container header-inner">
        <div class="brand">
            <img src="{{ asset('img/uptp-logo.png') }}" alt="UPTP" class="logo">
            <div class="brand-text">
                <h1>Bienestar Estudiantil</h1>
                <p style="margin:0;font-size:12px;opacity:.9">UPTP J.J. Montilla</p>
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
            <a href="/register" class="btn btn-outline">Registrarse</a>
            <button class="mobile-menu" id="mobileMenuBtn"><i class="fas fa-bars"></i></button>
        </div>
    </div>
</header>

<!-- HERO -->
<section id="inicio" class="hero" aria-label="Hero" style="background-image: url('{{ asset('img/unnamed.jpg') }}'); background-size: cover; background-position: center top;">
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
    <div class="container hero-inner">
        <div class="hero-media" data-aos="zoom-in"></div>
    </div>
</section>


<!-- How it works / Features -->
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

<!-- Insert 'Sobre nosotros' section after 'Cómo funciona' (Programas) -->
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



<!-- Actividades -->
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

<!-- Testimonials slider -->
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
                                <cite>- María P.</cite>
                            </blockquote>
                        </div>
                </div>
                <div class="swiper-slide">
                    <div class="testimonial-card">
                        <div class="thumb" style="background-image:url('{{ asset('img/estudiante2.png') }}')"></div>
                        <blockquote>
                            "Las actividades deportivas me conectaron con compañeros y mejoraron mi salud." 
                            <cite>- José R.</cite>
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
            <!-- Add Arrows -->
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer id="contactos" class="site-footer">
    <div class="footer-top">
        <div class="container footer-top-inner">
            <div class="footer-left">
                <img src="{{ asset('img/uptp-logo.png') }}" alt="UPTP" class="logo-foot">
                <div class="footer-brand-text">
                    <h4>Bienestar Estudiantil</h4>
                    <p class="muted" style="margin:0;font-size:13px">UPTP J.J. Montilla</p>
                </div>
            </div>
            <div class="footer-right">
                <div class="follow">SÍGUENOS</div>
                <div class="social-icons">
                    <a href="https://www.instagram.com/bienestaruptp/" target="_blank" rel="noopener" aria-label="instagram" class="social"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </div>

    <div class="footer-divider" role="presentation"></div>

    <div class="footer-mid">
        <div class="container footer-mid-inner">
            <div class="col locations">
                <h5>SEDE PRINCIPAL</h5>
                <p>Diagonal a la Cruz Roja, Sector Bellas Artes<br>Acarigua, Portuguesa</p>
                <p class="muted">Tel: 0414-9548887</p>
            </div>

            <div class="col links">
                <h5>ENLACES</h5>
                <ul>
                    <li><a href="#inicio">Inicio</a></li>
                    <li><a href="#programas">Programas</a></li>
                    <li><a href="#sobre-nosotros">Sobre nosotros</a></li>
                    <li><a href="#actividades">Actividades</a></li>
                </ul>
            </div>

            <div class="col contact">
                <h5>CONTACTO</h5>
                <p>info@uptp.edu.ve</p>
                <p class="muted">Horario de atención: Lunes a Viernes</p>
            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container footer-bottom-inner">
            <div class="left">© {{ date('Y') }} Bienestar Estudiantil UPTP — Unidad de Sistemas</div>
            <div class="right">Privacidad y Seguridad &nbsp;|&nbsp; Términos y Condiciones</div>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="{{ asset('js/landing.js') }}"></script>
</body>
</html>
