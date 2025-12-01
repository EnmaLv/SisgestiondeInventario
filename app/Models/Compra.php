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
            'proveedores' => DB::table('proveedors')->select('id','nombre','email')->get(),
            'productos'   => DB::table('productos')->select('id','codigo','nombre')->get(),
            'sucursales'  => DB::table('sucursals')->select('id','nombre')->get(),
        ];
    }


    // LISTAR COMPRAS CON FILTROS
    public static function listarCompras($buscar, $activo)
    {
        $query = DB::table('compras');

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('codigo', 'like', "%{$buscar}%")
                  ->orWhere('nombre', 'like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int) $activo);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    // CREAR COMPRA
    public static function crearCompra($data)
    {
        return DB::table('compras')->insertGetId([
            'proveedor_id'  => $data['proveedor_id'],
            'fecha'         => $data['fecha'],
            'observaciones' => $data['observaciones'],
            'total'         => 0,
            'estado'        => 'Pendiente',
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);
    }

    // OBTENER COMPRA (con detalles)
    public static function obtenerCompra($id)
    {
        return DB::table('compras')
            ->where('id', $id)
            ->first();
    }

    // ELIMINAR COMPRA CON SUS DETALLES Y LOTES
    public static function eliminarCompra($id)
    {
        DB::beginTransaction();

        try {
            $detalles = DB::table('detalle_compras')
                ->where('compra_id', $id)
                ->get();

            foreach ($detalles as $detalle) {
                DB::table('lotes')->where('id', $detalle->lote_id)->delete();
                DB::table('detalle_compras')->where('id', $detalle->id)->delete();
            }

            DB::table('compras')->where('id', $id)->delete();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
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
