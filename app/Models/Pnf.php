<?php

namespace App\Models;

use App\Traits\ConvierteAMayusculas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pnf extends Model
{
    use ConvierteAMayusculas;

    protected $table = 'pnf';
    protected $id = 'id_pnf';
    protected $fillable = [
        'id_pnf',
        'nombre_pnf',
        'id_estatus'
    ];

    protected $mayusculas = [
        'nombre_pnf'
    ];

    public function personaPnf()
    {
        return $this->hasMany(PersonaPnf::class, 'id_pnf');
    }

    public static function getPnfs(Request $request)
    {
        $paginacion = 10;

        if ($request->activo) {
            return Pnf::where('id_estatus', $request->activo)->paginate($paginacion);
        }

        if ($request->buscar) {
            return Pnf::where('nombre_pnf', 'LIKE', "%$request->buscar%")->paginate($paginacion);
        }
        return Pnf::paginate($paginacion);
    }

    public static function createPnf(Request $request, array $data = [])
    {
        $pnf = Pnf::where('nombre_pnf', $request->nombre)->first();
        if ($pnf) {
            return redirect()->route('admin.maestros.pnf.index')->with('error', 'El PNF ya existe. Intente con otro nombre.');
        }

        return Pnf::create([
            'nombre_pnf' => $request->nombre,
            'id_estatus' => 1,
        ]);
    }

    public static function updatePnf(Request $request, array $data = [])
    {
        $pnf = Pnf::where('nombre_pnf', $request->nombre)->first();

        if ($pnf && $pnf->id_pnf != $request->id && $pnf->nombre_pnf == $request->nombre) {
            return redirect()->route('admin.maestros.pnf.edit', $pnf->id_pnf)->with('error', 'El PNF ya existe. Intente con otro nombre.');
        }

        return Pnf::where('id_pnf', $request->id)->update([
            'nombre_pnf' => strtoupper($request->nombre),
            'id_estatus' => $request->id_estatus,
        ]);
    }

    public static function activarPnf($id)
    {
        $pnf = Pnf::where('id_pnf', $id)->first();

        if (!$pnf) {
            return false;
        }
        $nuevoEstado = $pnf->id_estatus == 1 ? 2 : 1;
        return Pnf::where('id_pnf', $id)->update([
            'id_estatus' => $nuevoEstado
        ]);
    }
}
