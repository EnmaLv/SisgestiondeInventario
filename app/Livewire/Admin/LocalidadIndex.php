<?php

namespace App\Livewire\Admin;

use App\Models\Estado;
use App\Models\Municipio;
use App\Models\Localidad;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AnioEscolar;

class LocalidadIndex extends Component
{
    use WithPagination;

    public $nombre_localidad;
    public $estado_id; 
    public $municipio_id;
    public $localidad_id;
    public $updateMode = false;
    public $search = '';
    public $municipios = []; //municipios dinámicos filtrados

    protected $rules = [
        'nombre_localidad' => 'required|string|max:255',
        'estado_id' => 'required|integer|exists:estados,id',
        'municipio_id' => 'required|integer|exists:municipios,id',
    ];

        /**
     * Se ejecuta al montar el componente
     */
    public function mount()
    {
    }


    public function render()
    {
        $estados = Estado::where('status', true)->get();

        $localidades = Localidad::where('status', true)
            ->when($this->search, function ($query) {
                $query->where('nombre_localidad', 'like', '%' . $this->search . '%')
                    ->orWhereHas('municipio', function ($q) {
                        $q->where('nombre_municipio', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('municipio.estado', function ($q) {
                        $q->where('nombre_estado', 'like', '%' . $this->search . '%');
                    });
            })
            ->orderBy('nombre_localidad', 'asc')
            ->paginate(10);
            
        return view('livewire.admin.localidad-index', compact('estados', 'localidades'));
    }

    public function paginationView()
    {
        return 'vendor.livewire.bootstrap-custom';
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }


    //Cuando cambia el estado, actualizamos los municipios
    public function updatedEstadoId($estado_id)
    {
        $this->municipios = Municipio::where('estado_id', $estado_id)
            ->where('status', true)
            ->orderBy('nombre_municipio', 'asc')
            ->get();

        $this->municipio_id = null; //Reinicia el select dependiente
    }

    public function resetInputFields()
    {
        $this->nombre_localidad = '';
        $this->estado_id = null;
        $this->municipio_id = null;
        $this->localidad_id = null;
        $this->municipios = [];
        $this->updateMode = false;
    }

    public function store()
    {
        $this->validate();

        if (Localidad::where('nombre_localidad', $this->nombre_localidad)->where('status', true)->exists()) {
            session()->flash('error', 'Ya existe una localidad con ese nombre.');
            return;
        }

        Localidad::create([
            'nombre_localidad' => $this->nombre_localidad,
            'municipio_id' => $this->municipio_id,
            'status' => true,
        ]);

        session()->flash('success', 'Localidad creada correctamente.');
        $this->dispatch('cerrarModal');
        $this->resetInputFields();
    }



    public function edit($id)
    {
        $localidad = Localidad::with('municipio.estado')->findOrFail($id);

        $this->localidad_id = $localidad->id;
        $this->nombre_localidad = $localidad->nombre_localidad;

        // Obtenemos correctamente el estado desde la relación
        $this->estado_id = $localidad->municipio->estado->id ?? null;
        $this->municipio_id = $localidad->municipio_id;

        // Cargamos los municipios de ese estado
        if ($this->estado_id) {
            $this->municipios = Municipio::where('estado_id', $this->estado_id)
                ->where('status', true)
                ->orderBy('nombre_municipio', 'asc')
                ->get();
        }

        $this->updateMode = true;
    }


    public function update()
    {
        $this->validate();

        $localidad = Localidad::findOrFail($this->localidad_id);
        $localidad->update([
            'nombre_localidad' => $this->nombre_localidad,
            'municipio_id' => $this->municipio_id,

        ]);

        session()->flash('success', 'Localidad actualizada correctamente.');
        $this->dispatch('cerrarModal');
        $this->resetInputFields();
    }

    public function destroy($id)
    {
        $localidad = Localidad::findOrFail($id);
        $localidad->update(['status' => false]);

        session()->flash('success', 'Localidad eliminada correctamente.');
        $this->dispatch('cerrarModal');
        $this->resetPage();
    }
}
