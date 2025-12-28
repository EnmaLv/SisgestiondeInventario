<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Compra extends Model
{
    protected $table = 'compras';

    protected $fillable = [
        'proveedor_id',    
        'fecha',           
        'total',           
        'estado',          
        'observaciones',   
    ];

    protected $casts = [
        'fecha' => 'date',
        'total' => 'decimal:2',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class);
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function getDatosFormulario()
    {
        return [
            'proveedores' => DB::table('proveedors')
                ->select('id', 'nombre', 'email')
                ->where('estado', 1) 
                ->orderBy('nombre')
                ->get(),

            'productos' => DB::table('productos')
                ->select('id', 'codigo', 'nombre')
                ->where('estado', 1) 
                ->orderBy('nombre')
                ->get(),

            'sucursales' => DB::table('sucursals')
                ->select('id', 'nombre')
                ->where('activo', 1) 
                ->orderBy('nombre')
                ->get(),
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE CONSULTA
    |--------------------------------------------------------------------------
    */

    public static function listarCompras($buscar, $estado)
    {
        // Construir la consulta base con join a proveedores
        $query = DB::table('compras')
            ->join('proveedors', 'proveedors.id', '=', 'compras.proveedor_id')
            ->select(
                'compras.*',
                'proveedors.empresa as proveedor_empresa',
                'proveedors.nombre as proveedor_nombre',
                'proveedors.id as proveedor_id'
            );

        $buscar = $query->where(function ($q) use ($buscar) {
            $q->where('proveedors.empresa', 'like', "%{$buscar}%")
                ->orWhere('compras.id', 'like', "%{$buscar}%");
        });

        // Aplicar filtro de estado si se proporciona
        if ($estado !== null && $estado !== '') {
            $query->where('compras.estado', (int) $estado);
        }

        // Ordenar por ID de forma descendente y paginar
        return $query->orderBy('compras.id', 'desc')->paginate(2);
    }

    public static function crearCompra($data)
    {
        return DB::table('compras')->insertGetId([
            'proveedor_id'  => $data['proveedor_id'],
            'fecha'         => $data['fecha'],
            'observaciones' => $data['observaciones'] ?? null,
            'total'         => 0, 
            'estado'        => 'Pendiente',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

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

    public static function obtenerSucursalDestino($compra_id)
    {
        return DB::table('movimiento_inventarios')
            ->join('sucursals', 'sucursals.id', '=', 'movimiento_inventarios.sucursal_id')
            ->join('detalle_compras', 'detalle_compras.lote_id', '=', 'movimiento_inventarios.lote_id')
            ->where('detalle_compras.compra_id', $compra_id)
            ->select('sucursals.id', 'sucursals.nombre')
            ->first();
    }

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

    public static function obtenerTodosDetallesCompras($buscar, $estado)
    {
        $query = DB::table('detalle_compras')
            ->join('productos', 'productos.id', '=', 'detalle_compras.producto_id')
            ->join('compras', 'compras.id', '=', 'detalle_compras.compra_id')
            ->join('proveedors', 'proveedors.id', '=', 'compras.proveedor_id')
            ->join('unidades', 'unidades.id', '=', 'detalle_compras.unidad_id')
            ->select(
                'detalle_compras.*',
                'compras.fecha',
                'proveedors.empresa as proveedor_empresa',
                'productos.nombre as producto_nombre',
                'unidades.nombre as unidad_nombre',
                'unidades.abreviatura as unidad_abreviatura'
            )
            ->orderBy('detalle_compras.id');

        if ($estado) {
            $query->where('detalle_compras.estado', $estado);
        }

        if ($buscar) {
            $query->where('detalle_compras.id', 'like', "%{$buscar}%");
        }

        return $query->get();
    }

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
                            'cantidad'         => $inventario->cantidad + $detalle->cantidad, // unidades
                            'cantidad_gramos'  => $inventario->cantidad_gramos + $detalle->cantidad_gramos // gramos
                        ]);
                } else {
                    DB::table('inventario_sucursal_lotes')->insert([
                        'lote_id'      => $detalle->lote_id,
                        'sucursal_id'  => $sucursal_id,
                        'cantidad'     => $detalle->cantidad,
                        'cantidad_gramos'     => $detalle->cantidad_gramos
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
                    'cantidad_gramos'        => $detalle->cantidad_gramos,
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

    public static function finalizarCompraDistribuida($compra)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Obtener sucursales activas (Acarigua primero)
            $sucursales = DB::table('sucursals')
                ->where('activo', 1)
                ->orderByRaw("nombre = 'Acarigua' DESC")
                ->pluck('id');

            if ($sucursales->isEmpty()) {
                throw new \Exception('No existen sucursales activas.');
            }

            $cantidadSucursales = $sucursales->count();

            // 2️⃣ Detalles de compra
            $detalles = DB::table('detalle_compras')
                ->where('compra_id', $compra->id)
                ->get();

            foreach ($detalles as $detalle) {

                // 🔢 Reparto de UNIDADES
                $cantidadBase = intdiv($detalle->cantidad, $cantidadSucursales);
                $resto = $detalle->cantidad % $cantidadSucursales;

                foreach ($sucursales as $index => $sucursal_id) {

                    $cantidadAsignada = $cantidadBase + ($index < $resto ? 1 : 0);

                    if ($cantidadAsignada <= 0) {
                        continue;
                    }

                    // ✅ GRAMOS FIJOS (1 unidad = 1000g)
                    $gramosAsignados = $cantidadAsignada * 1000;

                    // 🔍 Buscar inventario existente
                    $inventario = DB::table('inventario_sucursal_lotes')
                        ->where('lote_id', $detalle->lote_id)
                        ->where('sucursal_id', $sucursal_id)
                        ->first();

                    if ($inventario) {
                        DB::table('inventario_sucursal_lotes')
                            ->where('id', $inventario->id)
                            ->update([
                                'cantidad'        => $inventario->cantidad + $cantidadAsignada,
                                'cantidad_gramos' => $inventario->cantidad_gramos + $gramosAsignados,
                            ]);
                    } else {
                        DB::table('inventario_sucursal_lotes')->insert([
                            'lote_id'         => $detalle->lote_id,
                            'sucursal_id'     => $sucursal_id,
                            'cantidad'        => $cantidadAsignada,
                            'cantidad_gramos' => $gramosAsignados,
                        ]);
                    }

                    // 📦 Movimiento
                    DB::table('movimiento_inventarios')->insert([
                        'producto_id'     => $detalle->producto_id,
                        'lote_id'         => $detalle->lote_id,
                        'sucursal_id'     => $sucursal_id,
                        'tipo_movimiento' => 'ENTRADA',
                        'unidad_id'       => $detalle->unidad_id,
                        'cantidad'        => $cantidadAsignada,
                        'cantidad_gramos' => $gramosAsignados,
                        'fecha'           => now(),
                    ]);
                }
            }

            // 3️⃣ Finalizar compra
            DB::table('compras')
                ->where('id', $compra->id)
                ->update([
                    'estado' => 'Finalizada',
                    'updated_at' => now(),
                ]);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


}
