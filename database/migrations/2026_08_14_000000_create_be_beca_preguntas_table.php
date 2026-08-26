<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('be_beca_preguntas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('beca_id')->index();
            $table->string('texto');
            $table->enum('tipo', ['text', 'number'])->default('text');
            $table->decimal('min', 15, 2)->nullable();
            $table->decimal('max', 15, 2)->nullable();
            $table->timestamps();

            $table->foreign('beca_id')->references('id')->on('be_becas')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('be_beca_preguntas');
    }
};
