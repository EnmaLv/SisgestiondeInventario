<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BusMarca;

class BusModelo extends Model
{
    protected $table = 'bus_modelos';
    
    protected $fillable = [
        'bus_marca_id',
        'nombre',
        'archivo',
        'estado',
    ];
    
    public function busMarca()
    {
        return $this->belongsTo(BusMarca::class, 'bus_marca_id');
    }
}
