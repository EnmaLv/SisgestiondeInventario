# SIGA - Sistema Integrado de Gestión Universitaria

[![Laravel](https://img.shields.io/badge/Framework-Laravel%2011-red?style=flat&logo=laravel)](https://laravel.com)
[![Docker](https://img.shields.io/badge/Container-Docker-blue?style=flat&logo=docker)](https://www.docker.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

[![Demo Live](https://img.shields.io/badge/Demo-Visitar%20Sistema-brightgreen?style=for-the-badge&logo=google-chrome&logoColor=white)](https://sistemauniversitario-oc5a.onrender.com/)

SIGA es una plataforma web centralizada (Intranet) diseñada para digitalizar, unificar y automatizar la operación interna de las dependencias de bienestar estudiantil. El sistema consolida múltiples áreas críticas de la institución en una sola arquitectura modular, eliminando registros manuales y garantizando trazabilidad total.

---

## Arquitectura Modular del Sistema

El sistema unifica 5 grandes módulos funcionales compartiendo una misma base de datos e infraestructura de seguridad:

- **Módulo de Comedor:** Control del ciclo de servicio alimentario, gestión de recetas, ingredientes, compras y control de asistencia diaria de estudiantes mediante comedor automatizado.
- **Módulo de Salud (Médico y Psicológico):** Agenda de citas, historiales clínicos digitales, emisión de recetas y dispensación de medicamentos con control de inventario por lote.
- **Módulo de Deportes:** Control de inscripciones a actividades y torneos, registro de asistencia y sistema de préstamo de utensilios deportivos con alertas y sanciones.
- **Módulo de Becas:** Recepción y procesamiento de formularios socioeconómicos por periodos académicos, aprobación, renovación y revocación de beneficios.
- **Módulo de Transporte:** Control de flota, asignación de rutas/paradas y monitoreo de mantenimientos preventivos y correctivos. **Aplicación Móvil:** [![GitHub móvil](https://img.shields.io/badge/Repositorio-App%20Móvil-blue?style=flat-square&logo=github)](https://github.com/EnmaLv/MoviBus).

### Características Técnicas Destacadas

- **Inventario Unificado:** Consolidación de stock en tiempo real (alimentos, medicinas, utilería) filtrado dinámicamente por módulo/contexto.
- **Seguridad Avanzada:** Control de acceso granular (RBAC) con soporte para permisos directos de asignación/revocación (`grant/revoke`) a nivel de usuario.
- **Trazabilidad Extrema:** Bitácora de auditoría nativa para el rastreo de cambios administrativos.

---

## 🛠️ Stack Tecnológico

- **Backend & Frontend:** Laravel (PHP) utilizando el motor de plantillas Blade.
- **Estilos y Reactividad:** CSS y JavaScript nativo/estructurado.
- **Entorno de Desarrollo:** Docker & Docker Compose.

---

## 🚀 Guía de Instalación y Despliegue (Docker)

Sigue estos pasos si es la **primera vez** que levantas el proyecto en tu máquina local:

### 1. Inicializar contenedores e instalar dependencias

```bash
# Levantar el entorno y compilar imágenes
docker compose up -d --build

# Apagar los servicios temporalmente para configuraciones iniciales
docker compose down

# Instalar dependencias de PHP (Composer) y Frontend (NPM)
docker compose run --entrypoint "" app composer install
docker compose run --entrypoint "" app npm install
```

### 2. Configurar el entorno de Laravel

```bash
# Encender los servicios en segundo plano
docker compose up -d

# Generar la llave de seguridad de la aplicación
docker compose exec app php artisan key:generate

# Crear el enlace simbólico para el almacenamiento de archivos (imágenes, PDFs, etc.)
docker compose exec app php artisan storage:link

# Ejecutar las migraciones de la Base de Datos junto con los Seeders maestros
docker compose exec app php artisan migrate --seed
```

### 3. Flujo de Trabajo Diario

```bash
# Iniciar el servidor diariamente:
docker compose up -d

# Apagar los servicios al terminar de programar:
docker compose down

# Limpiar y resetear la Base de Datos (En caso de acumular datos de prueba obsoletos):
docker compose exec app php artisan migrate:fresh --seed
```

## 🗺️ Roadmap y Próximos Pasos (TODO)

Nuestra hoja de ruta actual para la optimización del sistema se centra en:

- [ ] **Módulo Comedor:** Migrar el entorno de pruebas de correos de Mailtrap a un servicio de producción real (Brevo, Mailgun o Render).
- [ ] **Base de Datos:** Refactorizar y adaptar el esquema relacional al nuevo modelo unificado de inventario y permisos.
- [ ] **Diseño:** Adaptar de forma estricta las interfaces de todos los módulos para garantizar un comportamiento 100% Responsive.
- [ ] **QA:** Auditoría general de diseño y flujos de negocio del comedor previo al cierre de fase.

---

## 👥 Equipo de Desarrollo

- **EnmaLv** - Lead Developer / DevOps
- **Angel Linarez** (@DevAngelJS) - Fullstack Developer
- **Nohely Sosa** (@Roxanita17) - Fullstack Developer
- **Michele Piñuela** (@Toniielperro) - Fullstack Developer
