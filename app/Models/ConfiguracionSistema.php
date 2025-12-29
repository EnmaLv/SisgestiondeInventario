<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ConfiguracionSistema extends Model
{
    protected $table = 'configuracion_sistema';
    protected $fillable = ['clave_parametro', 'valor_parametro'];

    public static function getParametro(string $clave)
    {
        return self::where('clave_parametro', $clave)->first();
    }

    public static function checkMasterKey(string $candidate): bool
    {
        $row = self::getParametro('master_key');
        if (!$row) return false;
        return Hash::check($candidate, $row->valor_parametro);
    }

    public static function updateMasterKey(string $newKey): void
    {
        $hash = Hash::make($newKey);
        $row = self::updateOrCreate(['clave_parametro' => 'master_key'], ['valor_parametro' => $hash]);
    }
}
