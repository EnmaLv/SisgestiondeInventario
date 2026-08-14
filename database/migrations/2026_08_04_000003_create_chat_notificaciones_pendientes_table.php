<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_notificaciones_pendientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id');
            $table->unsignedBigInteger('receiver_id');
            $table->integer('cantidad_mensajes')->default(1);
            $table->timestamp('programado_para');
            $table->enum('estado', ['pendiente', 'enviada', 'cancelada', 'error'])->default('pendiente');
            $table->timestamps();

            $table->index(['estado', 'programado_para']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_notificaciones_pendientes');
    }
};
