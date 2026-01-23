<?php

namespace App\Traits;

trait ConvierteAMayusculas
{
    /**
     * Convierte automáticamente los campos indicados a mayúsculas
     */
    public static function bootConvierteAMayusculas()
    {
        static::saving(function ($model) {
            if (property_exists($model, 'mayusculas') && is_array($model->mayusculas)) {
                foreach ($model->mayusculas as $campo) {
                    if (isset($model->{$campo}) && $model->{$campo} !== null) {
                        $model->{$campo} = mb_strtoupper($model->{$campo});
                    }
                }
            }
        });
    }
}
