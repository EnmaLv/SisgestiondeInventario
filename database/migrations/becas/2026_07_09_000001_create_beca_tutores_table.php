<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('be_beca_tutores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('beca_id')->constrained('be_becas')->onDelete('cascade');
            $table->foreignId('tutor_id')->constrained('persona', 'id_persona')->onDelete('cascade');
            $table->timestamps();
            $table->unique(['beca_id', 'tutor_id']);
        });

        Schema::table('be_becas', function (Blueprint $table) {
            $table->boolean('requiere_tutor')->default(false)->after('activo');
        });
    }

    public function down(): void
    {
        Schema::table('be_becas', function (Blueprint $table) {
            $table->dropColumn('requiere_tutor');
        });

        Schema::dropIfExists('be_beca_tutores');
    }
};
