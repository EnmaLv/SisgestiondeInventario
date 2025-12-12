<?php

namespace App\Livewire;

use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Models\Persona;
use App\Models\PersonaPnf;
use App\Models\Registro_diario;
use App\Models\DetalleRegistroDiario;
use App\Models\Receta;
use App\Models\Lote;
use App\Models\InventarioSucursalLote;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;
use Exception;
use Livewire\WithPagination;

class RegisterNoti extends Component
{
    use WithPagination;

    #[Validate('required|numeric|min:7', message: ['required' => 'La cédula es requerida', 'numeric' => 'La cédula debe ser un número', 'min' => 'La cédula debe tener al menos 7 dígitos'])]
    public $cedula = '';

    public $showNotification = false;

    public $notification = [
        'type' => 'success',
        'message' => ''
    ];

    public $receta_id = null;
    public $cantidad_servido = null;
    public $desayuno_registrado = false;
    public $desayuno_del_dia = null;
    public $horarioPermitido;

    public $alertInventario = null;
    public $alertLimite = null;




    public function save()
    {

        if (!$this->desayuno_registrado) {
            $this->notification = [
                'type' => 'danger',
                'message' => 'Debe seleccionar el desayuno y la cantidad antes de registrar estudiantes.'
            ];
            $this->showNotification();
            return;
        }

        // 🚨 Validación de límite de raciones
        $detalleHoy = DetalleRegistroDiario::whereDate('created_at', date('Y-m-d'))->first();

        if (!$detalleHoy) {
            $this->notification = [
                'type' => 'danger',
                'message' => 'Debe registrar el desayuno y la cantidad servida antes de registrar estudiantes.'
            ];
            $this->showNotification();
            return;
        }

        $registradosHoy = Registro_diario::where('fecha_regis_diario_c', date('Y-m-d'))->count();

        if ($registradosHoy >= $detalleHoy->cantidad_servido) {
            $this->alertLimite = "Ya se alcanzó el límite de {$detalleHoy->cantidad_servido} raciones. No se pueden registrar más estudiantes.";
            $this->dispatch('notify-limite');
            return;
        }

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

            if ($is_register) {
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

            try {
                //Iniciamos las transacciones
                DB::beginTransaction();
                $personaPnf = PersonaPnf::where('id_persona_pnf', $persona->id_persona)->first();

                //Insertamos el registro diario
                DB::table('registro_diario_c')->insert([
                    'id_persona' => $persona->id_persona,
                    'id_persona_pnf' => $personaPnf->id_persona_pnf,
                    'fecha_regis_diario_c' => date('Y-m-d'),
                    'hora' => date('H:i:s'),
                ]);

                //Codigo para la parte del inventario


                //Aplicamos en la base de datos
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                //retornamos un mensaje de error
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

            //retornamos un mensaje de exito
            $this->notification = [
                'type' => 'success',
                'message' => "El estudiante {$persona->nombre_persona} {$persona->apellido_persona} se registro exitosamente!"
            ];

            $DatosHistorial['nombre'] = $persona->nombre_persona;
            $DatosHistorial['estado'] = 'Aprobado';
            $DatosHistorial['observacion'] = 'El estudiante se registro exitosamente';

            $this->dispatch('cedula-validada', datos: $DatosHistorial);
        } else {

            //retornamos un mensaje de error
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
        // usa el helper request(), NO la inyección por parámetro
        $buscar = request()->input('buscar');
        $fecha_desde = request()->input('fecha_desde');
        $fecha_hasta = request()->input('fecha_hasta');

        $filter = [
            'fecha_desde' => $fecha_desde ?? null,
            'fecha_hasta' => $fecha_hasta ?? null,
            'buscar'      => $buscar ?? null,
        ];

        $data = Registro_diario::showData($filter);
        $comidas = Receta::orderBy('id', 'desc')->where('estado', true)->get();


        // envía también $buscar a la vista (evita "undefined variable")
        return view('livewire.register-noti', [
            'data'   => $data,
            'buscar' => $buscar,
            'comidas' => $comidas,
        ]);
    }
}
