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
        Schema::create('bus_modelos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bus_marca_id')->constrained('bus_marcas')->onDelete('cascade');
            $table->string('nombre', 100);
            $table->string('descripcion', 255)->nullable();
            $table->tinyInteger('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_modelos');
    }
};
