<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'productos';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'imagen',
        'precio_compra',
        'stock_minimo',
        'stock_maximo',
        'unidad_id',
        'estado',
        'categoria_id'
    ];
// (Mantén tus relaciones Eloquent si las necesitas en otras partes)
    public function unidad() { return $this->belongsTo(Unidad::class); }
    public function categoria() { return $this->belongsTo(Categoria::class); }
    public function lotes() { return $this->hasMany(Lote::class); }
    public function movimientos() { return $this->hasMany(MovimientoInventario::class); }
    public function detalleCompras() { return $this->hasMany(DetalleCompra::class); }
    public function recetaIngredientes() { return $this->hasMany(RecetaIngrediente::class); }

    /**
     * Listar productos usando Query Builder pero devolviendo objetos
     * compatibles con la vista (categoria->nombre y unidad->nombre).
     *
     * @param string|null $buscar
     * @param int|string|null $activo
     * @param int $perPage
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function listarProductos($buscar = null, $activo = null, $perPage = 10)
    {
        $query = DB::table('productos')
            ->leftJoin('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->leftJoin('unidades', 'productos.unidad_id', '=', 'unidades.id')
            ->select(
                'productos.*',
                'categorias.nombre as categoria_nombre',
                'unidades.nombre as unidad_nombre'
            );

        if (!empty($buscar)) {
            $query->where(function ($q) use ($buscar) {
                $q->where('productos.codigo', 'like', "%{$buscar}%")
                  ->orWhere('productos.nombre', 'like', "%{$buscar}%");
            });
        }

        if ($activo !== null && $activo !== '') {
            // si tu campo estado es booleano en la BD, conviértelo a int
            $query->where('productos.estado', (int)$activo);
        }

        $paginador = $query->orderBy('productos.id', 'desc')->paginate($perPage);

        // Transformar la colección para añadir objetos "categoria" y "unidad"
        $paginador->getCollection()->transform(function ($item) {
            // $item es stdClass; añadimos propiedades con objetos para mantener compatibilidad con la vista
            $item->categoria = (object) ['nombre' => $item->categoria_nombre ?? null];
            $item->unidad = (object) ['nombre' => $item->unidad_nombre ?? null];

            // opcional: eliminar campos extra si quieres
            // unset($item->categoria_nombre, $item->unidad_nombre);

            return $item;
        });

        return $paginador;
    }

    /**
     * Datos para formularios create/edit
     */
    public static function getDatosFormulario()
    {
        return [
            'categorias' => DB::table('categorias')->select('id','nombre')->get(),
            'unidades'   => DB::table('unidades')->select('id','nombre')->get(),
        ];
    }

    /**
     * Crear producto (recibe array ya validado).
     * Devuelve el id insertado.
     */
    public static function crearProducto(array $data)
    {
        // Asegurar codigo (si no viene, lo generamos)
        if (empty($data['codigo'])) {
            $categoriaNombre = DB::table('categorias')->where('id', $data['categoria_id'])->value('nombre') ?? 'CAT';
            $data['codigo'] = self::generarCodigoProducto($categoriaNombre, $data['nombre']);
        } else {
            $data['codigo'] = strtoupper($data['codigo']);
        }

        $insert = [
            'categoria_id'  => $data['categoria_id'],
            'codigo'        => $data['codigo'],
            'nombre'        => $data['nombre'],
            'descripcion'   => $data['descripcion'] ?? null,
            'imagen'        => $data['imagen'] ?? 'imagenes/productos/default.png',
            'precio_compra' => $data['precio_compra'] ?? 0,
            'stock_minimo'  => $data['stock_minimo'] ?? 0,
            'stock_maximo'  => $data['stock_maximo'] ?? 0,
            'unidad_id'     => $data['unidad_id'] ?? null,
            'estado'        => isset($data['estado']) ? (int)$data['estado'] : 1,
            'created_at'    => now(),
            'updated_at'    => now(),
        ];

        return DB::table('productos')->insertGetId($insert);
    }

    /**
     * Obtener producto por id (con nombres relacionados si los necesitas)
     */
    public static function obtenerProducto($id)
    {
        return DB::table('productos')
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
    }

    /**
     * Actualizar producto por id (recibe array validado)
     */
    public static function actualizarProducto($id, array $data)
    {
        $update = [
            'categoria_id'  => $data['categoria_id'],
            'codigo'        => strtoupper($data['codigo'] ?? ''),
            'nombre'        => $data['nombre'],
            'descripcion'   => $data['descripcion'] ?? null,
            'imagen'        => $data['imagen'] ?? null,
            'precio_compra' => $data['precio_compra'] ?? 0,
            'stock_minimo'  => $data['stock_minimo'] ?? 0,
            'stock_maximo'  => $data['stock_maximo'] ?? 0,
            'unidad_id'     => $data['unidad_id'] ?? null,
            'estado'        => isset($data['estado']) ? (int)$data['estado'] : 1,
            'updated_at'    => now(),
        ];

        // Si el front no envía imagen (null) no la sobreescribimos
        if (empty($update['imagen'])) {
            unset($update['imagen']);
        }

        return DB::table('productos')->where('id', $id)->update($update);
    }

    /**
     * Eliminar producto (simple)
     */
    public static function eliminarProducto($id)
    {
        return DB::table('productos')->where('id', $id)->delete();
    }

    /**
     * Generar código legible: mezcla de categoría + producto + sufijo único
     * Ej: CAT-NOM-YYYYMMDD-XXX o similar.
     */
    protected static function generarCodigoProducto($categoriaNombre, $nombreProducto)
    {
        $cat = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $categoriaNombre), 0, 3) ?: 'CAT');
        $prod = strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $nombreProducto), 0, 3) ?: 'PRD');
        $date = now()->format('Ymd');

        // intentamos crear sufijo único (3 digitos) comprobando existencia
        $base = "{$cat}-{$prod}";
        $suf = 1;
        do {
            $codigo = "{$base}-" . str_pad($suf, 3, '0', STR_PAD_LEFT);
            $exists = DB::table('productos')->where('codigo', $codigo)->exists();
            $suf++;
        } while ($exists && $suf < 999);

        // Si por alguna razón colisiona hasta 999, agregamos uniqid
        if ($exists) {
            $codigo = "{$base}-" . strtoupper(substr(uniqid(), -4));
        }

        return $codigo;
    }
}
