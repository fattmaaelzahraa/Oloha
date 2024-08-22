<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function createChat(Request $request): JsonResponse
    {
        $chat = Chat::create([
            'user1_id' => Auth::id(),
            'user2_id' => $request->user2_id,
        ]);

        return response()->json($chat, 201);
    }

    public function sendMessage(Request $request, $chatId): JsonResponse
    {
        $message = Message::create([
            'chat_id' => $chatId,
            'sender_id' => Auth::id(),
            'message' => $request->message,
        ]);

        // Broadcasting the message event to enable real-time communication
        event(new \App\Events\MessageSent($message));

        return response()->json($message, 201);
    }

    public function getMessages($chatId): JsonResponse
    {
        $messages = Message::where('chat_id', $chatId)->get();
        return response()->json($messages);
    }
}
