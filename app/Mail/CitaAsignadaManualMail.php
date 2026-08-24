<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CitaAsignadaManualMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $cita;
    public $psicologo;

    public function __construct($cita, $psicologo)
    {
        $this->cita = $cita;
        $this->psicologo = $psicologo;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Solicitud de Cita Abierta por tu Psicólogo',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.cita_asignada_manual',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
