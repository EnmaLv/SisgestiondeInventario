<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
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
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->withErrors(['email' => 'Usuario no encontrado.']);
        }

        // show questions
        $questions = $user->security_questions ?? [];
        if (empty($questions)) {
            return back()->withErrors(['email' => 'El usuario no tiene preguntas de seguridad configuradas.']);
        }

        session(['recovery_user_id' => $user->id]);
        return view('auth.recover_questions', ['questions' => $questions, 'role' => $user->role]);
    }

    public function verifyAnswers(Request $request)
    {
        $id = session('recovery_user_id');
        if (!$id) {
            return redirect()->route('password.recover.email')->withErrors(['email' => 'Sesión expirada.']);
        }

        $user = User::find($id);
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

        // Answers validated
        if ($user->role === 'Obrero') {
            // allow reset password flow
            return view('auth.reset_password', ['user' => $user]);
        }

        // Admin: give choice to reset password or master key
        return view('auth.admin_recovery_choice', ['user' => $user]);
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

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('password.recover.email')->withErrors(['email' => 'Usuario no encontrado.']);
        }

        $questions = $user->security_questions ?? [];
        if (empty($questions)) {
            return redirect()->route('password.recover.email')->withErrors(['email' => 'El usuario no tiene preguntas de seguridad configuradas.']);
        }

        return view('auth.recover_questions', ['questions' => $questions, 'role' => $user->role]);
    }

    public function resetPassword(Request $request)
    {
        $id = session('recovery_user_id');
        if (!$id) return redirect()->route('password.recover.email');

        $request->validate(['password' => 'required|string|min:8|confirmed']);
        $user = User::find($id);
        $user->password = Hash::make($request->password);
        $user->save();
        session()->forget('recovery_user_id');
        return redirect()->route('login')->with('status', 'Contraseña restablecida correctamente.');
    }

    public function resetMasterKey(Request $request)
    {
        $id = session('recovery_user_id');
        if (!$id) return redirect()->route('password.recover.email');

        $request->validate(['master_key' => 'required|string|min:6']);
        $user = User::find($id);
        $user->master_key = $request->master_key; // mutator encrypts
        $user->save();
        session()->forget('recovery_user_id');
        return redirect()->route('login')->with('status', 'Llave maestra restablecida correctamente.');
    }
}
