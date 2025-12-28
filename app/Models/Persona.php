<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    protected $table = 'persona';
<<<<<<< HEAD
    protected $primaryKey = 'id_persona';
    public $timestamps = false;

    protected $fillable = [
=======
    protected $id = 'id_persona';

    protected $fillable = [
        'id_persona',
        'cedula_persona',
>>>>>>> 357e4cdaba75ae2dc079ffec813e4fa3fb3f6164
        'nombre_persona',
        'segundo_nombre_persona',
        'apellido_persona',
        'segundo_apellido_persona',
<<<<<<< HEAD
        'cedula_persona',
=======
>>>>>>> 357e4cdaba75ae2dc079ffec813e4fa3fb3f6164
        'telefono_persona',
        'genero_persona',
        'edad_persona',
        'fecha_nacimiento_persona',
        'email_persona',
<<<<<<< HEAD
        'id_perfil',
        'id_sede',
    ];

    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'id_persona', 'id_persona');
    }
}

=======
    ];

}
>>>>>>> 357e4cdaba75ae2dc079ffec813e4fa3fb3f6164
