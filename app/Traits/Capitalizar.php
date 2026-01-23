<?php

namespace App\Traits;

trait Capitalizar
{
    /**
     * Sobrescribe el método setAttribute para capitalizar automáticamente
     * los campos indicados en la propiedad $capitalizar
     */
    public function setAttribute($key, $value)
    {
        if (property_exists($this, 'capitalizar') && in_array($key, $this->capitalizar)) {
            $value = $value ? mb_convert_case($value, MB_CASE_TITLE, "UTF-8") : null;
        }

        parent::setAttribute($key, $value);
    }
}
