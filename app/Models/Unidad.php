<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unidad extends Model
{
    use HasFactory;

    protected $table = 'unidades';

    protected $fillable = [
        'nombre',
        'abreviatura',
        'factor_a_gramo',
    ];

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function productos()
    {
        return $this->hasMany(Producto::class);
    }


    public function recetaIngredientes()
    {
        return $this->hasMany(RecetaIngrediente::class);
    }
}
