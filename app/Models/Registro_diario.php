<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro_diario extends Model
{
    protected $table = 'registro_diario_c';

    protected $fillable = [
        'id_persona',
        'id_persona_pnf',
        'fecha_regis_diario_c',
        'hora',
    ];

    public $timestamps = false;


    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }


    public function persona_pnf()
    {
        return $this->belongsTo(PersonaPnf::class);
    }
}
