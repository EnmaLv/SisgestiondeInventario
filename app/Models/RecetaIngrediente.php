<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RecetaIngrediente extends Model
{
    use HasFactory;

    protected $table = 'receta_ingredientes';

    protected $fillable = [
        'recetas_id',
        'producto_id',
        'cantidad_porcion',
        'cantidad_gramos',
        'unidad_id',
        'estado',
    ];

    // Relaciones

    public function registro_diario()
    {
        return $this->hasMany(Registro_diario::class);
    }

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'recetas_id', 'id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public static function eliminarIngredientes($id)
    {
        return DB::table('receta_ingredientes')
            ->where('id', $id)
            ->delete();
    }
}
