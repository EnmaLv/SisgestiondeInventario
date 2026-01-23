<?php

namespace App\Models;

use App\Observers\SucursalObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use App\Traits\ConvierteAMayusculasNoEloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

#[ObservedBy(SucursalObserver::class)]
class Sucursal extends Model
{
    use ConvierteAMayusculasNoEloquent;
    use HasFactory;

    protected $table = 'sucursals';

    protected $fillable = [
        'nombre',
        'direccion',
        'telefono',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean'
    ];

    public function inventarioSucursalLotes()
    {
        return $this->hasMany(InventarioSucursalLote::class);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public static function listarSucursales($buscar = null, $activo = null)
    {
        $query = DB::table('sucursals')
            ->select('sucursals.*');

        if ($buscar) {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                    ->orWhere('direccion', 'like', "%{$buscar}%")
                    ->orWhere('telefono', 'like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('activo', (int)$activo);
        } else {
            $query->where('activo', 1);
        }
        return $query->orderBy('id', 'desc')->paginate(10);
    }

    public static function crearSucursal(array $data)
    {
        $helper = new self();

        $data = $helper->convertirCamposAMayusculas($data, ['nombre', 'direccion']);
        $sucursal =Sucursal::create([
            'nombre'     => $data['nombre'],
            'direccion'  => $data['direccion'],
            'telefono'   => $data['telefono'],
            'activo'     => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return $sucursal->id;
    }

    public static function obtenerSucursal($id)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->first();
    }

    public static function actualizarSucursal($id, array $data)
    {
        return DB::table('sucursals')
            ->where('id', $id)
            ->update([
                'nombre'     => $data['nombre'],
                'direccion'  => $data['direccion'],
                'telefono'   => $data['telefono'],
                'activo'     => 1,
                'updated_at' => now()
            ]);
    }

    public static function eliminarSucursal($id)
    {
        $sucursal = DB::table('sucursals')
            ->where('id', $id)
            ->update([
                'activo' => 0,
                'updated_at' => now()
            ]);
        //Aplicamos el estado a la sede
        DB::table('sede')
            ->where('id_sucursal', $id)
            ->update([
                'estatus' => 0,
                'updated_at' => now()
            ]);
        return $sucursal;
    }

    public static function activarSucursal($id)
    {
        $sucursal = DB::table('sucursals')
            ->where('id', $id)
            ->update([
                'activo' => 1,
                'updated_at' => now()
            ]);
        //Activamos tambien la sede
        DB::table('sede')
            ->where('id_sucursal', $id)
            ->update([
                'estatus' => 1,
                'updated_at' => now()
            ]);
        return $sucursal;
    }

    public static function obtenerSucursalConInventario($id)
    {
        $sucursal = DB::table('sucursals')
            ->where('id', $id)
            ->first();

        if ($sucursal) {
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

    public static function tieneInventario($id)
    {
        return DB::table('inventario_sucursal_lotes')
            ->where('sucursal_id', $id)
            ->where('cantidad', '>', 0)
            ->exists();
    }

    public static function tieneMovimientos($id)
    {
        return DB::table('movimiento_inventarios')
            ->where('sucursal_id', $id)
            ->exists();
    }

    public static function obtenerSucursalesActivas()
    {
        return DB::table('sucursals')
            ->where('activo', 1)
            ->select('id', 'nombre', 'direccion', 'telefono')
            ->orderBy('nombre', 'asc')
            ->get();
    }

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

    public static function exportarCSV($buscar = null, $activo = null)
    {
        $query = DB::table('sucursals')
            ->select('id', 'nombre', 'direccion', 'telefono', 'activo');

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
