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
        Schema::create('medicamentos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->string('nombre');
            $table->string('descripcion')->nullable();
            $table->string('imagen', 255)->nullable();
            $table->decimal('precio_compra', 10, 2)->nullable();
            $table->decimal('stock_minimo', 14, 2)->default(0);
            $table->decimal('stock_maximo', 14, 2)->default(0);
            $table->decimal('peso_contenido', 14, 2)->default(0);
            $table->foreignId('unidad_id')->constrained('unidades')->onDelete('cascade');
            $table->foreignId('envase_primario_id')->constrained('envase_primarios')->onDelete('cascade');
            $table->foreignId('categoria_medicamento_id')->constrained('categoria_medicamentos')->onDelete('cascade');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medicamentos');
    }
};
