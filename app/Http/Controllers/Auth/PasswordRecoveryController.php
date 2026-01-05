<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class PasswordRecoveryController extends Controller
{
    public function showEmailForm()
    {
        return view('auth.recover_email');
    }

    public function postEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = Usuario::where('username', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado.']);
        }

        // show questions
        $questions = $user->security_questions ?? [];
        if (empty($questions)) {
            return back()->withErrors(['email' => 'El usuario no tiene preguntas de seguridad configuradas.']);
        }

        session(['recovery_user_id' => $user->id_usuario]);
        return view('auth.recover_questions', ['questions' => $questions]);
    }

    public function verifyAnswers(Request $request)
    {
        $id = session('recovery_user_id');
        if (!$id) {
            return redirect()->route('password.recover.email')->withErrors(['email' => 'Sesión expirada.']);
        }
        $user = Usuario::find($id);
        $stored = $user->security_questions ?? [];

        $answers = $request->input('answers', []);
        if (count($answers) !== count($stored)) {
            return back()->withErrors(['answers' => 'Responda todas las preguntas.']);
        }

        foreach ($stored as $i => $qa) {
            if (!isset($answers[$i]) || !Hash::check($answers[$i], $qa['answer'])) {
                return back()->withErrors(['answers' => 'Respuestas incorrectas.']);
            }
        }

        // Answers validated: decide based on assigned roles
        $hasObrero = $user->roles()->where('nombre', 'Obrero')->exists();
        $hasAdmin = $user->roles()->where('nombre', 'Administrador')->exists();

        if ($hasObrero && ! $hasAdmin) {
            return view('auth.reset_password', ['user' => $user]);
        }

        if ($hasAdmin) {
            return view('auth.admin_recovery_choice', ['user' => $user]);
        }

        // Default: allow password reset
        return view('auth.reset_password', ['user' => $user]);
    }

    /**
     * Show the verification form if accessed via GET (reads questions from session).
     */
    public function showVerifyForm(Request $request)
    {
        $id = session('recovery_user_id');
        if (!$id) {
            return redirect()->route('password.recover.email')->withErrors(['email' => 'Sesión expirada.']);
        }

        $user = Usuario::find($id);
        if (!$user) {
            return redirect()->route('password.recover.email')->withErrors(['email' => 'Usuario no encontrado.']);
        }

        $questions = $user->security_questions ?? [];
        if (empty($questions)) {
            return redirect()->route('password.recover.email')->withErrors(['email' => 'El usuario no tiene preguntas de seguridad configuradas.']);
        }

        return view('auth.recover_questions', ['questions' => $questions]);
    }

    public function resetPassword(Request $request)
    {
        $id = session('recovery_user_id');
        if (!$id) return redirect()->route('password.recover.email');

        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $user = Usuario::find($id);
        $user->password = Hash::make($request->password);
        $user->save();
        session()->forget('recovery_user_id');
        return redirect()->route('login')->with('status', 'Contraseña restablecida Exitosamente.');
    }

    public function resetMasterKey(Request $request)
    {
        $id = session('recovery_user_id');
        if (!$id) return redirect()->route('password.recover.email');

        $request->validate(['master_key' => 'required|string|min:6']);
        $user = Usuario::find($id);
        $user->master_key = $request->master_key; // mutator encrypts
        $user->save();
        session()->forget('recovery_user_id');
        return redirect()->route('login')->with('status', 'Llave maestra restablecida Exitosamente.');
    }
}
