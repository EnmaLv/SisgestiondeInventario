<?php

namespace App\Livewire;

use App\Models\Registro_diario;
use Livewire\Component;

class PaginacionReusable extends Component
{
    public function render()
    {
        $data = Registro_diario::paginate(1);

        return view('livewire.paginacion-reusable', ['data'   => $data]);
    }
}
