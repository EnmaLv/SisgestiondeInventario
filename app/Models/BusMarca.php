<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusMarca extends Model
{
    protected $table    = 'bus_marcas';
    protected $fillable = ['nombre', 'estado'];

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function modelos()
    {
        return $this->hasMany(BusModelo::class);
    }
}
