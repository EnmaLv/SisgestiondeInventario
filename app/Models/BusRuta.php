<?php

namespace App\Models;

use App\Traits\ConvierteAMayusculasNoEloquent;
use Illuminate\Database\Eloquent\Model;

class BusRuta extends Model
{
    use ConvierteAMayusculasNoEloquent;

    protected $table = 'bus_rutas';

    protected $fillable = [
        'nombre',
        'distancia_km',
        'descripcion',
        'sucursal_id',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }

    public function horarios()
    {
        return $this->hasMany(RutaHorario::class, 'bus_ruta_id');
    }

    public function paradas()
    {
        return $this->belongsToMany(BusParada::class, 'bus_ruta_paradas')
            ->withPivot('orden')
            ->orderBy('bus_ruta_paradas.orden');
    }

    public function getDescripcionAttribute($value): string
    {
        return $value ?? 'Ninguna';
    }

    public static function listarRutas($buscar = null, $estado = 1)
    {
        return self::query()
            ->with(['sucursal', 'horarios'])
            ->when($buscar, fn($q) => $q->where('nombre', 'like', "%{$buscar}%"))
            ->when($estado !== null && $estado !== '', fn($q) => $q->where('estado', $estado))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();
    }

    public static function crearRuta(array $datos)
    {
        return self::create([
            'nombre'       => $datos['nombre'],
            'distancia_km' => $datos['distancia_km'],
            'descripcion'  => $datos['descripcion'] ?? null,
            'sucursal_id'  => $datos['sucursal_id'],
            'estado'       => 1,
        ]);
    }

    public static function actualizarRuta(BusRuta $ruta, array $datos)
    {
        $ruta->update([
            'nombre'       => $datos['nombre'],
            'distancia_km' => $datos['distancia_km'],
            'descripcion'  => $datos['descripcion'] ?? null,
            'sucursal_id'  => $datos['sucursal_id'],
        ]);
        return $ruta;
    }
}
