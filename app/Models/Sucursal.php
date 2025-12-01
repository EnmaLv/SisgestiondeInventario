<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Sucursal extends Model
{
    use HasFactory;

    protected $table = 'sucursals';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'activo',
    ];

    public function inventarioSucursalLotes()
    {
        return $this->hasMany(InventarioSucursalLote::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    // ========== MÉTODOS ESTÁTICOS CON QUERY BUILDER ==========

    /**
     * Listar sucursales con filtros
     */
    public static function listarSucursales($buscar = null, $activo = null)
    {
        $query = DB::table('sucursals')
            ->select('sucursals.*');

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('direccion', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('activo', (int)$activo);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    /**
     * Crear una nueva sucursal
     */
    public static function crearSucursal(array $data)
    {
        return DB::table('sucursals')->insertGetId([
            'nombre'     => $data['nombre'],
            'direccion'  => $data['direccion'],
            'telefono'   => $data['telefono'],
            'activo'     => $data['activo'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Obtener una sucursal por ID
     */
    public static function obtenerSucursal($id)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->first();
    }

    /**
     * Actualizar una sucursal
     */
    public static function actualizarSucursal($id, array $data)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->update([
                'nombre'     => $data['nombre'],
                'direccion'  => $data['direccion'],
                'telefono'   => $data['telefono'],
                'activo'     => $data['activo'],
                'updated_at' => now(),
            ]);
    }

    /**
     * Eliminar una sucursal
     */
    public static function eliminarSucursal($id)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->delete();
    }

    /**
     * Cambiar estado de la sucursal
     */
    public static function cambiarEstado($id, $activo)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->update([
                'activo'     => $activo,
                'updated_at' => now(),
            ]);
    }

    /**
     * Obtener sucursal con inventario
     */
    public static function obtenerSucursalConInventario($id)
    {
        $sucursal = DB::table('sucursals')
            ->where('id', $id)
            ->first();

        if ($sucursal) {
            // Obtener inventario de la sucursal con información de productos
            $sucursal->inventario = DB::table('inventario_sucursal_lotes as isl')
                ->join('lotes', 'lotes.id', '=', 'isl.lote_id')
                ->join('productos', 'productos.id', '=', 'lotes.producto_id')
                ->where('isl.sucursal_id', $id)
                ->where('isl.cantidad', '>', 0)
                ->select(
                    'productos.nombre as producto_nombre',
                    'productos.codigo as producto_codigo',
                    'lotes.codigo_lote',
                    'lotes.fecha_vencimiento',
                    'isl.cantidad'
                )
                ->get();

            $sucursal->total_productos = $sucursal->inventario->count();
            $sucursal->cantidad_total = $sucursal->inventario->sum('cantidad');
        }

        return $sucursal;
    }

    /**
     * Verificar si la sucursal tiene inventario
     */
    public static function tieneInventario($id)
    {
        return DB::table('inventario_sucursal_lotes')
            ->where('sucursal_id', $id)
            ->where('cantidad', '>', 0)
            ->exists();
    }

    /**
     * Verificar si la sucursal tiene movimientos
     */
    public static function tieneMovimientos($id)
    {
        return DB::table('movimiento_inventarios')
            ->where('sucursal_id', $id)
            ->exists();
    }

    /**
     * Obtener todas las sucursales activas (para selects)
     */
    public static function obtenerSucursalesActivas()
    {
        return DB::table('sucursals')
            ->where('activo', 1)
            ->select('id', 'nombre', 'direccion', 'telefono')
            ->orderBy('nombre', 'asc')
            ->get();
    }

    /**
     * Obtener estadísticas de la sucursal
     */
    public static function obtenerEstadisticas($id)
    {
        return [
            'total_productos' => DB::table('inventario_sucursal_lotes as isl')
                ->join('lotes', 'lotes.id', '=', 'isl.lote_id')
                ->where('isl.sucursal_id', $id)
                ->where('isl.cantidad', '>', 0)
                ->distinct('lotes.producto_id')
                ->count('lotes.producto_id'),
            
            'total_lotes' => DB::table('inventario_sucursal_lotes')
                ->where('sucursal_id', $id)
                ->where('cantidad', '>', 0)
                ->count(),
            
            'movimientos_mes' => DB::table('movimiento_inventarios')
                ->where('sucursal_id', $id)
                ->whereMonth('fecha', date('m'))
                ->whereYear('fecha', date('Y'))
                ->count(),
        ];
    }

    /**
     * Exportar sucursales a CSV
     */
    public static function exportarCSV($buscar = null, $activo = null)
    {
        $query = DB::table('sucursals')
            ->select('id', 'nombre', 'direccion', 'telefono', 'activo');

        if ($buscar) {
            $query->where(function($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('direccion', 'like', "%{$buscar}%")
                  ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('activo', (int)$activo);
        }

        return $query->orderBy('id', 'desc')->get();
    }
}