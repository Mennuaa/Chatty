<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'user_id' => 'required|integer|exists:users,id',
            'conversation_id' => 'required|integer|exists:conversations,id',
            'type' => 'in:text,image,video'  // Assuming types are text, image, or video
        ]);

        $message = Message::create($request->all());

        event(new MessageSent($message));

        return response()->json($message, 201);
    }

    public function index(Conversation $conversation)
    {
        $messages = $conversation->messages()->with('user')->get();

        return response()->json($messages);
    }
}
