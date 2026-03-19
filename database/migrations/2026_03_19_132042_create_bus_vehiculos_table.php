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
        Schema::create('bus_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->string('placa', 20);
            $table->foreignId('bus_modelo_id')->constrained('bus_modelos')->onDelete('cascade');
            $table->foreignId('bus_marca_id')->constrained('bus_marcas')->onDelete('cascade');
            $table->time('anio');
            $table->string('color');
            $table->integer('cantidad_pasajeros')->default(0);
            $table->foreignId('bus_tipo_combustible_id')->constrained('bus_tipo_combustibles')->onDelete('cascade');
            $table->integer('cantidad_bocas')->default(1);
            $table->decimal('capacidad_tanque_litros', 8, 2)->default(0);
            $table->decimal('consumo_litros_km', 6, 3)->default(0);
            $table->decimal('km_actual', 10, 2)->default(0);
            $table->decimal('km_proximo_mantenimiento', 10, 2)->default(0);
            $table->foreignId('bus_ruta_id')->constrained('bus_rutas')->onDelete('cascade');
            $table->unsignedBigInteger('conductor_id')->nullable();
            $table->foreign('conductor_id')->references('id_usuario')->on('usuario')->nullOnDelete();
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade');
            $table->tinyInteger('activo')->default(1);
            $table->enum('estado', ['disponible', 'en_ruta', 'mantenimiento', 'inactivo'])->default('disponible');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_vehiculos');
    }
};
