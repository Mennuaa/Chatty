<?php

namespace App\Http\Controllers;

use App\Http\Resources\ConversationResource;
use App\Models\Conversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sender_id' => 'required|exists:users,id',
            'receiver_id' => 'required|exists:users,id'
        ]);

        $conversation = Conversation::create($request->all());

        return response()->json($conversation, 201);
    }

    public function show(Conversation $conversation)
    {
        return response()->json(ConversationResource::make($conversation));
    }
}
