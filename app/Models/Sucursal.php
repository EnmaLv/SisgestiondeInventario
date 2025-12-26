<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

// Modelo para gestionar las sucursales del sistema
class Sucursal extends Model
{
    use HasFactory;

    // Nombre de la tabla en la base de datos
    protected $table = 'sucursals';

    // Campos que pueden ser llenados masivamente
    protected $fillable = [
        'nombre',     // Nombre de la sucursal
        'direccion',  // Dirección física
        'telefono',   // Número de teléfono
        'activo',     // Estado de la sucursal (activo/inactivo)
    ];

    // Conversión de tipos de datos
    protected $casts = [
        'activo' => 'boolean'  // Convierte el campo activo a booleano
    ];

    // Relación con InventarioSucursalLote
    public function inventarioSucursalLotes()
    {
        return $this->hasMany(InventarioSucursalLote::class);
    }

    // Relación con MovimientoInventario
    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    // ========== MÉTODOS ESTÁTICOS CON QUERY BUILDER ==========

    /**
     * Obtiene un listado paginado de sucursales con opción de búsqueda y filtro por estado
     */
    public static function listarSucursales($buscar = null, $activo = null)
    {
        $query = DB::table('sucursals')
            ->select('sucursals.*');

        // Aplicar filtro de búsqueda si se proporciona
        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('direccion', 'like', "%{$buscar}%")
                    ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        // Filtrar por estado activo/inactivo si se especifica
        if ($activo !== null && $activo !== '') {
            $query->where('activo', (int)$activo);
        } else {
            // Por defecto, mostrar solo activos
            $query->where('activo', 1);
        }

        // Ordenar por ID y paginar los resultados (10 por página)
        return $query->orderBy('id', 'desc')->paginate(10);
    }

    /**
     * Crea una nueva sucursal en la base de datos
     */
    public static function crearSucursal(array $data)
    {
        return DB::table('sucursals')->insertGetId([
            'nombre'     => $data['nombre'],        // Nombre de la sucursal
            'direccion'  => $data['direccion'],     // Dirección completa
            'telefono'   => $data['telefono'],      // Número de contacto
            'activo'     => 1,        // Estado activo/inactivo
            'created_at' => now(),                 // Fecha de creación
            'updated_at' => now()                  // Fecha de actualización
        ]);
    }

    /**
     * Obtiene una sucursal por su ID
     */
    public static function obtenerSucursal($id)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->first();
    }

    /**
     * Actualiza los datos de una sucursal existente
     */
    public static function actualizarSucursal($id, array $data)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->update([
                'nombre'     => $data['nombre'],        // Nuevo nombre
                'direccion'  => $data['direccion'],     // Nueva dirección
                'telefono'   => $data['telefono'],      // Nuevo teléfono
                'activo'     => 1,        // Nuevo estado
                'updated_at' => now()                  // Actualizar fecha de modificación
            ]);
    }

    /**
     * Elimina una sucursal de la base de datos
     * Nota: Se debe verificar antes que no tenga inventario ni movimientos
     */
    public static function eliminarSucursal($id)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->update([
                'activo' => 0,
                'updated_at' => now()
            ]);
    }

    /**
     * Cambia el estado de una sucursal
     */
    public static function activarSucursal($id)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->update([
                'activo' => 1,
                'updated_at' => now()
            ]);
    }

    /**
     * Obtiene los datos básicos de una sucursal con su inventario
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
     * Verifica si una sucursal tiene productos en inventario
     * Devuelve true si hay al menos un producto con cantidad mayor a 0
     */
    public static function tieneInventario($id)
    {
        return DB::table('inventario_sucursal_lotes')
            ->where('sucursal_id', $id)
            ->where('cantidad', '>', 0)
            ->exists();
    }

    /**
     * Verifica si una sucursal tiene registros de movimientos de inventario
     * Útil para prevenir la eliminación de sucursales con historial
     */
    public static function tieneMovimientos($id)
    {
        return DB::table('movimiento_inventarios')
            ->where('sucursal_id', $id)
            ->exists();
    }

    /**
     * Obtiene todas las sucursales activas para selects
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
     * Obtiene estadísticas de la sucursal
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
     * Genera una colección de sucursales para exportar a CSV
     * Acepta filtros de búsqueda y estado
     */
    public static function exportarCSV($buscar = null, $activo = null)
    {
        $query = DB::table('sucursals')
            ->select('id', 'nombre', 'direccion', 'telefono', 'activo');

        // Aplicar filtros si se proporcionan
        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
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
