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
        return $this->hasMany(RecetaIngrediente::class, 'recetas_id', 'id');
    }

    public function detalleRegistroDiarios()
    {
        return $this->hasMany(DetalleRegistroDiario::class, 'receta_id');
    }

    public static function tieneIngredienes($id)
    {
        return DB::table('receta_ingredientes')
            ->where('recetas_id', $id)
            ->where('estado', 1)
            ->exists();
    }

    public static function cantidadIngredientes($id)
    {
        return DB::table('receta_ingredientes')
            ->where('recetas_id', $id)
            ->where('estado', 1)
            ->count();
    }

    public static function eliminarReceta($id)
    {
        return DB::table('receta')
            ->where('id', $id)
            ->update([
                'estado' => 0,
                'updated_at' => now()
            ]);
    }

    public static function activarReceta($id)
    {
        return DB::table('receta')
            ->where('id', $id)
            ->update([
                'estado' => 1,
                'updated_at' => now()
            ]);
    }
}
