<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('rol_modulo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rol_id');
            $table->unsignedBigInteger('modulo_id');
            $table->timestamps();

            $table->foreign('rol_id')->references('id_rol')->on('rol')->onDelete('cascade');
            $table->foreign('modulo_id')->references('id')->on('modulos')->onDelete('cascade');

            $table->unique(['rol_id', 'modulo_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('rol_modulo');
    }
};
