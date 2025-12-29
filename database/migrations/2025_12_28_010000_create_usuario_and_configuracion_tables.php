<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // usuario table
        Schema::create('usuario', function (Blueprint $table) {
            $table->id('id_usuario');
            $table->foreignId('id_persona')->constrained('persona', 'id_persona')->onDelete('cascade');
            $table->foreignId('id_perfil')->constrained('perfil', 'id_perfil')->onDelete('cascade');
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });

        // configuracion_sistema table
        Schema::create('configuracion_sistema', function (Blueprint $table) {
            $table->id();
            $table->string('clave_parametro')->unique();
            $table->text('valor_parametro');
            $table->timestamps();
        });

        // seed default perfiles if not present and estatus row exists
        if (Schema::hasTable('perfil') && Schema::hasTable('estatus')) {
            // Only insert if there is an estatus with id_estatus = 1 (or any) to satisfy FK
            $estatusExists = DB::table('estatus')->exists();
            if ($estatusExists) {
                $exists = DB::table('perfil')->whereIn('nombre_perfil', ['Administrador','Obrero','Económico'])->count();
                if ($exists === 0) {
                    // find a sensible id_estatus to reference: try id_estatus = 1 else pick first
                    $estatusRow = DB::table('estatus')->orderBy('id_estatus')->first();
                    $estatusId = $estatusRow->id_estatus ?? null;
                    if ($estatusId) {
                        DB::table('perfil')->insert([
                            ['nombre_perfil' => 'Administrador', 'id_estatus' => $estatusId, 'created_at' => now(), 'updated_at' => now()],
                            ['nombre_perfil' => 'Obrero', 'id_estatus' => $estatusId, 'created_at' => now(), 'updated_at' => now()],
                            ['nombre_perfil' => 'Económico', 'id_estatus' => $estatusId, 'created_at' => now(), 'updated_at' => now()],
                        ]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
        Schema::dropIfExists('configuracion_sistema');
    }
};
