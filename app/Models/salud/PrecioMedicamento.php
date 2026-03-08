<?php

namespace App\Models\salud;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PrecioMedicamento extends Model
{
    use HasFactory;

    protected $table = 'precio_medicamentos';

    protected $fillable = [
        'medicamento_id',
        'costo_usd',
        'margen',
        'precio_usd',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class);
    }
}
