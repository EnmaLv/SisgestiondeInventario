<?php

namespace App\Http\Controllers\beca;

use App\Http\Requests\beca\GuardarJornadaRequest;

use App\Http\Controllers\Controller;
use App\Models\Becas\JornadaBeca;
use Illuminate\Http\Request;
use App\Services\becas\JornadaBecasServices;
use App\Models\Becas\Lapso;
use App\Models\Becas\Beneficio;

class JornadaBecaController extends Controller
{
    protected JornadaBecasServices $JornadaServices;
    
    public function __construct(JornadaBecasServices $Jornadaserices)
    {
        $this->JornadaServices = $Jornadaserices;
    }

    //Funcion para mostrar la vista principal de las jornadas
    public function index()
    {
        return $this->JornadaServices->index();
    }

    //Funcion para mostrar la vista para crear una nueva jornada
    public function create()
    {
        $beneficios = Beneficio::where('status', 1)->get();
        $lapsos = Lapso::all();
        return view('admin.becas.jornada.create', compact('beneficios', 'lapsos'));
    }

    //Funcion para guardar la jornada
    public function store(GuardarJornadaRequest $request)
    {
        //Validamos los campos de la jornada
        $validated = $request->validated();

        try {
            //Enviamos los datos al services
            $this->JornadaServices->crearJornada($validated);

            //Recargamos la cache para saber las jornadas activas
            $this->JornadaServices->obtenerJornadaActiva();
            return redirect()->route('admin.becas.jornada.index')->with('success', 'Jornada creada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al crear la jornada: ' . $e->getMessage());
        }
    }

    //Funcion que muestra la vista de editar jornada
    public function edit(int $id)
    {
        $jornada = JornadaBeca::findOrFail($id);
        $beneficios = Beneficio::where('status', 1)->get();
        $lapsos = Lapso::all();

        return view('admin.becas.jornada.edit', compact('jornada', 'beneficios', 'lapsos'));
    }

    //Funcion encargada en actualizar la jornada
    public function update(GuardarJornadaRequest $request,int $id)
    {
        $validated = $request->validated();

        try {
            $this->JornadaServices->actualizarJornada($id, $validated);
            //Recargamos la cache para saber las jornadas activas
            $this->JornadaServices->obtenerJornadaActiva();

            return redirect()->route('admin.becas.jornada.index')->with('success', 'Jornada actualizada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error al actualizar la jornada: ' . $e->getMessage());
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->JornadaServices->desactivarJornada($id);
            //Recargamos la cache para saber las jornadas activas
            $this->JornadaServices->obtenerJornadaActiva();

            return redirect()->route('admin.becas.jornada.index')->with('success', 'Jornada inactivada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.becas.jornada.index')->with('error', 'Error al inactivar la jornada: ' . $e->getMessage());
        }
    }

    public function activar($id)
    {
        try {
            $this->JornadaServices->activarJornada($id);
            //Recargamos la cache para saber las jornadas activas
            $this->JornadaServices->obtenerJornadaActiva();

            return redirect()->route('admin.becas.jornada.index')->with('success', 'Jornada activada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('admin.becas.jornada.index')->with('error', 'Error al activar la jornada: ' . $e->getMessage());
        }
    }
}
