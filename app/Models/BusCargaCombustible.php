<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusVehiculo;
use App\Models\BusViaje;
use App\Models\BusTipoCombustible;

class BusCargaCombustible extends Model
{
    protected $table = 'carga_combustibles';
    
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
    
    protected $casts = [
        'fecha'         => 'date',
        'litros'        => 'decimal:2',
        'precio_litros' => 'decimal:2',
        'total'         => 'decimal:2',
        'km_al_cargar'  => 'decimal:2',
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

    public static function listarCargas($buscar = null, $vehiculoId = null)
    {
        return self::query()
            ->with(['vehiculo', 'viaje.ruta', 'tipoCombustible'])
            ->when($buscar, fn ($q) => $q
                ->whereHas('vehiculo', fn ($q2) => $q2->where('placa', 'like', "%{$buscar}%"))
                ->orWhereHas('tipoCombustible', fn ($q2) => $q2->where('nombre', 'like', "%{$buscar}%"))
                ->orWhere('observaciones', 'like', "%{$buscar}%"))
            ->when($vehiculoId, fn ($q) => $q->where('bus_vehiculo_id', $vehiculoId))
            ->orderBy('fecha', 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    public static function crearCarga(array $datos): self
    {
        $datos['total'] = $datos['litros'] * $datos['precio_litros'];
        return self::create($datos);
    }

    public static function actualizarCarga(self $carga, array $datos): self
    {
        $datos['total'] = $datos['litros'] * $datos['precio_litros'];
        $carga->update($datos);
        return $carga;
    }
}
