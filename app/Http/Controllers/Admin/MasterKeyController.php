<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConfiguracionSistema;
use Illuminate\Support\Facades\Auth;

class MasterKeyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showForm()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Administrador') {
            abort(403);
        }

        return view('admin.master_key_manage');
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'Administrador') {
            abort(403);
        }

        $request->validate([
            'current_master_key' => 'required|string',
            'new_master_key' => 'required|string|min:6|confirmed',
        ]);

        if (!ConfiguracionSistema::checkMasterKey($request->current_master_key)) {
            return back()->withErrors(['current_master_key' => 'Llave maestra actual inválida.']);
        }

        ConfiguracionSistema::updateMasterKey($request->new_master_key);
        return back()->with('status', 'Llave maestra actualizada Exitosamente.');
    }
}
