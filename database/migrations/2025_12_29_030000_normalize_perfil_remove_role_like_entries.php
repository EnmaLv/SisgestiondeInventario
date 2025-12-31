<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('perfil') || ! Schema::hasTable('usuario')) {
            return;
        }

        // Ensure 'Usuario' perfil exists
        $usuarioPerfil = DB::table('perfil')->where('nombre_perfil', 'Usuario')->first();
        if (! $usuarioPerfil) {
            $estatusRow = DB::table('estatus')->orderBy('id_estatus')->first();
            if (! $estatusRow) {
                // create a default estatus row so FK constraint is satisfied
                $estatusId = DB::table('estatus')->insertGetId(['nombre_estatus' => 'Activo', 'created_at' => now(), 'updated_at' => now()]);
            } else {
                $estatusId = $estatusRow->id_estatus;
            }

            $id = DB::table('perfil')->insertGetId(['nombre_perfil' => 'Usuario', 'id_estatus' => $estatusId, 'created_at' => now(), 'updated_at' => now()]);
            $usuarioPerfil = DB::table('perfil')->where('id_perfil', $id)->first();
        }

        // Move users assigned to role-like perfiles (Administrador, Obrero) to 'Usuario' perfil
        $roleLike = ['Administrador', 'Obrero'];
        $rows = DB::table('perfil')->whereIn('nombre_perfil', $roleLike)->get();
        foreach ($rows as $r) {
            DB::table('usuario')->where('id_perfil', $r->id_perfil)->update(['id_perfil' => $usuarioPerfil->id_perfil]);
        }

        // Delete those perfil rows that are role-like
        DB::table('perfil')->whereIn('nombre_perfil', $roleLike)->delete();
    }

    public function down(): void
    {
        // Do not attempt to restore deleted perfiles automatically.
    }
};
