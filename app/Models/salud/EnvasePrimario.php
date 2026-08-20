<?php

namespace App\Models\salud;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;
use App\Traits\ConvierteAMayusculasNoEloquent;


class EnvasePrimario extends Model
{
    use HasFactory;
    use ConvierteAMayusculasNoEloquent;
    protected $table = 'envase_primarios';

    protected $fillable = [
        'nombre',
        'estado',
    ];
    

    public static function listar($buscar = null, $estado = null)
    {
        $query = DB::table('envase_primarios')
            ->select('envase_primarios.*');

        if ($buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        if ($estado !== null && $estado !== '') {
            $query->where('estado', (int)$estado);
        }

        return $query->orderBy('nombre', 'asc')->paginate(10);
    }

    public static function crear(array $data)
    {
        $helper = new self();

        $data = $helper->convertirCamposAMayusculas($data, ['nombre']);

        return DB::table('envase_primarios')->insertGetId([
            'nombre'      => $data['nombre'],
            'estado'      => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    public static function obtenerDatos($id)
    {
        return DB::table('envase_primarios')
            ->where('id', $id)
            ->first();
    }

    public static function actualizar($id, array $data)
    {
        $helper = new self();

        $data = $helper->convertirCamposAMayusculas($data, ['nombre']);

        return DB::table('envase_primarios')
            ->where('id', $id)
            ->update([
                'nombre'      => $data['nombre'],
                'updated_at'  => now(),
            ]);
    }

    public static function eliminar($id)
    {
        return DB::table('envase_primarios')
            ->where('id', $id)
            ->update([
                'estado' => 0,
                'updated_at' => now()
            ]);
    }

    public static function activar($id)
    {
        return DB::table('envase_primarios')
            ->where('id', $id)
            ->update([
                'estado' => 1,
                'updated_at' => now()
            ]);
    }
}
