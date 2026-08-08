<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use App\Models\PrecioProducto;
use App\Models\salud\EnvasePrimario;
use App\Traits\ConvierteAMayusculasNoEloquent;

class Producto extends Model
{
    use ConvierteAMayusculasNoEloquent;
    use HasFactory;
    use WithPagination;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'imagen',
        'precio_compra',
        'stock_minimo',
        'stock_maximo',
        'peso_contenido',
        'unidad_id',
        'presentacion_id',
        'estado',
        'categoria_id',
        'envase_primario_id',
        'requiere_receta_medica'
    ];

    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function envasePrimario()
    {
        return $this->belongsTo(EnvasePrimario::class);
    }

    public function precioProducto()
    {
        return $this->hasOne(PrecioProducto::class)->latestOfMany();
    }

    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }

    public function inventarioSedeAcarigua()
    {
        return $this->hasManyThrough(
            InventarioSedeLote::class,
            Lote::class,
            'producto_id',
            'lote_id',
            'id',
            'id'
        )->where('inventario_sede_lotes.sede_id', 1);
    }

    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    public function recetaIngredientes()
    {
        return $this->hasMany(RecetaIngrediente::class);
    }

    public static function listarProductos($buscar = null, $activo = 1, $categoria = null, $perPage = 10, $cantidadMin = null, $cantidadMax = null, $tipoProductoId = 2)
    {
        $query = self::with(['categoria', 'unidad'])
            ->select('productos.*')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->where('categorias.tipo_producto_id', $tipoProductoId)
            ->withSum([
                'inventarioSedeAcarigua as cantidad_actual' => function ($query) {}
            ], 'cantidad');

        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('productos.codigo', 'like', "%{$buscar}%")
                    ->orWhere('productos.nombre', 'like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            $query->where('productos.estado', (int)$activo);
        } else {
            $query->where('productos.estado', 1);
        }

        if ($cantidadMin !== null) {
            $query->having('cantidad_actual', '>=', $cantidadMin);
        }
        if ($cantidadMax !== null) {
            $query->having('cantidad_actual', '<=', $cantidadMax);
        }

        if ($categoria !== null && $categoria !== '') {
            $query->where('productos.categoria_id', $categoria);
        }

        return $query->orderByDesc('cantidad_actual')->paginate($perPage)->withQueryString();
    }

    public function getCantidadEnSede($sedeId)
    {
        return DB::table('inventario_sede_lotes')
            ->join('lotes', 'lotes.id', '=', 'inventario_sede_lotes.lote_id')
            ->where('lotes.producto_id', $this->id)
            ->where('inventario_sede_lotes.sede_id', $sedeId)
            ->sum('inventario_sede_lotes.cantidad');
    }

    public static function getDatosFormulario(?int $tipoProductoId = null)
    {
        $query = DB::table('categorias')->select('id', 'nombre')->where('activo', 1);

        if ($tipoProductoId !== null) {
            $query->where('tipo_producto_id', $tipoProductoId);
        }
        return [
            'categorias' =>  $query->get(),
            'unidades'   => DB::table('unidades')->select('id', 'nombre', 'abreviatura')->get(),
            'envases'   => DB::table('envase_primarios')->select('id', 'nombre')->get(),
        ];
    }

    public static function crearProducto(array $data)
    {
        $helper = new self();

        $data = $helper->convertirCamposAMayusculas($data, ['nombre', 'descripcion']);
        return DB::transaction(function () use ($data) {

            if (empty($data['codigo'])) {
                $categoriaNombre = DB::table('categorias')
                    ->where('id', $data['categoria_id'])
                    ->value('nombre') ?? 'CAT';

                $data['codigo'] = self::generarCodigoProducto(
                    $categoriaNombre,
                    $data['nombre']
                );
            } else {
                $data['codigo'] = strtoupper($data['codigo']);
            }
            $unidad = DB::table('unidades')
                ->where('id', $data['unidad_id'])
                ->first();

            $pesoBase = $data['peso_contenido'] * ($unidad->factor_a_base ?? 1);

            $productoId = DB::table('productos')->insertGetId([
                'categoria_id'  => $data['categoria_id'],
                'codigo'        => $data['codigo'],
                'nombre'        => $data['nombre'],
                'descripcion'   => $data['descripcion'] ?? null,
                'imagen'        => $data['imagen'] ?? 'imagenes/productos/product-defect.webp',
                'precio_compra' => 0,
                'stock_minimo'  => $data['stock_minimo'] ?? 0,
                'stock_maximo'  => $data['stock_maximo'] ?? 0,
                'peso_contenido' => $pesoBase,
                'unidad_id'     => $data['unidad_id'] ?? null,
                'presentacion_id' => $data['envase_primario_id'] ?? null,
                'estado'        => isset($data['estado']) ? (int)$data['estado'] : 1,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);

            PrecioProducto::create([
                'producto_id' => $productoId,
                'costo_usd'  => $data['costo_usd'],
                'margen'      => $data['margen'] ?? 0
            ]);

            return $productoId;
        });
    }

    public static function obtenerProducto($id)
    {
        $producto = DB::table('productos')
            ->select(
                'productos.*',
                'categorias.nombre as categoria_nombre',
                'unidades.nombre as unidad_nombre',
                'unidades.abreviatura as unidad_abreviatura'
            )
            ->leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->leftJoin('unidades', 'productos.unidad_id', '=', 'unidades.id')
            ->where('productos.id', $id)
            ->first();

        if (!$producto) return null;

        $producto->categoria = (object)[
            'nombre' => $producto->categoria_nombre
        ];

        $producto->unidad = (object)[
            'nombre'       => $producto->unidad_nombre,
            'abreviatura'  => $producto->unidad_abreviatura
        ];

        return $producto;
    }

    public static function actualizarProducto($id, array $data)
    {
        $helper = new self();

        $data = $helper->convertirCamposAMayusculas($data, ['nombre', 'descripcion']);

        // Manejo seguro de unidad
        $unidadId = $data['unidad_id'] ?? null;
        $unidad = $unidadId ? DB::table('unidades')->where('id', $unidadId)->first() : null;

        $pesoContenido = $data['peso_contenido'] ?? 0;
        $pesoBase = $pesoContenido * ($unidad->factor_a_base ?? 1);

        // Capturamos costo_usd o precio_compra (según lo que venga en el $data)
        $precioUsd = $data['costo_usd'] ?? $data['precio_compra'] ?? 0;

        $update = [
            'categoria_id'    => $data['categoria_id'],
            'nombre'          => $data['nombre'],
            'descripcion'     => $data['descripcion'] ?? null,
            'precio_compra'   => $precioUsd,
            'stock_minimo'    => $data['stock_minimo'] ?? 0,
            'stock_maximo'    => $data['stock_maximo'] ?? 0,
            'peso_contenido'   => $pesoBase,
            'unidad_id'       => $unidadId,
            'presentacion_id' => $data['envase_primario_id'] ?? null, // <-- AQUÍ: Guardamos envase_primario_id en la columna real 'presentacion_id'
            'estado'          => isset($data['estado']) ? (int)$data['estado'] : 1,
            'updated_at'      => now(),
        ];

        // Si viene un nuevo código en $data, lo actualizamos; si no, dejamos el que ya tiene
        if (!empty($data['codigo'])) {
            $update['codigo'] = strtoupper($data['codigo']);
        }

        // Si se subió una nueva imagen
        if (!empty($data['imagen'])) {
            $update['imagen'] = $data['imagen'];
        }

        // Manejo del historial de Precios
        $ultimoPrecio = PrecioProducto::where('producto_id', $id)->latest()->first();
        $margenRequest = $data['margen'] ?? 0;

        if (!$ultimoPrecio || $ultimoPrecio->costo_usd != $precioUsd || $ultimoPrecio->margen != $margenRequest) {
            PrecioProducto::create([
                'producto_id' => $id,
                'costo_usd'   => $precioUsd,
                'margen'      => $margenRequest,
            ]);
        }

        return DB::table('productos')->where('id', $id)->update($update);
    }

    public static function eliminarProducto($id)
    {
        return DB::table('productos')
            ->where('id', $id)
            ->update([
                'estado' => 0,
                'updated_at' => now()
            ]);
    }

    public static function activarProducto($id)
    {
        return DB::table('productos')
            ->where('id', $id)
            ->update([
                'estado' => 1,
                'updated_at' => now()
            ]);
    }

    protected static function generarCodigoProducto($categoriaNombre, $nombreProducto)
    {
        $cat = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $categoriaNombre), 0, 3) ?: 'CAT');
        $prod = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $nombreProducto), 0, 3) ?: 'PRD');
        $date = now()->format('Ymd');
        $base = "{$cat}-{$prod}";
        $suf = 1;
        do {
            $codigo = "{$base}-" . str_pad($suf, 3, '0', STR_PAD_LEFT);
            $exists = DB::table('productos')->where('codigo', $codigo)->exists();
            $suf++;
        } while ($exists && $suf < 999);

        if ($exists) {
            $codigo = "{$base}-" . strtoupper(substr(uniqid(), -4));
        }

        return $codigo;
    }

    public static function tieneInventarioEnAcarigua($productoId)
    {
        return DB::table('inventario_sede_lotes as isl')
            ->join('lotes as l', 'l.id', '=', 'isl.lote_id')
            ->where('l.producto_id', $productoId)
            ->where('isl.sede_id', 1)
            ->where('isl.cantidad', '>', 0)
            ->exists();
    }

    public static function cantidadEnAcarigua($productoId)
    {
        return DB::table('inventario_sede_lotes as isl')
            ->join('lotes as l', 'l.id', '=', 'isl.lote_id')
            ->where('l.producto_id', $productoId)
            ->where('isl.sede_id', 1)
            ->sum('isl.cantidad');
    }
}
