<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $id = 'id_persona';

    protected $fillable = [
        'id_persona',
        'cedula_persona',
        'nombre_persona',
        'segundo_nombre_persona',
        'apellido_persona',
        'segundo_apellido_persona',
        'telefono_persona',
        'genero_persona',
        'edad_persona',
        'fecha_nacimiento_persona',
        'email_persona',
    ];

}
