<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categoria extends Model
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

    public static function listarCategorias($buscar = null, $estado = null)
    {
        $query = DB::table('categorias')
            ->select('categorias.*');

        if ($buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        if ($estado !== null && $estado !== '') {
            $query->where('estado', (int)$estado);
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

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

    public static function obtenerCategoria($id)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->first();
    }

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

    public static function eliminarCategoria($id)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->update([
                'estado' => 0,
                'updated_at' => now()
            ]);
    }

    public static function activarCategoria($id)
    {
        return DB::table('categorias')
            ->where('id', $id)
            ->update([
                'estado' => 1,
                'updated_at' => now()
            ]);
    }

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

    public static function tieneProductos($id)
    {
        return DB::table('productos')
            ->where('categoria_id', $id)
            ->exists();
    }

    public static function contarProductos($id)
    {
        return DB::table('productos')
            ->where('categoria_id', $id)
            ->count();
    }

    public static function obtenerCategoriasActivas()
    {
        return DB::table('categorias')
            ->where('estado', 1)
            ->select('id', 'nombre')
            ->orderBy('nombre', 'asc')
            ->get();
    }
}
