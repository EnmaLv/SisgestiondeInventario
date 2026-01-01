<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Archivo;
use Carbon\Carbon;

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

        // Guardar archivo físico
        $ruta = $this->archivo->store('informacion', 'public');

        // Guardar registro en BD
        Archivo::create([
            'info_estudiantes' => $ruta,
            'fecha' => Carbon::now()->toDateString(),
            'estado' => 'Procesado',
        ]);

        // Reset
        $this->reset('archivo');
        $this->archivoKey = rand();

        session()->flash('success', 'Archivo procesado correctamente.');
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

