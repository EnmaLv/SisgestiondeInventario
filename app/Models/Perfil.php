<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfil';
    protected $primaryKey = 'id_perfil';
    public $timestamps = true;

    protected $fillable = ['nombre_perfil','id_estatus'];
}
