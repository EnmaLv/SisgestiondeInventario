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

        // 1. Extraer permisos y MÓDULOS adicionales ya guardados
        $extra = is_string($usuario->extra_permissions) ? json_decode($usuario->extra_permissions, true) : ($usuario->extra_permissions ?? []);
        $allow = $extra['allow'] ?? [];
        $deny = $extra['deny'] ?? [];
        $modulosExtra = $extra['modulos'] ?? []; // <--- Módulos extra guardados

        // 2. Obtener todos los módulos existentes para pintarlos en la vista
        $modulos = \App\Models\Modulo::all(); // <-- Ajusta el namespace de tu modelo Modulo si es diferente

        // 3. Extraer qué módulos e ítems de menú hereda el usuario de sus Roles asignados
        $rolePerms = [];
        $roleModules = []; // <--- Módulos heredados del rol
        foreach ($usuario->roles as $r) {
            // Menús
            $perms = $r->menu_permissions ?? [];
            if (is_array($perms)) $rolePerms = array_merge($rolePerms, $perms);

            // Módulos (Asumiendo que tu modelo Rol tiene la relación 'modulos')
            $mods = $r->modulos ? $r->modulos->pluck('id')->toArray() : [];
            $roleModules = array_merge($roleModules, $mods);
        }
        $rolePerms = array_values(array_unique($rolePerms));
        $roleModules = array_values(array_unique($roleModules));

        // Mapping de menús a patrones de URL/Ruta
        $keyToPatterns = [];
        $collector = function ($items) use (&$collector, &$keyToPatterns) {
            foreach ($items as $it) {
                if (isset($it['submenu']) && is_array($it['submenu'])) {
                    $collector($it['submenu']);
                }
                $key = $it['key'] ?? null;
                $patterns = [];
                if (!empty($it['active']) && is_array($it['active'])) {
                    $patterns = array_merge($patterns, $it['active']);
                }
                if (!empty($it['url'])) {
                    $patterns[] = ltrim($it['url'], '/');
                }
                if (!empty($it['route'])) {
                    $patterns[] = $it['route'];
                }
                if ($key && ! empty($patterns)) {
                    $keyToPatterns[$key] = array_values(array_unique($patterns));
                }
            }
        };
        $collector($menu);

        $expand = function ($arr) use ($keyToPatterns) {
            $out = [];
            foreach ($arr as $p) {
                if (isset($keyToPatterns[$p])) {
                    foreach ($keyToPatterns[$p] as $pat) $out[] = $pat;
                } else {
                    $out[] = $p;
                }
            }
            return array_values(array_unique($out));
        };

        $rolePatterns = $expand($rolePerms);
        $denyPatterns = $expand($deny);
        $allowPatterns = $expand($allow);

        $effective = array_values(array_unique(array_merge(array_values(array_diff($rolePatterns, $denyPatterns)), $allowPatterns)));

        // Enviamos las nuevas variables a la vista
        return view('admin.configuracion.permisos.edit', compact(
            'usuario','menu','allow','effective','rolePerms','rolePatterns','keyToPatterns',
            'modulos', 'roleModules', 'modulosExtra'
        ));
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
            'modulos' => 'nullable|array', // <--- Validamos los módulos adicionales
        ]);

        // Estructuramos el JSON final
        $extra = [
            'allow' => array_values($data['allow'] ?? []),
            'deny' => array_values($data['deny'] ?? []),
            'modulos' => array_map('intval', array_values($data['modulos'] ?? [])), // Guardamos los IDs como enteros
        ];

        $usuario->extra_permissions = $extra;
        $usuario->save();

        return redirect()->route('admin.configuracion.permisos.edit', $usuario->id_usuario)->with('success','Permisos y Módulos especiales actualizados con éxito.');
    }
}
