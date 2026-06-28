<?php

namespace App\Models;

use App\Models\BusMarca;
use App\Models\BusVehiculo;
use Illuminate\Database\Eloquent\Model;

class BusModelo extends Model
{
    protected $table    = 'bus_modelos';
    protected $fillable = ['bus_marca_id', 'nombre', 'estado'];

    protected $casts = ['estado' => 'boolean'];

    public function marca()
    {
        return $this->belongsTo(BusMarca::class, 'bus_marca_id');
    }

    public function vehiculos()
    {
        return $this->hasMany(BusVehiculo::class, 'id_modelo');
    }
}