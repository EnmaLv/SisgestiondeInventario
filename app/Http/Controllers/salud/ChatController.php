<?php

namespace App\Http\Controllers\salud;

use App\Models\Usuario;
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
        /** @var Usuario $user */
        $user = Auth::user();
        $userId = $user->id_usuario;
        $isPsicologo = $user ? $user->tieneRol(['psicologo', 'administrador']) : false;
        $contacts = $user->obtenerContactosParaChat($userId, $isPsicologo);

        \Illuminate\Support\Facades\Log::info('CHAT DEBUG', [
            'userId'       => $userId,
            'isPsicologo'  => $isPsicologo,
            'roles'        => $user->roles->pluck('slug', 'nombre')->toArray(),
            'contacts_raw' => $contacts->toArray(),

            'citas_como_psicologo' => \Illuminate\Support\Facades\DB::table('citas')
                ->where('psicologo_id', $userId)
                ->select('id', 'user_id', 'psicologo_id')
                ->limit(10)
                ->get()->toArray(),

            'citas_como_paciente' => \Illuminate\Support\Facades\DB::table('citas')
                ->where('user_id', $userId)
                ->select('id', 'user_id', 'psicologo_id')
                ->limit(10)
                ->get()->toArray(),
        ]);

        return $contacts->map(function ($contact) use ($userId) {
            $conversation = Conversation::obtenerConversacion($userId, $contact->id_usuario);
            $lastMessage = $conversation ? Message::obtenerUltimoMensaje($conversation->id) : null;

            $unreadCount = $conversation
                ? DB::table('messages')->where('conversation_id', $conversation->id)
                    ->where('sender_id', $contact->id_usuario)
                    ->whereNull('read_at')
                    ->count()
                    : 0;

            return [
                'id'   => $contact->id_usuario,
                'name' => $contact->name,
                'avatar' => strtoupper(substr($contact->name, 0, 2)),
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

        /** @var Usuario $user */
        $user = Auth::user();
        $userId = $user->id_usuario;
        $targetUserId = $request->chat_activo_user_id;

        $isPsicologo = $user ? $user->tieneRol(['psicologo', 'administrador']) : false;
        $contacts = $user->obtenerContactosParaChat($userId, $isPsicologo);

        $isAuthorized = $contacts->contains('id', $targetUserId);

        if ($isAuthorized) {
            Message::registrarActividadChat($userId, $targetUserId);
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'error', 'message' => 'No autorizado'], 403);
    }

    public function fetchMessages($targetUserId)
    {
        /** @var Usuario $user */
        $user = Auth::user();
        $userId = $user->id_usuario;
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

        /** @var Usuario $user */
        $user = Auth::user();
        $userId = $user->id_usuario;

        if ($user && $user->tieneRol(['paciente', 'administrador'])) {
            $hasConversation = DB::table('conversations')->where(function ($q) use ($userId, $targetUserId) {
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
