<?php

namespace App\Models\Becas;

use Illuminate\Database\Eloquent\Model;

class BecaPregunta extends Model
{
    protected $table = 'be_beca_preguntas';

    protected $fillable = [
        'beca_id',
        'texto',
        'tipo',
        'min',
        'max',
    ];

    protected $casts = [
        'min' => 'decimal:2',
        'max' => 'decimal:2',
    ];

    public function beca()
    {
        return $this->belongsTo(Beca::class, 'beca_id');
    }
}
