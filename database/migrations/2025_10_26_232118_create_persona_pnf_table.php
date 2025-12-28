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
        Schema::create('pnf', function (Blueprint $table) {
            $table->id('id_pnf');
            $table->string('nombre_pnf');
            $table->foreignId('id_estatus')->nullable()->constrained('estatus', 'id_estatus')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('persona_pnf', function (Blueprint $table) {
            $table->id('id_persona_pnf');
            $table->foreignId('id_persona')
                ->references("id_persona")->on("persona")
                ->onDelete('cascade');
            $table->foreignId('id_pnf')
                ->references("id_pnf")->on("pnf")
                ->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('persona_pnf');
        Schema::dropIfExists('pnf');
    }
};
