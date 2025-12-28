<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
        'nombre_persona',
        'segundo_nombre_persona',
        'apellido_persona',
        'segundo_apellido_persona',
        'cedula_persona',
        'telefono_persona',
        'genero_persona',
        'edad_persona',
        'fecha_nacimiento_persona',
        'email_persona',
        'id_perfil',
        'id_sede',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_persona', 'id_persona');
    }
}

