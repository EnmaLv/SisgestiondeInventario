<?php

namespace App\Services;

use App\Models\Becas\Beca;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BecaService
{
    public function listar(array $filters)
    {
        return Beca::with(['beneficios'])
            ->buscar($filters['buscar'] ?? null)
            ->activo($filters['activo'] ?? 1)
            ->latest()
            ->paginate(10)
            ->appends($filters);
    }

    public function crear(array $data): Beca
    {
        return DB::transaction(function () use ($data) {
            $beca = Beca::create($this->datosBeca($data) + [
                'codigo' => $this->generarCodigoUnico(),
            ]);

            $this->sincronizarBeneficios($beca, $data['beneficios'] ?? []);

            return $beca->load(['beneficios']);
        });
    }

    public function actualizar(Beca $beca, array $data): array
    {
        return DB::transaction(function () use ($beca, $data) {
            $beneficiosAntes = $beca->beneficios()->pluck('be_beneficios.id')->sort()->values()->all();

            $beca->update($this->datosBeca($data));
            $this->sincronizarBeneficios($beca, $data['beneficios'] ?? []);

            $beneficiosDespues = $beca->beneficios()->pluck('be_beneficios.id')->sort()->values()->all();

            return [
                'beca' => $beca->fresh(['beneficios']),
                'beneficios_cambiaron' => $beneficiosAntes !== $beneficiosDespues,
            ];
        });
    }

    public function cambiarEstado(Beca $beca): Beca
    {
        $beca->update(['activo' => !$beca->activo]);

        return $beca->fresh();
    }

    public function generarCodigoUnico(): string
    {
        do {
            $codigo = 'BE-' . now()->format('Y') . '-' . Str::upper(Str::random(5));
        } while (Beca::where('codigo', $codigo)->exists());

        return $codigo;
    }

    private function datosBeca(array $data): array
    {
        return Arr::only($data, [
            'nombre',
            'descripcion',
            'activo',
        ]);
    }

    private function sincronizarBeneficios(Beca $beca, array $beneficios): void
    {
        $sync = [];

        foreach ($beneficios as $beneficio) {
            if (empty($beneficio['id'])) {
                continue;
            }

            $sync[$beneficio['id']] = [
                'observacion' => $beneficio['observacion'] ?? null,
                'activo' => isset($beneficio['activo']),
            ];
        }

        $beca->beneficios()->sync($sync);
    }
}
