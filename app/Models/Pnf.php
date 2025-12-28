<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Pnf extends Model
{
    protected $table = 'pnf';
    protected $id = 'id_pnf';
    protected $fillable = [
        'id_pnf',
        'nombre_pnf',
        'id_estatus'
    ];




    public function personaPnf()
    {
        return $this->hasMany(PersonaPnf::class, 'id_pnf');
    }

    public static function getPnfs(Request $request)
    {
        $paginacion = 10;

        if($request->activo){
            return Pnf::where('id_estatus', $request->activo)->paginate($paginacion);
        }
        
        if($request->buscar){
            return Pnf::where('nombre_pnf', 'LIKE', "%$request->buscar%")->paginate($paginacion);
        }
        return Pnf::paginate($paginacion);   
    }

    /**
     * Funcion para crear un pnf
     * @param Request $request
     * @return Pnf|\Illuminate\Http\RedirectResponse
     */
    public static function createPnf(Request $request)
    {
        //Verifica que no haya un pnf repetido
        $pnf = Pnf::where('nombre_pnf', $request->nombre)->first();
        if ($pnf) {
            return redirect()->route('admin.maestros.pnf.create')->with('error', 'El PNF ya existe. Intente con otro nombre.');
        }

        return Pnf::create([
            'nombre_pnf' => $request->nombre,
            'id_estatus' => $request->id_estatus,
        ]);
    }

    /**
     * Funcion que actualiza un pnf
     * @param Request $request
     * @return bool|\Illuminate\Http\RedirectResponse
     */
    public static function updatePnf(Request $request)
    {
        //Verificamos que que no vaya a guardar un pnf repetido 
        $pnf = Pnf::where('nombre_pnf', $request->nombre)->first();
        if ($pnf) {
            return redirect()->route('admin.maestros.pnf.edit', $pnf->id_pnf)->with('error', 'El PNF ya existe. Intente con otro nombre.');
        }

        return Pnf::where('id_pnf', $request->id)->update([
            'nombre_pnf' => $request->nombre,
            'id_estatus' => $request->id_estatus,
        ]);
    }


    /** Funcion para activar o desactivar un pnf
     * /
     * @param int $id
     * @return bool
     */
    public static function activarPnf($id){

        //Verficamos que estus tiene para hacer diferentes operaciones
        $pnf = Pnf::where('id_pnf', $id)->first();
        
        if (!$pnf) {
            return false; // O lanzar una excepción si prefieres
        }
        // Alternar entre activo (1) e inactivo (2)
        $nuevoEstado = $pnf->id_estatus == 1 ? 2 : 1;
        return Pnf::where('id_pnf', $id)->update([
            'id_estatus' => $nuevoEstado
        ]);
    }


}
