<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';
    protected $fillable = [
        'nombre_municipio',
        'estado_id',
        'status',
    ];

    public function estado(){
    return $this->belongsTo(Estado::class, "estado_id", "id");
    }

    public function localidades(){
        return $this->hasMany(Localidad::class, "municipio_id", "id");
    }

}
