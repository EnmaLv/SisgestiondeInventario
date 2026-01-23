<?php

namespace App\Models;

use App\Traits\ConvierteAMayusculas;
use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    use ConvierteAMayusculas;

    protected $table = 'estados';
    protected $fillable = [
        'nombre_estado',
        'status',
    ];

    protected $mayusculas = [
        'nombre_estado'
    ];

    public function municipio(){
        return $this->hasMany(Municipio::class,"estado_id","id");
    }
}