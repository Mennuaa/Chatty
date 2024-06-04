<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Fetch the last message
        $lastMessage = $this->messages()->latest()->first();
    
        return [
            'id' => $this->id,
            'sender_id' => $this->sender_id,
            'receiver_id' => $this->receiver_id,
            'messages' => MessageResource::collection($this->messages()->paginate(15)),
            'last_message' => $lastMessage ? [
                'sent_at' => $lastMessage->created_at->format('d/m/Y H:i'),  
                'message' => $lastMessage->message,         
                'is_read' => $lastMessage->is_read       
            ] : null,
        ];
    }
    
}
