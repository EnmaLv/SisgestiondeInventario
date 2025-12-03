<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Receta extends Model
{
    /** @use HasFactory<\Database\Factories\RecetaFactory> */
    use HasFactory;

    protected $table = 'recetas';

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function recetaIngredientes()
    {
        return $this->hasMany(RecetaIngrediente::class, 'recetas_id');
    }

    public function detalleRegistroDiarios()
    {
        return $this->hasMany(DetalleRegistroDiario::class, 'receta_id');
    }

    public static function eliminarReceta($id)
    {
        return DB::table('recetas')
            ->where('id', $id)
            ->update([
                'estado' => 0,
                'updated_at' => now()
            ]);
    }

    public static function activarReceta($id)
    {
        return DB::table('recetas')
            ->where('id', $id)
            ->update([
                'estado' => 1,
                'updated_at' => now()
            ]);
    }
}
