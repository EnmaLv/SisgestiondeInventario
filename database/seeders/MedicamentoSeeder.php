<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('productos')->insert([
            [
                'categoria_id'    => 22, 
                'codigo'          => 'ANT-ACE-001',
                'nombre'          => 'ACETAMINOFEN 500 MG',
                'descripcion'     => 'ANALGÉSICO Y ANTIPIRÉTICO UTILIZADO PARA ALIVIAR EL DOLOR Y REDUCIR LA FIEBRE.',
                'imagen'          => null,
                'stock_minimo'    => 15,
                'stock_maximo'    => 60,
                'peso_contenido'  => 10,
                'unidad_id'       => 1,
                'presentacion_id' => 1,
                'estado'          => 1
            ],
            // Loratadina 10 mg (Antihistamínico / Blíster / Gramos)
            [
                'categoria_id'    => 21, 
                'codigo'          => 'ANT-LOR-006',
                'nombre'          => 'LORATADINA 10 MG',
                'descripcion'     => 'ANTIHISTAMÍNICO UTILIZADO PARA ALIVIAR LOS SÍNTOMAS ALÉRGICOS.',
                'imagen'          => null,
                'stock_minimo'    => 10,
                'stock_maximo'    => 40,
                'peso_contenido'  => 8,
                'unidad_id'       => 1,  
                'presentacion_id' => 1, 
                'estado'          => 1
            ],
            // Paracetamol Jarabe 120 mg/5ml (Analgésico / Frasco / Mililitros)
            [
                'categoria_id'    => 14, 
                'codigo'          => 'ANA-PAR-007',
                'nombre'          => 'PARACETAMOL JARABE 120 MG/5ML',
                'descripcion'     => 'ANALGÉSICO Y ANTIPIRÉTICO UTILIZADO PARA ALIVIAR EL DOLOR Y REDUCIR LA FIEBRE, ESPECIALMENTE EN NIÑOS.',
                'imagen'          => null,
                'stock_minimo'    => 5,
                'stock_maximo'    => 30,
                'peso_contenido'  => 120,
                'unidad_id'       => 3,  
                'presentacion_id' => 2,  
                'estado'          => 1
            ],
            // Ibuprofeno 800 mg (Antiinflamatorio / Blíster / Gramos)
            [
                'categoria_id'    => 15, 
                'codigo'          => 'ANT-IBU-008',
                'nombre'          => 'IBUPROFENO 800 MG',
                'descripcion'     => 'ANTIINFLAMATORIO UTILIZADO PARA ALIVIAR EL DOLOR Y REDUCIR LA FIEBRE.',
                'imagen'          => null,
                'stock_minimo'    => 10,
                'stock_maximo'    => 50,
                'peso_contenido'  => 15,
                'unidad_id'       => 1, 
                'presentacion_id' => 1,  
                'estado'          => 1
            ],
            // Atamel 500 mg (Analgésico / Blíster / Gramos)
            [
                'categoria_id'    => 14, 
                'codigo'          => 'ANA-ATA-001',
                'nombre'          => 'ATAMEL 500 MG',
                'descripcion'     => 'ANALGÉSICO Y ANTIPIRÉTICO UTILIZADO PARA ALIVIAR EL DOLOR Y REDUCIR LA FIEBRE.',
                'imagen'          => null,
                'stock_minimo'    => 10,
                'stock_maximo'    => 50,
                'peso_contenido'  => 10,
                'unidad_id'       => 1, 
                'presentacion_id' => 1,  
                'estado'          => 1
            ],
            // Brugesic 400 mg (Antiinflamatorio / Blíster / Gramos)
            [
                'categoria_id'    => 15, 
                'codigo'          => 'ANT-BRU-002',
                'nombre'          => 'BRUGESIC 400 MG',
                'descripcion'     => 'ANTIINFLAMATORIO UTILIZADO PARA ALIVIAR EL DOLOR Y REDUCIR LA FIEBRE.',
                'imagen'          => null,
                'stock_minimo'    => 10,
                'stock_maximo'    => 50,
                'peso_contenido'  => 12,
                'unidad_id'       => 1,  
                'presentacion_id' => 1,  
                'estado'          => 1
            ]
        ]);
    }
}
