<?php

namespace App\Models;

use App\Traits\ConvierteAMayusculasNoEloquent;
use Illuminate\Database\Eloquent\Model;

class BusModelo extends Model
{
    use ConvierteAMayusculasNoEloquent;

    protected $table    = 'bus_modelos';
    protected $fillable = ['bus_marca_id', 'nombre', 'descripcion', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function busMarca()
    {
        return $this->belongsTo(BusMarca::class, 'bus_marca_id');
    }

    public function getDescripcionAttribute($value): string
    {
        return $value ?? 'Ninguna';
    }

    public static function listarModelos($buscar = null, $estado = 1)
    {
        return self::query()
            ->with('busMarca')
            ->when($buscar, fn ($q) => $q->where('nombre', 'like', "%{$buscar}%"))
            ->when($estado !== null && $estado !== '', fn ($q) => $q->where('estado', $estado))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();
    }

    public static function crearModelo(array $datos)
    {
        return self::create([
            'bus_marca_id' => $datos['bus_marca_id'],
            'nombre'       => $datos['nombre'],
            'descripcion'  => $datos['descripcion'] ?? null,
            'estado'       => 1,
        ]);
    }

    public static function actualizarModelo(BusModelo $modelo, array $datos)
    {
        $modelo->update([
            'bus_marca_id' => $datos['bus_marca_id'],
            'nombre'       => $datos['nombre'],
            'descripcion'  => $datos['descripcion'] ?? null,
        ]);

        return $modelo;
    }
}