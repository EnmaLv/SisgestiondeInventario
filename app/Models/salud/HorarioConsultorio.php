<?php

namespace App\Models\salud;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

class HorarioConsultorio extends Model
{
    use HasFactory;

    protected $table = 'horario_consultorios';

    protected $fillable = [
        'consultorio_id',
        'dia',
        'hora_inicio',
        'hora_fin',
        'descripcion',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Días hábiles (lunes a viernes) respetando el horario estudiantil.
     */
    public const DIAS = [
        'lunes'     => 'Lunes',
        'martes'    => 'Martes',
        'miercoles' => 'Miércoles',
        'jueves'    => 'Jueves',
        'viernes'   => 'Viernes',
    ];

    /**
     * Bloques de hora fijos por jornada.
     */
    /**
     * Bloques de hora fijos por jornada (respetando formato 24h para BD).
     */
    public const BLOQUES = [
        'Matutino' => [
            ['inicio' => '07:00', 'fin' => '08:15'],
            ['inicio' => '08:15', 'fin' => '09:00'],
            ['inicio' => '09:20', 'fin' => '10:00'],
            ['inicio' => '10:00', 'fin' => '10:45'],
            ['inicio' => '10:45', 'fin' => '11:30'],
            ['inicio' => '11:30', 'fin' => '12:00'],
        ],
        'Vespertino' => [
            ['inicio' => '13:45', 'fin' => '14:25'],
            ['inicio' => '14:25', 'fin' => '15:05'],
            ['inicio' => '15:05', 'fin' => '15:45'],
            ['inicio' => '15:45', 'fin' => '16:40'],
            ['inicio' => '16:40', 'fin' => '17:20'],
            ['inicio' => '17:20', 'fin' => '18:00'],
        ],
        'Nocturno' => [
            ['inicio' => '18:00', 'fin' => '18:35'],
            ['inicio' => '18:35', 'fin' => '19:10'],
            ['inicio' => '19:10', 'fin' => '19:45'],
            ['inicio' => '19:45', 'fin' => '20:20'],
            ['inicio' => '20:20', 'fin' => '20:55'],
            ['inicio' => '20:55', 'fin' => '21:30'],
        ],
    ];

    /**
     * Accessor para formatear hora_inicio a HH:mm
     */
    protected function horaInicio(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::parse($value)->format('H:i') : null,
        );
    }

    /**
     * Accessor para formatear hora_fin a HH:mm
     */
    protected function horaFin(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? Carbon::parse($value)->format('H:i') : null,
        );
    }

    public function consultorio()
    {
        return $this->belongsTo(Consultorio::class, 'consultorio_id');
    }

    /**
     * Horarios activos de un consultorio, agrupados por día.
     */
    public static function porConsultorioAgrupado(int $consultorioId)
    {
        return self::where('consultorio_id', $consultorioId)
            ->where('activo', true)
            ->get()
            ->groupBy('dia');
    }

    /**
     * Mapa [consultorio_id => ['lunes|07:00|09:00', ...]] usado para
     * bloquear en el formulario de creación los cruces ya ocupados.
     */
    public static function ocupadosPorConsultorio()
    {
        return self::where('activo', true)
            ->get()
            ->groupBy('consultorio_id')
            ->map(function ($items) {
                return $items->map(function ($h) {
                    return $h->dia . '|' . $h->hora_inicio . '|' . $h->hora_fin;
                })->values();
            });
    }

    /**
     * Busca si un conjunto de horarios de un día coincide con un bloque específico.
     */
    public static function buscarRegistroEnBloque($horariosDia, string $horaInicio, string $horaFin)
    {
        if (!$horariosDia) {
            return null;
        }

        return $horariosDia->first(function ($h) use ($horaInicio, $horaFin) {
            $inicio = Carbon::parse($h->hora_inicio)->format('H:i');
            $fin    = Carbon::parse($h->hora_fin)->format('H:i');

            return $inicio === $horaInicio && $fin === $horaFin;
        });
    }
}
