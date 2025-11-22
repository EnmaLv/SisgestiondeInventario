<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Registro_diario extends Model
{
    protected $table = 'registro_diario_c';

    protected $fillable = [
        'id_persona',
        'id_persona_pnf',
        'fecha_regis_diario_c',
        'hora',
    ];

    public $timestamps = false;


    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }


    public function persona_pnf()
    {
        return $this->belongsTo(PersonaPnf::class);
    }

    private static function relacionTable(){
        return DB::table('registro_diario_c')
            ->join('persona', 'registro_diario_c.id_persona', '=', 'persona.id_persona')
            ->join('persona_pnf', 'registro_diario_c.id_persona_pnf', '=', 'persona_pnf.id_persona_pnf')
            ->join('pnf', 'persona_pnf.id_pnf', '=', 'pnf.id_pnf');
    }

    public static function showData(Array $filter = [], bool $isPdf = false)
    {
        $query = self::relacionTable()
            ->select('registro_diario_c.*', 'persona.nombre_persona', 'persona.apellido_persona', 'pnf.nombre_pnf');


        //Por si hay que buscar por el input
        if(isset($filter['buscar']) && $filter['buscar']){
            $query->where('persona.nombre_persona', 'like', '%' . $filter['buscar'] . '%')
            ->orWhere('persona.apellido_persona', 'like', '%' . $filter['buscar'] . '%')
            ->orWhere('pnf.nombre_pnf', 'like', "%{$filter['buscar']}%");
        }

        //Por si hay que buscar entre 2 fechas
        if(isset($filter['fecha_desde']) && isset($filter['fecha_hasta'])){
            $query->whereBetween('registro_diario_c.fecha_regis_diario_c', [$filter['fecha_desde'], $filter['fecha_hasta']]);
        }

        //Por si hay que buscar por fecha
        if(isset($filter['fecha_desde'])){
            $query->where('registro_diario_c.fecha_regis_diario_c', '>=', $filter['fecha_desde']);
        }

        if(isset($filter['fecha_hasta'])){
            $query->where('registro_diario_c.fecha_regis_diario_c', '<=', $filter['fecha_hasta']);
        }

        //En el caso de que los datos lo necesitos para generar un pdf
        if($isPdf){
            return $query->get();
        }


        return $query->paginate(10);
    }
}
