<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CitaRequestedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $cita;

    public function __construct(Cita $cita)
    {
        $this->cita = $cita;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type_id' => 'cita_requested',
            'cita_id' => $this->cita->id,
            'paciente_name' => $this->cita->paciente->name,
            'body' => 'Tienes una nueva solicitud de cita de ' . $this->cita->paciente->name . '.',
            'url' => route('agenda.index'), // Psicologo views requested in the calendar or maybe route('citas.show', $cita) - Wait, we can route to agenda for now.
        ];
    }
}
