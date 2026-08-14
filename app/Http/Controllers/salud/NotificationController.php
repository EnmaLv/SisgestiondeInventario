<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use App\Models\salud\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function markAsRead($id)
    {
        $userId = Auth::id();
        $notification = Notification::obtenerPorIdYUsuario($id, $userId);
            
        abort_if(!$notification, 404);
        
        Notification::marcarComoLeida($id);

        $data = json_decode($notification->data, true);
        $url = $data['url'] ?? route('dashboard');
        $parsedUrl = parse_url($url);
        if (isset($parsedUrl['path'])) {
            $url = $parsedUrl['path'] . (isset($parsedUrl['query']) ? '?' . $parsedUrl['query'] : '');
        }
        
        return redirect($url);
    }

    public function markAllAsRead(Request $request)
    {
        $userId = Auth::id();
        Notification::marcarTodasComoLeidas($userId);

        return response()->json(['success' => true]);
    }
}
