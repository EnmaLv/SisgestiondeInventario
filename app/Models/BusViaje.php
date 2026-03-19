<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusVehiculo;
use App\Models\BusRuta;
use App\Models\Usuario;

class BusViaje extends Model
{
    protected $table = 'bus_viajes';

    protected $fillable = [
        'bus_vehiculo_id',
        'bus_ruta_id',
        'conductor_id',
        'turno',
        'firebase_id',
        'fecha_inicio',
        'fecha_fin',
        'km_inicio',
        'km_fin',
        'distancia_km',
        'litros_gastados',
        'pasajeros',
        'observaciones',
        'estado',
    ];  


    public function vehiculo()
    {
        return $this->belongsTo(BusVehiculo::class, 'bus_vehiculo_id');
    }

    public function ruta()
    {
        return $this->belongsTo(BusRuta::class, 'bus_ruta_id');
    }

    public function conductor()
    {
        return $this->belongsTo(Usuario::class, 'conductor_id');
    }
}
