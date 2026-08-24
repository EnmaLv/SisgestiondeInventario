#!/bin/bash
set -e

echo "Esperando a que MySQL esté listo..."
sleep 5

if [ "${APP_ENV}" = "production" ]; then
    echo "Compilando assets para producción..."
    npm run build
else
    echo "Iniciando Vite en modo desarrollo..."
    npm run dev &
fi

echo "Iniciando queue worker..."
php artisan queue:work --daemon &

echo "Iniciando Reverb..."
php artisan reverb:start --host=0.0.0.0 --port=8080 &

echo "Iniciando servidor Laravel..."
php artisan serve --host=0.0.0.0 --port=8000