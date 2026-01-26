<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Services\AuthService;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Usuario;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function login(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = $request->input('email');
        $password = $request->input('password');

        $authService = new AuthService();
        $usuario = $authService->validateCredentials($username, $password);
        if (!$usuario) {
            return back()->withErrors(['email' => 'Credenciales inválidas.']);
        }

        $perfil = $usuario->perfil()->first();
        if ($perfil && $perfil->nombre_perfil === 'Administrador') {
            session(['pending_admin_id' => $usuario->id_usuario]);
            return redirect()->route('admin.configuracion.master_key.form');
        }

        AuthFacade::login($usuario);

        return redirect()->intended($this->redirectTo);
    }

    protected function authenticated(Request $request, $user)
    {
        try {
            $isAdmin = $user->roles()->where('nombre', 'Administrador')->exists();
        } catch (\Exception $e) {
            $isAdmin = ($user->role ?? '') === 'Administrador';
        }

        if ($isAdmin) {
            Auth::logout();
            session(['pending_admin_id' => $user->id_usuario ?? $user->id]);
            return redirect()->route('admin.configuracion.master_key.form');
        }

        return redirect()->intended($this->redirectPath());
    }
}
