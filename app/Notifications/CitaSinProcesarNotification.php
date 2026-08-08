<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Mail\CitaSinProcesarMail;

class CitaSinProcesarNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $cita;

    public function __construct($cita)
    {
        $this->cita = $cita;
    }

    public function via(object $notifiable): array
    {
        // Only return database. We send the email manually in the command like in other places, or we can use toMail here if configured.
        // Looking at Cita::notificarUsuario, it checks if $notifiable->email exists but CitaController sends mail separately (e.g. Mail::to()->send()). 
        // We can just use 'database' and send Mail in the command. Or use 'mail' here.
        // Wait, Cita::notificarUsuario in Cita.php has a fake notifiable class that returns `$this->email` for routeNotificationForMail.
        // So 'mail' works if we implement `toMail()`.
        
        // Actually, we'll return both database and mail.
        return ['database', 'mail'];
    }
    
    public function toMail(object $notifiable)
    {
        return (new CitaSinProcesarMail($this->cita))
                    ->to($notifiable->email);
    }

    public function toArray(object $notifiable): array
    {
        $fechaStr = $this->cita->fecha ? \Carbon\Carbon::parse($this->cita->fecha)->format('d/m/Y') : '';
        $horaStr = $this->cita->hora ? \Carbon\Carbon::parse($this->cita->hora)->format('g:i A') : '';
        $pacienteName = $this->cita->paciente_nombre ?? ($this->cita->paciente->name ?? 'Desconocido');
        
        return [
            'type_id' => 'cita_sin_procesar',
            'cita_id' => $this->cita->id,
            'paciente_name' => $pacienteName,
            'body' => "Hay citas pendientes por gestionar, del paciente {$pacienteName} por el cual se ha pasado la semana de la gestión de esa precisa cita.",
            'url' => route('historias.index'), 
        ];
    }
}
