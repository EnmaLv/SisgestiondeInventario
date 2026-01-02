<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Estado;
use Livewire\Attributes\On;

class EstadoIndex extends Component
{
    use WithPagination;

    public $nombre_estado;
    public $estado_id;
    public $updateMode = false;
    public $search = '';

    protected $rules = [
        'nombre_estado' => 'required|string|max:255',
    ];

    public function render()
    {
        $estados = Estado::where('status', true)
            ->when($this->search, function ($query) {
                $query->where('nombre_estado', 'like', '%' . $this->search . '%');
            })
            ->orderBy('nombre_estado', 'asc')
            ->paginate(10);

        return view('livewire.admin.estado-index', compact('estados'));
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap-custom';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function resetInputFields()
    {
        $this->nombre_estado = '';
        $this->estado_id = null;
        $this->updateMode = false;
    }

    public function store()
    {
        $this->validate();

        if (Estado::where('nombre_estado', $this->nombre_estado)->where('status', true)->exists()) {
            $this->dispatch('swal',
                icon: 'error',
                title: 'Error',
                text: 'Ya existe un estado con ese nombre.'
            );
            return;
        }

        Estado::create([
            'nombre_estado' => $this->nombre_estado,
            'status' => true,
        ]);

        $this->resetInputFields();

        $this->dispatch('swal',
            icon: 'success',
            title: '¡Éxito!',
            text: 'Estado creado correctamente.'
        );

    }

    public function edit($id)
    {
        $estado = Estado::findOrFail($id);
        $this->estado_id = $id;
        $this->nombre_estado = $estado->nombre_estado;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate();

        Estado::find($this->estado_id)
            ->update(['nombre_estado' => $this->nombre_estado]);

        $this->dispatch('swal',
            icon: 'success',
            title: 'Actualizado',
            text: 'Estado actualizado correctamente.'
        );
    }

    public function confirmDestroy($id)
    {
        $this->dispatch('confirm-delete', id: $id);
    }

    #[On('destroy-estado')]
    public function destroy($id)
    {
        Estado::findOrFail($id)->update([
            'status' => false
        ]);

        $this->dispatch('swal',
            icon: 'success',
            title: 'Eliminado',
            text: 'Estado eliminado correctamente.'
        );
    }



}