<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;

class PersonaController extends Controller
{
    public function index(Request $request)
    {
        //En el caso de que tenga una busqueda
        
        $query = Persona::select('id_persona','nombre_persona', 'apellido_persona','email_persona', 'id_perfil');
        
        if ($request->has('buscar') && $request->buscar != '') {
            $query->where('nombre_persona', 'like', '%' . $request->buscar . '%')
                  ->orWhere('apellido_persona', 'like', '%' . $request->buscar . '%');
        }
        
        $personas = $query->paginate(10);
        return view('admin.configuracion.persona.index', compact('personas'));
    }
    
    public function create()
    {
        return view('admin.configuracion.persona.create');
    }

    public function edit($id)
    {
        return view('admin.configuracion.persona.edit', compact('id'));
    }

    public function show($id)
    {
        return view('admin.configuracion.persona.show', compact('id'));
    }
    
}
