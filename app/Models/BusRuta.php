<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sucursal;

class BusRuta extends Model
{
    protected $table = 'bus_rutas';
    
    protected $fillable = [
        'nombre',
        'distancia_km',
        'hora_salida_manana',
        'hora_salida_tarde',
        'hora_salida_noche',
        'archivo',
        'descripcion',
        'sucursal_origen_id',
        'sucursal_destino_id',
        'estado',
    ];
    
    public function sucursalOrigen()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_origen_id');
    }
    
    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_destino_id');
    }
}
