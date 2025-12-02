<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Modelo que representa un producto en el sistema de inventario.
 * 
 * Este modelo maneja todas las operaciones relacionadas con los productos,
 * incluyendo relaciones con categorías, unidades, lotes y movimientos de inventario.
 */
class Producto extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'productos';

    /**
     * Atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo',          // Código único del producto
        'nombre',          // Nombre del producto
        'descripcion',     // Descripción detallada
        'imagen',          // Ruta de la imagen del producto
        'precio_compra',   // Precio de compra del producto
        'stock_minimo',    // Nivel mínimo de inventario
        'stock_maximo',    // Nivel máximo de inventario
        'unidad_id',       // ID de la unidad de medida
        'estado',          // Estado del producto (activo/inactivo)
        'categoria_id'     // ID de la categoría a la que pertenece
    ];
    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene la unidad de medida del producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unidad()
    {
        return $this->belongsTo(Unidad::class);
    }

    /**
     * Obtiene la categoría a la que pertenece el producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }

    /**
     * Obtiene los lotes asociados al producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lotes()
    {
        return $this->hasMany(Lote::class);
    }

    /**
     * Obtiene los movimientos de inventario del producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoInventario::class);
    }

    /**
     * Obtiene los detalles de compra del producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function detalleCompras()
    {
        return $this->hasMany(DetalleCompra::class);
    }

    /**
     * Obtiene los ingredientes de receta que usan este producto.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recetaIngredientes()
    {
        return $this->hasMany(RecetaIngrediente::class);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS DE CONSULTA
    |--------------------------------------------------------------------------
    */

    /**
     * Obtiene una lista paginada de productos con información relacionada.
     *
     * Este método realiza una consulta optimizada utilizando Query Builder y devuelve
     * los resultados formateados para ser compatibles con las vistas existentes.
     *
     */
    public static function listarProductos($buscar = null, $activo = 1, $perPage = 10)
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

        // Aplicar filtro de estado si se especifica
        if ($activo !== null && $activo !== '') {
            $query->where('productos.estado', (int)$activo);
        } else {
            // Por defecto, mostrar solo activos
            $query->where('productos.estado', 1);
        }

        $paginador = $query->orderBy('productos.id', 'desc')->paginate($perPage);

        // Transformar la colección para añadir objetos "categoria" y "unidad"
        $paginador->getCollection()->transform(function ($item) {
            $item->categoria = (object) ['nombre' => $item->categoria_nombre ?? null];
            $item->unidad = (object) ['nombre' => $item->unidad_nombre ?? null];
            return $item;
        });

        return $paginador;
    }

    /**
     * Obtiene los datos necesarios para los formularios de creación y edición.
     *
     */
    public static function getDatosFormulario()
    {
        return [
            'categorias' => DB::table('categorias')->select('id', 'nombre')->get(),
            'unidades'   => DB::table('unidades')->select('id', 'nombre')->get(),
        ];
    }

    /**
     * Crea un nuevo producto en la base de datos.
     *
     * Si no se proporciona un código, se genera automáticamente basado en la categoría y nombre.
     *
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
     * Obtiene un producto por su ID con información relacionada.
     *
     * Incluye datos de la categoría y unidad de medida asociadas.
     *
     */
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

        // Crear objetos para mantener compatibilidad con las vistas
        $producto->categoria = (object)[
            'nombre' => $producto->categoria_nombre
        ];

        $producto->unidad = (object)[
            'nombre'       => $producto->unidad_nombre,
            'abreviatura'  => $producto->unidad_abreviatura
        ];

        return $producto;
    }


    /**
     * Actualiza los datos de un producto existente.
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
     * Elimina un producto de la base de datos.
     *
     * Nota: Asegúrate de manejar las restricciones de clave foránea.
     */
    public static function eliminarProducto($id)
    {
        return DB::table('productos')
            ->where('id', $id)
            ->update([
                'estado' => 0,
                'updated_at' => now()
            ]);
    }

    /**
     * Genera un código único para el producto basado en su categoría y nombre.
     *
     * El formato generado es: PRF-CAT-NOM-XXXXXX
     * Donde:
     * - PRF: Prefijo 'PRD'
     * - CAT: Primeras 3 letras de la categoría en mayúsculas
     * - NOM: Primeras 3 letras del nombre en mayúsculas
     * - XXXXXX: 6 caracteres aleatorios alfanuméricos
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
