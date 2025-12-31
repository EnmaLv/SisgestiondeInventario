<?php

namespace App\Http\Controllers\Admin\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;
use App\Models\Perfil;
use App\Models\Rol;
use Illuminate\Support\Facades\Auth;

class EmpleosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\RequireMasterKey::class);
    }

    public function index(Request $request)
    {
        $perfiles = Perfil::pluck('nombre_perfil', 'id_perfil')->toArray();
        $roles = Rol::orderBy('nombre')->get();

        $query = Usuario::with(['persona', 'perfil', 'roles']);

        if ($request->filled('perfil')) {
            $query->where('id_perfil', $request->input('perfil'));
        }

        if ($request->filled('rol')) {
            $rolId = $request->input('rol');
            $query->whereHas('roles', function ($q) use ($rolId) {
                $table = $q->getModel()->getTable();
                $q->where("{$table}.id_rol", $rolId);
            });
        }

        $usuarios = $query->orderBy('id_usuario', 'asc')->paginate(15);

        return view('admin.configuracion.empleados.index', compact('usuarios', 'perfiles', 'roles'));
    }

    public function edit($id)
    {
        $usuario = Usuario::with(['persona', 'perfil', 'roles'])->findOrFail($id);
        $roles = Rol::orderBy('nombre')->get();

        $otherAdminExists = false;
        $adminRol = Rol::where('nombre', 'Administrador')->first();
        if ($adminRol) {
            $otherAdminExists = $adminRol->usuarios()->where('usuario.id_usuario', '!=', $usuario->id_usuario)->exists();
        }

        return view('admin.configuracion.empleados.edit', compact('usuario', 'roles', 'otherAdminExists'));
    }

    public function show($id)
    {
        $usuario = Usuario::with(['persona','perfil','roles'])->findOrFail($id);
        return view('admin.configuracion.empleados.show', compact('usuario'));
    }

    public function destroy($id)
    {
        $usuario = Usuario::with('roles')->findOrFail($id);

        $auth = Auth::user();
        // Prevent an administrator from deleting themselves; only another admin can delete an admin
        $adminRol = Rol::where('nombre', 'Administrador')->first();
        if ($auth && $adminRol && $auth->id_usuario == $usuario->id_usuario && $auth->roles->contains('id_rol', $adminRol->id_rol)) {
            return redirect()->route('admin.configuracion.empleados.index')->withErrors(['delete' => 'No puedes eliminarte a ti mismo. Pide a otro Administrador que lo haga.']);
        }

        // Prevent deleting last admin
        if ($adminRol && $usuario->roles->contains('id_rol', $adminRol->id_rol)) {
            $adminCount = $adminRol->usuarios()->count();
            if ($adminCount <= 1) {
                return redirect()->route('admin.configuracion.empleados.index')->withErrors(['delete' => 'No se puede eliminar al último Administrador.']);
            }
        }

        // detach roles and delete
        $usuario->roles()->detach();
        $usuario->delete();

        return redirect()->route('admin.configuracion.empleados.index')->with('success', 'Empleado eliminado correctamente');
    }

    public function update(Request $request, $id)
    {
        // Validate role input (single selection). If form omits role, we won't change roles.
        $request->validate([
            'role' => 'nullable|integer|exists:rol,id_rol',
        ]);

        $usuario = Usuario::with('roles')->findOrFail($id);
        $auth = Auth::user();

        $role = $request->input('role', null);
        // If the form submitted the role field, interpret intent: empty => remove roles, null => no change
        $newRoleIds = $request->has('role') ? ($role ? [$role] : []) : null;

        // If the authenticated user is editing themselves and currently an admin, prevent removing the last admin
        $adminRol = Rol::where('nombre', 'Administrador')->first();
        if ($adminRol) {
            $hasAdminNow = $usuario->roles->contains('id_rol', $adminRol->id_rol);
            $willHaveAdmin = is_array($newRoleIds) ? in_array($adminRol->id_rol, $newRoleIds) : $hasAdminNow;
            $adminCount = $adminRol->usuarios()->count();

            // Prevent removing Admin role from the last admin (whether editing self or another user)
            if ($hasAdminNow && ! $willHaveAdmin && $adminCount <= 1) {
                return back()->withErrors(['roles' => 'No se puede quitar el rol de Administrador: existe sólo un Administrador activo.']);
            }
        }

        // Sync only if a role was provided in the request; otherwise keep existing roles
        if (is_array($newRoleIds)) {
            // detect promotion to Administrador: new includes admin and user didn't have it
            $adminRol = Rol::where('nombre', 'Administrador')->first();
            $promotingToAdmin = false;
            if ($adminRol) {
                $hadAdminBefore = $usuario->roles->contains('id_rol', $adminRol->id_rol);
                $willHaveAdmin = in_array($adminRol->id_rol, $newRoleIds);
                $promotingToAdmin = (!$hadAdminBefore && $willHaveAdmin);
            }

            if ($promotingToAdmin) {
                // require a master key value to set for the new admin
                $request->validate([
                    'new_admin_master_key' => 'required|string|min:6',
                ]);
                $usuario->master_key = $request->input('new_admin_master_key');
            }

            $usuario->roles()->sync($newRoleIds);
            if ($promotingToAdmin) {
                $usuario->save();
            }
        }

        return redirect()->route('admin.configuracion.empleados.index')->with('success', 'Roles actualizados correctamente');
    }

    // Master key form and verify (simple flow for configuration access)
    public function masterKeyForm()
    {
        return view('admin.configuracion.master_key');
    }

    public function verifyMasterKey(Request $request)
    {
        $request->validate([
            'master_key' => 'required|string',
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->verifyMasterKey($request->input('master_key'))) {
            session(['master_key_validated' => true]);
            return redirect()->route('admin.configuracion.empleados.index');
        }

        return back()->withErrors(['master_key' => 'Llave maestra incorrecta']);
    }
}
