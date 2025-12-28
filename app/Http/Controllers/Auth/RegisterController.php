<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;


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

    use RegistersUsers;

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
        $this->middleware('guest')->except('showRegistrationForm');
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'security_questions' => ['required', 'array', 'min:1'],
            'security_questions.*.question' => ['required', 'string'],
            'security_questions.*.answer' => ['required', 'string'],
        ];

        // If no Administrador exists, require master_key (first admin must set it)
        if (User::where('role', 'Administrador')->count() === 0) {
            $rules['master_key'] = ['required', 'string', 'min:6'];
        }

        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // Use the new AuthService to register persona + usuario, but keep creating legacy User for compatibility
        $authService = new AuthService();

        // Prepare persona data mapping to existing persona table structure if needed
            $personaData = [
                'nombre_persona' => $data['name'],
                'segundo_nombre_persona' => null,
                'apellido_persona' => $data['name'],
                'segundo_apellido_persona' => null,
                'cedula_persona' => $data['email'],
                'telefono_persona' => '',
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

        // register persona + usuario
        $usuario = $authService->register($personaData, $userData);

        // Legacy Laravel user (keeps default auth scaffolding working)
        $role = User::where('role', 'Administrador')->count() === 0 ? 'Administrador' : 'Obrero';
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $role,
        ]);

        // Save security questions to legacy user as well
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
            $user->security_questions = $sq;
            $user->save();
        }

        return $user;
    }
}
