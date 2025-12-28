<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Services\AuthService;
use Illuminate\Support\Facades\Auth as AuthFacade;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * Override login to use new usuario table and AuthService
     */
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

        // If usuario is admin, start second step: ask for master key
        $perfil = $usuario->perfil()->first();
        if ($perfil && $perfil->nombre_perfil === 'Administrador') {
            session(['pending_admin_id' => $usuario->id_usuario]);
            return redirect()->route('admin.master_key.form');
        }

        // Non-admin: log in the legacy user (if exists) and redirect
        $legacy = \App\Models\User::where('email', $username)->first();
        if ($legacy) {
            AuthFacade::login($legacy);
        }

        return redirect()->intended($this->redirectTo);
    }

    /**
     * After user credentials are validated and user is authenticated.
     * If user is Administrator, require master key verification before granting access.
     */
    protected function authenticated(Request $request, $user)
    {
        if ($user->role === 'Administrador') {
            // Log out the session and require master key step
            Auth::logout();
            session(['pending_admin_id' => $user->id]);
            return redirect()->route('admin.master_key.form');
        }

        return redirect()->intended($this->redirectPath());
    }
}
