<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckMenuPermission
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        // Always allow access to master-key endpoints so RequireMasterKey can handle auth
        $allowedPaths = [
            'admin/configuracion/master-key',
            'admin/configuracion/master-key/verify',
        ];
        $routeName = $request->route() ? $request->route()->getName() : null;
        $allowedRouteNames = [
            'admin.configuracion.master_key.form',
            'admin.configuracion.master_key.verify',
        ];

        $currentPath = ltrim($request->path(), '/');
        if (in_array($currentPath, $allowedPaths) || ($routeName && in_array($routeName, $allowedRouteNames))) {
            return $next($request);
        }

        // Gather role-based patterns (could be menu keys or direct path patterns)
        $rolePatterns = collect($user->roles)->pluck('menu_permissions')->flatten()->filter()->unique()->values()->all();

        // Expand menu 'keys' into actual path patterns by consulting config('adminlte.menu')
        $menu = config('adminlte.menu', []);
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

        // Build expanded patterns list: if a role pattern matches a known key, use that key's patterns
        $expandedRolePatterns = [];
        foreach ($rolePatterns as $p) {
            if (isset($keyToPatterns[$p])) {
                foreach ($keyToPatterns[$p] as $pat) {
                    $expandedRolePatterns[] = $pat;
                }
            } else {
                $expandedRolePatterns[] = $p;
            }
        }
        // replace rolePatterns with expanded list
        $rolePatterns = array_values(array_unique($expandedRolePatterns));

        // Gather user extra allow/deny from extra_permissions JSON
        $extra = is_array($user->extra_permissions ?? null) ? $user->extra_permissions : (is_string($user->extra_permissions) ? json_decode($user->extra_permissions, true) : []);
        $userAllow = $extra['allow'] ?? [];
        $userDeny = $extra['deny'] ?? [];

        $path = ltrim($request->path(), '/');
        $routeName = $request->route() ? $request->route()->getName() : null;

        // Deny if any user deny pattern matches (match against path or route name)
        foreach ($userDeny as $p) {
            if (Str::is($p, $path) || ($routeName && Str::is($p, $routeName))) {
                abort(403);
            }
        }

        // If user allow patterns exist, allow if matches (path or route name)
        foreach ($userAllow as $p) {
            if (Str::is($p, $path) || ($routeName && Str::is($p, $routeName))) {
                return $next($request);
            }
        }

        // If role patterns exist, require at least one match (path or route name)
        if (! empty($rolePatterns)) {
            foreach ($rolePatterns as $p) {
                if (Str::is($p, $path) || ($routeName && Str::is($p, $routeName))) {
                    return $next($request);
                }
            }

            abort(403);
        }

        // No role restrictions configured, allow by default
        return $next($request);
    }
}
