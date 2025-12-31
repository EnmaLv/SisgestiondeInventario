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
            // Authentication and security fields consolidated from later alters
            $table->text('master_key')->nullable();
            $table->json('security_questions')->nullable();
            $table->json('extra_permissions')->nullable();
            $table->timestamps();
        });

        // configuracion_sistema table
        Schema::create('configuracion_sistema', function (Blueprint $table) {
            $table->id();
            $table->string('clave_parametro')->unique();
            $table->text('valor_parametro');
            $table->timestamps();
        });

        // seed a default perfil used for grouping users (do NOT create role-like perfiles such as 'Administrador' here)
        if (Schema::hasTable('perfil') && Schema::hasTable('estatus')) {
            $estatusExists = DB::table('estatus')->exists();
            if ($estatusExists) {
                $exists = DB::table('perfil')->where('nombre_perfil', 'Usuario')->count();
                if ($exists === 0) {
                    $estatusRow = DB::table('estatus')->orderBy('id_estatus')->first();
                    $estatusId = $estatusRow->id_estatus ?? null;
                    if ($estatusId) {
                        DB::table('perfil')->insert([
                            ['nombre_perfil' => 'Usuario', 'id_estatus' => $estatusId, 'created_at' => now(), 'updated_at' => now()],
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
