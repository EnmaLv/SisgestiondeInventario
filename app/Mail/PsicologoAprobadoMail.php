<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class PsicologoAprobadoMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $usuario;

    public function __construct($usuario)
    {
        $this->usuario = $usuario;
    }

    public function build()
    {
        return $this->subject('Su cuenta ha sido aprobada - Psico-Guía UPTP')
                    ->view('emails.psicologo_aprobado');
    }
}
