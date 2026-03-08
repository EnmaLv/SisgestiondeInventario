<?php

namespace App\AdminLTE\Filters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class ModuleFilter implements FilterInterface
{
    public function transform($item)
    {
        if (! isset($item['module']) || empty($item['module'])) {
            return $item;
        }

        $moduloActivo = session('modulo_activo');

        if (is_null($moduloActivo)) {
            return false;
        }

        if ($item['module'] === $moduloActivo) {
            return $item;
        }

        return false;
    }
}