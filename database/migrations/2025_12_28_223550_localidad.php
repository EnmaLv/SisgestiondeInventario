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
        Schema::create('localidads', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_localidad');
            $table->boolean('status')->default(true);
            $table->foreignId('municipio_id')->constrained('municipios')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('localidads');
    }
};
