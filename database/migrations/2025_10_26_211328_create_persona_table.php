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

        Schema::create('perfil', function (Blueprint $table) {
            $table->id('id_perfil');
            $table->string('nombre_perfil');
            $table->foreignId('id_estatus')->references('id_estatus')->on('estatus')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('sede', function (Blueprint $table) {
            $table->id('id_sede');
            $table->string('nombre_sede');
            $table->foreignId('id_estado')->constrained('estados')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('persona', function (Blueprint $table) {
            $table->id('id_persona');
            $table->string('nombre_persona');
            $table->string('segundo_nombre_persona')->nullable();
            $table->string('apellido_persona');
            $table->string('segundo_apellido_persona')->nullable();
            $table->string('cedula_persona');
            $table->string('telefono_persona');
            $table->string('genero_persona');
            $table->integer('edad_persona');
            $table->date('fecha_nacimiento_persona');
            $table->string('email_persona');
            $table->foreignId('id_perfil')->references('id_perfil')->on('perfil')->onDelete('cascade');
            $table->foreignId('id_sede')->references('id_sede')->on('sede')->onDelete('cascade');
        });

        Schema::create('direccion', function (Blueprint $table) {
            $table->id('id_direccion');
            $table->string('sector');
            $table->string('calle');
            $table->foreignId('id_persona')->references('id_persona')->on('persona')->onDelete('cascade');
            $table->foreignId('id_localidad')->constrained('localidads')->cascadeOnDelete();

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('persona');
    }
};
