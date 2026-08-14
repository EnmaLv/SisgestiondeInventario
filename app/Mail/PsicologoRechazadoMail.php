<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PsicologoRechazadoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Actualización sobre su registro - Psico-Guía UPTP')
                    ->view('emails.psicologo_rechazado');
    }
}
