<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusModelo;
use App\Models\BusMarca;
use App\Models\BusTipoCombustible;
use App\Models\BusRuta;
use App\Models\Sucursal;
use App\Models\User;

class BusVehiculo extends Model
{
    protected $table = 'bus_vehiculos';
    protected $fillable = [
        'placa',
        'bus_modelo_id',
        'bus_marca_id',
        'anio',
        'color',
        'cantidad_pasajeros',
        'bus_tipo_combustible_id',
        'cantidad_bocas',
        'capacidad_tanque_litros',
        'consumo_litros_km',
        'km_actual',
        'km_proximo_mantenimiento',
        'bus_ruta_id',
        'conductor_id',
        'sucursal_id',
        'activo',
        'estado',
    ];
    
    public function modelo()
    {
        return $this->belongsTo(BusModelo::class, 'bus_modelo_id');
    }
    
    public function marca()
    {
        return $this->belongsTo(BusMarca::class, 'bus_marca_id');
    }
    
    public function tipoCombustible()
    {
        return $this->belongsTo(BusTipoCombustible::class, 'bus_tipo_combustible_id');
    }
    
    public function ruta()
    {
        return $this->belongsTo(BusRuta::class, 'bus_ruta_id');
    }
    
    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
    
    public function conductor()
    {
        return $this->belongsTo(User::class, 'conductor_id');
    }
}
