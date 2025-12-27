<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SobranteComedor extends Model
{
    /** @use HasFactory<\Database\Factories\SobranteComedorFactory> */
    use HasFactory;

    protected $fillable = [
        'fecha',
        'cantidad_sobrante',
        'motivo',
        'accion_tomada',
    ];
}
