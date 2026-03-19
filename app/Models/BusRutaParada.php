<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusRuta;
use App\Models\BusParada;

class BusRutaParada extends Model
{
    protected $table = 'bus_ruta_paradas';
    
    protected $fillable = [
        'bus_ruta_id',
        'bus_parada_id',
        'orden',
        'estado',
    ];
    
    public function busRuta()
    {
        return $this->belongsTo(BusRuta::class, 'bus_ruta_id');
    }
    
    public function busParada()
    {
        return $this->belongsTo(BusParada::class, 'bus_parada_id');
    }
}
