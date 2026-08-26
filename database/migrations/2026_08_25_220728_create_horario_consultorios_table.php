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
        Schema::create('horario_consultorios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultorio_id')->constrained('consultorios', 'id')->onDelete('cascade');
            $table->string('dia');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->text('descripcion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horario_consultorios');
    }
};
