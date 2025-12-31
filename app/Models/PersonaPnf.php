<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PersonaPnf extends Model
{
    protected $table = 'persona_pnf';
    protected $id = 'id_persona_pnf';
    protected $fillable = [
        "id_persona",
        "id_persona_pnf ",
        "fecha_inicio",
        "fecha_fin"
    ];

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    public function pnf()
    {
        return $this->belongsTo(Pnf::class, 'id_pnf', 'id_pnf');
    }
}
