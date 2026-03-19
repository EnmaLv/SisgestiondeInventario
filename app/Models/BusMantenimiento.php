<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusVehiculo;
use App\Models\Usuario;

class BusMantenimiento extends Model
{
    protected $table = 'bus_mantenimientos';
    
    protected $fillable = [
        'bus_vehiculo_id',
        'tipo',
        'titulo',
        'descripcion',
        'costo',
        'fecha',
        'km_al_servicio',
        'proximo_km',
        'proxima_fecha',
        'estado',
        'usuario_registro_id',
    ];
    
    public function vehiculo()
    {
        return $this->belongsTo(BusVehiculo::class, 'bus_vehiculo_id');
    }
    
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'usuario_registro_id');
    }
}
