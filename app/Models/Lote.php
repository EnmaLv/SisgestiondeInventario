<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{

    protected $table = 'lotes';

    protected $fillable = [
        'codigo_lote',
        'fecha_entrada',
        'fecha_vencimiento',
        'cantidad_inicial',
        'cantidad_actual',
        'precio_compra',
        'estado',
        'producto_id',
        'proveedor_id',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function inventarioSucursalLotes()
    {
        return $this->hasMany(InventarioSucursalLote::class);
    }
}
