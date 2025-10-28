<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Persona;
use App\Models\PersonaPnf;
use App\Models\Registro_diario;
use Illuminate\Support\Facades\DB;
use Exception;

class RegisterNoti extends Component
{
    #[Validate('required|numeric|min:7', message: ['required' => 'La cédula es requerida', 'numeric' => 'La cédula debe ser un número', 'min' => 'La cédula debe tener al menos 7 dígitos'])]
    public $cedula = '';

    
    public $showNotification = false;

    public $notification = [    
        'type' => 'success',
        'message' => ''
    ];

    public function save()
    {
        $this->validate();

        $DatosHistorial = [
            'cedula' => $this->cedula,
            'fecha' => date('Y-m-d'),
            'hora' => date('H:i:s'),
        ];
        //Valida que la persona exista
        $persona = Persona::where('cedula_persona', $this->cedula)->first();

        if ($persona) {
            //Valida que la persona no se haya registrado hoy
            $is_register = Registro_diario::where('id_persona', $persona->id_persona)->where('fecha_regis_diario_c', date('Y-m-d'))->exists();

            if($is_register){
                //retornamos un mensaje de erro
                $this->notification = [
                    'type' => 'danger',
                    'message' => "El estudiante {$persona->nombre_persona} {$persona->apellido_persona} ya se registro hoy"
                ];

                $DatosHistorial['nombre'] = $persona->nombre_persona;
                $DatosHistorial['estado'] = 'Rechazado';
                $DatosHistorial['observacion'] = 'El estudiante ya se registro hoy';

                $this->showNotification();
                $this->cedula = '';

                //hacemos un evento para recuperar los datos
                $this->dispatch('cedula-validada', datos: $DatosHistorial);
                return;
            }

            try{
                DB::beginTransaction();
                $personaPnf = PersonaPnf::where('id_persona_pnf', $persona->id_persona)->first();
                   

                DB::table('registro_diario_c')->insert([
                    'id_persona' => $persona->id_persona,
                    'id_persona_pnf' => $personaPnf->id_persona_pnf ,
                    'fecha_regis_diario_c' => date('Y-m-d'),
                    'hora' => date('H:i:s'),
                ]);

                //Codigo para la parte del inventario

                $DatosHistorial['nombre'] = $persona->nombre_persona;
                $DatosHistorial['estado'] = 'Aprobado';
                $DatosHistorial['observacion'] = 'El estudiante se registro exitosamente';

                //Aplicamos en la base de datos
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                $this->notification = [
                    'type' => 'danger',
                    'message' => "Error al registrar el estudiante {$persona->nombre_persona} {$persona->apellido_persona}, Intente de nuevo."
                ];

                $DatosHistorial['nombre'] = "Sin Nombre";
                $DatosHistorial['estado'] = 'Rechazado';
                $DatosHistorial['observacion'] = 'Error al registrar el estudiante';

                //hacemos un evento para recuperar los datos
                $this->dispatch('cedula-validada', datos: $DatosHistorial);
            }

            $this->notification = [
                'type' => 'success',
                'message' => "El estudiante {$persona->nombre_persona} {$persona->apellido_persona} se registro exitosamente!"
            ];

            $DatosHistorial['nombre'] = $persona->nombre_persona;
            $DatosHistorial['estado'] = 'Aprobado';
            $DatosHistorial['observacion'] = 'El estudiante se registro exitosamente';

            $this->dispatch('cedula-validada', datos: $DatosHistorial);
        } else {

            $this->notification = [
                'type' => 'danger',
                'message' => 'No se encontró un registro para la cédula: ' . $this->cedula
            ];
        }
        
        $this->showNotification();
        $this->cedula = '';
        
        // Ocultar la notificación después de 5 segundos
        $this->dispatch('notify-saved');
    }

    public function showNotification()
    {
        $this->showNotification = true;
    }

    public function render()
    {
        return view('livewire.register-noti');
    }
}
