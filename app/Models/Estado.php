<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';
    protected $fillable = [
        'nombre_estado',
        'status',
    ];

    public function municipio(){
        return $this->hasMany(Municipio::class,"estado_id","id");
    }
}