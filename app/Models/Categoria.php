<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Modelo que representa una categoría de productos en el sistema de inventario.
 * 
 * Este modelo maneja todas las operaciones relacionadas con las categorías,
 * incluyendo la relación con los productos y consultas comunes.
 */
class Categoria extends Model
{
    use HasFactory;

    /**
     * Nombre de la tabla asociada al modelo.
     *
     * @var string
     */
    protected $table = 'categorias';

    /**
     * Atributos que son asignables masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',       // Nombre de la categoría
        'descripcion',  // Descripción detallada de la categoría
        'estado',       // Estado de la categoría (activo/inactivo)
    ];

    /**
     * Obtiene los productos asociados a esta categoría.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

    // ========== MÉTODOS ESTÁTICOS CON QUERY BUILDER ==========

    /**
     * Obtiene una lista paginada de categorías con opciones de filtrado.
     *
     * @param  string|null  $buscar  Término de búsqueda para filtrar por nombre
     * @param  int|string|null  $activo  Filtro por estado (1 = activo, 0 = inactivo)
     * @return \Illuminate\Pagination\LengthAwarePaginator
     */
    public static function listarCategorias($buscar = null, $activo = null)
    {
        $query = DB::table('categorias')
            ->select('categorias.*');

        if ($buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        if ($activo !== null && $activo !== '') {
            $query->where('estado', (int)$activo);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    /**
     * Crea una nueva categoría en la base de datos.
     *
     * @param  array  $data  Datos de la categoría a crear
     * @return int  ID de la categoría recién creada
     */
    public static function crearCategoria(array $data)
    {
        return DB::table('categorias')->insertGetId([
            'nombre'      => $data['nombre'],
            'descripcion' => $data['descripcion'],
            'estado'      => true,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
    }

    /**
     * Obtiene una categoría por su ID.
     *
     * @param  int  $id  ID de la categoría a buscar
     * @return object|null  Objeto con los datos de la categoría o null si no se encuentra
     */
    public static function obtenerCategoria($id)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->first();
    }

    /**
     * Actualiza los datos de una categoría existente.
     *
     * @param  int  $id  ID de la categoría a actualizar
     * @param  array  $data  Nuevos datos para la categoría
     * @return int  Número de registros actualizados (0 o 1)
     */
    public static function actualizarCategoria($id, array $data)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->update([
                'nombre'      => $data['nombre'],
                'descripcion' => $data['descripcion'],
                'updated_at'  => now(),
            ]);
    }

    /**
     * Elimina una categoría de la base de datos.
     *
     * @param  int  $id  ID de la categoría a eliminar
     * @return int  Número de registros eliminados (0 o 1)
     * @throws \Exception Si la categoría no puede ser eliminada
     */
    public static function eliminarCategoria($id)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->delete();
    }

    /**
     * Cambia el estado de una categoría (activo/inactivo).
     *
     * @param  int  $id  ID de la categoría
     * @param  bool  $estado  Nuevo estado (true = activo, false = inactivo)
     * @return int  Número de registros actualizados (0 o 1)
     */
    public static function cambiarEstado($id, $estado)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->update([
                'estado'     => $estado,
                'updated_at' => now(),
            ]);
    }

    /**
     * Obtiene una categoría junto con todos sus productos activos.
     *
     * @param  int  $id  ID de la categoría
     * @return object|null  Objeto con los datos de la categoría y sus productos, o null si no se encuentra
     */
    public static function obtenerCategoriaConProductos($id)
    {
        $categoria = DB::table('categorias')
            ->where('id', $id)
            ->first();

        if ($categoria) {
            $categoria->productos = DB::table('productos')
                ->where('categoria_id', $id)
                ->where('estado', 1)
                ->select('id', 'codigo', 'nombre', 'precio_compra', 'estado')
                ->get();

            $categoria->cantidad_productos = $categoria->productos->count();
        }

        return $categoria;
    }

    /**
     * Verifica si una categoría tiene productos asociados.
     *
     * @param  int  $id  ID de la categoría
     * @return bool  true si tiene productos, false en caso contrario
     */
    public static function tieneProductos($id)
    {
        return DB::table('productos')
            ->where('categoria_id', $id)
            ->exists();
    }

    /**
     * Cuenta la cantidad de productos asociados a una categoría.
     *
     * @param  int  $id  ID de la categoría
     * @return int  Número de productos en la categoría
     */
    public static function contarProductos($id)
    {
        return DB::table('productos')
            ->where('categoria_id', $id)
            ->count();
    }

    /**
     * Obtiene todas las categorías activas para usar en menús desplegables.
     *
     * @return \Illuminate\Support\Collection  Colección de categorías activas con ID y nombre
     */
    public static function obtenerCategoriasActivas()
    {
        return DB::table('categorias')
            ->where('estado', 1)
            ->select('id', 'nombre')
            ->orderBy('nombre', 'asc')
            ->get();
    }
}
