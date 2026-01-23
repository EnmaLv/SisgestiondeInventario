<?php

namespace App\Traits;

trait ConvierteAMayusculasNoEloquent
{
    public function convertirCamposAMayusculas(array $data, array $campos): array
    {
        foreach ($campos as $campo) {
            if (isset($data[$campo]) && $data[$campo] !== null) {
                $data[$campo] = mb_strtoupper($data[$campo]);
            }
        }

        return $data;
    }
}