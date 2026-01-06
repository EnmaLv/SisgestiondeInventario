<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Archivo;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use App\Models\Persona;

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

    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {

            // 1. Guardar archivo
            $ruta = $this->archivo->store('informacion', 'public');
            $fullPath = storage_path('app/public/' . $ruta);

            // 2. BORRAR TODOS LOS ESTUDIANTES ACTUALES
            Persona::where('id_perfil', 2)->delete();

            // 3. Leer Excel
            $spreadsheet = IOFactory::load($fullPath);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            unset($rows[0]); // eliminar encabezado

            // 👇 NUEVO: control de duplicados
            $cedulasProcesadas = [];
            $cedulasDuplicadas = 0;
            $insertados = 0;

            // 4. Procesar filas
            foreach ($rows as $row) {

                $cedula = trim($row[4]);

                // 👇 Si la cédula ya fue procesada, se omite
                if (in_array($cedula, $cedulasProcesadas)) {
                    $cedulasDuplicadas++;
                    continue;
                }

                $cedulasProcesadas[] = $cedula;

                Persona::create([
                    'nombre_persona' => $row[0],
                    'segundo_nombre_persona' => $row[1] ?: null,
                    'apellido_persona' => $row[2],
                    'segundo_apellido_persona' => $row[3] ?: null,
                    'cedula_persona' => $cedula,
                    'telefono_persona' => $row[5],
                    'genero_persona' => $row[6],
                    'edad_persona' => $row[7],
                    'fecha_nacimiento_persona' => Carbon::parse($row[8]),
                    'email_persona' => $row[9],
                    'id_perfil' => 2,
                    'id_sede' => 1,
                ]);

                $insertados++;
            }

            // 5. Guardar historial
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
                text: 'El archivo fue procesado Exitosamente.'
            );
        } catch (\Throwable $e) {

            DB::rollBack();
            report($e);

            $this->dispatch(
                'swal',
                icon: 'error',
                title: '¡Error!',
                text: 'El archivo no concuerda con la informacion de los estudiantes.'
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
