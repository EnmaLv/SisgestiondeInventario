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
        Schema::create('consumo_diario_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consumo_diario_id')
                ->constrained('consumo_diarios')
                ->onDelete('cascade');
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->onDelete('cascade');
            $table->decimal('cantidad_total', 14, 2)->notNull();
            $table->foreignId('unidad_id')
                ->constrained('unidades')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consumo_diario_detalles');
    }
};
