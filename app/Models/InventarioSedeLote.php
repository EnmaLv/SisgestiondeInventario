<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioSedeLote extends Model
{
    protected $table = 'inventario_sede_lotes';

    protected $fillable = [
        'sede_id',
        'lote_id',
        'cantidad',
        'cantidad_gramos',
    ];

    public function sede()
    {
        return $this->belongsTo(Sede::class, 'sede_id');
    }

    public function lote()
    {
        return $this->belongsTo(Lote::class);
    }
}