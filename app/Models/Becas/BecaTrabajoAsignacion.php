<?php

namespace App\Models\Becas;

use App\Models\Persona;
use Illuminate\Database\Eloquent\Model;

class BecaTrabajoAsignacion extends Model
{
    protected $table = 'be_beca_trabajo_asignaciones';

    protected $fillable = [
        'beca_id',
        'area',
        'horario',
        'tutor_id',
        'observaciones',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function beca()
    {
        return $this->belongsTo(Beca::class, 'beca_id');
    }

    public function tutor()
    {
        return $this->belongsTo(Persona::class, 'tutor_id', 'id_persona');
    }
}
