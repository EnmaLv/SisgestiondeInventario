<?php

namespace App\Models;

use App\Traits\ConvierteAMayusculas;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    use ConvierteAMayusculas;
    use HasFactory;

    protected $table = 'localidads';
    
    protected $fillable = [
        'estado_id',
        'municipio_id',
        'nombre_localidad',
        'status',
    ];

    protected $mayusculas = [
        'nombre_localidad'
    ];

    public function municipio(){
    return $this->belongsTo(Municipio::class, "municipio_id", "id");
    }

    public function estado(){
        return $this->belongsTo(Estado::class, "estado_id", "id");
    }
    
}
