<?php

namespace App\Http\Controllers\Admin\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario;

class PermisosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\RequireMasterKey::class);
    }

    public function index(Request $request)
    {
        $usuarios = Usuario::with(['persona', 'perfil', 'roles'])->orderBy('id_usuario', 'asc')->paginate(15);
        return view('admin.configuracion.permisos.index', compact('usuarios'));
    }

    public function edit($id)
    {
        $usuario = Usuario::with(['persona','perfil','roles'])->findOrFail($id);
        $auth = auth()->user();
        if ($auth && $auth->id_usuario == $usuario->id_usuario && $auth->roles->contains('nombre', 'Administrador')) {
            return redirect()->route('admin.configuracion.permisos.index')->withErrors(['permisos' => 'No puedes editar tus propios permisos. Pide a otro Administrador que lo haga.']);
        }
        $menu = config('adminlte.menu', []);
            $extra = is_string($usuario->extra_permissions) ? json_decode($usuario->extra_permissions, true) : ($usuario->extra_permissions ?? []);
            $allow = $extra['allow'] ?? [];
            $deny = $extra['deny'] ?? [];

        $rolePerms = [];
        foreach ($usuario->roles as $r) {
            $perms = $r->menu_permissions ?? [];
            if (is_array($perms)) $rolePerms = array_merge($rolePerms, $perms);
        }
        $rolePerms = array_values(array_unique($rolePerms));
            $effective = array_values(array_unique(array_merge(array_values(array_diff($rolePerms, $deny)), $allow)));

        return view('admin.configuracion.permisos.edit', compact('usuario','menu','allow','effective','rolePerms'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);
        $auth = auth()->user();
        if ($auth && $auth->id_usuario == $usuario->id_usuario && $auth->roles->contains('nombre', 'Administrador')) {
            return back()->withErrors(['permisos' => 'No puedes modificar tus propios permisos. Pide a otro Administrador que lo haga.']);
        }
            $data = $request->validate([
                'allow' => 'nullable|array',
                'deny' => 'nullable|array',
            ]);

            $extra = [
                'allow' => array_values($data['allow'] ?? []),
                'deny' => array_values($data['deny'] ?? []),
            ];

        $usuario->extra_permissions = $extra;
        $usuario->save();

        return redirect()->route('admin.configuracion.permisos.edit', $usuario->id_usuario)->with('success','Permisos actualizados');
    }
}
