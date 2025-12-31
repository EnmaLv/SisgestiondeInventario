<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Rol;
use App\Models\Usuario;

class ConvertMenuPermissionsToKeys extends Command
{
    protected $signature = 'convert:menu-perms {--apply : Apply changes to database}';
    protected $description = 'Preview (or apply) conversion of stored menu permissions (urls) to menu keys based on config/adminlte.php';

    public function handle()
    {
        $menu = config('adminlte.menu', []);
        $map = [];
        $this->traverseMenu($menu, $map);

        if (empty($map)) {
            $this->info('No menu items with keys found in config/adminlte.php. Ensure items include a "key" attribute.');
            return 0;
        }

        $this->info('Found '.count($map).' mappings (url/route -> key).');

        $roleChanges = [];
        $roles = Rol::all();
        foreach ($roles as $role) {
            $old = (array)($role->menu_permissions ?? []);
            $new = $this->mapArrayToKeys($old, $map);
            if ($old !== $new) {
                $roleChanges[] = ['role' => $role->nombre, 'id' => $role->id_rol, 'old' => $old, 'new' => $new];
            }
        }

        $userChanges = [];
        $users = Usuario::all();
        foreach ($users as $u) {
            $extra = is_string($u->extra_permissions) ? json_decode($u->extra_permissions, true) : ($u->extra_permissions ?? []);
            $allow = $extra['allow'] ?? [];
            $newAllow = $this->mapArrayToKeys((array)$allow, $map);
            if ($allow !== $newAllow) {
                $userChanges[] = ['user' => $u->username, 'id' => $u->id_usuario, 'old' => $allow, 'new' => $newAllow];
            }
        }

        $this->info('Roles to change: '.count($roleChanges));
        $this->info('Users to change: '.count($userChanges));

        if (!empty($roleChanges)) {
            $this->line('--- Role changes preview ---');
            foreach ($roleChanges as $c) {
                $this->line("Role {$c['role']} ({$c['id']})");
                $this->line('  Old: '.json_encode($c['old']));
                $this->line('  New: '.json_encode($c['new']));
            }
        }

        if (!empty($userChanges)) {
            $this->line('--- User changes preview ---');
            foreach ($userChanges as $c) {
                $this->line("User {$c['user']} ({$c['id']})");
                $this->line('  Old allow: '.json_encode($c['old']));
                $this->line('  New allow: '.json_encode($c['new']));
            }
        }

        if ($this->option('apply')) {
            // apply role changes
            foreach ($roleChanges as $c) {
                $r = Rol::find($c['id']);
                $r->menu_permissions = $c['new'];
                $r->save();
            }
            foreach ($userChanges as $c) {
                $u = Usuario::find($c['id']);
                $extra = is_string($u->extra_permissions) ? json_decode($u->extra_permissions, true) : ($u->extra_permissions ?? []);
                $extra['allow'] = $c['new'];
                $u->extra_permissions = $extra;
                $u->save();
            }
            $this->info('Applied changes to database.');
        } else {
            $this->info('Dry run complete. To apply changes run with --apply');
        }

        return 0;
    }

    protected function traverseMenu($items, array & $map)
    {
        foreach ($items as $it) {
            if (isset($it['submenu'])) {
                $this->traverseMenu($it['submenu'], $map);
            } else {
                $key = $it['key'] ?? null;
                $val = $it['url'] ?? ($it['route'] ?? null);
                if ($key && $val) {
                    $map[$val] = $key;
                }
            }
        }
    }

    protected function mapArrayToKeys(array $arr, array $map)
    {
        $res = [];
        foreach ($arr as $v) {
            if (isset($map[$v])) {
                $res[] = $map[$v];
            } else {
                // keep as-is (could already be a key)
                $res[] = $v;
            }
        }
        // unique and reindex
        return array_values(array_unique($res));
    }
}
