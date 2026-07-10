<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('be_beca_tutores', function (Blueprint $table) {
            $table->unsignedBigInteger('rol_id')->nullable()->after('tutor_id');
            $table->text('descripcion')->nullable()->after('rol_id');

            $table->foreign('rol_id')
                ->references('id_rol')
                ->on('rol')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('be_beca_tutores', function (Blueprint $table) {
            $table->dropForeign(['rol_id']);
            $table->dropColumn(['rol_id', 'descripcion']);
        });
    }
};
