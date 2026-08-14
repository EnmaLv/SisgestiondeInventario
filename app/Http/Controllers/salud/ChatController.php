<?php

namespace App\Http\Controllers\salud;

use App\Http\Controllers\Controller;
use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\salud\Conversation;
use App\Models\salud\Message;
use App\Models\salud\Notification as SaludNotification;

class ChatController extends Controller
{
    private function getContactsData()
    {
        $user = Auth::user();
        $userId = Auth::id();
        $isPsicologo = $user ? $user->tieneRol('psicologo') : false;
        $contacts = $this->obtenerContactosParaChat($userId, $isPsicologo);

        return $contacts->map(function ($contact) use ($userId) {
            $conversation = Conversation::obtenerConversacion($userId, $contact->id);
            $lastMessage = $conversation ? Message::obtenerUltimoMensaje($conversation->id) : null;

            $profilePhoto = null;
            if ($contact->profile_photo_path && file_exists(public_path('storage/' . $contact->profile_photo_path))) {
                $profilePhoto = asset('storage/' . $contact->profile_photo_path);
            }

            $unreadCount = $conversation
                ? Message::where('conversation_id', $conversation->id)
                ->where('sender_id', $contact->id)
                ->whereNull('read_at')
                ->count()
                : 0;

            return [
                'id' => $contact->id,
                'name' => $contact->name,
                'avatar' => strtoupper(substr($contact->name, 0, 2)),
                'profile_photo' => $profilePhoto,
                'lastMessage' => $lastMessage ? $lastMessage->body : 'Inicia una conversación',
                'time' => $lastMessage ? Carbon::parse($lastMessage->created_at)->diffForHumans() : '',
                'last_message_time' => $lastMessage ? Carbon::parse($lastMessage->created_at)->timestamp : 0,
                'unreadCount' => $unreadCount,
                'status' => 'Conectado'
            ];
        })->sortByDesc('last_message_time')->values();
    }

    public function index()
    {
        $contactsData = $this->getContactsData();
        return view('chat.index', compact('contactsData'));
    }

    public function fetchContacts()
    {
        $contactsData = $this->getContactsData();
        return response()->json($contactsData);
    }

    public function ping(Request $request)
    {
        $request->validate([
            'chat_activo_user_id' => 'required|integer'
        ]);

        $user = Auth::user();
        $userId = Auth::id();
        $targetUserId = $request->chat_activo_user_id;

        $isPsicologo = $user ? $user->tieneRol('psicologo') : false;
        $contacts = $this->obtenerContactosParaChat($userId, $isPsicologo);

        $isAuthorized = $contacts->contains('id', $targetUserId);

        if ($isAuthorized) {
            Message::registrarActividadChat($userId, $targetUserId);
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
    }

    public function fetchMessages($targetUserId)
    {
        $userId = Auth::id();
        $conversation = Conversation::obtenerOUCrearConversacion($userId, $targetUserId);

        Message::marcarLeidos($conversation->id, $targetUserId);
        SaludNotification::limpiarNotificacionesMensajes($userId, $targetUserId);
        Message::cancelarNotificacionesPendientes($userId, $targetUserId);

        $rawMessages = Conversation::obtenerMensajes($conversation->id);
        $messages = $rawMessages->map(function ($msg) use ($userId) {
            return [
                'id' => $msg->id,
                'body' => $msg->body,
                'is_mine' => $msg->sender_id === $userId,
                'time' => Carbon::parse($msg->created_at)->format('h:i A')
            ];
        });

        return response()->json([
            'messages' => $messages,
            'conversation_id' => $conversation->id
        ]);
    }

    public function sendMessage(Request $request, $targetUserId)
    {
        $request->validate(['body' => 'required|string']);
        $user = Auth::user();
        $userId = Auth::id();

        if ($user && $user->tieneRol('paciente')) {
            $hasConversation = Conversation::where(function ($q) use ($userId, $targetUserId) {
                $q->where('user_one_id', $userId)->where('user_two_id', $targetUserId);
            })->orWhere(function ($q) use ($userId, $targetUserId) {
                $q->where('user_one_id', $targetUserId)->where('user_two_id', $userId);
            })->exists();

            $hasAppointment = DB::table('citas')
                ->where('user_id', $userId)
                ->where('psicologo_id', $targetUserId)
                ->exists();

            if (!$hasConversation && !$hasAppointment) {
                return response()->json(['error' => 'No tienes permiso para iniciar esta conversación.'], 403);
            }
        }

        $conversation = Conversation::obtenerOUCrearConversacion($userId, $targetUserId);

        $message = Message::crearMensaje($conversation->id, $userId, $request->body);

        try {
            broadcast(new MessageSent($message))->toOthers();
        } catch (\Throwable $e) {
            Log::error("Error broadcasting message: " . $e->getMessage());
        }

        return response()->json([
            'id' => $message->id,
            'body' => $message->body,
            'is_mine' => true,
            'time' => Carbon::parse($message->created_at)->format('h:i A')
        ]);
    }
}
