<?php

namespace App\Menu\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;
use Illuminate\Support\Facades\Auth;

class PermissionFilter implements FilterInterface
{
    public function transform($item)
    {
        // Process submenu items recursively: keep only allowed children
        if (isset($item['submenu']) && is_array($item['submenu'])) {
            $newSub = [];
            foreach ($item['submenu'] as $sub) {
                $t = $this->transform($sub);
                if (empty($t['restricted'])) {
                    $newSub[] = $t;
                }
            }
            $item['submenu'] = $newSub;
            if (! count($item['submenu'])) {
                $item['restricted'] = true;
            }
            return $item;
        }

        $user = Auth::user();
        if (! $user) {
            $item['restricted'] = true;
            return $item;
        }

        // Administrador should see everything
        foreach ($user->roles ?? [] as $r) {
            if (isset($r->nombre) && mb_strtolower($r->nombre) === 'administrador') {
                return $item;
            }
        }

        // Build allowed keys strictly from roles' menu_permissions (no implicit additions).
        $allowed = [];
        foreach ($user->roles ?? [] as $role) {
            $perms = $role->menu_permissions ?? [];
            if (is_array($perms)) {
                $allowed = array_merge($allowed, $perms);
            }
        }
        $allowed = array_values(array_unique($allowed));

        // Apply explicit user deny/allow overrides if present.
        // Deny removes keys from role-derived allowed list; Allow adds explicit keys.
        $extra = is_array($user->extra_permissions) ? $user->extra_permissions : (is_string($user->extra_permissions) ? json_decode($user->extra_permissions, true) : []);
        $userDeny = $extra['deny'] ?? [];
        $userAllow = $extra['allow'] ?? [];

        if (! empty($userDeny) && is_array($userDeny)) {
            $allowed = array_values(array_diff($allowed, $userDeny));
        }

        if (! empty($userAllow) && is_array($userAllow)) {
            $allowed = array_values(array_unique(array_merge($allowed, $userAllow)));
        }

        // derive a key if not explicitly provided
        // If this is a header (string or has 'header'), decide visibility based on following items
        if (is_string($item) || isset($item['header'])) {
            $headerText = is_string($item) ? $item : $item['header'];
            $menu = config('adminlte.menu', []);
            $found = false;
            $visible = false;
            foreach ($menu as $m) {
                if (! $found) {
                    if ((is_string($m) && $m === $headerText) || (is_array($m) && isset($m['header']) && $m['header'] === $headerText)) {
                        $found = true;
                    }
                    continue;
                }

                // stop at next header
                if (is_string($m) || (is_array($m) && isset($m['header']))) {
                    break;
                }

                if ($this->isMenuItemVisible($m, $allowed)) {
                    $visible = true;
                    break;
                }
            }

            if (! $visible) {
                return is_string($item) ? ['header' => $headerText, 'restricted' => true] : array_merge($item, ['restricted' => true]);
            }

            // if visible, return normalized header array
            return is_string($item) ? ['header' => $headerText] : $item;
        }

        $key = $item['key'] ?? ($item['url'] ?? ($item['route'] ?? ($item['text'] ?? null)));
        if (! $key) {
            // No meaningful key for a link — restrict by default
            $item['restricted'] = true;
            return $item;
        }

        // Default: show only if the key is in the allowed list (roles' menu_permissions)
        if (! in_array($key, $allowed)) {
            $item['restricted'] = true;
        }

        return $item;
    }

    /**
     * Determine whether a menu item would be visible given the allowed keys.
     */
    private function isMenuItemVisible($item, array $allowed)
    {
        if (! is_array($item)) return false;

        if (isset($item['submenu']) && is_array($item['submenu'])) {
            foreach ($item['submenu'] as $sub) {
                if ($this->isMenuItemVisible($sub, $allowed)) return true;
            }
            return false;
        }

        $key = $item['key'] ?? ($item['url'] ?? ($item['route'] ?? ($item['text'] ?? null)));
        if (! $key) return false;
        return in_array($key, $allowed);
    }
}
