<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\BusVehiculo;
use App\Models\BusRuta;
use App\Models\Usuario;

class BusViaje extends Model
{
    protected $table = 'bus_viajes';

    protected $fillable = [
        'vehiculo_id',
        'bus_ruta_id',
        'conductor_id',
        'turno',
        'firebase_id',
        'fecha_inicio',
        'km_inicio',
        'km_fin',
        'distancia_km',
        'litros_gastados',
        'pasajeros',
        'hubo_desvio',
        'motivo_desvio',
        'estado',
    ];

    protected $casts = [
        'fecha_inicio' => 'datetime',
        'hubo_desvio' => 'boolean',
        'km_inicio' => 'decimal:2',
        'km_fin' => 'decimal:2',
        'distancia_km' => 'decimal:2',
        'litros_gastados' => 'decimal:2',
        'pasajeros' => 'integer',
    ];

    // Relaciones
    public function vehiculo()
    {
        return $this->belongsTo(BusVehiculo::class, 'vehiculo_id');
    }

    public function ruta()
    {
        return $this->belongsTo(BusRuta::class, 'bus_ruta_id');
    }

    public function conductor()
    {
        return $this->belongsTo(Usuario::class, 'conductor_id', 'id_usuario');
    }

    public static function calcularTurnoActual(): string
    {
        $hora = Carbon::now()->hour;

        if ($hora >= 6 && $hora < 13) {
            return 'mañana';
        } elseif ($hora >= 13 && $hora < 18) {
            return 'tarde';
        } else {
            return 'noche';
        }
    }
}