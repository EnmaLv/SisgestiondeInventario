<?php

namespace App\Models\Becas;

use Illuminate\Database\Eloquent\Model;

class Lapso extends Model
{
    protected $table = 'be_lapsos';

    protected $fillable = [
        'codigo',
        'fecha_inicio',
        'fecha_fin',
        'es_actual',
        'permite_solicitudes',
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'es_actual' => 'boolean',
        'permite_solicitudes' => 'boolean',
    ];
}
