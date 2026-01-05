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
use App\Models\SobranteComedor;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;
use Exception;
use Livewire\WithPagination;

use function Symfony\Component\Clock\now;

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

    public $enableInput = true;
    public $showBtnFinalizar = true;

    public $alertInventario = null;
    public $alertLimite = null;


    //Funcion para pasar datos al form de finalizar
    public $fecha;
    public $sobrante;
    public $motivo;
    public $accion;
    //Saber cuantos es la cantidad de estudiantes que se registraron
    public $registradosHoy;
    public function finalizarDia()
    {
        //Verificamos en bd cuantos estudiantes de registraron
        $validated = $this->validate([
            'fecha' => 'required|date',
            'sobrante' => 'required|numeric',
            'motivo' => 'required|string',
            'accion' => 'required|string'
        ], [
            'fecha.required' => 'La fecha es requerida',
            'fecha.date' => 'La fecha no es válida',
            'sobrante.required' => 'La cantidad sobrante es requerida',
            'sobrante.numeric' => 'La cantidad sobrante debe ser un número',
            'motivo.required' => 'El motivo es requerido',
            'accion.required' => 'La acción es requerida'
        ]);

        //Guardamos los datos en la tabla de sobrantes 

        SobranteComedor::create([
            'fecha' => $validated['fecha'],
            'cantidad_sobrante' => $validated['sobrante'],
            'motivo' => $validated['motivo'],
            'accion_tomada' => $validated['accion'],
            'created_at' => now(),
            'updated_at' => now()
        ]);

        //Hacemos innaccesible el formulario
        $this->enableInput = false;

        //Ocultamos el botón de finalizar
        $this->showBtnFinalizar = false;


        //Al finalizar enviar un evento con el resultado
        $this->dispatch('finalizar-dia-guardado', [
            'icon' => 'success',
            'title' => 'Cierre de jornada registrado',
            'text' => 'El cierre de jornada se ha registrado Exitosamente.'
        ]);
    }

    private function recalcularSobrante()
    {
        $registradosHoy = Registro_diario::where('fecha_regis_diario_c', date('Y-m-d'))->count();
        $desayunoDelDia = DetalleRegistroDiario::where('fecha', now()->format('Y-m-d'))->get();

        if ($desayunoDelDia->isEmpty()) {
            $this->sobrante = 0;
            return;
        }

        $desayunoTotal = $desayunoDelDia->sum('cantidad_servido');

        // El sobrante es el total preparado menos los que ya comieron
        $this->sobrante = $desayunoTotal - $registradosHoy;
    }

    public function openModal()
    {
        $this->dispatch('openModal');
    }


    public function save()
    {



        // 🚨 Validación de límite de raciones
        $detalleHoy = DetalleRegistroDiario::whereDate('created_at', now())->first();

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
                //Actualizamos el sobrante
                $this->recalcularSobrante();
                //Si el sobrante queda en 0, ocultamos el boton de finalizar
                if ($this->sobrante == 0) {
                    $this->showBtnFinalizar = false;
                    $this->enableInput = false;
                }
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

    //Verificar al montar el componente
    public function mount()
    {
        //Verifica si ya se registro un cierre de jornada hoy
        $cierreHoy = DB::table('sobrantes_comedor')->whereDate('fecha', now()->format('Y-m-d'))->exists();

        //Inhabilitar el registro si se hizo un cierre
        if ($cierreHoy) {
            $this->enableInput = false;
            $this->showBtnFinalizar = false;
            return;
        }

        //Cargamos los sobrantes
        $this->recalcularSobrante();

        //Cargamos datos
        $this->fecha = now()->format('Y-m-d');

        //Si hay alimentos sobrantes mostrar el boton de finalizar día
        if ($this->sobrante > 0) {
            $this->showBtnFinalizar = true;
        }
    }
    public function render()
    {
        $receta_diario = DetalleRegistroDiario::whereDate('created_at', now())->exists();
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
            "receta_diario" => $receta_diario,
        ]);
    }
}
