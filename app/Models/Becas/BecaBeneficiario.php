<?php

namespace App\Models\Becas;

use Illuminate\Database\Eloquent\Model;
use App\Models\Persona;

class BecaBeneficiario extends Model
{
    protected $table = 'be_beca_beneficiarios';

    protected $fillable = [
        'beca_id',
        'persona_id',
        'area',
        'horario',
        'tutor_id',
        'observaciones',
        'estado',
        'activo',
        'motivo_suspension',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function beca()
    {
        return $this->belongsTo(Beca::class, 'beca_id');
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class, 'persona_id', 'id_persona');
    }

    public function tutor()
    {
        return $this->belongsTo(Persona::class, 'tutor_id', 'id_persona');
    }
}
