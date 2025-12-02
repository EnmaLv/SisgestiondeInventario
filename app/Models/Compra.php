<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo que representa una compra en el sistema de inventario.
 * 
 * Este modelo maneja todas las operaciones relacionadas con las compras a proveedores,
 * incluyendo la gestión de detalles de compra, lotes y actualización de inventario.
 */
class Compra extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'compras';

    /**
     * Atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'proveedor_id',    // ID del proveedor asociado a la compra
        'fecha',           // Fecha en que se realizó la compra
        'total',           // Monto total de la compra
        'estado',          // Estado actual de la compra (Pendiente, Finalizada, etc.)
        'observaciones',   // Notas adicionales sobre la compra
    ];

    /**
     * Atributos que deberían ser convertidos a tipos nativos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene el proveedor asociado a la compra.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    /**
     * Obtiene los detalles de la compra.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    /**
     * Obtiene los datos necesarios para los formularios de compra.
     * 
     * Este método devuelve un array con listas de proveedores, productos y sucursales
     * que se utilizan en los formularios de creación y edición de compras.
     *
     * @return array Array asociativo con listas de proveedores, productos y sucursales
     */
    public function getDatosFormulario()
    {
        return [
            'proveedores' => DB::table('proveedors')
                ->select('id', 'nombre', 'email')
                ->where('estado', 1) // Solo proveedores activos
                ->orderBy('nombre')
                ->get(),

            'productos' => DB::table('productos')
                ->select('id', 'codigo', 'nombre')
                ->where('estado', 1) // Solo productos activos
                ->orderBy('nombre')
                ->get(),

            'sucursales' => DB::table('sucursals')
                ->select('id', 'nombre')
                ->where('activo', 1) // Solo sucursales activas
                ->orderBy('nombre')
                ->get(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE CONSULTA
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene un listado paginado de compras con opciones de búsqueda y filtrado.
     *
     * @param  string|null  $buscar  Término para buscar en empresa de proveedor o ID de compra
     * @param  int|string|null  $activo  Filtro por estado (1 = activo, 0 = inactivo, null = todos)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function listarCompras($buscar, $activo)
    {
        // Construir la consulta base con join a proveedores
        $query = DB::table('compras')
            ->join('proveedors', 'proveedors.id', '=', 'compras.proveedor_id')
            ->select(
                'compras.*',
                'proveedors.empresa as proveedor_empresa',
                'proveedors.id as proveedor_id'
            );

        // Aplicar filtro de búsqueda si se proporciona
        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('proveedors.empresa', 'like', "%{$buscar}%")
                    ->orWhere('compras.id', 'like', "%{$buscar}%");
            });
        }

        // Aplicar filtro de estado si se proporciona
        if ($activo !== null && $activo !== '') {
            $query->where('compras.estado', (int) $activo);
        }

        // Ordenar por ID de forma descendente y paginar
        return $query->orderBy('compras.id', 'desc')->paginate(10);
    }



    /**
     * Crea una nueva compra en el sistema.
     * 
     * Inicializa una compra con estado 'Pendiente' y total en 0.
     * El total se actualizará cuando se agreguen los detalles de la compra.
     *
     * @param  array  $data  Datos de la compra a crear
     * @return int  ID de la compra recién creada
     */
    public static function crearCompra($data)
    {
        return DB::table('compras')->insertGetId([
            'proveedor_id'  => $data['proveedor_id'],
            'fecha'         => $data['fecha'],
            'observaciones' => $data['observaciones'] ?? null,
            'total'         => 0, // Se actualizará al agregar detalles
            'estado'        => 'Pendiente',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    /**
     * Obtiene los detalles de una compra específica con información relacionada.
     *
     * Incluye información del producto, lote y unidad de medida para cada detalle.
     *
     * @param  int  $compra_id  ID de la compra
     * @return \Illuminate\Support\Collection  Colección de detalles de la compra
     */
    public static function obtenerDetallesCompra($compra_id)
    {
        return DB::table('detalle_compras')
            ->join('productos', 'productos.id', '=', 'detalle_compras.producto_id')
            ->join('lotes', 'lotes.id', '=', 'detalle_compras.lote_id')
            ->join('unidades', 'unidades.id', '=', 'detalle_compras.unidad_id')
            ->where('detalle_compras.compra_id', $compra_id)
            ->select(
                'detalle_compras.*',
                'productos.nombre as producto_nombre',
                'productos.codigo as producto_codigo',
                'lotes.codigo_lote',
                'lotes.fecha_vencimiento',
                'unidades.nombre as unidad_nombre',
                'unidades.abreviatura as unidad_abreviatura'
            )
            ->orderBy('detalle_compras.id')
            ->get();
    }

    /**
     * Obtiene la sucursal destino de una compra a través de sus movimientos de inventario.
     *
     * @param  int  $compra_id  ID de la compra
     * @return object|null  Objeto con id y nombre de la sucursal, o null si no se encuentra
     */
    public static function obtenerSucursalDestino($compra_id)
    {
        return DB::table('movimiento_inventarios')
            ->join('sucursals', 'sucursals.id', '=', 'movimiento_inventarios.sucursal_id')
            ->join('detalle_compras', 'detalle_compras.lote_id', '=', 'movimiento_inventarios.lote_id')
            ->where('detalle_compras.compra_id', $compra_id)
            ->select('sucursals.id', 'sucursals.nombre')
            ->first();
    }

    /**
     * Obtiene una compra específica con información del proveedor.
     *
     * @param  int  $id  ID de la compra a buscar
     * @return object|null  Objeto con los datos de la compra o null si no se encuentra
     */
    public static function obtenerCompra($id)
    {
        return DB::table('compras')
            ->join('proveedors', 'proveedors.id', '=', 'compras.proveedor_id')
            ->select(
                'compras.*',
                'proveedors.nombre AS proveedor_nombre',
                'proveedors.empresa AS proveedor_empresa',
                'proveedors.id AS proveedor_id',
                'proveedors.email AS proveedor_email',
                'proveedors.telefono AS proveedor_telefono'
            )
            ->where('compras.id', $id)
            ->first();
    }


    /**
     * Elimina una compra y todos sus registros relacionados de manera segura.
     * 
     * Este método elimina:
     * 1. Los registros de lotes asociados a los detalles de la compra
     * 2. Los detalles de la compra
     * 3. Finalmente, el registro de la compra
     *
     * @param  int  $id  ID de la compra a eliminar
     * @return bool  true si la eliminación fue exitosa, false en caso de error
     * @throws \Exception Si ocurre un error durante la transacción
     */
    public static function eliminarCompra($id)
    {
        DB::beginTransaction();

        try {
            // Obtener todos los detalles de la compra
            $detalles = DB::table('detalle_compras')
                ->where('compra_id', $id)
                ->get();

            // Eliminar lotes y detalles
            foreach ($detalles as $detalle) {
                // Primero verificar si el lote no está siendo usado en otro lugar
                $usoLote = DB::table('inventario_sucursal_lotes')
                    ->where('lote_id', $detalle->lote_id)
                    ->exists();

                if ($usoLote) {
                    throw new \Exception('No se puede eliminar la compra porque uno de sus lotes está siendo utilizado en el inventario.');
                }

                // Eliminar el lote
                DB::table('lotes')->where('id', $detalle->lote_id)->delete();

                // Eliminar el detalle de compra
                DB::table('detalle_compras')->where('id', $detalle->id)->delete();
            }

            // Finalmente, eliminar la compra
            DB::table('compras')->where('id', $id)->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error al eliminar la compra: ' . $e->getMessage());
            return false;
        }
    }

    // FINALIZAR COMPRA (inventario y movimientos)
    public static function finalizarCompra($compra, $sucursal_id)
    {
        DB::beginTransaction();
        try {
            $detalles = DB::table('detalle_compras')
                ->where('compra_id', $compra->id)
                ->get();

            foreach ($detalles as $detalle) {

                // Inventario por sucursal
                $inventario = DB::table('inventario_sucursal_lotes')
                    ->where('lote_id', $detalle->lote_id)
                    ->where('sucursal_id', $sucursal_id)
                    ->first();

                if ($inventario) {
                    DB::table('inventario_sucursal_lotes')
                        ->where('id', $inventario->id)
                        ->update([
                            'cantidad' => $inventario->cantidad + $detalle->cantidad
                        ]);
                } else {
                    DB::table('inventario_sucursal_lotes')->insert([
                        'lote_id'      => $detalle->lote_id,
                        'sucursal_id'  => $sucursal_id,
                        'cantidad'     => $detalle->cantidad
                    ]);
                }

                // CREAR MOVIMIENTO
                DB::table('movimiento_inventarios')->insert([
                    'producto_id'     => $detalle->producto_id,
                    'lote_id'         => $detalle->lote_id,
                    'sucursal_id'     => $sucursal_id,
                    'tipo_movimiento' => 'ENTRADA',
                    'unidad_id'       => $detalle->unidad_id,
                    'cantidad'        => $detalle->cantidad,
                    'fecha'           => now(),
                ]);
            }

            // ACTUALIZAR COMPRA
            DB::table('compras')
                ->where('id', $compra->id)
                ->update([
                    'estado'     => 'Finalizada',
                    'updated_at' => now()
                ]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
