<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Archivo;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;
use App\Models\PersonaPnf;

class Archivos extends Component
{
    use WithFileUploads;

    public $archivo;
    public $archivoKey;
    public $buscar = '';

    protected $rules = [
        'archivo' => 'required|file|mimes:xlsx,xls,pdf,txt|max:10240',
    ];

    public function mount()
    {
        $this->archivoKey = rand();
    }

    private function normalizarPnf(string $pnfExcel): int
    {
        // Normalizar el texto
        $pnfExcel = strtoupper(trim($pnfExcel));
        $pnfExcel = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $pnfExcel);
        $pnfExcel = preg_replace('/\s+/', ' ', $pnfExcel);
        
        // Obtener todos los PNF de la BD
        $pnfs = DB::table('pnf')
            ->where('id_estatus', 1)
            ->get(['id_pnf', 'nombre_pnf']);
        
        $mejorCoincidencia = null;
        $mejorSimilitud = 0;
        
        foreach ($pnfs as $pnf) {
            $nombreBD = strtoupper($pnf->nombre_pnf);
            $nombreBD = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $nombreBD);
            
            // Calcular similitud
            similar_text($pnfExcel, $nombreBD, $porcentaje);
            
            if ($porcentaje > $mejorSimilitud) {
                $mejorSimilitud = $porcentaje;
                $mejorCoincidencia = $pnf->id_pnf;
            }
            
            // Si la similitud es del 90% o más, retornar inmediatamente
            if ($porcentaje >= 90) {
                return $pnf->id_pnf;
            }
        }
        
        // Si la mejor similitud es al menos 70%, usarla
        if ($mejorSimilitud >= 70) {
            return $mejorCoincidencia;
        }
        
        // Si no hay buena coincidencia, log para revisar
        logger()->warning('PNF no identificado', [
            'pnf_excel' => $pnfExcel,
            'mejor_similitud' => $mejorSimilitud
        ]);
        
        return 1; // ID por defecto
    }

    private function parseFechaNacimiento($fecha)
    {
        if ($fecha === null || $fecha === '' || trim($fecha) === '') {
            return null;
        }

        if (is_numeric($fecha) && $fecha > 0 && $fecha < 100000) {
            try {
                $fechaConvertida = Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fecha)
                );
                
                if ($fechaConvertida->year >= 1900 && $fechaConvertida->year <= 2010) {
                    return $fechaConvertida->format('Y-m-d');
                }
            } catch (\Exception $e) {
            }
        }

        $fechaLimpia = trim((string)$fecha);
        $fechaLimpia = preg_replace('/[^0-9\/\-]/', '', $fechaLimpia); 
        $fechaLimpia = str_replace(['-', '.', ','], '/', $fechaLimpia);
        $fechaLimpia = preg_replace('/\/+/', '/', $fechaLimpia); 
        
        if (substr_count($fechaLimpia, '/') !== 2) {
            logger()->warning('Fecha inválida - formato incorrecto', ['fecha' => $fecha]);
            return null;
        }
        
        $partes = explode('/', $fechaLimpia);
        $partes = array_map('intval', $partes);
        
        if (count($partes) !== 3) {
            return null;
        }
        
        $combinaciones = [
            ['d' => $partes[0], 'm' => $partes[1], 'y' => $partes[2]], 
            ['y' => $partes[0], 'm' => $partes[1], 'd' => $partes[2]], 
            ['m' => $partes[0], 'd' => $partes[1], 'y' => $partes[2]], 
        ];
        
        foreach ($combinaciones as $combo) {
            try {
                $dia = $combo['d'];
                $mes = $combo['m'];
                $anio = $combo['y'];
                
                if ($anio < 100) {
                    $anio = $anio < 50 ? 2000 + $anio : 1900 + $anio;
                }
                
                if ($anio < 1900 || $anio > 2010 || $mes < 1 || $mes > 12 || $dia < 1 || $dia > 31) {
                    continue;
                }
                
                $fechaCreada = Carbon::createFromDate($anio, $mes, $dia);
                
                if ($fechaCreada->day == $dia && $fechaCreada->month == $mes && $fechaCreada->year == $anio) {
                    return $fechaCreada->format('Y-m-d');
                }
                
            } catch (\Exception $e) {
                continue;
            }
        }
        
        logger()->warning('No se pudo parsear la fecha', ['fecha' => $fecha]);
        return null;
    }

    private function obtenerPnfId(string $nombrePnf)
    {
        return $this->normalizarPnf($nombrePnf);
    }



    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $ruta = $this->archivo->store('informacion', 'public');
            $fullPath = storage_path('app/public/' . $ruta);

            $spreadsheet = IOFactory::load($fullPath);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            // Eliminar encabezado
            unset($rows[0]);

            $cedulasProcesadas = [];

            $total = 0;
            $insertados = 0;
            $omitidosFecha = 0;
            $omitidosCedulaVacia = 0;
            $omitidosDuplicadosExcel = 0;
            $actualizados = 0;


            foreach ($rows as $row) {

                $total++;

                $cedula = trim($row[2]);

                if (!$cedula) {
                    $omitidosCedulaVacia++;
                    continue;
                }

                if (in_array($cedula, $cedulasProcesadas)) {
                    $omitidosDuplicadosExcel++;
                    continue;
                }

                $cedulasProcesadas[] = $cedula;

                $fechaNacimiento = $this->parseFechaNacimiento($row[8]);

                if (!$fechaNacimiento) {
                    $omitidosFecha++;
                    $edad = null;
                } else {
                    $edad = Carbon::parse($fechaNacimiento)->age;
                }


                $sexoRaw = strtoupper(trim($row[7]));
                $sexo = match ($sexoRaw) {
                    'M' => 'MASCULINO',
                    'F' => 'FEMENINO',
                    default => 'NO DEFINIDO',
                };

                $telefono = preg_replace('/\D/', '', $row[9]);

                $persona = Persona::updateOrCreate(
                    ['cedula_persona' => $cedula],
                    [
                        'nombre_persona'            => trim($row[3]),
                        'segundo_nombre_persona'    => trim($row[4]) ?: null,
                        'apellido_persona'          => trim($row[5]),
                        'segundo_apellido_persona'  => trim($row[6]) ?: null,
                        'telefono_persona'          => $telefono,
                        'genero_persona'            => $sexo,
                        'edad_persona'              => $edad,
                        'fecha_nacimiento_persona'  => $fechaNacimiento,
                        'email_persona'             => trim($row[11]),
                        'semestre_persona'          => $row[13] ?? null,
                        'id_perfil'                 => 2,
                        'id_sede'                   => 1,
                    ]
                );

                if ($persona->wasRecentlyCreated) {
                    $insertados++;
                } else {
                    $actualizados++;
                }


                PersonaPnf::updateOrCreate(
                    [
                        'id_persona' => $persona->id_persona,
                    ],
                    [
                        'id_pnf'       => $this->normalizarPnf(trim($row[12])), // <-- CAMBIO AQUÍ
                        'fecha_inicio' => now()->toDateString(),
                        'fecha_fin'    => now()->toDateString(),
                    ]
                );
            }

            $fechaLimite = Carbon::now()->subYears(2);

            $inactivados = Persona::where('updated_at', '<', $fechaLimite)
                ->where('id_perfil', 2)
                ->update([
                    'estado' => false,
                ]);


            Archivo::create([
                'info_estudiantes' => 'Estudiantes UPTP - ' . now()->format('Y-m-d H:i:s'),
                'fecha' => now()->toDateString(),
                'estado' => 'Procesado',
            ]);

            logger()->info('IMPORTACIÓN EXCEL', [
                'total_filas' => $total,
                'insertados' => $insertados,
                'actualizados' => $actualizados,
                'omitidos_fecha' => $omitidosFecha,
                'omitidos_cedula_vacia' => $omitidosCedulaVacia,
                'omitidos_duplicados_excel' => $omitidosDuplicadosExcel,
                'cantidad_inactivados' => $inactivados,
                'fecha_limite' => $fechaLimite->toDateString(),
            ]);

            DB::commit();

            $this->reset('archivo');
            $this->archivoKey = rand();

            $this->dispatch(
                'swal',
                icon: 'success',
                title: '¡Éxito!',
                text: 'El archivo fue procesado correctamente.'
            );
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            $this->dispatch(
                'swal',
                icon: 'error',
                title: 'Error',
                text: 'El archivo no concuerda con la estructura esperada.'
            );
        }
    }



    public function render()
    {
        return view('livewire.archivos', [
            'archivos' => Archivo::latest()
                ->where('info_estudiantes', 'like', "%{$this->buscar}%")
                ->get(),
        ]);
    }
}
