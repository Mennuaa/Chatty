<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VideoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id"=> $this->id,
            "video"=> $this->video,
            "description"=> $this->description,
            "user_id"=> $this->user_id,
            "created_at"=> $this->created_at,
            "comments"=> $this->comments,
            "likes"=> $this->likes
        ];
    }
}
