<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EstadoAnimoDiario
{
    protected static $table = 'estado_animo_diarios';

    public static function create(array $data)
    {
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        
        return DB::table(self::$table)->insertGetId($data);
    }

    public static function getTodayForUser($userId)
    {
        return DB::table(self::$table)
            ->where('paciente_id', $userId)
            ->whereDate('fecha', Carbon::today())
            ->first();
    }
}
