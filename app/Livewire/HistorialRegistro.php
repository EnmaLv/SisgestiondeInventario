<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;

class HistorialRegistro extends Component
{

    public $registro = [];

    public function mount()
    {
        $this->registro = [];
    }

    #[On('cedula-validada')]
    public function agregarHistorial($datos)
    {
        $nuevo_registro = [
            'cedula' => $datos['cedula'],
            'nombre' => $datos['nombre']??'Sin Nombre'  ,
            'estado' => $datos['estado'],
            'observacion' => $datos['observacion'],
            'fecha' => $datos['fecha'],
            'hora' => $datos['hora'],
        ];

        //Insertamos el nuevo registro de primero
        array_unshift($this->registro, $nuevo_registro);

        //Limitamos la lista a 20 registros
        if (count($this->registro) > 20) {
            array_splice($this->registro, 20);
        }


    }

    public function render()
    {
        return view('livewire.historial-registro');
    }
}
