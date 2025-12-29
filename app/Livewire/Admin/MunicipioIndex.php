<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Estado;
use App\Models\Municipio;
use App\Models\AnioEscolar;

class MunicipioIndex extends Component
{
    /* paginacion */
    use WithPagination;
    public $nombre_municipio;
    public $municipio_id;
    public $estado_id;
    public $updateMode = false;
    public $search = '';
    /**
     * Se ejecuta al montar el componente
     */
    public function mount()
    {
    }


    protected $rules = [
        'nombre_municipio' => 'required|string|max:255',
        'estado_id'=> 'required|integer|exists:estados,id',
    ];
    public function render()
    {
        $estados = Estado::where('status', true)
            ->get();
        $municipios = Municipio::where('status', true)
            ->when($this->search, function ($query) {
                $query->where('nombre_municipio', 'like', '%' . $this->search . '%')
                    ->orWhereHas('estado', function ($q) {
                        $q->where('nombre_estado', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('nombre_municipio', 'asc')
            ->paginate(10);

        return view('livewire.admin.municipio-index', compact('municipios', 'estados'));
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
        $this->nombre_municipio = '';
        $this->municipio_id = null;
        $this->estado_id = null;
        $this->updateMode = false;
    }

    public function store()
    {
        $this->validate();

        // Evitar duplicados
        if (Municipio::where('nombre_municipio', $this->nombre_municipio)
            ->where('estado_id', $this->estado_id)
            ->where('status', true)->exists()) {
            session()->flash('error', 'Ya existe un municipio con ese nombre.');
            return;
        }

        Municipio::create([
            'nombre_municipio' => $this->nombre_municipio,
            'estado_id' => $this->estado_id,
            'status' => true,
        ]);

        session()->flash('success', 'Municipio creado correctamente.');
        $this->dispatch('cerrarModal');
        $this->resetInputFields();

    }

    public function edit($id)
    {
        $municipio = Municipio::findOrFail($id);
        $this->municipio_id = $id;
        $this->nombre_municipio = $municipio->nombre_municipio;
        $this->estado_id = $municipio->estado_id;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate();

        $municipio = Municipio::find($this->municipio_id);
        $municipio->update(['nombre_municipio' => $this->nombre_municipio, 'estado_id' => $this->estado_id]);

        session()->flash('success', 'Municipio actualizado correctamente.');
        $this->dispatch('cerrarModal');
        $this->resetInputFields();
    }

    public function destroy($id)
    {
        $municipio = Municipio::findOrFail($id);
        $municipio->update(['status' => false]);

        session()->flash('success', 'Municipio eliminado correctamente.');

        $this->dispatch('cerrarModal');
        $this->resetPage();

    }

}

