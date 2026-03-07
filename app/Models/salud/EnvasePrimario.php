<?php

namespace App\Models\salud;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class EnvasePrimario extends Model
{
    use HasFactory;
    protected $table = 'envase_primarios';

    protected $fillable = [
        'nombre',
        'estado',
    ];
}
