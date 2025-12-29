<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    /** @use HasFactory<\Database\Factories\LocalidadFactory> */
    use HasFactory;

    protected $table = 'localidads';
    
    protected $fillable = [
        'estado_id',
        'municipio_id',
        'nombre_localidad',
        'status',
    ];

    public function municipio(){
    return $this->belongsTo(Municipio::class, "municipio_id", "id");
    }

    public function estado(){
        return $this->belongsTo(Estado::class, "estado_id", "id");
    }


   
    
}
