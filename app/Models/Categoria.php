<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categoria extends Model  // ← Con mayúscula
{
    use HasFactory;

    protected $table = 'categorias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'estado',
    ];

    public function productos()
    {
        return $this->hasMany(Producto::class, 'categoria_id');
    }

    // ========== MÉTODOS ESTÁTICOS CON QUERY BUILDER ==========

    /**
     * Listar categorías con filtros
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
     * Crear una nueva categoría
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
     * Obtener una categoría por ID
     */
    public static function obtenerCategoria($id)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->first();
    }

    /**
     * Actualizar una categoría
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
     * Eliminar una categoría
     */
    public static function eliminarCategoria($id)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->delete();
    }

    /**
     * Cambiar estado de la categoría
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
     * Obtener categoría con sus productos
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
     * Verificar si la categoría tiene productos
     */
    public static function tieneProductos($id)
    {
        return DB::table('productos')
            ->where('categoria_id', $id)
            ->exists();
    }

    /**
     * Contar productos por categoría
     */
    public static function contarProductos($id)
    {
        return DB::table('productos')
            ->where('categoria_id', $id)
            ->count();
    }

    /**
     * Obtener todas las categorías activas (para selects)
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