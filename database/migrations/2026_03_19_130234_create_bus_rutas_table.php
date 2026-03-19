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
        Schema::create('bus_rutas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->decimal('distancia_km', 10, 2);
            $table->timestamp('hora_salida_manana')->nullable();
            $table->timestamp('hora_salida_tarde')->nullable();
            $table->timestamp('hora_salida_noche')->nullable();
            $table->tinyInteger('archivo')->nullable();
            $table->text('descripcion')->nullable();
            $table->foreignId('sucursal_origen_id')->constrained('sucursals')->onDelete('cascade');
            $table->foreignId('sucursal_destino_id')->constrained('sucursals')->onDelete('cascade');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_rutas');
    }
};
