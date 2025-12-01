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

class RegisterNoti extends Component
{
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
    


    public function mount()
    {
        $this->checkDesayunoStatus();
    }

    public function checkDesayunoStatus()
    {
        $hoy = now()->toDateString();

        // Buscar desayuno guardado hoy
        $registroHoy = DetalleRegistroDiario::whereDate('created_at', $hoy)->first();

        $hora = now()->format('H:i');
        $this->horarioPermitido = $hora >= '09:00' && $hora <= '22:00';

        if ($registroHoy) {
            // Ya existe → bloquear y cargar los datos
            $this->desayuno_registrado = true;
            $this->desayuno_del_dia = $registroHoy->receta_id;
            $this->cantidad_servido = $registroHoy->cantidad_servido;
        } else {
            // Aún no existe → permitir solo si está en horario
            $this->desayuno_registrado = !$this->horarioPermitido; // si NO está en horario → bloquear
        }
    }



    public function saveDesayuno()
    {
        $hora = now()->format('H:i');

        if (!($hora >= '09:00' && $hora <= '22:00')) {
            $this->addError('hora', 'Solo puede registrar desayuno entre 09:00am y 10:00pm.');
            return;
        }

        $this->validate([
            'desayuno_del_dia' => 'required|numeric',
            'cantidad_servido' => 'required|numeric|min:1'
        ]);

        $hoy = now()->toDateString();

        if (DetalleRegistroDiario::whereDate('created_at', $hoy)->exists()) {
            $this->addError('existe', 'El desayuno de hoy ya fue registrado.');
            return;
        }

        DB::beginTransaction();

        try {

            // GUARDAR EL DESAYUNO DEL DÍA
            $detalle = DetalleRegistroDiario::create([
                'receta_id' => $this->desayuno_del_dia,
                'cantidad_servido' => $this->cantidad_servido,
            ]);

            // CARGAR LA RECETA Y SUS INGREDIENTES
            $receta = Receta::with('recetaIngredientes.producto')->find($this->desayuno_del_dia);

            // SUCURSAL FIJA
            $sucursalId = 1; 

            // PROCESAR CADA INGREDIENTE
            foreach ($receta->recetaIngredientes as $ingrediente) {

                $total_descontar = $ingrediente->cantidad_porcion * $this->cantidad_servido;

                // TRAER LOTES FIFO
                $lotes = Lote::where('producto_id', $ingrediente->producto_id)
                            ->where('cantidad_actual', '>', 0)
                            ->orderBy('fecha_entrada', 'asc')
                            ->get();

                $cantidadPendiente = $total_descontar;

                foreach ($lotes as $lote) {

                    if ($cantidadPendiente <= 0) break;

                    // Referencia al inventario por lote de la SUCURSAL
                    $inventarioSucursal = InventarioSucursalLote::where('lote_id', $lote->id)
                        ->where('sucursal_id', $sucursalId)
                        ->first();

                    if (!$inventarioSucursal || $inventarioSucursal->cantidad <= 0) {
                        continue; // pasa al siguiente lote
                    }

                    // Cantidad disponible en sucursal
                    $dispSucursal = $inventarioSucursal->cantidad;

                    // Cantidad disponible total del lote
                    $dispLote = $lote->cantidad_actual;

                    // TOMAR LO QUE SE PUEDA DEL LOTE
                    $tomar = min($cantidadPendiente, $dispSucursal, $dispLote);

                    // Actualizar LOTE
                    $lote->cantidad_actual -= $tomar;
                    $lote->save();

                    // Actualizar INVENTARIO DE LA SUCURSAL
                    $inventarioSucursal->cantidad -= $tomar;
                    $inventarioSucursal->save();

                    // REGISTRAR MOVIMIENTO
                    MovimientoInventario::create([
                        'producto_id'    => $ingrediente->producto_id,
                        'lote_id'        => $lote->id,
                        'sucursal_id'    => $sucursalId,
                        'tipo_movimiento'=> 'SALIDA',
                        'unidad_id'      => $ingrediente->unidad_id,
                        'cantidad'       => $tomar,
                        'fecha'          => now(),
                        'observaciones'  => 'Consumo por desayuno: ' . $receta->nombre
                    ]);

                    // Reducir lo que falta por descontar
                    $cantidadPendiente -= $tomar;
                }

                if ($cantidadPendiente > 0) {
                    throw new Exception("Inventario insuficiente en la sucursal para el producto: {$ingrediente->producto->nombre}");
                }
            }

            DB::commit();

            $this->desayuno_registrado = true;
            $this->dispatch('notify-saved');

        } catch (Exception $e) {
            DB::rollBack();
            $this->addError('inventario', $e->getMessage());
            return;
        }
    }



    


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
                //Iniciamos las transacciones
                DB::beginTransaction();
                $personaPnf = PersonaPnf::where('id_persona_pnf', $persona->id_persona)->first();
                   
                //Insertamos el registro diario
                DB::table('registro_diario_c')->insert([
                    'id_persona' => $persona->id_persona,
                    'id_persona_pnf' => $personaPnf->id_persona_pnf ,
                    'fecha_regis_diario_c' => date('Y-m-d'),
                    'hora' => date('H:i:s'),
                ]);

                //Codigo para la parte del inventario


                //Aplicamos en la base de datos
                DB::commit();
            }catch(Exception $e){
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
        $comidas = Receta::all();


        // envía también $buscar a la vista (evita "undefined variable")
        return view('livewire.register-noti', [
            'data'   => $data,
            'buscar' => $buscar,
            'comidas' => $comidas,
        ]);
    }

}
