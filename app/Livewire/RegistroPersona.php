<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  
use App\Models\Persona;
use App\Models\PersonaPnf;
use Livewire\Attributes\Validate;
use Exception;
use Livewire\Attributes\Title;

class RegistroPersona extends Component
{

    public $isEdit = false;
    public $onlyShow = false;
    public $cedula; 
    public $estadosVeId; 
    public $municipiosId; 
    public $parroquiaId;
    public $municipiosVE = [];
    public $parroquiasVE = [];

    #[Validate('required')]
    public $fecha_nacimiento;


    public $nombre;
    public $segundo_nombre;
    public $apellido;
    public $segundo_apellido;

    public $genero;
    public $telefono;
    public $email;

    public $pnfId;
    public $sedeId;

    public $calle;
    public $sector;

    public $formHabilitado = false;
    public $showPnf = false;
    public $enabledMunicipio = false;
    public $enabledParroquia = false;

    protected function rules()
    {
        $rules = [
            'cedula' => 'required|min:1000000|numeric|unique:persona,cedula_persona',
            'nombre' => 'required|min:3|max:50',
            'segundo_nombre' => 'nullable',
            'apellido' => 'required|min:3|max:50',
            'segundo_apellido' => 'nullable',
            'fecha_nacimiento' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(18)->format('Y-m-d'),
            ],
            'genero' => 'required',
            'telefono' => 'required',
            'email' => 'required|email',
            'calle' => 'required',
            'sector' => 'required',
            'estadosVeId' => 'required|numeric',
            'municipiosId' => 'required|numeric',
            'parroquiaId' => 'required|numeric',
            'sedeId' => 'required|numeric',
            'pnfId' => 'required|numeric',
        ];
        
        if ($this->isEdit) {
            $rules['cedula'] = 'required|min:100000|numeric';
        }
        
        return $rules;
    }


    protected $messages = [
        'estadosVeId.required' => 'Debe seleccionar un estado',
        'cedula.min' => 'La cédula debe tener al menos 7 dígitos',
        'nombre.required' => 'El nombre es obligatorio',
        'apellido.required' => 'El apellido es obligatorio',
        'genero.required' => 'El genero es obligatorio',
        'telefono.required' => 'El telefono es obligatorio',
        'email.required' => 'El email es obligatorio',
        'calle.required' => 'La calle es obligatoria',
        'sector.required' => 'El sector es obligatorio',
        'municipiosId.required' => 'Debe seleccionar un municipio',
        'parroquiaId.required' => 'Debe seleccionar una parroquia',
        'pnfId.required' => 'El PNF es obligatorio',
        'sedeId.required' => 'La Sede es obligatoria',
    ];


    public function updatedCedula($value)
    {
        
        if (empty($value)) {
            $this->formHabilitado = false;
            return;
        }

        try {
            $this->validateOnly('cedula'); 
        
            $this->formHabilitado = true; 
        } catch (Exception $e) {
            $this->formHabilitado = false;
            throw $e;
        }
    }


    public function updatedEstadosVeId($value)
    {
        $this->validateOnly('estadosVeId');

        $this->municipiosVE = DB::table('municipios')
            ->where('estado_id', $value)
            ->where('status', 1)
            ->get();
        
        $this->enabledMunicipio = ($value && $this->formHabilitado);
    }

    public function updatedMunicipiosId($value)
    {
        $this->validateOnly('municipiosId');

        $this->parroquiasVE = DB::table('localidads')->where('municipio_id', $value)->where('status', 1)->get();
        
        $this->enabledParroquia = ($value && $this->formHabilitado);
    }

    public function create()
    {
        //Validamos todos los campos

            $data = $this->validate();
            $finish = Persona::crearPersona($data);

            if($finish) {

                redirect()->route('admin.configuracion.persona.index')->with('success', 'Estudiante creado exitosamente.');
            } else {
                $this->dispatch('alert', 
                    type: 'error',
                    title: 'Error',
                    text: 'Error al crear el estudiante. Revisa los datos'
                );
            }


    }

    public $personaId;
    public function update()
    {
        try {
            $data = $this->validate();

            [$finish, $error] = Persona::actualizarPersona($data, $this->personaId);

            if($finish) {

                redirect()->route('admin.configuracion.persona.index')->with('success', 'Estudiante actualizado exitosamente.');
            } else {
                $this->dispatch('alert', 
                    type: 'error',
                    title: 'Error',
                    text: $error ?? 'Error al actualizar el estudiante. Revisa los datos'
                );
            }
        } catch (Exception $e) {
            $this->dispatch('alert', 
                type: 'error',
                title: 'Error',
                text: $e->getMessage() ?: 'Ocurrió un error al procesar la solicitud'
            );
        }

    }

    public $perfil = [];
    public $pnfs = [];
    public $estadosVE = [];
    public $sede = [];
    public $data = [];

    public function mount()
    {
        if($this->isEdit)
        {
            $this->formHabilitado = true;
            $this->enabledMunicipio = true;
            $this->enabledParroquia = true;

            $id = request("id");
            $this->personaId = $id;
            $persona = Persona::where('id_persona', $id)->first();
            $personaPnf = PersonaPnf::with('pnf')->where('id_persona', $id)->first();
            $direccion = DB::table('direccion')
                ->join('localidads', 'direccion.id_localidad', '=', 'localidads.id')
                ->join('municipios', 'localidads.municipio_id', '=', 'municipios.id')
                ->join('estados', 'municipios.estado_id', '=', 'estados.id')
                ->where('direccion.id_persona', $id)
                ->select('direccion.*', 'localidads.*', 'municipios.*', 'estados.*')
                ->first();

            $this->cedula = $persona->cedula_persona;
            $this->perfil = DB::table('perfil')->get();
            $this->fecha_nacimiento = $persona->fecha_nacimiento_persona;
            $this->nombre = $persona->nombre_persona;
            $this->segundo_nombre = $persona->segundo_nombre_persona;
            $this->apellido = $persona->apellido_persona;
            $this->segundo_apellido = $persona->segundo_apellido_persona;
            $this->genero = $persona->genero_persona;
            $this->telefono = $persona->telefono_persona;
            $this->email = $persona->email_persona;
            $this->pnfs = DB::table('pnf')->where('id_estatus', 1)->get();
            $this->sede = DB::table('sede')->get();
            $this->showPnf = true;
            $this->pnfId = $personaPnf->id_pnf;
            $this->sedeId = $persona->id_sede;
            
            if(!$direccion)
            {
                $this->enabledMunicipio = false;
                $this->enabledParroquia = false;
                $this->estadosVE = DB::table('estados')->get();

                $this->dispatch('alert',
                    type: 'warning',
                    title: 'Usuario sin Dirección',
                    text: 'El usuario no tiene una dirección registrada. Por favor, complete los datos de dirección.');
                return;
            }
            $this->estadosVeId = $direccion->estado_id;
            $this->municipiosVE = DB::table('municipios')
                ->where('estado_id', $this->estadosVeId)
                ->get();
            
            $this->municipiosId = $direccion->municipio_id;
            
            $this->parroquiasVE = DB::table('localidads')
                ->where('municipio_id', $this->municipiosId)
                ->get();
                
            $this->parroquiaId = $direccion->id_localidad;
            $this->calle = $direccion->calle;
            $this->sector = $direccion->sector;
                


        }

        $this->pnfs = DB::table('pnf')->where('id_estatus', 1)->get();
        $this->estadosVE = DB::table('estados')->where('status', 1)->get();
        $this->sede = DB::table('sede')->get();
    }
    
    public function render()
    {
        return view('livewire.registro-persona');
    }
}
