<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;

class AdminMasterKeyController extends Controller
{
    public function showForm()
    {
        if (!Auth::check() && !session('pending_admin_id')) {
            return redirect()->route('login');
        }

        return view('auth.admin_master_key');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'master_key' => ['required', 'string'],
        ]);

        $id = session('pending_admin_id') ?: Auth::id();
        if (!$id) {
            return redirect()->route('login')->withErrors(['email' => 'Session expired, please login again.']);
        }

        $user = Usuario::find($id);
        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'User not found.']);
        }

        if ($user->verifyMasterKey($request->input('master_key'))) {
            // clear pending and login
            session()->forget('pending_admin_id');
            Auth::loginUsingId($user->id_usuario ?? $user->id);
            $request->session()->regenerate();
            return redirect()->intended('/home');
        }

        return back()->withErrors(['master_key' => 'Llave maestra incorrecta.']);
    }
}
