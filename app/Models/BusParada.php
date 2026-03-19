<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusParada extends Model
{
    protected $table = 'bus_paradas';
    
    protected $fillable = [
        'nombre',
        'lat',
        'lng',
        'direccion',
        'archivo',
        'estado',
    ];
}
