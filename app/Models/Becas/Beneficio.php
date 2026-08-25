<?php

namespace App\Models\Becas;

use Illuminate\Database\Eloquent\Model;

class Beneficio extends Model
{
    protected $table = "be_beneficios";
    
    protected $fillable = [
        'nombre_beneficio',
        'descripcion',
        'slug',
        'cupones_disponibles',
        'cupones_ocupados',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function becas()
    {
        return $this->belongsToMany(Beca::class, 'be_beca_beneficio', 'beneficio_id', 'beca_id')
            ->withPivot(['observacion', 'activo'])
            ->withTimestamps();
    }
}
