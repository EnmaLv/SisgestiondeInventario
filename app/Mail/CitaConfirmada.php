<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;

class CitaConfirmada extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $cita;
    protected $paciente;
    protected $psicologo;

    public function __construct($cita)
    {
        $this->cita = $cita;
        $this->paciente = $cita->paciente;
        $this->psicologo = $cita->psicologo;
    }

    public function build()
    {
        return $this->subject('Confirmación de cita')
            ->view('emails.cita_confirmada')
            ->with([
                'paciente' => optional($this->paciente->persona)->nombre_persona ?: 'Paciente',
                'psicologo' => optional($this->psicologo->persona)->nombre_persona ?: 'Tu psicólogo',
                'fecha' => $this->cita->fecha ? Carbon::parse($this->cita->fecha)->translatedFormat('d \d\e F, Y') : now()->translatedFormat('d \d\e F, Y'),
                'hora' => $this->cita->hora ?: 'Pendiente',
                'bloque' => $this->cita->bloque_propuesto ?? ($this->cita->bloques_sugeridos ?? 'No definido'),
            ]);
    }
}
