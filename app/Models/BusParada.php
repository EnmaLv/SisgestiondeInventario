<?php

namespace App\Models;

use App\Traits\ConvierteAMayusculasNoEloquent;
use Illuminate\Database\Eloquent\Model;

class BusParada extends Model
{
    use ConvierteAMayusculasNoEloquent;

    protected $table    = 'bus_paradas';
    protected $fillable = ['nombre', 'lat', 'lng', 'direccion', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function getDireccionAttribute($value): string
    {
        return $value ?? 'Ninguna';
    }

    public static function listarParadas($buscar = null, $estado = 1)
    {
        return self::query()
            ->when($buscar, fn ($q) => $q->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('direccion', 'like', "%{$buscar}%"))
            ->when($estado !== null && $estado !== '', fn ($q) => $q->where('estado', $estado))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();
    }

    public static function crearParada(array $datos)
    {
        return self::create([
            'nombre'    => $datos['nombre'],
            'lat'       => $datos['lat'] ?? null,
            'lng'       => $datos['lng'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
            'estado'    => 1,
        ]);
    }

    public static function actualizarParada(BusParada $parada, array $datos)
    {
        $parada->update([
            'nombre'    => $datos['nombre'],
            'lat'       => $datos['lat'] ?? null,
            'lng'       => $datos['lng'] ?? null,
            'direccion' => $datos['direccion'] ?? null,
        ]);
        return $parada;
    }
}