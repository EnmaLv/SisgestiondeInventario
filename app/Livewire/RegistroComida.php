<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Receta;
use App\Models\DetalleRegistroDiario;
use App\Models\InventarioSedeLote;
use App\Models\MovimientoInventario;
use Illuminate\Support\Facades\DB;
use Exception;

class RegistroComida extends Component
{
    public $desayunos_agregados = [];
    public $showNotification = false;
    public $notification = ['type' => 'success', 'message' => ''];
    public $desayuno_registrado = false;
    public $horarioPermitido;
    public $alertInventario = null;
    public $alertLimite = null;

    public function updated($property)
    {
        if (str_starts_with($property, 'desayunos_agregados')) {
            $this->validateOnly(
                $property,
                $this->rulesRealtime()
            );
        }

        if (str_contains($property, 'receta_id')) {
            $ids = array_filter(array_column($this->desayunos_agregados, 'receta_id'));

            if (count($ids) !== count(array_unique($ids))) {
                $this->addError('duplicado', 'No puede seleccionar la misma receta más de una vez.');
            } else {
                $this->resetErrorBag('duplicado');
            }
        }
    }

    protected function rulesRealtime(): array
    {
        $rules = [];

        foreach ($this->desayunos_agregados as $i => $item) {
            $rules["desayunos_agregados.$i.receta_id"] = 'required|exists:recetas,id';
            $rules["desayunos_agregados.$i.cantidad"] = 'required|numeric|min:1';
        }

        return $rules;
    }

    public function mount()
    {
        $this->checkDesayunoStatus();
        if (empty($this->desayunos_agregados)) {
            $this->addDesayuno();
        }
    }

    public function checkDesayunoStatus()
    {
        $hoy = now()->toDateString();
        $registroHoy = DetalleRegistroDiario::whereDate('created_at', $hoy)->exists();

        $hora = now()->format('H:i');
        $this->horarioPermitido = $hora >= '00:00' && $hora <= '23:59';
        $this->desayuno_registrado = $registroHoy;
        if ($this->desayuno_registrado) {
            $detalles = DetalleRegistroDiario::whereDate('created_at', $hoy)->get(['receta_id', 'cantidad_servido']);
            $this->desayunos_agregados = $detalles->map(function ($item) {
                return ['receta_id' => $item->receta_id, 'cantidad' => $item->cantidad_servido];
            })->toArray();
        }
    }

    public function addDesayuno()
    {
        if ($this->desayuno_registrado) return;

        $this->desayunos_agregados[] = [
            'receta_id' => null,
            'cantidad' => null,
        ];

        $this->resetErrorBag();
    }


    public function removeDesayuno($index)
    {
        if ($this->desayuno_registrado) return;

        unset($this->desayunos_agregados[$index]);
        $this->desayunos_agregados = array_values($this->desayunos_agregados);

        $this->resetErrorBag();
    }

    public function saveDesayuno()
    {
        $hora = now()->format('H:i');
        if (!($hora >= '00:00' && $hora <= '23:59')) {
            return;
        }

        if (DetalleRegistroDiario::whereDate('created_at', now()->toDateString())->exists()) {
            $this->addError('existe', 'El desayuno de hoy ya fue registrado.');
            return;
        }

        $rules = [];
        $messages = [];

        if (empty(array_filter($this->desayunos_agregados, fn($d) => $d['receta_id'] !== null))) {
            $this->addError('general', 'Debe seleccionar al menos un desayuno con su cantidad.');
            return;
        }

        foreach ($this->desayunos_agregados as $i => $registro) {
            $rules['desayunos_agregados.' . $i . '.receta_id'] = 'required|numeric|exists:recetas,id';
            $rules['desayunos_agregados.' . $i . '.cantidad'] = 'required|numeric|min:1';

            $messages['desayunos_agregados.' . $i . '.receta_id.required'] = "Seleccione una opción para el Desayuno #" . ($i + 1);
            $messages['desayunos_agregados.' . $i . '.cantidad.required'] = "Ingrese la cantidad para el Desayuno #" . ($i + 1);
            $messages['desayunos_agregados.' . $i . '.cantidad.min'] = "La cantidad debe ser 1 o superior para el Desayuno #" . ($i + 1);
        }

        $recetaIds = array_filter(array_column($this->desayunos_agregados, 'receta_id'));

        if (!empty($recetaIds) && count($recetaIds) !== count(array_unique($recetaIds))) {
            $this->addError('duplicado', 'No puede seleccionar la misma receta más de una vez. Por favor, elimine el registro duplicado.');
            return;
        }

        $this->validate($rules, $messages);

        DB::beginTransaction();

        try {
            $sedeId = 1;

            foreach ($this->desayunos_agregados as $registro) {

                $recetaId = $registro['receta_id'];
                $cantidadServido = $registro['cantidad'];

                DetalleRegistroDiario::create([
                    'receta_id' => $recetaId,
                    'cantidad_servido' => $cantidadServido,
                    'fecha' => now(),
                ]);

                $receta = Receta::with('recetaIngredientes.producto')->find($recetaId);

                foreach ($receta->recetaIngredientes as $ingrediente) {

                    $totalDescontarGramos = $ingrediente->cantidad_gramos * $cantidadServido;
                    $pesoUnidad = $ingrediente->producto->peso_contenido;

                    if ($pesoUnidad <= 0) {
                        throw new Exception("El producto {$ingrediente->producto->nombre} no tiene peso_contenido definido.");
                    }

                    $lotes = InventarioSedeLote::where('sede_id', $sedeId)
                        ->whereHas('lote', function ($q) use ($ingrediente) {
                            $q->where('producto_id', $ingrediente->producto_id)
                                ->whereDate('fecha_vencimiento', '>=', now()->toDateString())
                                ->where('estado', 1);
                        })
                        ->where('cantidad_gramos', '>', 0)
                        ->orderBy('lote_id', 'asc')
                        ->get();


                    $pendiente = $totalDescontarGramos;

                    foreach ($lotes as $inv) {

                        if ($pendiente <= 0) break;

                        $dispGramos = $inv->cantidad_gramos;
                        $tomarGramos = min($pendiente, $dispGramos);

                        $inv->cantidad_gramos -= $tomarGramos;
                        $inv->cantidad = floor($inv->cantidad_gramos / $pesoUnidad);

                        $inv->save();

                        $lote = $inv->lote;
                        $lote->cantidad_inicial = floor($lote->cantidad_inicial - ($tomarGramos / $pesoUnidad));
                        $lote->cantidad_actual = $inv->cantidad_gramos;
                        $lote->save();

                        MovimientoInventario::create([
                            'producto_id'    => $ingrediente->producto_id,
                            'lote_id'        => $lote->id,
                            'sede_id'        => $sedeId,
                            'tipo_movimiento' => 'SALIDA',
                            'unidad_id'      => $ingrediente->unidad_id,
                            'cantidad'       => floor($tomarGramos / $pesoUnidad),
                            'cantidad_gramos' => $tomarGramos,
                            'fecha'          => now(),
                            'observaciones'  => "Consumo por receta {$receta->nombre} ({$cantidadServido} raciones)"
                        ]);

                        $pendiente -= $tomarGramos;
                    }

                    if ($pendiente > 0) {
                        throw new Exception("No hay suficiente inventario para el ingrediente: {$ingrediente->producto->nombre}. Faltan " . round($pendiente, 2) . " gramos.");
                    }
                }
            }

            DB::commit();

            $this->desayuno_registrado = true;
            $this->dispatch('swal', [
                'title' => '¡Exito!',
                'text'  => 'Los desayunos del día fueron guardados Exitosamente.',
                'icon'  => 'success'
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            $this->alertInventario = $e->getMessage();
            $this->dispatch('notify-inventario');
            $this->desayuno_registrado = false;
            $this->showNotification = true;
            return;
        }
    }

    protected function validationAttributes(): array
    {
        $attributes = [];

        foreach ($this->desayunos_agregados as $i => $registro) {
            $attributes["desayunos_agregados.$i.receta_id"] = 'desayuno #' . ($i + 1);
            $attributes["desayunos_agregados.$i.cantidad"] = 'cantidad del desayuno #' . ($i + 1);
        }

        return $attributes;
    }

    public function showNotification()
    {
        $this->showNotification = true;
    }

    public function render()
    {
        $buscar = request()->input('buscar');

        $data = DetalleRegistroDiario::with('receta')->paginate(10);
        $comidas = Receta::orderBy('id', 'desc')->where('estado', true)->get();

        return view('livewire.registro-comida', [
            'data'    => $data,
            'buscar'  => $buscar,
            'comidas' => $comidas,
            'desayuno_registrado' => $this->desayuno_registrado,
        ]);
    }
}