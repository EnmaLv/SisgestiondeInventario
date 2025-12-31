<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Rol extends Model
{
    protected $table = 'rol';
    protected $primaryKey = 'id_rol';
    public $timestamps = true;

    protected $fillable = [
        'nombre',
        'descripcion',
        'menu_permissions',
    ];

    protected $casts = [
        'menu_permissions' => 'array',
    ];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'rol_usuario', 'id_rol', 'id_usuario');
    }
}
