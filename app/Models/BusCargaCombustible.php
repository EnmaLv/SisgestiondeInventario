<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusVehiculo;
use App\Models\BusViaje;
use App\Models\BusTipoCombustible;

class BusCargaCombustible extends Model
{
    protected $table = 'bus_carga_combustibles';
    
    protected $fillable = [
        'bus_vehiculo_id',
        'bus_viaje_id',
        'bus_tipo_combustible_id',
        'fecha',
        'litros',
        'precio_litros',
        'total',
        'km_al_cargar',
        'boca_numero',
        'observaciones',
    ];
    
    public function vehiculo()
    {
        return $this->belongsTo(BusVehiculo::class, 'bus_vehiculo_id');
    }
    
    public function viaje()
    {
        return $this->belongsTo(BusViaje::class, 'bus_viaje_id');
    }
    
    public function tipoCombustible()
    {
        return $this->belongsTo(BusTipoCombustible::class, 'bus_tipo_combustible_id');
    }
}
