<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SobranteComedor extends Model
{
    use HasFactory;

    protected $table = 'sobrantes_comedor';

    protected $fillable = [
        'fecha',
        'cantidad_sobrante',
        'motivo',
        'accion_tomada',
    ];
}
