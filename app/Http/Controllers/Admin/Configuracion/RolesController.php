<?php

namespace App\Http\Controllers\Admin\Configuracion;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rol;
use App\Models\Usuario;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(\App\Http\Middleware\RequireMasterKey::class);
    }

    public function index(Request $request)
    {
        $query = Rol::query();
        if ($search = $request->get('q')) {
            $query->where('nombre', 'like', "%{$search}%");
        }
        $roles = $query->orderBy('id_rol', 'desc')->paginate(15);
        return view('admin.configuracion.roles.index', compact('roles'));
    }

    public function create()
    {
        $menu = config('adminlte.menu', []);
        return view('admin.configuracion.roles.create', compact('menu'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => 'required|string|unique:rol,nombre',
            'descripcion' => 'nullable|string',
            'menu_permissions' => 'nullable|array',
        ], [
            'nombre.required' => 'El nombre del rol es requerido',
            'nombre.unique' => 'Este nombre de rol ya existe',
        ]);

        $data['menu_permissions'] = array_values($data['menu_permissions'] ?? []);
        Rol::create($data);

        return redirect()->route('admin.configuracion.roles.index')->with('success', 'Rol creado Exitosamente');
    }

    public function edit($id)
    {
        $rol = Rol::findOrFail($id);
        $menu = config('adminlte.menu', []);
        $protected = ['Empleado', 'Obrero', 'Administrador'];
        $isProtected = in_array(strtolower($rol->nombre ?? ''), array_map('strtolower', $protected));
        return view('admin.configuracion.roles.edit', compact('rol', 'menu', 'isProtected'));
    }

    public function update(Request $request, $id)
    {
        $rol = Rol::findOrFail($id);
        $protected = ['Empleado', 'Obrero', 'Administrador'];
        if (in_array(strtolower($rol->nombre ?? ''), array_map('strtolower', $protected))) {
            return back()->withErrors(['roles' => 'El rol ' . $rol->nombre . ' está protegido y no puede editarse.']);
        }
        $data = $request->validate([
            'nombre' => 'required|string|unique:rol,nombre,' . $rol->id_rol . ',id_rol',
            'descripcion' => 'nullable|string',
            'menu_permissions' => 'nullable|array',
        ], [
            'nombre.required' => 'El nombre del rol es requerido',
            'nombre.unique' => 'Este nombre de rol ya existe',
        ]);
        // If this is the Administrador role, lock menu_permissions to all menu keys
        if (($rol->nombre ?? '') === 'Administrador') {
            $menu = config('adminlte.menu', []);
            $all = [];
            $collector = function ($items) use (&$collector, &$all) {
                foreach ($items as $it) {
                    if (isset($it['submenu'])) {
                        $collector($it['submenu']);
                    } else {
                        $val = $it['key'] ?? ($it['url'] ?? ($it['route'] ?? null));
                        if ($val) $all[] = $val;
                    }
                }
            };
            $collector($menu);
            $data['menu_permissions'] = array_values(array_unique($all));
        } else {
            $data['menu_permissions'] = array_values($data['menu_permissions'] ?? []);
        }

        $rol->update($data);
        return redirect()->route('admin.configuracion.roles.index')->with('success', 'Rol actualizado exitosamente');
    }

    public function destroy($id)
    {
        $rol = Rol::findOrFail($id);
        $protected = ['Empleado', 'Obrero', 'Administrador'];
        if (in_array(strtolower($rol->nombre ?? ''), array_map('strtolower', $protected))) {
            return redirect()->route('admin.configuracion.roles.index')->withErrors(['delete' => 'El rol ' . $rol->nombre . ' está protegido y no puede eliminarse.']);
        }
        $rol->usuarios()->detach();
        $rol->delete();
        return redirect()->route('admin.configuracion.roles.index')->with('success', 'Rol eliminado exitosamente');
    }
}
