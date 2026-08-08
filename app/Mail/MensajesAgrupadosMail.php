<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MensajesAgrupadosMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $remitente;
    public $cantidadMensajes;

    /**
     * Create a new message instance.
     */
    public function __construct($remitente, $cantidadMensajes)
    {
        $this->remitente = $remitente;
        $this->cantidadMensajes = $cantidadMensajes;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $roleName = $this->remitente->role === 'psicologo' ? 'El psicólogo' : 'El paciente';
        $sujet = $this->cantidadMensajes > 1 
            ? "{$roleName} {$this->remitente->name} te ha enviado {$this->cantidadMensajes} mensajes nuevos"
            : "{$roleName} {$this->remitente->name} te ha enviado un mensaje nuevo";
            
        return new Envelope(
            subject: $sujet,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.mensajes_agrupados',
            with: [
                'roleName' => $this->remitente->role === 'psicologo' ? 'El psicólogo' : 'El paciente',
                'remitenteName' => $this->remitente->name,
                'cantidad' => $this->cantidadMensajes
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
