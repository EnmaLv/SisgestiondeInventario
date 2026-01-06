<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MovimientoInventario extends Model
{
    protected $table = 'movimiento_inventarios';

    protected $fillable = [
        'producto_id',
        'lote_id',
        'sucursal_id',
        'tipo_movimiento',
        'unidad_id',
        'cantidad',
        'cantidad_gramos',
        'fecha',
        'observaciones',
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public static function getData(Array $filtro, bool $isPdf)
    {
        $query = self::query();
        // Buscar por lote o producto
        if ($filtro['buscar']) {
            $query->where(function($q) use ($filtro) {

                // Código de lote
                $q->where('tipo_movimiento', 'like', "%{$filtro['buscar']}%")->orWhereHas('lote.producto', function($p) use ($filtro){
                    $p->where('nombre', 'like', "%{$filtro['buscar']}%");
                });

                $q->orWhereHas('lote', function($p) use ($filtro){
                    $p->where('codigo_lote', 'like', "%{$filtro['buscar']}%");
                });

            });
        }

        // Filtrar por estado
        if ($filtro['activo'] !== null && $filtro['activo'] !== '') {
            $query->where('estado', (int)$filtro['activo']);
        }

        // Filtrar por tipo de movimiento
        if ($filtro['tipo_movimiento'] !== null && $filtro['tipo_movimiento'] !== '') {
            $query->where('tipo_movimiento', 'LIKE', '%' . $filtro['tipo_movimiento'] . '%');
        }

        // Filtrar por fecha desde
        if ($filtro['fecha_desde'] !== null && $filtro['fecha_desde'] !== '') {
            $query->whereDate('fecha', '>=', $filtro['fecha_desde']);
        }

        // Filtrar por fecha hasta
        if ($filtro['fecha_hasta'] !== null && $filtro['fecha_hasta'] !== '') {
            $query->whereDate('fecha', '<=', $filtro['fecha_hasta']);
        }

        // Ejecutar consulta
        $movimiento = $query
            ->with(['lote.producto', 'lote.proveedor', 'sucursal', 'unidad'])
            ->orderBy('id','desc');
        
        // Si marca es un pdf devolvemos una coleccion
        if (!$isPdf) {
            $movimiento = $movimiento->paginate(10);
        }else{
            $movimiento = $movimiento->get();
        }
        
        return $movimiento;
    }
}
