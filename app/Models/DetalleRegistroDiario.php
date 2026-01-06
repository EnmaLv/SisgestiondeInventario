<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleRegistroDiario extends Model
{
    use HasFactory;
    
    protected $table = 'detalle_registro_diarios';

    protected $fillable = [
        'receta_id',
        'cantidad_servido',
        'fecha',
    ];

    public function receta()
    {
        return $this->belongsTo(Receta::class, 'receta_id');
    }
}
