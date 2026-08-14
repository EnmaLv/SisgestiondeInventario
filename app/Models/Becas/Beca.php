<?php

namespace App\Models\Becas;

use Illuminate\Database\Eloquent\Model;

class Beca extends Model
{
    protected $table = 'be_becas';

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'activo',
        'requiere_tutor',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'requiere_tutor' => 'boolean',
    ];

    public function beneficios()
    {
        return $this->belongsToMany(Beneficio::class, 'be_beca_beneficio', 'beca_id', 'beneficio_id')
            ->withPivot(['observacion', 'activo'])
            ->withTimestamps();
    }

    public function asignacionesTrabajo()
    {
        return $this->hasMany(BecaTrabajoAsignacion::class, 'beca_id');
    }

    public function beneficiarios()
    {
        return $this->hasMany(BecaBeneficiario::class, 'beca_id');
    }

    public function tutores()
    {
        return $this->hasMany(BecaTutor::class, 'beca_id');
    }

    public function preguntas()
    {
        return $this->hasMany(BecaPregunta::class, 'beca_id');
    }

    public function scopeBuscar($query, ?string $buscar)
    {
        return $query->when($buscar, function ($q) use ($buscar) {
            $q->where('nombre', 'like', "%{$buscar}%")
                ->orWhere('codigo', 'like', "%{$buscar}%");
        });
    }

    public function scopeActivo($query, $activo)
    {
        if ($activo === null || $activo === '') {
            return $query;
        }

        return $query->where('activo', (bool) $activo);
    }
}
