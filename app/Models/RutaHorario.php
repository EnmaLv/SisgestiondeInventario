<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RutaHorario extends Model
{
    protected $table = 'ruta_horarios';

    protected $fillable = [
        'bus_ruta_id',
        'hora_salida',
        'tipo_viaje',
        'estado'
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function ruta()
    {
        return $this->belongsTo(BusRuta::class, 'bus_ruta_id');
    }
}
