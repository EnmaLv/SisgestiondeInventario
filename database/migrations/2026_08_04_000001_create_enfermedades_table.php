<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enfermedades', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 50)->nullable()->unique();
            $table->string('nombre', 255);                      
            $table->enum('categoria', ['mental', 'fisica', 'biopsicosocial'])->default('fisica');
            $table->unsignedTinyInteger('nivel')->default(0);   
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enfermedades');
    }
};
