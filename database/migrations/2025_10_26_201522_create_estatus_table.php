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
        Schema::create('estatus', function (Blueprint $table) {
            $table->id('id_estatus');
            $table->string('nombre_estatus');
            $table->timestamps();
        });

        Schema::create('estado_ve', function (Blueprint $table) {
            $table->id('id_estado_ve');
            $table->string('nombre_estado_ve');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estatus');
    }
};
