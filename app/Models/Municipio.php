<?php

namespace App\Models;

use App\Traits\ConvierteAMayusculas;
use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    use ConvierteAMayusculas;

    protected $table = 'municipios';
    protected $fillable = [
        'nombre_municipio',
        'estado_id',
        'status',
    ];

    protected $mayusculas = [
        'nombre_municipio'
    ];

    public function estado(){
    return $this->belongsTo(Estado::class, "estado_id", "id");
    }

    public function localidades(){
        return $this->hasMany(Localidad::class, "municipio_id", "id");
    }

}
