<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('historia_clinicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('usuario', 'id_usuario')->onDelete('cascade');
            $table->foreignId('psicologo_id')->constrained('usuario', 'id_usuario')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historia_clinicas');
    }
};
