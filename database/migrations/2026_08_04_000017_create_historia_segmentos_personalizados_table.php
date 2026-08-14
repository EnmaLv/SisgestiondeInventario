<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historia_segmentos_personalizados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seccion_id')->constrained('historia_secciones_personalizadas')->onDelete('cascade');
            $table->string('titulo')->nullable();
            $table->string('subtitulo')->nullable();
            $table->string('icono')->nullable();
            $table->longText('contenido')->nullable();
            $table->integer('orden')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historia_segmentos_personalizados');
    }
};
