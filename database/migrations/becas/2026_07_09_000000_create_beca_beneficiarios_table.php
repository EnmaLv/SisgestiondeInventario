<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('be_beca_beneficiarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beca_id')->constrained('be_becas')->onDelete('cascade');
            $table->unsignedBigInteger('persona_id');
            $table->string('area')->nullable();
            $table->string('horario')->nullable();
            $table->foreignId('tutor_id')->nullable()->constrained('persona', 'id_persona')->nullOnDelete();
            $table->text('observaciones')->nullable();
            $table->enum('estado', ['activo', 'suspendido', 'finalizado'])->default('activo');
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['beca_id', 'persona_id']);
            $table->foreign('persona_id')->references('id_persona')->on('persona')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('be_beca_beneficiarios');
    }
};
