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
        Schema::create('bus_ruta_paradas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_ruta_id')->constrained('bus_rutas')->onDelete('cascade');
            $table->foreignId('bus_parada_id')->constrained('bus_paradas')->onDelete('cascade');
            $table->integer('orden');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_ruta_paradas');
    }
};
