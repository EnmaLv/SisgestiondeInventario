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
        Schema::create('bus_paradas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->decimal('lat', 10, 7);
            $table->decimal('lng', 11, 7);
            $table->string('direccion', 255)->nullable();
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_paradas');
    }
};
