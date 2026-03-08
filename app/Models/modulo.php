<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    protected $table = 'modulos';
    protected $fillable = ['key','nombre','descripcion','activo'];

    public function roles()
    {
        return $this->belongsToMany(\App\Models\Rol::class, 'rol_modulo', 'modulo_id', 'rol_id');
    }
}