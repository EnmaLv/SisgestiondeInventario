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
        Schema::create('precio_medicamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicamento_id')->constrained('medicamentos')->onDelete('cascade');
            $table->decimal('costo_usd', 10, 2);
            $table->decimal('margen', 5, 2)->default(0); // porcentaje (%)
            $table->decimal('precio_usd', 10, 2)->nullable(); // opcional
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('precio_medicamentos');
    }
};
