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
        Schema::create('movimiento_inventarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('producto_id')->constrained('productos')->onDelete('cascade');
            $table->foreignId('lote_id')->constrained('lotes')->onDelete('cascade');
            $table->foreignId('sucursal_id')->constrained('sucursals')->onDelete('cascade');
            $table->string('tipo_movimiento', 50);
            $table->decimal('cantidad', 14, 2)->notNull();
            $table->foreignId('unidad_id')->constrained('unidades')->onDelete('cascade');
            $table->timestamp('fecha')->useCurrent();
            $table->text('observaciones')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_inventarios');
    }
};
