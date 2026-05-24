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
        $usuario = Usuario::with(['persona', 'perfil', 'roles'])->findOrFail($id);
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
            'usuario',
            'menu',
            'allow',
            'effective',
            'rolePerms',
            'rolePatterns',
            'keyToPatterns',
            'modulos',
            'roleModules',
            'modulosExtra'
        ));
    }

    public function update(Request $request, $id)
    {
        // Traemos el usuario con sus roles cargados
        $usuario = Usuario::with('roles')->findOrFail($id);
        $auth = auth()->user();
        if ($auth && $auth->id_usuario == $usuario->id_usuario && $auth->roles->contains('nombre', 'Administrador')) {
            return back()->withErrors(['permisos' => 'No puedes modificar tus propios permisos. Pide a otro Administrador que lo haga.']);
        }

        $data = $request->validate([
            'allow' => 'nullable|array',
            'deny' => 'nullable|array',
            'modulos' => 'nullable|array',
        ]);

        // 1. Extraer datos heredados de los Roles para el cálculo real
        $roleModules = [];
        $rolePerms = [];
        foreach ($usuario->roles as $r) {
            $mods = $r->modulos ? $r->modulos->pluck('id')->toArray() : [];
            $roleModules = array_merge($roleModules, $mods);

            $perms = $r->menu_permissions ?? [];
            if (is_array($perms)) $rolePerms = array_merge($rolePerms, $perms);
        }
        $roleModules = array_values(array_unique($roleModules));
        $rolePerms = array_values(array_unique($rolePerms));

        // Módulos adicionales seleccionados en el formulario
        $modulosExtra = array_map('intval', array_values($data['modulos'] ?? []));

        // Sumatoria total real de módulos activos finales
        $totalModulosCount = count(array_unique(array_merge($roleModules, $modulosExtra)));

        $allow = array_values($data['allow'] ?? []);
        $deny = array_values($data['deny'] ?? []);
        $selectorKey = 'admin/modulos/seleccionar';

        // ✨ VALIDACIÓN PREVENTIVA AUTOMÁTICA (CAPA DE PERMISOS ESPECIALES)
        if ($totalModulosCount > 1) {
            // El usuario DEBE tener activo el selector de módulos
            if (in_array($selectorKey, $rolePerms)) {
                // Si ya lo heredaba del Rol, quitamos cualquier restricción accidental en deny
                $deny = array_values(array_diff($deny, [$selectorKey]));
            } else {
                // Si el Rol no lo incluía, lo inyectamos forzosamente en los permitidos (allow)
                if (!in_array($selectorKey, $allow)) {
                    $allow[] = $selectorKey;
                }
            }
        } else {
            // El usuario DEBE tener INACTIVO el selector (0 o 1 módulo asignado)
            if (in_array($selectorKey, $rolePerms)) {
                // Si venía del Rol, lo metemos a la fuerza en deny para bloquearlo
                if (!in_array($selectorKey, $deny)) {
                    $deny[] = $selectorKey;
                }
            } else {
                // Si no venía de ningún lado, nos aseguramos de limpiarlo de allow por si acaso
                $allow = array_values(array_diff($allow, [$selectorKey]));
            }
        }

        // Estructuramos el array final limpio
        $extra = [
            'allow' => $allow,
            'deny' => $deny,
            'modulos' => $modulosExtra,
        ];

        // Guardamos codificado en JSON
        $usuario->extra_permissions = json_encode($extra);
        $usuario->save();

        // Limpiamos caché de sesión
        session()->forget(['modulos_permitidos', 'modulo_activo', 'es_admin']);

        return redirect()->route('admin.configuracion.permisos.index', $usuario->id_usuario)->with('success', 'Permisos y Módulos especiales actualizados con éxito.');
    }
}
