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

 
        $usuarioPerfil = DB::table('perfil')->where('nombre_perfil', 'Usuario')->first();
        if (! $usuarioPerfil) {
            $estatusRow = DB::table('estatus')->orderBy('id_estatus')->first();
            if (! $estatusRow) {
               
                $estatusId = DB::table('estatus')->insertGetId(['nombre_estatus' => 'Activo', 'created_at' => now(), 'updated_at' => now()]);
            } else {
                $estatusId = $estatusRow->id_estatus;
            }

            $id = DB::table('perfil')->insertGetId(['nombre_perfil' => 'Usuario', 'id_estatus' => $estatusId, 'created_at' => now(), 'updated_at' => now()]);
            $usuarioPerfil = DB::table('perfil')->where('id_perfil', $id)->first();
        }

        
        $roleLike = ['Administrador', 'Obrero'];
        $rows = DB::table('perfil')->whereIn('nombre_perfil', $roleLike)->get();
        foreach ($rows as $r) {
            DB::table('usuario')->where('id_perfil', $r->id_perfil)->update(['id_perfil' => $usuarioPerfil->id_perfil]);
        }

        
        DB::table('perfil')->whereIn('nombre_perfil', $roleLike)->delete();
    }

    public function down(): void
    {
        
    }
};
