<?php

namespace App\Models\salud;

use App\Models\Unidad;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Medicamento extends Model
{
    protected $table = 'medicamentos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'imagen',
        'precio_compra',
        'stock_minimo',
        'stock_maximo',
        'peso_contenido',
        'unidad_id',
        'envase_primario_id',
        'categoria_medicamento_id',
        'estado',
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function envasePrimario()
    {
        return $this->belongsTo(EnvasePrimario::class);
    }

    public function categoriaMedicamento()
    {
        return $this->belongsTo(CategoriaMedicamento::class);
    }

    public static function listar($buscar = null, $estado = null)
    {
        $query = DB::table('medicamentos')
            ->select('medicamentos.*');

        if ($buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        if ($estado !== null && $estado !== '') {
            $query->where('estado', (int)$estado);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }
}
