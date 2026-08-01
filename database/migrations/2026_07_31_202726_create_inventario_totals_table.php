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
        Schema::create('inventario_totals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('protucto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('sede_id')->constrained('sede')->onDelete('cascade');
            $table->decimal('cantidad_total', 14, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_totals');
    }
};
