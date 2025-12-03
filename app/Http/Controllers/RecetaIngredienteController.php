<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Receta;
use App\Models\Producto;
use App\Models\Unidad;
use App\Models\RecetaIngrediente;
use Illuminate\Support\Facades\DB;

class RecetaIngredienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $buscar = $request->input('buscar');

        // Consulta base: cargar recetas con ingredientes y productos
        $query = Receta::with(['recetaIngredientes.producto', 'recetaIngredientes.unidad']);

        // Buscador por nombre de receta
        if ($buscar) {
            $query->where('nombre', 'like', "%{$buscar}%");
        }

        // Paginamos recetas (no ingredientes)
        $recetas = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.maestros.receta_ingredientes.index', compact('recetas', 'buscar'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $recetas = Receta::all();
        $productos = Producto::all();
        $unidades = Unidad::all();
        return view('admin.maestros.receta_ingredientes.create', compact('recetas', 'productos', 'unidades'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'recetas_id' => 'required|exists:recetas,id',

            // arrays
            'producto_id' => 'required|array|min:1',
            'producto_id.*' => 'required|exists:productos,id',

            'cantidad_porcion' => 'required|array',
            'cantidad_porcion.*' => 'required|numeric|min:0.0001',

            'unidad_id' => 'required|array',
            'unidad_id.*' => 'required|exists:unidades,id',
        ]);

        // Guardar múltiples ingredientes en transacción
        DB::beginTransaction();
        try {
            foreach ($validated['producto_id'] as $index => $productoId) {
                $cantidad = $validated['cantidad_porcion'][$index] ?? null;
                $unidadId = $validated['unidad_id'][$index] ?? null;

                $unidad = Unidad::find($unidadId);
                $cantidadGramos = $cantidad * $unidad->factor_a_gramo;


                // seguridad: saltar si faltan datos
                if (!$cantidad || !$unidadId) continue;

                RecetaIngrediente::create([
                    'recetas_id' => $validated['recetas_id'],
                    'producto_id' => $productoId,
                    'cantidad_porcion' => $cantidad,
                    'cantidad_gramos' => $cantidadGramos,
                    'unidad_id' => $unidadId,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.maestros.receta_ingredientes.index')->with('success', 'Ingredientes agregados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($recetaId)
    {
        $recetaIngrediente = RecetaIngrediente::with(['producto', 'unidad', 'receta'])
            ->findOrFail($recetaId);

        $recetas = Receta::all();
        $productos = Producto::all();
        $unidades = Unidad::all();

        return view('admin.maestros.receta_ingredientes.edit', compact(
            'recetaIngrediente',
            'recetas',
            'productos',
            'unidades'
        ));
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $recetaId)
    {
        $validated = $request->validate([
            'recetas_id' => 'required|exists:recetas,id',
            'producto_id' => 'required|array|min:1',
            'producto_id.*' => 'required|exists:productos,id',
            'cantidad_porcion' => 'required|array',
            'cantidad_porcion.*' => 'required|numeric|min:0.0001',
            'unidad_id' => 'required|array',
            'unidad_id.*' => 'required|exists:unidades,id',
        ]);

        // seguridad: ensure ruta y form coinciden
        if ((int)$validated['recetas_id'] !== (int)$recetaId) {
            return redirect()->back()->withInput()->with('error', 'Id de receta inválido.');
        }

        DB::beginTransaction();
        try {
            // 1) eliminar ingredientes actuales de la receta
            RecetaIngrediente::where('recetas_id', $recetaId)->delete();

            // 2) insertar los nuevos (o los mismos)
            foreach ($validated['producto_id'] as $index => $productoId) {
                $cantidad = $validated['cantidad_porcion'][$index] ?? null;
                $unidadId = $validated['unidad_id'][$index] ?? null;

                $unidad = Unidad::find($unidadId);
                $cantidadGramos = $cantidad * $unidad->factor_a_gramo;


                if (!$cantidad || !$unidadId) continue;

                RecetaIngrediente::create([
                    'recetas_id' => $recetaId,
                    'producto_id' => $productoId,
                    'cantidad_porcion' => $cantidad,
                    'cantidad_gramos' => $cantidadGramos,
                    'unidad_id' => $unidadId,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.maestros.receta_ingredientes.index')
                ->with('success', 'Ingredientes de la receta actualizados correctamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $ingrediente = RecetaIngrediente::findOrFail($id);
        $ingrediente->delete();

        return redirect()->route('admin.maestros.receta_ingredientes.index')->with('success', 'Ingrediente de receta eliminado exitosamente.');
    }
}
