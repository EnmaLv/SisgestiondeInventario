<?php

namespace App\Models\salud;

use Illuminate\Support\Facades\DB;

class Notification
{
    /**
     * Obtiene una notificación específica por su ID y el ID del usuario.
     */
    public static function obtenerPorIdYUsuario($id, $userId)
    {
        return DB::table('notifications')
            ->where('id', $id)
            ->where('notifiable_id', $userId)
            ->first();
    }

    /**
     * Obtiene las notificaciones del último mes para un usuario.
     */
    public static function obtenerNotificacionesRecientes($userId)
    {
        $fechaLimite = now()->subMonth();
        $notificaciones = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $userId)
            ->where('created_at', '>=', $fechaLimite)
            ->orderBy('created_at', 'desc')
            ->get();

        // Obtener psicólogos para acortar nombres en las notificaciones
        $psicologos = DB::table('usuario')->where('id_perfil', 1)->get();
        $replacements = [];
        foreach ($psicologos as $psi) {
            $nombres = trim($psi->nombres ?? '');
            $apellidos = trim($psi->apellidos ?? '');
            $fullName = trim($nombres . ' ' . $apellidos);
            if ($fullName) {
                $firstName = explode(' ', $nombres)[0] ?? '';
                $firstLastName = explode(' ', $apellidos)[0] ?? '';
                $shortName = trim($firstName . ' ' . $firstLastName);
                $replacements[$fullName] = $shortName;
            }
        }

        return $notificaciones->map(function ($notif) use ($replacements) {
            $notif->data = is_string($notif->data) ? json_decode($notif->data, true) : $notif->data;
            if (is_array($notif->data)) {
                if (isset($notif->data['body'])) {
                    foreach ($replacements as $full => $short) {
                        $notif->data['body'] = str_replace($full, $short, $notif->data['body']);
                    }
                }
                if (isset($notif->data['psicologo_name'])) {
                    foreach ($replacements as $full => $short) {
                        if ($notif->data['psicologo_name'] === $full) {
                            $notif->data['psicologo_name'] = $short;
                        }
                    }
                }
            }
            return $notif;
        });
    }

    /**
     * Obtiene el conteo de notificaciones no leídas del último mes.
     */
    public static function obtenerConteoNoLeidas($userId)
    {
        $fechaLimite = now()->subMonth();
        return DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $userId)
            ->where('created_at', '>=', $fechaLimite)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Marca una notificación específica como leída.
     */
    public static function marcarComoLeida($id)
    {
        try {
            DB::beginTransaction();
            $res = DB::table('notifications')
                ->where('id', $id)
                ->update(['read_at' => now(), 'updated_at' => now()]);
            DB::commit();
            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Marca todas las notificaciones no leídas de un usuario como leídas.
     */
    public static function marcarTodasComoLeidas($userId)
    {
        try {
            DB::beginTransaction();
            $res = DB::table('notifications')
                ->where('notifiable_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now(), 'updated_at' => now()]);
            DB::commit();
            return $res;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Limpia las notificaciones de mensajes nuevos flotantes enviadas por un usuario específico.
     */
    public static function limpiarNotificacionesMensajes($userId, $targetUserId)
    {
        try {
            DB::beginTransaction();
            $notifications = DB::table('notifications')
                ->where('notifiable_id', $userId)
                ->where('type', 'App\Notifications\NewMessageNotification')
                ->whereNull('read_at')
                ->get();

            foreach ($notifications as $notification) {
                $data = json_decode($notification->data, true);
                if (($data['sender_id'] ?? null) == $targetUserId) {
                    DB::table('notifications')
                        ->where('id', $notification->id)
                        ->update(['read_at' => now(), 'updated_at' => now()]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
