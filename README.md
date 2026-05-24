# COMO USAR DOCKER POR PRIMERA VEZ? Ejecuta esto en la terminal:

docker compose up -d --build

docker compose exec app composer install

docker compose exec app npm install

docker compose exec app php artisan key:generate

docker compose exec app php artisan storage:link

docker compose exec app php artisan migrate --seed

# COMO USAR DIARIAMENTE? Ejecuta esto en la terminal:

docker compose down (Para apagar los servicios de docker cuando termines de programar)

docker compose up -d (Para encender diariamente el servidor)

docker compose exec app php artisan migrate --seed (Si se te llena de basura la bd)

# COSAS POR HACER EN EL MODULO DE COMEDOR

Agregar direccion de la persona en el pdf y excel

Cambiar de mailtrap a un distribuidor de mails de verdad (Bravo, Render, Mailgun, Laravel mail con riesgo de spam)

Acomodar la base de datos a la nueva

Acomodar todos los modulos del sistema para que sean responsive

Ver si hay algun otro error de diseño o funcionalidad para terminar comedor

Filtros piches:
1 en  recetas y Ingredientes 
2.Categorias y Producto 
3.Sedes y Anexos,  Proveedores 
4.Programas de formacion
