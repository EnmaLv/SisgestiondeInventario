<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusTipoCombustible extends Model
{
    protected $table = 'bus_tipo_combustibles';
    
    protected $fillable = [
        'nombre',
        'archivo',
        'estado',
    ];
}
