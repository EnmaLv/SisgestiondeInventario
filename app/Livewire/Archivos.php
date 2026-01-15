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

    public function save()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $ruta = $this->archivo->store('informacion', 'public');
            $fullPath = storage_path('app/public/' . $ruta);
            $spreadsheet = IOFactory::load($fullPath);
            $rows = $spreadsheet->getActiveSheet()->toArray();

            unset($rows[0]);

            $cedulasEnBd = Persona::where('id_perfil', 2)->pluck('cedula_persona')->toArray();
            $cedulasProcesadas = [];
            $cedulasDuplicadas = 0;
            $insertados = 0;

            foreach ($rows as $row) {

                $cedula = trim($row[4]);

                if (in_array($cedula, $cedulasProcesadas)) {
                    $cedulasDuplicadas++;
                    continue;
                }

                $cedulasProcesadas[] = $cedula;

                if (in_array($cedula, $cedulasEnBd)) {
                    continue;
                }

                $persona = Persona::create([
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

                PersonaPnf::create([
                    'id_persona'  => $persona->id_persona,
                    'id_pnf'      => $row[11],
                    'fecha_inicio' => now()->toDateString(),
                    'fecha_fin'   => now()->toDateString(),
                ]);

                $insertados++;
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
