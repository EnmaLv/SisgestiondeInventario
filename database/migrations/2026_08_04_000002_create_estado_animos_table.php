<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {

        Schema::create('estado_animos', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('valor')->comment('Nivel de 1 a 10');
            $table->string('nombre');
            $table->timestamps();
            $table->tinyInteger('status')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estado_animos');
    }
};