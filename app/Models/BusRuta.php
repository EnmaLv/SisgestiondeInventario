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
        'hora_salida_manana',
        'hora_salida_tarde',
        'hora_salida_noche',
        'descripcion',
        'sucursal_origen_id',
        'sucursal_destino_id',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function sucursalOrigen()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_origen_id');
    }

    public function sucursalDestino()
    {
        return $this->belongsTo(Sucursal::class, 'sucursal_destino_id');
    }

    public function getDescripcionAttribute($value): string
    {
        return $value ?? 'Ninguna';
    }

    public static function listarRutas($buscar = null, $estado = 1)
    {
        return self::query()
            ->with(['sucursalOrigen', 'sucursalDestino'])
            ->when($buscar, fn ($q) => $q->where('nombre', 'like', "%{$buscar}%"))
            ->when($estado !== null && $estado !== '', fn ($q) => $q->where('estado', $estado))
            ->orderBy('nombre')
            ->paginate(10)
            ->withQueryString();
    }

    public static function crearRuta(array $datos)
    {
        return self::create($datos);
    }

    public static function actualizarRuta(BusRuta $ruta, array $datos)
    {
        $ruta->update($datos);
        return $ruta;
    }
    public function verificarNombre(Request $request)
    {
        $query = BusRuta::where('nombre', trim($request->nombre));
        if ($request->exclude) {
            $query->where('id', '!=', $request->exclude);
        }
        return response()->json(['existe' => $query->exists()]);
    }
    
}