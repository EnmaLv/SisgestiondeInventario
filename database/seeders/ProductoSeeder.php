<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('productos')->insert([
            ['categoria_id' => 7, 'codigo' => 'CER-HAR-001', 'nombre' => 'Harina', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 1, 'codigo' => 'EMB-MOR-002', 'nombre' => 'Mortadela', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 9, 'codigo' => 'CAR-AZU-003', 'nombre' => 'Azucar', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 2, 'codigo' => 'FRU-PUL-004', 'nombre' => 'Pulpa de Mora', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 10, 'codigo' => 'GRA-MAN-005', 'nombre' => 'Mantequilla 1k', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 10, 'codigo' => 'GRA-MAN-005-1', 'nombre' => 'Mantequilla 500g', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 500, 'unidad_id' => 1,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 5, 'codigo' => 'CAR-CAR-006', 'nombre' => 'Carne Molida', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 3, 'codigo' => 'VER-PAP-007', 'nombre' => 'Papa', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 3, 'codigo' => 'VER-ZAN-008', 'nombre' => 'Zanahoria', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 3, 'codigo' => 'VER-ALI-009', 'nombre' => 'Aliños Verdes', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 3, 'codigo' => 'VER-CEB-010', 'nombre' => 'Cebolla', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 2, 'codigo' => 'FRU-TOM-011', 'nombre' => 'Tomate', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 3, 'codigo' => 'VER-AJO-012', 'nombre' => 'Ajo', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 1, 'codigo' => 'EMB-SAL-013', 'nombre' => 'Salchicha', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 5, 'codigo' => 'CAR-POL-014', 'nombre' => 'Pollo', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 6, 'codigo' => 'LAC-QUE-015', 'nombre' => 'Queso Blanco', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 6, 'codigo' => 'LAC-QUE-015-1', 'nombre' => 'Queso Amarillo', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 100, 'peso_contenido' => 300, 'unidad_id' => 1,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 5, 'codigo' => 'CAR-RES-016', 'nombre' => 'Carne de Res', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 80, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 5, 'codigo' => 'CAR-CER-017', 'nombre' => 'Carne de Cerdo', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 80, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 2, 'codigo' => 'FRU-MAN-018', 'nombre' => 'Manzana', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 10, 'stock_maximo' => 150, 'peso_contenido' => 1000, 'unidad_id' => 2, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 2, 'codigo' => 'FRU-BAN-019', 'nombre' => 'Cambur', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 10, 'stock_maximo' => 150, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 3, 'codigo' => 'VER-LEC-020', 'nombre' => 'Lechuga', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 60, 'peso_contenido' => 500, 'unidad_id' => 1,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 3, 'codigo' => 'VER-PIM-021', 'nombre' => 'Pimentón', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 60, 'peso_contenido' => 500, 'unidad_id' => 1,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 7, 'codigo' => 'CER-ARR-022', 'nombre' => 'Arroz', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 10, 'stock_maximo' => 200, 'peso_contenido' => 1000, 'unidad_id' => 2,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 9, 'codigo' => 'CAR-PAST-023', 'nombre' => 'Pasta', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 10, 'stock_maximo' => 200, 'peso_contenido' => 500, 'unidad_id' => 1,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 6, 'codigo' => 'LAC-LEC-024', 'nombre' => 'Leche', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 10, 'stock_maximo' => 120, 'peso_contenido' => 1000, 'unidad_id' => 3,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 6, 'codigo' => 'LAC-YOG-025', 'nombre' => 'Yogurt', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 10, 'stock_maximo' => 80, 'peso_contenido' => 500, 'unidad_id' => 1, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 11, 'codigo' => 'BEB-AGU-026', 'nombre' => 'Agua Mineral', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 20, 'stock_maximo' => 300, 'peso_contenido' => 1500, 'unidad_id' => 3,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 11, 'codigo' => 'BEB-REF-027', 'nombre' => 'Refresco', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 20, 'stock_maximo' => 300, 'peso_contenido' => 2000, 'unidad_id' => 3,'presentacion_id' => null, 'estado' => 1],
            ['categoria_id' => 13, 'codigo' => 'CON-PIM-028', 'nombre' => 'Pimienta', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 40, 'peso_contenido' => 100, 'unidad_id' => 1, 'presentacion_id' => null,'estado' => 1],
            ['categoria_id' => 13, 'codigo' => 'CON-COM-029', 'nombre' => 'Comino', 'descripcion' => null, 'imagen' => null, 'stock_minimo' => 5, 'stock_maximo' => 40, 'peso_contenido' => 100, 'unidad_id' => 1,'presentacion_id' => null, 'estado' => 1],

        ]);
    }
}
