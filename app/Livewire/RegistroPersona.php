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

    //Variable para saber si el componente va a crear o editar
    public $isEdit = false;
    //Variable si se va a usar el componente para mostrar datos
    public $onlyShow = false;

    //Inputs a validar
    public $cedula; 
    public $estadosVeId; 
    public $municipiosId; 
    public $parroquiaId;
    public $municipiosVE = [];
    public $parroquiasVE = [];

    #[Validate('required')]
    public $fecha_nacimiento;


    //Datos basicos
    public $nombre;
    public $segundo_nombre;

    public $apellido;
    public $segundo_apellido;

    //Otros datos

    public $genero;
    public $telefono;
    public $email;

    //Para estudiantes
    public $pnfId;
    public $sedeId;

    //calle y sector
    public $calle;
    public $sector;


    //Variables Para habilitar campos del formulario
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
        
        // Si es edición, no validamos la cédula como única
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

    //Añadir reglas en el caso de que se vaya a registar un estudiantes

    /**
     * Funcion para habilitar el formulario por la cedula
     * @param mixed $value
     * @return void
     */
    public function updatedCedula($value)
    {
        // $value es lo que el usuario acaba de escribir
        
        if (empty($value)) {
            $this->formHabilitado = false;
            return;
        }

        try {
            // Ejecutamos la validación 'unique' usando el valor recibido
            $this->validateOnly('cedula'); 
            
            // Si la ejecución llega aquí, significa que la cédula NO existe en la BD
            $this->formHabilitado = true; 
        } catch (Exception $e) {
            // Si llega aquí, es porque la cédula ya existe o no es un número
            $this->formHabilitado = false;
            throw $e;
        }
    }


    /*Funcion para actualizar los municipios dependiendo del estados */
    public function updatedEstadosVeId($value)
    {
        $this->validateOnly('estadosVeId');

        $this->municipiosVE = DB::table('municipios')
            ->where('estado_id', $value)
            ->where('status', 1)
            ->get();
        
        $this->enabledMunicipio = ($value && $this->formHabilitado);
    }

    /*Funcion para actualizar los municipios dependiendo del estados */

    public function updatedMunicipiosId($value)
    {
        $this->validateOnly('municipiosId');

        $this->parroquiasVE = DB::table('localidads')->where('municipio_id', $value)->where('status', 1)->get();
        
        $this->enabledParroquia = ($value && $this->formHabilitado);
    }


    //funcion para crear una persona
    public function create()
    {
        //Validamos todos los campos
        try {
            $data = $this->validate();
            $finish = Persona::crearPersona($data);

            if($finish) {

                redirect()->route('admin.configuracion.persona.index')->with('success', 'Persona creada exitosamente.');
            } else {
                $this->dispatch('alert', 
                    type: 'error',
                    title: 'Error',
                    text: 'Error al crear la persona. Revisa los datos'
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

    public $personaId;
    //Funcion para actualizar a la persona
    public function update()
    {
        //Validamos todos los campos
        try {
            $data = $this->validate();

            [$finish, $error] = Persona::actualizarPersona($data, $this->personaId);

            if($finish) {

                redirect()->route('admin.configuracion.persona.index')->with('success', 'Persona actualizada exitosamente.');
            } else {
                $this->dispatch('alert', 
                    type: 'error',
                    title: 'Error',
                    text: $error ?? 'Error al actualizar la persona. Revisa los datos'
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

    //Funcion para actualizar a la persona



    /* Montar los datos al componente */
    public $perfil = [];
    public $pnfs = [];
    public $estadosVE = [];
    public $sede = [];
    public $data = [];

    public function mount()
    {
        //en el caso que el componente vaya a editar
        if($this->isEdit)
        {

            //Habilitamos el formulario
            $this->formHabilitado = true;
            $this->enabledMunicipio = true;
            $this->enabledParroquia = true;

            //Recuperamos el id de la persona
            $id = request("id");
            $this->personaId = $id;
            // Aquí iría la lógica para cargar los datos de la persona con ese ID
            //Recuperamos tanto la persona, personapnf y direccion
            $persona = Persona::where('id_persona', $id)->first();
            $personaPnf = PersonaPnf::with('pnf')->where('id_persona', $id)->first();
            $direccion = DB::table('direccion')
                ->join('localidads', 'direccion.id_localidad', '=', 'localidads.id')
                ->join('municipios', 'localidads.municipio_id', '=', 'municipios.id')
                ->join('estados', 'municipios.estado_id', '=', 'estados.id')
                ->where('direccion.id_persona', $id)
                ->select('direccion.*', 'localidads.*', 'municipios.*', 'estados.*')
                ->first();

            //Remplazamos los valores
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




            //cargamos los selects
            $this->pnfs = DB::table('pnf')->where('id_estatus', 1)->get();
            $this->sede = DB::table('sede')->get();
            $this->showPnf = true;
            $this->pnfId = $personaPnf->id_pnf;
            $this->sedeId = $persona->id_sede;
            

            //En el caso del que usurio NO tenga una direccion asociada(osea null)

            if(!$direccion)
            {
                $this->enabledMunicipio = false;
                $this->enabledParroquia = false;
                $this->estadosVE = DB::table('estados')->get();

                //Alertamos al usuario que no tiene direccion registrada
                $this->dispatch('alert',
                    type: 'warning',
                    title: 'Usuario sin Dirección',
                    text: 'El usuario no tiene una dirección registrada. Por favor, complete los datos de dirección.');
                return;
            }
            //Direccion
            $this->estadosVeId = $direccion->estado_id;
            // 2. Carga los municipios del estado
            $this->municipiosVE = DB::table('municipios')
                ->where('estado_id', $this->estadosVeId)
                ->get();
            
            // 3. Asigna el municipio
            $this->municipiosId = $direccion->municipio_id;
            
            // 4. Carga las parroquias del municipio
            $this->parroquiasVE = DB::table('localidads')
                ->where('municipio_id', $this->municipiosId)
                ->get();
                
            // 5. Finalmente asigna la parroquia
            $this->parroquiaId = $direccion->id_localidad;

            $this->calle = $direccion->calle;
            $this->sector = $direccion->sector;
                


        }

        // Lógica de inicialización si es necesaria
        $this->pnfs = DB::table('pnf')->where('id_estatus', 1)->get();
        $this->estadosVE = DB::table('estados')->where('status', 1)->get();
        $this->sede = DB::table('sede')->get();
    }
    
    public function render()
    {
        return view('livewire.registro-persona');
    }
}
