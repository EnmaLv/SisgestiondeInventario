<?php

namespace App\Observers;

use App\Models\Sucursal;
use Illuminate\Support\Facades\DB;

class SucursalObserver
{
    public function created(Sucursal $sucursal)
    {
        //Cuando se cree una sucursal se debe crear una sede
        DB::table('sede')->insert([
            'nombre_sede' => $sucursal->nombre,
            'id_sucursal' => $sucursal->id,
            'estatus' => 1,
        ]);
    }

    public function updated(Sucursal $sucursal)
    {
        //Cuando se actualize una sucursal se debe actualizar una sede
        DB::table('sede')->where('id_sucursal', $sucursal->id)->update([
            'nombre_sede' => $sucursal->nombre
        ]);
    }
}
