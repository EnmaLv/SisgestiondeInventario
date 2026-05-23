<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Usuario;
use App\Models\Rol;

use App\Services\AuthService;
use App\Models\ConfiguracionSistema;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers {
        register as traitRegister;
    }

    /**
     * Where to redirect users after registration.
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

        // Allow showing the registration form even if a user is authenticated
        // (prevents redirect to /home when clicking the register link while logged in)
        // Allow guests to access showRegistrationForm; also allow authenticated admins
        // to submit the registration form, so we exclude the 'register' action
        // from the guest middleware and handle auth checks in the method.
        $this->middleware('guest')->except(['showRegistrationForm', 'register']);
    }

    /**
     * Show the application registration form.
     * Only allow access to this page when either no Administrador exists (first-time setup)
     * or the current user is an authenticated administrator.
     */
    public function showRegistrationForm()
    {
        $roles = collect(); // Inicializar colección vacía por defecto

        if ($this->adminExists()) {
            if (! auth()->check() || ! $this->isAdmin(auth()->user())) {
                abort(403, 'Acceso restringido');
            }
            // Cargamos todos los roles disponibles para que el administrador elija
            $roles = Rol::all();
        }

        return view('auth.register', compact('roles'));
    }

    /**
     * Override register to allow authenticated administrators to register employees.
     */
    public function register(Request $request)
    {
        // If the request is from an authenticated user, only allow if they're an admin
        // If an administrator already exists, block unauthenticated users from registering
        if (! auth()->check() && $this->adminExists()) {
            abort(403, 'Acceso restringido');
        }

        if (auth()->check()) {
            if (! $this->isAdmin(auth()->user())) {
                abort(403, 'No autorizado');
            }

            // For admin-created employees: validate, create, fire Registered event,
            // but DO NOT log in as the created user. Redirect back to employees list.
            $this->validator($request->all())->validate();
            $user = $this->create($request->all());
            event(new Registered($user));

            return redirect()->route('admin.configuracion.empleados.index');
        }

        return $this->traitRegister($request);
    }

    /**
     * Returns true if at least one Administrador role/user exists in the system.
     */
    private function adminExists()
    {
        try {
            $adminRol = Rol::where('nombre', 'Administrador')->first();
            $adminCount = $adminRol ? $adminRol->usuarios()->count() : 0;
        } catch (\Throwable $e) {
            $adminCount = Usuario::join('perfil', 'usuario.id_perfil', '=', 'perfil.id_perfil')
                ->where('perfil.nombre_perfil', 'Administrador')
                ->count();
        }

        return ($adminCount > 0);
    }

    /**
     * Determine if a user is an administrator (checks both `role` field and roles relation).
     */
    private function isAdmin($user)
    {
        if (! $user) return false;

        $roleField = strtolower($user->role ?? '');
        if ($roleField === 'administrador') return true;

        try {
            if ($user->roles()->whereRaw("LOWER(nombre) = ?", ['administrador'])->exists()) {
                return true;
            }
        } catch (\Throwable $e) {
            // ignore and fallback
        }

        return false;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:255'],
            'first_lastname' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'digits_between:1,8'],
            'telefono' => ['nullable', 'regex:/^\d{4}-\d{7}$/'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:usuario,username'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'security_questions' => ['required', 'array', 'size:2'],
            'security_questions.*.question' => ['required', 'string'],
            'security_questions.*.answer' => ['required', 'string'],
        ];

        // Verificar existencias de administradores para reglas dinámicas
        try {
            $adminRol = Rol::where('nombre', 'Administrador')->first();
            $adminCount = $adminRol ? $adminRol->usuarios()->count() : 0;
        } catch (\Throwable $e) {
            $adminCount = Usuario::join('perfil', 'usuario.id_perfil', '=', 'perfil.id_perfil')->where('perfil.nombre_perfil', 'Administrador')->count();
        }

        if ($adminCount === 0) {
            $rules['master_key'] = ['required', 'string', 'min:6'];
        } else {
            // Si ya hay un admin registrando, obligamos a que elija un rol válido
            $rules['id_rol'] = ['required', 'exists:rol,id_rol'];
        }

        return Validator::make($data, $rules);
    }

    protected function create(array $data)
    {
        $authService = new AuthService();

        $personaData = [
            'nombre_persona' => $data['first_name'],
            'segundo_nombre_persona' => null,
            'apellido_persona' => $data['first_lastname'],
            'segundo_apellido_persona' => null,
            'cedula_persona' => $data['cedula'] ?? null,
            'telefono_persona' => $data['telefono'] ?? null,
            'genero_persona' => '',
            'edad_persona' => 0,
            'fecha_nacimiento_persona' => now(),
            'email_persona' => $data['email'],
        ];

        $userData = [
            'username' => $data['email'],
            'password' => $data['password'],
            'master_key' => $data['master_key'] ?? null,
        ];

        if (!empty($data['telefono'])) {
            $digits = preg_replace('/\D+/', '', $data['telefono']);
            $digits = substr($digits, 0, 11);
            if (strlen($digits) >= 5) {
                $personaData['telefono_persona'] = substr($digits, 0, 4) . '-' . substr($digits, 4);
            } else {
                $personaData['telefono_persona'] = $digits;
            }
        }

        // Registrar la persona y su usuario base
        $usuario = $authService->register($personaData, $userData);

        try {
            $adminRol = Rol::where('nombre', 'Administrador')->first();
            $adminCount = $adminRol ? $adminRol->usuarios()->count() : 0;

            // Lógica de asignación de rol dinámico
            if ($adminCount === 0) {
                // Si es el primer registro del sistema, obligatoriamente es Administrador
                $assignRol = Rol::firstOrCreate(
                    ['nombre' => 'Administrador'],
                    ['descripcion' => 'Rol por defecto Administrador', 'slug' => 'administrador']
                );
            } else {
                // Buscamos el rol que el administrador seleccionó en el formulario
                $assignRol = Rol::find($data['id_rol']);
            }

            // Sincronizar en la tabla intermedia 'rol_usuario'
            if ($assignRol) {
                $usuario->roles()->sync([$assignRol->id_rol]);
            }

            // Guardar Llave Maestra si es el primer Administrador
            if ($assignRol && $assignRol->nombre === 'Administrador' && !empty($data['master_key'])) {
                try {
                    ConfiguracionSistema::updateMasterKey($data['master_key']);
                } catch (\Throwable $ex) {
                }
            }
        } catch (\Throwable $e) {
            // Fallback heredado por si la tabla relacional falla
            $role = Usuario::join('perfil', 'usuario.id_perfil', '=', 'perfil.id_perfil')->where('perfil.nombre_perfil', 'Administrador')->count() === 0 ? 'Administrador' : 'Obrero';
            $usuario->role = $role;
        }

        if (!empty($data['master_key'])) {
            $usuario->master_key = $data['master_key'];
        }

        // Guardar preguntas de seguridad
        if (!empty($data['security_questions']) && is_array($data['security_questions'])) {
            $sq = [];
            foreach ($data['security_questions'] as $qa) {
                if (!empty($qa['question']) && !empty($qa['answer'])) {
                    $sq[] = [
                        'question' => $qa['question'],
                        'answer' => Hash::make($qa['answer']),
                    ];
                }
            }
            if (!empty($sq)) {
                $usuario->security_questions = $sq;
            }
        }

        $usuario->save();

        return $usuario;
    }
}
