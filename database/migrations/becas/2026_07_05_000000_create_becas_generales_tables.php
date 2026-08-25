<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('be_becas', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();
            $table->string('nombre');
            $table->enum('modalidad', ['general', 'trabajo'])->default('general');
            $table->text('descripcion')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });

        Schema::create('be_beca_beneficio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beca_id')->constrained('be_becas')->onDelete('cascade');
            $table->foreignId('beneficio_id')->constrained('be_beneficios')->onDelete('cascade');
            $table->text('observacion')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['beca_id', 'beneficio_id']);
        });

        Schema::create('be_beca_trabajo_asignaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beca_id')->constrained('be_becas')->onDelete('cascade');
            $table->string('area');
            $table->string('horario');
            $table->foreignId('tutor_id')->nullable()->constrained('persona', 'id_persona')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('be_beca_trabajo_asignaciones');
        Schema::dropIfExists('be_beca_beneficio');
        Schema::dropIfExists('be_becas');
    }
};
