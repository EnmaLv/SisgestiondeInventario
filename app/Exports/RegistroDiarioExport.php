<?php

namespace App\Exports;

use App\Models\Registro_diario;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;




class RegistroDiarioExport implements FromCollection, WithHeadings, ShouldAutoSize, WithMapping, WithCustomStartCell
{

    private $filtro;

    public function __construct(array $filtro)
    {
        $this->filtro = $filtro;
    }

    public function map($registro): array
    {
        return [
            $registro->id,
            $registro->cedula_persona,
            $registro->nombre_persona,
            $registro->apellido_persona,
            $registro->nombre_pnf,
            $registro->fecha_regis_diario_c,
            $registro->nombre_estado ?? 'N/A',
            $registro->nombre_municipio ?? 'N/A',
            $registro->nombre_localidad ?? 'N/A',
            $registro->sector ?? 'N/A',
            $registro->calle ?? 'N/A',
        ]; 
    }

    //Modifica el inicio de la tabla
    public function startCell(): string
    {
        return 'A1';
    }

    public function headings(): array
    {
        return [
            'ID',
            'Cedula',
            'Nombre',
            'Apellido',
            'PNF',
            'Fecha Registro',
            'Estado',
            'Municipio',
            'Localidad',
            'Sector',
            'Calle',
        ];
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Registro_diario::showData($this->filtro, true);
    }
}
