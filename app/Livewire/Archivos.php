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

    private function parseFechaNacimiento($fecha)
    {
        if (!$fecha) {
            return null;
        }

        // Limpiar espacios y caracteres raros
        $fecha = trim($fecha);

        // Validar formato manualmente
        if (!preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $fecha)) {
            return null;
        }

        try {
            return Carbon::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    private function obtenerPnfId(string $nombrePnf)
    {
        return DB::table('pnf')
            ->where('nombre_pnf', 'like', "%{$nombrePnf}%")
            ->value('id_pnf') ?? 1;
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

            $cedulasEnBd = Persona::where('id_perfil', 2)
                ->pluck('cedula_persona')
                ->toArray();

            $cedulasProcesadas = [];

            foreach ($rows as $row) {

                // CÉDULA
                $cedula = trim($row[2]);

                if (!$cedula) {
                    continue;
                }

                // Evitar duplicados en el mismo Excel
                if (in_array($cedula, $cedulasProcesadas)) {
                    continue;
                }

                $cedulasProcesadas[] = $cedula;

                // Evitar duplicados en BD
                if (in_array($cedula, $cedulasEnBd)) {
                    continue;
                }

                $fechaNacimiento = $this->parseFechaNacimiento($row[8]);

                if (!$fechaNacimiento) {
                    // Saltar fila si la fecha es inválida
                    continue;
                }

                $edad = Carbon::parse($fechaNacimiento)->age;


                // EDAD
                $edad = Carbon::parse($fechaNacimiento)->age;

                $sexoRaw = strtoupper(trim($row[7]));

                $sexo = match ($sexoRaw) {
                    'M' => 'Masculino',
                    'F' => 'Femenino',
                    default => 'Otro',
                };


                // TELÉFONO (limpio)
                $telefono = preg_replace('/\D/', '', $row[9]);

                // CREAR PERSONA
                $persona = Persona::create([
                    'nombre_persona'          => trim($row[3]),
                    'segundo_nombre_persona'  => trim($row[4]) ?: null,
                    'apellido_persona'        => trim($row[5]),
                    'segundo_apellido_persona' => trim($row[6]) ?: null,
                    'cedula_persona'          => $cedula,
                    'telefono_persona'        => $telefono,
                    'genero_persona'          => $sexo,
                    'edad_persona'            => $edad,
                    'fecha_nacimiento_persona' => $fechaNacimiento,
                    'email_persona'           => trim($row[11]),
                    'id_perfil'               => 2,
                    'id_sede'                 => 1,
                ]);

                // RELACIÓN PERSONA - PNF
                PersonaPnf::create([
                    'id_persona'   => $persona->id_persona,
                    'id_pnf'       => $this->obtenerPnfId(trim($row[12])),
                    'fecha_inicio' => now()->toDateString(),
                    'fecha_fin'    => now()->toDateString(),

                    // 'semestre' => $row[13],
                ]);
            }

            Archivo::create([
                'info_estudiantes' => $ruta,
                'fecha' => now()->toDateString(),
                'estado' => 'Procesado',
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
