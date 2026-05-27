<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Compra;
use Livewire\WithPagination;

class CompraIndex extends Component
{
    use WithPagination;

    public $buscar = '';      
    public $estado = null;

    public function updatingBuscar()
    {
        $this->resetPage();
    }

    public function render()
    {
        $compras = Compra::listarCompras(
            $this->buscar,
            $this->estado
        );

        return view('livewire.compra-index', compact('compras'));
    }
}
