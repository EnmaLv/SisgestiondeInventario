<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class RespuestaPropuestaNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $cita;
    public $respuesta;

    public function __construct(Cita $cita, $respuesta)
    {
        $this->cita = $cita;
        $this->respuesta = $respuesta; // 'aceptada' o 'rechazada'
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable)
    {
        if ($this->respuesta === 'aceptada') {
            return (new \App\Mail\PropuestaAceptadaMail($this->cita, $this->cita->paciente))
                        ->to($notifiable->email);
        }
        // If rejected, we don't send mail from here, it will be handled by ContrapropuestaRechazadaNotification
    }

    public function toArray(object $notifiable): array
    {
        $pacienteName = trim(($this->cita->paciente->nombres ?? '') . ' ' . ($this->cita->paciente->apellidos ?? '')) ?: 'Un paciente';
        $body = $this->respuesta === 'aceptada' 
            ? "$pacienteName ha aceptado tu propuesta de horario para su cita."
            : "$pacienteName ha rechazado tu propuesta de horario para su cita.";

        return [
            'type_id' => 'respuesta_propuesta_cita',
            'cita_id' => $this->cita->id,
            'body' => $body,
            'url' => route('agenda.index'), // Lleva al módulo de agenda del psicólogo
        ];
    }
}
