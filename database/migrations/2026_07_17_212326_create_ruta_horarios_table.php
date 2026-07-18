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
        Schema::create('ruta_horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_ruta_id')->constrained('bus_rutas')->onDelete('cascade');
            $table->time('hora_salida');
            $table->enum('tipo_viaje', ['entrada', 'salida'])->default('entrada');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ruta_horarios');
    }
};
