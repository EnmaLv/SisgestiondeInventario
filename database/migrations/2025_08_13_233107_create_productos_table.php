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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();

            $table->string('codigo', 50)->unique();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('imagen', 255)->nullable();
            $table->decimal('precio_compra', 10, 2);
            $table->decimal('stock_minimo', 14, 2)->default(0);
            $table->decimal('stock_maximo', 14, 2)->default(0);
            $table->decimal('peso_contenido', 14, 2)->default(0);
            $table->foreignId('unidad_id')->constrained('unidades')->onDelete('cascade');
            $table->boolean('estado')->default(true);
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
