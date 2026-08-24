<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'fecha_fin'    => 'datetime',
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
        return $this->belongsTo(Usuario::class, 'conductor_id', 'id_usuario');
    }

    public function cargasCombustible()
    {
        return $this->hasMany(BusCargaCombustible::class, 'bus_viaje_id');
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match($this->estado) {
            'programado' => '<span class="rd-badge rd-badge-warning">Programado</span>',
            'en_curso'   => '<span class="rd-badge rd-badge-info">En Curso</span>',
            'finalizado' => '<span class="rd-badge rd-badge-success">Finalizado</span>',
            'cancelado'  => '<span class="rd-badge rd-badge-danger">Cancelado</span>',
            default      => '<span class="rd-badge">-</span>',
        };
    }

    public static function listarViajes($buscar = null, $estado = null)
    {
        return self::query()
            ->with(['vehiculo', 'ruta', 'conductor'])
            ->when($buscar, fn ($q) => $q
                ->whereHas('vehiculo', fn ($q2) => $q2->where('placa', 'like', "%{$buscar}%"))
                ->orWhereHas('ruta', fn ($q2) => $q2->where('nombre', 'like', "%{$buscar}%")))
            ->when($estado !== null && $estado !== '', fn ($q) => $q->where('estado', $estado))
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
    }

    public static function crearViaje(array $datos): self
    {
        return self::create($datos);
    }

    public static function actualizarViaje(self $viaje, array $datos): self
    {
        $viaje->update($datos);
        return $viaje;
    }
}