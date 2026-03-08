<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Modulo;
use Illuminate\Support\Facades\DB;

class ModuloController extends Controller
{
    public function seleccionarForm()
    {
        $user = Auth::user();
        $rolId = $user->id_perfil ?? null;
        $modulos = Modulo::where('activo', true)->get();

        return view('admin.modulos.seleccionar', compact('modulos'));
    }

    public function cambiar(Request $request)
    {
        $request->validate(['modulo' => 'required|string']);

        $moduloKey = $request->input('modulo');

        // Verificar que el módulo existe y está activo
        $modulo = Modulo::where('key', $moduloKey)->where('activo', true)->first();
        if (! $modulo) {
            return redirect()->back()->with('error', 'Módulo no válido.');
        }

        // Verificar que el usuario tiene acceso a ese módulo
        $permitidos = session('modulos_permitidos', []);
        if (! in_array($moduloKey, $permitidos)) {
            return redirect()->back()->with('error', 'No tienes acceso a ese módulo.');
        }

        session(['modulo_activo' => $modulo->key]);

        return redirect()->route('home')->with('success', 'Módulo cambiado a: ' . $modulo->nombre);
    }
}
