<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserImageResource extends JsonResource
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
            "image" => $this->image,
            'likes' => UserImageLikeResource::collection($this->likes),
            "likes_count" => $this->likes->count(),
            "comments" => UserImageCommentResource::collection($this->comments),
        ];
    }
}
