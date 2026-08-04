<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoriaMedicamentoSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [

            'ANALGÉSICOS',
            'ANTIINFLAMATORIOS',
            'ANTIBIÓTICOS',
            'ANTIVIRALES',
            'ANTIFÚNGICOS',
            'ANTIPARASITARIOS',
            'ANTIALÉRGICOS',
            'ANTIHISTAMÍNICOS',
            'ANTIPIRÉTICOS',
            'ANTIHIPERTENSIVOS',
            'ANTIDIABÉTICOS',
            'ANTIDEPRESIVOS',
            'ANSIOLÍTICOS',
            'ANTICONVULSIVOS',
            'ANTICOAGULANTES',
            'ANTIAGREGANTES PLAQUETARIOS',
            'BRONCODILATADORES',
            'ANTIÁCIDOS',
            'PROTECTORES GÁSTRICOS',
            'LAXANTES',
            'ANTIDIARREICOS',
            'ANTIEMÉTICOS',
            'DIURÉTICOS',
            'CORTICOESTEROIDES',
            'INMUNOSUPRESORES',
            'INMUNOESTIMULANTES',
            'HORMONAS',
            'ANTICONCEPTIVOS',
            'VITAMINAS',
            'SUPLEMENTOS MINERALES',
            'VACUNAS',
            'ANTISÉPTICOS',
            'DESINFECTANTES',
            'ANESTÉSICOS',
            'SEDANTES',
            'RELANTES MUSCULARES',
            'DERMATOLÓGICOS',
            'OFTÁLMICOS',
            'OTOLÓGICOS',
            'NASALES',
            'RESPIRATORIOS',
            'CARDIOVASCULARES',
            'GASTROINTESTINALES',
            'ENDOCRINOLÓGICOS',
            'NEUROLÓGICOS',
            'ONCOLÓGICOS',
            'HEMATOLÓGICOS',
            'UROLÓGICOS',
            'GINECOLÓGICOS',
            'PEDIÁTRICOS',
            'MEDICAMENTOS BIOLÓGICOS'

        ];

        foreach ($categorias as $categoria) {
            DB::table('categorias')->insert([
                'nombre' => $categoria,
                'descripcion' => null,
                'tipo_producto_id' => 2,
                'activo' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}