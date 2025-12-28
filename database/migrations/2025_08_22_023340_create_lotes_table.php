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
        Schema::create('lotes', function (Blueprint $table) {
            $table->id();

            $table->string('codigo_lote', 50);
            $table->date('fecha_entrada');
            $table->date('fecha_vencimiento')->nullable();
            $table->decimal('cantidad_inicial', 14, 2)->default(0);
            $table->decimal('cantidad_actual', 14, 2)->default(0);
            $table->decimal('precio_compra', 10, 2)->nullable();
            $table->boolean('estado')->default(true);
            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('proveedor_id')->constrained('proveedors')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lotes');
    }
};
