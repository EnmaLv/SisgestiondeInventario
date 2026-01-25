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
        
        return 1; // ID por defecto (Generalmente "No Definido" o similar)
    }

    private function parseFechaNacimiento($fecha)
    {
        if ($fecha === null || $fecha === '' || trim($fecha) === '') {
            return null;
        }

        // Si es un número de serie de Excel (como 34867)
        if (is_numeric($fecha)) {
            try {
                return Carbon::instance(
                    \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($fecha)
                )->format('Y-m-d');
            } catch (\Exception $e) {
                // Si falla, continua al parseo manual
            }
        }

        // Si viene como string, limpiar caracteres extraños (incluyendo Unicode "chino")
        $fechaLimpia = preg_replace('/[^\x20-\x7E]/u', '', (string)$fecha);
        $fechaLimpia = preg_replace('/[^0-9\/\-]/', '', $fechaLimpia); 
        $fechaLimpia = str_replace(['-', '.', ','], '/', $fechaLimpia);
        
        if (substr_count($fechaLimpia, '/') !== 2) {
            return null;
        }
        
        $partes = explode('/', $fechaLimpia);
        if (count($partes) !== 3) return null;

        // Intentar parsear formatos comunes (D/M/Y)
        try {
            $fechaCreada = Carbon::createFromFormat('d/m/Y', $fechaLimpia);
            if ($fechaCreada && $fechaCreada->year > 1920 && $fechaCreada->year < now()->year) {
                return $fechaCreada->format('Y-m-d');
            }
        } catch (\Exception $e) {
            return null;
        }

        return null;
    }

    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $ruta = $this->archivo->store('informacion', 'public');
            $fullPath = storage_path('app/public/' . $ruta);

            $spreadsheet = IOFactory::load($fullPath);
            // IMPORTANTE: El 4to parámetro 'true' habilita los índices por letras (A, B, C...)
            // El 3er parámetro 'false' evita que Excel formatee las fechas a "chino"
            $rows = $spreadsheet->getActiveSheet()->toArray(null, true, false, true);

            // Eliminar encabezado (Fila 1)
            unset($rows[1]);

            $cedulasProcesadas = [];
            $stats = [
                'total' => 0, 'insertados' => 0, 'actualizados' => 0, 
                'omitidos_fecha' => 0, 'omitidos_cedula' => 0, 'omitidos_duplicados' => 0
            ];

            foreach ($rows as $row) {
                $stats['total']++;

                // Usamos índices de letras según tu DD
                $cedula = trim($row['C'] ?? '');

                if (!$cedula) {
                    $stats['omitidos_cedula']++;
                    continue;
                }

                if (in_array($cedula, $cedulasProcesadas)) {
                    $stats['omitidos_duplicados']++;
                    continue;
                }

                $cedulasProcesadas[] = $cedula;

                // Columna 'I' para fecha de nacimiento
                $fechaNacimiento = $this->parseFechaNacimiento($row['I'] ?? null);
                $edad = $fechaNacimiento ? Carbon::parse($fechaNacimiento)->age : null;

                if (!$fechaNacimiento) {
                    $stats['omitidos_fecha']++;
                }

                $sexoRaw = strtoupper(trim($row['H'] ?? ''));
                $sexo = match ($sexoRaw) {
                    'M' => 'MASCULINO',
                    'F' => 'FEMENINO',
                    default => 'NO DEFINIDO',
                };

                $telefono = preg_replace('/\D/', '', $row['J'] ?? '');

                $persona = Persona::updateOrCreate(
                    ['cedula_persona' => $cedula],
                    [
                        'nombre_persona'            => trim($row['D'] ?? ''),
                        'segundo_nombre_persona'    => trim($row['E'] ?? '') ?: null,
                        'apellido_persona'          => trim($row['F'] ?? ''),
                        'segundo_apellido_persona'  => trim($row['G'] ?? '') ?: null,
                        'telefono_persona'          => $telefono,
                        'genero_persona'            => $sexo,
                        'edad_persona'              => $edad,
                        'fecha_nacimiento_persona'  => $fechaNacimiento,
                        'email_persona'             => trim($row['L'] ?? ''),
                        'semestre_persona'          => $row['N'] ?? null,
                        'id_perfil'                 => 2,
                        'id_sede'                   => 1,
                    ]
                );

                if ($persona->wasRecentlyCreated) {
                    $stats['insertados']++;
                } else {
                    $stats['actualizados']++;
                }

                // Columna 'M' para el PNF
                PersonaPnf::updateOrCreate(
                    ['id_persona' => $persona->id_persona],
                    [
                        'id_pnf'       => $this->normalizarPnf(trim($row['M'] ?? '')),
                        'fecha_inicio' => now()->toDateString(),
                        'fecha_fin'    => now()->toDateString(),
                    ]
                );
            }

            Archivo::create([
                'info_estudiantes' => 'Estudiantes UPTP - ' . now()->format('Y-m-d H:i:s'),
                'fecha' => now()->toDateString(),
                'estado' => 'Procesado',
            ]);

            DB::commit();

            $this->reset('archivo');
            $this->archivoKey = rand();

            $this->dispatch('swal', icon: 'success', title: '¡Éxito!', text: 'Procesado: ' . $stats['insertados'] . ' nuevos y ' . $stats['actualizados'] . ' actualizados.');

        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('swal', icon: 'error', title: 'Error', text: 'Error al procesar: ' . $e->getMessage());
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