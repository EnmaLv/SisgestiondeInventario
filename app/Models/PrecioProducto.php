<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrecioProducto extends Model
{
    use HasFactory;

    protected $table = 'precio_productos';

    protected $fillable = [
        'producto_id',
        'costo_usd',
        'margen',
        'precio_usd',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
