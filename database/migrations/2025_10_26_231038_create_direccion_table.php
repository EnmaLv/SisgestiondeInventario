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

        Schema::create('municipio', function (Blueprint $table) {
            $table->id('id_municipio');
            $table->string('nombre_municipio');
            $table->foreignId('id_estado_ve')->references('id_estado_ve')->on('estado_ve')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('parroquia', function (Blueprint $table){
            $table->id('id_parroquia');
            $table->string('nombre_parroquia');
            $table->foreignId('id_municipio')->references('id_municipio')->on('municipio')->onDelete('cascade');
            $table->timestamps();
        });


        Schema::create('direccion', function (Blueprint $table) {
            $table->id('id_direccion');
            $table->string('sector');
            $table->string('calle');
            $table->foreignId('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->foreignId('id_parroquia')->references('id_parroquia')->on('parroquia')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estado_ve');
        Schema::dropIfExists('municipio');
        Schema::dropIfExists('parroquia');
        Schema::dropIfExists('direccion');
    }
};
