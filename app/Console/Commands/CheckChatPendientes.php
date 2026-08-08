<?php

namespace App\Console\Commands;

use \App\Models\salud\Message;
use \App\Mail;
use Illuminate\Console\Command;

class CheckChatPendientes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-chat-pendientes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Revisa y envía notificaciones de chat agrupadas pendientes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $pendientes = Message::obtenerNotificacionesParaEnviar();

        foreach ($pendientes as $notificacion) {
            try {
                $remitente = Message::obtenerRemitente($notificacion->sender_id);
                $destinatario = Message::obtenerRemitente($notificacion->receiver_id);

                if ($remitente && $destinatario) {
                    \Illuminate\Support\Facades\Mail::to($destinatario->email)
                        ->send(new \App\Mail\MensajesAgrupadosMail($remitente, $notificacion->cantidad_mensajes));
                    
                    Message::actualizarEstadoNotificacion($notificacion->id, 'enviada');
                } else {
                    Message::actualizarEstadoNotificacion($notificacion->id, 'cancelada'); // Invalid users
                }
            } catch (\Exception $e) {
                // If there's an error sending the mail, mark as error so it can be retried
                Message::actualizarEstadoNotificacion($notificacion->id, 'error');
                \Illuminate\Support\Facades\Log::error('Error enviando notificación agrupada de chat: ' . $e->getMessage());
            }
        }
    }
}
