<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedors';

    protected $fillable = [
        'empresa',
        'direccion',
        'nombre',
        'telefono',
        'email',
        'estado',
    ];

    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }

    public function compras()
    {
        return $this->hasMany(Compra::class);
    }

    public static function listarProveedores($buscar = null, $activo = null)
    {
        $query = DB::table('proveedors')
            ->select('proveedors.*');

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('empresa', 'like', "%{$buscar}%")
                    ->orWhere('nombre', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        } else {
            $query->where('estado', 1);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public static function crearProveedor(array $data)
    {
        return DB::table('proveedors')->insertGetId([
            'empresa'    => $data['empresa'],
            'direccion'  => $data['direccion'],
            'nombre'     => $data['nombre'],
            'telefono'   => $data['telefono'],
            'email'      => $data['email'],
            'estado'     => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public static function obtenerProveedor($id)
    {
        return DB::table('proveedors')
            ->where('id', $id)
            ->first();
    }

    public static function actualizarProveedor($id, array $data)
    {
        return DB::table('proveedors')
            ->where('id', $id)
            ->update([
                'empresa'    => $data['empresa'],
                'direccion'  => $data['direccion'],
                'nombre'     => $data['nombre'],
                'telefono'   => $data['telefono'],
                'email'      => $data['email'],
                'updated_at' => now(),
            ]);
    }

    public static function eliminarProveedor($id)
    {
        return DB::table('proveedors')
            ->where('id', $id)
            ->update([
                'estado' => 0,
                'updated_at' => now()
            ]);
    }

    public static function activarProveedor($id)
    {
        return DB::table('proveedors')
            ->where('id', $id)
            ->update([
                'estado' => 1,
                'updated_at' => now()
            ]);
    }

    public static function cambiarEstado($id, $estado)
    {
        return DB::table('proveedors')
            ->where('id', $id)
            ->update([
                'estado'     => $estado,
                'updated_at' => now(),
            ]);
    }

    public static function obtenerProveedorConCompras($id)
    {
        $proveedor = DB::table('proveedors')
            ->where('id', $id)
            ->first();

        if ($proveedor) {
            $proveedor->compras = DB::table('compras')
                ->where('proveedor_id', $id)
                ->orderBy('fecha', 'desc')
                ->get();
        }

        return $proveedor;
    }

    public static function tieneCompras($id)
    {
        return DB::table('compras')
            ->where('proveedor_id', $id)
            ->exists();
    }
}
