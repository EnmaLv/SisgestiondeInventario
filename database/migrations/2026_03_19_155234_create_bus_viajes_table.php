<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bus_viajes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_vehiculo_id')->constrained('bus_vehiculos')->onDelete('cascade');
            $table->foreignId('bus_ruta_id')->constrained('bus_rutas')->onDelete('cascade');
            $table->unsignedBigInteger('conductor_id')->nullable();
            $table->foreign('conductor_id')->references('id_usuario')->on('usuario')->nullOnDelete();
            $table->enum('turno', ['mañana', 'tarde', 'noche'])->default('mañana')->nullable();
            $table->string('firebase_id', 100)->nullable();
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->decimal('km_inicio', 10, 2)->default(0);
            $table->decimal('km_fin', 10, 2)->default(0);
            $table->decimal('distancia_km', 8, 2)->default(0);
            $table->decimal('litros_gastados', 8, 2)->default(0);
            $table->integer('pasajeros')->default(0);
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['programado', 'en_curso', 'finalizado', 'cancelado'])->default('programado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_viajes');
    }
};
