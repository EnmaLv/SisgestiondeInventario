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
}
