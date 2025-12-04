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

    public $alertInventario = null;
    public $alertLimite = null;



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
        $this->horarioPermitido = $hora >= '00:00' && $hora <= '22:00';

        if ($registroHoy) {
            // Ya existe → bloquear inputs
            $this->desayuno_registrado = true;
            $this->desayuno_del_dia = $registroHoy->receta_id;
            $this->cantidad_servido = $registroHoy->cantidad_servido;
        } else {
            // No existe desayuno todavía
            $this->desayuno_registrado = false;
        }
    }




    public function saveDesayuno()
    {
        $hora = now()->format('H:i');

        if (!($hora >= '00:00' && $hora <= '22:00')) {
            $this->addError('hora', 'Solo puede registrar desayuno entre 12:00am y 12:00pm.');
            $this->desayuno_registrado = false;
            return;
        }

        $this->validate([
            'desayuno_del_dia' => 'required|numeric',
            'cantidad_servido' => 'required|numeric|min:1'
        ]);

        $hoy = now()->toDateString();

        if (DetalleRegistroDiario::whereDate('created_at', $hoy)->exists()) {
            $this->addError('existe', 'El desayuno de hoy ya fue registrado.');
            $this->desayuno_registrado = false;
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

                // Total en gramos a descontar
                $totalDescontarGramos = $ingrediente->cantidad_porcion * $this->cantidad_servido;

                // Peso de una unidad del producto (en gramos)
                $pesoUnidad = $ingrediente->producto->peso_contenido;

                if ($pesoUnidad <= 0) {
                    throw new Exception("El producto {$ingrediente->producto->nombre} no tiene peso_contenido definido.");
                }

                // LOTES FIFO (por sucursal)
                $lotes = InventarioSucursalLote::where('sucursal_id', $sucursalId)
                    ->whereHas('lote', function ($q) use ($ingrediente) {
                        $q->where('producto_id', $ingrediente->producto_id);
                    })
                    ->where('cantidad_gramos', '>', 0)
                    ->orderBy('id', 'asc')
                    ->get();

                $pendiente = $totalDescontarGramos;

                foreach ($lotes as $inv) {

                    if ($pendiente <= 0) break;

                    // Gramos disponibles en este lote
                    $dispGramos = $inv->cantidad_gramos;

                    // Tomar lo menor entre lo que falta y lo disponible
                    $tomarGramos = min($pendiente, $dispGramos);

                    // Descontar gramos
                    $inv->cantidad_gramos -= $tomarGramos;

                    // Recalcular unidades: unidades = floor( gramos / peso_unitario )
                    $inv->cantidad = floor($inv->cantidad_gramos / $pesoUnidad);

                    $inv->save();

                    // Descontar del LOTE original también
                    $lote = $inv->lote;
                    $lote->cantidad_inicial = $inv->cantidad;
                    $lote->cantidad_actual = $inv->cantidad_gramos;
                    $lote->save();

                    // Registrar movimiento
                    MovimientoInventario::create([
                        'producto_id'    => $ingrediente->producto_id,
                        'lote_id'        => $lote->id,
                        'sucursal_id'    => $sucursalId,
                        'tipo_movimiento' => 'SALIDA',
                        'unidad_id'      => $ingrediente->unidad_id,
                        'cantidad'       => $inv->cantidad,
                        'cantidad_gramos' => $tomarGramos,
                        'fecha'          => now(),
                        'observaciones'  => 'Consumo por receta: ' . $receta->nombre
                    ]);

                    // actualizar lo que falta por descontar
                    $pendiente -= $tomarGramos;
                }

                if ($pendiente > 0) {
                    throw new Exception("No hay suficiente inventario para esta receta");
                }
            }


            DB::commit();

            $this->desayuno_registrado = true;
            $this->dispatch('swal', [
                'title' => '¡Desayuno registrado!',
                'text'  => 'El desayuno del día fue guardado correctamente.',
                'icon'  => 'success'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            // Aquí se activa el mensaje
            $this->alertInventario = $e->getMessage();
            $this->dispatch('notify-inventario');

            $this->desayuno_registrado = false;

            // Mostrar notificación
            $this->showNotification = true;
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
