<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecetaIngrediente extends Model
{
    /** @use HasFactory<\Database\Factories\RecetaIngredienteFactory> */
    use HasFactory;

    protected $table = 'detalle_registro_diarios';

    protected $fillable = [
        'recetas_id',
        'producto_id',
        'cantidad_porcion',
    ];
}
