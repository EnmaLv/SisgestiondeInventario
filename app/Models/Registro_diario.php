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

    public function receta_ingrediente()
    {
        return $this->belongsTo(RecetaIngrediente::class);
    }

    public function persona()
    {
        return $this->belongsTo(Persona::class);
    }


    public function persona_pnf()
    {
        return $this->belongsTo(PersonaPnf::class);
    }

    private static function relacionTable()
    {
        return DB::table('registro_diario_c')
            ->join('persona', 'registro_diario_c.id_persona', '=', 'persona.id_persona')
            ->join('persona_pnf', 'registro_diario_c.id_persona_pnf', '=', 'persona_pnf.id_persona_pnf')
            ->join('pnf', 'persona_pnf.id_pnf', '=', 'pnf.id_pnf');
    }

    public static function getRegister(int $id)
    {

        function formatColumn(array $column, string $prefix)
        {
            // Esta función podría ser usada para formatear los nombres de las columnas con un prefijo
            return array_map(function ($col) use ($prefix) {
                return $prefix . '.' . $col;
            }, $column);
        }
        //Creamos una variable para almacenar la columnas que necesitamos

        $personaColumn = formatColumn([
            'nombre_persona',
            'segundo_nombre_persona',
            'apellido_persona',
            'segundo_apellido_persona',
            'cedula_persona',
            'telefono_persona',
            'genero_persona',
            'edad_persona',
            'fecha_nacimiento_persona',
            'email_persona',
        ], 'persona');

        $pnfColumn = formatColumn([
            'nombre_pnf',
        ], 'pnf');

        $registroColumn = formatColumn([
            'fecha_regis_diario_c',
            'hora'
        ], 'registro_diario_c');


        //Lo unimos en un solo array
        $colums = [...$personaColumn, ...$pnfColumn, ...$registroColumn];
        $query = self::relacionTable()->select($colums)->where('registro_diario_c.id', $id);

        return $query->first();
    }

    public static function showData(array $filter = [], bool $isPdf = false)
    {
        $query = self::relacionTable()
            ->select('registro_diario_c.*', 'persona.nombre_persona', 'persona.apellido_persona', 'persona.cedula_persona', 'pnf.nombre_pnf');


        //Por si hay que buscar por el input
        if (isset($filter['buscar']) && $filter['buscar']) {
            $query->where('persona.nombre_persona', 'like', '%' . $filter['buscar'] . '%')
                ->orWhere('persona.apellido_persona', 'like', '%' . $filter['buscar'] . '%')
                ->orWhere('persona.cedula_persona', 'like', '%' . $filter['buscar'] . '%')
                ->orWhere('pnf.nombre_pnf', 'like', "%{$filter['buscar']}%");
        }

        //Por si hay que buscar entre 2 fechas
        if (isset($filter['fecha_desde']) && isset($filter['fecha_hasta'])) {
            $query->whereBetween('registro_diario_c.fecha_regis_diario_c', [$filter['fecha_desde'], $filter['fecha_hasta']]);
        }

        //Por si hay que buscar por fecha
        if (isset($filter['fecha_desde'])) {
            $query->where('registro_diario_c.fecha_regis_diario_c', '>=', $filter['fecha_desde']);
        }

        if (isset($filter['fecha_hasta'])) {
            $query->where('registro_diario_c.fecha_regis_diario_c', '<=', $filter['fecha_hasta']);
        }

        //En el caso de que los datos lo necesitos para generar un pdf
        if ($isPdf) {
            return $query->get();
        }


        return $query->paginate(10);
    }

    public static function getAllByPnf()
    {
        //Relacionamos las tablas necesarias
        $query = self::relacionTable()
            // Obtenemos el nombre del pnf y contamos los registros por pnf
            ->select('pnf.nombre_pnf', DB::raw('COUNT(*) as total_registros'))
            ->groupBy('pnf.nombre_pnf')
            ->orderBy('total_registros', 'desc');

        return $query->get();
    }
}
