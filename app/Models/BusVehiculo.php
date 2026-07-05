<?php

namespace App\Models;

use App\Traits\ConvierteAMayusculasNoEloquent;
use Illuminate\Database\Eloquent\Model;

class BusVehiculo extends Model
{
    use ConvierteAMayusculasNoEloquent;

    protected $table = 'bus_vehiculos';

    protected $fillable = [
        'placa',
        'bus_modelo_id',
        'anio',
        'color',
        'cantidad_pasajeros',
        'bus_tipo_combustible_id',
        'cantidad_bocas',
        'capacidad_tanque_litros',
        'consumo_litros_km',
        'km_actual',
        'km_proximo_mantenimiento',
        'conductor_id',
        'sucursal_id',
        'activo',
        'estado',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function modelo()
    {
        return $this->belongsTo(BusModelo::class, 'bus_modelo_id');
    }

    public function tipoCombustible()
    {
        return $this->belongsTo(BusTipoCombustible::class, 'bus_tipo_combustible_id');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function conductor()
    {
        return $this->belongsTo(\App\Models\Usuario::class, 'conductor_id', 'id_usuario');
    }

    public static function listarVehiculos($buscar = null, $activo = 1)
    {
        return self::query()
            ->with(['modelo.busMarca', 'tipoCombustible', 'sucursal'])
            ->when($buscar, fn ($q) => $q->where('placa', 'like', "%{$buscar}%")
                ->orWhere('color', 'like', "%{$buscar}%"))
            ->when($activo !== null && $activo !== '', fn ($q) => $q->where('activo', $activo))
            ->orderBy('placa')
            ->paginate(10)
            ->withQueryString();
    }

    public static function crearVehiculo(array $datos)
    {
        return self::create($datos);
    }

    public static function actualizarVehiculo(BusVehiculo $vehiculo, array $datos)
    {
        $vehiculo->update($datos);
        return $vehiculo;
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'disponible'   => '<span class="rd-badge rd-badge-success">Disponible</span>',
            'en_ruta'      => '<span class="rd-badge rd-badge-info">En Ruta</span>',
            'mantenimiento'=> '<span class="rd-badge rd-badge-warning">Mantenimiento</span>',
            'inactivo'     => '<span class="rd-badge rd-badge-danger">Inactivo</span>',
            default        => '<span class="rd-badge">Desconocido</span>',
        };
    }
}