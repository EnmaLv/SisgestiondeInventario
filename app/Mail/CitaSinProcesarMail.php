<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CitaSinProcesarMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $cita;

    public function __construct($cita)
    {
        $this->cita = $cita;
    }

    public function build()
    {
        return $this->subject('Recordatorio: Cita pendiente de procesamiento')
                    ->view('emails.cita-sin-procesar');
    }
}
