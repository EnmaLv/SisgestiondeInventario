<?php

namespace App\Http\Controllers\salud;

use App\Models\salud\Medicamento;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\salud\CategoriaMedicamento;
use App\Http\Requests\MedicamentoRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\ExchangeRates;


class MedicamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categorias = CategoriaMedicamento::select('id', 'nombre')->where('estado', 1)->get();
        $medicamentos = Medicamento::listar(
            $request->input('buscar'),
            $request->input('estado', 1)
        );
        return view('admin.salud.maestros.medicamentos.index', compact('medicamentos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datos = Medicamento::getDatosFormulario();
        return view('admin.salud.maestros.medicamentos.create', $datos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicamentoRequest $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validated();

            if ($request->hasFile('imagen')) {
                $validated['imagen'] = $request->file('imagen')
                    ->store('imagenes/productos', 'public');
            } else {
                $validated['imagen'] = 'imagenes/productos/product-defect.webp';
            }

            $medicamentoId = Medicamento::crear($validated);

            $this->procesarTasaYPrecios($medicamentoId);

            DB::commit();

            $from = $request->input('from');

            if ($from) {
                return redirect($from . '?medicamentoId=' . $medicamentoId)
                    ->with('success', 'Medicamento creado exitosamente.');
            } else {
                return redirect()->route('admin.salud.maestros.medicamentos.index')
                    ->with('success', 'Medicamento creado y precio actualizado Exitosamente.');
            }
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Error al crear medicamento', ['error' => $e->getMessage()]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Error al crear el medicamento.');
        }
    }

    private function procesarTasaYPrecios(?int $medicamentoId = null)
    {
        $tasa = ExchangeRates::orderByDesc('fecha_vigencia')->first();

        if (!$tasa) {
            throw new \Exception('No existe una tasa registrada');
        }

        $query = Medicamento::with('precioMedicamento');

        if ($medicamentoId) {
            $query->where('id', $medicamentoId);
        }

        $medicamentos = $query->get();

        foreach ($medicamentos as $medicamento) {

            if (!$medicamento->precioMedicamento) {
                continue;
            }

            $precioUSD =
                $medicamento->precioMedicamento->costo_usd ??
                $medicamento->precioMedicamento->precio_usd ??
                0;

            if ($precioUSD <= 0) {
                continue;
            }

            $margen = $medicamento->precioMedicamento->margen ?? 0;

            $precioBs = round(
                $precioUSD * (1 + $margen / 100) * $tasa->promedio,
                2
            );

            DB::table('medicamentos')
                ->where('id', $medicamento->id)
                ->update([
                    'precio_compra' => $precioBs,
                    'updated_at'    => now()
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Medicamento $medicamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Medicamento $medicamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Medicamento $medicamento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Medicamento $medicamento)
    {
        //
    }
}
