<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequireMasterKey
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            $isAdmin = false;
            if (method_exists($user, 'roles')) {
                // Eloquent relationship
                try {
                    $isAdmin = $user->roles()->where('nombre', 'Administrador')->exists();
                } catch (\Exception $e) {
                    $isAdmin = false;
                }
            } else {
                $isAdmin = ($user->role ?? '') === 'Administrador';
            }

            if ($isAdmin) {
            // Allow access to master-key form/verify routes to avoid redirect loops
            $allowed = [
                'admin/configuracion/master-key',
                'admin/configuracion/master-key/verify',
            ];

                if (!session('master_key_validated') && !in_array($request->path(), $allowed)) {
                    return redirect()->route('admin.configuracion.master_key.form');
                }
            }
        }

        return $next($request);
    }
}
