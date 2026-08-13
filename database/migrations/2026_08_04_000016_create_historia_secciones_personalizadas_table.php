<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historia_secciones_personalizadas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('historia_clinica_id')->constrained('historia_clinicas')->onDelete('cascade');
            $table->string('titulo');
            $table->string('descripcion_general')->nullable();
            $table->integer('orden')->default(0);
            $table->tinyInteger('status')->default(1)->comment('1=activo, 0=eliminado (soft delete)');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historia_secciones_personalizadas');
    }
};
