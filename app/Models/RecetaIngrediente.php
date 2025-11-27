<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecetaIngrediente extends Model
{
    /** @use HasFactory<\Database\Factories\RecetaIngredienteFactory> */
    use HasFactory;

    protected $table = 'receta_ingredientes';

    protected $fillable = [
        'recetas_id',
        'producto_id',
        'cantidad_porcion',
        'unidad_id',
    ];

    // Relaciones
    public function receta()
    {
        return $this->belongsTo(Receta::class, 'recetas_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }
}
