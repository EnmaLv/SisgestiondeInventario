<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $this->addPermission('Administrador de Beca', 'beneficios_becas');
    }

    public function down(): void
    {
        $this->removePermission('Administrador de Beca', 'beneficios_becas');
    }

    private function addPermission(string $roleName, string $permission): void
    {
        $role = DB::table('rol')->where('nombre', $roleName)->first();

        if (!$role) {
            return;
        }

        $permissions = json_decode($role->menu_permissions ?? '[]', true) ?: [];

        if (!in_array($permission, $permissions, true)) {
            $permissions[] = $permission;
        }

        DB::table('rol')->where('id_rol', $role->id_rol)->update([
            'menu_permissions' => json_encode(array_values(array_unique($permissions))),
            'updated_at' => now(),
        ]);
    }

    private function removePermission(string $roleName, string $permission): void
    {
        $role = DB::table('rol')->where('nombre', $roleName)->first();

        if (!$role) {
            return;
        }

        $permissions = json_decode($role->menu_permissions ?? '[]', true) ?: [];

        DB::table('rol')->where('id_rol', $role->id_rol)->update([
            'menu_permissions' => json_encode(array_values(array_diff($permissions, [$permission]))),
            'updated_at' => now(),
        ]);
    }
};
