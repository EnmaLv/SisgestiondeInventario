<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExchangeRates;

class PrecioProducto extends Model
{
    /** @use HasFactory<\Database\Factories\PrecioProductoFactory> */
    use HasFactory;

    protected $table = 'precio_productos';

    protected $fillable = [
        'producto_id',
        'costo_usd',
        'margen',
        'precio_usd',
    ];

    // Relación con producto
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }

    // Atributo dinámico: precio en bolívares
    public function getPrecioBsAttribute()
    {
        // Obtiene la última tasa oficial
        $tasa = ExchangeRates::where('nombre', 'Oficial')->latest()->first();

        if ($tasa) {
            // Calcula precio con margen
            $precioUSD = $this->precio_usd ?? $this->costo_usd;
            $margen = $this->margen ?? 0;

            $precioConMargenUSD = $precioUSD * (1 + $margen / 100);

            // Convierte a bolívares
            return round($precioConMargenUSD * $tasa->promedio, 2);
        }

        return null;
    }
}
