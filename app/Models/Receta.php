<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
