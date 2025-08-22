<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioSucursalLote extends Model
{
    protected $table = 'inventario_sucursal_lotes';

    protected $fillable = [
        'sucursal_id',
        'lote_id',
        'cantidad',
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursal::class);
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }
}
